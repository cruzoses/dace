<div class="row">
	<div class="col-md-12">
		<div class="box box-info box-solid">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-info"></i>&nbsp;Mension Carrera</h3>
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
                    <dd><?= h($mensionCarrera->nombre) ?></dd>
                    <dt scope="row"><?= __('Id') ?></dt>
                    <dd><?= $this->Number->format($mensionCarrera->id) ?></dd>
                    <dt scope="row"><?= __('Created') ?></dt>
                    <dd><?= h($mensionCarrera->created) ?></dd>
                    <dt scope="row"><?= __('Modified') ?></dt>
                    <dd><?= h($mensionCarrera->modified) ?></dd>
                    <dt scope="row"><?= __('Activa') ?></dt>
                    <dd><?= $mensionCarrera->activa ? __('Yes') : __('No'); ?></dd>
                </dl>
            </div>
            <div class="box-footer">
		        <?= $this->Html->link('<i class="fa fa-pencil"></i>&nbsp;'.__('Edit'),
			        ['action' => 'edit',$mensionCarrera->id],['class' => 'btn bg-olive btn-flat pull-left','escape' => false]); 
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
                <h3 class="box-title"><i class="fa fa-share-alt"></i>&nbsp;Carreras</h3>
            </div>
            <div class="box-body">
                <?php if (!empty($mensionCarrera->carreras)): ?>
                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th scope="col"><?= __('Id') ?></th>
                                <th scope="col"><?= __('Codigo') ?></th>
                                <th scope="col"><?= __('Nombre') ?></th>
                                <th scope="col"><?= __('Mension Carrera Id') ?></th>
                                <th scope="col"><?= __('Titulo Otorgado') ?></th>
                                <th scope="col"><?= __('Activa') ?></th>
                                <th scope="col"><?= __('Created') ?></th>
                                <th scope="col"><?= __('Modified') ?></th>
                                <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mensionCarrera->carreras as $carreras): ?>
                                <tr>
                                    <td><?= h($carreras->id) ?></td>
                                    <td><?= h($carreras->codigo) ?></td>
                                    <td><?= h($carreras->nombre) ?></td>
                                    <td><?= h($carreras->mension_carrera_id) ?></td>
                                    <td><?= h($carreras->titulo_otorgado) ?></td>
                                    <td><?= h($carreras->activa) ?></td>
                                    <td><?= h($carreras->created) ?></td>
                                    <td><?= h($carreras->modified) ?></td>
                                    <td class="actions text-center">
                                        <?= $this->Html->link('<i class="fa fa-eye"></i>', ['controller' => 'Carreras', 'action' => 'view', $carreras->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                        <?= $this->Html->link('<i class="fa fa-pencil"></i>', ['controller' => 'Carreras', 'action' => 'edit', $carreras->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                        <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['controller' => 'Carreras', 'action' => 'delete', $carreras->id], ['confirm' => __('Are you sure you want to delete # {0}?', $carreras->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
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

