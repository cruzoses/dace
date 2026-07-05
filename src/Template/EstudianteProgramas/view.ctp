<div class="row">
	<div class="col-md-12">
		<div class="box box-info box-solid">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-info"></i>&nbsp;Estudiante Programa</h3>
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
                    <dt scope="row"><?= __('Estudiante') ?></dt>
                    <dd><?= $estudiantePrograma->has('estudiante') ? $this->Html->link($estudiantePrograma->estudiante->id, ['controller' => 'Estudiantes', 'action' => 'view', $estudiantePrograma->estudiante->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Sede') ?></dt>
                    <dd><?= $estudiantePrograma->has('sede') ? $this->Html->link($estudiantePrograma->sede->codename, ['controller' => 'Sedes', 'action' => 'view', $estudiantePrograma->sede->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Programa') ?></dt>
                    <dd><?= $estudiantePrograma->has('programa') ? $this->Html->link($estudiantePrograma->programa->codename, ['controller' => 'Programas', 'action' => 'view', $estudiantePrograma->programa->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Cohorte') ?></dt>
                    <dd><?= h($estudiantePrograma->cohorte) ?></dd>
                    <dt scope="row"><?= __('Id') ?></dt>
                    <dd><?= $this->Number->format($estudiantePrograma->id) ?></dd>
                    <dt scope="row"><?= __('Indice') ?></dt>
                    <dd><?= $this->Number->format($estudiantePrograma->indice) ?></dd>
                    <dt scope="row"><?= __('Fecha Egreso') ?></dt>
                    <dd><?= h($estudiantePrograma->fecha_egreso) ?></dd>
                    <dt scope="row"><?= __('Created') ?></dt>
                    <dd><?= h($estudiantePrograma->created) ?></dd>
                    <dt scope="row"><?= __('Modified') ?></dt>
                    <dd><?= h($estudiantePrograma->modified) ?></dd>
                    <dt scope="row"><?= __('Culminado') ?></dt>
                    <dd><?= $estudiantePrograma->culminado ? __('Yes') : __('No'); ?></dd>
                    <dt scope="row"><?= __('Activo') ?></dt>
                    <dd><?= $estudiantePrograma->activo ? __('Yes') : __('No'); ?></dd>
                </dl>
            </div>
            <div class="box-footer">
		        <?= $this->Html->link('<i class="fa fa-pencil"></i>&nbsp;'.__('Edit'),
			        ['action' => 'edit',$estudiantePrograma->id],['class' => 'btn bg-olive btn-flat pull-left','escape' => false]); 
		        ?>
		        <?= $this->Html->link('<i class="fa fa-power-off"></i>&nbsp;Cerrar',
			        ['action' => 'index'],['class' => 'btn bg-maroon btn-flat pull-right','escape' => false]); 
		        ?>
            </div>
        </div>
    </div>
</div>

