<?php
/**
 * @var \App\View\AppView $this
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
                'url' => ['action' => 'students'],
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
                        ]);
                        echo $this->Form->control('cedula', [
                            'type' => 'text',
                            'label' => 'No. Cédula',
                            'class' => 'isNumeric',
                            'prepend' => '<i class="fa fa-id-card"></i>',
                        ]);
                        echo $this->Form->control('apellidos', [
                            'class' => 'isUpper',
                            'prepend' => '<i class="fa fa-asterisk"></i>',
                        ]);
                        echo $this->Form->control('nombres', [
                            'class' => 'isUpper',
                            'prepend' => '<i class="fa fa-asterisk"></i>',
                        ]);
                        echo $this->Form->control('id', [
                            'type' => 'text',
                            'label' => 'No. Id',
                            'class' => 'isNumeric',
                            'prepend' => '<i class="fa fa-hashtag"></i>',
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
    </div>
</div>
