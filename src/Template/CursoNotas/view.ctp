<div class="row">
	<div class="col-md-12">
		<div class="box box-info box-solid">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-info"></i>&nbsp;Curso Nota</h3>
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
                    <dt scope="row"><?= __('Contenido Curso') ?></dt>
                    <dd><?= $cursoNota->has('contenido_curso') ? $this->Html->link($cursoNota->contenido_curso->id, ['controller' => 'ContenidoCursos', 'action' => 'view', $cursoNota->contenido_curso->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Estudiante') ?></dt>
                    <dd><?= $cursoNota->has('estudiante') ? $this->Html->link($cursoNota->estudiante->full_name, ['controller' => 'Estudiantes', 'action' => 'view', $cursoNota->estudiante->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Calificacion') ?></dt>
                    <dd><?= h($cursoNota->calificacion) ?></dd>
                    <dt scope="row"><?= __('Responsable') ?></dt>
                    <dd><?= h($cursoNota->responsable) ?></dd>
                    <dt scope="row"><?= __('Id') ?></dt>
                    <dd><?= $this->Number->format($cursoNota->id) ?></dd>
                    <dt scope="row"><?= __('Created') ?></dt>
                    <dd><?= h($cursoNota->created) ?></dd>
                    <dt scope="row"><?= __('Modified') ?></dt>
                    <dd><?= h($cursoNota->modified) ?></dd>
                </dl>
            </div>
            <div class="box-footer">
		        <?= $this->Html->link('<i class="fa fa-pencil"></i>&nbsp;'.__('Edit'),
			        ['action' => 'edit',$cursoNota->id],['class' => 'btn bg-olive btn-flat pull-left','escape' => false]); 
		        ?>
		        <?= $this->Html->link('<i class="fa fa-power-off"></i>&nbsp;Cerrar',
			        ['action' => 'index'],['class' => 'btn bg-maroon btn-flat pull-right','escape' => false]); 
		        ?>
            </div>
        </div>
    </div>
</div>

