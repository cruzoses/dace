<div class="row">
	<div class="col-md-12">
		<div class="box box-info box-solid">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-info"></i>&nbsp;Subsistema</h3>
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
                    <dd><?= h($subsistema->codigo) ?></dd>
                    <dt scope="row"><?= __('Nombre') ?></dt>
                    <dd><?= h($subsistema->nombre) ?></dd>
                    <dt scope="row"><?= __('Id') ?></dt>
                    <dd><?= $this->Number->format($subsistema->id) ?></dd>
                    <dt scope="row"><?= __('Created') ?></dt>
                    <dd><?= h($subsistema->created) ?></dd>
                    <dt scope="row"><?= __('Modified') ?></dt>
                    <dd><?= h($subsistema->modified) ?></dd>
                    <dt scope="row"><?= __('Activo') ?></dt>
                    <dd><?= $subsistema->activo ? __('Yes') : __('No'); ?></dd>
                </dl>
            </div>
            <div class="box-footer">
		        <?= $this->Html->link('<i class="fa fa-pencil"></i>&nbsp;'.__('Edit'),
			        ['action' => 'edit',$subsistema->id],['class' => 'btn bg-olive btn-flat pull-left','escape' => false]); 
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
                <h3 class="box-title"><i class="fa fa-share-alt"></i>&nbsp;Programas</h3>
            </div>
            <div class="box-body">
                <?php if (!empty($subsistema->programas)): ?>
                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th scope="col"><?= __('Id') ?></th>
                                <th scope="col"><?= __('Codigo') ?></th>
                                <th scope="col"><?= __('Nombre') ?></th>
                                <th scope="col"><?= __('Carrera Id') ?></th>
                                <th scope="col"><?= __('Subsistema Id') ?></th>
                                <th scope="col"><?= __('Nota Minima') ?></th>
                                <th scope="col"><?= __('Creditos') ?></th>
                                <th scope="col"><?= __('Pasantia') ?></th>
                                <th scope="col"><?= __('Activo') ?></th>
                                <th scope="col"><?= __('Created') ?></th>
                                <th scope="col"><?= __('Modified') ?></th>
                                <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($subsistema->programas as $programas): ?>
                                <tr>
                                    <td><?= h($programas->id) ?></td>
                                    <td><?= h($programas->codigo) ?></td>
                                    <td><?= h($programas->nombre) ?></td>
                                    <td><?= h($programas->carrera_id) ?></td>
                                    <td><?= h($programas->subsistema_id) ?></td>
                                    <td><?= h($programas->nota_minima) ?></td>
                                    <td><?= h($programas->creditos) ?></td>
                                    <td><?= h($programas->pasantia) ?></td>
                                    <td><?= h($programas->activo) ?></td>
                                    <td><?= h($programas->created) ?></td>
                                    <td><?= h($programas->modified) ?></td>
                                    <td class="actions text-center">
                                        <?= $this->Html->link('<i class="fa fa-eye"></i>', ['controller' => 'Programas', 'action' => 'view', $programas->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                        <?= $this->Html->link('<i class="fa fa-pencil"></i>', ['controller' => 'Programas', 'action' => 'edit', $programas->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                        <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['controller' => 'Programas', 'action' => 'delete', $programas->id], ['confirm' => __('Are you sure you want to delete # {0}?', $programas->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
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

