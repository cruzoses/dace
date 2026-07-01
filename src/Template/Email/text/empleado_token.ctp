<?php
use Cake\Core\Configure;
?>
<?= Configure::read('Universidad.Siglas') ?>
<?= Configure::read('Universidad.RIF') ?>


Estimado(a) <?= $empleado->full_name ?>,

Ha sido registrado en el sistema SACE UPTBAL como personal administrativo/obrero de la institución.

A continuación se muestra su código de verificación para completar el registro de usuario:

    Cédula: <?= $empleado->cedula ?>

    Correo: <?= $empleado->email ?>

    Token de registro: <?= $empleado->token ?>


Acceda al siguiente enlace para completar su registro:
https://www.uptbal.info.ve/registro?usuario=COESTU

Si tiene alguna duda, puede presentarse en la oficina de recursos humanos para recibir asistencia.

Atentamente,
Administración SACE
