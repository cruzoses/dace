<div class="row">
    <div class="col-xs-12">
        <div class="box box-info box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Empleados</h3>
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
                            <th scope="col"><?= $this->Paginator->sort('cedula') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('nombres') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('apellidos') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('fecha_nacimiento') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('sexo') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('email') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('telefonos') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('token') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('usuario_id') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('activo') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                            <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($empleados as $empleado): ?>
                        <tr>
                                    <td><?= $this->Number->format($empleado->id) ?></td>
                                            <td><?= $this->Number->format($empleado->cedula) ?></td>
                                        <td><?= h($empleado->nombres) ?></td>
                                        <td><?= h($empleado->apellidos) ?></td>
                                        <td><?= h($empleado->fecha_nacimiento) ?></td>
                                        <td><?= h($empleado->sexo) ?></td>
                                        <td><?= h($empleado->email) ?></td>
                                        <td><?= h($empleado->telefonos) ?></td>
                                        <td><?= h($empleado->token) ?></td>
                                            <td><?= $empleado->has('usuario') ? $this->Html->link($empleado->usuario->alias, ['controller' => 'Usuarios', 'action' => 'view', $empleado->usuario->id]) : '' ?></td>
                                            <td><?= h($empleado->activo) ?></td>
                                        <td><?= h($empleado->created) ?></td>
                                        <td><?= h($empleado->modified) ?></td>
                            <td class="actions text-center">
                                <?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $empleado->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                <?= $this->Html->link('<i class="fa fa-pencil"></i>', ['action' => 'edit', $empleado->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['action' => 'delete', $empleado->id], ['confirm' => __('Are you sure you want to delete # {0}?', $empleado->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="box-footer">
                <?= $this->Html->link('<i class="fa fa-plus"></i>&nbsp;'.__('New'), 
                    ['action' => 'add'], ['class'=>'btn btn-success pull-left','escape' => false]) 
                ?>
                <?= $this->Html->link('<i class="fa fa-close"></i>&nbsp;'.__('Go Back'), '/', ['class'=>'btn bg-maroon pull-right','escape' => false]) ?>
            </div>
        </div>
    </div>
</div>
