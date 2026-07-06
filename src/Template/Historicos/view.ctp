<div class="row">
	<div class="col-md-12">
		<div class="box box-info box-solid">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-info"></i>&nbsp;Historico</h3>
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
                    <dt scope="row"><?= __('Estudiante') ?></dt>
                    <dd><?= $historico->has('estudiante') ? $this->Html->link($historico->estudiante->id, ['controller' => 'Estudiantes', 'action' => 'view', $historico->estudiante->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Periodo') ?></dt>
                    <dd><?= $historico->has('periodo') ? $this->Html->link($historico->periodo->codename, ['controller' => 'Periodos', 'action' => 'view', $historico->periodo->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Asignatura') ?></dt>
                    <dd><?= $historico->has('asignatura') ? $this->Html->link($historico->asignatura->codename, ['controller' => 'Asignaturas', 'action' => 'view', $historico->asignatura->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Calificacion') ?></dt>
                    <dd><?= h($historico->calificacion) ?></dd>
                    <dt scope="row"><?= __('Seccion') ?></dt>
                    <dd><?= h($historico->seccion) ?></dd>
                    <dt scope="row"><?= __('Responsable') ?></dt>
                    <dd><?= h($historico->responsable) ?></dd>
                    <dt scope="row"><?= __('Id') ?></dt>
                    <dd><?= $this->Number->format($historico->id) ?></dd>
                    <dt scope="row"><?= __('Created') ?></dt>
                    <dd><?= h($historico->created) ?></dd>
                    <dt scope="row"><?= __('Modified') ?></dt>
                    <dd><?= h($historico->modified) ?></dd>
                </dl>
            </div>
            <div class="box-footer">
		        <?= $this->Html->link('<i class="fa fa-pencil"></i>&nbsp;'.__('Edit'),
			        ['action' => 'edit',$historico->id],['class' => 'btn bg-olive btn-flat pull-left','escape' => false]); 
		        ?>
		        <?= $this->Html->link('<i class="fa fa-power-off"></i>&nbsp;Cerrar',
			        ['action' => 'index'],['class' => 'btn bg-maroon btn-flat pull-right','escape' => false]); 
		        ?>
            </div>
        </div>
    </div>
</div>

