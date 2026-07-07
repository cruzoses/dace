<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Asignatura $estudiante
*/
use Cake\Core\Configure;
?>
<div class="content">
    <div class="row">
        <?= $this->element('Datos/ficha',['estudiante' => $estudiante, 'showOptions' => true]); ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary" id="information">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-bordered table-condensed table-striped">
                        <tr>
                            <td class="table-sub-title" colspan="2">Datos Personales del Estudiante</td>
                        </tr>
                        <tr>
                            <th class="bg-gray  text-center col-lg-3" style colspan="1">Dato</th>
                            <th class="bg-gray  text-center" style colspan="1">Informaci&oacute;n</th>
                        </tr>
                        <tr>
                            <td class=" text-left" style colspan="1">No. de Id</td>
                            <td class=" text-left" style colspan="1"><?= $this->Number->format($estudiante->id);?></td>
                        </tr>
                        <tr>
                            <td class=" text-left" style colspan="1">N&uacute;mero de Expediente</td>
                            <td class=" text-left" style colspan="1"><?= $estudiante->expediente_formateado ?? $estudiante->expediente ?></td>
                        </tr>
                        <tr>
                            <td class=" text-left" style colspan="1">N&uacute;mero de C&eacute;dula </td>
                            <td class=" text-left" style colspan="1"><?= $this->Number->format($estudiante->cedula);?></td>
                        </tr>
                        <tr>
                            <td class=" text-left" style colspan="1">Apellidos</td>
                            <td class=" text-left" style colspan="1"><?= $estudiante->apellidos;?></td>
                        </tr>
                        <tr>
                            <td class=" text-left" style colspan="1">Nombres</td>
                            <td class=" text-left" style colspan="1"><?= $estudiante->nombres;?></td>
                        </tr>
                        <tr>
                            <td class=" text-left" style colspan="1">Sexo</td>
                            <td class=" text-left" style colspan="1"><?= Configure::read('aGeneros')[$estudiante->sexo];?></td>
                        </tr>
                        <tr>
                            <td class=" text-left" style colspan="1">Fecha Nacimiento</td>
                            <td class=" text-left" style colspan="1"><?= $estudiante->fecha_nacimiento;?></td>
                        </tr>
                        <tr>
                            <td class=" text-left" style colspan="1">Discapacitado</td>
                            <td class=" text-left" style colspan="1"><?= $estudiante->discapacitado ? 'SI' : 'NO';?></td>
                        </tr>
                        <tr>
                            <td class=" text-left" style colspan="1">Tel&eacute;fono</td>
                            <td class=" text-left" style colspan="1"><?= $estudiante->telefonos;?></td>
                        </tr>
                        <tr>
                            <td class=" text-left" style colspan="1">Correo Electr&oacute;nico</td>
                            <td class=" text-left" style colspan="1"><?= $estudiante->email;?></td>
                        </tr>
                        <tr>
                            <td class=" text-left" style colspan="1">Direcci&oacute;n</td>
                            <td class=" text-left" style colspan="1"><?= $estudiante->direccion;?></td>
                        </tr>
                    </table>
                </div>
                <div class="box-footer"></div>
            </div>
            <div class="oculto" id="ajax-content"></div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.btnTools').on('click', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var btnId = $(this).attr('id');

        if (btnId === 'btnDatos') {
            $('#information').removeClass('oculto').show();
            $('#ajax-content').addClass('oculto').hide();
            return;
        }

        $('#information').hide();
        $('#ajax-content').removeClass('oculto').show();
        $('#ajax-content').html('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');

        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                $('#ajax-content').html(response);
            },
            error: function(xhr, status, error) {
                $('#ajax-content').html('<div class="alert alert-danger">Error al cargar: ' + error + '</div>');
                console.error(xhr.responseText);
            }
        });
    });

    $(document).on('click', '.btn-cerrar-ajax', function() {
        $('#ajax-content').addClass('oculto').hide();
        $('#information').removeClass('oculto').show();
    });
});
</script>
