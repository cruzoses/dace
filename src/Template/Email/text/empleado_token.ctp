Estimado(a) <?= $empleado->nombres ?> <?= $empleado->apellidos ?>,

Usted ha sido registrado en el sistema DACE.

Para crear su cuenta de usuario, utilice el siguiente token de verificación:

    <?= $empleado->token ?>

Puede presentarse en la oficina correspondiente para completar su registro o utilizar el token proporcionado.

Atentamente,
Administración DACE
