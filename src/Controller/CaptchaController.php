<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

class CaptchaController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['image']);
        $this->autoRender = false;
    }

    public function image($id = null)
    {
        if (!$id) {
            $this->generateEmptyImage();
            return;
        }

        $session = $this->request->getSession();
        $stored = $session->read('Captcha.' . $id);

        if (!$stored || !isset($stored['code'])) {
            $this->generateEmptyImage();
            return;
        }

        $config = $stored['config'];
        $this->generateCaptchaImage($stored['code'], $config);
    }

    protected function generateCaptchaImage($code, $config)
    {
        $width = $config['imageWidth'];
        $height = $config['imageHeight'];
        $fontSize = $config['fontSize'];

        $img = imagecreatetruecolor($width, $height);

        $bgColor = $this->allocateColor($img, $config['bgColor'], [240, 240, 240]);
        imagefill($img, 0, 0, $bgColor);

        $fontDir = WWW_ROOT . 'fonts';
        $fonts = [];
        if (is_dir($fontDir)) {
            $fonts = glob($fontDir . '/*.ttf');
        }
        if (empty($fonts)) {
            $fonts = $this->getSystemFallbackFonts();
        }

        for ($i = 0; $i < $config['lines']; $i++) {
            $col = imagecolorallocate($img, random_int(100, 200), random_int(100, 200), random_int(100, 200));
            imageline($img, random_int(0, $width), random_int(0, $height), random_int(0, $width), random_int(0, $height), $col);
        }

        $noiseColor = imagecolorallocate($img, random_int(150, 220), random_int(150, 220), random_int(150, 220));
        for ($i = 0; $i < $config['noiseDots']; $i++) {
            imagesetpixel($img, random_int(0, $width), random_int(0, $height), $noiseColor);
        }

        $charWidth = $width / strlen($code);
        for ($i = 0; $i < strlen($code); $i++) {
            $font = $fonts[random_int(0, count($fonts) - 1)];
            $angle = random_int(-25, 25);
            $x = $charWidth * $i + $charWidth * 0.2;
            $y = $height - random_int($height * 0.2, $height * 0.35);
            $color = imagecolorallocate($img, random_int(30, 150), random_int(30, 150), random_int(30, 150));
            imagettftext($img, $fontSize, $angle, (int)$x, (int)$y, $color, $font, $code[$i]);
        }

        $this->applyDistortion($img, $width, $height);

        header('Content-Type: image/png');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        imagepng($img);
        imagedestroy($img);
    }

    protected function generateEmptyImage()
    {
        $img = imagecreatetruecolor(200, 60);
        $bg = imagecolorallocate($img, 240, 240, 240);
        imagefill($img, 0, 0, $bg);
        $text = imagecolorallocate($img, 200, 200, 200);

        $fonts = $this->getSystemFallbackFonts();
        $font = !empty($fonts) ? $fonts[0] : null;
        if ($font) {
            imagettftext($img, 20, 0, 40, 40, $text, $font, 'expired');
        } else {
            imagestring($img, 5, 40, 20, 'expired', $text);
        }

        header('Content-Type: image/png');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        imagepng($img);
        imagedestroy($img);
    }

    protected function allocateColor($img, $color, $default)
    {
        if ($color && preg_match('/^#?([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})$/', $color, $m)) {
            return imagecolorallocate($img, hexdec($m[1]), hexdec($m[2]), hexdec($m[3]));
        }
        return imagecolorallocate($img, $default[0], $default[1], $default[2]);
    }

    protected function applyDistortion($img, $width, $height)
    {
        $dest = imagecreatetruecolor($width, $height);
        $bg = imagecolorallocate($dest, 240, 240, 240);
        imagefill($dest, 0, 0, $bg);

        $ampX = 3;
        $ampY = 2;
        $freqX = 0.05;
        $freqY = 0.08;

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $srcX = $x + sin($y * $freqX) * $ampX;
                $srcY = $y + sin($x * $freqY) * $ampY;
                $srcX = max(0, min($width - 1, $srcX));
                $srcY = max(0, min($height - 1, $srcY));
                $color = imagecolorat($img, (int)$srcX, (int)$srcY);
                imagesetpixel($dest, $x, $y, $color);
            }
        }

        imagecopy($img, $dest, 0, 0, 0, 0, $width, $height);
        imagedestroy($dest);
    }

    protected function getSystemFallbackFonts()
    {
        $paths = [
            'C:/Windows/Fonts/arial.ttf',
            'C:/Windows/Fonts/verdana.ttf',
            'C:/Windows/Fonts/tahoma.ttf',
        ];
        $fonts = [];
        foreach ($paths as $p) {
            if (file_exists($p)) {
                $fonts[] = $p;
            }
        }
        return $fonts;
    }
}
