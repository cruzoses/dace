<div class="row">
	<div class="col-md-12">
		<div class="box box-info box-solid">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-info"></i>&nbsp;Curso</h3>
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
                    <dt scope="row"><?= __('Sede') ?></dt>
                    <dd><?= $curso->has('sede') ? $this->Html->link($curso->sede->nombre, ['controller' => 'Sedes', 'action' => 'view', $curso->sede->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Periodo') ?></dt>
                    <dd><?= $curso->has('periodo') ? $this->Html->link($curso->periodo->id, ['controller' => 'Periodos', 'action' => 'view', $curso->periodo->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Carrera') ?></dt>
                    <dd><?= $curso->has('carrera') ? $this->Html->link($curso->carrera->nombre, ['controller' => 'Carreras', 'action' => 'view', $curso->carrera->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Programa') ?></dt>
                    <dd><?= $curso->has('programa') ? $this->Html->link($curso->programa->id, ['controller' => 'Programas', 'action' => 'view', $curso->programa->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Trayecto') ?></dt>
                    <dd><?= $curso->has('trayecto') ? $this->Html->link($curso->trayecto->id, ['controller' => 'Trayectos', 'action' => 'view', $curso->trayecto->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Docente') ?></dt>
                    <dd><?= $curso->has('docente') ? $this->Html->link($curso->docente->id, ['controller' => 'Docentes', 'action' => 'view', $curso->docente->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Seccion') ?></dt>
                    <dd><?= h($curso->seccion) ?></dd>
                    <dt scope="row"><?= __('Aula') ?></dt>
                    <dd><?= $curso->has('aula') ? $this->Html->link($curso->aula->id, ['controller' => 'Aulas', 'action' => 'view', $curso->aula->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Id') ?></dt>
                    <dd><?= $this->Number->format($curso->id) ?></dd>
                    <dt scope="row"><?= __('Cupos') ?></dt>
                    <dd><?= $this->Number->format($curso->cupos) ?></dd>
                    <dt scope="row"><?= __('Created') ?></dt>
                    <dd><?= h($curso->created) ?></dd>
                    <dt scope="row"><?= __('Modified') ?></dt>
                    <dd><?= h($curso->modified) ?></dd>
                    <dt scope="row"><?= __('Activo') ?></dt>
                    <dd><?= $curso->activo ? __('Yes') : __('No'); ?></dd>
                </dl>
            </div>
            <div class="box-footer">
		        <?= $this->Html->link('<i class="fa fa-pencil"></i>&nbsp;'.__('Edit'),
			        ['action' => 'edit',$curso->id],['class' => 'btn bg-olive btn-flat pull-left','escape' => false]); 
		        ?>
		        <?= $this->Html->link('<i class="fa fa-power-off"></i>&nbsp;Cerrar',
			        ['action' => 'index'],['class' => 'btn bg-maroon btn-flat pull-right','escape' => false]); 
		        ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box box-default box-solid">
            <div class="box-header with-border">                
                <h3 class="box-title"><i class="fa fa-share-alt"></i>&nbsp;Estudiante Cursos</h3>
            </div>
            <div class="box-body">
                <?php if (!empty($curso->estudiante_cursos)): ?>
                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th scope="col"><?= __('Id') ?></th>
                                <th scope="col"><?= __('Curso Id') ?></th>
                                <th scope="col"><?= __('Estudiante Id') ?></th>
                                <th scope="col"><?= __('Calificacion') ?></th>
                                <th scope="col"><?= __('Recuperacion') ?></th>
                                <th scope="col"><?= __('Definitiva') ?></th>
                                <th scope="col"><?= __('Responsable') ?></th>
                                <th scope="col"><?= __('Observacion') ?></th>
                                <th scope="col"><?= __('Activo') ?></th>
                                <th scope="col"><?= __('Created') ?></th>
                                <th scope="col"><?= __('Modified') ?></th>
                                <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($curso->estudiante_cursos as $estudianteCursos): ?>
                                <tr>
                                    <td><?= h($estudianteCursos->id) ?></td>
                                    <td><?= h($estudianteCursos->curso_id) ?></td>
                                    <td><?= h($estudianteCursos->estudiante_id) ?></td>
                                    <td><?= h($estudianteCursos->calificacion) ?></td>
                                    <td><?= h($estudianteCursos->recuperacion) ?></td>
                                    <td><?= h($estudianteCursos->definitiva) ?></td>
                                    <td><?= h($estudianteCursos->responsable) ?></td>
                                    <td><?= h($estudianteCursos->observacion) ?></td>
                                    <td><?= h($estudianteCursos->activo) ?></td>
                                    <td><?= h($estudianteCursos->created) ?></td>
                                    <td><?= h($estudianteCursos->modified) ?></td>
                                    <td class="actions text-center">
                                        <?= $this->Html->link('<i class="fa fa-eye"></i>', ['controller' => 'EstudianteCursos', 'action' => 'view', $estudianteCursos->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                        <?= $this->Html->link('<i class="fa fa-pencil"></i>', ['controller' => 'EstudianteCursos', 'action' => 'edit', $estudianteCursos->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                        <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['controller' => 'EstudianteCursos', 'action' => 'delete', $estudianteCursos->id], ['confirm' => __('Are you sure you want to delete # {0}?', $estudianteCursos->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
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

<div class="row">
    <div class="col-md-12">
        <div class="box box-default box-solid">
            <div class="box-header with-border">                
                <h3 class="box-title"><i class="fa fa-share-alt"></i>&nbsp;Indicador Cursos</h3>
            </div>
            <div class="box-body">
                <?php if (!empty($curso->indicador_cursos)): ?>
                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th scope="col"><?= __('Id') ?></th>
                                <th scope="col"><?= __('Curso Id') ?></th>
                                <th scope="col"><?= __('Indicador Id') ?></th>
                                <th scope="col"><?= __('Desde') ?></th>
                                <th scope="col"><?= __('Hasta') ?></th>
                                <th scope="col"><?= __('Escala Nota') ?></th>
                                <th scope="col"><?= __('Created') ?></th>
                                <th scope="col"><?= __('Modified') ?></th>
                                <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($curso->indicador_cursos as $indicadorCursos): ?>
                                <tr>
                                    <td><?= h($indicadorCursos->id) ?></td>
                                    <td><?= h($indicadorCursos->curso_id) ?></td>
                                    <td><?= h($indicadorCursos->indicador_id) ?></td>
                                    <td><?= h($indicadorCursos->desde) ?></td>
                                    <td><?= h($indicadorCursos->hasta) ?></td>
                                    <td><?= h($indicadorCursos->escala_nota) ?></td>
                                    <td><?= h($indicadorCursos->created) ?></td>
                                    <td><?= h($indicadorCursos->modified) ?></td>
                                    <td class="actions text-center">
                                        <?= $this->Html->link('<i class="fa fa-eye"></i>', ['controller' => 'IndicadorCursos', 'action' => 'view', $indicadorCursos->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                        <?= $this->Html->link('<i class="fa fa-pencil"></i>', ['controller' => 'IndicadorCursos', 'action' => 'edit', $indicadorCursos->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                        <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['controller' => 'IndicadorCursos', 'action' => 'delete', $indicadorCursos->id], ['confirm' => __('Are you sure you want to delete # {0}?', $indicadorCursos->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
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

