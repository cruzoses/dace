<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Estudiante $estudiante
 * @var array $situaciones
*/
?>
<?php $this->assign('title', 'Situación Académica'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-user"></i>&nbsp;
                <h3 class="box-title">Datos del Estudiante</h3>
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
        <?php if (!empty($situaciones)): ?>
            <?php if (count($situaciones) > 1): ?>
                <div class="box box-info">
                    <div class="box-header with-border">                        
                        <h3 class="box-title"><i class="far fa-chart-bar"></i>&nbsp;Resumen de &Iacute;ndices Acad&eacute;micos</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <tr>
                                    <th>Programa</th>
                                    <th class="text-center">Cr&eacute;ditos</th>
                                    <th class="text-center">Aprobados</th>
                                    <th class="text-center">%</th>
                                    <th class="text-center">ISA</th>
                                    <th class="text-center">IRA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($situaciones as $item): ?>
                                    <?php $prog = $item['programa']; ?>
                                        <tr>
                                            <td><?= h($prog->carrera->codigo) ?> - <?= h($prog->programa->codename) ?></td>
                                            <td class="text-center"><?= $item['totalCreditosPrograma'] ?></td>
                                            <td class="text-center"><?= $item['totalCreditosAprobados'] ?></td>
                                            <td class="text-center"><?= $item['porcentajeAprobado'] ?>%</td>
                                            <td class="text-center" style="<?= $item['isa'] >= 10 ? 'color:#0056b3;font-weight:bold' : 'color:#dc3545;font-weight:bold' ?>">
                                                <?= number_format($item['isa'], 5, '.', '') ?>
                                            </td>
                                            <td class="text-center" style="<?= $item['ira'] >= 10 ? 'color:#0056b3;font-weight:bold' : 'color:#dc3545;font-weight:bold' ?>">
                                                <?= number_format($item['ira'], 5, '.', '') ?>
                                            </td>
                                        </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
            <?php foreach ($situaciones as $item): ?>
                <?php $programa = $item['programa']; ?>
                <?php $asignaturas = $item['asignaturas']; ?>
                <?php $totalCreditosPrograma = $item['totalCreditosPrograma']; ?>
                <?php $totalAsignaturas = $item['totalAsignaturas']; ?>
                <?php $totalCreditosAprobados = $item['totalCreditosAprobados']; ?>
                <?php $totalAsignaturasAprobadas = $item['totalAsignaturasAprobadas']; ?>
                <?php $porcentajeAprobado = $item['porcentajeAprobado']; ?>
                <?php $mallasPorAsignatura = $item['mallasPorAsignatura']; ?>
                <?php $isa = $item['isa']; ?>
                <?php $ira = $item['ira']; ?>
                <?php $notaMinimaPrograma = (float)$programa->programa->nota_minima; ?>
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-graduation-cap"></i>&nbsp;<?= h($programa->programa->codename) ?></h3>
                            <div class="box-tools pull-right">
                                <?= $this->Html->link(
                                    '<i class="fa fa-print"></i>&nbsp;Imprimir',
                                    ['controller' => 'Reportes', 'action' => 'situacionAcademica', $estudiante->id, $programa->programa_id],
                                    ['class' => 'btn btn-default btn-xs', 'escape' => false, 'title' => 'Imprimir Situación Académica']
                                ) ?>
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
                                        <th class="text-center">Trayecto</th>
                                        <th>Asignatura</th>
                                        <th class="text-center" style="width:70px">Cr&eacute;ditos</th>
                                        <th>Nombre Asignatura</th>
                                        <th class="text-center" style="width:60px">Nota</th>
                                        <th class="text-center" style="width:70px">Secci&oacute;n</th>
                                        <th class="text-center">Per&iacute;odo</th>
                                        <th class="text-center">Responsable</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($asignaturas)): ?>
                                        <?php $cont = 0; ?>
                                        <?php foreach ($asignaturas as $asig): ?>
                                            <?php
                                                $cont++;
                                                $aprobada = false;
                                                $esCualitativa = false;
                                                $notaMinimaFila = $notaMinimaPrograma;
                                                if (!empty($asig->calificacion)) {
                                                    $esCualitativa = $asig->has('asignatura') && (int)$asig->asignatura->calificacion === 1;
                                                    if ($esCualitativa) {
                                                        $aprobada = strtoupper($asig->calificacion) === 'A';
                                                    } else {
                                                        if ($asig->has('asignatura') && isset($mallasPorAsignatura[$asig->asignatura_id]) && !empty($mallasPorAsignatura[$asig->asignatura_id]->nota_minima)) {
                                                            $notaMinimaFila = (float)$mallasPorAsignatura[$asig->asignatura_id]->nota_minima;
                                                        }
                                                        $aprobada = (float)$asig->calificacion >= $notaMinimaFila;
                                                    }
                                                }
                                            ?>
                                            <tr>
                                                <td class="text-center"><?= $cont ?></td>
                                                <td class="text-center"><?= $asig->has('trayecto') ? h($asig->trayecto->codigo) : '' ?></td>
                                                <td><?= $asig->has('asignatura') ? h($asig->asignatura->codigo) : '' ?></td>
                                                <td class="text-center"><?= $asig->has('asignatura') ? $this->Number->format($asig->asignatura->creditos) : '' ?></td>
                                                <td><?= $asig->has('asignatura') ? h($asig->asignatura->nombre) : '' ?></td>
                                                <td class="text-center" style="<?= !empty($asig->calificacion) ? ($aprobada ? 'color:#0056b3;font-weight:bold' : 'color:#dc3545;font-weight:bold') : '' ?>"><?= h($asig->calificacion) ?></td>
                                                <td class="text-center"><?= !empty($asig->calificacion) ? h($asig->seccion) : '' ?></td>
                                                <td><?= !empty($asig->calificacion) && $asig->has('periodo') ? h($asig->periodo->codigo) : '' ?></td>
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
                                                    &Iacute;ndice de la Situaci&oacute;n Acad&eacute;mica:
                                                    <span style="<?= $isa >= 10 ? 'color:#0056b3;font-weight:bold' : 'color:#dc3545;font-weight:bold' ?>">
                                                        <?= number_format($isa, 5, '.', '') ?>
                                                    </span>
                                                </div>
                                                <div class="col-md-4">
                                                    &Iacute;ndice del Proceso:
                                                    <span style="<?= $ira >= 10 ? 'color:#0056b3;font-weight:bold' : 'color:#dc3545;font-weight:bold' ?>">
                                                        <?= $ira ?>
                                                    </span>
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

