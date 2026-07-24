<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Estudiante $estudiante
 * @var array $programas
 * @var array $inscripciones
 */
?>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-graduation-cap"></i>&nbsp;Inscripciones — <?= h($estudiante->full_name) ?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool btn-cerrar-ajax"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <div class="box-body">

        <div class="row">
            <div class="col-md-12">
                <div class="box box-success box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Programas Asignados</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-striped table-condensed">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th>Código</th>
                                    <th>Programa</th>
                                    <th>Expediente</th>
                                    <th class="text-center">Fecha Ingreso</th>
                                    <th class="text-center">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($programas)): ?>
                                    <?php foreach ($programas as $i => $prog): ?>
                                        <tr>
                                            <td class="text-center"><?= ($i + 1) ?></td>
                                            <td><?= h($prog->programa->codigo) ?></td>
                                            <td><?= h($prog->programa->nombre) ?></td>
                                            <td><?= h($prog->estudiante->expediente_formateado) ?></td>
                                            <td class="text-center"><?= $prog->created->format('d/m/Y') ?></td>
                                            <td class="text-center">
                                                <?= $this->Html->link('<i class="fa fa-plus-circle"></i>&nbsp;Inscribir',
                                                    ['action' => 'inscribir', $estudiante->id, $prog->carrera_id],
                                                    [
                                                        'class' => 'btn btn-success btn-xs btn-inscribir-programa',
                                                        'escape' => false,
                                                        'title' => 'Inscribir en cursos de esta carrera',
                                                    ]
                                                ) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No hay programas asignados a este estudiante.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-list"></i>&nbsp;Cursos Inscritos (<?= count($inscripciones) ?>)</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-danger btn-xs" id="btn-eliminar-seleccionados" style="display:none;">
                                <i class="fa fa-trash"></i>&nbsp;Eliminar seleccionados (<span id="count-seleccionados">0</span>)
                            </button>
                        </div>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-condensed" id="tabla-inscripciones">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:30px;">
                                        <input type="checkbox" id="check-todos" title="Seleccionar todos">
                                    </th>
                                    <th class="text-center">Curso</th>
                                    <th>Carrera</th>
                                    <th>Trayecto</th>
                                    <th class="text-center">Sección</th>
                                    <th>Asignatura</th>
                                    <th class="text-center">Fecha</th>
                                    <th>Responsable</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($inscripciones)): ?>
                                    <?php foreach ($inscripciones as $ins): ?>
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" class="check-inscripcion" value="<?= $ins->id ?>">
                                            </td>
                                            <td class="text-center"><?= $ins->curso->id ?></td>
                                            <td><?= $ins->curso->has('carrera') ? h($ins->curso->carrera->codigo) : '' ?></td>
                                            <td><?= $ins->curso->has('trayecto') ? h($ins->curso->trayecto->codigo) : '' ?></td>
                                            <td class="text-center"><?= h($ins->curso->seccion) ?></td>
                                            <td><?= $ins->curso->has('asignatura') ? h($ins->curso->asignatura->codename) : '' ?></td>
                                            <td class="text-center"><?= $ins->created->format('d/m/Y') ?></td>
                                            <td><?= h($ins->responsable) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No hay cursos inscritos para este estudiante.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="modal-inscripcion" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <i class="fa fa-refresh fa-spin fa-3x"></i>
            </div>
        </div>
    </div>
</div>

<script>
var EC_URLS = {
    index: '<?= $this->Url->build(["controller" => "EstudianteCursos", "action" => "index"]) ?>',
    getCursos: '<?= $this->Url->build(["controller" => "EstudianteCursos", "action" => "getCursos"]) ?>',
    inscribirCurso: '<?= $this->Url->build(["controller" => "EstudianteCursos", "action" => "inscribirCurso"]) ?>',
    eliminar: '<?= $this->Url->build(["controller" => "EstudianteCursos", "action" => "eliminar"]) ?>',
    eliminarSeleccionados: '<?= $this->Url->build(["controller" => "EstudianteCursos", "action" => "eliminarSeleccionados"]) ?>'
};

function recargarPagina(estudianteId) {
    $.ajax({
        url: EC_URLS.index + '/' + estudianteId,
        type: 'GET',
        success: function(html) { $('#ajax-content').html(html); }
    });
}

function actualizarBotonEliminar() {
    var total = $('.check-inscripcion:checked').length;
    if (total > 0) {
        $('#btn-eliminar-seleccionados').show();
        $('#count-seleccionados').text(total);
    } else {
        $('#btn-eliminar-seleccionados').hide();
    }
}

