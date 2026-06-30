<?= Configure::read('Universidad.Siglas') ?>
<?= Configure::read('Universidad.RIF') ?>


Estimado(a) <?= $empleado->nombres ?> <?= $empleado->apellidos ?>,

A continuación, te entregamos las credenciales para que puedas completar el registro de usuario:

    Token de Registro: <?= $empleado->token ?>

Accede a través del siguiente enlace:
https://www.uptbal.info.ve/registro?usuario=COESTU

Puede presentarse en la oficina correspondiente para completar su registro o utilizar el token proporcionado.

Atentamente,
Administración SACE
