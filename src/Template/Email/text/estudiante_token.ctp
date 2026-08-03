<?php
use Cake\Core\Configure;
?>
<?= Configure::read('Universidad.Siglas') ?>
<?= Configure::read('Universidad.RIF') ?>


Estimado(a) estudiante <?= $estudiante->nombres ?> <?= $estudiante->apellidos ?>,

Ha solicitado su clave de registro al sistema SACE UPTBAL. A continuación se muestran sus datos y la información necesaria para crear su usuario:

    Cédula: <?= $estudiante->cedula ?>

    Fecha de nacimiento: <?= $estudiante->fecha_nacimiento ? $estudiante->fecha_nacimiento->format('d/m/Y') : '' ?>

    Correo electrónico: <?= $estudiante->email ?>

    Número de expediente: <?= $estudiante->expediente ?>

    Clave de registro: <?= $estudiante->token ?>


Ingrese al sistema y utilice su cédula, número de expediente y clave de registro. Sus datos personales se cargarán automáticamente; solo deberá elegir su usuario (alias) y contraseña.

Si no solicitó este registro, por favor comuníquese con la coordinación académica.

Atentamente,
Coordinación Académica - SACE
