<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Estudiante[] $estudiantes
 * @var array $searchFields
 * @var array $filtros
 */
?>
<div class="content row">
    <div class="col-md-12">
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i>&nbsp;Buscar Estudiantes</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                    <?= $this->Html->link('<i class="fa fa-times"></i>',
                        ['action' => 'homepage'], ['class'=>'btn btn-box-tool','escape' => false])
                    ?>
                </div>
            </div>
            <?= $this->Form->create(null, [
                'url' => ['action' => 'index'],
                'type' => 'get',
                'role' => 'form',
                'align' => [
                    'sm' => ['left' => 6, 'middle' => 6, 'right' => 12],
                    'md' => ['left' => 2, 'middle' => 9, 'right' => 1]
                ],
                'class' => 'horizontal'
            ]) ?>
            <div class="box-body">
                <fieldset>
                    <legend class="text-primary" style="font-size:1.1em;border-bottom:1px solid #3c8dbc;">
                        <i class="fa fa-filter"></i>&nbsp;Criterios de B&uacute;squeda
                    </legend>
                    <?php
                        echo $this->Form->control('expediente', [
                            'type' => 'text',
                            'label' => 'No. Expediente',
                            'prepend' => '<i class="fa fa-folder-open"></i>',
                            'value' => $filtros['expediente'] ?? '',
                        ]);
                        echo $this->Form->control('cedula', [
                            'type' => 'text',
                            'label' => 'No. Cédula',
                            'class' => 'isNumeric',
                            'prepend' => '<i class="fa fa-id-card"></i>',
                            'value' => $filtros['cedula'] ?? '',
                        ]);
                        echo $this->Form->control('apellidos', [
                            'class' => 'isUpper',
                            'prepend' => '<i class="fa fa-asterisk"></i>',
                            'value' => $filtros['apellidos'] ?? '',
                        ]);
                        echo $this->Form->control('nombres', [
                            'class' => 'isUpper',
                            'prepend' => '<i class="fa fa-asterisk"></i>',
                            'value' => $filtros['nombres'] ?? '',
                        ]);
                        echo $this->Form->control('id', [
                            'type' => 'text',
                            'label' => 'No. Id',
                            'class' => 'isNumeric',
                            'prepend' => '<i class="fa fa-hashtag"></i>',
                            'value' => $filtros['id'] ?? '',
                        ]);
                    ?>
                </fieldset>
            </div>
            <div class="box-footer text-center">
                <?= $this->Form->button('<i class="fa fa-search"></i>&nbsp;Buscar',
                    ['type' => 'submit', 'class' => 'btn btn-success btn-flat', 'escape' => false])
                ?>
                <?= $this->Html->link('<i class="fa fa-eraser"></i>&nbsp;Limpiar',
                    ['action' => 'index'], ['class' => 'btn bg-maroon btn-flat', 'escape' => false])
                ?>
            </div>
            <?= $this->Form->end() ?>
        </div>

        <?php if (!empty($estudiantes)): ?>
        <div class="box box-info box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Resultados</h3>
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
                                <td><?= $this->Html->link(
                                    $estudiante->expediente_formateado ?? $estudiante->expediente,
                                    ['action' => 'estudiante', $estudiante->id]
                                ) ?></td>
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
                <?= $this->Html->link('<i class="fa fa-times"></i>&nbsp;Cerrar',
                    ['action' => 'homepage'], ['class'=>'btn bg-maroon pull-right','escape' => false])
                ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
// Add JS for autofocus and enter-to-search
$this->Html->scriptStart(['block' => true]);
echo "$(document).ready(function() { \$('#expediente').focus(); });";
$this->Html->scriptEnd();
?>