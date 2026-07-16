<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Estudiante $estudiante
 * @var array $situaciones
*/
?>

<div class="row">
    <div class="col-md-12">
        <?php if (!empty($situaciones)): ?>
            <?php if (count($situaciones) > 1): ?>
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-bar-chart"></i>&nbsp;Resumen de &Iacute;ndices Acad&eacute;micos</h3>
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
                                            <?= $item['isa'] ?>
                                        </td>
                                        <td class="text-center" style="<?= $item['ira'] >= 10 ? 'color:#0056b3;font-weight:bold' : 'color:#dc3545;font-weight:bold' ?>">
                                            <?= $item['ira'] ?>
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
                                        <tr data-id="<?= $asig->id ?>" data-tipo="<?= $esCualitativa ? 1 : 0 ?>" data-nota-minima="<?= $notaMinimaFila ?>">
                                            <td class="text-center"><?= $cont ?></td>
                                            <td class="text-center"><?= $asig->has('trayecto') ? h($asig->trayecto->codigo) : '' ?></td>
                                            <td>
                                                <?php if ($asig->has('asignatura')): ?>
                                                    <?php if ($programa->programa->califica): ?>
                                                        <?= $this->Html->link(h($asig->asignatura->codigo), '#',[
                                                            'class' => 'btn-link btn-calificar',
                                                            'data-id' => $asig->id,
                                                            'data-nombre' => h($asig->asignatura->codename),
                                                            'title' => 'Cargar calificación']) 
                                                        ?>
                                                    <?php else: ?>
                                                        <?= h($asig->asignatura->codigo) ?>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
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
                                                    <span id="total-creditos-aprobados-<?= $programa->programa_id ?>" style="<?= $porcentajeAprobado == 100 ? 'color:#0056b3;font-weight:bold' : 'color:#dc3545;font-weight:bold' ?>">
                                                        <?= $totalCreditosAprobados ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row" style="margin:0">
                                                <div class="col-md-4">
                                                    Total Asignaturas Aprobadas:
                                                    <span id="total-asignaturas-aprobadas-<?= $programa->programa_id ?>" style="<?= $porcentajeAprobado == 100 ? 'color:#0056b3;font-weight:bold' : 'color:#dc3545;font-weight:bold' ?>">
                                                        <?= $totalAsignaturasAprobadas ?>
                                                    </span>
                                                </div>
                                                <div class="col-md-4">
                                                    &Iacute;ndice de la Situaci&oacute;n Acad&eacute;mica:
                                                    <span id="isa-<?= $programa->programa_id ?>" style="<?= $isa >= 10 ? 'color:#0056b3;font-weight:bold' : 'color:#dc3545;font-weight:bold' ?>">
                                                        <?= $isa ?>
                                                    </span>
                                                </div>
                                                <div class="col-md-4">
                                                    &Iacute;ndice del Proceso:
                                                    <span id="ira-<?= $programa->programa_id ?>" style="<?= $ira >= 10 ? 'color:#0056b3;font-weight:bold' : 'color:#dc3545;font-weight:bold' ?>">
                                                        <?= $ira ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row" style="margin:0">
                                                <div class="col-md-12">
                                                    Porcentaje Aprobado:
                                                    <span id="porcentaje-aprobado-<?= $programa->programa_id ?>" style="<?= $porcentajeAprobado == 100 ? 'color:#0056b3;font-weight:bold' : 'color:#dc3545;font-weight:bold' ?>">
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


<div class="modal fade" id="modal-calificacion" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-aqua">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i>&nbsp;Calificación</h4>
            </div>
            <div class="modal-body text-center">
                <i class="fa fa-refresh fa-spin fa-3x"></i>
            </div>
        </div>
    </div>
</div>

<script>
$(document).on('click', '.btn-calificar', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    var nombre = $(this).data('nombre');
    $('#modal-calificacion .modal-title').html('<i class="fa fa-pencil-square-o"></i>&nbsp;' + nombre);
    $('#modal-calificacion .modal-body').html('<div class="text-center"><i class="fa fa-refresh fa-spin fa-3x"></i></div>');
    $('#modal-calificacion').modal('show');

    $.ajax({
        url: '<?= $this->Url->build(['controller' => 'SituacionEstudiantes', 'action' => 'califica']) ?>/' + id,
        type: 'GET',
        success: function(html) {
            $('#modal-calificacion .modal-body').removeClass('text-center').html(html);
            $('#modal-calificacion .modal-body .select2').select2({
                language: 'es',
                placeholder: 'Seleccione una Opción',
                allowClear: true,
                width: 'resolve',
                dropdownParent: $('#modal-calificacion')
            });
        },
        error: function() {
            $('#modal-calificacion .modal-body').html('<div class="alert alert-danger">Error al cargar el formulario.</div>');
        }
    });
});

$(document).on('submit', '#form-calificar', function(e) {
    e.preventDefault();
    var form = $(this);
    var url = form.attr('action');

    $.ajax({
        url: url,
        type: 'POST',
        data: form.serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                var d = response.data;
                var tr = $('tr[data-id="' + d.id + '"]');

                if (tr.length) {
                    var tipo = parseInt(tr.data('tipo'));
                    var notaMinima = parseFloat(tr.data('nota-minima'));
                    var aprobada = false;

                    if (tipo === 1) {
                        aprobada = d.calificacion.toUpperCase() === 'A';
                    } else {
                        aprobada = parseFloat(d.calificacion) >= notaMinima;
                    }

                    var styleNota = d.calificacion
                        ? (aprobada ? 'color:#0056b3;font-weight:bold' : 'color:#dc3545;font-weight:bold')
                        : '';

                    tr.find('td:eq(5)').attr('style', styleNota).text(d.calificacion);
                    tr.find('td:eq(6)').text(d.calificacion ? d.seccion : '');
                    tr.find('td:eq(7)').text(d.calificacion ? d.periodo : '');
                    tr.find('td:eq(8)').text(d.calificacion ? d.responsable : '');
                }

                $('#modal-calificacion').modal('hide');
                toastr.success(response.message);

                var pid = d.programa_id;
                var color = d.porcentajeAprobado == 100 ? 'color:#0056b3;font-weight:bold' : 'color:#dc3545;font-weight:bold';
                $('#total-creditos-aprobados-' + pid).text(d.totalCreditosAprobados).attr('style', color);
                $('#total-asignaturas-aprobadas-' + pid).text(d.totalAsignaturasAprobadas).attr('style', color);
                $('#porcentaje-aprobado-' + pid).text(d.porcentajeAprobado + '%').attr('style', color);

                var isaStyle = d.isa >= 10 ? 'color:#0056b3;font-weight:bold' : 'color:#dc3545;font-weight:bold';
                $('#isa-' + pid).text(d.isa).attr('style', isaStyle);

                var iraStyle = d.ira >= 10 ? 'color:#0056b3;font-weight:bold' : 'color:#dc3545;font-weight:bold';
                $('#ira-' + pid).text(d.ira).attr('style', iraStyle);
            } else {
                var errorMsg = response.message || 'Error al guardar';
                if (response.errors) {
                    $.each(response.errors, function(field, msgs) {
                        if (typeof msgs === 'object') {
                            $.each(msgs, function(key, msg) {
                                errorMsg += '<br>' + msg;
                            });
                        } else {
                            errorMsg += '<br>' + msgs;
                        }
                    });
                }
                form.prepend('<div class="alert alert-danger">' + errorMsg + '</div>');
            }
        },
        error: function() {
            form.prepend('<div class="alert alert-danger">Error de conexión. Intente de nuevo.</div>');
        }
    });
});
</script>
