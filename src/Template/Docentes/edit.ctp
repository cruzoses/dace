<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Docente $docente
 */
?>
<div class="row">
    <div class="col-md-12">    
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Docentes</h3>
		        <div class="box-tools pull-right">
			        <?= $this->Html->link('<i class="fa fa-close"></i>',
				        ['action' => 'index'],
				        ['class'=>'btn btn-box-tool','title'=>'cerrar','escape'=>false]);
			        ?>
		        </div>
            </div>                    
            <?= $this->Form->create($docente, [
                'role' => 'form', 
                'align' => [
                    'sm' => ['left' => 6, 'middle' => 6, 'right' => 12],
                    'md' => ['left' => 2,'middle' => 9,'right' => 1]
                ],
                'class' => 'horizontal']); 
            ?>
            <div class="box-body">
                <?php
                    echo $this->Form->control('cedula', ['type' => 'text', 'label' => 'Cédula',
                        'class' => 'isNumeric','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('nombres', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('apellidos', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('fecha_nacimiento', ['type' => 'text','class' => 'datepicker',
                        'prepend' => '<i class="fa fa-asterisk"></i>','append' => '<i class="fa fa-calendar"></i>']);
                    echo $this->Form->control('sexo', ['type' => 'select', 'options' => $aGeneros, 'empty' => true,
                        'class' => 'form-control select2', 'data-width' => '100%', 'required' => true,
                        'prepend' => '<i class="fa fa-asterisk"></i>',  ]
                    );
                    echo $this->Form->control('email', ['class' => 'isLower','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('telefonos', ['label' => 'Teléfonos','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('departamento_id', ['type' => 'select', 'options' => $departamentos, 'empty' => true,
                        'class' => 'form-control select2', 'data-width' => '100%', 'required' => true,
                        'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->hidden('token');
                    echo $this->Form->hidden('usuario_id');
                    echo $this->Form->control('activo', ['type' => 'checkbox']);
                ?>
            </div>            
            <div class="box-footer">
		        <?= $this->Form->button('<i class="fa fa-save"></i>&nbsp;Guardar',
			        ['type' => 'submit','class'=>'btn btn-success btn-flat pull-left','escape'=>false]); 
		        ?>
		        <?= $this->Html->link('<i class="fa fa-power-off"></i>&nbsp;Cerrar',
			        ['action' => 'index'],['class' => 'btn bg-maroon btn-flat pull-right','escape' => false]); 
		        ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
