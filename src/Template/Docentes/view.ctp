<div class="box box-primary">

	<div class="box-header with-border">
		<h3 class="box-title"><i class="fa fa-child"></i>&nbsp;Datos del Docente</h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse">
				<i class="fa fa-minus"></i>
			</button>
			<?= $this->Html->link('<i class="fa fa-close"></i>',
				['action' => 'index'],['class'=>'btn btn-box-tool','title'=>'cerrar','escape'=>false]);
			?>
		</div>
	</div>

	<div class="box-body table-responsive no-padding">
		<table class="table table-bordered table-condensed text-center">
			<tr>
				<th class="bg-gray">C&eacute;dula</th>
				<th class="bg-gray" colspan="2">Nombres Y Apellidos</th>
				<th rowspan="4" class="avatar no-padding">
					<?= $this->Html->image('site/usuario.jpg',['class'=>'avatar img-responsive img-lazy', 'alt'=> 'Foto']) ?>
				</th>
			</tr>
			<tr>
				<td><?= $this->Number->format($docente->cedula) ?></td>
				<td colspan="2">
					<?= h($docente->full_name) ?>
				</td>
			</tr>
			<tr>
				<th class="bg-gray">Usuario</th>
				<th class="bg-gray">Correo Electr&oacute;nico</th>
				<th class="bg-gray">Tel&eacute;fono</th>
			</tr>
			<tr>
				<td><?= h($docente->usuario->username) ?></td>
				<td><?= h($docente->email) ?></td>
				<td><?= h($docente->telefonos) ?></td>
			</tr>
		</table>
	</div>

</div>

<div class="box box-primary">

	<div class="box-header with-border">
		<h3 class="box-title"><i class="fa fa-info"></i>&nbsp;Datos del Personales</h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse">
				<i class="fa fa-minus"></i>
			</button>
		</div>
	</div>

	<div class="box-body table-responsive no-padding">
		<table class="table table-bordered table-condensed">
			<tr>
				<th class="bg-gray text-center col-lg-3" style colspan="1">Dato</th>
				<th class="bg-gray text-center" style colspan="1">Detalle</th>
			</tr>
			<tr>
				<td class="text-left" style colspan="1">ID N&uacute;mero</td>
				<td class="text-left" style colspan="1"><?= $this->Number->format($docente->id) ?></td>
			</tr>
			<tr>
				<td class="text-left" style colspan="1">N&uacute;mero de C&eacute;dula </td>
				<td class="text-left" style colspan="1"><?= $this->Number->format($docente->cedula) ?></td>
			</tr>
			<tr>
				<td class="text-left" style colspan="1">Apellidos</td>
				<td class="text-left" style colspan="1"><?= h($docente->apellidos) ?></td>
			</tr>
			<tr>
				<td class="text-left" style colspan="1">Nombres</td>
				<td class="text-left" style colspan="1"><?= h($docente->nombres) ?></td>
			</tr>
			<tr>
				<td class=" text-left" style colspan="1">Fecha Nacimiento</td>
				<td class=" text-left" style colspan="1">
					<?= h($docente->fecha_nacimiento) ?>
				</td>
			</tr>
			<tr>
				<td class="text-left" style colspan="1">Correo Electr&oacute;nico</td>
				<td class="text-left" style colspan="1"><?= h($docente->email) ?></td>
			</tr>
			<tr>
				<td class="text-left" style colspan="1">Clave de Registro</td>
				<td class="text-left" style colspan="1"><?= h($docente->token) ?></td>
			</tr>
		</table>
	</div>

	<div class="box-footer">
		<?php if( empty( $docente->usuario_id) ) : ?>
			<?= $this->Html->link('<i class="fa fa-envelope"></i>&nbsp;Token de Registro',
				['action' => 'nuevotoken',$docente->id],
				['class' => 'btn bg-olive btn-flat pull-left','escape' => false]); 
			?>
		<?php endif; ?>
	</div>

</div>

