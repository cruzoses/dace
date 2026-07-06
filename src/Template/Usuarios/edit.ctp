<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Usuario $usuario
 */
?>
<div class="row">
    <div class="col-md-12">    
        <div class="box box-info box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Editar Usuario</h3>
		        <div class="box-tools pull-right">
			        <button type="button" class="btn btn-box-tool" data-widget="collapse">
				        <i class="fa fa-minus"></i>
			        </button>
			        <?= $this->Html->link('<i class="fa fa-times"></i>',
				        ['action' => 'index'],
				        ['class'=>'btn btn-box-tool','title'=>'cerrar','escape'=>false]);
			        ?>
		        </div>
            </div>                    
            <?= $this->Form->create($usuario, [
                'role' => 'form', 
                'align' => [
                    'sm' => ['left' => 6, 'middle' => 6, 'right' => 12],
                    'md' => ['left' => 2,'middle' => 9,'right' => 1]
                ],
                'class' => 'horizontal']); 
            ?>
            <div class="box-body">
                <?php
                    echo $this->Form->control('cedula',['type' => 'text', 'label' => 'Cédula',
                        'class' => 'isNumeric','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('nombres',['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('apellidos',['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('fecha_nacimiento', ['type' => 'text', 'class' => 'datepicker', 
                        'prepend' => '<i class="fa fa-calendar"></i>']
                    );
                    echo $this->Form->control('sexo',['type' => 'select', 'options' => $aGeneros, 
                        'class' => 'select2', 'prepend' => '<i class="fa fa-calendar"></i>', 'empty' => true]
                    );
                    echo $this->Form->control('email',['class' => 'isLower','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('telefonos',['label' => 'Teléfonos','prepend' => '<i class="fa fa-phone"></i>']);
                    echo $this->Form->control('username',['label' => 'Usuario', 'prepend' => '<i class="fa fa-user"></i>']);
                    echo $this->Form->control('password',['label' => 'Contraseña', 'prepend' => '<i class="fa fa-key"></i>']);
                    echo $this->Form->hidden('activo',['value' => 1]);
                    echo $this->Form->control('rols._ids', ['label' => 'Tipo de Usuario',
                        'options' => $rols,'prepend' => '<i class="fa fa-user"></i>']
                    );
                    echo $this->Form->control('activo');
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
<pre>
    <?php //print_r($usuario); ?>
</pre>
