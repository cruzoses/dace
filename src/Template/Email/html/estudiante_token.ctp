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
<p>Estimado(a) estudiante <strong><?= h($estudiante->nombres) ?> <?= h($estudiante->apellidos) ?></strong>,</p>
<p>Ha solicitado su clave de registro al sistema <strong>SACE UPTBAL</strong>. A continuación se muestran sus datos y la información necesaria para crear su usuario:</p>
<br>
<div style="margin:20px 40px;padding:20px;border:1px solid #ccc;border-radius:8px;background:#f9f9f9">
    <p><strong>Cédula:</strong> <?= h($estudiante->cedula) ?></p>
    <p><strong>Fecha de nacimiento:</strong> <?= h($estudiante->fecha_nacimiento ? $estudiante->fecha_nacimiento->format('d/m/Y') : '') ?></p>
    <p><strong>Correo electrónico:</strong> <?= h($estudiante->email) ?></p>
    <br>
    <p><strong>Número de expediente:</strong></p>
    <div style="text-align:center;margin:20px 0">
        <span style="font-size:22px;font-weight:bold;letter-spacing:4px;padding:12px 32px;border:2px dashed #007bff;border-radius:6px;background:#fff">
            <?= h($estudiante->expediente) ?>
        </span>
    </div>
    <p><strong>Clave de registro:</strong></p>
    <div style="text-align:center;margin:20px 0">
        <span style="font-size:28px;font-weight:bold;letter-spacing:8px;padding:12px 32px;border:2px dashed #28a745;border-radius:6px;background:#fff">
            <?= h($estudiante->token) ?>
        </span>
    </div>
</div>
<br>
<p>Ingrese al sistema y utilice su cédula, número de expediente y clave de registro. Sus datos personales se cargarán automáticamente; solo deberá elegir su usuario (alias) y contraseña.</p>
<br>
<p>Si no solicitó este registro, por favor comuníquese con la coordinación académica.</p>
<p>Atentamente,<br><strong>Coordinación Académica - SACE</strong></p>
