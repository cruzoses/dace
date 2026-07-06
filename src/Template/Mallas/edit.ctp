<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Malla $malla
 */
?>
<div class="row">
    <div class="col-md-12">    
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Mallas</h3>
		        <div class="box-tools pull-right">
			        <?= $this->Html->link('<i class="fa fa-times"></i>',
				        ['action' => 'index'],
				        ['class'=>'btn btn-box-tool','title'=>'cerrar','escape'=>false]);
			        ?>
		        </div>
            </div>                    
            <?= $this->Form->create($malla, [
                'role' => 'form', 
                'align' => [
                    'sm' => ['left' => 6, 'middle' => 6, 'right' => 12],
                    'md' => ['left' => 2,'middle' => 9,'right' => 1]
                ],
                'class' => 'horizontal']); 
            ?>
            <div class="box-body">
                <?php
                    echo $this->Form->control('carrera_id', ['type' => 'select', 'options' => $carreras, 'empty' => true,
                        'class' => 'form-control select2', 'data-width' => '100%', 'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('programa_id', ['type' => 'select','options' => $programas, 'empty' => true,
                        'class' => 'form-control select2', 'data-width' => '100%','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('trayecto_id', ['type' => 'select', 'options' => $trayectos, 'empty' => true,
                        'class' => 'form-control select2', 'data-width' => '100%', 'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('asignatura_id', ['type' => 'select', 'options' => $asignaturas, 'empty' => true,
                        'class' => 'form-control select2', 'data-width' => '100%','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('nota_minima', ['type' => 'text', 'label' => 'Nota Miníma',
                        'class' => 'isNumeric','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
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
