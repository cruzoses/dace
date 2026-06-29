<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\I18n\Time;

/**
 * Usuarios Controller
 *
 * @property \App\Model\Table\UsuariosTable $Usuarios
 *
 * @method \App\Model\Entity\Usuario[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsuariosController extends AppController
{
	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
        $this->Auth->allow(['login', 'logout']);
	}

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Captcha', ['preset' => 'Default']);
    }

	public function isAuthorized($user)
	{
		return parent::isAuthorized($user);
	}
	
    public function login()
    {
        if ( $this->Auth->user() ) 
        {
            return $this->redirect($this->Auth->redirectUrl());
        }

        if ($this->request->is('post')) 
        {
            $user = $this->Auth->identify();
            if ($user) 
            {
                $this->Auth->setUser($user);
                $this->Auditorias->registrar('INGRESA', 'Ingresa al sistema');
                return $this->redirect($this->Auth->redirectUrl());
            } else {
                $this->Flash->error(__('Username or password is incorrect'));
                //$this->Flash->error(__('Usuario o contraseña incorrectos'));
            }
        }
    }

    public function logout()
    {
        $this->Auditorias->registrar('SALE', 'Sale del sistema');
        return $this->redirect($this->Auth->logout());
    }

    public function cambiaclave()
    {
        $userId = $this->Auth->user('id');
        $usuario = $this->Usuarios->get($userId);
        $captchaId = $this->Captcha->generate();

        if ($this->request->is(['patch', 'post', 'put'])) 
        {
            $data = $this->request->getData();
            $hasher = new \Cake\Auth\DefaultPasswordHasher();

            if (!isset($data['captcha_id']) || !$this->Captcha->validate($data['CaptchaCode'], $data['captcha_id'])) {
                $this->Flash->error(__('Código captcha incorrecto.'));
                $captchaId = $this->Captcha->generate();
            } elseif (!$hasher->check($data['password_actual'], $usuario->password)) {
                $this->Flash->error(__('La contraseña actual no es correcta.'));
                $captchaId = $this->Captcha->generate();
            } elseif (empty($data['password_nueva'])) {
                $this->Flash->error(__('La contraseña nueva no puede estar vacía.'));
                $captchaId = $this->Captcha->generate();
            } elseif ($data['password_nueva'] !== $data['password_confirmar']) {
                $this->Flash->error(__('La confirmación no coincide con la contraseña nueva.'));
                $captchaId = $this->Captcha->generate();
            } else {
                $usuario = $this->Usuarios->patchEntity($usuario, ['password' => $data['password_nueva']]);
                if ($this->Usuarios->save($usuario)) {
                    $this->Auditorias->registrar('MODIFICA', 'Cambia la contraseña');
                    $this->Flash->success(__('Contraseña actualizada correctamente.'));
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('No se pudo guardar la contraseña. Intente de nuevo.'));
                $captchaId = $this->Captcha->generate();
            }
        }

        $this->set(compact('usuario', 'captchaId'));
    }

    public function register()
    {
        $user = $this->Users->newEntity($this->request->getData());
        if ($this->Users->save($user)) 
        {
            $this->Auth->setUser($user->toArray());
            return $this->redirect([
                'controller' => 'Pages',
                'action' => 'display'
            ]);
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
    */
    public function index()
    {
        $conditions = $this->Usuarios->formatConditions($this->request->getQueryParams());
        $this->paginate['conditions'] = $conditions;
        $usuarios = $this->paginate($this->Usuarios);
        $filtros = $this->request->getQuery();
        $searchFields = $this->Usuarios->getSearchFields();
        $this->set(compact('usuarios', 'filtros', 'searchFields'));
    }

    /**
     * View method
     *
     * @param string|null $id Usuario id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $usuario = $this->Usuarios->get($id, [
            'contain' => ['Rols', 'Auditorias', 'Docentes', 'Empleados', 'Estudiantes', 'Noticias'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Usuarios ' . json_encode($usuario->toArray()));
        $this->set('usuario', $usuario);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $usuario = $this->Usuarios->newEntity();
        if ($this->request->is('post')) 
        {
            $aDatos = $this->request->getData();
            // Convertir la fecha antes de hacer patchEntity
            if ( !empty( $aDatos['fecha_nacimiento'] ) ) 
            {
                $fecha = str_replace('/', '-', $aDatos['fecha_nacimiento']);
                $aDatos['fecha_nacimiento'] = Time::createFromFormat('d-m-Y', $fecha)->format('Y-m-d');
            }
            $usuario = $this->Usuarios->patchEntity($usuario, $aDatos);
            //$usuario = $this->Usuarios->patchEntity($usuario, $this->request->getData());
            if ($this->Usuarios->save($usuario)) 
            {
                $this->Flash->success(__('The {0} has been saved.', 'Usuario'));
                //$this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Usuarios ' . json_encode($aDatos));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Usuario'));
        }
        $rols = $this->Usuarios->Rols->find('list', ['limit' => 200]);
        $aGeneros = \Cake\Core\Configure::read('aGeneros');
        $this->set(compact('usuario', 'rols','aGeneros'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Usuario id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $usuario = $this->Usuarios->get($id, [
            'contain' => ['Rols']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) 
        {
            $aDatos = $this->request->getData();
            if (!empty($aDatos['fecha_nacimiento'])) 
            {
                $fecha = str_replace('/', '-', $aDatos['fecha_nacimiento']);
                $aDatos['fecha_nacimiento'] = Time::createFromFormat('d-m-Y', $fecha)->format('Y-m-d');
            }
            $usuario = $this->Usuarios->patchEntity($usuario, $aDatos);
            if ($this->Usuarios->save($usuario)) 
            {
                $this->Flash->success(__('The {0} has been saved.', 'Usuario'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Usuarios ' . json_encode($aDatos));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Usuario'));
        }
        $rols = $this->Usuarios->Rols->find('list', ['limit' => 200]);
        $aGeneros = \Cake\Core\Configure::read('aGeneros');
        $this->set(compact('usuario', 'rols','aGeneros'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Usuario id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $usuario = $this->Usuarios->get($id);
        if ($this->Usuarios->delete($usuario)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Usuario'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Usuarios ' . json_encode($usuario->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Usuario'));
        }

        return $this->redirect(['action' => 'index']);
    }


    /**
     * Edit method
     *
     * @param string|null $id Usuario id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function perfil()
    {
        $userId = $this->Auth->user('id');
        $usuario = $this->Usuarios->get($userId, [
            'contain' => ['Rols']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) 
        {
            /*
            $aDatos = $this->request->getData();
            if (!empty($aDatos['fecha_nacimiento'])) 
            {
                $fecha = str_replace('/', '-', $aDatos['fecha_nacimiento']);
                $aDatos['fecha_nacimiento'] = Time::createFromFormat('d-m-Y', $fecha)->format('Y-m-d');
            }
            $usuario = $this->Usuarios->patchEntity($usuario, $aDatos);
            if ($this->Usuarios->save($usuario)) 
            {
                $this->Flash->success(__('The {0} has been saved.', 'Usuario'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Usuarios ' . json_encode($aDatos));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Usuario'));
            */
        }
        $rols = $this->Usuarios->Rols->find('list', ['limit' => 200]);
        $aGeneros = \Cake\Core\Configure::read('aGeneros');
        $this->set(compact('usuario', 'rols','aGeneros'));
    }

}
