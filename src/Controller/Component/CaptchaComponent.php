<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;

class CaptchaComponent extends Component
{
    protected $defaultConfig = [
        'captcha_type'        => 'string',
        'code_length'         => 6,
        'charset'             => 'abcdefghijkmnopqrstuvwxzyABCDEFGHJKLMNPQRSTUVWXZY23456789',
        'case_sensitive'      => false,
        'use_wordlist'        => false,
        'wordlist_file'       => null,
        'image_width'         => 215,
        'image_height'        => 80,
        'font_ratio'          => 0.4,
        'ttf_files'           => [],
        'image_bg_color'      => '#ffffff',
        'text_color'          => '#707070',
        'line_color'          => '#707070',
        'noise_color'         => '#707070',
        'num_lines'           => 5,
        'noise_level'         => 2,
        'perturbation'        => 0.85,
        'use_random_spaces'   => false,
        'use_text_angles'     => false,
        'use_random_baseline' => false,
        'use_random_boxes'    => false,
        'background_directory' => null,
        'image_signature'     => '',
        'signature_color'     => '#707070',
        'timeout'             => 900,
    ];

    protected $config;

    public function initialize(array $config)
    {
        $captchaConfig = Configure::read('captcha.Default');
        if (!$captchaConfig) {
            $captchaConfig = Configure::load('captcha', 'default', false);
            $captchaConfig = Configure::read('captcha.Default');
        }

        $preset = 'Default';
        if (!empty($config['preset'])) {
            $preset = $config['preset'];
        }

        $this->config = $this->defaultConfig;
        if ($captchaConfig) {
            $this->config = array_merge($this->config, $captchaConfig);
        }
        $presetConfig = Configure::read('captcha.' . $preset);
        if ($presetConfig) {
            $this->config = array_merge($this->config, $presetConfig);
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

        $captcha_type = $this->config['captcha_type'];
        $code         = '';
        $code_display = '';

        switch ($captcha_type) {
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
                $words = $this->readCodeFromFile(2);
                if ($words === false) {
                    $code         = $this->randomCode($this->config['code_length']);
                    $code_display = $code;
                } else {
                    $code         = implode(' ', $words);
                    $code_display = $code;
                }
                break;

            default:
                if ($this->config['use_wordlist'] && !empty($this->config['wordlist_file']) && is_readable($this->config['wordlist_file'])) {
                    $word = $this->readCodeFromFile(1);
                    if ($word !== false) {
                        $code = $word;
                    }
                }
                if ($code == '') {
                    $code = $this->randomCode($this->config['code_length']);
                }
                $code_display = $code;
                $code         = ($this->config['case_sensitive']) ? $code : strtolower($code);
                break;
        }

        $this->request->getSession()->write('Captcha.' . $id, [
            'code'         => $code,
            'code_display' => $code_display,
            'time'         => time(),
            'config'       => $this->config,
        ]);

        return $id;
    }

    public function validate($input, $id)
    {
        $session = $this->request->getSession();
        $stored  = $session->read('Captcha.' . $id);

        if (!$stored) {
            return false;
        }

        $session->delete('Captcha.' . $id);

        if (time() - $stored['time'] > $stored['config']['timeout']) {
            return false;
        }

        $code_entered = trim($input);
        $stored_code  = $stored['code'];

        $case_sensitive = isset($stored['config']['case_sensitive']) ? $stored['config']['case_sensitive'] : false;

        if (!$case_sensitive) {
            $stored_code  = strtolower($stored_code);
            $code_entered = strtolower($code_entered);
        }

        if (strpos($stored_code, ' ') !== false) {
            $code_entered = preg_replace('/\s+/', ' ', $code_entered);
        }

        return (string)$stored_code === (string)$code_entered;
    }

    public function getCaptchaConfig($id = null)
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

    public function getCodeDisplay($id)
    {
        $stored = $this->request->getSession()->read('Captcha.' . $id);
        if ($stored && isset($stored['code_display'])) {
            return $stored['code_display'];
        }
        return null;
    }

    protected function randomCode($length)
    {
        $chars = $this->config['charset'];
        if (empty($chars)) {
            $chars = 'abcdefghijkmnopqrstuvwxzyABCDEFGHJKLMNPQRSTUVWXZY23456789';
        }
        $code = '';
        $max  = $this->strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $code .= $this->substr($chars, mt_rand(0, $max), 1);
        }
        return $code;
    }

    protected function readCodeFromFile($numWords = 1)
    {
        $wordlist_file = $this->config['wordlist_file'];
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

            if (($p = $this->strpos($data, "\n")) !== false) {
                $data = $this->substr($data, $p + 1);
            }

            if (($start = @$this->strpos($data, "\n", mt_rand(0, $this->strlen($data) / 2))) === false) {
                continue;
            }

            $data = $this->substr($data, $start + 1);
            $word = '';

            for ($i = 0; $i < $this->strlen($data); ++$i) {
                $c = $this->substr($data, $i, 1);
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

    protected function strlen($string)
    {
        $strlen = 'strlen';
        if (function_exists('mb_strlen')) {
            $strlen = 'mb_strlen';
        }
        return $strlen($string);
    }

    protected function substr($string, $start, $length = null)
    {
        $substr = 'substr';
        if (function_exists('mb_substr')) {
            $substr = 'mb_substr';
        }
        if ($length === null) {
            return $substr($string, $start);
        } else {
            return $substr($string, $start, $length);
        }
    }

    protected function strpos($haystack, $needle, $offset = 0)
    {
        $strpos = 'strpos';
        if (function_exists('mb_strpos')) {
            $strpos = 'mb_strpos';
        }
        return $strpos($haystack, $needle, $offset);
    }
}
