<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Estudiante $estudiante
 * @var array $aOrigen
 * @var array $searchFields
 * @var array $filtros
 * @var array $estudiantes
*/
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-info box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Lista de Estudiantes</h3>
                <div class="box-tools pull-right">
			        <button type="button" class="btn btn-box-tool" id="goSearch" title="Buscar">
				        <i class="fa fa-search"></i>
			        </button>
			        <button type="button" class="btn btn-box-tool" data-widget="collapse">
				        <i class="fa fa-minus"></i>
			        </button>
                    <?= $this->Html->link('<i class="fa fa-close"></i>',
                        ['action' => 'homepage'], ['class'=>'btn btn-box-tool','escape' => false]) 
                    ?>
                </div>
            </div>        
            <div class="box-body table-responsive no-padding">
		        <div class="oculto" id="buscar">
			        <?= $this->element('search_form', [
                        'title' => 'Buscar Estudiante', 'searchFields' => $searchFields,
                        'filtros' => $filtros]);
                    ?>
		        </div>
                <table class="table table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('origen') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('cedula','Cédula') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('nombres') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('apellidos') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('fecha_nacimiento') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('sexo') ?></th>
                            <!--
                            <th scope="col"><?= $this->Paginator->sort('estado_civil') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('discapacitado') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('etnia') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('telefonos') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('email') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('pais_id') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('estado_id') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('municipio_id') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('parroquia_id') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('asignado') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('codigo_opsu') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('fecha_notas') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('codigo_notas') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('fecha_titulo') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('codigo_titulo') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('expediente') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('token') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('usuario_id') ?></th>
                            -->
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('activo') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('created') ?></th>
                            <th scope="col" class="text-center"><?= $this->Paginator->sort('modified') ?></th>
                            <th scope="col" class="actions text-center"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $this->Paginator->options(['url' => $filtros]); ?>
                        <?php foreach ($estudiantes as $estudiante): ?>
                            <tr>
                                <td><?= $this->Number->format($estudiante->id) ?></td>
                                <td class="text-center"><?= h($estudiante->origen) ?></td>
                                <td><?= $this->Number->format($estudiante->cedula) ?></td>
                                <td><?= h($estudiante->nombres) ?></td>
                                <td ><?= h($estudiante->apellidos) ?></td>
                                <td class="text-center"><?= h($estudiante->fecha_nacimiento) ?></td>
                                <td class="text-center"><?= h($estudiante->sexo) ?></td>
                                <!--
                                <td><?= h($estudiante->estado_civil) ?></td>
                                <td><?= h($estudiante->discapacitado) ?></td>
                                <td><?= h($estudiante->etnia) ?></td>
                                <td><?= h($estudiante->telefonos) ?></td>
                                <td><?= h($estudiante->email) ?></td>
                                <td><?= $estudiante->has('paise') ? $this->Html->link($estudiante->paise->nombre, ['controller' => 'Paises', 'action' => 'view', $estudiante->paise->id]) : '' ?></td>
                                <td><?= $estudiante->has('estado') ? $this->Html->link($estudiante->estado->nombre, ['controller' => 'Estados', 'action' => 'view', $estudiante->estado->id]) : '' ?></td>
                                <td><?= $estudiante->has('municipio') ? $this->Html->link($estudiante->municipio->nombre, ['controller' => 'Municipios', 'action' => 'view', $estudiante->municipio->id]) : '' ?></td>
                                <td><?= $estudiante->has('parroquia') ? $this->Html->link($estudiante->parroquia->id, ['controller' => 'Parroquias', 'action' => 'view', $estudiante->parroquia->id]) : '' ?></td>
                                <td><?= h($estudiante->asignado) ?></td>
                                <td><?= h($estudiante->codigo_opsu) ?></td>
                                <td><?= h($estudiante->fecha_notas) ?></td>
                                <td><?= h($estudiante->codigo_notas) ?></td>
                                <td><?= h($estudiante->fecha_titulo) ?></td>
                                <td><?= h($estudiante->codigo_titulo) ?></td>
                                <td><?= h($estudiante->expediente_formateado) ?></td>
                                <td><?= h($estudiante->token) ?></td>
                                <td><?= $estudiante->has('usuario') ? $this->Html->link($estudiante->usuario->alias, ['controller' => 'Usuarios', 'action' => 'view', $estudiante->usuario->id]) : '' ?></td>
                                -->
                                <td class="text-center"><?= h($estudiante->activo) ? 'SI' : 'NO' ?></td>
                                <td class="text-center"><?= h($estudiante->created) ?></td>
                                <td class="text-center"><?= h($estudiante->modified) ?></td>
                                <td class="actions text-center">
                                    <?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $estudiante->id], ['class'=>'btn btn-warning btn-xs','escape' => false]) ?>
                                    <?= $this->Html->link('<i class="fa fa-pencil"></i>', ['action' => 'edit', $estudiante->id], ['class'=>'btn btn-info btn-xs','escape' => false]) ?>
                                    <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['action' => 'delete', $estudiante->id], ['confirm' => __('Are you sure you want to delete # {0}?', $estudiante->id), 'class'=>'btn btn-danger btn-xs','escape' => false]) ?>
                                    <?= $this->Html->link('<i class="fa fa-gear"></i>', 
                                        ['controller' => 'EstudianteProgramas','action' => 'nuevo', $estudiante->id], 
                                        ['class'=>'btn bg-olive btn-xs','title' => 'Registrar Programa','escape' => false]) 
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="no-padding">
                        <tr>
                            <td colspan="12" class="text-center">
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
                <?= $this->Html->link('<i class="fa fa-close"></i>&nbsp;'.__('Go Back'),
                    ['action' => 'homepage'], ['class'=>'btn bg-maroon pull-right','escape' => false]) 
                ?>
            </div>
        </div>
    </div>
</div>
