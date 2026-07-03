<div class="row">
	<div class="col-md-12">
		<div class="box box-info box-solid">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-info"></i>&nbsp;Malla</h3>
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
                    <dt scope="row"><?= __('Programa') ?></dt>
                    <dd><?= $malla->has('programa') ? $this->Html->link($malla->programa->codename, ['controller' => 'Programas', 'action' => 'view', $malla->programa->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Trayecto') ?></dt>
                    <dd><?= $malla->has('trayecto') ? $this->Html->link($malla->trayecto->id, ['controller' => 'Trayectos', 'action' => 'view', $malla->trayecto->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Asignatura') ?></dt>
                    <dd><?= $malla->has('asignatura') ? $this->Html->link($malla->asignatura->codename, ['controller' => 'Asignaturas', 'action' => 'view', $malla->asignatura->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Nota Minima') ?></dt>
                    <dd><?= h($malla->nota_minima) ?></dd>
                    <dt scope="row"><?= __('Id') ?></dt>
                    <dd><?= $this->Number->format($malla->id) ?></dd>
                    <dt scope="row"><?= __('Created') ?></dt>
                    <dd><?= h($malla->created) ?></dd>
                    <dt scope="row"><?= __('Modified') ?></dt>
                    <dd><?= h($malla->modified) ?></dd>
                </dl>
            </div>
            <div class="box-footer">
		        <?= $this->Html->link('<i class="fa fa-pencil"></i>&nbsp;'.__('Edit'),
			        ['action' => 'edit',$malla->id],['class' => 'btn bg-olive btn-flat pull-left','escape' => false]); 
		        ?>
		        <?= $this->Html->link('<i class="fa fa-power-off"></i>&nbsp;Cerrar',
			        ['action' => 'index'],['class' => 'btn bg-maroon btn-flat pull-right','escape' => false]); 
		        ?>
            </div>
        </div>
    </div>
</div>

