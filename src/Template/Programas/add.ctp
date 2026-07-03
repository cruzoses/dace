<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Programa $programa
 */
?>
<div class="row">
    <div class="col-md-12">    
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Registrar Programa</h3>
		        <div class="box-tools pull-right">
			        <?= $this->Html->link('<i class="fa fa-close"></i>',
				        ['action' => 'index'],
				        ['class'=>'btn btn-box-tool','title'=>'cerrar','escape'=>false]);
			        ?>
		        </div>
            </div>                    
            <?= $this->Form->create($programa, [
                'role' => 'form', 
                'align' => [
                    'sm' => ['left' => 6, 'middle' => 6, 'right' => 12],
                    'md' => ['left' => 2,'middle' => 9,'right' => 1]
                ],
                'class' => 'horizontal']); 
            ?>
            <div class="box-body">
                <?php
                    echo $this->Form->control('codigo', ['label' => 'Código','class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('nombre', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('carrera_id', ['type' => 'select','options' => $carreras, 'empty' => true,
                        'class' => 'form-control select2', 'data-widh' => '100%','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('subsistema_id', ['type' => 'select','options' => $subsistemas, 'empty' => true,
                        'class' => 'form-control select2', 'data-widh' => '100%','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('nota_minima', ['type' => 'text','class' => 'isNumeric','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('creditos', ['type' => 'text','class' => 'isNumeric','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('pasantia', ['type' => 'checkbox', 'value' => 0,'label' => 'Requiere Practica Profesional']);
                    echo $this->Form->control('califica', ['type' => 'checkbox', 'value' => 0,'label' => 'Califica']);
                    echo $this->Form->hidden('activo', ['type' => 'checkbox', 'value' => 1]);
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
