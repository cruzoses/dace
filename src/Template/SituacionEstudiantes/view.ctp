<div class="row">
	<div class="col-md-12">
		<div class="box box-info box-solid">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-info"></i>&nbsp;Situacion Estudiante</h3>
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
                    <dd><?= $situacionEstudiante->has('estudiante') ? $this->Html->link($situacionEstudiante->estudiante->full_name, ['controller' => 'Estudiantes', 'action' => 'view', $situacionEstudiante->estudiante->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Programa') ?></dt>
                    <dd><?= $situacionEstudiante->has('programa') ? $this->Html->link($situacionEstudiante->programa->codename, ['controller' => 'Programas', 'action' => 'view', $situacionEstudiante->programa->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Asignatura') ?></dt>
                    <dd><?= $situacionEstudiante->has('asignatura') ? $this->Html->link($situacionEstudiante->asignatura->codename, ['controller' => 'Asignaturas', 'action' => 'view', $situacionEstudiante->asignatura->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Periodo') ?></dt>
                    <dd><?= $situacionEstudiante->has('periodo') ? $this->Html->link($situacionEstudiante->periodo->codename, ['controller' => 'Periodos', 'action' => 'view', $situacionEstudiante->periodo->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Seccion') ?></dt>
                    <dd><?= h($situacionEstudiante->seccion) ?></dd>
                    <dt scope="row"><?= __('Calificacion') ?></dt>
                    <dd><?= h($situacionEstudiante->calificacion) ?></dd>
                    <dt scope="row"><?= __('Responsable') ?></dt>
                    <dd><?= h($situacionEstudiante->responsable) ?></dd>
                    <dt scope="row"><?= __('Id') ?></dt>
                    <dd><?= $this->Number->format($situacionEstudiante->id) ?></dd>
                    <dt scope="row"><?= __('Created') ?></dt>
                    <dd><?= h($situacionEstudiante->created) ?></dd>
                    <dt scope="row"><?= __('Modified') ?></dt>
                    <dd><?= h($situacionEstudiante->modified) ?></dd>
                </dl>
            </div>
            <div class="box-footer">
		        <?= $this->Html->link('<i class="fa fa-pencil"></i>&nbsp;'.__('Edit'),
			        ['action' => 'edit',$situacionEstudiante->id],['class' => 'btn bg-olive btn-flat pull-left','escape' => false]); 
		        ?>
		        <?= $this->Html->link('<i class="fa fa-power-off"></i>&nbsp;Cerrar',
			        ['action' => 'index'],['class' => 'btn bg-maroon btn-flat pull-right','escape' => false]); 
		        ?>
            </div>
        </div>
    </div>
</div>

