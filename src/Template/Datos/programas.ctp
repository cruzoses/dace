<?php 
/**
 * @var int $estudianteId
*/
?>
<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-cogs"></i>&nbsp;Programas Asignados</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                <i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool btn-cerrar-ajax">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover table-striped table-condensed">
            <thead>
                <tr>
                    <th>Carrera</th>
                    <th>Programa</th>
                    <th>Sede</th>
                    <th class="text-center">Fecha Ingreso</th>
                    <th class="text-center">Fecha Egreso</th>
                    <th class="text-center">Culminado</th>
                    <th class="text-center">Activo</th>
                    <th class="text-center">Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($programas)): ?>
                    <?php foreach ($programas as $key): ?>
                        <tr>
                            <td><?= h($key->carrera->codigo) ?></td>
                            <td>
                                <?php if ($key->programa->califica): ?>
                                    <?= $this->Html->link(h($key->programa->codigo),
                                        ['action' => 'situacion', $estudianteId, $key->programa_id],
                                        ['class' => 'btn bg-purple btn-xs btn-link btn-situacion', 'title' => 'Ver situación académica']) ?>
                                <?php else: ?>
                                    <?= h($key->programa->codigo) ?>
                                <?php endif; ?>
                            </td>
                            <td><?= h($key->sede->nombre) ?></td>
                            <td class="text-center"><?= $key->created->format('d-m-Y') ?></td>
                            <td class="text-center"><?= $key->fecha_egreso ? $key->fecha_egreso->format('d-m-Y') : '' ?></td>
                            <td class="text-center"><?= $key->culminado ? 'Sí' : 'No' ?></td>
                            <td class="text-center"><?= $key->activo ? 'Sí' : 'No' ?></td>
                            <td class="actions text-center">
                                <?= $this->Html->link('<i class="fa fa-edit"></i>',
                                    ['controller' => 'EstudianteProgramas', 'action' => 'edit', $key->id],
                                    ['class' => 'btn btn-xs btn-warning btn-abrir-modal', 'escape' => false, 'title' => 'Editar']) 
                                ?>
                                <?= $this->Form->postLink('<i class="fa fa-trash"></i>',
                                    ['controller' => 'EstudianteProgramas','action' => 'eliminar', $key->id], 
                                    ['confirm' => __('Are you sure you want to delete # {0}?', $key->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) 
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">No hay programas asignados a este estudiante.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="box-footer">
        <?= $this->Html->link('<i class="fa fa-plus"></i>&nbsp;Registrar Programa',
            ['controller' => 'EstudianteProgramas', 'action' => 'add', $estudianteId],
            ['class' => 'btn bg-olive btn-abrir-modal', 'escape' => false]) ?>
    </div>
</div>

<div class="modal fade" id="modal-programa" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <i class="fa fa-refresh fa-spin fa-3x"></i>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $(document).on('click', '.btn-situacion', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $('#ajax-content').html('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
        $('#ajax-content').removeClass('oculto').show();
        $('#information').hide();

        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                $('#ajax-content').html(response);
            },
            error: function() {
                $('#ajax-content').html('<div class="alert alert-danger">Error al cargar la situaci\u00f3n acad\u00e9mica.</div>');
            }
        });
    });

    $(document).on('click', '.btn-abrir-modal', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $('#modal-programa .modal-body').html('<div class="text-center"><i class="fa fa-refresh fa-spin fa-3x"></i></div>');
        $('#modal-programa').modal('show');

        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                $('#modal-programa .modal-body').html(response);
            },
            error: function() {
                $('#modal-programa .modal-body').html('<div class="alert alert-danger">Error al cargar el formulario.</div>');
            }
        });
    });

    $(document).on('submit', '#add-programa-form, #edit-programa-form', function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');

        $.ajax({
            url: url,
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#modal-programa').modal('hide');
                    toastr.success(response.message);
                    $('#btnProgramas').trigger('click');
                } else {
                    var errorMsg = response.message || 'Error al guardar';
                    if (response.errors) {
                        $.each(response.errors, function(field, msgs) {
                            if (typeof msgs === 'object') {
                                $.each(msgs, function(key, msg) {
                                    errorMsg += '<br>' + msg;
                                });
                            } else {
                                errorMsg += '<br>' + msgs;
                            }
                        });
                    }
                    form.prepend('<div class="alert alert-danger">' + errorMsg + '</div>');
                }
            },
            error: function() {
                form.prepend('<div class="alert alert-danger">Error de conexión. Intente de nuevo.</div>');
            }
        });
    });
});
</script>