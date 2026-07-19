<?php
/**
 * @var \App\Model\Entity\Malla $mallas
 * @var \App\View\AppView $this
 * @var array $searchFields
 * @var array $filtros
*/
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-sace box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Lista de Mallas</h3>
                <div class="box-tools pull-right">
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
			        <?= $this->element('search_form', ['title' => 'Buscar Malla', 'searchFields' => $searchFields, 'filtros' => $filtros]);?>
		        </div>
                <table class="table table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('carrera_id') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('programa_id') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('trayecto_id') ?></th>
                            <th scope="col">Código</th>
                            <th scope="col"><?= $this->Paginator->sort('asignatura_id') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('nota_minima') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('created') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('modified') ?></th>
                            <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mallas as $malla): ?>
                            <tr>
                                <td><?= $this->Number->format($malla->id) ?></td>
                                <td><?= $malla->has('carrera') ? h($malla->carrera->codigo) : '' ?></td>
                                <td><?= $malla->has('programa') ? h($malla->programa->codigo) : '' ?></td>
                                <td class="text-center"><?= $malla->has('trayecto') ? h($malla->trayecto->codigo) : ''  ?></td>
                                <td><?= $malla->has('asignatura') ? h($malla->asignatura->codigo) : '' ?></td>
                                <td><?= $malla->has('asignatura') ? h($malla->asignatura->nombre) : '' ?></td>
                                <td class="text-center"><?= h($malla->nota_minima) ?></td>
                                <td class="text-center"><?= h($malla->created) ?></td>
                                <td class="text-center"><?= h($malla->modified) ?></td>
                                <td class="actions text-center">
                                    <?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $malla->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                    <?= $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $malla->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                    <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['action' => 'delete', $malla->id], ['confirm' => __('Are you sure you want to delete # {0}?', $malla->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
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
                    ['action' => 'add'], ['class'=>'btn btn-success pull-left','escape' => false]) 
                ?>
                <?= $this->Html->link('<i class="fa fa-times"></i>&nbsp;'.__('Go Back'),
                    ['action' => 'homepage'], ['class'=>'btn bg-maroon pull-right','escape' => false]) 
                ?>
            </div>
        </div>
    </div>
</div>

<?php $this->start('script'); ?>
<?= $this->Html->script('mallas') ?>
<script>
var MALLAS_PROGRAMAS_URL = '<?= $this->Url->build(['controller' => 'Mallas', 'action' => 'getProgramas']) ?>';
$(document).ready(initMallasBuscar);
</script>
<?php $this->end(); ?>
