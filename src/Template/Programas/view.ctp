<div class="row">
	<div class="col-md-12">
		<div class="box box-info box-solid">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-info"></i>&nbsp;Programa</h3>
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
                    <dt scope="row"><?= __('Codigo') ?></dt>
                    <dd><?= h($programa->codigo) ?></dd>
                    <dt scope="row"><?= __('Nombre') ?></dt>
                    <dd><?= h($programa->nombre) ?></dd>
                    <dt scope="row"><?= __('Carrera') ?></dt>
                    <dd><?= $programa->has('carrera') ? $this->Html->link($programa->carrera->nombre, ['controller' => 'Carreras', 'action' => 'view', $programa->carrera->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Subsistema') ?></dt>
                    <dd><?= $programa->has('subsistema') ? $this->Html->link($programa->subsistema->id, ['controller' => 'Subsistemas', 'action' => 'view', $programa->subsistema->id]) : '' ?></dd>
                    <dt scope="row"><?= __('Nota Minima') ?></dt>
                    <dd><?= h($programa->nota_minima) ?></dd>
                    <dt scope="row"><?= __('Id') ?></dt>
                    <dd><?= $this->Number->format($programa->id) ?></dd>
                    <dt scope="row"><?= __('Creditos') ?></dt>
                    <dd><?= $this->Number->format($programa->creditos) ?></dd>
                    <dt scope="row"><?= __('Created') ?></dt>
                    <dd><?= h($programa->created) ?></dd>
                    <dt scope="row"><?= __('Modified') ?></dt>
                    <dd><?= h($programa->modified) ?></dd>
                    <dt scope="row"><?= __('Activo') ?></dt>
                    <dd><?= $programa->activo ? __('Yes') : __('No'); ?></dd>
                </dl>
            </div>
            <div class="box-footer">
		        <?= $this->Html->link('<i class="fa fa-pencil"></i>&nbsp;'.__('Edit'),
			        ['action' => 'edit',$programa->id],['class' => 'btn bg-olive btn-flat pull-left','escape' => false]); 
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
                <h3 class="box-title"><i class="fa fa-share-alt"></i>&nbsp;Cursos</h3>
            </div>
            <div class="box-body">
                <?php if (!empty($programa->cursos)): ?>
                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th scope="col"><?= __('Id') ?></th>
                                <th scope="col"><?= __('Sede Id') ?></th>
                                <th scope="col"><?= __('Periodo Id') ?></th>
                                <th scope="col"><?= __('Carrera Id') ?></th>
                                <th scope="col"><?= __('Programa Id') ?></th>
                                <th scope="col"><?= __('Trayecto Id') ?></th>
                                <th scope="col"><?= __('Docente Id') ?></th>
                                <th scope="col"><?= __('Seccion') ?></th>
                                <th scope="col"><?= __('Cupos') ?></th>
                                <th scope="col"><?= __('Aula Id') ?></th>
                                <th scope="col"><?= __('Activo') ?></th>
                                <th scope="col"><?= __('Created') ?></th>
                                <th scope="col"><?= __('Modified') ?></th>
                                <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($programa->cursos as $cursos): ?>
                                <tr>
                                    <td><?= h($cursos->id) ?></td>
                                    <td><?= h($cursos->sede_id) ?></td>
                                    <td><?= h($cursos->periodo_id) ?></td>
                                    <td><?= h($cursos->carrera_id) ?></td>
                                    <td><?= h($cursos->programa_id) ?></td>
                                    <td><?= h($cursos->trayecto_id) ?></td>
                                    <td><?= h($cursos->docente_id) ?></td>
                                    <td><?= h($cursos->seccion) ?></td>
                                    <td><?= h($cursos->cupos) ?></td>
                                    <td><?= h($cursos->aula_id) ?></td>
                                    <td><?= h($cursos->activo) ?></td>
                                    <td><?= h($cursos->created) ?></td>
                                    <td><?= h($cursos->modified) ?></td>
                                    <td class="actions text-center">
                                        <?= $this->Html->link('<i class="fa fa-eye"></i>', ['controller' => 'Cursos', 'action' => 'view', $cursos->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                        <?= $this->Html->link('<i class="fa fa-pencil"></i>', ['controller' => 'Cursos', 'action' => 'edit', $cursos->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                        <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['controller' => 'Cursos', 'action' => 'delete', $cursos->id], ['confirm' => __('Are you sure you want to delete # {0}?', $cursos->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
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
            </div>
            <div class="box-body">
                <?php if (!empty($programa->estudiante_programas)): ?>
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
                            <?php foreach ($programa->estudiante_programas as $estudianteProgramas): ?>
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
                <h3 class="box-title"><i class="fa fa-share-alt"></i>&nbsp;Mallas</h3>
            </div>
            <div class="box-body">
                <?php if (!empty($programa->mallas)): ?>
                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th scope="col"><?= __('Id') ?></th>
                                <th scope="col"><?= __('Programa Id') ?></th>
                                <th scope="col"><?= __('Asignatura Id') ?></th>
                                <th scope="col"><?= __('Nota Minima') ?></th>
                                <th scope="col"><?= __('Created') ?></th>
                                <th scope="col"><?= __('Modified') ?></th>
                                <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($programa->mallas as $mallas): ?>
                                <tr>
                                    <td><?= h($mallas->id) ?></td>
                                    <td><?= h($mallas->programa_id) ?></td>
                                    <td><?= h($mallas->asignatura_id) ?></td>
                                    <td><?= h($mallas->nota_minima) ?></td>
                                    <td><?= h($mallas->created) ?></td>
                                    <td><?= h($mallas->modified) ?></td>
                                    <td class="actions text-center">
                                        <?= $this->Html->link('<i class="fa fa-eye"></i>', ['controller' => 'Mallas', 'action' => 'view', $mallas->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                        <?= $this->Html->link('<i class="fa fa-pencil"></i>', ['controller' => 'Mallas', 'action' => 'edit', $mallas->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                        <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['controller' => 'Mallas', 'action' => 'delete', $mallas->id], ['confirm' => __('Are you sure you want to delete # {0}?', $mallas->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
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

