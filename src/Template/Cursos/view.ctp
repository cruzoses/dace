<div class="row">
	<div class="col-md-12">
		<div class="box box-sace box-solid">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-info"></i>&nbsp;Curso</h3>
				<div class="box-tools pull-right">
			        <button type="button" class="btn btn-box-tool" data-widget="collapse">
				        <i class="fa fa-minus"></i>
			        </button>
			        <?= $this->Html->link('<i class="fa fa-times"></i>',
				        ['action' => 'index'],['class'=>'btn btn-box-tool','title'=>'cerrar','escape'=>false]);
			        ?>
                </div>
            </div>

<div class="modal fade" id="modal-registrar-participantes" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
(function(){
    var BASE_URL = <?= json_encode($this->Url->build('/')) ?>;
    var cursoId = <?= json_encode($curso->id) ?>;

    $(document).off('click.rpabrir').on('click.rpabrir', '#btn-registrar-participantes', function(e) {
        e.preventDefault();
        var $modal = $('#modal-registrar-participantes');
        $modal.find('.modal-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i></div>');
        $modal.modal('show');

        $.ajax({
            url: BASE_URL + 'estudiante-cursos/registrar-participantes/' + cursoId,
            type: 'GET',
            success: function(response) {
                $modal.find('.modal-body').html(response);
            },
            error: function() {
                $modal.find('.modal-body').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Error al cargar el formulario.</div>');
            }
        });
    });
})();
</script>
        	<div class="box-body">
          		<dl class="dl-horizontal">
                    <dt scope="row"><?= __('Sede') ?></dt>
                    <dd><?= $curso->has('sede') ? $this->Html->link($curso->sede->codename, ['controller' => 'Sedes', 'action' => 'view', $curso->sede->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Periodo') ?></dt>
                    <dd><?= $curso->has('periodo') ? $this->Html->link($curso->periodo->codename, ['controller' => 'Periodos', 'action' => 'view', $curso->periodo->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Carrera') ?></dt>
                    <dd><?= $curso->has('carrera') ? $this->Html->link($curso->carrera->nombre, ['controller' => 'Carreras', 'action' => 'view', $curso->carrera->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Programas') ?></dt>
                    <dd><?= h($curso->programas) ?></dd>
                    <dt scope="row"><?= __('Trayecto') ?></dt>
                    <dd><?= $curso->has('trayecto') ? $this->Html->link($curso->trayecto->codename, ['controller' => 'Trayectos', 'action' => 'view', $curso->trayecto->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Asignatura') ?></dt>
                    <dd><?= $curso->has('asignatura') ? $this->Html->link($curso->asignatura->codename, ['controller' => 'Asignaturas', 'action' => 'view', $curso->asignatura->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Profesores') ?></dt>
                    <dd><?= h($curso->profesores) ?></dd>
                    <dt scope="row"><?= __('Docente') ?></dt>
                    <dd><?= $curso->has('docente') ? $this->Html->link($curso->docente->codename, ['controller' => 'Docentes', 'action' => 'view', $curso->docente->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Seccion') ?></dt>
                    <dd><?= h($curso->seccion) ?></dd>
                    <dt scope="row"><?= __('Aula') ?></dt>
                    <dd><?= $curso->has('aula') ? $this->Html->link($curso->aula->nombre, ['controller' => 'Aulas', 'action' => 'view', $curso->aula->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Horario') ?></dt>
                    <dd><?= h($curso->horario) ?></dd>
                    <dt scope="row"><?= __('Id') ?></dt>
                    <dd><?= $this->Number->format($curso->id) ?></dd>
                    <dt scope="row"><?= __('Cupos') ?></dt>
                    <dd><?= $this->Number->format($curso->cupos) ?></dd>
                    <dt scope="row"><?= __('Created') ?></dt>
                    <dd><?= h($curso->created) ?></dd>
                    <dt scope="row"><?= __('Modified') ?></dt>
                    <dd><?= h($curso->modified) ?></dd>
                    <dt scope="row"><?= __('Activo') ?></dt>
                    <dd><?= $curso->activo ? __('Yes') : __('No'); ?></dd>
                </dl>
            </div>
            <div class="box-footer">
		        <?= $this->Html->link('<i class="fa fa-pencil"></i>&nbsp;'.__('Edit'),
			        ['action' => 'edit',$curso->id],['class' => 'btn bg-olive btn-flat pull-left','escape' => false]); 
		        ?>
		        <?= $this->Html->link('<i class="fa fa-power-off"></i>&nbsp;Cerrar',
			        ['action' => 'index'],['class' => 'btn bg-maroon btn-flat pull-right','escape' => false]); 
		        ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box box-default box-solid">
            <div class="box-header with-border">                
                <h3 class="box-title"><i class="fa fa-users"></i>&nbsp;Estudiantes Inscritos (<?= count($curso->estudiante_cursos) ?>)</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <?php if (!empty($curso->estudiante_cursos)): ?>
                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th>Cédula</th>
                                <th>Estudiante</th>
                                <th class="text-center">Calificación</th>
                                <th class="text-center">Recuperación</th>
                                <th class="text-center">Definitiva</th>
                                <th>Responsable</th>
                                <th>Observación</th>
                                <th class="text-center">Activo</th>
                                <th class="text-center">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($curso->estudiante_cursos as $i => $ec): ?>
                                <tr>
                                    <td class="text-center"><?= ($i + 1) ?></td>
                                    <td><?= $ec->has('estudiante') ? $this->Number->format($ec->estudiante->cedula) : h($ec->estudiante_id) ?></td>
                                    <td>
                                        <?= $ec->has('estudiante')
                                            ? $this->Html->link($ec->estudiante->full_name, ['controller' => 'Datos', 'action' => 'estudiante', $ec->estudiante->id])
                                            : h($ec->estudiante_id) ?>
                                    </td>
                                    <td class="text-center"><?= h($ec->calificacion ?? '') ?></td>
                                    <td class="text-center"><?= h($ec->recuperacion ?? '') ?></td>
                                    <td class="text-center"><?= h($ec->definitiva ?? '') ?></td>
                                    <td><?= h($ec->responsable) ?></td>
                                    <td><?= h($ec->observacion ?? '') ?></td>
                                    <td class="text-center"><?= $ec->activo ? 'Sí' : 'No' ?></td>
                                    <td class="actions text-center">
                                        <?= $this->Form->postLink('<i class="fa fa-trash"></i>',
                                            ['controller' => 'EstudianteCursos', 'action' => 'eliminar', $ec->id],
                                            ['confirm' => '¿Está seguro de eliminar esta inscripción?', 'class' => 'btn btn-danger btn-xs', 'escape' => false])
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-center text-muted">No hay estudiantes inscritos en este curso.</p>
                <?php endif; ?>
            </div>
            <div class="box-footer">
                <?= $this->Html->link('<i class="fa fa-users"></i>&nbsp;Registrar Participantes',
                    ['#'], ['id' => 'btn-registrar-participantes', 'class' => 'btn btn-primary btn-flat pull-right', 'escape' => false])
                ?>
                <span class="text-muted" style="margin-left:15px;"><i class="fa fa-info-circle"></i> Para inscribir estudiantes individualmente, utilice la vista del estudiante &rarr; Inscripciones.</span>
            </div>            
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box box-default box-solid">
            <div class="box-header with-border">                
                <h3 class="box-title"><i class="fa fa-share-alt"></i>&nbsp;Indicador Cursos</h3>
            </div>
            <div class="box-body">
                <?php if (!empty($curso->indicador_cursos)): ?>
                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th scope="col"><?= __('Id') ?></th>
                                <th scope="col"><?= __('Curso Id') ?></th>
                                <th scope="col"><?= __('Indicador Id') ?></th>
                                <th scope="col"><?= __('Desde') ?></th>
                                <th scope="col"><?= __('Hasta') ?></th>
                                <th scope="col"><?= __('Escala Nota') ?></th>
                                <th scope="col"><?= __('Created') ?></th>
                                <th scope="col"><?= __('Modified') ?></th>
                                <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($curso->indicador_cursos as $indicadorCursos): ?>
                                <tr>
                                    <td><?= h($indicadorCursos->id) ?></td>
                                    <td><?= h($indicadorCursos->curso_id) ?></td>
                                    <td><?= h($indicadorCursos->indicador_id) ?></td>
                                    <td><?= h($indicadorCursos->desde) ?></td>
                                    <td><?= h($indicadorCursos->hasta) ?></td>
                                    <td><?= h($indicadorCursos->escala_nota) ?></td>
                                    <td><?= h($indicadorCursos->created) ?></td>
                                    <td><?= h($indicadorCursos->modified) ?></td>
                                    <td class="actions text-center">
                                        <?= $this->Html->link('<i class="fa fa-eye"></i>', ['controller' => 'IndicadorCursos', 'action' => 'view', $indicadorCursos->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                        <?= $this->Html->link('<i class="fa fa-pencil"></i>', ['controller' => 'IndicadorCursos', 'action' => 'edit', $indicadorCursos->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                        <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['controller' => 'IndicadorCursos', 'action' => 'delete', $indicadorCursos->id], ['confirm' => __('Are you sure you want to delete # {0}?', $indicadorCursos->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            <div class="box-footer"></div>            
        </div>
    </div>
</div>

<div class="modal fade" id="modal-registrar-participantes" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
(function(){
    var BASE_URL = <?= json_encode($this->Url->build('/')) ?>;
    var cursoId = <?= json_encode($curso->id) ?>;
    var rpCursoCupos = 0;
    var rpCursoInscritos = <?= json_encode(count($curso->estudiante_cursos)) ?>;
    var rpFaltantes = 0;

    $(document).off('click.rpabrir').on('click.rpabrir', '#btn-registrar-participantes', function(e) {
        e.preventDefault();
        var $modal = $('#modal-registrar-participantes');
        $modal.find('.modal-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i></div>');
        $modal.modal('show');

        $.ajax({
            url: BASE_URL + 'estudiante-cursos/registrar-participantes/' + cursoId,
            type: 'GET',
            success: function(response) {
                $modal.find('.modal-body').html(response);
                rpCursoCupos = parseInt($('#rp-curso-cupos').val()) || 0;
                rpFaltantes = rpCursoCupos - rpCursoInscritos;

                if (rpFaltantes <= 0) {
                    $('#rp-alerta-texto').text('Este curso ya no tiene cupos disponibles (' + rpCursoInscritos + '/' + rpCursoCupos + ').');
                    $('#rp-alerta').show();
                    $('#rp-btn-registrar').prop('disabled', true);
                }
            },
            error: function() {
                $modal.find('.modal-body').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Error al cargar el formulario.</div>');
            }
        });
    });

    $(document).off('change.rptrayecto').on('change.rptrayecto', '#rp-trayecto-origen', function() {
        $('#rp-btn-cargar-trayecto').prop('disabled', !$(this).val());
        $('#rp-resultado-trayecto').hide();
    });

    $(document).off('click.rpcargar').on('click.rpcargar', '#rp-btn-cargar-trayecto', function() {
        var trayectoId = $('#rp-trayecto-origen').val();
        if (!trayectoId) return;
        var $tbody = $('#tbl-rp-trayecto tbody');
        $tbody.html('<tr><td colspan="5" class="text-center"><i class="fa fa-spinner fa-spin"></i> Cargando...</td></tr>');
        $('#rp-resultado-trayecto').show();
        $('#rp-btn-registrar').prop('disabled', true);

        $.ajax({
            url: BASE_URL + 'estudiante-cursos/get-estudiantes-trayecto',
            type: 'GET',
            data: { curso_id: cursoId, trayecto_origen_id: trayectoId },
            dataType: 'json',
            success: function(resp) {
                $tbody.empty();
                if (resp.estudiantes.length === 0) {
                    $tbody.html('<tr><td colspan="5" class="text-center text-muted">No se encontraron estudiantes del trayecto anterior.</td></tr>');
                    return;
                }
                $.each(resp.estudiantes, function(i, e) {
                    var cls = e.tiene_programa ? '' : ' class="text-muted"';
                    var disabled = e.tiene_programa ? '' : ' disabled';
                    var estado = e.tiene_programa
                        ? '<span class="label label-success">OK</span>'
                        : '<span class="label label-warning">Sin programa</span>';
                    $tbody.append(
                        '<tr' + cls + '>' +
                        '<td class="text-center"><input type="checkbox" class="check-rp-trayecto" value="' + e.id + '"' + disabled + '></td>' +
                        '<td>' + e.cedula + '</td>' +
                        '<td>' + e.nombre + '</td>' +
                        '<td>' + e.expediente + '</td>' +
                        '<td class="text-center">' + estado + '</td>' +
                        '</tr>'
                    );
                });
                $('#rp-contador-trayecto').text(resp.estudiantes.length);
                rpActualizarBotonRegistrar();
            },
            error: function() {
                $tbody.html('<tr><td colspan="5" class="text-center text-red"><i class="fa fa-exclamation-triangle"></i> Error al cargar estudiantes.</td></tr>');
            }
        });
    });

    $(document).off('change.rparchivo').on('change.rparchivo', '#rp-archivo', function() {
        $('#rp-btn-procesar-excel').prop('disabled', !this.files.length);
        $('#rp-resultado-excel').hide();
    });

    $(document).off('click.rpprocesar').on('click.rpprocesar', '#rp-btn-procesar-excel', function() {
        var archivo = $('#rp-archivo')[0].files[0];
        if (!archivo) return;
        var fd = new FormData();
        fd.append('archivo', archivo);
        fd.append('curso_id', cursoId);
        var $tbody = $('#tbl-rp-excel tbody');
        $tbody.html('<tr><td colspan="6" class="text-center"><i class="fa fa-spinner fa-spin"></i> Procesando archivo...</td></tr>');
        $('#rp-resultado-excel').show();
        $('#rp-btn-registrar').prop('disabled', true);

        $.ajax({
            url: BASE_URL + 'estudiante-cursos/procesar-excel',
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(resp) {
                $tbody.empty();
                $('#rp-validos-contador').text(resp.total_validos);
                $('#rp-rechazados-contador').text(resp.total_rechazados);

                if (!resp.success) {
                    $tbody.html('<tr><td colspan="6" class="text-center text-red"><i class="fa fa-exclamation-triangle"></i> ' + resp.message + '</td></tr>');
                    return;
                }

                $.each(resp.validos, function(i, e) {
                    $tbody.append(
                        '<tr>' +
                        '<td class="text-center"><input type="checkbox" class="check-rp-excel" value="' + e.estudiante_id + '" checked></td>' +
                        '<td class="text-center">-</td>' +
                        '<td>' + e.cedula + '</td>' +
                        '<td>' + e.nombre + '</td>' +
                        '<td>' + e.expediente + '</td>' +
                        '<td class="text-center"><span class="label label-success">Valido</span></td>' +
                        '</tr>'
                    );
                });

                $.each(resp.rechazados, function(i, e) {
                    $tbody.append(
                        '<tr class="text-muted danger">' +
                        '<td class="text-center"><input type="checkbox" disabled></td>' +
                        '<td class="text-center">' + e.fila + '</td>' +
                        '<td>' + e.cedula + '</td>' +
                        '<td>' + e.nombre + '</td>' +
                        '<td colspan="2"><span class="text-red"><i class="fa fa-exclamation-circle"></i> ' + e.error + '</span></td>' +
                        '</tr>'
                    );
                });

                rpActualizarBotonRegistrar();
            },
            error: function() {
                $tbody.html('<tr><td colspan="6" class="text-center text-red"><i class="fa fa-exclamation-triangle"></i> Error al procesar el archivo.</td></tr>');
            }
        });
    });

    $(document).off('change.rpcktrayecto').on('change.rpcktrayecto', '#rp-check-todos-trayecto', function() {
        $('#tbl-rp-trayecto .check-rp-trayecto').not(':disabled').prop('checked', $(this).prop('checked'));
        rpActualizarBotonRegistrar();
    });

    $(document).off('change.rpckexcel').on('change.rpckexcel', '#rp-check-todos-excel', function() {
        $('#tbl-rp-excel .check-rp-excel').not(':disabled').prop('checked', $(this).prop('checked'));
        rpActualizarBotonRegistrar();
    });

    $(document).off('change.rpckfila').on('change.rpckfila', '.check-rp-trayecto, .check-rp-excel', function() {
        var $tabla = $(this).closest('table');
        var total = $tabla.find('.check-rp-trayecto:not(:disabled), .check-rp-excel:not(:disabled)').length;
        var marcados = $tabla.find('.check-rp-trayecto:checked, .check-rp-excel:checked').length;
        var ckAll = $tabla.closest('.tab-pane').find('input[type="checkbox"][id^="rp-check-todos"]');
        ckAll.prop('checked', total > 0 && marcados === total);
        rpActualizarBotonRegistrar();
    });

    function rpActualizarBotonRegistrar() {
        var count = $('.check-rp-trayecto:checked, .check-rp-excel:checked').length;
        $('#rp-btn-registrar').prop('disabled', count === 0 || rpFaltantes <= 0);
    }

    $(document).off('click.rpregistrar').on('click.rpregistrar', '#rp-btn-registrar', function() {
        var ids = [];
        $('.check-rp-trayecto:checked, .check-rp-excel:checked').each(function() {
            ids.push($(this).val());
        });

        if (ids.length === 0) return;

        if (ids.length > rpFaltantes) {
            $('#rp-alerta-texto').text('Solo quedan ' + rpFaltantes + ' cupo(s) disponible(s). Selecciono ' + ids.length + ' estudiante(s).');
            $('#rp-alerta').show();
            return;
        }

        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>&nbsp;Registrando...');
        $('#rp-alerta').hide();

        $.ajax({
            url: BASE_URL + 'estudiante-cursos/registrar-lote',
            type: 'POST',
            data: { curso_id: cursoId, estudiante_ids: ids },
            dataType: 'json',
            success: function(resp) {
                if (resp.success) {
                    $('#modal-registrar-participantes').modal('hide');
                    window.location.href = BASE_URL + 'cursos/view/' + cursoId;
                } else {
                    $('#rp-alerta-texto').text(resp.message);
                    $('#rp-alerta').show();
                    $btn.prop('disabled', false).html('<i class="fa fa-save"></i>&nbsp;Registrar Participantes');
                }
            },
            error: function() {
                $('#rp-alerta-texto').text('Error de conexion al registrar.');
                $('#rp-alerta').show();
                $btn.prop('disabled', false).html('<i class="fa fa-save"></i>&nbsp;Registrar Participantes');
            }
        });
    });
})();
</script>

