<?php if (!isset($sedeId) || !$sedeId): ?>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-success box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tasks"></i>&nbsp;Reporte Avance Docente</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <?= $this->Form->create(null, [
                'type' => 'get',
                'role' => 'form',
                'class' => 'horizontal',
            ]); ?>
            <div class="box-body">
                <fieldset>
                    <?= $this->Form->control('sede_id', [
                        'label' => 'Sede <span class="text-red">*</span>',
                        'type' => 'select',
                        'options' => $sedes,
                        'empty' => '-- Seleccione --',
                        'class' => 'form-control select2',
                        'data-width' => '100%',
                        'escape' => false,
                    ]) ?>
                    <?= $this->Form->control('periodo_id', [
                        'label' => 'Periodo <span class="text-red">*</span>',
                        'type' => 'select',
                        'options' => $periodos,
                        'empty' => '-- Seleccione --',
                        'class' => 'form-control select2',
                        'data-width' => '100%',
                        'escape' => false,
                    ]) ?>
                    <?= $this->Form->control('carrera_id', [
                        'label' => 'Carrera',
                        'type' => 'select',
                        'options' => $carreras,
                        'empty' => '-- Todas --',
                        'class' => 'form-control select2',
                        'data-width' => '100%',
                    ]) ?>
                    <?= $this->Form->control('docente_id', [
                        'label' => 'Docente',
                        'type' => 'select',
                        'options' => $docentes,
                        'empty' => '-- Todos --',
                        'class' => 'form-control select2',
                        'data-width' => '100%',
                    ]) ?>
                </fieldset>
            </div>
            <div class="box-footer text-center">
                <?= $this->Form->button('<i class="fa fa-search"></i>&nbsp;Generar Reporte', [
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'escape' => false,
                ]) ?>
                <?= $this->Html->link('<i class="fa fa-times"></i>&nbsp;Cerrar',
                    '/', ['class' => 'btn bg-maroon', 'escape' => false])
                ?>
            </div>
            <?= $this->Form->end(); ?>
        </div>
    </div>
</div>
<?php else: ?>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-success box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tasks"></i>&nbsp;Avance Docente</h3>
                <div class="box-tools pull-right">
                    <?= $this->Html->link('<i class="fa fa-arrow-left"></i>&nbsp;Nuevo Reporte',
                        ['action' => 'listarAvanceDocente'],
                        ['class' => 'btn btn-default btn-xs', 'escape' => false])
                    ?>
                </div>
            </div>
            <div class="box-body">
                <p>
                    <strong>Sede:</strong> <?= h($sTituloSede) ?> &nbsp;|&nbsp;
                    <strong>Periodo:</strong> <?= h($sTituloPeriodo) ?>
                    <?php if (!empty($docenteId)): ?>
                        &nbsp;|&nbsp; <strong>Docente:</strong> <?php
                            $oDocente = \Cake\ORM\TableRegistry::getTableLocator()->get('Docentes')->get($docenteId);
                            echo h($oDocente->full_name);
                        ?>
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <div class="box box-default box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-graduation-cap"></i>&nbsp;Cursos Encontrados (<?= count($cursos) ?>)</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <?php if ($cursos->isEmpty()): ?>
                    <p class="text-center text-muted">No se encontraron cursos con los filtros seleccionados.</p>
                <?php else: ?>
                <table class="table table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Asignatura</th>
                            <th class="text-center">Sección</th>
                            <th>Docente</th>
                            <th class="text-center">Indicadores</th>
                            <th class="text-center">Plan Eval.</th>
                            <th class="text-center">Notas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($cursos as $curso): ?>
                        <?php
                            $cid = $curso->id;
                            $nInd = $conteoIndicadores[$cid] ?? 0;
                            $nCont = $conteoContenidos[$cid] ?? 0;
                            $nNotas = $conteoNotas[$cid] ?? 0;
                        ?>
                        <tr>
                            <td class="text-center"><?= $i++ ?></td>
                            <td><?= h($curso->asignatura->codename ?? '') ?></td>
                            <td class="text-center"><?= h($curso->seccion) ?></td>
                            <td><?= h($curso->docente->full_name ?? '') ?></td>
                            <td class="text-center">
                                <?php if ($nInd > 0): ?>
                                    <span class="badge bg-green"><?= $nInd ?></span>
                                <?php else: ?>
                                    <span class="badge bg-gray">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($nCont > 0): ?>
                                    <span class="badge bg-green"><?= $nCont ?></span>
                                <?php else: ?>
                                    <span class="badge bg-gray">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($nNotas > 0): ?>
                                    <span class="badge bg-green"><?= $nNotas ?></span>
                                <?php else: ?>
                                    <span class="badge bg-gray">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
            <div class="box-footer">
                <?= $this->Html->link('<i class="fa fa-arrow-left"></i>&nbsp;Volver al filtro',
                    ['action' => 'listarAvanceDocente'],
                    ['class' => 'btn btn-default', 'escape' => false])
                ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
