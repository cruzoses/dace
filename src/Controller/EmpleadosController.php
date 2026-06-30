<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Mailer\Email;

/**
 * Empleados Controller
 *
 * @property \App\Model\Table\EmpleadosTable $Empleados
 *
 * @method \App\Model\Entity\Empleado[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EmpleadosController extends AppController
{

    /**
     * 
    */
	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
	}

    /**
     * 
    */
	public function isAuthorized($user)
	{
		return parent::isAuthorized($user);
	}
	
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
    */
    public function index()
    {
        $conditions = $this->Empleados->formatConditions($this->request->getQueryParams());
        $this->paginate = [
            'contain' => ['Usuarios'],
            'conditions' => $conditions,
        ];
        $empleados = $this->paginate($this->Empleados);
        $filtros = $this->request->getQuery();
        $searchFields = $this->Empleados->getSearchFields();
        $this->set(compact('empleados', 'filtros', 'searchFields'));
    }

    /**
     * View method
     *
     * @param string|null $id Empleado id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $empleado = $this->Empleados->get($id, [
            'contain' => ['Usuarios'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Empleados ' . json_encode($empleado->toArray()));

        $this->set('empleado', $empleado);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $empleado = $this->Empleados->newEntity();
        if ($this->request->is('post')) {
            $empleado = $this->Empleados->patchEntity($empleado, $this->request->getData());
            if ($this->Empleados->save($empleado)) {
                $this->Flash->success(__('The {0} has been saved.', 'Empleado'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Empleados ' . json_encode($this->request->getData()));

                if ($this->enviarTokenPorEmail($empleado)) {
                    $this->Flash->success(__('Token enviado al correo del empleado.'));
                } else {
                    $this->Flash->warning(__('No se pudo enviar el email. Token: {0}', $empleado->token));
                }

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Empleado'));
        }
        $usuarios = $this->Empleados->Usuarios->find('list', ['limit' => 200]);
        $aGeneros = Configure::read('aGeneros');
        $sToken = $this->generateToken();
        $this->set(compact('empleado', 'usuarios','aGeneros','sToken'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Empleado id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $empleado = $this->Empleados->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $empleado = $this->Empleados->patchEntity($empleado, $this->request->getData());
            if ($this->Empleados->save($empleado)) {
                $this->Flash->success(__('The {0} has been saved.', 'Empleado'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Empleados ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Empleado'));
        }
        $usuarios = $this->Empleados->Usuarios->find('list', ['limit' => 200]);
        $aGeneros = Configure::read('aGeneros');
        $this->set(compact('empleado', 'usuarios','aGeneros'));
    }


    /**
     * Envía el token de registro por correo electrónico al empleado.
     *
     * @param \App\Model\Entity\Empleado $empleado
     * @return bool True si se envió correctamente, false en caso contrario.
     */
    private function enviarTokenPorEmail($empleado)
    {
        $profile = Configure::read('App.emailProfile', 'default');
        $email = new Email($profile);
        try {
            $email->setTo($empleado->email)
                ->setSubject('Token de registro - Sistema DACE')
                ->setTemplate('empleado_token')
                ->setViewVars(['empleado' => $empleado])
                /*
                ->attachments([
                    'logo.png' => [
                        'file' => ROOT .DS. 'webroot' .DS. 'img' .DS. 'logos' .DS. 'logouptbal.png', 
                        'mimetype' => 'image/png',
                        'contentId' => 'unique-image-id'
                    ]
                ])
                */
                ->send();
            return true;
        } catch (\Exception $e) {
            $this->log('Error al enviar email a ' . $empleado->email . ': ' . $e->getMessage(), 'error');
            return false;
        }
    }

    /**
     * Reenvía el token de registro por correo electrónico al empleado.
     *
     * @param string|null $id Empleado id.
     * @return \Cake\Http\Response|null
     */
    public function reenviarToken($id = null)
    {
        $this->request->allowMethod(['post']);
        $empleado = $this->Empleados->get($id);

        if ($this->enviarTokenPorEmail($empleado)) {
            $this->Flash->success(__('Token reenviado correctamente a {0}.', $empleado->email));
        } else {
            $this->Flash->error(__('No se pudo reenviar el token a {0}. Intente de nuevo.', $empleado->email));
        }

        return $this->redirect(['action' => 'view', $id]);
    }

    /**
     * Delete method
     *
     * @param string|null $id Empleado id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $empleado = $this->Empleados->get($id);
        if ($this->Empleados->delete($empleado)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Empleado'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Empleados ' . json_encode($empleado->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Empleado'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
