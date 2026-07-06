<div class="row">
    <div class="col-xs-12">
        <div class="box box-info box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Auditorias</h3>
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
                            <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('usuario_id') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('fecha') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('evento') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('host') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('agente') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                            <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($auditorias as $auditoria): ?>
                        <tr>
                                    <td><?= $this->Number->format($auditoria->id) ?></td>
                                            <td><?= $auditoria->has('usuario') ? $this->Html->link($auditoria->usuario->alias, ['controller' => 'Usuarios', 'action' => 'view', $auditoria->usuario->id]) : '' ?></td>
                                            <td><?= h($auditoria->fecha) ?></td>
                                        <td><?= h($auditoria->evento) ?></td>
                                        <td><?= h($auditoria->host) ?></td>
                                        <td><?= h($auditoria->agente) ?></td>
                                        <td><?= h($auditoria->created) ?></td>
                                        <td><?= h($auditoria->modified) ?></td>
                            <td class="actions text-center">
                                <?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $auditoria->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                <?= $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $auditoria->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['action' => 'delete', $auditoria->id], ['confirm' => __('Are you sure you want to delete # {0}?', $auditoria->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="no-padding">
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="paginator">
                                    <ul class="pagination">
                                        <?= $this->Paginator->first('<< ' . __('first')) ?>
                                        <?= $this->Paginator->prev('< ' . __('previous')) ?>
                                        <?= $this->Paginator->numbers() ?>
                                        <?= $this->Paginator->next(__('next') . ' >') ?>
                                        <?= $this->Paginator->last(__('last') . ' >>') ?>
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
                <?= $this->Html->link('<i class="fa fa-times"></i>&nbsp;'.__('Go Back'), '/', ['class'=>'btn bg-maroon pull-right','escape' => false]) ?>
            </div>
        </div>
    </div>
</div>
