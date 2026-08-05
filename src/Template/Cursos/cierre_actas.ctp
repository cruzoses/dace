<div class="row">
    <div class="col-xs-12">
        <div class="box box-sace box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-file-text-o"></i>&nbsp;Cierre de Actas de Notas</h3>
                <div class="box-tools pull-right">
                    <?php
                        $nPendientes = 0;
                        if (!empty($cursos)) {
                            foreach ($cursos as $oCurso) {
                                $info = isset($aInfo[$oCurso->id]) ? $aInfo[$oCurso->id] : ['n_estudiantes' => 0, 'n_evaluaciones' => 0];
                                if ((int)$oCurso->cerrado === 0 && $info['n_evaluaciones'] > 0 && $info['n_estudiantes'] > 0) {
                                    $nPendientes++;
                                }
                            }
                        }
                    ?>
                    <?php if ($nPendientes > 0) : ?>
                        <button type="button" class="btn btn-success btn-sm" id="btnCerrarPeriodo">
                            <i class="fa fa-lock"></i>&nbsp;Cerrar todos los pendientes (<?= $nPendientes ?>)
                        </button>
                    <?php endif; ?>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                    <?= $this->Html->link('<i class="fa fa-times"></i>',
                        ['controller' => 'Pages', 'action' => 'display', 'home'],
                        ['class' => 'btn btn-box-tool', 'escape' => false]);
                    ?>
                </div>
            </div>
            <div class="box-body">
                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <label for="periodoSelect">Periodo</label>
                        <select id="periodoSelect" class="form-control">
                            <?php foreach ($periodos as $nId => $sCodename) : ?>
                                <option value="<?= $nId ?>"<?= (int)$nId === (int)$periodoId ? ' selected' : '' ?>>
                                    <?= h($sCodename) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <?php if ($oPeriodo) : ?>
                    <div class="callout callout-info">
                        <i class="fa fa-calendar"></i>&nbsp;<strong><?= h($oPeriodo->codigo) ?> : <?= h($oPeriodo->nombre) ?></strong>
                        &nbsp;|&nbsp;Cursos: <?= count($cursos) ?>&nbsp;|&nbsp;Pendientes: <?= $nPendientes ?>
                    </div>
                <?php endif; ?>

                <?php if (empty($cursos)) : ?>
                    <div class="callout callout-warning">
                        <i class="fa fa-exclamation-triangle"></i>&nbsp;<strong>No hay cursos registrados en este periodo.</strong>
                    </div>
                <?php else : ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-condensed" id="tablaCierreActas">
                            <thead>
                                <tr>
                                    <th class="bg-gray text-center" style="width:40px;">No.</th>
                                    <th class="bg-gray text-center">Asignatura</th>
                                    <th class="bg-gray text-center" style="width:80px;">Secci&oacute;n</th>
                                    <th class="bg-gray text-center">Docente</th>
                                    <th class="bg-gray text-center" style="width:70px;">Estud.</th>
                                    <th class="bg-gray text-center" style="width:70px;">Eval.</th>
                                    <th class="bg-gray text-center" style="width:110px;">Estado</th>
                                    <th class="bg-gray text-center" style="width:130px;">Acci&oacute;n</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $nIdx = 1; ?>
                                <?php foreach ($cursos as $oCurso) : ?>
                                    <?php
                                        $info = isset($aInfo[$oCurso->id]) ? $aInfo[$oCurso->id] : ['n_estudiantes' => 0, 'n_evaluaciones' => 0];
                                        if ((int)$oCurso->cerrado === 1) {
                                            $sEstado = 'CERRADO';
                                            $sBadge = 'bg-gray';
                                            $sEstadoTxt = 'Cerrado';
                                            $bCerrable = false;
                                        } elseif ($info['n_evaluaciones'] === 0) {
                                            $sEstado = 'SIN_PLAN';
                                            $sBadge = 'bg-orange';
                                            $sEstadoTxt = 'Sin plan de evaluaci&oacute;n';
                                            $bCerrable = false;
                                        } elseif ($info['n_estudiantes'] === 0) {
                                            $sEstado = 'SIN_ESTUDIANTES';
                                            $sBadge = 'bg-orange';
                                            $sEstadoTxt = 'Sin estudiantes';
                                            $bCerrable = false;
                                        } else {
                                            $sEstado = 'PENDIENTE';
                                            $sBadge = 'bg-green';
                                            $sEstadoTxt = 'Pendiente';
                                            $bCerrable = true;
                                        }
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= $nIdx ?></td>
                                        <td>
                                            <?= $oCurso->has('asignatura') ? h($oCurso->asignatura->codigo) . ' - ' . h($oCurso->asignatura->nombre) : '' ?>
                                        </td>
                                        <td class="text-center"><?= h($oCurso->seccion) ?></td>
                                        <td><?= $oCurso->has('docente') && $oCurso->docente ? h($oCurso->docente->codename) : 'SIN DOCENTE' ?></td>
                                        <td class="text-center"><?= $info['n_estudiantes'] ?></td>
                                        <td class="text-center"><?= $info['n_evaluaciones'] ?></td>
                                        <td class="text-center">
                                            <span class="badge <?= $sBadge ?>"><?= $sEstadoTxt ?></span>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($bCerrable) : ?>
                                                <button type="button"
                                                        class="btn bg-navy btn-xs btn-cerrar-curso"
                                                        data-curso="<?= $oCurso->id ?>"
                                                        data-titulo="<?= h($oCurso->asignatura->codigo . ' - ' . $oCurso->asignatura->nombre . ' - Secc. ' . $oCurso->seccion) ?>">
                                                    <i class="fa fa-lock"></i>&nbsp;Cerrar acta
                                                </button>
                                            <?php else : ?>
                                                &mdash;
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php $nIdx++; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    var sBaseUrl = '<?= $this->Url->build(['controller' => 'Cursos', 'action' => 'cierreActas']) ?>';
    var sCsrf = $('meta[name="csrfToken"]').attr('content');

    $('#periodoSelect').on('change', function () {
        var nId = $(this).val();
        if (nId) {
            window.location.href = sBaseUrl + '?periodo_id=' + nId;
        }
    });

    function fnCerrar(datos, textoConfirmacion) {
        return $.ajax({
            url: sBaseUrl,
            type: 'POST',
            dataType: 'json',
            data: $.extend({ _csrfToken: sCsrf }, datos)
        });
    }

    $('.btn-cerrar-curso').on('click', function () {
        var $btn = $(this);
        var nCurso = $btn.data('curso');
        var sTitulo = $btn.data('titulo');

        Swal.fire({
            title: 'Cerrar acta',
            text: 'Se calculará la nota final de cada estudiante del curso "' + sTitulo + '" y se actualizará su calificación. Esta acción no se puede deshacer.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4190d1',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, cerrar acta',
            cancelButtonText: 'Cancelar'
        }).then(function (result) {
            if (!result.isConfirmed) {
                return;
            }
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>&nbsp;Procesando...');
            fnCerrar({ curso_id: nCurso }).done(function (response) {
                if (response.success) {
                    Swal.fire({ title: '¡Éxito!', text: response.message, icon: 'success', confirmButtonText: 'Aceptar' })
                        .then(function () { window.location.reload(); });
                } else {
                    Swal.fire('Error', response.message, 'error');
                    $btn.prop('disabled', false).html('<i class="fa fa-lock"></i>&nbsp;Cerrar acta');
                }
            }).fail(function (jqXHR) {
                var sMsg = 'Error al cerrar el acta.';
                if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                    sMsg = jqXHR.responseJSON.message;
                }
                Swal.fire('Error', sMsg, 'error');
                $btn.prop('disabled', false).html('<i class="fa fa-lock"></i>&nbsp;Cerrar acta');
            });
        });
    });

    $('#btnCerrarPeriodo').on('click', function () {
        var $btn = $(this);
        var nPeriodo = <?= $periodoId ?: 'null' ?>;

        if (!nPeriodo) {
            toastr.warning('Seleccione un periodo.');
            return;
        }

        Swal.fire({
            title: 'Cerrar todas las actas del periodo',
            text: 'Se cerrará el acta de todos los cursos pendientes del periodo. Esta acción no se puede deshacer.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4190d1',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, cerrar todas',
            cancelButtonText: 'Cancelar'
        }).then(function (result) {
            if (!result.isConfirmed) {
                return;
            }
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>&nbsp;Procesando...');
            fnCerrar({ periodo_id: nPeriodo }).done(function (response) {
                if (response.success) {
                    Swal.fire({ title: '¡Éxito!', text: response.message, icon: 'success', confirmButtonText: 'Aceptar' })
                        .then(function () { window.location.reload(); });
                } else {
                    Swal.fire('Error', response.message, 'error');
                    $btn.prop('disabled', false).html('<i class="fa fa-lock"></i>&nbsp;Cerrar todos los pendientes');
                }
            }).fail(function (jqXHR) {
                var sMsg = 'Error al cerrar las actas del periodo.';
                if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                    sMsg = jqXHR.responseJSON.message;
                }
                Swal.fire('Error', sMsg, 'error');
                $btn.prop('disabled', false).html('<i class="fa fa-lock"></i>&nbsp;Cerrar todos los pendientes');
            });
        });
    });
});
</script>
