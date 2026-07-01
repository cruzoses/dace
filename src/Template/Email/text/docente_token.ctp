<?php
use Cake\Core\Configure;
?>
<?= Configure::read('Universidad.Siglas') ?>
<?= Configure::read('Universidad.RIF') ?>


Estimado(a) docente <?= $docente->nombres ?> <?= $docente->apellidos ?>,

Bienvenido(a) al sistema SACE UPTBAL. Se le ha registrado como facilitador académico de la institución.

A continuación se muestran sus datos y el token necesario para crear su usuario:

    Cédula: <?= $docente->cedula ?>

    Correo electrónico: <?= $docente->email ?>

    <?php if ($docente->has('departamento')): ?>
    Departamento: <?= $docente->departamento->nombre ?>

    <?php endif; ?>
    Token de verificación: <?= $docente->token ?>


Utilice este token en el portal de registro:
https://www.uptbal.info.ve/registro?usuario=COEDOC

Si no solicitó este registro, por favor comuníquese con la coordinación académica.

Atentamente,
Coordinación Académica - SACE
