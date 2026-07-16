<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Estudiante $estudiante
 * @var array $situaciones
*/
?>
<div class="content">
    <div class="row">
        <?= $this->element('Datos/ficha', ['estudiante' => $estudiante, 'showOptions' => true]); ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php if (!empty($situaciones)): ?>
                <?php foreach ($situaciones as $item): ?>
                    <?php $programa = $item['programa']; ?>
                    <?php $asignaturas = $item['asignaturas']; ?>
                    <?php $totalCreditosPrograma = $item['totalCreditosPrograma']; ?>
                    <?php $totalAsignaturas = $item['totalAsignaturas']; ?>
                    <?php $totalCreditosAprobados = $item['totalCreditosAprobados']; ?>
                    <?php $totalAsignaturasAprobadas = $item['totalAsignaturasAprobadas']; ?>
                    <?php $porcentajeAprobado = $item['porcentajeAprobado']; ?>
                    <?php $mallasPorAsignatura = $item['mallasPorAsignatura']; ?>
                    <?php $notaMinimaPrograma = (float)$programa->programa->nota_minima; ?>

                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <i class="fa fa-graduation-cap"></i>&nbsp;
                                <?= h($programa->programa->codigo) ?> - <?= h($programa->programa->nombre) ?>
                                &nbsp;|&nbsp;<?= h($programa->carrera->codigo) ?> - <?= h($programa->carrera->nombre) ?>
                            </h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-bordered table-hover table-condensed">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width:40px">No</th>
                                        <th>Trayecto</th>
                                        <th>Cod. Asignatura</th>
                                        <th class="text-center" style="width:70px">Cr&eacute;ditos</th>
                                        <th>Nombre Asignatura</th>
                                        <th class="text-center" style="width:60px">Nota</th>
                                        <th class="text-center" style="width:70px">Secci&oacute;n</th>
                                        <th>Per&iacute;odo</th>
                                        <th>Responsable</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($asignaturas)): ?>
                                        <?php $cont = 0; ?>
                                        <?php foreach ($asignaturas as $asig): ?>
                                            <?php
                                            $cont++;
                                            $aprobada = false;
                                            if (!empty($asig->calificacion)) {
                                                $notaMinima = $notaMinimaPrograma;
                                                if ($asig->has('asignatura') && isset($mallasPorAsignatura[$asig->asignatura_id]) && !empty($mallasPorAsignatura[$asig->asignatura_id]->nota_minima)) {
                                                    $notaMinima = (float)$mallasPorAsignatura[$asig->asignatura_id]->nota_minima;
                                                }
                                                $aprobada = (float)$asig->calificacion >= $notaMinima;
                                            }
                                            ?>
                                            <tr>
                                                <td class="text-center"><?= $cont ?></td>
                                                <td><?= $asig->has('trayecto') ? h($asig->trayecto->codename) : '' ?></td>
                                                <td><?= $asig->has('asignatura') ? h($asig->asignatura->codigo) : '' ?></td>
                                                <td class="text-center"><?= $asig->has('asignatura') ? $this->Number->format($asig->asignatura->creditos) : '' ?></td>
                                                <td><?= $asig->has('asignatura') ? h($asig->asignatura->nombre) : '' ?></td>
                                                <td class="text-center" style="<?= !empty($asig->calificacion) ? ($aprobada ? 'color:#0056b3;font-weight:bold' : 'color:#dc3545;font-weight:bold') : '' ?>"><?= h($asig->calificacion) ?></td>
                                                <td class="text-center"><?= !empty($asig->calificacion) ? h($asig->seccion) : '' ?></td>
                                                <td><?= !empty($asig->calificacion) && $asig->has('periodo') ? h($asig->periodo->nombre) : '' ?></td>
                                                <td><?= !empty($asig->calificacion) ? h($asig->responsable) : '' ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center">No hay asignaturas registradas en este programa.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="9" style="background-color:#f4f4f4;font-weight:bold">
                                            <div class="row" style="margin:0">
                                                <div class="col-md-4">
                                                    Total Cr&eacute;ditos del Programa: <?= $totalCreditosPrograma ?>
                                                </div>
                                                <div class="col-md-4">
                                                    Total Asignaturas: <?= $totalAsignaturas ?>
                                                </div>
                                                <div class="col-md-4">
                                                    Total Cr&eacute;ditos Aprobados:
                                                    <span style="<?= $porcentajeAprobado == 100 ? 'color:#0056b3;font-weight:bold' : 'color:#dc3545;font-weight:bold' ?>">
                                                        <?= $totalCreditosAprobados ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row" style="margin:0">
                                                <div class="col-md-4">
                                                    Total Asignaturas Aprobadas:
                                                    <span style="<?= $porcentajeAprobado == 100 ? 'color:#0056b3;font-weight:bold' : 'color:#dc3545;font-weight:bold' ?>">
                                                        <?= $totalAsignaturasAprobadas ?>
                                                    </span>
                                                </div>
                                                <div class="col-md-4">
                                                    &Iacute;ndice de la Situaci&oacute;n: (Pendiente)
                                                </div>
                                                <div class="col-md-4">
                                                    &Iacute;ndice del Proceso: (Pendiente)
                                                </div>
                                            </div>
                                            <div class="row" style="margin:0">
                                                <div class="col-md-12">
                                                    Porcentaje Aprobado:
                                                    <span style="<?= $porcentajeAprobado == 100 ? 'color:#0056b3;font-weight:bold' : 'color:#dc3545;font-weight:bold' ?>">
                                                        <?= $porcentajeAprobado ?>%
                                                    </span>
                                                </div>
                                            </div>
                                            <?php if ($porcentajeAprobado == 100): ?>
                                                <div class="row" style="margin:8px 0 0 0">
                                                    <div class="col-md-12">
                                                        <?= $this->Html->link(
                                                            '<i class="fa fa-graduation-cap"></i>&nbsp;Solicitar Acto de Grado',
                                                            ['controller' => 'Reportes', 'action' => 'actoGrado', $estudiante->id, $programa->programa_id],
                                                            ['class' => 'btn btn-success btn-flat', 'escape' => false]
                                                        ) ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="callout callout-info">
                    <p>No hay programas asociados a este estudiante.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
