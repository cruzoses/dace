<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Docente $docente
 * @var array $searchFields
 * @var array $filtros
 * @var array $docentes
*/
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-info box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Lista de Docentes</h3>
                <div class="box-tools pull-right">
                    <?= $this->Html->link('<i class="fa fa-file-excel-o"></i>',
                        ['controller' => 'archivos','action' => 'exportarDocentes'],
                        ['class'=>'btn btn-box-tool','escape' => false]) 
                    ?>
                    <?= $this->Html->link('<i class="fa fa-print"></i>',
                        ['controller' => 'reportes', 'action' => 'listarDocentes'], 
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
			        <?= $this->element('search_form', ['title' => 'Buscar Docente', 'searchFields' => $searchFields, 'filtros' => $filtros]);?>
		        </div>
                <table class="table table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('cedula', ['Cédula']) ?></th>
                            <th scope="col"><?= $this->Paginator->sort('nombres') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('apellidos') ?></th>
                            <!--th scope="col"><?= $this->Paginator->sort('fecha_nacimiento') ?></th-->
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('sexo') ?></th>
                            <!--
                            <th scope="col"><?= $this->Paginator->sort('email') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('telefonos') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('departamento_id') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('token') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('usuario_id') ?></th>                            
                            <th scope="col"><?= $this->Paginator->sort('activo') ?></th>
                            -->
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('created') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('modified') ?></th>
                            <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $this->Paginator->options(['url' => $filtros]); ?>
                        <?php foreach ($docentes as $docente): ?>
                            <tr>
                                <td><?= $this->Number->format($docente->id) ?></td>
                                <td><?= $this->Number->format($docente->cedula) ?></td>
                                <td><?= h($docente->nombres) ?></td>
                                <td><?= h($docente->apellidos) ?></td>
                                <!--td><?= h($docente->fecha_nacimiento) ?></td-->
                                <td class="text-center"><?= h($docente->sexo) ?></td>
                                <!--
                                <td><?= h($docente->email) ?></td>
                                <td><?= h($docente->telefonos) ?></td>
                                <td><?= $docente->has('departamento') ? $this->Html->link($docente->departamento->id, ['controller' => 'Departamentos', 'action' => 'view', $docente->departamento->id]) : '' ?></td>
                                <td><?= h($docente->token) ?></td>
                                <td><?= $docente->has('usuario') ? $this->Html->link($docente->usuario->alias, ['controller' => 'Usuarios', 'action' => 'view', $docente->usuario->id]) : '' ?></td>
                                <td><?= h($docente->activo) ?></td>
                                -->
                                <td class="text-center"><?= h($docente->created) ?></td>
                                <td class="text-center"><?= h($docente->modified) ?></td>
                                <td class="actions text-center">
                                    <?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $docente->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                    <?= $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $docente->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                    <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['action' => 'delete', $docente->id], ['confirm' => __('Are you sure you want to delete # {0}?', $docente->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
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
