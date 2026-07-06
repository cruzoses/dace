<div class="row">
    <div class="col-xs-12">
        <div class="box box-info box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Lista de Empleados</h3>
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
			        <?= $this->element('search_form', [
                        'title' => 'Buscar Empleado', 'searchFields' => $searchFields, 
                        'filtros' => $filtros]);
                    ?>
		        </div>
                <table class="table table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('cedula') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('nombres') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('apellidos') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('fecha_nacimiento') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('sexo') ?></th>
                            <!--
                            <th scope="col"><?= $this->Paginator->sort('email') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('telefonos') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('token') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('usuario_id') ?></th>
                            -->
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('activo') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('created') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('modified') ?></th>
                            <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $this->Paginator->options(['url' => $filtros]); ?>
                        <?php foreach ($empleados as $empleado): ?>
                            <tr>
                                <td><?= $this->Number->format($empleado->id) ?></td>
                                <td><?= $this->Number->format($empleado->cedula) ?></td>
                                <td><?= h($empleado->nombres) ?></td>
                                <td><?= h($empleado->apellidos) ?></td>
                                <td class="text-center"><?= h($empleado->fecha_nacimiento) ?></td>
                                <td class="text-center"><?= h($empleado->sexo) ?></td>
                                <!--
                                <td><?= h($empleado->email) ?></td>
                                <td><?= h($empleado->telefonos) ?></td>
                                <td><?= h($empleado->token) ?></td>
                                <td><?= $empleado->has('usuario') ? $this->Html->link($empleado->usuario->alias, ['controller' => 'Usuarios', 'action' => 'view', $empleado->usuario->id]) : '' ?></td>
                                -->
                                <td class="text-center"><?= h($empleado->activo) ? 'SI' : 'NO' ?></td>
                                <td class="text-center"><?= h($empleado->created->format('d-m-Y g:i a')) ?></td>
                                <td class="text-center"><?= h($empleado->modified->format('d-m-Y g:i a')) ?></td>
                                <td class="actions text-center">
                                    <?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $empleado->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                    <?= $this->Html->link('<i class="fa fa-pencil"></i>', ['action' => 'edit', $empleado->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                    <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['action' => 'delete', $empleado->id], ['confirm' => __('Are you sure you want to delete # {0}?', $empleado->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
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
