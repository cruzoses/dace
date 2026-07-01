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
<p>Estimado(a) <strong><?= h($empleado->full_name) ?></strong>,</p>
<p>Ha sido registrado en el sistema <strong>SACE UPTBAL</strong> como personal administrativo/obrero de la institución.</p>
<p>A continuación se muestra su código de verificación para completar el registro de usuario:</p>
<br>
<div style="margin:20px 40px;padding:20px;border:1px solid #ccc;border-radius:8px;background:#f9f9f9">
    <p><strong>Cédula:</strong> <?= h($empleado->cedula) ?></p>
    <p><strong>Correo:</strong> <?= h($empleado->email) ?></p>
    <br>
    <p><strong>Token de registro:</strong></p>
    <div style="text-align:center;margin:20px 0">
        <span style="font-size:28px;font-weight:bold;letter-spacing:8px;padding:12px 32px;border:2px dashed #007bff;border-radius:6px;background:#fff">
            <?= h($empleado->token) ?>
        </span>
    </div>
</div>
<br>
<p>Acceda al siguiente enlace para completar su registro: <a href="https://www.uptbal.info.ve/registro?usuario=COESTU">https://www.uptbal.info.ve/registro?usuario=COESTU</a></p>
<br>
<p>Si tiene alguna duda, puede presentarse en la oficina de recursos humanos para recibir asistencia.</p>
<p>Atentamente,<br><strong>Administración SACE</strong></p>
