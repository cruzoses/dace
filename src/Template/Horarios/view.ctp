<?php
/**
 * @var \App\Model\Entity\Horario $horario
 * @var \App\View\AppView $this
*/
?>
<div class="row">
	<div class="col-md-12">
		<div class="box box-info box-solid">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-info"></i>&nbsp;Horario</h3>
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
                    <dt scope="row"><?= __('Sede') ?></dt>
                    <dd><?= $horario->has('sede') ? $this->Html->link($horario->sede->codename, ['controller' => 'Sedes', 'action' => 'view', $horario->sede->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Periodo') ?></dt>
                    <dd><?= $horario->has('periodo') ? $this->Html->link($horario->periodo->codename, ['controller' => 'Periodos', 'action' => 'view', $horario->periodo->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Codigo') ?></dt>
                    <dd><?= h($horario->codigo) ?></dd>
                    <dt scope="row"><?= __('Desde') ?></dt>
                    <dd><?= h($horario->desde) ?></dd>
                    <dt scope="row"><?= __('Hasta') ?></dt>
                    <dd><?= h($horario->hasta) ?></dd>
                    <dt scope="row"><?= __('Id') ?></dt>
                    <dd><?= $this->Number->format($horario->id) ?></dd>
                    <dt scope="row"><?= __('Dia') ?></dt>
                    <dd><?= h($aDias[$horario->dia] ?? $horario->dia) ?></dd>
                    <dt scope="row"><?= __('Turno') ?></dt>
                    <dd><?= h($aTurnos[$horario->turno] ?? $horario->turno) ?></dd>
                    <dt scope="row"><?= __('Created') ?></dt>
                    <dd><?= h($horario->created) ?></dd>
                    <dt scope="row"><?= __('Modified') ?></dt>
                    <dd><?= h($horario->modified) ?></dd>
                    <dt scope="row"><?= __('Activo') ?></dt>
                    <dd><?= $horario->activo ? __('Yes') : __('No'); ?></dd>
                </dl>
            </div>
            <div class="box-footer">
		        <?= $this->Html->link('<i class="fa fa-pencil"></i>&nbsp;'.__('Edit'),
			        ['action' => 'edit',$horario->id],['class' => 'btn bg-olive btn-flat pull-left','escape' => false]); 
		        ?>
		        <?= $this->Html->link('<i class="fa fa-power-off"></i>&nbsp;Cerrar',
			        ['action' => 'index'],['class' => 'btn bg-maroon btn-flat pull-right','escape' => false]); 
		        ?>
            </div>
        </div>
    </div>
</div>

