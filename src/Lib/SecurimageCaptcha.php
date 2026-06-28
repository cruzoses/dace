<?php
namespace App\Lib;

class SecurimageCaptcha
{
    const SI_IMAGE_JPEG = 1;
    const SI_IMAGE_PNG  = 2;
    const SI_IMAGE_GIF  = 3;

    public $image_width      = 215;
    public $image_height     = 80;
    public $font_ratio;
    public $image_type       = self::SI_IMAGE_PNG;
    public $image_bg_color   = '#ffffff';
    public $text_color       = '#707070';
    public $line_color       = '#707070';
    public $noise_color      = '#707070';
    public $code_length      = 6;
    public $use_random_spaces = false;
    public $use_text_angles  = false;
    public $use_random_baseline = false;
    public $use_random_boxes = false;
    public $perturbation     = 0.85;
    public $num_lines        = 5;
    public $noise_level      = 2;
    public $image_signature  = '';
    public $signature_color  = '#707070';
    public $signature_font;
    public $ttf_file;
    public $background_directory;
    public $send_headers     = true;
    public $display_value;

    protected $im;
    protected $tmpimg;
    protected $bgimg = '';
    protected $iscale = 2;
    protected $gdbgcolor;
    protected $gdtextcolor;
    protected $gdlinecolor;
    protected $gdnoisecolor;
    protected $gdsignaturecolor;
    protected $code;
    protected $code_display;
    protected $no_exit = true;
    protected $outputData;

    public function __construct($options = [])
    {
        foreach ($options as $prop => $val) {
            $this->$prop = $val;
        }

        $this->initAllColors();

        if (is_null($this->ttf_file)) {
            $this->ttf_file = WWW_ROOT . 'fonts' . DIRECTORY_SEPARATOR . 'AHGBold.ttf';
        }
        if (is_null($this->signature_font)) {
            $this->signature_font = $this->ttf_file;
        }
        if (is_null($this->font_ratio)) {
            $this->font_ratio = 0.4;
        }
        if (is_null($this->code_length) || (int)$this->code_length < 1) {
            $this->code_length = 6;
        }
        if (is_null($this->perturbation) || !is_numeric($this->perturbation)) {
            $this->perturbation = 0.75;
        }
    }

    protected function initAllColors()
    {
        $this->image_bg_color  = $this->initColor($this->image_bg_color,  '#ffffff');
        $this->text_color      = $this->initColor($this->text_color,      '#616161');
        $this->line_color      = $this->initColor($this->line_color,      '#616161');
        $this->noise_color     = $this->initColor($this->noise_color,     '#616161');
        $this->signature_color = $this->initColor($this->signature_color, '#616161');
    }

    public function generate($code, $codeDisplay)
    {
        $this->code         = $code;
        $this->code_display = $codeDisplay;

        if ($this->bgimg != '' || function_exists('imagecreatetruecolor')) {
            $imagecreate = 'imagecreatetruecolor';
        } else {
            $imagecreate = 'imagecreate';
        }

        $this->im = $imagecreate($this->image_width, $this->image_height);

        if (function_exists('imageantialias')) {
            imageantialias($this->im, true);
        }

        $this->allocateColors();

        if ($this->perturbation > 0) {
            $this->tmpimg = $imagecreate($this->image_width * $this->iscale, $this->image_height * $this->iscale);
            imagepalettecopy($this->tmpimg, $this->im);
        } else {
            $this->iscale = 1;
        }

        $this->setBackground();

        if ($this->noise_level > 0) {
            $this->drawNoise();
        }

        $this->drawWord();

        if ($this->perturbation > 0) {
            $this->distortedCopy();
        }

        if ($this->num_lines > 0) {
            $this->drawLines();
        }

        if (trim($this->image_signature) != '') {
            $this->addSignature();
        }

        $this->outputData = $this->getOutputData();
        return $this->outputData;
    }

