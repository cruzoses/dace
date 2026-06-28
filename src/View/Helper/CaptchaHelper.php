<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\Routing\Router;

class CaptchaHelper extends Helper
{
    const HTML_IMG          = 1;
    const HTML_INPUT        = 4;
    const HTML_INPUT_LABEL  = 8;
    const HTML_ICON_REFRESH = 16;
    const HTML_ALL          = 0xffffffff;

    public $helpers = ['Form', 'Html'];

    protected static $javascriptInit = false;

    public function render(array $options = [], $parts = 0xffffffff)
    {
        $defaults = [
            'image_id'          => 'captcha_image',
            'image_alt_text'    => 'CAPTCHA Image',
            'show_refresh_button' => true,
            'refresh_icon_url'  => null,
            'icon_size'         => 32,
            'show_text_input'   => true,
            'input_id'          => 'captcha_code',
            'input_name'        => 'CaptchaCode',
            'input_text'        => 'Type the text:',
            'input_required'    => true,
            'input_attributes'  => [],
            'image_attributes'  => [],
            'error_html'        => null,
            'captcha_id'        => null,
        ];

        $options = array_merge($defaults, $options);

        if (!$options['captcha_id']) {
            $options['captcha_id'] = $this->generateCaptchaId();
        }

        $image_id  = $options['image_id'];
        $captchaId = $options['captcha_id'];
        $icon_size = $options['icon_size'];

        $imageUrl = Router::url([
            'controller' => 'Captcha',
            'action'     => 'image',
            $captchaId,
        ]);

        $imageAttrs = $options['image_attributes'];
        if (!is_array($imageAttrs)) $imageAttrs = [];
        if (!isset($imageAttrs['style'])) {
            $imageAttrs['style'] = 'float: left; padding-right: 5px';
        }
        $imageAttrs['id']  = $image_id;
        $imageAttrs['src'] = $imageUrl;
        $imageAttrs['alt'] = $options['image_alt_text'];

        $imageAttrStr = '';
        foreach ($imageAttrs as $name => $val) {
            $imageAttrStr .= sprintf('%s="%s" ', $name, htmlspecialchars($val));
        }

        $html = '';

        if (($parts & self::HTML_IMG) > 0) {
            $html .= sprintf('<img %s/>', $imageAttrStr);
        }

        if (($parts & self::HTML_ICON_REFRESH) > 0 && $options['show_refresh_button']) {
            $iconPath = $options['refresh_icon_url'];
            if (!$iconPath) {
                $iconPath = Router::url('/img/refresh.png');
            }

            $imgTag = sprintf(
                '<img height="%d" width="%d" src="%s" alt="Refresh Image" onclick="this.blur()" style="border: 0px; vertical-align: bottom">',
                $icon_size,
                $icon_size,
                htmlspecialchars($iconPath)
            );

            $html .= sprintf(
                '<a tabindex="-1" style="border: 0" href="#" title="Refresh Image" onclick="securimageRefreshCaptcha(\'%s\'); this.blur(); return false">%s</a>',
                $image_id,
                $imgTag
            );
        }

        if ($parts == self::HTML_ALL) {
            $html .= '<div style="clear: both"></div>';
        }

        if (($parts & self::HTML_INPUT_LABEL) > 0 && $options['show_text_input']) {
            $html .= sprintf(
                '<label for="%s">%s</label> ',
                htmlspecialchars($options['input_id']),
                htmlspecialchars($options['input_text'])
            );

            if (!empty($options['error_html'])) {
                $html .= $options['error_html'];
            }
        }

        if (($parts & self::HTML_INPUT) > 0 && $options['show_text_input']) {
            $inputAttrs = $options['input_attributes'];
            if (!is_array($inputAttrs)) $inputAttrs = [];
            $inputAttrs['type'] = 'text';
            $inputAttrs['name'] = $options['input_name'];
            $inputAttrs['id']   = $options['input_id'];
            $inputAttrs['autocomplete'] = 'off';
            if ($options['input_required']) {
                $inputAttrs['required'] = true;
            }

            $inputAttrStr = '';
            foreach ($inputAttrs as $name => $val) {
                if ($val === true) {
                    $inputAttrStr .= sprintf('%s ', $name);
                } else {
                    $inputAttrStr .= sprintf('%s="%s" ', $name, htmlspecialchars($val));
                }
            }

            $html .= sprintf('<input %s>', $inputAttrStr);
            $html .= sprintf(
                '<input type="hidden" id="%s_captcha_id" name="captcha_id" value="%s">',
                $image_id,
                htmlspecialchars($captchaId)
            );
        }

        if (strpos($html, 'securimageRefreshCaptcha') !== false && !self::$javascriptInit) {
            $html .= sprintf(
                '<script type="text/javascript" src="%s"></script>',
                Router::url('/js/captcha.js')
            );
            self::$javascriptInit = true;
        }

        return $html;
    }

    public function imageUrl($id)
    {
        return Router::url(['controller' => 'Captcha', 'action' => 'image', $id]);
    }

    public static function generateCaptchaId()
    {
        $data = sprintf('%s:%d-%s', $_SERVER['REMOTE_ADDR'], $_SERVER['REMOTE_PORT'], microtime(true));
        return sha1(uniqid($data, true));
    }
}
