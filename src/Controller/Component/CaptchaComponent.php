<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Event\Event;

class CaptchaComponent extends Component
{
    protected $defaultConfig = [
        'codeLength' => 5,
        'imageWidth' => 200,
        'imageHeight' => 60,
        'fontSize' => 28,
        'lines' => 4,
        'noiseDots' => 80,
        'bgColor' => null,
        'textColor' => null,
        'lineColor' => null,
        'timeout' => 300,
    ];

    protected $config;

    public function initialize(array $config)
    {
        $captchaConfig = Configure::read('Captcha');
        if (!$captchaConfig) {
            $captchaConfig = Configure::load('captcha', 'default', false);
        }

        $preset = 'Default';
        if (!empty($config['preset'])) {
            $preset = $config['preset'];
        }

        $this->config = $this->defaultConfig;
        if ($captchaConfig && isset($captchaConfig[$preset])) {
            $this->config = array_merge($this->config, $captchaConfig[$preset]);
        }
        if (!empty($config['config'])) {
            $this->config = array_merge($this->config, $config['config']);
        }
    }

    public function generate($id = null)
    {
        if (!$id) {
            $id = uniqid('captcha_', true);
        }

        $code = $this->randomCode($this->config['codeLength']);

        $this->request->getSession()->write('Captcha.' . $id, [
            'code' => $code,
            'time' => time(),
            'config' => $this->config,
        ]);

        return $id;
    }

    public function validate($input, $id)
    {
        $session = $this->request->getSession();
        $stored = $session->read('Captcha.' . $id);

        if (!$stored) {
            return false;
        }

        $session->delete('Captcha.' . $id);

        if (time() - $stored['time'] > $stored['config']['timeout']) {
            return false;
        }

        return strtolower(trim($input)) === strtolower(trim($stored['code']));
    }

    public function getConfig($id = null)
    {
        if ($id) {
            $stored = $this->request->getSession()->read('Captcha.' . $id);
            if ($stored) {
                return $stored['config'];
            }
        }
        return $this->config;
    }

    public function getCode($id)
    {
        $stored = $this->request->getSession()->read('Captcha.' . $id);
        if ($stored) {
            return $stored['code'];
        }
        return null;
    }

    protected function randomCode($length)
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789';
        $code = '';
        $max = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[random_int(0, $max)];
        }
        return $code;
    }
}
