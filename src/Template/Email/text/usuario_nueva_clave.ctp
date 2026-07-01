<?php
use Cake\Core\Configure;
?>
<?= Configure::read('Universidad.Siglas') ?>
<?= Configure::read('Universidad.RIF') ?>


Estimado(a) <?= $usuario->nombres ?> <?= $usuario->apellidos ?>,

Hemos recibido una solicitud para restablecer su contraseña de acceso al sistema SACE UPTBAL.

A continuación, se muestran sus datos de acceso y la nueva contraseña generada:

    Usuario: <?= $usuario->username ?>

    Nombres: <?= $usuario->nombres ?> <?= $usuario->apellidos ?>

    Correo: <?= $usuario->email ?>

    Nueva contraseña: <?= $nuevaClave ?>


Recomendamos cambiar esta contraseña una vez que inicie sesión, accediendo a la opción "Cambiar contraseña" en el menú del sistema.

Si no solicitó este cambio, por favor contacte al administrador del sistema de inmediato.

Atentamente,
Administración SACE
