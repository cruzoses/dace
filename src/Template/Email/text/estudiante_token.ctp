<?php
use Cake\Core\Configure;
?>
<?= Configure::read('Universidad.Siglas') ?>
<?= Configure::read('Universidad.RIF') ?>


Estimado(a) estudiante <?= $estudiante->nombres ?> <?= $estudiante->apellidos ?>,

Bienvenido(a) al SACE UPTBAL. Su registro como estudiante ha sido procesado exitosamente.

A continuación se muestran sus datos de inscripción y el token de verificación para acceder al sistema:

    Cédula: <?= $estudiante->cedula ?>

    Correo electrónico: <?= $estudiante->email ?>

    <?php if (!empty($estudiante->telefonos)): ?>
    Teléfono: <?= $estudiante->telefonos ?>

    <?php endif; ?>
    Token de acceso: <?= $estudiante->token ?>


Ingrese al sistema usando el siguiente enlace:
https://www.uptbal.info.ve/registro?usuario=COEEST

Conserve este token, ya que le será solicitado durante el proceso de inscripción y consultas académicas.

Atentamente,
Control de Estudios - SACE
