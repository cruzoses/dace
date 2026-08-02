<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Estudiante $estudiante
 * @var array $periodos
*/
?>
<?php $this->assign('title', 'Notas de Lapso'); ?>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-user"></i>&nbsp;Datos del Estudiante</h3>
                </div>
                <div class="box-body no-padding">
                    <table class="table table-bordered table-condensed text-center">
                        <tr>
                            <th class="bg-gray">Expediente</th>
                            <th class="bg-gray">Documento Identidad</th>
                            <th width="30%" class="bg-gray">Nombre</th>
                            <th rowspan="4" style="width:95px" class="avatar no-padding">
                                <?= $this->Html->image('site/usuario.jpg', ['class' => 'avatar img-responsive', 'alt' => 'Foto']) ?>
                            </th>
                        </tr>
                        <tr>
                            <td><?= $estudiante->expediente_formateado ?? $estudiante->expediente ?></td>
                            <td><?= $this->Number->format($estudiante->cedula) ?></td>
                            <td><?= $estudiante->full_name ?></td>
                        </tr>
                        <tr>
                            <th class="bg-gray">Usuario</th>
                            <th class="bg-gray">Correo Electr&oacute;nico</th>
                            <th class="bg-gray">Tel&eacute;fono</th>
                        </tr>
                        <tr>
                            <td><?= $estudiante->has('usuario') ? $estudiante->usuario->username : '' ?></td>
                            <td><?= $estudiante->email ?></td>
                            <td><?= $estudiante->telefonos ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php if (!empty($periodos)): ?>
                <?php
                    $periodoIds = array_keys($periodos);
                    $nPeriodos = count($periodoIds);
                ?>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-calendar"></i>&nbsp;Periodos</h3>
                    </div>
                    <div class="box-body">
                        <?php foreach ($periodoIds as $i => $pid): ?>
                            <?php $periodoNav = $periodos[$pid]['periodo']; ?>
                            <?php if ($nPeriodos > 3 && $i === 3): ?>
                                <div class="btn-group" style="margin-left:5px">
                            <?php endif; ?>
                            <a href="#periodo-<?= $pid ?>" class="btn btn-info btn-sm" style="margin-bottom:4px">
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
                    <div class="box box-info" id="periodo-<?= $pid ?>">
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
                                    foreach ($notas as $nota) {
                                        if ($nota->has('contenido_curso')) {
                                            $sumaPonderaciones += (float)$nota->contenido_curso->ponderacion;
                                        }
                                    }

                                    $tipoCalificacion = $curso->has('asignatura') ? (int)$curso->asignatura->calificacion : 0;

                                    if ($tipoCalificacion === 1) {
                                        $nA = 0;
                                        $nR = 0;
                                        foreach ($notas as $nota) {
                                            $sNota = strtoupper((string)$nota->calificacion);
                                            if ($sNota === 'A') {
                                                $nA++;
                                            } elseif ($sNota === 'R') {
                                                $nR++;
                                            }
                                        }
                                        $sTotalNotas = ($nA > $nR) ? 'Aprobada' : 'Reprobada';
                                    } else {
                                        $nEscala = 0;
                                        foreach ($notas as $nota) {
                                            if ($nota->has('contenido_curso') && $nota->contenido_curso->has('indicador_curso')) {
                                                $nEscala = (int)$nota->contenido_curso->indicador_curso->escala_nota;
                                                break;
                                            }
                                        }

                                        $nTotalNotas = 0;
                                        foreach ($notas as $nota) {
                                            $nNota = (float)$nota->calificacion;
                                            if ($nEscala === 1) {
                                                $nTotalNotas += $nNota * ((float)$nota->contenido_curso->ponderacion / 100);
                                            } else {
                                                $nTotalNotas += $nNota;
                                            }
                                        }

                                        if ($nEscala === 3) {
                                            $sTotalNotas = $this->Number->format(
                                                $this->EscalaNotas->aEscala20($nTotalNotas),
                                                ['precision' => 0]
                                            );
                                        } else {
                                            $sTotalNotas = $this->Number->format($nTotalNotas, ['precision' => 2]);
                                        }
                                    }
                                ?>
                                <div class="box box-default">
                                    <div class="box-header with-border">
                                        <h3 class="box-title" style="font-weight:bold">
                                            <?= $curso->has('asignatura') ? h($curso->asignatura->codigo) : '' ?>
                                            - <?= $curso->has('asignatura') ? h($curso->asignatura->nombre) : '' ?>
                                            - <?= h($curso->seccion) ?>
                                        </h3>
                                        <div class="box-tools pull-right">
                                            <span class="badge bg-light-blue"><?= count($notas) ?> calificaciones</span>
                                        </div>
                                    </div>
                                    <div class="box-body table-responsive no-padding">
                                        <table class="table table-bordered table-hover table-condensed">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="width:45px">No.</th>
                                                    <th>Descripci&oacute;n</th>
                                                    <th class="text-center" style="width:90px">Nota</th>
                                                    <th class="text-center" style="width:120px">Ponderaci&oacute;n (%)</th>
                                                    <th class="text-center" style="width:110px">Fecha</th>
                                                    <th class="text-center">Responsable</th>
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
                                                            <td class="text-center"><?= $nota->has('contenido_curso') ? $this->Number->toPercentage($nota->contenido_curso->ponderacion,0) : '' ?></td>
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
                    <p>No hay cursos inscritos para este estudiante.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
