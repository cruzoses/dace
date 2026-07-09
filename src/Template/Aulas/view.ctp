<?php
/**
 * @var \App\Model\Entity\Aula $aula
 * @var \App\View\AppView $this
*/
?>
<div class="row">
	<div class="col-md-12">
		<div class="box box-info box-solid">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-info"></i>&nbsp;Aula</h3>
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
                    <dt scope="row"><?= __('Codigo') ?></dt>
                    <dd><?= h($aula->codigo) ?></dd>
                    <dt scope="row"><?= __('Nombre') ?></dt>
                    <dd><?= h($aula->nombre) ?></dd>
                    <dt scope="row"><?= __('Ubicacion') ?></dt>
                    <dd><?= h($aula->ubicacion) ?></dd>
                    <dt scope="row"><?= __('Id') ?></dt>
                    <dd><?= $this->Number->format($aula->id) ?></dd>
                    <dt scope="row"><?= __('Sede') ?></dt>
                    <dd><?= $aula->has('sede') ? h($aula->sede->nombre) : '' ?></dd>
                    <dt scope="row"><?= __('Capacidad') ?></dt>
                    <dd><?= $this->Number->format($aula->capacidad) ?></dd>
                    <dt scope="row"><?= __('Created') ?></dt>
                    <dd><?= h($aula->created) ?></dd>
                    <dt scope="row"><?= __('Modified') ?></dt>
                    <dd><?= h($aula->modified) ?></dd>
                    <dt scope="row"><?= __('Condicion') ?></dt>
                    <dd><?= $aula->condicion ? __('Yes') : __('No'); ?></dd>
                </dl>
            </div>
            <div class="box-footer">
		        <?= $this->Html->link('<i class="fa fa-pencil"></i>&nbsp;'.__('Edit'),
			        ['action' => 'edit',$aula->id],['class' => 'btn bg-olive btn-flat pull-left','escape' => false]); 
		        ?>
		        <?= $this->Html->link('<i class="fa fa-power-off"></i>&nbsp;Cerrar',
			        ['action' => 'index'],['class' => 'btn bg-maroon btn-flat pull-right','escape' => false]); 
		        ?>
            </div>
        </div>
    </div>
</div>

