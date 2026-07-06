<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Usuario $usuario
 * @var string $rolNombre
 * @var string $captchaId
 * @var array $aGeneros
*/
use Cake\Core\Configure;
?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fas fa-chalkboard-teacher"></i>&nbsp;Registro Docente</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                    <?= $this->Html->link('<i class="fa fa-times"></i>',
                        ['action' => 'homepage'], ['class'=>'btn btn-box-tool','escape' => false]) 
                    ?>
                </div>
            </div>
            <?= $this->Flash->render() ?>
            <?= $this->Form->create($usuario, [
                'role' => 'form',
                'align' => [
                    'sm' => ['left' => 6, 'middle' => 6, 'right' => 12],
                    'md' => ['left' => 2, 'middle' => 9, 'right' => 1]
                ],
                'class' => 'horizontal'
            ]) ?>
            <div class="box-body">
                <fieldset>
                    <!--
                    <legend class="text-primary" style="font-size:1.1em;border-bottom:1px solid #3c8dbc;">
                        <i class="fa fa-info-circle"></i>&nbsp;Tipo de Usuario
                    </legend>
                    -->
                    <?= $this->Form->control('rol_nombre', [
                        'type' => 'text',
                        'value' => $rolNombre,
                        'disabled' => true,
                        'label' => 'Tipo de Usuario',
                        'class' => 'form-control',
                        'prepend' => '<i class="fa fa-user"></i>',
                    ]) ?>
                </fieldset>

                <fieldset>
                    <!--
                    <legend class="text-primary" style="font-size:1.1em;border-bottom:1px solid #3c8dbc;margin-top:20px;">
                        <i class="fa fa-address-card"></i>&nbsp;Datos Personales
                    </legend>
                    -->
                    <h4 class="box-sub-title-info"><i class="fa fa-address-card"></i>&nbsp;Datos Personales</h4>
                    <?php
                        echo $this->Form->control('nombres', [
                            'class' => 'isUpper',
                            'prepend' => '<i class="fa fa-asterisk"></i>',
                        ]);
                        echo $this->Form->control('apellidos', [
                            'class' => 'isUpper',
                            'prepend' => '<i class="fa fa-asterisk"></i>',
                        ]);
                        echo $this->Form->control('fecha_nacimiento', [
                            'type' => 'text',
                            'class' => 'datepicker',
                            'prepend' => '<i class="fa fa-calendar"></i>',
                        ]);
                        echo $this->Form->control('sexo', [
                            'type' => 'select',
                            'options' => $aGeneros,
                            'class' => 'select2',
                            'prepend' => '<i class="fa fa-venus-mars"></i>',
                            'empty' => true,
                        ]);
                        echo $this->Form->control('telefonos', [
                            'label' => 'Teléfonos',
                            'prepend' => '<i class="fa fa-phone"></i>',
                        ]);
                        echo $this->Form->control('email', [
                            'class' => 'isLower',
                            'prepend' => '<i class="fa fa-envelope"></i>',
                        ]);
                    ?>
                </fieldset>

                <fieldset>
                    <!--
                    <legend class="text-primary" style="font-size:1.1em;border-bottom:1px solid #3c8dbc;margin-top:20px;">
                        <i class="fa fa-lock"></i>&nbsp;Cuenta de Usuario
                    </legend>
                    -->
                    <h4 class="box-sub-title-info"><i class="fa fa-lock"></i>&nbsp;Cuenta de Usuario</h4>
                    <?php
                        echo $this->Form->control('username', [
                            'label' => 'Usuario',
                            'prepend' => '<i class="fa fa-user-circle"></i>',
                        ]);
                        echo $this->Form->control('password', [
                            'label' => 'Contraseña',
                            'type' => 'password',
                            'prepend' => '<i class="fa fa-key"></i>',
                        ]);
                        echo $this->Form->control('password_confirmar', [
                            'label' => 'Repita Contraseña',
                            'type' => 'password',
                            'required' => true,
                            'prepend' => '<i class="fa fa-key"></i>',
                        ]);
                    ?>
                </fieldset>

                <fieldset>
                    <!--
                    <legend class="text-primary" style="font-size:1.1em;border-bottom:1px solid #3c8dbc;margin-top:20px;">
                        <i class="fa fa-clipboard"></i>&nbsp;Datos del Registro
                    </legend>
                    -->
                    <h4 class="box-sub-title-info"><i class="fa fa-clipboard"></i>&nbsp;Datos del Registro</h4>
                    <?php
                        echo $this->Form->control('cedula', [
                            'type' => 'text',
                            'label' => 'Número de Cédula',
                            'class' => 'isNumeric',
                            'prepend' => '<i class="fa fa-id-card"></i>',
                        ]);
                        echo $this->Form->control('token', [
                            'type' => 'text',
                            'label' => 'Clave de Registro',
                            'maxlength' => 10,
                            'prepend' => '<i class="fa fa-tag"></i>',
                        ]);
                    ?>
                    <hr>
                    <div class="dace-captcha-wrapper text-center">
                        <?= $this->Captcha->render([
                            'captcha_id' => $captchaId,
                            'input_text' => __('Escriba los caracteres de la imagen:'),
                            'input_attributes' => ['class' => 'form-control', 'placeholder' => ''],
                            'image_attributes' => ['style' => 'display:inline;vertical-align:middle;'],
                        ]) ?>
                    </div>
                </fieldset>
            </div>
            <div class="box-footer">
                <?= $this->Form->button('<i class="fa fa-save"></i>&nbsp;Registrarse',
                    ['type' => 'submit', 'class' => 'btn btn-success btn-flat pull-left', 'escape' => false])
                ?>
                <?= $this->Html->link('<i class="fa fa-sign-in-alt"></i>&nbsp;Ya tengo cuenta',
                    ['action' => 'login'],
                    ['class' => 'btn btn-primary btn-flat pull-right', 'escape' => false])
                ?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<?= $this->Html->script('login') ?>
<script>
    $(document).ready(function () {
        $('#cedula').focus();
    });
</script>
