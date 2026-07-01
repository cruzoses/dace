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
<p>Estimado(a) docente <strong><?= h($docente->nombres) ?> <?= h($docente->apellidos) ?></strong>,</p>
<p>Bienvenido(a) al sistema <strong>SACE UPTBAL</strong>. Se le ha registrado como facilitador académico de la institución.</p>
<p>A continuación se muestran sus datos y el token necesario para crear su usuario:</p>
<br>
<div style="margin:20px 40px;padding:20px;border:1px solid #ccc;border-radius:8px;background:#f9f9f9">
    <p><strong>Cédula:</strong> <?= h($docente->cedula) ?></p>
    <p><strong>Correo electrónico:</strong> <?= h($docente->email) ?></p>
    <?php if ($docente->has('departamento')): ?>
    <p><strong>Departamento:</strong> <?= h($docente->departamento->nombre) ?></p>
    <?php endif; ?>
    <br>
    <p><strong>Token de verificación:</strong></p>
    <div style="text-align:center;margin:20px 0">
        <span style="font-size:28px;font-weight:bold;letter-spacing:8px;padding:12px 32px;border:2px dashed #28a745;border-radius:6px;background:#fff">
            <?= h($docente->token) ?>
        </span>
    </div>
</div>
<br>
<p>Utilice este token en el portal de registro: <a href="https://www.uptbal.info.ve/registro?usuario=COEDOC">https://www.uptbal.info.ve/registro?usuario=COEDOC</a></p>
<br>
<p>Si no solicitó este registro, por favor comuníquese con la coordinación académica.</p>
<p>Atentamente,<br><strong>Coordinación Académica - SACE</strong></p>
