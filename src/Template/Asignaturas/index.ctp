<div class="row">
    <div class="col-xs-12">
        <div class="box box-info box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Lista de Asignaturas</h3>
                <div class="box-tools pull-right">
                    <?= $this->Html->link('<i class="fa fa-print"></i>',
                        ['controller' => 'Reportes', 'action' => 'listarAsignaturas'], 
                        ['class'=>'btn btn-box-tool','escape' => false]) 
                    ?>
			        <button type="button" class="btn btn-box-tool" id="goSearch" title="Buscar">
				        <i class="fa fa-search"></i>
			        </button>
			        <button type="button" class="btn btn-box-tool" data-widget="collapse">
				        <i class="fa fa-minus"></i>
			        </button>
                    <?= $this->Html->link('<i class="fa fa-times"></i>',
                        ['action' => 'homepage'], ['class'=>'btn btn-box-tool','escape' => false]) 
                    ?>
                </div>
            </div>        
            <div class="box-body table-responsive no-padding">
		        <div class="oculto" id="buscar">
			        <?= $this->element('search_form', ['title' => 'Buscar Asignatura', 'searchFields' => $searchFields, 'filtros' => $filtros]);?>
		        </div>
                <table class="table table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('codigo', 'Código') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('nombre') ?></th>
                            <!--
                            <th scope="col"><?= $this->Paginator->sort('horas_teoricas') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('horas_practicas') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('frecuencia') ?></th>
                            -->
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('creditos', 'Créditos') ?></th>
                            <!--
                            <th scope="col"><?= $this->Paginator->sort('costo') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('grupo_asignatura_id') ?></th>
                            -->
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('activa') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('created') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('modified') ?></th>
                            <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($asignaturas as $asignatura): ?>
                            <tr>
                                <td><?= $this->Number->format($asignatura->id) ?></td>
                                <td><?= h($asignatura->codigo) ?></td>
                                <td><?= h($asignatura->nombre) ?></td>
                                <!--
                                <td><?= $this->Number->format($asignatura->horas_teoricas) ?></td>
                                <td><?= $this->Number->format($asignatura->horas_practicas) ?></td>
                                <td><?= $this->Number->format($asignatura->frecuencia) ?></td>
                                -->
                                <td class="text-center"><?= $this->Number->format($asignatura->creditos) ?></td>
                                <!--
                                <td><?= $this->Number->format($asignatura->costo) ?></td>
                                <td><?= $asignatura->has('grupo_asignatura') ? $this->Html->link($asignatura->grupo_asignatura->id, ['controller' => 'GrupoAsignaturas', 'action' => 'view', $asignatura->grupo_asignatura->id]) : '' ?></td>
                                -->
                                <td class="text-center"><?= h($asignatura->activa) ? __('Yes') : __('No') ?></td>
                                <td class="text-center"><?= h($asignatura->created) ?></td>
                                <td class="text-center"><?= h($asignatura->modified) ?></td>
                                <td class="actions text-center">
                                    <?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $asignatura->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                    <?= $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $asignatura->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                    <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['action' => 'delete', $asignatura->id], ['confirm' => __('Are you sure you want to delete # {0}?', $asignatura->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="no-padding">
                        <tr>
                            <td colspan="8" class="text-center">
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
                    ['action' => 'add'], ['class'=>'btn btn-success pull-left','escape' => false]) 
                ?>
                <?= $this->Html->link('<i class="fa fa-times"></i>&nbsp;'.__('Go Back'),
                    ['action' => 'homepage'], ['class'=>'btn bg-maroon pull-right','escape' => false]) 
                ?>
            </div>
        </div>
    </div>
</div>