<?php if( !empty($docente->usuario) ) : ?>

	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title"><i class="fa fa-info"></i>&nbsp;Cuenta de Usuario</h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse">
					<i class="fa fa-minus"></i>
				</button>
			</div>
		</div>
		<div class="box-body table-responsive no-padding">
			<table class="table  table-bordered table-condensed table-striped">
				<tr>
					<th class="bg-gray text-center col-lg-3" style colspan="1">Dato</th>
					<th class="bg-gray text-center" style colspan="1">Detalle</th>
				</tr>
				<tr>
					<td class="text-left" style colspan="1">ID de Usuario</td>
					<td class="text-left" style colspan="1"><?= $docente->usuario->id;?></td>
				</tr>
				<tr>
					<td class="text-left" style colspan="1">Tipo Registro</td>
					<td class="text-left" style colspan="1">Docente</td>
				</tr>
				<tr>
					<td class="text-left" style colspan="1">N&uacute;mero de C&eacute;dula </td>
					<td class="text-left" style colspan="1"><?= $docente->usuario->cedula;?></td>
				</tr>
				<tr>
					<td class="text-left" style colspan="1">Apellidos</td>
					<td class="text-left" style colspan="1"><?= $docente->usuario->apellidos;?></td>
				</tr>
				<tr>
					<td class="text-left" style colspan="1">Nombres</td>
					<td class="text-left" style colspan="1"><?= $docente->usuario->nombres;?></td>
				</tr>
				<tr>
					<td class="text-left" style colspan="1">Usuario</td>
					<td class="text-left" style colspan="1"><?= $docente->usuario->username;?></td>
				</tr>
				<tr>
					<td class="text-left" style colspan="1">Fecha de Nacimiento</td>
					<td class="text-left" style colspan="1"><?= h($docente->usuario->fecha_nacimiento);?></td>
				</tr>
				<tr>
				<td class="text-left" style colspan="1">Correo Electr&oacute;nico</td>
					<td class="text-left" style colspan="1"><?= $docente->usuario->email;?></td>
				</tr>
				<tr>
					<td class="text-left" style colspan="1">Tel&eacute;fonos</td>
					<td class="text-left" style colspan="1"><?= $docente->usuario->telefonos;?></td>
				</tr>
				<tr>
					<td class="text-left" style colspan="1">Twitter</td>
					<td class="text-left" style colspan="1"><?= $docente->usuario->twitter;?></td>
				</tr>
				<tr>
					<td class="text-left" style colspan="1">Instagram</td>
					<td class="text-left" style colspan="1"><?= $docente->usuario->instagram;?></td>
				</tr>
				<tr>
					<td class=" text-left" style colspan="1">Direcci&oacute;n</td>
					<td class=" text-left" style colspan="1"><?= $docente->usuario->direccion;?></td>
				</tr>
			</table>
		</div>
	</div>

<?php endif; ?>

<?php if( ! empty($docente->cursos)) : ?>

	<div class="box box-default box-solid">

		<div class="box-header with-border">
			<h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Cursos Asignados</h3>
		</div>

		<div class="box-body table-responsive">

			<table class="table table-hover table-condensed">

				<thead>
					<tr>
						<th><b>Per&iacute;odo</b></th>
						<th><b>Asignaci&oacute;n</b></th>
						<th><b>Programa</b></th>
						<th><b>Asignatura</b></th>
						<th><b>Nombre de la Asignatura</b></th>
						<th class="text-center"><b>Cr&eacute;ditos</b></th>
						<th class="text-center"><b>Secci&oacute;n</b></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($docente->cursos as $curso) : ?>
						<tr>
							<td><?= $curso->periodo->codigo;?></td>
							<td><?= $curso->id;?></td>
							<td><?= $curso->programa->codigo;?></td>
							<td><?= $curso->asignatura->codigo;?></td>
							<td><?= $curso->asignatura->nombre;?></td>
							<td class="text-center"><?= $curso->asignatura->creditos;?></td>
							<td class="text-center">
								<?= $this->Html->link( $curso->seccion,
									['controller' => 'Docentes', 'action' => 'cursosasignados',$curso->docente_id],
									['class' => 'btn btn-default btn-xs']);
								?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<div class="box-footer"></div>
			
	</div>
<?php endif; ?>

<!--
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

-->