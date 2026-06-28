<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
*/
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;
use Cake\Core\Configure;
use Cake\Event\Event;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3/en/controllers.html#the-app-controller
*/
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
    */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');
        $this->loadComponent('Auditorias');
        $this->loadComponent('Auth', [
            'authorize' => ['Controller'],
            'authenticate' => [
                'Form' => [
                    'userModel' => 'Usuarios',
                    'fields' => [
                        'username' => 'username',
                        'password' => 'password'
                    ],
                    'finder' => 'auth'
                ]
            ],
            'loginAction' => ['controller' => 'Usuarios', 'action' => 'login'],
            'authError' => false, //'Ingrese sus datos',
            'loginRedirect' => ['controller' => 'Pages','action' => 'display'],
            'logoutRedirect' => ['controller' => 'Pages','action' => 'display'],
            'unauthorizedRedirect' => $this->referer()
        ]);

        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3/en/controllers/components/security.html
        */
        //$this->loadComponent('Security');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);        
        $userActivo = $this->getRequest()->getSession()->read('Auth.User');
        //$userActivo = $this->Auth->user();
        if ( $userActivo ) 
        {
            $this->viewBuilder()->setLayout("admin");
        } else {
            $this->viewBuilder()->setLayout("default");
        }
        //$this->set('userActivo', $this->Auth->user());
        $this->set(compact('userActivo'));

        $aPermisosId = [];
        $aPermisosNombre = [];
        if (!empty($userActivo['rols'])) {
            foreach ($userActivo['rols'] as $rol) {
                $aPermisosId[] = (int)$rol['id'];
                $aPermisosNombre[] = $rol['nombre'];
            }
        }
        $this->Auth->allow(['display','keepalive']);
        $this->set(compact('aPermisosId', 'aPermisosNombre'));
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\EventInterface $event The beforeRender event.
     * @return \Cake\Http\Response|null|void
    */
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
        if (!array_key_exists('_serialize', $this->viewVars) && in_array($this->response->getType(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }        
        $this->viewBuilder()->setTheme('AdminLTE');
        if ($this->viewBuilder()->getClassName() === null) {
            $this->viewBuilder()->setClassName('AdminLTE.AdminLTE');
        }
    }

    /**
     * 
    */
    public function isAuthorized($user)
    {        
        if( isset( $user['activo'] ) && isset( $user['rols'] ) && $user['activo'] && $this->tienePermiso(1) )
        {
            return true;
        }
        $this->Flash->error(__('Woopsie, you are not authorized to access this area.'));
        return false;
    }

    public function keepalive()
    {
        $this->autoRender = false;
        $this->response = $this->response->withType('application/json');

        if ($this->Auth->user()) {
            $this->getRequest()->getSession()->write('_keepalive', time());
            $this->response = $this->response->withStringBody(json_encode(['status' => 'ok']));
        } else {
            $this->response = $this->response->withStringBody(json_encode(['status' => 'expired']));
        }
        return $this->response;
    }

    public function homepage()
    {
        return $this->redirect($this->Auth->redirectUrl());
    }

    /**
     * Verifica si el usuario autenticado tiene al menos uno de los roles especificados.
     *
     * @param int|string|array $nRol ID del rol, nombre del rol o arreglo mixto de IDs/nombres.
     * @return bool
    */
    public function tienePermiso($nRol)
    {
        $user = $this->getRequest()->getSession()->read('Auth.User');
        if (empty($user) || empty($user['rols'])) {
            return false;
        }

        $roles = (array)$nRol;

        foreach ($user['rols'] as $rol) {
            foreach ($roles as $r) {
                if (is_numeric($r)) {
                    if ((int)$rol['id'] === (int)$r) {
                        return true;
                    }
                } else {
                    if (strcasecmp($rol['nombre'], $r) === 0) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function convertDate($date)
    {
        $parts = explode('/', $date);
        if (count($parts) === 3) 
        {
            if (strlen($parts[2]) === 4) {
                return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
            }
            if (strlen($parts[0]) === 4) {
                return $date;
            }
        }        
        return $date;
    }

    public function generateToken()
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $token = '';
        for ($i = 0; $i < 6; $i++) {
            $token .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $token;
    }

}