    protected function allocateColors()
    {
        $this->image_bg_color  = $this->ensureColorObj($this->image_bg_color,  '#ffffff');
        $this->text_color      = $this->ensureColorObj($this->text_color,      '#616161');
        $this->line_color      = $this->ensureColorObj($this->line_color,      '#616161');
        $this->noise_color     = $this->ensureColorObj($this->noise_color,     '#616161');
        $this->signature_color = $this->ensureColorObj($this->signature_color, '#616161');

        $this->gdbgcolor = imagecolorallocate($this->im,
            $this->image_bg_color->r,
            $this->image_bg_color->g,
            $this->image_bg_color->b);

        $this->gdtextcolor = imagecolorallocate($this->im,
            $this->text_color->r,
            $this->text_color->g,
            $this->text_color->b);

        $this->gdlinecolor = imagecolorallocate($this->im,
            $this->line_color->r,
            $this->line_color->g,
            $this->line_color->b);

        $this->gdnoisecolor = imagecolorallocate($this->im,
            $this->noise_color->r,
            $this->noise_color->g,
            $this->noise_color->b);

        $this->gdsignaturecolor = imagecolorallocate($this->im,
            $this->signature_color->r,
            $this->signature_color->g,
            $this->signature_color->b);
    }

    protected function setBackground()
    {
        imagefilledrectangle($this->im, 0, 0,
            $this->image_width, $this->image_height,
            $this->gdbgcolor);

        if ($this->perturbation > 0) {
            imagefilledrectangle($this->tmpimg, 0, 0,
                $this->image_width * $this->iscale, $this->image_height * $this->iscale,
                $this->gdbgcolor);
        }

        if ($this->bgimg == '') {
            if ($this->background_directory != null &&
                is_dir($this->background_directory) &&
                is_readable($this->background_directory))
            {
                $img = $this->getBackgroundFromDirectory();
                if ($img != false) {
                    $this->bgimg = $img;
                }
            }
        }

        if ($this->bgimg == '') {
            return;
        }

        $dat = @getimagesize($this->bgimg);
        if ($dat == false) {
            return;
        }

        switch ($dat[2]) {
            case 1:  $newim = @imagecreatefromgif($this->bgimg); break;
            case 2:  $newim = @imagecreatefromjpeg($this->bgimg); break;
            case 3:  $newim = @imagecreatefrompng($this->bgimg); break;
            default: return;
        }

        if (!$newim) return;

        imagecopyresized($this->im, $newim, 0, 0, 0, 0,
            $this->image_width, $this->image_height,
            imagesx($newim), imagesy($newim));
    }

    protected function getBackgroundFromDirectory()
    {
        $images = [];

        if (($dh = opendir($this->background_directory)) !== false) {
            while (($file = readdir($dh)) !== false) {
                if (preg_match('/(jpg|gif|png)$/i', $file)) $images[] = $file;
            }

            closedir($dh);

            if (sizeof($images) > 0) {
                return rtrim($this->background_directory, '/') . '/' . $images[mt_rand(0, sizeof($images)-1)];
            }
        }

        return false;
    }

    protected function drawNoise()
    {
        if ($this->noise_level > 10) {
            $noise_level = 10;
        } else {
            $noise_level = $this->noise_level;
        }

        $noise_level *= M_LOG2E;

        for ($x = 1; $x < $this->image_width; $x += 20) {
            for ($y = 1; $y < $this->image_height; $y += 20) {
                for ($i = 0; $i < $noise_level; ++$i) {
                    $x1 = mt_rand($x, $x + 20);
                    $y1 = mt_rand($y, $y + 20);
                    $size = mt_rand(1, 3);

                    if ($x1 - $size <= 0 && $y1 - $size <= 0) continue;
                    imagefilledarc($this->im, $x1, $y1, $size, $size, 0, mt_rand(180, 360), $this->gdlinecolor, IMG_ARC_PIE);
                }
            }
        }
    }

