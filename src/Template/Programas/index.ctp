<div class="row">
    <div class="col-xs-12">
        <div class="box box-info box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Lista de Programas</h3>
                <div class="box-tools pull-right">
			        <button type="button" class="btn btn-box-tool" id="goSearch" title="Buscar">
				        <i class="fa fa-search"></i>
			        </button>
			        <button type="button" class="btn btn-box-tool" data-widget="collapse">
				        <i class="fa fa-minus"></i>
			        </button>
                    <?= $this->Html->link('<i class="fa fa-close"></i>',
                        ['action' => 'homepage'], ['class'=>'btn btn-box-tool','escape' => false]) 
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
                            <th scope="col"><?= $this->Paginator->sort('codigo') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('nombre') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('carrera_id') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('subsistema_id') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('nota_minima') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('creditos') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('activo') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                            <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($programas as $programa): ?>
                            <tr>
                                <td><?= $this->Number->format($programa->id) ?></td>
                                        <td><?= h($programa->codigo) ?></td>
                                        <td><?= h($programa->nombre) ?></td>
                            <td><?= $programa->has('carrera') ? $this->Html->link($programa->carrera->nombre, ['controller' => 'Carreras', 'action' => 'view', $programa->carrera->id]) : '' ?></td>
                                <td><?= $programa->has('subsistema') ? $this->Html->link($programa->subsistema->id, ['controller' => 'Subsistemas', 'action' => 'view', $programa->subsistema->id]) : '' ?></td>
                                            <td><?= h($programa->nota_minima) ?></td>
                                        <td><?= $this->Number->format($programa->creditos) ?></td>
                                        <td><?= h($programa->activo) ?></td>
                                        <td><?= h($programa->created) ?></td>
                                        <td><?= h($programa->modified) ?></td>
                                <td class="actions text-center">
                                    <?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $programa->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                    <?= $this->Html->link('<i class="fa fa-pencil"></i>', ['action' => 'edit', $programa->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                    <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['action' => 'delete', $programa->id], ['confirm' => __('Are you sure you want to delete # {0}?', $programa->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="no-padding">
                        <tr>
                            <td colspan="6" class="text-center">
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
                <?= $this->Html->link('<i class="fa fa-close"></i>&nbsp;'.__('Go Back'),
                    ['action' => 'homepage'], ['class'=>'btn bg-maroon pull-right','escape' => false]) 
                ?>
            </div>
        </div>
    </div>
</div>
