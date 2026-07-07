<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Estudiante $estudiante
 * @var array $searchFields
 * @var array $filtros
 * @var array $estudiantes
 */
?>
<div class="content">
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
                        <?= $this->Html->link('<i class="fa fa-times"></i>',
                            ['action' => 'index'], ['class'=>'btn btn-box-tool','escape' => false])
                        ?>
                    </div>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('cedula','Cédula') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('nombres') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('apellidos') ?></th>
                                <th scope="col" class="text-center"><?= $this->Paginator->sort('fecha_nacimiento') ?></th>
                                <th scope="col" class="text-center"><?= $this->Paginator->sort('sexo') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('expediente') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $this->Paginator->options(['url' => $filtros]); ?>
                            <?php foreach ($estudiantes as $estudiante): ?>
                                <tr>
                                    <td><?= $this->Number->format($estudiante->id) ?></td>
                                    <td><?= $this->Number->format($estudiante->cedula) ?></td>
                                    <td><?= h($estudiante->nombres) ?></td>
                                    <td><?= h($estudiante->apellidos) ?></td>
                                    <td class="text-center"><?= h($estudiante->fecha_nacimiento) ?></td>
                                    <td class="text-center"><?= h($estudiante->sexo) ?></td>
                                    <td>
                                        <?= $this->Html->link($estudiante->expediente_formateado ?? $estudiante->expediente,
                                            ['action' => 'estudiante', $estudiante->id],['class' => 'btn btn-default btn-xs']) 
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="no-padding">
                            <tr>
                                <td colspan="7" class="text-center">
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
                    <?= $this->Html->link('<i class="fa fa-arrow-left"></i>&nbsp;Nueva B&uacute;squeda',
                        ['action' => 'index'], ['class'=>'btn btn-primary pull-left','escape' => false])
                    ?>
                    <?= $this->Html->link('<i class="fa fa-times"></i>&nbsp;Cerrar',
                        ['action' => 'homepage'], ['class'=>'btn bg-maroon pull-right','escape' => false])
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>