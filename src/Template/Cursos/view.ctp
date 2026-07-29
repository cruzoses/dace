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
				        ['action' => 'index'],
                        ['class' => 'btn btn-box-tool', 'title' => 'cerrar', 'escape' => false]);
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
            
        	<div class="box-body table-responsive">
                <table id="Cursos" class="table table-bordered table-striped">
                    <tbody>
                        <dl class="dl-horizontal">
                            <dt scope="row"><?= __('No. de Id') ?></dt>
                            <dd><?= $this->Number->format($curso->id) ?></dd>
                            <dt scope="row"><?= __('Sede') ?></dt>
                            <dd><?= $curso->has('sede') ? h($curso->sede->codename) : '' ?></dd>
                            <dt scope="row"><?= __('Periodo') ?></dt>
                            <dd><?= $curso->has('periodo') ? h($curso->periodo->codename) : '' ?></dd>
                            <dt scope="row"><?= __('Carrera') ?></dt>
                            <dd><?= $curso->has('carrera') ? h($curso->carrera->nombre) : '' ?></dd>
                            <dt scope="row"><?= __('Programas') ?></dt>
                            <dd><?= h($curso->programas) ?></dd>
                            <dt scope="row"><?= __('Trayecto') ?></dt>
                            <dd><?= $curso->has('trayecto') ?h($curso->trayecto->codename) : '' ?></dd>
                            <dt scope="row"><?= __('Asignatura') ?></dt>
                            <dd><?= $curso->has('asignatura') ? h($curso->asignatura->codename) : '' ?></dd>
                            <dt scope="row"><?= __('Profesores') ?></dt>
                            <dd><?= h($curso->profesores) ?></dd>
                            <dt scope="row"><?= __('Docente') ?></dt>
                            <dd><?= $curso->has('docente') ? h($curso->docente->codename) : '' ?></dd>
                            <dt scope="row"><?= __('Seccion') ?></dt>
                            <dd><?= h($curso->seccion) ?></dd>
                            <dt scope="row"><?= __('Aula') ?></dt>
                            <dd><?= $curso->has('aula') ? h($curso->aula->nombre) : '' ?></dd>
                            <dt scope="row"><?= __('Horario') ?></dt>
                            <dd><?= h($curso->horario) ?></dd>
                            <dt scope="row"><?= __('Cupos') ?></dt>
                            <dd><?= $this->Number->format($curso->cupos) ?></dd>
                            <dt scope="row"><?= __('Cerrado') ?></dt>
                            <dd><?= $curso->cerrado ? __('Yes') : __('No'); ?></dd>
                            <dt scope="row"><?= __('Activo') ?></dt>
                            <dd><?= $curso->activo ? __('Yes') : __('No'); ?></dd>
                            <dt scope="row"><?= __('Created') ?></dt>
                            <dd><?= h($curso->created) ?></dd>
                            <dt scope="row"><?= __('Modified') ?></dt>
                            <dd><?= h($curso->modified) ?></dd>
                        </dl>
                    </tbody>
                </table>
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
                <h3 class="box-title"><i class="fa fa-users"></i>&nbsp;Estudiantes Inscritos (<?= $nTotalEstudiantes ?>)</h3>
                <?php if ($this->Permiso->tiene([1,2,3])) : ?>
                    <div class="box-title">
                        <?= $this->Html->link('C',
                            ['action' => 'calificar','?' => ['nCursoId' => $curso->id, 'sNota' => 'calificacion'] ],
                            ['class' => 'btn bg-purple btn-xs']);
                        ?>&nbsp;
                        <?= $this->Html->link('R',
                            ['action' => 'calificar','?' => ['nCursoId' => $curso->id, 'sNota' => 'recuperacion'] ],
                            ['class' => 'btn bg-olive btn-xs']);
                        ?>&nbsp;
                        <?= $this->Html->link('D',
                            ['action' => 'calificar','?' => ['nCursoId' => $curso->id, 'sNota' => 'definitiva'] ],
                            ['class' => 'btn bg-maroon btn-xs']);
                        ?>&nbsp;
                    </div>
                <?php endif; ?>                
                <div class="box-tools pull-right">
                    <div class="btn-group">
                        <?= $this->Html->link('<i class="fa fa-print"></i>&nbsp;Imprimir',
                            ['controller' => 'Reportes', 'action' => 'listarParticipantes', $curso->id],
                            ['class' => 'btn btn-default btn-sm', 'escape' => false, 'title' => 'Imprimir Lista'])
                        ?>
                        <?= $this->Html->link('<i class="fas fa-file-excel"></i>&nbsp;Exportar',
                            ['controller' => 'Archivos', 'action' => 'exportarParticipantes', $curso->id],
                            ['class' => 'btn btn-default btn-sm', 'escape' => false, 'title' => 'Exportar Lista'])
                        ?>
                    </div>
                </div>
            </div>
            <div class="box-body table-responsive no-padding">
                <?php if (!empty($estudianteCursos)): ?>
                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th>Cédula</th>
                                <th>Estudiante</th>
                                <th class="text-center">Calificación</th>
                                <th class="text-center">Recuperación</th>
                                <th class="text-center">Definitiva</th>
                                <th>Análista</th>
                                <th>Calificador</th>
                                <th class="text-center">Activo</th>
                                <th class="text-center">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $nPagina = (int)$this->request->getQuery('page', 1);
                                $nLimite = 20;
                                $nOffset = ($nPagina - 1) * $nLimite;
                            ?>
                            <?php foreach ($estudianteCursos as $i => $ec): ?>
                                <tr>
                                    <td class="text-center"><?= ($nOffset + $i + 1) ?></td>
                                    <td><?= $ec->has('estudiante') ? $this->Number->format($ec->estudiante->cedula) : h($ec->estudiante_id) ?></td>
                                    <td>
                                        <?= $ec->has('estudiante') ? $ec->estudiante->full_name : h($ec->estudiante_id) ?>
                                    </td>
                                    <td class="text-center"><?= h($ec->calificacion ?? '') ?></td>
                                    <td class="text-center"><?= h($ec->recuperacion ?? '') ?></td>
                                    <td class="text-center"><?= h($ec->definitiva ?? '') ?></td>
                                    <td><?= h($ec->analista) ?></td>
                                    <td><?= h($ec->responsable) ?></td>
                                    <td class="text-center"><?= $ec->activo ? 'Sí' : 'No' ?></td>
                                    <td class="actions text-center">
                                        <?php if ($this->Permiso->tiene([1,2,3])): ?>
                                        <button type="button"
                                            class="btn btn-warning btn-xs btn-calificar-individual"
                                            data-id="<?= $ec->id ?>"
                                            data-estudiante="<?= h($ec->has('estudiante') ? $ec->estudiante->full_name : '') ?>"
                                            data-calificacion="<?= h($ec->calificacion ?? '') ?>"
                                            data-recuperacion="<?= h($ec->recuperacion ?? '') ?>"
                                            data-definitiva="<?= h($ec->definitiva ?? '') ?>"
                                            title="Cargar nota individual">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <?php endif; ?>
                                        <?= $this->Form->postLink('<i class="fa fa-trash"></i>',
                                            ['controller' => 'EstudianteCursos', 'action' => 'eliminar', $ec->id],
                                            ['confirm' => '¿Está seguro de eliminar esta inscripción?', 'class' => 'btn btn-danger btn-xs', 'escape' => false])
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="no-padding">
                            <tr>
                                <td colspan="13" class="text-center">
                                    <div class="paginator">
                                        <ul class="pagination pagination-sm">
                                            <?= $this->Paginator->first('<i class="fa fa-angle-double-left"></i>',['class' => 'btn btn-sm','escape' => false]) ?>
                                            <?= $this->Paginator->prev('<i class="fa fa-angle-left"></i>',['class' => 'btn btn-sm','escape' => false]) ?>
                                            <?= $this->Paginator->numbers(['before' => '','after' => '']) ?>
                                            <?= $this->Paginator->next('<i class="fa fa-angle-right"></i>',['class' => 'btn btn-sm','escape' => false]) ?>
                                            <?= $this->Paginator->last('<i class="fa fa-angle-double-right"></i>',['class' => 'btn btn-sm','escape' => false]) ?>
                                        </ul>
                                        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                <?php else: ?>
                    <p class="text-center text-muted">No hay estudiantes inscritos en este curso.</p>
                <?php endif; ?>
            </div>
            <div class="box-footer">
                <?php
                    if ($curso->cerrado == 0){
                        echo $this->Html->link('<i class="fa fa-users"></i>&nbsp;Registrar Participantes',
                        ['#'], ['id' => 'btn-registrar-participantes', 'class' => 'btn btn-primary btn-flat pull-right', 'escape' => false]);
                    }
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
    var rpCursoInscritos = <?= json_encode($nTotalEstudiantes) ?>;
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

