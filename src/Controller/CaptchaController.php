<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Lib\SecurimageCaptcha;
use Cake\Event\Event;
use Cake\Core\Configure;

class CaptchaController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['image', 'reload']);
        $this->autoRender = false;
    }

    public function reload()
    {
        $defaults = [
            'captcha_type'  => 'string',
            'code_length'   => 6,
            'charset'       => 'abcdefghijkmnopqrstuvwxzyABCDEFGHJKLMNPQRSTUVWXZY23456789',
            'case_sensitive' => false,
            'use_wordlist'  => false,
            'wordlist_file' => null,
            'timeout'       => 900,
        ];

        $config = Configure::read('captcha.Default');
        if (!$config) {
            Configure::load('captcha', 'default');
            $config = Configure::read('captcha.Default');
        }
        if ($config) {
            $config = array_merge($defaults, $config);
        } else {
            $config = $defaults;
        }

        $code         = '';
        $code_display = '';

        switch ($config['captcha_type']) {
            case 'math':
                do {
                    $signs = ['+', '-', 'x'];
                    $left  = mt_rand(1, 10);
                    $right = mt_rand(1, 5);
                    $sign  = $signs[mt_rand(0, 2)];
                    switch ($sign) {
                        case 'x': $c = $left * $right; break;
                        case '-': $c = $left - $right; break;
                        default:  $c = $left + $right; break;
                    }
                } while ($c <= 0);
                $code         = (string)$c;
                $code_display = "$left $sign $right";
                break;

            case 'words':
                $wordlist = $config['wordlist_file'] ?: (WWW_ROOT . 'files' . DIRECTORY_SEPARATOR . 'words.txt');
                $words = $this->readCodeFromFile($wordlist, 2);
                if ($words === false) {
                    $chars       = $config['charset'] ?: 'abcdefghijkmnopqrstuvwxzyABCDEFGHJKLMNPQRSTUVWXZY23456789';
                    $code        = $this->quickRandom($chars, $config['code_length']);
                    $code_display = $code;
                } else {
                    $code         = implode(' ', $words);
                    $code_display = $code;
                }
                break;

            default:
                $chars = $config['charset'] ?: 'abcdefghijkmnopqrstuvwxzyABCDEFGHJKLMNPQRSTUVWXZY23456789';
                if ($config['use_wordlist'] && !empty($config['wordlist_file']) && is_readable($config['wordlist_file'])) {
                    $word = $this->readCodeFromFile($config['wordlist_file'], 1);
                    if ($word !== false) {
                        $code = $word;
                    }
                }
                if ($code == '') {
                    $code = $this->quickRandom($chars, $config['code_length']);
                }
                $code_display = $code;
                $code         = ($config['case_sensitive']) ? $code : strtolower($code);
                break;
        }

        $id = 'captcha_' . bin2hex(random_bytes(16));

        $this->request->getSession()->write('Captcha.' . $id, [
            'code'         => $code,
            'code_display' => $code_display,
            'time'         => time(),
            'config'       => $config,
        ]);

        $url = \Cake\Routing\Router::url(['controller' => 'Captcha', 'action' => 'image', $id]);
        $this->response = $this->response->withType('application/json')
            ->withStringBody(json_encode(['id' => $id, 'url' => $url]));
        return $this->response;
    }

    public function image($id = null)
    {
        ob_start();

        $response = null;

        if ($id) {
            $session = $this->request->getSession();
            $stored  = $session->read('Captcha.' . $id);

            if ($stored && isset($stored['code'])) {
                $code         = $stored['code'];
                $code_display = isset($stored['code_display']) ? $stored['code_display'] : $code;
                $config       = $stored['config'];
            } else {
                $id = $this->generateOnTheFly($id);
                $session = $this->request->getSession();
                $stored  = $session->read('Captcha.' . $id);
                if ($stored) {
                    $code         = $stored['code'];
                    $code_display = isset($stored['code_display']) ? $stored['code_display'] : $code;
                    $config       = $stored['config'];
                } else {
                    $response = $this->generateEmptyImage();
                }
            }
        } else {
            $response = $this->generateEmptyImage();
        }

        if (!$response) {
            $captcha = new SecurimageCaptcha();

            $captcha->image_width         = isset($config['image_width'])  ? $config['image_width']  : 215;
            $captcha->image_height        = isset($config['image_height']) ? $config['image_height'] : 80;
            $captcha->font_ratio          = isset($config['font_ratio'])   ? $config['font_ratio']   : 0.4;
            $captcha->image_bg_color      = isset($config['image_bg_color']) ? $config['image_bg_color'] : '#ffffff';
            $captcha->text_color          = isset($config['text_color'])     ? $config['text_color']     : '#707070';
            $captcha->line_color          = isset($config['line_color'])     ? $config['line_color']     : '#707070';
            $captcha->noise_color         = isset($config['noise_color'])    ? $config['noise_color']    : '#707070';
            $captcha->num_lines           = isset($config['num_lines'])      ? $config['num_lines']      : 5;
            $captcha->noise_level         = isset($config['noise_level'])    ? $config['noise_level']    : 2;
            $captcha->perturbation        = isset($config['perturbation'])   ? $config['perturbation']   : 0.85;
            $captcha->use_random_spaces   = isset($config['use_random_spaces'])   ? $config['use_random_spaces']   : false;
            $captcha->use_text_angles     = isset($config['use_text_angles'])     ? $config['use_text_angles']     : false;
            $captcha->use_random_baseline = isset($config['use_random_baseline']) ? $config['use_random_baseline'] : false;
            $captcha->use_random_boxes    = isset($config['use_random_boxes'])    ? $config['use_random_boxes']    : false;
            $captcha->background_directory = isset($config['background_directory']) ? $config['background_directory'] : null;
            $captcha->image_signature     = isset($config['image_signature']) ? $config['image_signature'] : '';
            $captcha->signature_color     = isset($config['signature_color']) ? $config['signature_color'] : '#707070';

            if (!empty($config['ttf_files'])) {
                $captcha->ttf_file = $config['ttf_files'];
            } else {
                $captcha->ttf_file = [
                    WWW_ROOT . 'fonts' . DIRECTORY_SEPARATOR . 'BDLatn1.ttf',
                    WWW_ROOT . 'fonts' . DIRECTORY_SEPARATOR . 'BDLatn2.ttf',
                    WWW_ROOT . 'fonts' . DIRECTORY_SEPARATOR . 'BDLatn3.ttf',
                    WWW_ROOT . 'fonts' . DIRECTORY_SEPARATOR . 'AHGBold.ttf',
                ];
            }

            $imageData = $captcha->generate($code, $code_display);
            $response = $this->response->withType('png')
                ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate')
                ->withHeader('Pragma', 'no-cache')
                ->withHeader('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT')
                ->withStringBody($imageData);
        }

        ob_clean();
        return $response;
    }

    protected function generateOnTheFly($id)
    {
        $defaults = [
            'captcha_type'  => 'string',
            'code_length'   => 6,
            'charset'       => 'abcdefghijkmnopqrstuvwxzyABCDEFGHJKLMNPQRSTUVWXZY23456789',
            'case_sensitive' => false,
            'use_wordlist'  => false,
            'wordlist_file' => WWW_ROOT . 'files' . DIRECTORY_SEPARATOR . 'words.txt',
            'timeout'       => 900,
        ];
        $config = Configure::read('captcha.Default');
        if (!$config) {
            Configure::load('captcha', 'default');
            $config = Configure::read('captcha.Default');
        }
        if ($config) {
            $config = array_merge($defaults, $config);
        } else {
            $config = $defaults;
        }

        $code         = '';
        $code_display = '';

        switch ($config['captcha_type']) {
            case 'math':
                do {
                    $signs = ['+', '-', 'x'];
                    $left  = mt_rand(1, 10);
                    $right = mt_rand(1, 5);
                    $sign  = $signs[mt_rand(0, 2)];
                    switch ($sign) {
                        case 'x': $c = $left * $right; break;
                        case '-': $c = $left - $right; break;
                        default:  $c = $left + $right; break;
                    }
                } while ($c <= 0);
                $code         = (string)$c;
                $code_display = "$left $sign $right";
                break;

            case 'words':
                $wordlist = $config['wordlist_file'] ?: (WWW_ROOT . 'files' . DIRECTORY_SEPARATOR . 'words.txt');
                $words = $this->readCodeFromFile($wordlist, 2);
                if ($words === false) {
                    $chars       = $config['charset'] ?: 'abcdefghijkmnopqrstuvwxzyABCDEFGHJKLMNPQRSTUVWXZY23456789';
                    $code        = $this->quickRandom($chars, $config['code_length']);
                    $code_display = $code;
                } else {
                    $code         = implode(' ', $words);
                    $code_display = $code;
                }
                break;

            default:
                $chars = $config['charset'] ?: 'abcdefghijkmnopqrstuvwxzyABCDEFGHJKLMNPQRSTUVWXZY23456789';
                if ($config['use_wordlist'] && !empty($config['wordlist_file']) && is_readable($config['wordlist_file'])) {
                    $word = $this->readCodeFromFile($config['wordlist_file'], 1);
                    if ($word !== false) {
                        $code = $word;
                    }
                }
                if ($code == '') {
                    $code = $this->quickRandom($chars, $config['code_length']);
                }
                $code_display = $code;
                $code         = ($config['case_sensitive']) ? $code : strtolower($code);
                break;
        }

        $this->request->getSession()->write('Captcha.' . $id, [
            'code'         => $code,
            'code_display' => $code_display,
            'time'         => time(),
            'config'       => $config,
        ]);

        return $id;
    }

    protected function generateEmptyImage()
    {
        $img = imagecreatetruecolor(215, 80);
        $bg  = imagecolorallocate($img, 240, 240, 240);
        imagefill($img, 0, 0, $bg);
        $text = imagecolorallocate($img, 200, 200, 200);

        $font = WWW_ROOT . 'fonts' . DIRECTORY_SEPARATOR . 'AHGBold.ttf';
        if (file_exists($font)) {
            imagettftext($img, 20, 0, 40, 45, $text, $font, 'expired');
        } else {
            imagestring($img, 5, 40, 30, 'expired', $text);
        }

        ob_start();
        imagepng($img);
        imagedestroy($img);
        $imageData = ob_get_clean();

        $response = $this->response->withType('png')
            ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->withHeader('Pragma', 'no-cache')
            ->withStringBody($imageData);
        return $response;
    }

    protected function quickRandom($chars, $length)
    {
        $code = '';
        $max  = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[random_int(0, $max)];
        }
        return $code;
    }

    protected function readCodeFromFile($wordlist_file, $numWords = 1)
    {
        if (empty($wordlist_file) || !is_readable($wordlist_file)) {
            return false;
        }

        $fp = fopen($wordlist_file, 'rb');
        if (!$fp) return false;

        $fsize = filesize($wordlist_file);
        if ($fsize < 512) { fclose($fp); return false; }

        if ((int)$numWords < 1 || (int)$numWords > 5) $numWords = 1;

        $words = [];
        $w     = 0;
        $tries = 0;

        do {
            fseek($fp, mt_rand(0, $fsize - 512), SEEK_SET);
            $data = fread($fp, 512);

            if (($p = strpos($data, "\n")) !== false) {
                $data = substr($data, $p + 1);
            }

            if (($start = @strpos($data, "\n", mt_rand(0, (int)(strlen($data) / 2)))) === false) {
                continue;
            }

            $data = substr($data, $start + 1);
            $word = '';

            for ($i = 0; $i < strlen($data); ++$i) {
                $c = substr($data, $i, 1);
                if ($c == "\r") continue;
                if ($c == "\n") break;
                $word .= $c;
            }

            $word = trim($word);

            if (empty($word)) {
                continue;
            }

            $words[] = $word;
        } while (++$w < $numWords && $tries++ < $numWords * 2);

        fclose($fp);

        if (count($words) < $numWords) {
            return false;
        }

        if ($numWords == 1) {
            return $words[0];
        } else {
            return $words;
        }
    }
}
