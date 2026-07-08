<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Docente $docente
*/
use Cake\Core\Configure;
?>
<div class="content">
    <div class="row">
        <?= $this->element('Profesores/ficha', ['docente' => $docente, 'showOptions' => true]); ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary" id="information">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-bordered table-condensed table-striped">
                        <tr>
                            <td class="table-sub-title" colspan="2">Datos Personales del Docente</td>
                        </tr>
                        <tr>
                            <th class="bg-gray text-center col-lg-3">Dato</th>
                            <th class="bg-gray text-center">Información</th>
                        </tr>
                        <tr>
                            <td class="text-left">No. de Id</td>
                            <td class="text-left"><?= $this->Number->format($docente->id) ?></td>
                        </tr>
                        <tr>
                            <td class="text-left">Cédula</td>
                            <td class="text-left"><?= $this->Number->format($docente->cedula) ?></td>
                        </tr>
                        <tr>
                            <td class="text-left">Apellidos</td>
                            <td class="text-left"><?= h($docente->apellidos) ?></td>
                        </tr>
                        <tr>
                            <td class="text-left">Nombres</td>
                            <td class="text-left"><?= h($docente->nombres) ?></td>
                        </tr>
                        <tr>
                            <td class="text-left">Sexo</td>
                            <td class="text-left"><?= Configure::read('aGeneros')[$docente->sexo] ?></td>
                        </tr>
                        <tr>
                            <td class="text-left">Fecha Nacimiento</td>
                            <td class="text-left"><?= h($docente->fecha_nacimiento) ?></td>
                        </tr>
                        <tr>
                            <td class="text-left">Departamento</td>
                            <td class="text-left"><?= $docente->has('departamento') ? h($docente->departamento->nombre) : '' ?></td>
                        </tr>
                        <tr>
                            <td class="text-left">Teléfono</td>
                            <td class="text-left"><?= h($docente->telefonos) ?></td>
                        </tr>
                        <tr>
                            <td class="text-left">Correo Electrónico</td>
                            <td class="text-left"><?= h($docente->email) ?></td>
                        </tr>
                    </table>
                </div>
                <div class="box-footer"></div>
            </div>
            <div class="box box-primary oculto" id="ajax-content">
                <div class="box-header"></div>
                <div class="box-footer"></div>
            </div>
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