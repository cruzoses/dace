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
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Plan de Evaluación</h3>
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
                            <th scope="col" class="text-center">Descripción de la Evaluación</th>
                            <th scope="col" class="text-center">Ponderación</th>
                            <th scope="col" class="text-center">Indicador</th>
                            <th scope="col" class="text-center"><?= __('Created') ?></th>
                            <th scope="col" class="text-center"><?= __('Modified') ?></th>
                            <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
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
                                    <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['action' => 'delete', $contenidoCurso->id], ['confirm' => __('Are you sure you want to delete # {0}?', $contenidoCurso->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
                                </td>
                            </tr>
                            <?php $nRow++; ?>
                        <?php endforeach; ?>
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
                                    <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <?php if ($nPorcentajeDefinido < 100): ?>
            <div class="box-body">
                <div class="callout callout-warning">
                    <h4><i class="fa fa-exclamation-triangle"></i>&nbsp;PLAN DE EVALUACION INCOMPLETO!</h4>
                    <p>El Plan de Evaluaci&oacute;n est&aacute; incompleto, faltan evaluaciones por definir. Hasta el momento s&oacute;lo tiene definido el <b><?= $nPorcentajeDefinido ?>%</b>.</p>
                    <p>Debe definir las evaluaciones correspondientes al <b><?= 100 - $nPorcentajeDefinido ?>%</b> faltante.</p>
                </div>
            </div>
            <?php endif; ?>
            <div class="box-footer">
                <?= $this->Html->link('<i class="fa fa-plus"></i>&nbsp;'.__('New'), 
                    ['action' => 'add', $nCursoId], ['class'=>'btn btn-success pull-left','escape' => false]) 
                ?>
                <?= $this->Html->link('<i class="fa fa-times"></i>&nbsp;'.__('Go Back'),
                    ['controller' => 'profesores','action' => 'listadeclase',$oCurso->id], 
                    ['class'=>'btn bg-maroon pull-right','escape' => false]) 
                ?>
            </div>
        </div>
    </div>
</div>
