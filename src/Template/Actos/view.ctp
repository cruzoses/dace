<div class="row">
	<div class="col-md-12">
		<div class="box box-info box-solid">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-info"></i>&nbsp;Acto</h3>
				<div class="box-tools pull-right">
			        <button type="button" class="btn btn-box-tool" data-widget="collapse">
				        <i class="fa fa-minus"></i>
			        </button>
			        <?= $this->Html->link('<i class="fa fa-times"></i>',
				        ['action' => 'index'],['class'=>'btn btn-box-tool','title'=>'cerrar','escape'=>false]);
			        ?>
		        </div>
        	</div>        
        	<div class="box-body">
          		<dl class="dl-horizontal">
                    <dt scope="row"><?= __('Id') ?></dt>
                    <dd><?= $this->Number->format($acto->id) ?></dd>
                    <dt scope="row"><?= __('Nombre') ?></dt>
                    <dd><?= h($acto->nombre) ?></dd>
                    <dt scope="row"><?= __('Cohorte') ?></dt>
                    <dd><?= h($acto->cohorte) ?></dd>
                    <dt scope="row"><?= __('Lapso') ?></dt>
                    <dd><?= $this->Number->format($acto->lapso) ?></dd>
                    <dt scope="row"><?= __('Fecha') ?></dt>
                    <dd><?= h($acto->fecha) ?></dd>
                    <dt scope="row"><?= __('Created') ?></dt>
                    <dd><?= h($acto->created) ?></dd>
                    <dt scope="row"><?= __('Modified') ?></dt>
                    <dd><?= h($acto->modified) ?></dd>
                    <dt scope="row"><?= __('Activo') ?></dt>
                    <dd><?= $acto->activo ? __('Yes') : __('No'); ?></dd>
                </dl>
            </div>
            <div class="box-footer">
		        <?= $this->Html->link('<i class="fa fa-pencil"></i>&nbsp;'.__('Edit'),
			        ['action' => 'edit',$acto->id],['class' => 'btn bg-olive btn-flat pull-left','escape' => false]); 
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
                <h3 class="box-title"><i class="fa fa-share-alt"></i>&nbsp;Lista de Graduandos</h3>
            </div>
            <div class="box-body">
                <?php if (!empty($acto->graduandos)): ?>
                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th scope="col"><?= __('Id') ?></th>
                                <th scope="col"><?= __('Institucion') ?></th>
                                <th scope="col"><?= __('Acto Id') ?></th>
                                <th scope="col"><?= __('Estudiante Id') ?></th>
                                <th scope="col"><?= __('Indice') ?></th>
                                <th scope="col"><?= __('Control') ?></th>
                                <th scope="col"><?= __('Created') ?></th>
                                <th scope="col"><?= __('Modified') ?></th>
                                <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($acto->graduandos as $graduandos): ?>
                                <tr>
                                    <td><?= h($graduandos->id) ?></td>
                                    <td><?= h($graduandos->institucion) ?></td>
                                    <td><?= h($graduandos->acto_id) ?></td>
                                    <td><?= h($graduandos->estudiante_id) ?></td>
                                    <td><?= h($graduandos->indice) ?></td>
                                    <td><?= h($graduandos->control) ?></td>
                                    <td><?= h($graduandos->created) ?></td>
                                    <td><?= h($graduandos->modified) ?></td>
                                    <td class="actions text-center">
                                        <?= $this->Html->link('<i class="fa fa-eye"></i>', ['controller' => 'Graduandos', 'action' => 'view', $graduandos->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                        <?= $this->Html->link('<i class="fa fa-edit"></i>', ['controller' => 'Graduandos', 'action' => 'edit', $graduandos->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                        <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['controller' => 'Graduandos', 'action' => 'delete', $graduandos->id], ['confirm' => __('Are you sure you want to delete # {0}?', $graduandos->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
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

