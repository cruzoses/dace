<?php 
/**
 * @var \App\View\AppView this
 * @var \App\Model\Entity\Rol $rols
 * @var array $filtros
 * @var array $searchFields
*/
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-sace box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Tipos de Usuarios</h3>
                <div class="box-tools pull-right">
			        <?= $this->Html->link('<i class="fa fa-print"></i>',
                        ['controller' => 'Reportes','action' => 'downloadPdf'],
					    ['class' => 'btn btn-box-tool','title' => 'Imprimir','escape' => false]);
			        ?>
			        <button type="button" class="btn btn-box-tool" data-widget="collapse">
				        <i class="fa fa-minus"></i>
			        </button>
                    <?= $this->Html->link('<i class="fa fa-times"></i>',
                        ['action' => 'homepage'], ['class'=>'btn btn-box-tool','escape' => false]) 
                    ?>
                </div>
            </div>        
            <div class="box-body table-responsive no-padding">
                <table class="table table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('nombre') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('activo') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('created') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('modified') ?></th>
                            <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rols as $rol): ?>
                        <tr>
                            <td><?= $this->Number->format($rol->id) ?></td>
                            <td><?= h($rol->nombre) ?></td>
                            <td class="text-center">
                                <?php if ($rol->activo): ?>
                                    <span class="label label-success">Activo</span>
                                <?php else: ?>
                                    <span class="label label-danger">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?= h($rol->created->format('d-m-Y g:i a')) ?></td>
                            <td class="text-center"><?= h($rol->modified->format('d-m-Y g:i a')) ?></td>
                            <td class="actions text-center">
                                <?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $rol->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                <?= $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $rol->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['action' => 'delete', $rol->id], ['confirm' => __('Are you sure you want to delete # {0}?', $rol->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
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
                    ['action' => 'add'], ['class'=>'btn btn-primary pull-left','escape' => false]) 
                ?>
                <?= $this->Html->link('<i class="fa fa-times"></i>&nbsp;'.__('Go Back'),
                    ['action' => 'homepage'], ['class'=>'btn bg-maroon pull-right','escape' => false]) 
                ?>
            </div>
        </div>
    </div>
</div>
