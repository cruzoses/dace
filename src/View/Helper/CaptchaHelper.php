<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\Routing\Router;

class CaptchaHelper extends Helper
{
    public $helpers = ['Form', 'Html'];

    public function render($id = null, array $options = [])
    {
        if (!$id) {
            $id = uniqid('captcha_', true);
        }

        $options += [
            'inputLabel' => __('Retype the characters from the picture:'),
            'inputId' => 'CaptchaCode',
            'inputName' => 'CaptchaCode',
            'inputMaxlength' => 10,
            'imageWidth' => null,
            'imageHeight' => null,
        ];

        $url = Router::url(['controller' => 'Captcha', 'action' => 'image', $id]);
        $width = $options['imageWidth'] ? ' width="' . $options['imageWidth'] . '"' : '';
        $height = $options['imageHeight'] ? ' height="' . $options['imageHeight'] . '"' : '';

        $out = '';
        $out .= '<div class="dace-captcha">';
        $out .= '<img src="' . $url . '" alt="Captcha" class="dace-captcha-image"' . $width . $height . '>';
        $out .= $this->Form->input($options['inputName'], [
            'label' => $options['inputLabel'],
            'id' => $options['inputId'],
            'maxlength' => $options['inputMaxlength'],
            'class' => 'dace-captcha-input',
        ]);
        $out .= '</div>';

        return $out;
    }

    public function imageUrl($id)
    {
        return Router::url(['controller' => 'Captcha', 'action' => 'image', $id]);
    }
}
