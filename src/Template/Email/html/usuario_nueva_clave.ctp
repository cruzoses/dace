<?php
use Cake\Core\Configure;
?>

<div style="text-align:center">
    <h1><strong><?= Configure::read('Universidad.Siglas') ?></strong></h1>
    <br>
    <h2><?= Configure::read('Universidad.RIF') ?></h2>
    <br>
    <img src="cid:734h3r38" width="256" height="256" border="0" align="center">
</div>
<br><br>
<p>Estimado(a) <strong><?= h($usuario->nombres) ?> <?= h($usuario->apellidos) ?></strong>,</p>
<p>Hemos recibido una solicitud para restablecer su contraseña de acceso al sistema <strong>SACE UPTBAL</strong>.</p>
<p>A continuación, se muestran sus datos de acceso y la nueva contraseña generada:</p>
<br>
<div style="margin:20px 40px;padding:20px;border:1px solid #ccc;border-radius:8px;background:#f9f9f9">
    <p><strong>Usuario:</strong> <?= h($usuario->username) ?></p>
    <p><strong>Nombres:</strong> <?= h($usuario->nombres) ?> <?= h($usuario->apellidos) ?></p>
    <p><strong>Correo:</strong> <?= h($usuario->email) ?></p>
    <br>
    <p><strong>Nueva contraseña:</strong></p>
    <div style="text-align:center;margin:20px 0">
        <span style="font-size:24px;font-weight:bold;letter-spacing:4px;padding:12px 24px;border:2px dashed #28a745;border-radius:6px;background:#fff">
            <?= h($nuevaClave) ?>
        </span>
    </div>
</div>
<br>
<p>Recomendamos cambiar esta contraseña una vez que inicie sesión, accediendo a la opción <strong>"Cambiar contraseña"</strong> en el menú del sistema.</p>
<p>Si no solicitó este cambio, por favor contacte al administrador del sistema de inmediato.</p>
<br>
<p>Atentamente,<br><strong>Administración SACE</strong></p>
