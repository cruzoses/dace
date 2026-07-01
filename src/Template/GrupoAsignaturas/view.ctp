<div class="row">
	<div class="col-md-12">
		<div class="box box-info box-solid">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-info"></i>&nbsp;Grupo Asignatura</h3>
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
                    <dt scope="row"><?= __('Codigo') ?></dt>
                    <dd><?= h($grupoAsignatura->codigo) ?></dd>
                    <dt scope="row"><?= __('Nombre') ?></dt>
                    <dd><?= h($grupoAsignatura->nombre) ?></dd>
                    <dt scope="row"><?= __('Id') ?></dt>
                    <dd><?= $this->Number->format($grupoAsignatura->id) ?></dd>
                    <dt scope="row"><?= __('Created') ?></dt>
                    <dd><?= h($grupoAsignatura->created) ?></dd>
                    <dt scope="row"><?= __('Modified') ?></dt>
                    <dd><?= h($grupoAsignatura->modified) ?></dd>
                    <dt scope="row"><?= __('Activo') ?></dt>
                    <dd><?= $grupoAsignatura->activo ? __('Yes') : __('No'); ?></dd>
                </dl>
            </div>
            <div class="box-footer">
		        <?= $this->Html->link('<i class="fa fa-pencil"></i>&nbsp;'.__('Edit'),
			        ['action' => 'edit',$grupoAsignatura->id],['class' => 'btn bg-olive btn-flat pull-left','escape' => false]); 
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
                <h3 class="box-title"><i class="fa fa-share-alt"></i>&nbsp;Asignaturas</h3>
            </div>
            <div class="box-body">
                <?php if (!empty($grupoAsignatura->asignaturas)): ?>
                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th scope="col"><?= __('Id') ?></th>
                                <th scope="col"><?= __('Codigo') ?></th>
                                <th scope="col"><?= __('Nombre') ?></th>
                                <th scope="col"><?= __('Horas Teoricas') ?></th>
                                <th scope="col"><?= __('Horas Practicas') ?></th>
                                <th scope="col"><?= __('Frecuencia') ?></th>
                                <th scope="col"><?= __('Creditos') ?></th>
                                <th scope="col"><?= __('Costo') ?></th>
                                <th scope="col"><?= __('Requisitos') ?></th>
                                <th scope="col"><?= __('Convalidacion') ?></th>
                                <th scope="col"><?= __('Grupo Asignatura Id') ?></th>
                                <th scope="col"><?= __('Activa') ?></th>
                                <th scope="col"><?= __('Created') ?></th>
                                <th scope="col"><?= __('Modified') ?></th>
                                <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($grupoAsignatura->asignaturas as $asignaturas): ?>
                                <tr>
                                    <td><?= h($asignaturas->id) ?></td>
                                    <td><?= h($asignaturas->codigo) ?></td>
                                    <td><?= h($asignaturas->nombre) ?></td>
                                    <td><?= h($asignaturas->horas_teoricas) ?></td>
                                    <td><?= h($asignaturas->horas_practicas) ?></td>
                                    <td><?= h($asignaturas->frecuencia) ?></td>
                                    <td><?= h($asignaturas->creditos) ?></td>
                                    <td><?= h($asignaturas->costo) ?></td>
                                    <td><?= h($asignaturas->requisitos) ?></td>
                                    <td><?= h($asignaturas->convalidacion) ?></td>
                                    <td><?= h($asignaturas->grupo_asignatura_id) ?></td>
                                    <td><?= h($asignaturas->activa) ?></td>
                                    <td><?= h($asignaturas->created) ?></td>
                                    <td><?= h($asignaturas->modified) ?></td>
                                    <td class="actions text-center">
                                        <?= $this->Html->link('<i class="fa fa-eye"></i>', ['controller' => 'Asignaturas', 'action' => 'view', $asignaturas->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                        <?= $this->Html->link('<i class="fa fa-pencil"></i>', ['controller' => 'Asignaturas', 'action' => 'edit', $asignaturas->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                        <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['controller' => 'Asignaturas', 'action' => 'delete', $asignaturas->id], ['confirm' => __('Are you sure you want to delete # {0}?', $asignaturas->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
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

