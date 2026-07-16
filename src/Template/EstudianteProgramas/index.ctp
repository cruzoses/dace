<div class="row">
    <div class="col-xs-12">
        <div class="box box-sace box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Lista de Estudiante Programas</h3>
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
			        <?= $this->element('search_form', [
                        'title' => 'Buscar Estudiante Programa',
                        'searchFields' => $searchFields,
                        'filtros' => $filtros
                    ]); ?>
		        </div>
                <?php $this->Paginator->options(['url' => $filtros]); ?>
                <table class="table table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('estudiante_id', 'Cédula') ?></th>
                            <th scope="col">Nombre</th>
                            <th scope="col"><?= $this->Paginator->sort('sede_id') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('programa_id') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('fecha_egreso') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('cohorte') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('indice') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('culminado') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('activo') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                            <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($estudianteProgramas as $estudiantePrograma): ?>
                            <tr>
                                <td><?= $this->Number->format($estudiantePrograma->id) ?></td>
                            <td><?= $estudiantePrograma->has('estudiante') ? $this->Html->link($estudiantePrograma->estudiante->cedula, ['controller' => 'Estudiantes', 'action' => 'view', $estudiantePrograma->estudiante->id]) : '' ?></td>
                            <td><?= $estudiantePrograma->has('estudiante') ? h($estudiantePrograma->estudiante->full_name) : '' ?></td>
                                <td><?= $estudiantePrograma->has('sede') ? $this->Html->link($estudiantePrograma->sede->codename, ['controller' => 'Sedes', 'action' => 'view', $estudiantePrograma->sede->id]) : '' ?></td>
                                <td><?= $estudiantePrograma->has('programa') ? $this->Html->link($estudiantePrograma->programa->codename, ['controller' => 'Programas', 'action' => 'view', $estudiantePrograma->programa->id]) : '' ?></td>
                                            <td><?= h($estudiantePrograma->fecha_egreso) ?></td>
                                        <td><?= h($estudiantePrograma->cohorte) ?></td>
                                        <td><?= $this->Number->format($estudiantePrograma->indice) ?></td>
                                        <td><?= h($estudiantePrograma->culminado) ?></td>
                                        <td><?= h($estudiantePrograma->activo) ?></td>
                                        <td><?= h($estudiantePrograma->created) ?></td>
                                        <td><?= h($estudiantePrograma->modified) ?></td>
                                <td class="actions text-center">
                                    <?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $estudiantePrograma->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                    <?= $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $estudiantePrograma->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                    <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['action' => 'delete', $estudiantePrograma->id], ['confirm' => __('Are you sure you want to delete # {0}?', $estudiantePrograma->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
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
