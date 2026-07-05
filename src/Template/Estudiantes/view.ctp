<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Estudiante $estudiante
 * @var object $estudiante
 * @var array $aGeneros
*/
?>
<div class="box box-info box-solid">

	<div class="box-header with-border">
		<h3 class="box-title with-border"><i class="fa fa-info"></i>&nbsp;Datos del Estudiante</h3>
		<div class="box-tools pull-right">
			<?= $this->Html->link('<i class="fa fa-print"></i>',
				['controller' => 'reportes','action' => 'fichaEstudiante',$estudiante->id],
                ['class' => 'btn btn-box-tool', 'title'=>'Cerrar', 'escape' => false]);
			?>
			<button type="button" class="btn btn-box-tool" data-widget="collapse" title="Contraer">
				<i class="fa fa-minus"></i>
            </button>
			<?= $this->Html->link('<i class="fa fa-close"></i>',
				['action' => 'index'],['class' => 'btn btn-box-tool', 'title'=>'Cerrar', 'escape' => false]);
			?>
		</div>
	</div>			

	<div class="box-body table-responsive no-padding">
		<table id="Estudiantes" class="table table-bordered table-striped">
			<tbody>
				<tr>
					<td><strong>No. de Id</strong></td>
					<td>
						<?= $this->Html->link( $this->Number->format(h($estudiante->id)),['action' => 'edit', $estudiante->id]); ?>
					</td>
				</tr>
				<tr>
					<td><strong>Cédula</strong></td>
					<td>
						<?= $this->Html->link($this->Number->format(h($estudiante->cedula)),
							['controller' => 'datos', 'action' => 'view', $estudiante->id]); 
						?>
					</td>
				</tr>
				<tr>
					<td><strong>Nombres</strong></td>
					<td><?= h($estudiante->nombres); ?>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>Apellidos</strong></td>
					<td><?= h($estudiante->apellidos); ?>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>Fecha Nacimiento</strong></td>
					<td><?= h($estudiante->fecha_nacimiento); ?>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>Sexo</strong></td>
					<td><?= $aGeneros[h($estudiante->sexo)] ?>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>Discapacitado</strong></td>
					<td><?= h($estudiante->discapacitado) ? 'SI' : 'NO';?>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>Etnia Indígena</strong></td>
					<td><?= h($estudiante->etnia) ? 'SI' : 'NO';?>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>Lugar Nacimiento</strong></td>
					<td><?= h($estudiante->lugar_nacimiento); ?>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>Dirección</strong></td>
					<td><?= h($estudiante->direccion); ?>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>Teléfonos</strong></td>
					<td><?= h($estudiante->telefonos); ?>&nbsp;</td>
				</tr>				
				<tr>
					<td><strong>Email</strong></td>
					<td><?= h($estudiante->email); ?>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>Token de Registro</strong></td>
					<td><?= h($estudiante->token); ?>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>Código Opsu</strong></td>
					<td><?= h($estudiante->codigo_opsu); ?>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>Codigo Título Bachiller</strong></td>
					<td><?= h($estudiante->codigo_titulo); ?>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>Código Notas Bachiller</strong></td>
					<td><?= h($estudiante->codigo_notas); ?>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>Código Acta Nacimiento</strong></td>
					<td><?= h($estudiante->acta_nacimiento); ?>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>Expediente</strong></td>
					<td>
						<?= $this->Html->link($this->Number->format(h($estudiante->expediente)),
							['controller' => 'datos', 'action' => 'view', $estudiante->id] ); 
						?>
					</td>
				</tr>
				<tr>
					<td><strong>Per&iacute;odo Inicial</strong></td>
                    <td><?= $estudiante->has('periodo') ? h($estudiante->periodo->codename) : '' ?></td>
				</tr>				
				<tr>
					<td><strong>Usuario</strong></td>
					<td><?= $estudiante->has('usuario') ? h($estudiante->usuario->username) : '' ?></td>
				</tr>
				<tr>
					<td><strong>Activo</strong></td>
					<td><?= h($estudiante->activo) ? 'SI' : 'NO'; ?>&nbsp;</td>
				</tr>
				<tr>
					<td><strong><?= __('Created'); ?></strong></td>
					<td><?= h($estudiante->created);?>&nbsp;</td>
				</tr>
				<tr>
					<td><strong><?= __('Modified'); ?></strong></td>
					<td><?= h($estudiante->modified); ?>&nbsp;</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="box-footer">
		<?= $this->Html->link('<i class="fa fa-envelope"></i>&nbsp;Token de Registro',
			['action' => 'nuevotoken',$estudiante->id],
			['class' => 'btn bg-olive btn-flat pull-left','escape' => false]); 
		?>
		<?= $this->Html->link('<i class="fa fa-power-off"></i>&nbsp;Cerrar',
			['action' => 'index'],['class' => 'btn bg-maroon btn-flat pull-right','escape' => false]); 
		?>
	</div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box box-default box-solid">
            <div class="box-header with-border">
                <i class="fa fa-text-width"></i>
                <h3 class="box-title"><?= __('Direccion') ?></h3>
            </div>
            <div class="box-body">
                <?= $this->Text->autoParagraph($estudiante->direccion); ?>
            </div>
            <div class="box-footer"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box box-default box-solid">
            <div class="box-header with-border">
                <i class="fa fa-text-width"></i>
                <h3 class="box-title"><?= __('Lugar Nacimiento') ?></h3>
            </div>
            <div class="box-body">
                <?= $this->Text->autoParagraph($estudiante->lugar_nacimiento); ?>
            </div>
            <div class="box-footer"></div>
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
                <?php if (!empty($estudiante->estudiante_cursos)): ?>
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
                            <?php foreach ($estudiante->estudiante_cursos as $estudianteCursos): ?>
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
                <h3 class="box-title"><i class="fa fa-share-alt"></i>&nbsp;Estudiante Programas</h3>
                <div class="box-tools pull-right">
                    <?= $this->Html->link('<i class="fa fa-plus"></i>',
                        ['controller' => 'EstudianteProgramas', 'action' => 'nuevo', $estudiante->id],
                        ['class' => 'btn btn-box-tool', 'title' => 'Agregar Programa', 'escape' => false]);
                    ?>
                </div>
            </div>
            <div class="box-body">
                <?php if (!empty($estudiante->estudiante_programas)): ?>
                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th scope="col"><?= __('Id') ?></th>
                                <th scope="col"><?= __('Estudiante Id') ?></th>
                                <th scope="col"><?= __('Sede Id') ?></th>
                                <th scope="col"><?= __('Programa Id') ?></th>
                                <th scope="col"><?= __('Fecha Egreso') ?></th>
                                <th scope="col"><?= __('Cohorte') ?></th>
                                <th scope="col"><?= __('Indice') ?></th>
                                <th scope="col"><?= __('Culminado') ?></th>
                                <th scope="col"><?= __('Activo') ?></th>
                                <th scope="col"><?= __('Created') ?></th>
                                <th scope="col"><?= __('Modified') ?></th>
                                <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($estudiante->estudiante_programas as $estudianteProgramas): ?>
                                <tr>
                                    <td><?= h($estudianteProgramas->id) ?></td>
                                    <td><?= h($estudianteProgramas->estudiante_id) ?></td>
                                    <td><?= h($estudianteProgramas->sede_id) ?></td>
                                    <td><?= h($estudianteProgramas->programa_id) ?></td>
                                    <td><?= h($estudianteProgramas->fecha_egreso) ?></td>
                                    <td><?= h($estudianteProgramas->cohorte) ?></td>
                                    <td><?= h($estudianteProgramas->indice) ?></td>
                                    <td><?= h($estudianteProgramas->culminado) ?></td>
                                    <td><?= h($estudianteProgramas->activo) ?></td>
                                    <td><?= h($estudianteProgramas->created) ?></td>
                                    <td><?= h($estudianteProgramas->modified) ?></td>
                                    <td class="actions text-center">
                                        <?= $this->Html->link('<i class="fa fa-eye"></i>', ['controller' => 'EstudianteProgramas', 'action' => 'view', $estudianteProgramas->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                        <?= $this->Html->link('<i class="fa fa-pencil"></i>', ['controller' => 'EstudianteProgramas', 'action' => 'edit', $estudianteProgramas->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                        <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['controller' => 'EstudianteProgramas', 'action' => 'delete', $estudianteProgramas->id], ['confirm' => __('Are you sure you want to delete # {0}?', $estudianteProgramas->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
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
                <h3 class="box-title"><i class="fa fa-share-alt"></i>&nbsp;Graduandos</h3>
            </div>
            <div class="box-body">
                <?php if (!empty($estudiante->graduandos)): ?>
                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th scope="col"><?= __('Id') ?></th>
                                <th scope="col"><?= __('Institucion') ?></th>
                                <th scope="col"><?= __('Acto Id') ?></th>
                                <th scope="col"><?= __('Estudiante Id') ?></th>
                                <th scope="col"><?= __('Indice') ?></th>
                                <th scope="col"><?= __('Control') ?></th>
                                <th scope="col"><?= __('Created') ?></th>
                                <th scope="col"><?= __('Modified') ?></th>
                                <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($estudiante->graduandos as $graduandos): ?>
                                <tr>
                                    <td><?= h($graduandos->id) ?></td>
                                    <td><?= h($graduandos->institucion) ?></td>
                                    <td><?= h($graduandos->acto_id) ?></td>
                                    <td><?= h($graduandos->estudiante_id) ?></td>
                                    <td><?= h($graduandos->indice) ?></td>
                                    <td><?= h($graduandos->control) ?></td>
                                    <td><?= h($graduandos->created) ?></td>
                                    <td><?= h($graduandos->modified) ?></td>
                                    <td class="actions text-center">
                                        <?= $this->Html->link('<i class="fa fa-eye"></i>', ['controller' => 'Graduandos', 'action' => 'view', $graduandos->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                        <?= $this->Html->link('<i class="fa fa-pencil"></i>', ['controller' => 'Graduandos', 'action' => 'edit', $graduandos->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                        <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['controller' => 'Graduandos', 'action' => 'delete', $graduandos->id], ['confirm' => __('Are you sure you want to delete # {0}?', $graduandos->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
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
                <h3 class="box-title"><i class="fa fa-share-alt"></i>&nbsp;Historicos</h3>
            </div>
            <div class="box-body">
                <?php if (!empty($estudiante->historicos)): ?>
                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th scope="col"><?= __('Id') ?></th>
                                <th scope="col"><?= __('Estudiante Id') ?></th>
                                <th scope="col"><?= __('Periodo Id') ?></th>
                                <th scope="col"><?= __('Asignatura Id') ?></th>
                                <th scope="col"><?= __('Calificacion') ?></th>
                                <th scope="col"><?= __('Seccion') ?></th>
                                <th scope="col"><?= __('Responsable') ?></th>
                                <th scope="col"><?= __('Created') ?></th>
                                <th scope="col"><?= __('Modified') ?></th>
                                <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($estudiante->historicos as $historicos): ?>
                                <tr>
                                    <td><?= h($historicos->id) ?></td>
                                    <td><?= h($historicos->estudiante_id) ?></td>
                                    <td><?= h($historicos->periodo_id) ?></td>
                                    <td><?= h($historicos->asignatura_id) ?></td>
                                    <td><?= h($historicos->calificacion) ?></td>
                                    <td><?= h($historicos->seccion) ?></td>
                                    <td><?= h($historicos->responsable) ?></td>
                                    <td><?= h($historicos->created) ?></td>
                                    <td><?= h($historicos->modified) ?></td>
                                    <td class="actions text-center">
                                        <?= $this->Html->link('<i class="fa fa-eye"></i>', ['controller' => 'Historicos', 'action' => 'view', $historicos->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                        <?= $this->Html->link('<i class="fa fa-pencil"></i>', ['controller' => 'Historicos', 'action' => 'edit', $historicos->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                        <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['controller' => 'Historicos', 'action' => 'delete', $historicos->id], ['confirm' => __('Are you sure you want to delete # {0}?', $historicos->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
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
                <h3 class="box-title"><i class="fa fa-share-alt"></i>&nbsp;Notas Cursos</h3>
            </div>
            <div class="box-body">
                <?php if (!empty($estudiante->notas_cursos)): ?>
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
                            <?php foreach ($estudiante->notas_cursos as $notasCursos): ?>
                                <tr>
                                    <td><?= h($notasCursos->id) ?></td>
                                    <td><?= h($notasCursos->contenido_curso_id) ?></td>
                                    <td><?= h($notasCursos->estudiante_id) ?></td>
                                    <td><?= h($notasCursos->calificacion) ?></td>
                                    <td><?= h($notasCursos->responsable) ?></td>
                                    <td><?= h($notasCursos->created) ?></td>
                                    <td><?= h($notasCursos->modified) ?></td>
                                    <td class="actions text-center">
                                        <?= $this->Html->link('<i class="fa fa-eye"></i>', ['controller' => 'NotasCursos', 'action' => 'view', $notasCursos->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                        <?= $this->Html->link('<i class="fa fa-pencil"></i>', ['controller' => 'NotasCursos', 'action' => 'edit', $notasCursos->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                        <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['controller' => 'NotasCursos', 'action' => 'delete', $notasCursos->id], ['confirm' => __('Are you sure you want to delete # {0}?', $notasCursos->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
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
                <h3 class="box-title"><i class="fa fa-share-alt"></i>&nbsp;Situacion Estudiantes</h3>
            </div>
            <div class="box-body">
                <?php if (!empty($estudiante->situacion_estudiantes)): ?>
                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th scope="col"><?= __('Id') ?></th>
                                <th scope="col"><?= __('Estudiante Id') ?></th>
                                <th scope="col"><?= __('Created') ?></th>
                                <th scope="col"><?= __('Modified') ?></th>
                                <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($estudiante->situacion_estudiantes as $situacionEstudiantes): ?>
                                <tr>
                                    <td><?= h($situacionEstudiantes->id) ?></td>
                                    <td><?= h($situacionEstudiantes->estudiante_id) ?></td>
                                    <td><?= h($situacionEstudiantes->created) ?></td>
                                    <td><?= h($situacionEstudiantes->modified) ?></td>
                                    <td class="actions text-center">
                                        <?= $this->Html->link('<i class="fa fa-eye"></i>', ['controller' => 'SituacionEstudiantes', 'action' => 'view', $situacionEstudiantes->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                        <?= $this->Html->link('<i class="fa fa-pencil"></i>', ['controller' => 'SituacionEstudiantes', 'action' => 'edit', $situacionEstudiantes->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                        <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['controller' => 'SituacionEstudiantes', 'action' => 'delete', $situacionEstudiantes->id], ['confirm' => __('Are you sure you want to delete # {0}?', $situacionEstudiantes->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
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

