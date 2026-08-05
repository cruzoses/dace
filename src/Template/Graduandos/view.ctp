<div class="row">
	<div class="col-md-12">
		<div class="box box-info box-solid">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-info"></i>&nbsp;Graduando</h3>
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
                    <dt scope="row"><?= __('Acto') ?></dt>
                    <dd><?= $graduando->has('acto') ? $this->Html->link($graduando->acto->id, ['controller' => 'Actos', 'action' => 'view', $graduando->acto->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Carrera') ?></dt>
                    <dd><?= $graduando->has('carrera') ? $this->Html->link($graduando->carrera->codename, ['controller' => 'Carreras', 'action' => 'view', $graduando->carrera->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Programa') ?></dt>
                    <dd><?= $graduando->has('programa') ? $this->Html->link($graduando->programa->codename, ['controller' => 'Programas', 'action' => 'view', $graduando->programa->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Estudiante') ?></dt>
                    <dd><?= $graduando->has('estudiante') ? $this->Html->link($graduando->estudiante->full_name, ['controller' => 'Estudiantes', 'action' => 'view', $graduando->estudiante->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Control') ?></dt>
                    <dd><?= h($graduando->control) ?></dd>
                    <dt scope="row"><?= __('Id') ?></dt>
                    <dd><?= $this->Number->format($graduando->id) ?></dd>
                    <dt scope="row"><?= __('Institucion') ?></dt>
                    <dd><?= $this->Number->format($graduando->institucion) ?></dd>
                    <dt scope="row"><?= __('Indice') ?></dt>
                    <dd><?= $this->Number->format($graduando->indice) ?></dd>
                    <dt scope="row"><?= __('Solicitud') ?></dt>
                    <dd><?= $this->Number->format($graduando->solicitud) ?></dd>
                    <dt scope="row"><?= __('Created') ?></dt>
                    <dd><?= h($graduando->created) ?></dd>
                    <dt scope="row"><?= __('Modified') ?></dt>
                    <dd><?= h($graduando->modified) ?></dd>
                </dl>
            </div>
            <div class="box-footer">
		        <?= $this->Html->link('<i class="fa fa-pencil"></i>&nbsp;'.__('Edit'),
			        ['action' => 'edit',$graduando->id],['class' => 'btn bg-olive btn-flat pull-left','escape' => false]); 
		        ?>
		        <?= $this->Html->link('<i class="fa fa-power-off"></i>&nbsp;Cerrar',
			        ['action' => 'index'],['class' => 'btn bg-maroon btn-flat pull-right','escape' => false]); 
		        ?>
            </div>
        </div>
    </div>
</div>

