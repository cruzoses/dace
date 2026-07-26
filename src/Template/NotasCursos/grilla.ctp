<div class="row">
    <div class="col-xs-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-info"></i>
                    CARGA DE NOTAS&nbsp;<b><?= $oCurso->periodo->codename ?></b>
                </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                    <?= $this->Html->link('<i class="fas fa-sign-out-alt"></i>',
                        ['controller' => 'profesores', 'action' => 'listadeclase', $oCurso->id],
                        ['class' => 'btn btn-box-tool', 'title' => 'cerrar', 'escape' => false]);
                    ?>
                </div>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-bordered table-condensed">
                    <tr>
                        <th class="bg-gray text-center">Asignatura</th>
                        <th class="bg-gray text-center">Cr&eacute;ditos</th>
                        <th class="bg-gray text-center">Secci&oacute;n</th>
                        <th class="bg-gray text-center">Docente</th>
                        <th class="bg-gray text-center">Tipo</th>
                    </tr>
                    <tr>
                        <td class="text-center"><?= h($oCurso->asignatura->nombre) ?></td>
                        <td class="text-center"><?= $oCurso->asignatura->creditos ?></td>
                        <td class="text-center"><?= h($oCurso->seccion) ?></td>
                        <td class="text-center"><?= $oCurso->has('docente') ? h($oCurso->docente->codename) : '' ?></td>
                        <td class="text-center">
                            <?= $nTipoCalificacion == 0 ? 'CUANTITATIVA' : 'CUALITATIVA' ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<?php if (count($aEstudiantes) > 0 && count($aEvaluaciones) > 0) : ?>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-sace box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-pencil-alt"></i>&nbsp;Grilla de Calificaciones</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-bordered table-condensed table-hover" id="grillaNotas">
                    <thead>
                        <tr>
                            <th class="bg-gray text-center" rowspan="2" style="width: 40px;">No.</th>
                            <th class="bg-gray text-center" rowspan="2" style="width: 80px;">C&eacute;dula</th>
                            <th class="bg-gray text-center" rowspan="2">Estudiante</th>
                            <?php $nEvalIdx = 1; ?>
                            <?php foreach ($aEvaluaciones as $oEvaluacion) :
                                $nEscala = (int)$oEvaluacion->indicador_curso->escala_nota;
                                $nMaxNota = 20;
                                if ($nTipoCalificacion == 0) {
                                    if ($nEscala == 2) {
                                        $nMaxNota = (int)$oEvaluacion->indicador_curso->porcentaje;
                                    } elseif ($nEscala == 3) {
                                        $nMaxNota = 100;
                                    }
                                }
                            ?>
                                <th class="bg-gray text-center" style="min-width: 80px;"
                                    title="<?= h($oEvaluacion->descripcion) ?> — Máx: <?= $nMaxNota ?> puntos">
                                    <?= $nEvalIdx ?> (<?= $oEvaluacion->ponderacion ?>%)
                                </th>
                                <?php $nEvalIdx++; ?>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $nIdx = 1; ?>
                        <?php foreach ($aEstudiantes as $oEstudianteCurso) :
                            $oEst = $oEstudianteCurso->estudiante;
                        ?>
                            <tr>
                                <td class="text-center"><?= $nIdx ?></td>
                                <td class="text-center"><?= $this->Number->format($oEst->cedula) ?></td>
                                <td><?= h($oEst->full_name) ?></td>
                                <?php foreach ($aEvaluaciones as $oEvaluacion) :
                                    $nEscala = (int)$oEvaluacion->indicador_curso->escala_nota;
                                    $nMaxNota = 20;
                                    $sAttrMax = '';
                                    if ($nTipoCalificacion == 0) {
                                        if ($nEscala == 2) {
                                            $nMaxNota = (int)$oEvaluacion->indicador_curso->porcentaje;
                                        } elseif ($nEscala == 3) {
                                            $nMaxNota = 100;
                                        }
                                        $sAttrMax = ' max="' . $nMaxNota . '" step="any"';
                                    }
                                ?>
                                    <td class="text-center">
                                        <?php if ($nTipoCalificacion == 1) : ?>
                                            <select class="nota-input form-control input-sm"
                                                    data-estudiante="<?= $oEst->id ?>"
                                                    data-contenido="<?= $oEvaluacion->id ?>"
                                                    data-escala="<?= $nEscala ?>"
                                                    data-max="<?= $nMaxNota ?>"
                                                    style="width: 70px; display: inline-block;">
                                                <option value=""></option>
                                                <option value="A">A</option>
                                                <option value="R">R</option>
                                            </select>
                                        <?php else : ?>
                                            <input type="number" class="nota-input form-control input-sm"
                                                   data-estudiante="<?= $oEst->id ?>"
                                                   data-contenido="<?= $oEvaluacion->id ?>"
                                                   data-escala="<?= $nEscala ?>"
                                                   data-max="<?= $nMaxNota ?>"
                                                   min="1"<?= $sAttrMax ?>
                                                   style="width: 70px; display: inline-block;">
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                            <?php $nIdx++; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <button type="button" class="btn btn-success btn-lg" id="btnGuardarNotas">
                            <i class="fa fa-save"></i>&nbsp;Guardar Notas
                        </button>
                        <?= $this->Html->link('<i class="fa fa-times"></i>&nbsp;Volver',
                            ['controller' => 'profesores', 'action' => 'listadeclase', $oCurso->id],
                            ['class' => 'btn bg-maroon btn-lg', 'escape' => false]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $('#modulo').text("Docente > Carga de Notas");

    var sBasePath = basePath + 'notasCursos/';
    var nTipoCalificacion = <?= $nTipoCalificacion ?>;

    $.ajax({
        url: sBasePath + 'getNotas/<?= $nCursoId ?>',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            $('.nota-input').each(function () {
                var $input = $(this);
                var nEstudiante = $input.data('estudiante');
                var nContenido = $input.data('contenido');
                if (data[nEstudiante] && data[nEstudiante][nContenido] !== undefined) {
                    $input.val(data[nEstudiante][nContenido]);
                }
            });
        }
    });

    $('.nota-input').on('change', function () {
        var $input = $(this);
        var sValor = $input.val();
        var nMax = parseInt($input.data('max'));

        if (sValor === '' || sValor === null) return;

        if (nTipoCalificacion == 0) {
            if (!/^\d+(\.\d+)?$/.test(sValor)) {
                $input.addClass('input-error');
                return;
            }
            var nVal = parseFloat(sValor);
            if (nVal < 1 || nVal > nMax) {
                $input.addClass('input-error');
            } else {
                $input.removeClass('input-error');
            }
        } else {
            sValor = sValor.toUpperCase();
            if (sValor !== 'A' && sValor !== 'R') {
                $input.addClass('input-error');
            } else {
                $input.removeClass('input-error');
                $input.val(sValor);
            }
        }
    });

    $('#btnGuardarNotas').click(function () {
        var aNotas = [];
        var bHayNotas = false;

        $('.nota-input').each(function () {
            var $input = $(this);
            var sValor = ($input.val() || '').toString().trim();

            if (sValor !== '') {
                bHayNotas = true;
                aNotas.push({
                    contenido_curso_id: $input.data('contenido'),
                    estudiante_id: $input.data('estudiante'),
                    calificacion: sValor
                });
            }
        });

        if (!bHayNotas) {
            toastr.warning('No hay calificaciones para guardar.');
            return;
        }

        if ($('.input-error').length > 0) {
            toastr.error('Hay calificaciones con errores. Revise los valores ingresados.');
            return;
        }

        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>&nbsp;Guardando...');

        $.ajax({
            url: sBasePath + 'guardar',
            type: 'POST',
            dataType: 'json',
            data: {
                notas: aNotas,
                tipo_calificacion: nTipoCalificacion
            },
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message);
                } else {
                    toastr.warning(response.message);
                    if (response.errores) {
                        response.errores.forEach(function (sError) {
                            toastr.error(sError, '', {timeOut: 5000});
                        });
                    }
                }
            },
            error: function () {
                toastr.error('Error de conexión al guardar las notas.');
            },
            complete: function () {
                $btn.prop('disabled', false).html('<i class="fa fa-save"></i>&nbsp;Guardar Notas');
            }
        });
    });
});
</script>

<style>
.input-error {
    border-color: #dd4b39 !important;
    box-shadow: 0 0 5px rgba(221, 75, 57, 0.5);
}
</style>

<?php elseif (count($aEstudiantes) == 0) : ?>
<div class="callout callout-info">
    <i class="fa fa-info-circle"></i>&nbsp;<strong>No tiene estudiantes inscritos en este curso.</strong>
</div>
<?php else : ?>
<div class="callout callout-warning">
    <i class="fa fa-exclamation-triangle"></i>&nbsp;<strong>No hay evaluaciones definidas en el plan de evaluaci&oacute;n.</strong>
    <br>Debe definir las evaluaciones antes de cargar notas.
    <?= $this->Html->link('Ir al Plan de Evaluaci&oacute;n',
        ['controller' => 'ContenidoCursos', 'action' => 'index', $nCursoId],
        ['class' => 'btn btn-sm btn-warning', 'escape' => false]) ?>
</div>
<?php endif; ?>