<div class="modal fade" id="modal-calificar-individual" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-purple">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-edit"></i>&nbsp;Cargar Nota Individual</h4>
            </div>
            <div class="modal-body">
                <p class="text-center"><strong id="ci-estudiante"></strong></p>
                <input type="hidden" id="ci-id">
                <div class="form-group">
                    <label>Calificación</label>
                    <input type="text" id="ci-calificacion" class="form-control input-sm nota-input-ci" inputmode="decimal" maxlength="5">
                </div>
                <div class="form-group">
                    <label>Recuperación</label>
                    <input type="text" id="ci-recuperacion" class="form-control input-sm nota-input-ci" inputmode="decimal" maxlength="5">
                </div>
                <div class="form-group">
                    <label>Definitiva</label>
                    <input type="text" id="ci-definitiva" class="form-control input-sm nota-input-ci" inputmode="decimal" maxlength="5">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" id="btn-ci-guardar" class="btn bg-purple"><i class="fa fa-save"></i>&nbsp;Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var nTipoCal = <?= json_encode($nTipoCalificacion ?? 0) ?>;

    $(document).on('click', '.btn-calificar-individual', function() {
        var $btn = $(this);
        $('#ci-estudiante').text($btn.data('estudiante'));
        $('#ci-id').val($btn.data('id'));
        $('#ci-calificacion').val($btn.data('calificacion'));
        $('#ci-recuperacion').val($btn.data('recuperacion'));
        $('#ci-definitiva').val($btn.data('definitiva'));
        $('#modal-calificar-individual').modal('show');
    });

    if (nTipoCal === 0) {
        var regex = /^\d*(\.\d{0,2})?$/;
        $(document).on('input', '.nota-input-ci', function() {
            var val = $(this).val();
            if (!regex.test(val)) {
                $(this).val(val.slice(0, -1));
            }
        });
        $(document).on('change', '.nota-input-ci', function() {
            var val = parseFloat($(this).val());
            if (!isNaN(val)) {
                if (val < 1) $(this).val('1');
                else if (val > 20) $(this).val('20');
            }
        });
    } else {
        $(document).on('change', '.nota-input-ci', function() {
            var val = $(this).val().toUpperCase();
            if (val !== '' && val !== 'A' && val !== 'R') {
                $(this).val('');
            } else {
                $(this).val(val);
            }
        });
    }

    $('#btn-ci-guardar').click(function() {
        var $btn = $(this);
        var ecId = $('#ci-id').val();
        var datos = {
            calificacion: $('#ci-calificacion').val(),
            recuperacion: $('#ci-recuperacion').val(),
            definitiva: $('#ci-definitiva').val()
        };

        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>&nbsp;Guardando...');

        $.ajax({
            url: basePath + 'estudiante-cursos/guardar-nota-individual/' + ecId,
            type: 'POST',
            data: { notas: datos, nCursoId: <?= $curso->id ?> },
            dataType: 'json',
            success: function(resp) {
                if (resp.success) {
                    $('#modal-calificar-individual').modal('hide');
                    toastr.success('Notas guardadas correctamente.');
                    setTimeout(function() { location.reload(); }, 800);
                } else {
                    toastr.error(resp.message || 'Error al guardar.');
                    $btn.prop('disabled', false).html('<i class="fa fa-save"></i>&nbsp;Guardar');
                }
            },
            error: function() {
                toastr.error('Error de conexión.');
                $btn.prop('disabled', false).html('<i class="fa fa-save"></i>&nbsp;Guardar');
            }
        });
    });
});
</script>

