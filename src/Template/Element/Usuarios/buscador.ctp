<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Usuario $usuario
 */
?>
<div class="content">
    <div class="box box-warning box-solid">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Buscar Usuario</h3>
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
                <?php
                    echo $this->Form->control('id',['label' => 'No. de ID', 'type' => 'text',
                        'class' => 'form-control isNumeric', 'prepend' => '<i class="fa fa-asterisk"></i>',
                        'value' => $filtros['id'] ?? '']
                    );
                    echo $this->Form->control('cedula',['label' => 'Cédula', 'type' => 'text', 
                        'class' => 'form-control isNumeric', 'prepend' => '<i class="fa fa-asterisk"></i>',
                        'value' => $filtros['cedula'] ?? '']
                    );
                    echo $this->Form->control('nombres',['class' => 'form-control isUpper',
                        'prepend' => '<i class="fa fa-asterisk"></i>', 'autocomplete' => false,
                        'value' => $filtros['nombres'] ?? '']
                    );
                    echo $this->Form->control('apellidos',['class' => 'form-control isUpper',
                        'prepend' => '<i class="fa fa-asterisk"></i>', 'autocomplete' => false,
                        'value' => $filtros['apellidos'] ?? '']
                    );
                    echo $this->Form->control('email',['class' => 'form-control isLower',
                        'prepend' => '<i class="fa fa-asterisk"></i>', 'autocomplete' => false,
                        'value' => $filtros['email'] ?? '']
                    );
                ?>
            </fieldset>
        </div>            
        <div class="box-footer text-center">
            <?= $this->Form->button('<i class="fa fa-search"></i>&nbsp;Buscar',['type' => 'submit',
                'class' => 'btn btn-success btn-sm','escape' => false]); 
            ?>
            <?= $this->Html->link('<i class="fa fa-eraser"></i>&nbsp;Limpiar',
                ['action' => 'index'],
                ['class' => 'btn btn-danger btn-sm','escape' => false]); 
            ?>
        </div>
        <?= $this->Form->end(); ?>
    </div>
</div>
