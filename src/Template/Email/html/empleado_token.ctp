<div style="text-align:center">
    <p><strong><?= Configure::read('Universidad.Siglas') ?></strong></p>
    <p><?= Configure::read('Universidad.RIF') ?></p>
    <br>
    <img src="<?= $this->Url->build('/img/logos/logouptbal.png', ['fullBase' => true]) ?>" style="width:128px;height:128px" alt="Logo">
</div>
<br><br>
<p>Estimado(a) <strong><?= h($empleado->nombres) ?> <?= h($empleado->apellidos) ?></strong>,</p>
<p>A continuación, te entregamos las credenciales para que puedas completar el registro de usuario:</p>
<div style="text-align:center;margin:30px 0">
    <span style="font-size:24px;font-weight:bold;letter-spacing:6px;padding:12px 24px;border:2px dashed #333;border-radius:6px;background:#f9f9f9"><?= h($empleado->token) ?></span>
</div>
<p>Accede a través del siguiente enlace: <a href="https://www.uptbal.info.ve/registro?usuario=COESTU">https://www.uptbal.info.ve/registro?usuario=COESTU</a></p>
<p>Puede presentarse en la oficina correspondiente para completar su registro o utilizar el token proporcionado.</p>
<p>Atentamente,<br><strong>Administración SACE</strong></p>
