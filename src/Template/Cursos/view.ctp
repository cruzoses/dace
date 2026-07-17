<div class="row">
	<div class="col-md-12">
		<div class="box box-info box-solid">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-info"></i>&nbsp;Curso</h3>
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
                    <dd><?= $curso->has('sede') ? $this->Html->link($curso->sede->codename, ['controller' => 'Sedes', 'action' => 'view', $curso->sede->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Periodo') ?></dt>
                    <dd><?= $curso->has('periodo') ? $this->Html->link($curso->periodo->codename, ['controller' => 'Periodos', 'action' => 'view', $curso->periodo->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Carrera') ?></dt>
                    <dd><?= $curso->has('carrera') ? $this->Html->link($curso->carrera->nombre, ['controller' => 'Carreras', 'action' => 'view', $curso->carrera->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Programas') ?></dt>
                    <dd><?= h($curso->programas) ?></dd>
                    <dt scope="row"><?= __('Trayecto') ?></dt>
                    <dd><?= $curso->has('trayecto') ? $this->Html->link($curso->trayecto->codename, ['controller' => 'Trayectos', 'action' => 'view', $curso->trayecto->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Asignatura') ?></dt>
                    <dd><?= $curso->has('asignatura') ? $this->Html->link($curso->asignatura->codename, ['controller' => 'Asignaturas', 'action' => 'view', $curso->asignatura->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Profesores') ?></dt>
                    <dd><?= h($curso->profesores) ?></dd>
                    <dt scope="row"><?= __('Docente') ?></dt>
                    <dd><?= $curso->has('docente') ? $this->Html->link($curso->docente->codename, ['controller' => 'Docentes', 'action' => 'view', $curso->docente->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Seccion') ?></dt>
                    <dd><?= h($curso->seccion) ?></dd>
                    <dt scope="row"><?= __('Aula') ?></dt>
                    <dd><?= $curso->has('aula') ? $this->Html->link($curso->aula->nombre, ['controller' => 'Aulas', 'action' => 'view', $curso->aula->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Horario') ?></dt>
                    <dd><?= h($curso->horario) ?></dd>
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
                <h3 class="box-title"><i class="fa fa-users"></i>&nbsp;Estudiantes Inscritos (<?= count($curso->estudiante_cursos) ?>)</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <?php if (!empty($curso->estudiante_cursos)): ?>
                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th>Cédula</th>
                                <th>Estudiante</th>
                                <th class="text-center">Calificación</th>
                                <th class="text-center">Recuperación</th>
                                <th class="text-center">Definitiva</th>
                                <th>Responsable</th>
                                <th>Observación</th>
                                <th class="text-center">Activo</th>
                                <th class="text-center">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($curso->estudiante_cursos as $i => $ec): ?>
                                <tr>
                                    <td class="text-center"><?= ($i + 1) ?></td>
                                    <td><?= $ec->has('estudiante') ? $this->Number->format($ec->estudiante->cedula) : h($ec->estudiante_id) ?></td>
                                    <td>
                                        <?= $ec->has('estudiante')
                                            ? $this->Html->link($ec->estudiante->full_name, ['controller' => 'Datos', 'action' => 'estudiante', $ec->estudiante->id])
                                            : h($ec->estudiante_id) ?>
                                    </td>
                                    <td class="text-center"><?= h($ec->calificacion ?? '') ?></td>
                                    <td class="text-center"><?= h($ec->recuperacion ?? '') ?></td>
                                    <td class="text-center"><?= h($ec->definitiva ?? '') ?></td>
                                    <td><?= h($ec->responsable) ?></td>
                                    <td><?= h($ec->observacion ?? '') ?></td>
                                    <td class="text-center"><?= $ec->activo ? 'Sí' : 'No' ?></td>
                                    <td class="actions text-center">
                                        <?= $this->Form->postLink('<i class="fa fa-trash"></i>',
                                            ['controller' => 'EstudianteCursos', 'action' => 'eliminar', $ec->id],
                                            ['confirm' => '¿Está seguro de eliminar esta inscripción?', 'class' => 'btn btn-danger btn-xs', 'escape' => false])
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-center text-muted">No hay estudiantes inscritos en este curso.</p>
                <?php endif; ?>
            </div>
            <div class="box-footer">
                <span class="text-muted"><i class="fa fa-info-circle"></i> Para inscribir estudiantes, utilice la vista del estudiante &rarr; Inscripciones.</span>
            </div>            
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

