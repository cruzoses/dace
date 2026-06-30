<?php
use Cake\Core\Configure;
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SACE</title>
    </head>
    <body>        
        <div style="text-align:center">
            <h3><strong><?= Configure::read('Universidad.Siglas') ?></strong></h3>
            <br>
            <h2><?= Configure::read('Universidad.RIF') ?></h2>
            <br>
            <img src="<?= $this->Url->build('/img/logos/logouptbal.png', ['fullBase' => true]) ?>" style="width:128px;height:128px" alt="Logo">
        </div>
        <br><br>
        <p>Estimado(a) <strong><?= h($empleado->full_name) ?> </strong>,</p>
        <p>Estimado(a) <strong><?= h($empleado->nombres) ?> <?= h($empleado->apellidos) ?></strong>,</p>
        <p>A continuación, te entregamos las credenciales para que puedas completar el registro de usuario:</p>
        <div style="text-align:center;margin:30px 0">
            <span style="font-size:24px;font-weight:bold;letter-spacing:6px;padding:12px 24px;border:2px dashed #333;border-radius:6px;background:#f9f9f9"><?= h($empleado->token) ?></span>
        </div>
        <br>
        <p>Accede a través del siguiente enlace: <a href="https://www.uptbal.info.ve/registro?usuario=COESTU">https://www.uptbal.info.ve/registro?usuario=COESTU</a></p>
        <br>
        <p>Puede presentarse en la oficina correspondiente para completar su registro o utilizar el token proporcionado.</p>
        <p>Atentamente,<br><strong>Administración SACE</strong></p>
    </body>
</html>
<body>