    protected function drawWord()
    {
        $ratio = ($this->font_ratio) ? $this->font_ratio : 0.4;

        if ((float)$ratio < 0.1 || (float)$ratio >= 1) {
            $ratio = 0.4;
        }

        if (!is_readable($this->ttfFile())) {
            $this->perturbation = 0;
            imagestring($this->im, 4, 10, ($this->image_height / 2) - 5, 'Failed to load TTF font file!', $this->gdtextcolor);
            return;
        }

        if ($this->perturbation > 0) {
            $width     = $this->image_width * $this->iscale;
            $height    = $this->image_height * $this->iscale;
            $font_size = $height * $ratio;
            $im        = &$this->tmpimg;
            $scale     = $this->iscale;
        } else {
            $height    = $this->image_height;
            $width     = $this->image_width;
            $font_size = $this->image_height * $ratio;
            $im        = &$this->im;
            $scale     = 1;
        }

        $captcha_text = $this->code_display;

        if ($this->use_random_spaces && $this->strpos($captcha_text, ' ') === false) {
            if (mt_rand(1, 100) % 5 > 0) {
                $index  = mt_rand(1, $this->strlen($captcha_text) - 1);
                $spaces = mt_rand(1, 3);

                $captcha_text = sprintf(
                    '%s%s%s',
                    $this->substr($captcha_text, 0, $index),
                    str_repeat(' ', $spaces),
                    $this->substr($captcha_text, $index)
                );
            }
        }

        $fonts    = [];
        $angles   = [];
        $distance = [];
        $dims     = [];
        $txtWid   = 0;

        $angle0 = mt_rand(10, 20);
        $angleN = mt_rand(-20, 10);

        if ($this->use_text_angles == false) {
            $angle0 = $angleN = $step = 0;
        }

        if (mt_rand(0, 99) % 2 == 0) {
            $angle0 = -$angle0;
        }
        if (mt_rand(0, 99) % 2 == 1) {
            $angleN = -$angleN;
        }

        $step = 0;
        if ($this->strlen($captcha_text) - 1) {
            $step = abs($angle0 - $angleN) / ($this->strlen($captcha_text) - 1);
        }
        $step   = ($angle0 > $angleN) ? -$step : $step;
        $angle  = $angle0;

        for ($c = 0; $c < $this->strlen($captcha_text); ++$c) {
            $font     = $this->ttfFile();
            $fonts[]  = $font;
            $angles[] = $angle;
            $dist     = mt_rand(-2, 0) * $scale;
            $distance[] = $dist;
            $char     = $this->substr($captcha_text, $c, 1);

            $dim = $this->getCharacterDimensions($char, $font_size, $angle, $font);

            $dim[0] += $dist;
            $txtWid += $dim[0];

            $dims[] = $dim;

            $angle += $step;

            if ($angle > 20) {
                $angle = 20;
                $step  = $step * -1;
            } elseif ($angle < -20) {
                $angle = -20;
                $step  = -1 * $step;
            }
        }

        $nextYPos = function($y, $i, $step) use ($height, $scale, $dims) {
            static $dir = 1;

            if ($y + $step + $dims[$i][2] + (10 * $scale) > $height) {
                $dir = 0;
            } elseif ($y - $step - $dims[$i][2] < $dims[$i][1] + $dims[$i][2] + (5 * $scale)) {
                $dir = 1;
            }

            if ($dir) {
                $y += $step;
            } else {
                $y -= $step;
            }

            return $y;
        };

        $cx = floor($width / 2 - ($txtWid / 2));
        $x  = mt_rand(5 * $scale, max($cx * 2 - (5 * $scale), 5 * $scale));

        if ($this->use_random_baseline) {
            $y = mt_rand($dims[0][1], $height - 10);
        } else {
            $y = ($height / 2 + $dims[0][1] / 2 - $dims[0][2]);
        }

        $st = $scale * mt_rand(5, 10);

        for ($c = 0; $c < $this->strlen($captcha_text); ++$c) {
            $font  = $fonts[$c];
            $char  = $this->substr($captcha_text, $c, 1);
            $angle = $angles[$c];
            $dim   = $dims[$c];

            if ($this->use_random_baseline) {
                $y = $nextYPos($y, $c, $st);
            }

            imagettftext(
                $im,
                $font_size,
                $angle,
                (int)$x,
                (int)$y,
                $this->gdtextcolor,
                $font,
                $char
            );

            if ($this->use_random_boxes && strlen(trim($char)) && mt_rand(1, 100) % 5 == 0) {
                imagesetthickness($im, 3);
                imagerectangle($im, $x, $y - $dim[1] + $dim[2], $x + $dim[0], $y + $dim[2], $this->gdtextcolor);
            }

            if ($c == ' ') {
                $x += $dim[0];
            } else {
                $x += $dim[0] + $distance[$c];
            }
        }
    }

    protected function getCharacterDimensions($char, $size, $angle, $font)
    {
        $box = imagettfbbox($size, $angle, $font, $char);

        return [$box[2] - $box[0], max($box[1] - $box[7], $box[5] - $box[3]), $box[1]];
    }

