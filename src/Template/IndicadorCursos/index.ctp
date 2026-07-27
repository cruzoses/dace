<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-book"></i>&nbsp;Ind&iacute;cadores del Plan de Evaluaci&oacute;n
                </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                    <?= $this->Html->link('<i class="fas fa-sign-in-alt"></i>',
                        ['controller' => 'profesores','action' => 'listadeclase',$nCursoId],
                        ['class' => 'btn btn-box-tool', 'title' => 'cerrar', 'escape' => false]);
                    ?>
                </div>
        </div>
        <div class="box-body table-responsive">
            <table class="table table-bordered table-condensed">
                <tr>
                    <th class="bg-gray text-center">C&oacute;digo</th>
                    <th class="bg-gray text-center">Cr&eacute;ditos</th>
                    <th class="bg-gray text-center">Asignatura</th>
                    <th class="bg-gray text-center">Secci&oacute;n</th>
                </tr>
                <tr>
                    <td class="text-center"><?= $oCurso->asignatura->codigo;?></td>
                    <td class="text-center"><?= $oCurso->asignatura->creditos;?></td>
                    <td class="text-center"><?= $oCurso->asignatura->nombre;?></td>
                    <td class="text-center"><?= $oCurso->seccion;?></td>
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
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Indicadores de Curso</h3>
                <div class="box-tools pull-right">
			        <button type="button" class="btn btn-box-tool" data-widget="collapse">
				        <i class="fa fa-minus"></i>
			        </button>
                    <?= $this->Html->link('<i class="fa fa-times"></i>',
                        ['controller' => 'profesores','action' => 'listadeclase',$nCursoId],
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
                            <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('curso_id') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('indicador_id') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('desde') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('hasta') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('escala_nota') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('porcentaje') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('created') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('modified') ?></th>
                            <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($indicadorCursos as $indicadorCurso): ?>
                            <tr>
                                <td><?= $this->Number->format($indicadorCurso->id) ?></td>
                                <td><?= $indicadorCurso->has('curso') ? $indicadorCurso->curso->id : '' ?></td>
                                <td><?= $indicadorCurso->has('indicadore') ? $indicadorCurso->indicadore->nombre : '' ?></td>
                                <td class="text-center"><?= h($indicadorCurso->desde) ?></td>
                                <td class="text-center"><?= h($indicadorCurso->hasta) ?></td>
                                <td class="text-center"><?= $aEscala[ $indicadorCurso->escala_nota ] ?></td>
                                <td class="text-center"><?= $this->Number->toPercentage($indicadorCurso->porcentaje,0) ?></td>
                                <td class="text-center"><?= h($indicadorCurso->created) ?></td>
                                <td class="text-center"><?= h($indicadorCurso->modified) ?></td>
                                <td class="actions text-center">
                                    <?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $indicadorCurso->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                    <?= $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $indicadorCurso->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                    <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['action' => 'delete', $indicadorCurso->id], ['confirm' => __('Are you sure you want to delete # {0}?', $indicadorCurso->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="no-padding">
                        <tr>
                            <td colspan="10" class="text-center">
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
            <div class="box-footer">
                <?= $this->Html->link('<i class="fa fa-plus"></i>&nbsp;'.__('New'), 
                    ['action' => 'add',$nCursoId], ['class'=>'btn btn-success pull-left','escape' => false]) 
                ?>
                <?= $this->Html->link('<i class="fa fa-times"></i>&nbsp;'.__('Go Back'),
                    ['controller' => 'profesores', 'action' => 'listadeclase',$nCursoId], 
                    ['class'=>'btn bg-maroon pull-right','escape' => false]) 
                ?>
            </div>
        </div>
    </div>
</div>
