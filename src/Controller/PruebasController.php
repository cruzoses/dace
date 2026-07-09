<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

class PruebasController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['login', 'logout', 'index']);
    }

    public function isAuthorized($user = null)
    {
        return parent::isAuthorized($user);
    }

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Captcha', ['preset' => 'Default']);
    }

    public function index()
    {
        $this->set('captchaId', $this->Captcha->generate());

        if ($this->request->is('post')) {
            $code = $this->request->getData('CaptchaCode');
            if ($this->Captcha->validate($code, $this->request->getData('captcha_id'))) {
                $this->Flash->success('Captcha correcto');
            } else {
                $this->Flash->error('Captcha incorrecto');
            }
            $this->set('captchaId', $this->Captcha->generate());
        }
    }
}