    protected function distortedCopy()
    {
        $numpoles = 3;
        $px       = [];
        $py       = [];
        $rad      = [];
        $amp      = [];
        $x        = ($this->image_width / 4);
        $maxX     = $this->image_width - $x;
        $dx       = mt_rand($x / 10, $x);
        $y        = mt_rand(20, $this->image_height - 20);
        $dy       = mt_rand(20, round($this->image_height * 0.7, 0));
        $minY     = 20;
        $maxY     = $this->image_height - 20;

        for ($i = 0; $i < $numpoles; ++ $i) {
            $px[$i]  = ($x + ($dx * $i)) % $maxX;
            $py[$i]  = ($y + ($dy * $i)) % $maxY + $minY;
            $rad[$i] = mt_rand($this->image_height * 0.4, $this->image_height * 0.8);
            $tmp     = ((- $this->frand()) * 0.15) - .15;
            $amp[$i] = $this->perturbation * $tmp;
        }

        $bgCol   = imagecolorat($this->tmpimg, 0, 0);
        $width2  = $this->iscale * $this->image_width;
        $height2 = $this->iscale * $this->image_height;
        imagepalettecopy($this->im, $this->tmpimg);

        for ($ix = 0; $ix < $this->image_width; ++ $ix) {
            for ($iy = 0; $iy < $this->image_height; ++ $iy) {
                $x = $ix;
                $y = $iy;
                for ($i = 0; $i < $numpoles; ++ $i) {
                    $dx = $ix - $px[$i];
                    $dy = $iy - $py[$i];
                    if ($dx == 0 && $dy == 0) {
                        continue;
                    }
                    $r = sqrt($dx * $dx + $dy * $dy);
                    if ($r > $rad[$i]) {
                        continue;
                    }
                    $rscale = $amp[$i] * sin(3.14 * $r / $rad[$i]);
                    $x += $dx * $rscale;
                    $y += $dy * $rscale;
                }
                $c = $bgCol;
                $x *= $this->iscale;
                $y *= $this->iscale;
                if ($x >= 0 && $x < $width2 && $y >= 0 && $y < $height2) {
                    $c = imagecolorat($this->tmpimg, round($x, 0), round($y, 0));
                }
                if ($c != $bgCol) {
                    imagesetpixel($this->im, $ix, $iy, $c);
                }
            }
        }
    }

    protected function drawLines()
    {
        for ($line = 0; $line < $this->num_lines; ++ $line) {
            $x = $this->image_width * (1 + $line) / ($this->num_lines + 1);
            $x += (0.5 - $this->frand()) * $this->image_width / $this->num_lines;
            $y = mt_rand(floor($this->image_height * 0.1), floor($this->image_height * 0.9));

            $theta = ($this->frand() - 0.5) * M_PI * 0.33;
            $w = $this->image_width;
            $len = mt_rand($w * 0.4, $w * 0.7);
            $lwid = mt_rand(0, 2);

            $k = $this->frand() * 0.6 + 0.2;
            $k = $k * $k * 0.5;
            $phi = $this->frand() * 6.28;
            $step = 0.5;
            $dx = $step * cos($theta);
            $dy = $step * sin($theta);
            $n = $len / $step;
            $amp = 1.5 * $this->frand() / ($k + 5.0 / $len);
            $x0 = $x - 0.5 * $len * cos($theta);
            $y0 = $y - 0.5 * $len * sin($theta);

            $ldx = round(- $dy * $lwid);
            $ldy = round($dx * $lwid);

            for ($i = 0; $i < $n; ++ $i) {
                $x = $x0 + $i * $dx + $amp * $dy * sin($k * $i * $step + $phi);
                $y = $y0 + $i * $dy - $amp * $dx * sin($k * $i * $step + $phi);
                imagefilledrectangle($this->im, round($x, 0), round($y, 0), round($x + $lwid, 0), round($y + $lwid, 0), $this->gdlinecolor);
            }
        }
    }

    protected function addSignature()
    {
        $bbox = imagettfbbox(10, 0, $this->signature_font, $this->image_signature);
        $textlen = $bbox[2] - $bbox[0];
        $x = $this->image_width - $textlen - 5;
        $y = $this->image_height - 3;

        imagettftext($this->im, 10, 0, $x, $y, $this->gdsignaturecolor, $this->signature_font, $this->image_signature);
    }

