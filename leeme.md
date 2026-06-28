# Componente Captcha personalizado

Reemplazo del sistema BotDetect (privativo, pesado, ofuscado) por un captcha liviano con GD.

## Archivos creados

| Archivo | Propósito |
|---|---|
| `src/Controller/Component/CaptchaComponent.php` | Genera códigos aleatorios, guarda en sesión, valida input |
| `src/Controller/CaptchaController.php` | Dibuja la imagen PNG con GD |
| `src/View/Helper/CaptchaHelper.php` | Helper para renderizar captcha en vistas |

## Archivos modificados

| Archivo | Cambio |
|---|---|
| `config/captcha.php` | Configuración con presets `Default` y `Login` |
| `config/routes.php` | Ruta `/captcha-image/:id` → `CaptchaController::image` |
| `src/View/AppView.php` | Se agregó `$this->loadHelper('Captcha')` |
| `webroot/css/sace.css` | Estilos `.dace-captcha` |
| `src/Controller/PruebasController.php` | Ejemplo de uso |
| `src/Template/Pruebas/index.ctp` | Vista de ejemplo |

## Cómo usarlo en un controlador

```php
// En initialize()
$this->loadComponent('Captcha', ['preset' => 'Default']);

// En la acción
$captchaId = $this->Captcha->generate();
$this->set('captchaId', $captchaId);

// En el POST
if ($this->request->is('post')) {
    $code = $this->request->getData('CaptchaCode');
    if ($this->Captcha->validate($code, $this->request->getData('captcha_id'))) {
        // Captcha correcto
    } else {
        // Captcha incorrecto
    }
}
```

## Cómo usarlo en una vista

```php
<?= $this->Captcha->render($captchaId, [
    'inputLabel' => __('Retype the characters from the picture:'),
]) ?>
<?= $this->Form->hidden('captcha_id', ['value' => $captchaId]) ?>
```

## Opciones de configuración (`config/captcha.php`)

```php
return [
    'Default' => [
        'codeLength' => 5,       // caracteres
        'imageWidth' => 200,     // px
        'imageHeight' => 60,     // px
        'fontSize' => 28,        // px
        'lines' => 4,            // líneas de ruido
        'noiseDots' => 80,       // puntos de ruido
        'bgColor' => null,       // color fondo (#RRGGBB o null = aleatorio)
        'textColor' => null,     // color texto
        'lineColor' => null,     // color líneas
        'timeout' => 300,        // segundos de validez
    ],
];
```

## Características de la imagen

- Fondo color gris claro
- Líneas aleatorias
- Puntos de ruido
- Texto rotado (ángulo ±25°) con fuente TTF
- Colores variables por caracter
- Distorsión sinusoidal ligera
- Sin mayúsculas ambiguas (O, I, 0, 1 excluidos)
- Sin sonido
- Sin dependencias externas (solo PHP GD y fuentes TTF incluidas)

## Fuentes TTF

Se copiaron 3 fuentes desde `vendor/captcha-com/captcha/.../Fonts/Latn/` a `webroot/fonts/`. Si se usan las fuentes del sistema Windows como fallback.
