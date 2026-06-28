<div class="row">
    <div class="col-xs-12">
        <div class="box box-info box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Usuarios</h3>
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
                            <th scope="col"><?= $this->Paginator->sort('username') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('password') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('activo') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                            <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= $this->Number->format($usuario->id) ?></td>
                                    <td><?= $this->Number->format($usuario->cedula) ?></td>
                                <td><?= h($usuario->nombres) ?></td>
                                <td><?= h($usuario->apellidos) ?></td>
                                <td><?= h($usuario->fecha_nacimiento) ?></td>
                                <td><?= h($usuario->sexo) ?></td>
                                <td><?= h($usuario->email) ?></td>
                                <td><?= h($usuario->telefonos) ?></td>
                                <td><?= h($usuario->username) ?></td>
                                <td><?= h($usuario->password) ?></td>
                                <td><?= h($usuario->activo) ?></td>
                                <td><?= h($usuario->created) ?></td>
                                <td><?= h($usuario->modified) ?></td>
                            <td class="actions text-center">
                                <?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $usuario->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                <?= $this->Html->link('<i class="fa fa-pencil"></i>', ['action' => 'edit', $usuario->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['action' => 'delete', $usuario->id], ['confirm' => __('Are you sure you want to delete # {0}?', $usuario->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
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