$(document).ready(function() {
    $('#check-todos').off('change.insc').on('change.insc', function() {
        $('.check-inscripcion').prop('checked', $(this).prop('checked'));
        actualizarBotonEliminar();
    });

    $(document).off('change.checkinsc').on('change.checkinsc', '.check-inscripcion', function() {
        var total = $('.check-inscripcion').length;
        var marcados = $('.check-inscripcion:checked').length;
        $('#check-todos').prop('checked', total > 0 && marcados === total);
        actualizarBotonEliminar();
    });

    $(document).off('click.inscribirprog').on('click.inscribirprog', '.btn-inscribir-programa', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $('#modal-inscripcion .modal-body').html('<div class="text-center"><i class="fa fa-refresh fa-spin fa-3x"></i></div>');
        $('#modal-inscripcion').modal('show');

        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                $('#modal-inscripcion .modal-body').html(response);
                var $modal = $('#modal-inscripcion');
                $modal.find('.select2').select2({ width: '100%', dropdownParent: $modal });
                $modal.find('#sel-periodo').off('change.selperiodo select2:select.selperiodo').on('change.selperiodo select2:select.selperiodo', function(e) {
                    var periodoId = $(this).val();
                    var carreraId = $('#hid-carrera-id').val();
                    var estudianteId = $('#hid-estudiante-id').val();
                    var $tabla = $('#tbl-cursos-disponibles tbody');

                    if (!periodoId) {
                        $tabla.html('<tr><td colspan="6" class="text-center text-muted">Seleccione un período.</td></tr>');
                        return;
                    }

                    $.ajax({
                        url: EC_URLS.getCursos,
                        type: 'GET',
                        data: { periodo_id: periodoId, carrera_id: carreraId, estudiante_id: estudianteId },
                        dataType: 'json',
                        beforeSend: function() {
                            $tabla.html('<tr><td colspan="6" class="text-center"><i class="fa fa-spinner fa-spin"></i> Cargando...</td></tr>');
                        },
                        success: function(resp) {
                            $tabla.empty();
                            if (resp.cursos.length === 0) {
                                $tabla.html('<tr><td colspan="6" class="text-center text-muted">No hay cursos disponibles.</td></tr>');
                                return;
                            }
                            $.each(resp.cursos, function(i, c) {
                                var cls = c.disponibles > 0 ? '' : ' class="text-muted"';
                                var disabled = c.disponibles > 0 ? '' : ' disabled';
                                $tabla.append(
                                    '<tr' + cls + '>' +
                                    '<td class="text-center"><input type="checkbox" class="check-curso" value="' + c.id + '"' + disabled + '></td>' +
                                    '<td class="text-center">' + c.id + '</td>' +
                                    '<td>' + c.trayecto + '</td>' +
                                    '<td class="text-center">' + c.seccion + '</td>' +
                                    '<td class="text-left">' + c.asignatura + '</td>' +
                                    '<td class="text-center"><span class="' + (c.disponibles > 0 ? 'text-green' : 'text-red') + '">' + c.disponibles + '/' + c.cupos + '</span></td>' +
                                    '</tr>'
                                );
                            });
                        },
                        error: function() {
                            $tabla.html('<tr><td colspan="6" class="text-center text-red"><i class="fa fa-exclamation-triangle"></i> Error al cargar cursos.</td></tr>');
                        }
                    });
                });
            },
            error: function() {
                $('#modal-inscripcion .modal-body').html('<div class="alert alert-danger">Error al cargar el formulario.</div>');
            }
        });
    });

    $(document).off('click.inscribirbtn').on('click.inscribirbtn', '#btn-inscribir-cursos', function() {
        $('#form-inscribir').submit();
    });

    $(document).off('change.checkcurso').on('change.checkcurso', '#modal-inscripcion .check-curso', function() {
        var total = $('#modal-inscripcion .check-curso').not(':disabled').length;
        var marcados = $('#modal-inscripcion .check-curso:checked').length;
        $('#modal-inscripcion #check-todos-cursos').prop('checked', total > 0 && marcados === total);
    });

    $(document).off('change.checktodocursos').on('change.checktodocursos', '#modal-inscripcion #check-todos-cursos', function() {
        $('#modal-inscripcion .check-curso').not(':disabled').prop('checked', $(this).prop('checked'));
    });

    $(document).off('submit.inscribir').on('submit.inscribir', '#form-inscribir', function(e) {
        e.preventDefault();
        var form = $(this);
        var cursos = [];
        $('#modal-inscripcion .check-curso:checked').each(function() {
            cursos.push($(this).val());
        });

        if (cursos.length === 0) {
            form.find('.alert').remove();
            form.find('.modal-body').prepend('<div class="alert alert-danger">Seleccione al menos un curso.</div>');
            return;
        }

        var postData = {
            estudiante_id: form.find('[name="estudiante_id"]').val(),
            curso_id: cursos
        };

        $.ajax({
            url: EC_URLS.inscribirCurso,
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function(resp) {
                if (resp.success) {
                    $('#modal-inscripcion').modal('hide');
                    toastr.success(resp.message);
                    recargarPagina(postData.estudiante_id);
                } else {
                    toastr.error(resp.message);
                }
            },
            error: function() {
                toastr.error('Error de conexión.');
            }
        });
    });

    $(document).off('click.elimsel').on('click.elimsel', '#btn-eliminar-seleccionados', function() {
        var ids = [];
        $('.check-inscripcion:checked').each(function() { ids.push($(this).val()); });

        if (ids.length === 0) return;

        if (!confirm('\u00bfEst\u00e1 seguro de eliminar ' + ids.length + ' inscripci\u00f3n(es)?')) return;

        $.ajax({
            url: EC_URLS.eliminarSeleccionados,
            type: 'POST',
            data: { ids: ids },
            dataType: 'json',
            success: function(resp) {
                if (resp.success) {
                    toastr.success(resp.message);
                    recargarPagina('<?= $estudiante->id ?>');
                } else {
                    toastr.error(resp.message);
                }
            }
        });
    });
});
</script>
