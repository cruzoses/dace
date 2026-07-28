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
                <h3 class="box-title"><i class="fa fa-pencil-alt"></i>&nbsp;Acta de Notas</h3>
                <div class="box-tools pull-right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-print"></i>&nbsp;Imprimir <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="<?= $this->Url->build([
                                'controller' => 'Reportes', 'action' => 'listarActadeNotas', $oCurso->id]) ?>">
                                <i class="fas fa-file-pdf"></i>&nbsp;Acta de Notas</a>
                            </li>
                            <li class="divider"></li>
                            <li><a href="<?= $this->Url->build([
                                'controller' => 'Archivos', 'action' => 'exportarActadeNotas', $oCurso->id]) ?>">
                                <i class="fas fa-file-excel"></i>&nbsp;Exportar a Excel</a>
                            </li>
                        </ul>
                    </div>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <?php if ($bCalifica && $oCurso->cerrado == 0) : ?>
                <div class="row" id="toolbar-evaluaciones" style="margin-bottom: 10px;">
                    <div class="col-xs-12">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <span class="text-muted" style="margin-right: 5px;">Evaluaciones:</span>
                                <?php $nEvalBtn = 1; ?>
                                <?php foreach ($aEvaluaciones as $oEvaluacion) : ?>
                                    <button type="button"
                                            class="btn btn-default btn-xs btn-eval"
                                            data-eval="<?= $nEvalBtn ?>"
                                            data-contenido-id="<?= $oEvaluacion->id ?>"
                                            title="<?= h($oEvaluacion->descripcion) ?>">
                                        <?= $nEvalBtn ?>
                                    </button>
                                    <?php $nEvalBtn++; ?>
                                <?php endforeach; ?>
                                <button type="button" class="btn bg-purple btn-sm" id="btnCerrarNota" disabled>
                                    <i class="fa fa-lock"></i>&nbsp;Cerrar Carga
                                </button>
                            </div>
                            <div>
                                <button type="button" class="btn bg-navy btn-sm" id="btnCerrarActa">
                                    <i class="fas fa-user-cog"></i>&nbsp;Cerrar Acta
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="table no-padding">
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
                                        if ($nEscala == 2 || $nEscala == 3) {
                                            $nMaxNota = (int)$oEvaluacion->ponderacion;
                                        }
                                    }
                                ?>
                                    <th class="bg-gray text-center" style="min-width: 80px;"
                                        title="<?= h($oEvaluacion->descripcion) ?> — Máx: <?= $nMaxNota ?> puntos">
                                        <?= $nEvalIdx ?> (<?= $oEvaluacion->ponderacion ?>%)
                                    </th>
                                    <?php $nEvalIdx++; ?>
                                <?php endforeach; ?>
                                <th class="bg-aqua text-center" rowspan="2" style="width: 80px;">Total</th>
                                <th class="bg-green text-center" rowspan="2" style="width: 80px;">Final</th>
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
                                    <?php $nColEval = 1; ?>
                                    <?php foreach ($aEvaluaciones as $oEvaluacion) :
                                        $nEscala = (int)$oEvaluacion->indicador_curso->escala_nota;
                                        $nMaxNota = 20;
                                        $sAttrMax = '';
                                        if ($nTipoCalificacion == 0) {
                                            if ($nEscala == 2 || $nEscala == 3) {
                                                $nMaxNota = (int)$oEvaluacion->ponderacion;
                                            }
                                            $sAttrMax = '';
                                        }
                                    ?>
                                        <td class="text-center">
                                            <?php if ($nTipoCalificacion == 1) : ?>
                                                <select class="nota-input form-control input-sm"
                                                        data-estudiante="<?= $oEst->id ?>"
                                                        data-contenido="<?= $oEvaluacion->id ?>"
                                                        data-eval="<?= $nColEval ?>"
                                                        data-escala="<?= $nEscala ?>"
                                                        data-max="<?= $nMaxNota ?>"
                                                        disabled
                                                        style="width: 70px; display: inline-block;">
                                                    <option value=""></option>
                                                    <option value="A">A</option>
                                                    <option value="R">R</option>
                                                </select>
                                            <?php else : ?>
                                                <input type="text" class="nota-input form-control input-sm"
                                                       data-estudiante="<?= $oEst->id ?>"
                                                       data-contenido="<?= $oEvaluacion->id ?>"
                                                       data-eval="<?= $nColEval ?>"
                                                       data-escala="<?= $nEscala ?>"
                                                       data-max="<?= $nMaxNota ?>"
                                                       inputmode="decimal"<?= $sAttrMax ?>
                                                       disabled
                                                       style="width: 70px; display: inline-block;">
                                            <?php endif; ?>
                                        </td>
                                        <?php $nColEval++; ?>
                                    <?php endforeach; ?>
                                    <td class="text-center" id="total-<?= $oEst->id ?>">&mdash;</td>
                                    <td class="text-center" id="final-<?= $oEst->id ?>">&mdash;</td>
                                </tr>
                                <?php $nIdx++; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if ($bCalifica && $oCurso->cerrado == 0) : ?>
            <div class="box-footer">
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <button type="button" class="btn btn-success" id="btnGuardarNotas" disabled>
                            <i class="fa fa-save"></i>&nbsp;Guardar Notas
                        </button>
                        <?= $this->Html->link('<i class="fa fa-times"></i>&nbsp;Volver',
                            ['controller' => 'profesores', 'action' => 'listadeclase', $oCurso->id],
                            ['class' => 'btn bg-maroon', 'escape' => false]) 
                        ?>
                    </div>
                </div>
            </div>
            <?php else : ?>
            <div class="box-footer">
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <?= $this->Html->link('<i class="fa fa-times"></i>&nbsp;Volver',
                            ['controller' => 'profesores', 'action' => 'listadeclase', $oCurso->id],
                            ['class' => 'btn bg-maroon', 'escape' => false]) 
                        ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $('#modulo').text("Docente > Carga de Notas");

    var sBasePath = basePath + 'cursoNotas/';
    var nTipoCalificacion = <?= $nTipoCalificacion ?>;
    var bCalifica = <?= $bCalifica ? 'true' : 'false' ?>;
    var nEvalActiva = 0;
    var nNotaMinima = <?= $nNotaMinima ?>;

    var aEvalMeta = [
        <?php $nMetaIdx = 0; ?>
        <?php foreach ($aEvaluaciones as $oEvaluacion) :
            $nEscala = (int)$oEvaluacion->indicador_curso->escala_nota;
            $nMaxNota = 20;
            if ($nTipoCalificacion == 0) {
                if ($nEscala == 2 || $nEscala == 3) {
                    $nMaxNota = (int)$oEvaluacion->ponderacion;
                }
            }
        ?>
        { ponderacion: <?= (int)$oEvaluacion->ponderacion ?>,
          escala: <?= $nEscala ?>,
          maxNota: <?= $nMaxNota ?>,
          contenidoId: <?= (int)$oEvaluacion->id ?> }<?= ++$nMetaIdx < count($aEvaluaciones) ? ',' : '' ?>
        <?php endforeach; ?>
    ];

    function fnNormalizar(nNota, nEscala, nMaxNota) {
        switch (nEscala) {
            case 1:  return (nNota / 20) * 100;
            case 2:  return (nNota / nMaxNota) * 100;
            case 3:  return nNota;
            default: return 0;
        }
    }

    function fnAEscala20(nValor) {
        nValor = Math.max(1, Math.min(100, Math.round(nValor)));
        if (nValor <= 5)  return 1;
        if (nValor <= 10) return 2;
        if (nValor <= 15) return 3;
        if (nValor <= 20) return 4;
        if (nValor <= 25) return 5;
        if (nValor <= 30) return 6;
        if (nValor <= 35) return 7;
        if (nValor <= 40) return 8;
        if (nValor <= 45) return 9;
        if (nValor <= 50) return 10;
        if (nValor <= 55) return 11;
        if (nValor <= 60) return 12;
        if (nValor <= 65) return 13;
        if (nValor <= 70) return 14;
        if (nValor <= 75) return 15;
        if (nValor <= 80) return 16;
        if (nValor <= 85) return 17;
        if (nValor <= 90) return 18;
        if (nValor <= 95) return 19;
        return 20;
    }

    function fnCalcularTotales() {
        $('#grillaNotas tbody tr').each(function () {
            var $fila = $(this);
            var nEstudiante = $fila.find('.nota-input').first().data('estudiante');
            if (!nEstudiante) return;

            if (nTipoCalificacion == 1) {
                var nA = 0, nR = 0;
                $fila.find('.nota-input').each(function () {
                    var sVal = ($(this).val() || '').toString().trim().toUpperCase();
                    if (sVal === 'A') nA++;
                    else if (sVal === 'R') nR++;
                });
                var sResultado = (nA + nR === 0) ? '&mdash;' : (nA >= nR ? 'A' : 'R');
                $fila.find('#total-' + nEstudiante).html(sResultado);
                $fila.find('#final-' + nEstudiante).html(sResultado);
                return;
            }

            var nTotalNat = 0;
            var nTotalNorm = 0;
            var bCompleto = false;
            var bMixto = false;
            var nPrimeraEscala = 0;

            $fila.find('.nota-input').each(function () {
                var $input = $(this);
                var sVal = ($input.val() || '').toString().trim();
                if (sVal === '') return;

                var nNota = parseFloat(sVal);
                if (isNaN(nNota)) return;

                bCompleto = true;
                var nEvalIdx = parseInt($input.data('eval')) - 1;
                var oMeta = aEvalMeta[nEvalIdx];
                if (!oMeta) return;

                if (nPrimeraEscala === 0) {
                    nPrimeraEscala = oMeta.escala;
                } else if (oMeta.escala !== nPrimeraEscala) {
                    bMixto = true;
                }

                nTotalNat += nNota * (oMeta.ponderacion / 100);

                var nNorm = fnNormalizar(nNota, oMeta.escala, oMeta.maxNota);
                nTotalNorm += nNorm * (oMeta.ponderacion / 100);
            });

            if (!bCompleto) {
                $fila.find('#total-' + nEstudiante).html('&mdash;');
                $fila.find('#final-' + nEstudiante).html('&mdash;');
                return;
            }

            $fila.find('#total-' + nEstudiante).text(nTotalNat.toFixed(2));

            if (!bMixto && nPrimeraEscala === 1) {
                $fila.find('#final-' + nEstudiante).text(nTotalNat.toFixed(0));
            } else {
                $fila.find('#final-' + nEstudiante).text(fnAEscala20(nTotalNorm));
            }
        });
    }

    function fnAplicarColores() {
        $('.nota-input').each(function () {
            var $input = $(this);
            var sVal = ($input.val() || '').toString().trim().toUpperCase();
            $input.removeClass('nota-aprobada nota-reprobada');
            if (sVal === '') return;

            if (nTipoCalificacion == 1) {
                if (sVal === 'A') $input.addClass('nota-aprobada');
                else if (sVal === 'R') $input.addClass('nota-reprobada');
            } else {
                var nVal = parseFloat(sVal);
                if (isNaN(nVal)) return;
                var nMax = parseInt($input.data('max')) || 20;
                if (nVal >= nMax / 2) $input.addClass('nota-aprobada');
                else $input.addClass('nota-reprobada');
            }
        });
    }

    function fnColorearTotales() {
        $('#grillaNotas tbody tr').each(function () {
            var $fila = $(this);
            var nEstudiante = $fila.find('.nota-input').first().data('estudiante');
            if (!nEstudiante) return;

            var $total = $fila.find('#total-' + nEstudiante);
            var $final = $fila.find('#final-' + nEstudiante);

            $total.removeClass('nota-aprobada nota-reprobada');
            $final.removeClass('nota-aprobada nota-reprobada');

            if (nTipoCalificacion == 1) {
                var sTotal = ($total.text() || '').trim().toUpperCase();
                var sFinal = ($final.text() || '').trim().toUpperCase();
                if (sTotal === 'A') $total.addClass('nota-aprobada');
                else if (sTotal === 'R') $total.addClass('nota-reprobada');
                if (sFinal === 'A') $final.addClass('nota-aprobada');
                else if (sFinal === 'R') $final.addClass('nota-reprobada');
            } else {
                var nTotalVal = parseFloat($total.text());
                if (!isNaN(nTotalVal) && nTotalVal > 0) {
                    if (nTotalVal >= nNotaMinima) $total.addClass('nota-aprobada');
                    else $total.addClass('nota-reprobada');
                }
                var nFinalVal = parseFloat($final.text());
                if (!isNaN(nFinalVal) && nFinalVal > 0) {
                    if (nFinalVal >= nNotaMinima) $final.addClass('nota-aprobada');
                    else $final.addClass('nota-reprobada');
                }
            }
        });
    }

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
            fnCalcularTotales();
            fnAplicarColores();
            fnColorearTotales();
        }
    });

    if (bCalifica) {
        $('.btn-eval').click(function () {
            var $btn = $(this);
            var nEval = parseInt($btn.data('eval'));

            if (nEvalActiva === nEval) {
                return;
            }

            $('.nota-input').prop('disabled', true).removeClass('input-activa');
            $('.btn-eval').removeClass('active').removeClass('btn-primary').addClass('btn-default');

            $('.nota-input[data-eval="' + nEval + '"]').prop('disabled', false).addClass('input-activa');
            $btn.addClass('active').removeClass('btn-default').addClass('btn-primary');

            nEvalActiva = nEval;
            $('#btnCerrarNota').prop('disabled', false);
            $('#btnGuardarNotas').prop('disabled', false);
        });

            $('#btnCerrarNota').click(function () {
            $('.nota-input').prop('disabled', true).removeClass('input-activa');
            $('.btn-eval').removeClass('active').removeClass('btn-primary').addClass('btn-default');
            $(this).prop('disabled', true);
            $('#btnGuardarNotas').prop('disabled', true);
            nEvalActiva = 0;
            toastr.info('Acta cerrada. Las calificaciones no se modifican hasta seleccionar otra evaluación.');
        });

        $('#btnCerrarActa').prop('disabled', false).click(function () {
            var $btn = $(this);
            Swal.fire({
                title: 'Cerrar Acta',
                text: 'Se calculará la nota final de cada estudiante y se actualizará su calificación en el curso. Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dd4b39',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, cerrar acta',
                cancelButtonText: 'Cancelar'
            }).then(function (result) {
                if (result.isConfirmed) {
                    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>&nbsp;Procesando...');
                    $.ajax({
                        url: sBasePath + 'cerrarActa',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            curso_id: <?= $nCursoId ?>,
                            _csrfToken: $('#csrf-token').val()
                        },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    title: '¡Éxito!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar'
                                }).then(function () {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error', response.message, 'error');
                                $btn.prop('disabled', false).html('<i class="fa fa-file-text-o"></i>&nbsp;Cerrar Acta');
                            }
                        },
                        error: function (jqXHR) {
                            var sMsg = 'Error al cerrar el acta.';
                            if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                                sMsg = jqXHR.responseJSON.message;
                            }
                            Swal.fire('Error', sMsg, 'error');
                            $btn.prop('disabled', false).html('<i class="fa fa-file-text-o"></i>&nbsp;Cerrar Acta');
                        }
                    });
                }
            });
        });
    }

    $('.nota-input').on('change', function () {
        var $input = $(this);
        var sValor = $input.val();
        var nMax = parseInt($input.data('max'));

        if (sValor === '' || sValor === null) return;

        if (nTipoCalificacion == 0) {
            if (!/^\d+(\.\d{1,2})?$/.test(sValor)) {
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
        fnCalcularTotales();
        fnAplicarColores();
        fnColorearTotales();
    });

    $(document).on('keydown', 'input.nota-input:not(:disabled)', function (e) {
        if (nTipoCalificacion != 0) return;
        var $input = $(this);
        var sTecla = e.key;
        var nCodigo = e.keyCode;
        var bCtrl = e.ctrlKey || e.metaKey;

        if (bCtrl || nCodigo === 8 || nCodigo === 9 || nCodigo === 13 ||
            nCodigo === 37 || nCodigo === 39 || nCodigo === 46) {
            return;
        }

        if (sTecla === '.' && $input.val().indexOf('.') === -1) {
            return;
        }

        if (!/^\d$/.test(sTecla)) {
            e.preventDefault();
        }
    });

    $(document).on('input', 'input.nota-input:not(:disabled)', function () {
        if (nTipoCalificacion != 0) return;
        var $input = $(this);
        var sVal = $input.val();
        var nMax = parseInt($input.data('max'));

        if (sVal === '') {
            $input.removeClass('input-error');
            return;
        }

        if (/^\d+(\.\d{0,2})?$/.test(sVal)) {
            var nVal = parseFloat(sVal);
            if (nVal >= 1 && nVal <= nMax) {
                $input.removeClass('input-error');
            } else {
                $input.addClass('input-error');
            }
        } else {
            $input.addClass('input-error');
        }
    });

    $('#btnGuardarNotas').click(function () {
        var aNotas = [];
        var bHayNotas = false;

        $('.nota-input:not(:disabled)').each(function () {
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
                    setTimeout(function () { location.reload(); }, 800);
                } else {
                    toastr.warning(response.message);
                    if (response.errores) {
                        response.errores.forEach(function (sError) {
                            toastr.error(sError, '', {timeOut: 5000});
                        });
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var sMsg = 'Error al guardar las notas.';
                if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                    sMsg = jqXHR.responseJSON.message;
                } else if (jqXHR.responseText) {
                    console.error('Respuesta del servidor:', jqXHR.responseText);
                }
                console.error('Status:', jqXHR.status, '|', textStatus, '|', errorThrown);
                toastr.error(sMsg);
            },
            complete: function () {
                $btn.prop('disabled', false).html('<i class="fa fa-save"></i>&nbsp;Guardar Notas');
            }
        });
    });
});
</script>

<style>
.nota-input:disabled {
    background-color: transparent !important;
    border-color: transparent !important;
    box-shadow: none !important;
    text-align: center;
    font-weight: bold;
    cursor: default;
}
select.nota-input:disabled {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    text-align: center;
}
.nota-aprobada { color: #0073b7 !important; }
.nota-reprobada { color: #dd4b39 !important; }
.input-error {
    border-color: #dd4b39 !important;
    box-shadow: 0 0 5px rgba(221, 75, 57, 0.5);
}
.input-activa {
    background-color: #fcf8e3 !important;
    border-color: #faebcc !important;
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
