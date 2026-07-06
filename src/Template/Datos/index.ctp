<?php
/**
 * @var \App\View\AppView $this
 */
?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="box box-primary box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i>&nbsp;Buscar Estudiantes</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                    <?= $this->Html->link('<i class="fa fa-close"></i>',
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
                    'md' => ['left' => 3, 'middle' => 8, 'right' => 1]
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
                            'label' => 'Expediente',
                            'prepend' => '<i class="fa fa-folder-open"></i>',
                        ]);
                        echo $this->Form->control('cedula', [
                            'type' => 'text',
                            'label' => 'C&eacute;dula',
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
                            'label' => 'ID',
                            'class' => 'isNumeric',
                            'prepend' => '<i class="fa fa-hashtag"></i>',
                        ]);
                    ?>
                </fieldset>
            </div>
            <div class="box-footer">
                <?= $this->Form->button('<i class="fa fa-search"></i>&nbsp;Buscar',
                    ['type' => 'submit', 'class' => 'btn btn-success btn-flat pull-left', 'escape' => false])
                ?>
                <?= $this->Html->link('<i class="fa fa-eraser"></i>&nbsp;Limpiar',
                    ['action' => 'index'], ['class' => 'btn btn-default btn-flat pull-right', 'escape' => false])
                ?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
