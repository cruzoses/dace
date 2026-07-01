<div class="row">
	<div class="col-md-12">
		<div class="box box-info box-solid">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-info"></i>&nbsp;Departamento</h3>
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
                    <dt scope="row"><?= __('Nombre') ?></dt>
                    <dd><?= h($departamento->nombre) ?></dd>
                    <dt scope="row"><?= __('Responsable') ?></dt>
                    <dd><?= h($departamento->responsable) ?></dd>
                    <dt scope="row"><?= __('Id') ?></dt>
                    <dd><?= $this->Number->format($departamento->id) ?></dd>
                    <dt scope="row"><?= __('Created') ?></dt>
                    <dd><?= h($departamento->created) ?></dd>
                    <dt scope="row"><?= __('Modified') ?></dt>
                    <dd><?= h($departamento->modified) ?></dd>
                </dl>
            </div>
            <div class="box-footer">
		        <?= $this->Html->link('<i class="fa fa-pencil"></i>&nbsp;'.__('Edit'),
			        ['action' => 'edit',$departamento->id],['class' => 'btn bg-olive btn-flat pull-left','escape' => false]); 
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
                <h3 class="box-title"><i class="fa fa-share-alt"></i>&nbsp;Docentes</h3>
            </div>
            <div class="box-body">
                <?php if (!empty($departamento->docentes)): ?>
                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th scope="col"><?= __('Id') ?></th>
                                <th scope="col"><?= __('Cedula') ?></th>
                                <th scope="col"><?= __('Nombres') ?></th>
                                <th scope="col"><?= __('Apellidos') ?></th>
                                <th scope="col"><?= __('Fecha Nacimiento') ?></th>
                                <th scope="col"><?= __('Sexo') ?></th>
                                <th scope="col"><?= __('Email') ?></th>
                                <th scope="col"><?= __('Telefonos') ?></th>
                                <th scope="col"><?= __('Departamento Id') ?></th>
                                <th scope="col"><?= __('Token') ?></th>
                                <th scope="col"><?= __('Usuario Id') ?></th>
                                <th scope="col"><?= __('Activo') ?></th>
                                <th scope="col"><?= __('Created') ?></th>
                                <th scope="col"><?= __('Modified') ?></th>
                                <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($departamento->docentes as $docentes): ?>
                                <tr>
                                    <td><?= h($docentes->id) ?></td>
                                    <td><?= h($docentes->cedula) ?></td>
                                    <td><?= h($docentes->nombres) ?></td>
                                    <td><?= h($docentes->apellidos) ?></td>
                                    <td><?= h($docentes->fecha_nacimiento) ?></td>
                                    <td><?= h($docentes->sexo) ?></td>
                                    <td><?= h($docentes->email) ?></td>
                                    <td><?= h($docentes->telefonos) ?></td>
                                    <td><?= h($docentes->departamento_id) ?></td>
                                    <td><?= h($docentes->token) ?></td>
                                    <td><?= h($docentes->usuario_id) ?></td>
                                    <td><?= h($docentes->activo) ?></td>
                                    <td><?= h($docentes->created) ?></td>
                                    <td><?= h($docentes->modified) ?></td>
                                    <td class="actions text-center">
                                        <?= $this->Html->link('<i class="fa fa-eye"></i>', ['controller' => 'Docentes', 'action' => 'view', $docentes->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                        <?= $this->Html->link('<i class="fa fa-pencil"></i>', ['controller' => 'Docentes', 'action' => 'edit', $docentes->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                        <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['controller' => 'Docentes', 'action' => 'delete', $docentes->id], ['confirm' => __('Are you sure you want to delete # {0}?', $docentes->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
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

