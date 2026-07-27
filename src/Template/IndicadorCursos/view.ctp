<div class="row">
	<div class="col-md-12">
		<div class="box box-info box-solid">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-info"></i>&nbsp;Indicador Curso</h3>
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
                    <dt scope="row"><?= __('Curso') ?></dt>
                    <dd><?= $indicadorCurso->has('curso') ? $this->Html->link($indicadorCurso->curso->id, ['controller' => 'Cursos', 'action' => 'view', $indicadorCurso->curso->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Indicadore') ?></dt>
                    <dd><?= $indicadorCurso->has('indicadore') ? $this->Html->link($indicadorCurso->indicadore->nombre, ['controller' => 'Indicadores', 'action' => 'view', $indicadorCurso->indicadore->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Id') ?></dt>
                    <dd><?= $this->Number->format($indicadorCurso->id) ?></dd>
                    <dt scope="row"><?= __('Escala Nota') ?></dt>
                    <dd><?= $this->Number->format($indicadorCurso->escala_nota) ?></dd>
                    <dt scope="row"><?= __('Desde') ?></dt>
                    <dd><?= h($indicadorCurso->desde) ?></dd>
                    <dt scope="row"><?= __('Hasta') ?></dt>
                    <dd><?= h($indicadorCurso->hasta) ?></dd>
                    <dt scope="row"><?= __('Created') ?></dt>
                    <dd><?= h($indicadorCurso->created) ?></dd>
                    <dt scope="row"><?= __('Modified') ?></dt>
                    <dd><?= h($indicadorCurso->modified) ?></dd>
                </dl>
            </div>
            <div class="box-footer">
		        <?= $this->Html->link('<i class="fa fa-pencil"></i>&nbsp;'.__('Edit'),
			        ['action' => 'edit',$indicadorCurso->id],['class' => 'btn bg-olive btn-flat pull-left','escape' => false]); 
		        ?>
		        <?= $this->Html->link('<i class="fa fa-power-off"></i>&nbsp;Cerrar',
			        ['action' => 'index',$indicadorCurso->curso->id],
                    ['class' => 'btn bg-maroon btn-flat pull-right','escape' => false]); 
		        ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box box-default box-solid">
            <div class="box-header with-border">                
                <h3 class="box-title"><i class="fa fa-share-alt"></i>&nbsp;Contenidos Cursos</h3>
            </div>
            <div class="box-body">
                <?php if (!empty($indicadorCurso->contenidos_cursos)): ?>
                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th scope="col"><?= __('Id') ?></th>
                                <th scope="col"><?= __('Fecha') ?></th>
                                <th scope="col"><?= __('Descripcion') ?></th>
                                <th scope="col"><?= __('Detalle') ?></th>
                                <th scope="col"><?= __('Ponderacion') ?></th>
                                <th scope="col"><?= __('Indicador Curso Id') ?></th>
                                <th scope="col"><?= __('Activo') ?></th>
                                <th scope="col"><?= __('Created') ?></th>
                                <th scope="col"><?= __('Modified') ?></th>
                                <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($indicadorCurso->contenidos_cursos as $contenidosCursos): ?>
                                <tr>
                                    <td><?= h($contenidosCursos->id) ?></td>
                                    <td><?= h($contenidosCursos->fecha) ?></td>
                                    <td><?= h($contenidosCursos->descripcion) ?></td>
                                    <td><?= h($contenidosCursos->detalle) ?></td>
                                    <td><?= h($contenidosCursos->ponderacion) ?></td>
                                    <td><?= h($contenidosCursos->indicador_curso_id) ?></td>
                                    <td><?= h($contenidosCursos->activo) ?></td>
                                    <td><?= h($contenidosCursos->created) ?></td>
                                    <td><?= h($contenidosCursos->modified) ?></td>
                                    <td class="actions text-center">
                                        <?= $this->Html->link('<i class="fa fa-eye"></i>', ['controller' => 'ContenidosCursos', 'action' => 'view', $contenidosCursos->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                        <?= $this->Html->link('<i class="fa fa-pencil"></i>', ['controller' => 'ContenidosCursos', 'action' => 'edit', $contenidosCursos->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                        <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['controller' => 'ContenidosCursos', 'action' => 'delete', $contenidosCursos->id], ['confirm' => __('Are you sure you want to delete # {0}?', $contenidosCursos->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
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

