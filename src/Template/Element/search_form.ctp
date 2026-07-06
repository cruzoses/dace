<?php
use Cake\Utility\Inflector;
$title = $title ?? 'Buscar';
?>
<div class="content">
    <div class="box box-warning box-solid">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;<?= h($title) ?></h3>
            <div class="box-tools pull-right">
                <?= $this->Html->link('<i class="fa fa-times"></i>',
                    ['action' => 'index'],
                    ['class'=>'btn btn-box-tool','title'=>'cerrar','escape'=>false]);
                ?>
            </div>
        </div>
        <?= $this->Form->create(null, ['url' => ['action' => 'buscar'],
            'role' => 'form', 'id' => 'buscadorForm',
            'align' => [
                'sm' => ['left' => 6, 'middle' => 6, 'right' => 12],
                'md' => ['left' => 2,'middle' => 9,'right' => 1]
            ],
            'class' => 'horizontal']);
        ?>
        <div class="box-body">
            <fieldset>
                <?php foreach ($searchFields as $field => $config):
                    $value = $filtros[$field] ?? '';
                    $label = $config['label'] ?? Inflector::humanize($field);
                    $class = $config['class'] ?? 'form-control';
                    $type = $config['type'] ?? 'text';
                    $options = $config['options'] ?? [];
                    $empty = $config['empty'] ?? false;
                    $prepend = $config['prepend'] ?? false;
                    $append = $config['append'] ?? false;

                    switch ($type):
                        case 'select': ?>
                            <?= $this->Form->control($field, [
                                'label' => $label,
                                'type' => 'select',
                                'options' => $options,
                                'empty' => $empty,
                                'prepend' => $prepend ?? '<i class="fa fa-asterisk"></i>',
                                'class' => 'form-control select2',
                                'data-width' => '100%',
                                'value' => $value,
                            ]) ?>
                        <?php break; ?>
                        <?php case 'date': ?>
                            <?= $this->Form->control($field, [
                                'label' => $label,
                                'type' => 'text',
                                'class' => $class,
                                'value' => $value,
                                'prepend' => $prepend ?? '<i class="fa fa-calendar"></i>',
                                'append' => $append,
                            ]) ?>
                        <?php break; ?>
                        <?php default: ?>
                            <?= $this->Form->control($field, [
                                'label' => $label,
                                'type' => 'text',
                                'class' => $class,
                                'value' => $value,
                                'prepend' => $prepend ?? '<i class="fa fa-asterisk"></i>',
                                'append' => $append,
                            ]) ?>
                    <?php endswitch; ?>
                <?php endforeach; ?>
            </fieldset>
        </div>
        <div class="box-footer text-center">
            <?= $this->Form->button('<i class="fa fa-search"></i>&nbsp;Buscar', ['type' => 'submit',
                'class' => 'btn btn-success btn-sm','escape' => false]);
            ?>
            <?= $this->Html->link('<i class="fa fa-eraser"></i>&nbsp;Limpiar',
                ['action' => 'index'],['class' => 'btn btn-danger btn-sm','escape' => false]);
            ?>
        </div>
        <?= $this->Form->end(); ?>
    </div>
</div>
