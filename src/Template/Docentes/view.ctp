<div class="row">
	<div class="col-md-12">
		<div class="box box-info box-solid">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-info"></i>&nbsp;Docente</h3>
				<div class="box-tools pull-right">
			        <button type="button" class="btn btn-box-tool" data-widget="collapse">
				        <i class="fa fa-minus"></i>
			        </button>
			        <?= $this->Html->link('<i class="fa fa-close"></i>',
				        ['action' => 'index'],['class'=>'btn btn-box-tool','title'=>'cerrar','escape'=>false]);
			        ?>
		        </div>
        	</div>        
        	<div class="box-body">
          		<dl class="dl-horizontal">
                    <dt scope="row"><?= __('Nombres') ?></dt>
                    <dd><?= h($docente->nombres) ?></dd>
                    <dt scope="row"><?= __('Apellidos') ?></dt>
                    <dd><?= h($docente->apellidos) ?></dd>
                    <dt scope="row"><?= __('Sexo') ?></dt>
                    <dd><?= h($docente->sexo) ?></dd>
                    <dt scope="row"><?= __('Email') ?></dt>
                    <dd><?= h($docente->email) ?></dd>
                    <dt scope="row"><?= __('Telefonos') ?></dt>
                    <dd><?= h($docente->telefonos) ?></dd>
                    <dt scope="row"><?= __('Departamento') ?></dt>
                    <dd><?= $docente->has('departamento') ? $this->Html->link($docente->departamento->id, ['controller' => 'Departamentos', 'action' => 'view', $docente->departamento->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Token') ?></dt>
                    <dd><?= h($docente->token) ?></dd>
                    <dt scope="row"><?= __('Usuario') ?></dt>
                    <dd><?= $docente->has('usuario') ? $this->Html->link($docente->usuario->alias, ['controller' => 'Usuarios', 'action' => 'view', $docente->usuario->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Id') ?></dt>
                    <dd><?= $this->Number->format($docente->id) ?></dd>
                    <dt scope="row"><?= __('Cedula') ?></dt>
                    <dd><?= $this->Number->format($docente->cedula) ?></dd>
                    <dt scope="row"><?= __('Fecha Nacimiento') ?></dt>
                    <dd><?= h($docente->fecha_nacimiento) ?></dd>
                    <dt scope="row"><?= __('Created') ?></dt>
                    <dd><?= h($docente->created) ?></dd>
                    <dt scope="row"><?= __('Modified') ?></dt>
                    <dd><?= h($docente->modified) ?></dd>
                    <dt scope="row"><?= __('Activo') ?></dt>
                    <dd><?= $docente->activo ? __('Yes') : __('No'); ?></dd>
                </dl>
            </div>
            <div class="box-footer">
		        <?= $this->Html->link('<i class="fa fa-pencil"></i>&nbsp;'.__('Edit'),
			        ['action' => 'edit',$docente->id],['class' => 'btn bg-olive btn-flat pull-left','escape' => false]); 
		        ?>
		        <?= $this->Form->postLink('<i class="fa fa-send"></i>&nbsp;Reenviar Token',
			        ['action' => 'reenviarToken', $docente->id],
			        ['class' => 'btn btn-warning btn-flat pull-left','escape' => false, 'style' => 'margin-left:5px']); 
		        ?>
		        <?= $this->Html->link('<i class="fa fa-power-off"></i>&nbsp;Cerrar',
			        ['action' => 'index'],['class' => 'btn bg-maroon btn-flat pull-right','escape' => false]); 
		        ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box box-default box-solid">
            <div class="box-header with-border">                
                <h3 class="box-title"><i class="fa fa-share-alt"></i>&nbsp;Cursos</h3>
            </div>
            <div class="box-body">
                <?php if (!empty($docente->cursos)): ?>
                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th scope="col"><?= __('Id') ?></th>
                                <th scope="col"><?= __('Sede Id') ?></th>
                                <th scope="col"><?= __('Periodo Id') ?></th>
                                <th scope="col"><?= __('Carrera Id') ?></th>
                                <th scope="col"><?= __('Programa Id') ?></th>
                                <th scope="col"><?= __('Trayecto Id') ?></th>
                                <th scope="col"><?= __('Docente Id') ?></th>
                                <th scope="col"><?= __('Seccion') ?></th>
                                <th scope="col"><?= __('Cupos') ?></th>
                                <th scope="col"><?= __('Aula Id') ?></th>
                                <th scope="col"><?= __('Activo') ?></th>
                                <th scope="col"><?= __('Created') ?></th>
                                <th scope="col"><?= __('Modified') ?></th>
                                <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($docente->cursos as $cursos): ?>
                                <tr>
                                    <td><?= h($cursos->id) ?></td>
                                    <td><?= h($cursos->sede_id) ?></td>
                                    <td><?= h($cursos->periodo_id) ?></td>
                                    <td><?= h($cursos->carrera_id) ?></td>
                                    <td><?= h($cursos->programa_id) ?></td>
                                    <td><?= h($cursos->trayecto_id) ?></td>
                                    <td><?= h($cursos->docente_id) ?></td>
                                    <td><?= h($cursos->seccion) ?></td>
                                    <td><?= h($cursos->cupos) ?></td>
                                    <td><?= h($cursos->aula_id) ?></td>
                                    <td><?= h($cursos->activo) ?></td>
                                    <td><?= h($cursos->created) ?></td>
                                    <td><?= h($cursos->modified) ?></td>
                                    <td class="actions text-center">
                                        <?= $this->Html->link('<i class="fa fa-eye"></i>', ['controller' => 'Cursos', 'action' => 'view', $cursos->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                        <?= $this->Html->link('<i class="fa fa-pencil"></i>', ['controller' => 'Cursos', 'action' => 'edit', $cursos->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                        <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['controller' => 'Cursos', 'action' => 'delete', $cursos->id], ['confirm' => __('Are you sure you want to delete # {0}?', $cursos->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            <div class="box-footer"></div>            
        </div>
    </div>
</div>

