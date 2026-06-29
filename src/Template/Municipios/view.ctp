<div class="row">
	<div class="col-md-12">
		<div class="box box-info box-solid">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-info"></i>&nbsp;Municipio</h3>
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
                    <dt scope="row"><?= __('Estado') ?></dt>
                    <dd><?= $municipio->has('estado') ? $this->Html->link($municipio->estado->nombre, ['controller' => 'Estados', 'action' => 'view', $municipio->estado->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Nombre') ?></dt>
                    <dd><?= h($municipio->nombre) ?></dd>
                    <dt scope="row"><?= __('Id') ?></dt>
                    <dd><?= $this->Number->format($municipio->id) ?></dd>
                    <dt scope="row"><?= __('Created') ?></dt>
                    <dd><?= h($municipio->created) ?></dd>
                    <dt scope="row"><?= __('Modified') ?></dt>
                    <dd><?= h($municipio->modified) ?></dd>
                </dl>
            </div>
        </div>
    </div>
</div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <i class="fa fa-share-alt"></i>
          <h3 class="box-title"><?= __('Estudiantes') ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <?php if (!empty($municipio->estudiantes)): ?>
          <table class="table table-hover">
              <tr>
                    <th scope="col"><?= __('Id') ?></th>
                    <th scope="col"><?= __('Origen') ?></th>
                    <th scope="col"><?= __('Cedula') ?></th>
                    <th scope="col"><?= __('Nombres') ?></th>
                    <th scope="col"><?= __('Apellidos') ?></th>
                    <th scope="col"><?= __('Fecha Nacimiento') ?></th>
                    <th scope="col"><?= __('Sexo') ?></th>
                    <th scope="col"><?= __('Estado Civil') ?></th>
                    <th scope="col"><?= __('Discapacitado') ?></th>
                    <th scope="col"><?= __('Etnia') ?></th>
                    <th scope="col"><?= __('Direccion') ?></th>
                    <th scope="col"><?= __('Telefonos') ?></th>
                    <th scope="col"><?= __('Email') ?></th>
                    <th scope="col"><?= __('Lugar Nacimiento') ?></th>
                    <th scope="col"><?= __('Pais Id') ?></th>
                    <th scope="col"><?= __('Estado Id') ?></th>
                    <th scope="col"><?= __('Municipio Id') ?></th>
                    <th scope="col"><?= __('Parroquia Id') ?></th>
                    <th scope="col"><?= __('Asignado') ?></th>
                    <th scope="col"><?= __('Codigo Opsu') ?></th>
                    <th scope="col"><?= __('Fecha Notas') ?></th>
                    <th scope="col"><?= __('Codigo Notas') ?></th>
                    <th scope="col"><?= __('Fecha Titulo') ?></th>
                    <th scope="col"><?= __('Codigo Titulo') ?></th>
                    <th scope="col"><?= __('Expediente') ?></th>
                    <th scope="col"><?= __('Token') ?></th>
                    <th scope="col"><?= __('Usuario Id') ?></th>
                    <th scope="col"><?= __('Activo') ?></th>
                    <th scope="col"><?= __('Created') ?></th>
                    <th scope="col"><?= __('Modified') ?></th>
                    <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
              </tr>
              <?php foreach ($municipio->estudiantes as $estudiantes): ?>
              <tr>
                    <td><?= h($estudiantes->id) ?></td>
                    <td><?= h($estudiantes->origen) ?></td>
                    <td><?= h($estudiantes->cedula) ?></td>
                    <td><?= h($estudiantes->nombres) ?></td>
                    <td><?= h($estudiantes->apellidos) ?></td>
                    <td><?= h($estudiantes->fecha_nacimiento) ?></td>
                    <td><?= h($estudiantes->sexo) ?></td>
                    <td><?= h($estudiantes->estado_civil) ?></td>
                    <td><?= h($estudiantes->discapacitado) ?></td>
                    <td><?= h($estudiantes->etnia) ?></td>
                    <td><?= h($estudiantes->direccion) ?></td>
                    <td><?= h($estudiantes->telefonos) ?></td>
                    <td><?= h($estudiantes->email) ?></td>
                    <td><?= h($estudiantes->lugar_nacimiento) ?></td>
                    <td><?= h($estudiantes->pais_id) ?></td>
                    <td><?= h($estudiantes->estado_id) ?></td>
                    <td><?= h($estudiantes->municipio_id) ?></td>
                    <td><?= h($estudiantes->parroquia_id) ?></td>
                    <td><?= h($estudiantes->asignado) ?></td>
                    <td><?= h($estudiantes->codigo_opsu) ?></td>
                    <td><?= h($estudiantes->fecha_notas) ?></td>
                    <td><?= h($estudiantes->codigo_notas) ?></td>
                    <td><?= h($estudiantes->fecha_titulo) ?></td>
                    <td><?= h($estudiantes->codigo_titulo) ?></td>
                    <td><?= h($estudiantes->expediente) ?></td>
                    <td><?= h($estudiantes->token) ?></td>
                    <td><?= h($estudiantes->usuario_id) ?></td>
                    <td><?= h($estudiantes->activo) ?></td>
                    <td><?= h($estudiantes->created) ?></td>
                    <td><?= h($estudiantes->modified) ?></td>
                      <td class="actions text-right">
                      <?= $this->Html->link(__('View'), ['controller' => 'Estudiantes', 'action' => 'view', $estudiantes->id], ['class'=>'btn btn-info btn-xs']) ?>
                      <?= $this->Html->link(__('Edit'), ['controller' => 'Estudiantes', 'action' => 'edit', $estudiantes->id], ['class'=>'btn btn-warning btn-xs']) ?>
                      <?= $this->Form->postLink(__('Delete'), ['controller' => 'Estudiantes', 'action' => 'delete', $estudiantes->id], ['confirm' => __('Are you sure you want to delete # {0}?', $estudiantes->id), 'class'=>'btn btn-danger btn-xs']) ?>
                  </td>
              </tr>
              <?php endforeach; ?>
          </table>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <i class="fa fa-share-alt"></i>
          <h3 class="box-title"><?= __('Parroquias') ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <?php if (!empty($municipio->parroquias)): ?>
          <table class="table table-hover">
              <tr>
                    <th scope="col"><?= __('Id') ?></th>
                    <th scope="col"><?= __('Municipio Id') ?></th>
                    <th scope="col"><?= __('Nombre') ?></th>
                    <th scope="col"><?= __('Activo') ?></th>
                    <th scope="col"><?= __('Created') ?></th>
                    <th scope="col"><?= __('Modified') ?></th>
                    <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
              </tr>
              <?php foreach ($municipio->parroquias as $parroquias): ?>
              <tr>
                    <td><?= h($parroquias->id) ?></td>
                    <td><?= h($parroquias->municipio_id) ?></td>
                    <td><?= h($parroquias->nombre) ?></td>
                    <td><?= h($parroquias->activo) ?></td>
                    <td><?= h($parroquias->created) ?></td>
                    <td><?= h($parroquias->modified) ?></td>
                      <td class="actions text-right">
                      <?= $this->Html->link(__('View'), ['controller' => 'Parroquias', 'action' => 'view', $parroquias->id], ['class'=>'btn btn-info btn-xs']) ?>
                      <?= $this->Html->link(__('Edit'), ['controller' => 'Parroquias', 'action' => 'edit', $parroquias->id], ['class'=>'btn btn-warning btn-xs']) ?>
                      <?= $this->Form->postLink(__('Delete'), ['controller' => 'Parroquias', 'action' => 'delete', $parroquias->id], ['confirm' => __('Are you sure you want to delete # {0}?', $parroquias->id), 'class'=>'btn btn-danger btn-xs']) ?>
                  </td>
              </tr>
              <?php endforeach; ?>
          </table>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

