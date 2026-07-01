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
<p>Bienvenido(a) al <strong>SACE UPTBAL</strong>. Su registro como estudiante ha sido procesado exitosamente.</p>
<p>A continuación se muestran sus datos de inscripción y el token de verificación para acceder al sistema:</p>
<br>
<div style="margin:20px 40px;padding:20px;border:1px solid #ccc;border-radius:8px;background:#f9f9f9">
    <p><strong>Cédula:</strong> <?= h($estudiante->cedula) ?></p>
    <p><strong>Correo electrónico:</strong> <?= h($estudiante->email) ?></p>
    <?php if (!empty($estudiante->telefonos)): ?>
    <p><strong>Teléfono:</strong> <?= h($estudiante->telefonos) ?></p>
    <?php endif; ?>
    <br>
    <p><strong>Token de acceso:</strong></p>
    <div style="text-align:center;margin:20px 0">
        <span style="font-size:28px;font-weight:bold;letter-spacing:8px;padding:12px 32px;border:2px dashed #ffc107;border-radius:6px;background:#fff">
            <?= h($estudiante->token) ?>
        </span>
    </div>
</div>
<br>
<p>Ingrese al sistema usando el siguiente enlace: <a href="https://www.uptbal.info.ve/registro?usuario=COEEST">https://www.uptbal.info.ve/registro?usuario=COEEST</a></p>
<br>
<p>Conserve este token, ya que le será solicitado durante el proceso de inscripción y consultas académicas.</p>
<p>Atentamente,<br><strong>Control de Estudios - SACE</strong></p>
