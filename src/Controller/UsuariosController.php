<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\Core\Configure;

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
        $this->Auth->allow(['login', 'logout', 'nuevaclave', 'registrodocente', 'registroestudiante']);
	}

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Captcha', ['preset' => 'Default']);
    }

	public function isAuthorized($user = null)
	{
		if (isset($user['activo']) && isset($user['rols']) && $user['activo'] && $this->tienePermiso([1,2])) {
			return true;
		}
		$this->Flash->error(__('No tiene permisos para acceder a esta sección.'));
		return false;
	}

	private function _getUserMinRoleId()
	{
		$user = $this->Auth->user();
		$roles = $user['rols'] ?? [];
		$ids = array_map(function ($r) {
			return (int)$r['id'];
		}, $roles);
		return !empty($ids) ? min($ids) : null;
	}

	private function _canAccessUser($targetUserId = null)
	{
		$userLevel = $this->_getUserMinRoleId();
		if ($userLevel === null) {
			return false;
		}
		if ($userLevel == 1) {
			return true;
		}
		$targetUser = $this->Usuarios->get($targetUserId, ['contain' => ['Rols']]);
		$targetRoles = $targetUser->rols;
		$targetIds = [];
		foreach ($targetRoles as $r) {
			$targetIds[] = (int)$r->id;
		}
		$targetMinRole = !empty($targetIds) ? min($targetIds) : null;
		return $targetMinRole !== null && $targetMinRole >= $userLevel;
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

    public function nuevaclave()
    {
        if ($this->Auth->user()) {
            return $this->redirect($this->Auth->redirectUrl());
        }

        if ($this->request->is('post')) {
            $email = $this->request->getData('email');

            if (empty($email)) {
                $this->Flash->error(__('Debe ingresar su correo electrónico.'));
                return;
            }

            $usuario = $this->Usuarios->findByEmail($email)->first();

            if (!$usuario) {
                $this->Flash->error(__('No existe un usuario con ese correo electrónico.'));
                return;
            }

            if (!$usuario->activo) {
                $this->Flash->error(__('El usuario está inactivo. Contacte al administrador.'));
                return;
            }

            $nuevaClave = substr(bin2hex(random_bytes(8)), 0, 10);

            $usuario = $this->Usuarios->patchEntity($usuario, ['password' => $nuevaClave]);

            if ($this->Usuarios->save($usuario)) {
                $this->Auditorias->registrar('RECUPERA', 'Recupera contraseña para usuario ' . $usuario->username);

                $profile = Configure::read('App.emailProfile', 'default');
                $emailObj = new Email($profile);
                try {
                    $emailObj->setTo($usuario->email)
                        ->emailFormat('both')
                        ->setSubject('Recuperación de contraseña - SACE UPTBAL')
                        ->setTemplate('usuario_nueva_clave')
                        ->setViewVars([
                            'usuario' => $usuario,
                            'nuevaClave' => $nuevaClave,
                        ])
                        ->send();

                    $this->Flash->success(__('Se ha enviado un correo con sus datos y una nueva contraseña.'));
                } catch (\Exception $e) {
                    $this->log('Error al enviar email de recuperación a ' . $usuario->email . ': ' . $e->getMessage(), 'error');
                    $this->Flash->success(__('Se ha actualizado su contraseña. Por favor, revise su correo electrónico.'));
                }
            } else {
                $this->Flash->error(__('No se pudo actualizar la contraseña. Intente de nuevo.'));
            }

            return $this->redirect(['action' => 'nuevaclave']);
        }
    }

    public function registrodocente()
    {
        if ($this->Auth->user()) {
            return $this->redirect($this->Auth->redirectUrl());
        }

        $usuario = $this->Usuarios->newEntity();
        $captchaId = $this->Captcha->generate();
        $rolDocente = $this->Usuarios->Rols->findByNombre('DOCENTE')->first();
        $rolNombre = $rolDocente ? $rolDocente->nombre : 'DOCENTE';

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            if (!isset($data['captcha_id']) || !$this->Captcha->validate($data['CaptchaCode'], $data['captcha_id'])) {
                $this->Flash->error(__('Código captcha incorrecto.'));
                $captchaId = $this->Captcha->generate();
                $this->set(compact('usuario', 'captchaId', 'rolDocente', 'rolNombre'));
                return;
            }

            if (empty($data['password_confirmar']) || $data['password'] !== $data['password_confirmar']) {
                $this->Flash->error(__('Las contraseñas no coinciden.'));
                $captchaId = $this->Captcha->generate();
                $this->set(compact('usuario', 'captchaId', 'rolDocente', 'rolNombre'));
                return;
            }

            $cedula = $data['cedula'] ?? null;
            $token = $data['token'] ?? null;

            if (empty($cedula) || empty($token)) {
                $this->Flash->error(__('Debe ingresar su cédula y clave de registro.'));
                $captchaId = $this->Captcha->generate();
                $this->set(compact('usuario', 'captchaId', 'rolDocente', 'rolNombre'));
                return;
            }

            $docente = $this->Usuarios->Docentes->find()
                ->where(['cedula' => $cedula, 'usuario_id IS' => null, 'activo' => 1])
                ->first();

            if (!$docente) {
                $this->Flash->error(__('La cédula ingresada no está registrada como docente o ya tiene un usuario asociado.'));
                $captchaId = $this->Captcha->generate();
                $this->set(compact('usuario', 'captchaId', 'rolDocente', 'rolNombre'));
                return;
            }

            if ($docente->token !== $token) {
                $this->Flash->error(__('La clave de registro no es correcta.'));
                $captchaId = $this->Captcha->generate();
                $this->set(compact('usuario', 'captchaId', 'rolDocente', 'rolNombre'));
                return;
            }

            unset($data['token'], $data['password_confirmar'], $data['CaptchaCode'], $data['captcha_id'], $data['rols']);

            $usuario = $this->Usuarios->patchEntity($usuario, $data);

            if ($this->Usuarios->save($usuario)) {
                if ($rolDocente) {
                    $this->Usuarios->Rols->link($usuario, [$rolDocente]);
                }

                $docente->usuario_id = $usuario->id;
                $this->Usuarios->Docentes->save($docente);

                $this->Auditorias->registrar('REGISTRA', 'Registro docente autónomo para usuario ' . $usuario->username);
                $this->Flash->success(__('Registro exitoso. Ya puede ingresar al sistema.'));
                return $this->redirect(['action' => 'login']);
            }

            $this->Flash->error(__('No se pudo completar el registro. Intente de nuevo.'));
            $captchaId = $this->Captcha->generate();
        }

        $aGeneros = \Cake\Core\Configure::read('aGeneros');
        $this->set(compact('usuario', 'captchaId', 'rolDocente', 'rolNombre', 'aGeneros'));
    }

    public function registroestudiante()
    {
        if ($this->Auth->user()) {
            return $this->redirect($this->Auth->redirectUrl());
        }

        $usuario = $this->Usuarios->newEntity();
        $captchaId = $this->Captcha->generate();
        $rolEstudiante = $this->Usuarios->Rols->findByNombre('ESTUDIANTE')->first();
        $rolNombre = $rolEstudiante ? $rolEstudiante->nombre : 'ESTUDIANTE';

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            if (!isset($data['captcha_id']) || !$this->Captcha->validate($data['CaptchaCode'], $data['captcha_id'])) {
                $this->Flash->error(__('Código captcha incorrecto.'));
                $captchaId = $this->Captcha->generate();
                $this->set(compact('usuario', 'captchaId', 'rolEstudiante', 'rolNombre'));
                return;
            }

            if (empty($data['password_confirmar']) || $data['password'] !== $data['password_confirmar']) {
                $this->Flash->error(__('Las contraseñas no coinciden.'));
                $captchaId = $this->Captcha->generate();
                $this->set(compact('usuario', 'captchaId', 'rolEstudiante', 'rolNombre'));
                return;
            }

            $cedula = $data['cedula'] ?? null;
            $token = $data['token'] ?? null;
            $expediente = $data['expediente'] ?? null;

            if (empty($cedula) || empty($token) || empty($expediente)) {
                $this->Flash->error(__('Debe ingresar su cédula, número de expediente y clave de registro.'));
                $captchaId = $this->Captcha->generate();
                $this->set(compact('usuario', 'captchaId', 'rolEstudiante', 'rolNombre'));
                return;
            }

            $estudiante = $this->Usuarios->Estudiantes->find()
                ->where(['cedula' => $cedula, 'usuario_id IS' => null, 'activo' => 1])
                ->first();

            if (!$estudiante) {
                $this->Flash->error(__('La cédula ingresada no está registrada como estudiante o ya tiene un usuario asociado.'));
                $captchaId = $this->Captcha->generate();
                $this->set(compact('usuario', 'captchaId', 'rolEstudiante', 'rolNombre'));
                return;
            }

            if ($estudiante->expediente !== $expediente || $estudiante->token !== $token) {
                $this->Flash->error(__('El número de expediente o la clave de registro no son correctos.'));
                $captchaId = $this->Captcha->generate();
                $this->set(compact('usuario', 'captchaId', 'rolEstudiante', 'rolNombre'));
                return;
            }

            unset($data['token'], $data['expediente'], $data['password_confirmar'], $data['CaptchaCode'], $data['captcha_id'], $data['rols']);

            $usuario = $this->Usuarios->patchEntity($usuario, $data);

            if ($this->Usuarios->save($usuario)) {
                if ($rolEstudiante) {
                    $this->Usuarios->Rols->link($usuario, [$rolEstudiante]);
                }

                $estudiante->usuario_id = $usuario->id;
                $this->Usuarios->Estudiantes->save($estudiante);

                $this->Auditorias->registrar('REGISTRA', 'Registro estudiante autónomo para usuario ' . $usuario->username);
                $this->Flash->success(__('Registro exitoso. Ya puede ingresar al sistema.'));
                return $this->redirect(['action' => 'login']);
            }

            $this->Flash->error(__('No se pudo completar el registro. Intente de nuevo.'));
            $captchaId = $this->Captcha->generate();
        }

        $aGeneros = \Cake\Core\Configure::read('aGeneros');
        $this->set(compact('usuario', 'captchaId', 'rolEstudiante', 'rolNombre', 'aGeneros'));
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
        $userLevel = $this->_getUserMinRoleId();
        $conditions = $this->Usuarios->formatConditions($this->request->getQueryParams());

        if ($userLevel !== null && $userLevel > 1) {
            $subquery = $this->Usuarios->Rols->find()
                ->select(['usuario_id' => 'RolsUsuarios.usuario_id'])
                ->innerJoin(
                    ['RolsUsuarios' => 'rols_usuarios'],
                    ['RolsUsuarios.rol_id = Rols.id']
                )
                ->group('RolsUsuarios.usuario_id')
                ->having(['MIN(Rols.id) >=' => $userLevel]);

            $conditions[] = ['Usuarios.id IN' => $subquery];
        }

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
        if (!$this->_canAccessUser($id)) {
            $this->Flash->error(__('No tiene permisos para ver este usuario.'));
            return $this->redirect(['action' => 'index']);
        }

        $usuario = $this->Usuarios->get($id, [
            'contain' => ['Rols', 'Docentes', 'Empleados', 'Estudiantes', 'Noticias'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Usuarios - ID: ' . $usuario->id . ', Username: ' . $usuario->username);

        $this->paginate = [
            'limit' => 20,
            'order' => [
                'Auditorias.id' => 'desc'
            ],
            'conditions' => [
                'Auditorias.usuario_id' => $usuario->id
            ]
        ];

        $auditorias = $this->paginate(\Cake\ORM\TableRegistry::getTableLocator()->get('Auditorias'));

        $this->set(compact('usuario', 'auditorias'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $usuario = $this->Usuarios->newEntity();
        $userLevel = $this->_getUserMinRoleId();
        if ($this->request->is('post')) 
        {
            $aDatos = $this->request->getData();
            $usuario = $this->Usuarios->patchEntity($usuario, $aDatos);
            if ($this->Usuarios->save($usuario)) 
            {
                $this->Flash->success(__('The {0} has been saved.', 'Usuario'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Usuario'));
        }
        $rols = $this->Usuarios->Rols->find('list', ['limit' => 200])
            ->where(['Rols.id >=' => $userLevel]);
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
        if (!$this->_canAccessUser($id)) {
            $this->Flash->error(__('No tiene permisos para editar este usuario.'));
            return $this->redirect(['action' => 'index']);
        }

        $usuario = $this->Usuarios->get($id, [
            'contain' => ['Rols']
        ]);
        $userLevel = $this->_getUserMinRoleId();
        if ($this->request->is(['patch', 'post', 'put'])) 
        {
            $aDatos = $this->request->getData();
            $usuario = $this->Usuarios->patchEntity($usuario, $aDatos);
            if ($this->Usuarios->save($usuario)) 
            {
                $this->Flash->success(__('The {0} has been saved.', 'Usuario'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Usuarios ' . json_encode($aDatos));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Usuario'));
        }
        $rols = $this->Usuarios->Rols->find('list', ['limit' => 200])
            ->where(['Rols.id >=' => $userLevel]);
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
        if (!$this->_canAccessUser($id)) {
            $this->Flash->error(__('No tiene permisos para eliminar este usuario.'));
            return $this->redirect(['action' => 'index']);
        }
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
            $aDatos = $this->request->getData();
            $foto = $this->request->getData('foto');

            if (!empty($foto['name']) && $foto['error'] === UPLOAD_ERR_OK) {
                if ($foto['size'] > 256) {
                    $this->Flash->error(__('La foto no debe superar los 256 bytes.'));
                    $this->set(compact('usuario'));
                    return;
                }
                $ext = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                if (!in_array($ext, $allowed)) {
                    $this->Flash->error(__('Formato de foto no válido. Use jpg, jpeg, png o gif.'));
                    $this->set(compact('usuario'));
                    return;
                }
                $fotoDir = WWW_ROOT . 'img' . DS . 'fotos';
                if (!is_dir($fotoDir)) {
                    @mkdir($fotoDir, 0777, true);
                }
                $filename = 'foto' . $userId . '.' . $ext;
                if (move_uploaded_file($foto['tmp_name'], $fotoDir . DS . $filename)) {
                    $aDatos['foto'] = $filename;
                } else {
                    $this->Flash->error(__('No se pudo guardar la foto. Intente de nuevo.'));
                    $this->set(compact('usuario'));
                    return;
                }
            } else {
                unset($aDatos['foto']);
            }

            $usuario = $this->Usuarios->patchEntity($usuario, $aDatos);
            if ($this->Usuarios->save($usuario)) 
            {
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA PERFIL Usuarios ' . json_encode($aDatos));
                $this->Flash->success(__('Perfil actualizado correctamente.'));
                return $this->redirect(['action' => 'perfil']);
            }
            $this->Flash->error(__('No se pudo guardar el perfil. Intente de nuevo.'));
        }
        $rols = $this->Usuarios->Rols->find('list', ['limit' => 200]);
        $aGeneros = Configure::read('aGeneros');
        $this->set(compact('usuario', 'rols','aGeneros'));
    }

}
