<div class="row">
    <div class="col-xs-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-info"></i>
                    PLAN DE EVALUACION&nbsp;<b><?= $oCurso->periodo->codename;?></b>
                </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                    <?= $this->Html->link('<i class="fas fa-sign-out-alt"></i>',
                        ['controller' => 'profesores','action' => 'listadeclase',$oCurso->id],
                        ['class' => 'btn btn-box-tool', 'title' => 'cerrar', 'escape' => false]);
                    ?>
                </div>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-bordered table-condensed">
                    <tr>
                        <th class="bg-gray text-center">Asignatura Id</th>
                        <th class="bg-gray text-center">C&oacute;digo</th>
                        <th class="bg-gray text-center">Cr&eacute;ditos</th>
                        <th class="bg-gray text-center">Asignatura</th>
                        <th class="bg-gray text-center">Secci&oacute;n</th>
                    </tr>
                    <tr>
                        <td class="text-center"><?php echo $oCurso->asignatura->id;?></td>
                        <td class="text-center"><?php echo $oCurso->asignatura->codigo;?></td>
                        <td class="text-center"><?php echo $oCurso->asignatura->creditos;?></td>
                        <td class="text-center"><?php echo $oCurso->asignatura->nombre;?></td>
                        <td class="text-center"><?php echo $oCurso->seccion;?></td>
                    </tr>
                    <tr>
                        <th class="bg-gray text-left" colspan="4">Docente</th>
                        <th class="bg-gray text-center" colspan="1">Asignaci&oacute;n</th>
                    </tr>
                    <tr>
                        <td class="text-left" colspan="4"><?= $oCurso->docente['codename'];?></td>
                        <td class="text-center"><?= $this->Number->format($oCurso->id) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-sace box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Plan de Evaluaci&oacute;n</h3>
                <div class="box-tools pull-right">
			        <button type="button" class="btn btn-box-tool" data-widget="collapse">
				        <i class="fa fa-minus"></i>
			        </button>
                    <?= $this->Html->link('<i class="fa fa-times"></i>',
                        ['controller' => 'profesores','action' => 'listadeclase',$oCurso->id], 
                        ['class'=>'btn btn-box-tool','escape' => false]) 
                    ?>
                </div>
            </div>        
            <div class="box-body table-responsive no-padding">
		        <div class="oculto" id="buscar">
			        <?= $this->element('buscador');?>
		        </div>
                <table class="table table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">No.</th>
                            <th scope="col" class="text-center">No.Control</th>
                            <th scope="col" class="text-center">Fecha</th>
                            <th scope="col" class="text-center">Descripci&oacute;n de la Evaluaci&oacute;n</th>
                            <th scope="col" class="text-center">Ponderaci&oacute;n</th>
                            <th scope="col" class="text-center">Indicador</th>
                            <th scope="col" class="text-center">Created</th>
                            <th scope="col" class="text-center">Modified</th>
                            <th scope="col" class="actions text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $nRow = 1; ?>
                        <?php foreach ($contenidoCursos as $contenidoCurso): ?>
                            <tr>
                                <td class="text-center"><?= $this->Number->format($nRow) ?></td>
                                <td class="text-center"><?= $this->Number->format($contenidoCurso->id) ?></td>
                                <td class="text-center"><?= h($contenidoCurso->fecha) ?></td>
                                <td><?= h($contenidoCurso->descripcion) ?></td>
                                <td class="text-center"><?= $this->Number->toPercentage($contenidoCurso->ponderacion, 0) ?></td>
                                <td class="text-center">
                                    <?= $contenidoCurso->has('indicador_curso') ? $contenidoCurso->indicador_curso->id : '' ?>
                                </td>
                                <td class="text-center"><?= h($contenidoCurso->created) ?></td>
                                <td class="text-center"><?= h($contenidoCurso->modified) ?></td>
                                <td class="actions text-center">
                                    <?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $contenidoCurso->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                    <?= $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $contenidoCurso->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                    <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['action' => 'delete', $contenidoCurso->id], ['confirm' => 'Are you sure you want to delete # ' . $contenidoCurso->id . '?', 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
                                </td>
                            </tr>
                            <?php $nRow++; ?>
                        <?php endforeach; ?>
                        <?php if ($nPorcentajeDefinido < 100): ?>
                            <tr>
                                <td colspan="9">
                                    <div class="callout callout-info">
                                        <h4><i class="fa fa-exclamation-triangle"></i>&nbsp;PLAN DE EVALUACION INCOMPLETO!</h4>
                                        <p>
                                            El Plan de Evaluaci&oacute;n est&aacute; incompleto, faltan evaluaciones por definir. Hasta el momento s&oacute;lo tiene definido el <b><?= $nPorcentajeDefinido ?>%</b>
                                            <br>Debe definir las evaluaciones correspondientes al <b><?= 100 - $nPorcentajeDefinido ?>%</b> faltante.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot class="no-padding">
                        <tr>
                            <td colspan="9" class="text-center">
                                <div class="paginator">
                                    <ul class="pagination pagination-sm">
                                        <?= $this->Paginator->first('<i class="fa fa-angle-double-left"></i>',['class' => 'btn btn-sm','escape' => false]) ?>
                                        <?= $this->Paginator->prev('<i class="fa fa-angle-left"></i>',['class' => 'btn btn-sm','escape' => false]) ?>
                                        <?= $this->Paginator->numbers(['before' => '','after' => '']) ?>
                                        <?= $this->Paginator->next('<i class="fa fa-angle-right"></i>',['class' => 'btn btn-sm','escape' => false]) ?>
                                        <?= $this->Paginator->last('<i class="fa fa-angle-double-right"></i>',['class' => 'btn btn-sm','escape' => false]) ?>
                                    </ul>
                                    <p><?= $this->Paginator->counter(['format' => 'Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total']) ?></p>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="box-footer">
                <?php if ($nPorcentajeDefinido >= 100): ?>
                    <button type="button" class="btn btn-success pull-left" disabled
                            title="El Plan de Evaluación está completo (100%)">
                        <i class="fa fa-plus"></i>&nbsp;<?= __('New') ?>
                    </button>
                <?php else: ?>
                    <?= $this->Html->link('<i class="fa fa-plus"></i>&nbsp;'.__('New'), 
                        ['action' => 'add', $nCursoId], ['class'=>'btn btn-success pull-left','escape' => false]) 
                    ?>
                <?php endif; ?>
                <?= $this->Html->link('<i class="fa fa-times"></i>&nbsp;'.__('Go Back'),
                    ['controller' => 'profesores','action' => 'listadeclase',$oCurso->id], 
                    ['class'=>'btn bg-maroon pull-right','escape' => false]) 
                ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-clipboard-list"></i>&nbsp;Resumen del Plan de Evaluaci&oacute;n</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th class="bg-gray text-center" style="width: 40%;">Descripci&oacute;n</th>
                            <th class="bg-gray text-center" style="width: 20%;">M&iacute;nimo</th>
                            <th class="bg-gray text-center" style="width: 20%;">M&aacute;ximo</th>
                            <th class="bg-gray text-center" style="width: 20%;">Definido</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><i class="fa fa-cubes"></i>&nbsp;Cantidad de Indicadores</td>
                            <td class="text-center"><?= $nIndicadoresMin ?></td>
                            <td class="text-center"><?= $nIndicadoresMax ?></td>
                            <td class="text-center"><?= $nIndicadoresDefinidos ?></td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-percent"></i>&nbsp;Porcentaje definido</td>
                            <td class="text-center">100%</td>
                            <td class="text-center">100%</td>
                            <td class="text-center"><?= $nPorcentajeDefinido ?>%</td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-list-ol"></i>&nbsp;Cantidad de Evaluaciones</td>
                            <td class="text-center">4</td>
                            <td class="text-center">20</td>
                            <td class="text-center"><?= $nEvaluacionesDefinidas ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
