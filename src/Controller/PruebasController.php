<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

class PruebasController extends AppController
{
	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
        $this->Auth->allow(['login', 'logout']);
	}

	public function isAuthorized($user)
	{
		return parent::isAuthorized($user);
	}

    public function initialize()
    {
        parent::initialize();
        // load the Captcha component and set its parameter
        $this->loadComponent('CakeCaptcha.Captcha', [
            'captchaConfig' => 'ExampleCaptcha'
        ]);
    }

    public function index()
    {
        
    }


}