    protected function getOutputData()
    {
        ob_start();
        switch ($this->image_type) {
            case self::SI_IMAGE_JPEG:
                imagejpeg($this->im, null, 90);
                break;
            case self::SI_IMAGE_GIF:
                imagegif($this->im);
                break;
            default:
                imagepng($this->im);
                break;
        }
        imagedestroy($this->im);
        return ob_get_clean();
    }

    protected function frand()
    {
        return 0.0001 * mt_rand(0, 9999);
    }

    protected function ttfFile()
    {
        if (is_string($this->ttf_file)) {
            return $this->ttf_file;
        } elseif (is_array($this->ttf_file)) {
            return $this->ttf_file[mt_rand(0, sizeof($this->ttf_file)-1)];
        } else {
            throw new \Exception('ttf_file is not a string or array');
        }
    }

    protected function initColor($color, $default)
    {
        if ($color == null) {
            return new SecurimageCaptcha_Color($default);
        } else if (is_string($color)) {
            try {
                return new SecurimageCaptcha_Color($color);
            } catch (\Exception $e) {
                return new SecurimageCaptcha_Color($default);
            }
        } else if (is_array($color) && sizeof($color) == 3) {
            return new SecurimageCaptcha_Color($color[0], $color[1], $color[2]);
        } else {
            return new SecurimageCaptcha_Color($default);
        }
    }

    protected function ensureColorObj($color, $default)
    {
        if ($color instanceof SecurimageCaptcha_Color) {
            return $color;
        }
        if (is_null($color)) {
            return new SecurimageCaptcha_Color($default);
        }
        if (is_string($color)) {
            try {
                return new SecurimageCaptcha_Color($color);
            } catch (\Exception $e) {
                return new SecurimageCaptcha_Color($default);
            }
        }
        if (is_array($color) && count($color) === 3) {
            return new SecurimageCaptcha_Color($color[0], $color[1], $color[2]);
        }
        return new SecurimageCaptcha_Color($default);
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

class SecurimageCaptcha_Color
{
    public $r;
    public $g;
    public $b;

    public function __construct($color = '#ffffff')
    {
        $args = func_get_args();

        if (sizeof($args) == 0) {
            $this->r = 255;
            $this->g = 255;
            $this->b = 255;
        } else if (sizeof($args) == 1) {
            if (substr($color, 0, 1) == '#') {
                $color = substr($color, 1);
            }

            if (strlen($color) != 3 && strlen($color) != 6) {
                throw new \InvalidArgumentException(
                    'Invalid HTML color code passed to SecurimageCaptcha_Color'
                );
            }

            $this->constructHTML($color);
        } else if (sizeof($args) == 3) {
            $this->constructRGB($args[0], $args[1], $args[2]);
        } else {
            throw new \InvalidArgumentException(
                'SecurimageCaptcha_Color constructor expects 0, 1 or 3 arguments; ' . sizeof($args) . ' given'
            );
        }
    }

    public function toLongColor()
    {
        return ($this->r << 16) + ($this->g << 8) + $this->b;
    }

    public function fromLongColor($color)
    {
        $this->r = ($color >> 16) & 0xff;
        $this->g = ($color >>  8) & 0xff;
        $this->b =  $color        & 0xff;
        return $this;
    }

    protected function constructRGB($red, $green, $blue)
    {
        if ($red < 0)     $red   = 0;
        if ($red > 255)   $red   = 255;
        if ($green < 0)   $green = 0;
        if ($green > 255) $green = 255;
        if ($blue < 0)    $blue  = 0;
        if ($blue > 255)  $blue  = 255;

        $this->r = $red;
        $this->g = $green;
        $this->b = $blue;
    }

    protected function constructHTML($color)
    {
        if (strlen($color) == 3) {
            $red   = str_repeat(substr($color, 0, 1), 2);
            $green = str_repeat(substr($color, 1, 1), 2);
            $blue  = str_repeat(substr($color, 2, 1), 2);
        } else {
            $red   = substr($color, 0, 2);
            $green = substr($color, 2, 2);
            $blue  = substr($color, 4, 2);
        }

        $this->r = hexdec($red);
        $this->g = hexdec($green);
        $this->b = hexdec($blue);
    }
}
