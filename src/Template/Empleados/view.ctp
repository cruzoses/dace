<div class="row">
	<div class="col-md-12">
		<div class="box box-info box-solid">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-info"></i>&nbsp;Empleado</h3>
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
                    <dd><?= h($empleado->nombres) ?></dd>
                    <dt scope="row"><?= __('Apellidos') ?></dt>
                    <dd><?= h($empleado->apellidos) ?></dd>
                    <dt scope="row"><?= __('Sexo') ?></dt>
                    <dd><?= h($empleado->sexo) ?></dd>
                    <dt scope="row"><?= __('Email') ?></dt>
                    <dd><?= h($empleado->email) ?></dd>
                    <dt scope="row"><?= __('Telefonos') ?></dt>
                    <dd><?= h($empleado->telefonos) ?></dd>
                    <dt scope="row"><?= __('Token') ?></dt>
                    <dd>
                        <?= h($empleado->token) ?>
                        <?php if (!$empleado->has('usuario')): ?>
                            <?= $this->Form->postLink('<i class="fa fa-envelope"></i> Reenviar',
                                ['action' => 'reenviarToken', $empleado->id],
                                ['class' => 'btn btn-xs btn-warning btnReenviar', 'escape' => false,
                                    'data-email' => $empleado->email,
                                    'data-confirm-text' => 'Sí, reenviar',
                                    'data-confirm-color' => '#f0ad4e',
                                    'confirm' => '¿Reenviar token por correo a ' . $empleado->email . '?'
                                ]
                            ) ?>
                        <?php endif; ?>
                    </dd>
                    <dt scope="row"><?= __('Usuario') ?></dt>
                    <dd><?= $empleado->has('usuario') ? $this->Html->link($empleado->usuario->alias, ['controller' => 'Usuarios', 'action' => 'view', $empleado->usuario->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Id') ?></dt>
                    <dd><?= $this->Number->format($empleado->id) ?></dd>
                    <dt scope="row"><?= __('Cedula') ?></dt>
                    <dd><?= $this->Number->format($empleado->cedula) ?></dd>
                    <dt scope="row"><?= __('Fecha Nacimiento') ?></dt>
                    <dd><?= h($empleado->fecha_nacimiento) ?></dd>
                    <dt scope="row"><?= __('Created') ?></dt>
                    <dd><?= h($empleado->created) ?></dd>
                    <dt scope="row"><?= __('Modified') ?></dt>
                    <dd><?= h($empleado->modified) ?></dd>
                    <dt scope="row"><?= __('Activo') ?></dt>
                    <dd><?= $empleado->activo ? __('Yes') : __('No'); ?></dd>
                </dl>
            </div>
            <div class="box-footer">
		        <?= $this->Html->link('<i class="fa fa-pencil"></i>&nbsp;'.__('Edit'),
			        ['action' => 'edit',$empleado->id],['class' => 'btn bg-olive btn-flat pull-left','escape' => false]); 
		        ?>
		        <?= $this->Html->link('<i class="fa fa-power-off"></i>&nbsp;Cerrar',
			        ['action' => 'index'],['class' => 'btn bg-maroon btn-flat pull-right','escape' => false]); 
		        ?>
            </div>
        </div>
    </div>
</div>

