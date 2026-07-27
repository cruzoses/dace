<div class="row">
	<div class="col-md-12">
		<div class="box box-info box-solid">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-info"></i>&nbsp;Contenido Curso</h3>
				<div class="box-tools pull-right">
			        <button type="button" class="btn btn-box-tool" data-widget="collapse">
				        <i class="fa fa-minus"></i>
			        </button>
                <?= $this->Html->link('<i class="fa fa-times"></i>',
			        ['action' => 'index', $nCursoId],['class'=>'btn btn-box-tool','title'=>'cerrar','escape'=>false]);
		        ?>
		        </div>
        	</div>        
        	<div class="box-body">
          		<dl class="dl-horizontal">
                    <dt scope="row"><?= __('Descripcion') ?></dt>
                    <dd><?= h($contenidoCurso->descripcion) ?></dd>
                    <dt scope="row"><?= __('Indicador Curso') ?></dt>
                    <dd><?= $contenidoCurso->has('indicador_curso') ? $this->Html->link($contenidoCurso->indicador_curso->id, ['controller' => 'IndicadorCursos', 'action' => 'view', $contenidoCurso->indicador_curso->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Id') ?></dt>
                    <dd><?= $this->Number->format($contenidoCurso->id) ?></dd>
                    <dt scope="row"><?= __('Ponderacion') ?></dt>
                    <dd><?= $this->Number->format($contenidoCurso->ponderacion) ?></dd>
                    <dt scope="row"><?= __('Fecha') ?></dt>
                    <dd><?= h($contenidoCurso->fecha) ?></dd>
                    <dt scope="row"><?= __('Created') ?></dt>
                    <dd><?= h($contenidoCurso->created) ?></dd>
                    <dt scope="row"><?= __('Modified') ?></dt>
                    <dd><?= h($contenidoCurso->modified) ?></dd>
                    <dt scope="row"><?= __('Activo') ?></dt>
                    <dd><?= $contenidoCurso->activo ? __('Yes') : __('No'); ?></dd>
                </dl>
            </div>
            <div class="box-footer">
		        <?= $this->Html->link('<i class="fa fa-pencil"></i>&nbsp;'.__('Edit'),
			        ['action' => 'edit',$contenidoCurso->id],['class' => 'btn bg-olive btn-flat pull-left','escape' => false]); 
		        ?>
		        <?= $this->Html->link('<i class="fa fa-power-off"></i>&nbsp;Cerrar',
			        ['action' => 'index', $nCursoId],['class' => 'btn bg-maroon btn-flat pull-right','escape' => false]); 
		        ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box box-default box-solid">
            <div class="box-header with-border">
                <i class="fa fa-text-width"></i>
                <h3 class="box-title"><?= __('Detalle') ?></h3>
            </div>
            <div class="box-body">
                <?= $this->Text->autoParagraph($contenidoCurso->detalle); ?>
            </div>
            <div class="box-footer"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box box-default box-solid">
            <div class="box-header with-border">                
                <h3 class="box-title"><i class="fa fa-share-alt"></i>&nbsp;Notas Cursos</h3>
            </div>
            <div class="box-body">
                <?php if (!empty($contenidoCurso->curso_notas)): ?>
                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th scope="col"><?= __('Id') ?></th>
                                <th scope="col"><?= __('Contenido Curso Id') ?></th>
                                <th scope="col"><?= __('Estudiante Id') ?></th>
                                <th scope="col"><?= __('Calificacion') ?></th>
                                <th scope="col"><?= __('Responsable') ?></th>
                                <th scope="col"><?= __('Created') ?></th>
                                <th scope="col"><?= __('Modified') ?></th>
                                <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contenidoCurso->curso_notas as $cursoNotas): ?>
                                <tr>
                                    <td><?= h($cursoNotas->id) ?></td>
                                    <td><?= h($cursoNotas->contenido_curso_id) ?></td>
                                    <td><?= h($cursoNotas->estudiante_id) ?></td>
                                    <td><?= h($cursoNotas->calificacion) ?></td>
                                    <td><?= h($cursoNotas->responsable) ?></td>
                                    <td><?= h($cursoNotas->created) ?></td>
                                    <td><?= h($cursoNotas->modified) ?></td>
                                    <td class="actions text-center">
                                        <?= $this->Html->link('<i class="fa fa-eye"></i>', ['controller' => 'CursoNotas', 'action' => 'view', $cursoNotas->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                        <?= $this->Html->link('<i class="fa fa-pencil"></i>', ['controller' => 'CursoNotas', 'action' => 'edit', $cursoNotas->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                        <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['controller' => 'CursoNotas', 'action' => 'delete', $cursoNotas->id], ['confirm' => __('Are you sure you want to delete # {0}?', $cursoNotas->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
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

