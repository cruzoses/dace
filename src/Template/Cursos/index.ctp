<div class="row">

    <div class="col-xs-12">
        <div class="box box-info box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Lista de Cursos</h3>
                <div class="box-tools pull-right">
			        <button type="button" class="btn btn-box-tool" id="goSearch" title="Buscar">
				        <i class="fa fa-search"></i>
			        </button>
			        <button type="button" class="btn btn-box-tool" data-widget="collapse">
				        <i class="fa fa-minus"></i>
			        </button>
                    <?= $this->Html->link('<i class="fa fa-times"></i>',
                        ['action' => 'homepage'], ['class'=>'btn btn-box-tool','escape' => false]) 
                    ?>
                </div>
            </div>        
            <div class="box-body table-responsive no-padding">
		        <div class="oculto" id="buscar">
			        <?= $this->element('search_form', ['title' => 'Buscar Curso', 'searchFields' => $searchFields, 'filtros' => $filtros]);?>
		        </div>
                <table class="table table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('sede_id') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('periodo_id') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('carrera_id') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('trayecto_id') ?></th>
							<th scope="col"><?= $this->Paginator->sort('asignatura_id') ?></th>
                            <!--th scope="col"><?= $this->Paginator->sort('profesores') ?></th-->
                            <th scope="col"><?= $this->Paginator->sort('docente_id') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('seccion') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('cupos') ?></th>
                            <!--th scope="col"><?= $this->Paginator->sort('aula_id') ?></th-->
                            <!--th scope="col"><?= $this->Paginator->sort('horario') ?></th-->
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('activo') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('created') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('modified') ?></th>
                            <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $this->Paginator->options(['url' => $filtros]); ?>
                        <?php foreach ($cursos as $curso): ?>
                            <tr>
                                <td><?= $this->Number->format($curso->id) ?></td>
                                <td><?= $curso->has('sede') ? h($curso->sede->nombre) : '' ?></td>
                                <td><?= $curso->has('periodo') ? h($curso->periodo->codigo) : '' ?></td>
                                <td><?= $curso->has('carrera') ? h($curso->carrera->codigo) : '' ?></td>
                                <td class="text-center"><?= $curso->has('trayecto') ? h($curso->trayecto->codigo) : '' ?></td>
								<td><?= $curso->has('asignatura') ? h($curso->asignatura->codigo) : '' ?></td>
                                <!--td><?= h($curso->profesores) ?></td-->
                                <td><?= $curso->has('docente') ? h($curso->docente->name) : '' ?></td>
                                <td class="text-center"><?= h($curso->seccion) ?></td>
                                <td class="text-center"><?= $this->Number->format($curso->cupos) ?></td>
                                <!--td class="text-center"><?= $curso->has('aula') ? h($curso->aula->id) : '' ?></td-->
                                <!--td class="text-center"><?= h($curso->horario) ?></td-->
                                <td class="text-center"><?= h($curso->activo) ? 'Sí' : 'No' ?></td>
                                <td class="text-center"><?= h($curso->created) ?></td>
                                <td class="text-center"><?= h($curso->modified) ?></td>
                                <td class="actions text-center">
                                    <?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $curso->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                    <?= $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $curso->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                    <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['action' => 'delete', $curso->id], ['confirm' => __('Are you sure you want to delete # {0}?', $curso->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="no-padding">
                        <tr>
                            <td colspan="13" class="text-center">
                                <div class="paginator">
                                    <ul class="pagination pagination-sm">
                                        <?= $this->Paginator->first('<i class="fa fa-angle-double-left"></i>',['class' => 'btn btn-sm','escape' => false]) ?>
                                        <?= $this->Paginator->prev('<i class="fa fa-angle-left"></i>',['class' => 'btn btn-sm','escape' => false]) ?>
                                        <?= $this->Paginator->numbers(['before' => '','after' => '']) ?>
                                        <?= $this->Paginator->next('<i class="fa fa-angle-right"></i>',['class' => 'btn btn-sm','escape' => false]) ?>
                                        <?= $this->Paginator->last('<i class="fa fa-angle-double-right"></i>',['class' => 'btn btn-sm','escape' => false]) ?>
                                    </ul>
                                    <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="box-footer">
                <?= $this->Html->link('<i class="fa fa-plus"></i>&nbsp;'.__('New'), 
                    ['action' => 'add'], ['class'=>'btn btn-success pull-left','escape' => false]) 
                ?>
                <?= $this->Html->link('<i class="fa fa-times"></i>&nbsp;'.__('Go Back'),
                    ['action' => 'homepage'], ['class'=>'btn bg-maroon pull-right','escape' => false]) 
                ?>
            </div>
        </div>
    </div>
	
</div>
