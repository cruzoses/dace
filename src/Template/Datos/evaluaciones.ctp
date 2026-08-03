<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Estudiante $estudiante
 * @var array $periodos
*/
?>
<?php $this->assign('title', 'Notas de Lapso'); ?>
<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-list-alt"></i>&nbsp;Notas de Lapso</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                <i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool btn-cerrar-ajax">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <?php if (!empty($periodos)): ?>
            <?php
                $periodoIds = array_keys($periodos);
                $nPeriodos = count($periodoIds);
            ?>
            <div class="box box-default">
                <div class="box-body" style="padding-bottom: 0;">
                    <?php foreach ($periodoIds as $i => $pid): ?>
                        <?php $periodoNav = $periodos[$pid]['periodo']; ?>
                        <?php if ($nPeriodos > 3 && $i === 3): ?>
                            <div class="btn-group" style="margin-left:5px">
                        <?php endif; ?>
                        <a href="#periodo-<?= $pid ?>" data-periodo="<?= $pid ?>"
                           class="btn btn-sm btn-periodo<?= $i === 0 ? ' btn-info active' : ' btn-default' ?>" style="margin-bottom:4px">
                            <i class="fa fa-chevron-right"></i>&nbsp;<?= h($periodoNav->codigo) ?>
                        </a>
                        <?php if ($nPeriodos > 3 && $i === $nPeriodos - 1): ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php foreach ($periodos as $pid => $periodoItem): ?>
                <?php $periodo = $periodoItem['periodo']; ?>
                <div class="box box-info periodo-box" id="periodo-<?= $pid ?>"<?= $pid === $periodoIds[0] ? '' : ' style="display:none"' ?>>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-calendar"></i>&nbsp;<?= $periodo->has('codigo') ? h($periodo->codigo) : '' ?> : <?= h($periodo->nombre) ?></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php foreach ($periodoItem['cursos'] as $cursoItem): ?>
                            <?php $curso = $cursoItem['curso']; ?>
                            <?php $notas = $cursoItem['notas']; ?>
                            <?php
                                $sumaPonderaciones = 0;
                                $aIndIds = [];
                                foreach ($notas as $nota) {
                                    if ($nota->has('contenido_curso')) {
                                        $sumaPonderaciones += (float)$nota->contenido_curso->ponderacion;
                                        $aIndIds[$nota->contenido_curso->indicador_curso_id] = true;
                                    }
                                }
                                $nIndicadores = count($aIndIds);

                                $tipoCalificacion = $curso->has('asignatura') ? (int)$curso->asignatura->calificacion : 0;

                                $sTotalNotas = '';
                                $sFinalNotas = '';
                                if ($cursoItem['total'] !== null) {
                                    if ($tipoCalificacion === 1) {
                                        $sTotalNotas = ($cursoItem['final'] === 'A') ? 'Aprobada' : 'Reprobada';
                                        $sFinalNotas = $cursoItem['final'];
                                    } else {
                                        $sTotalNotas = $this->Number->format($cursoItem['total'], ['precision' => 2]);
                                        $sFinalNotas = $cursoItem['final'];
                                    }
                                }
                            ?>
                            <div class="box box-default">
                                <div class="box-header with-border">
                                    <h3 class="box-title" style="font-weight:bold">
                                        <?php if (!empty($cursoItem['programa_codigo'])): ?>
                                            <?= h($cursoItem['programa_codigo']) ?> -
                                        <?php endif; ?>
                                        <?= $curso->has('asignatura') ? h($curso->asignatura->codigo) : '' ?>
                                        - <?= $curso->has('asignatura') ? h($curso->asignatura->nombre) : '' ?>
                                        - <?= h($curso->seccion) ?>
                                    </h3>
                                    <div class="box-tools pull-right">
                                        <span class="badge bg-light-blue"><?= count($notas) ?> calificaciones</span>
                                    </div>
                                </div>
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-bordered table-hover table-condensed tabla-notas">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width:5%">No.</th>
                                                <th style="width:35%">Descripci&oacute;n</th>
                                                <th class="text-center" style="width:10%">Nota</th>
                                                <th class="text-center" style="width:15%">Ponderaci&oacute;n (%)</th>
                                                <th class="text-center" style="width:15%">Fecha</th>
                                                <th class="text-center" style="width:20%">Responsable</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($notas)): ?>
                                                <?php $contador = 0; ?>
                                                <?php foreach ($notas as $nota): ?>
                                                    <?php $contador++; ?>
                                                    <tr>
                                                        <td class="text-center"><?= $contador ?></td>
                                                        <td><?= $nota->has('contenido_curso') ? h($nota->contenido_curso->descripcion) : '' ?></td>
                                                        <td class="text-center"><?= h($nota->calificacion) ?></td>
                                                        <td class="text-center"><?= $nota->has('contenido_curso') ? $this->Number->toPercentage($nota->contenido_curso->ponderacion, 0) : '' ?></td>
                                                        <td class="text-center"><?= $nota->has('contenido_curso') && $nota->contenido_curso->fecha ? $nota->contenido_curso->fecha->format('d/m/Y') : '' ?></td>
                                                        <td><?= h($nota->responsable) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="6" class="text-center">Sin evaluaciones registradas.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                        <?php if (!empty($notas)): ?>
                                            <tfoot>
                                                <tr class="bg-gray">
                                                    <td class="text-center"><strong>Total</strong></td>
                                                    <td></td>
                                                    <td class="text-center"><strong><?= $sTotalNotas ?></strong></td>
                                                    <td class="text-center"><strong><?= $this->Number->format($sumaPonderaciones, ['precision' => 2]) ?></strong></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr class="bg-gray">
                                                    <td class="text-center"><strong>Final</strong></td>
                                                    <td></td>
                                                    <td class="text-center"><strong><?= $sFinalNotas ?></strong></td>
                                                    <td class="text-center">
                                                        <?php if ($sumaPonderaciones > 100 && $nIndicadores > 0): ?>
                                                            <strong><?= $this->Number->format($sumaPonderaciones / $nIndicadores, ['precision' => 2]) ?></strong>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        <?php endif; ?>
                                    </table>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="callout callout-info">
                <i class="fa fa-info-circle"></i>&nbsp;<strong>No hay cursos inscritos para este estudiante.</strong>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.tabla-notas { table-layout: fixed; width: 100%; }
.tabla-notas td { word-break: break-word; }
</style>
<script>
    (function () {
        var $btns = $('.btn-periodo');
        var $boxes = $('.periodo-box');

        $btns.on('click', function (e) {
            e.preventDefault();
            var pid = $(this).data('periodo');
            $btns.removeClass('active btn-info').addClass('btn-default');
            $(this).addClass('active btn-info').removeClass('btn-default');
            $boxes.hide();
            $('#periodo-' + pid).show();
        });
    })();
</script>
