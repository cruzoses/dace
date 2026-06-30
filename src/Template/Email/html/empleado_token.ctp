<h3>Registro Exitoso</h3>
<p>Estimado(a) <strong><?= h($empleado->nombres) ?> <?= h($empleado->apellidos) ?></strong>,</p>
<p>Usted ha sido registrado en el sistema <strong>DACE</strong>.</p>
<p>Para crear su cuenta de usuario, utilice el siguiente token de verificación:</p>
<div style="text-align:center;margin:30px 0">
    <span style="font-size:24px;font-weight:bold;letter-spacing:6px;padding:12px 24px;border:2px dashed #333;border-radius:6px;background:#f9f9f9"><?= h($empleado->token) ?></span>
</div>
<p>Puede presentarse en la oficina correspondiente para completar su registro o utilizar el token proporcionado.</p>
<p>Atentamente,<br><strong>Administración DACE</strong></p>
