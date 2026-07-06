<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Asignatura $asignatura
 */
?>
<div class="row">
    <div class="col-md-12">    
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Registrar Asignaturas</h3>
		        <div class="box-tools pull-right">
			        <?= $this->Html->link('<i class="fa fa-times"></i>',
				        ['action' => 'index'],
				        ['class'=>'btn btn-box-tool','title'=>'cerrar','escape'=>false]);
			        ?>
		        </div>
            </div>                    
            <?= $this->Form->create($asignatura, [
                'role' => 'form', 
                'align' => [
                    'sm' => ['left' => 6, 'middle' => 6, 'right' => 12],
                    'md' => ['left' => 2,'middle' => 9,'right' => 1]
                ],
                'class' => 'horizontal']); 
            ?>
            <div class="box-body">
                <?php
                    echo $this->Form->control('codigo', ['label' => 'Código',
                        'class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('nombre', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('horas_teoricas', ['type' => 'text', 'label' => 'Horas Teóricas',
                        'class' => 'isNumeric','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('horas_practicas', ['type' => 'text', 'label' => 'Horas Prácticas',
                        'class' => 'isNumeric','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('frecuencia', ['type' => 'select', 'options' => $aFrecuencia, 'empty' => true,
                        'class' => 'form-control select2','data-width' => '100%','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('calificacion', ['type' => 'select', 'options' => $aTipoNota, 'empty' => true,
                        'label' => 'Tipo de Calificación','class' => 'form-control select2','data-width' => '100%',
                        'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('creditos', ['label' => 'Créditos', 'type' => 'text',
                        'class' => 'isNumeric','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('costo', ['type' => 'text', 'class' => 'isDecimal','label' => 'Costo',
                        'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('requisitos', ['class' => 'isUpper', 'rows' => '2','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('convalidacion', ['label' => 'Convalidación',
                        'class' => 'isUpper','rows' => '2','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('grupo_asignatura_id', ['type' => 'select', 'options' => $grupoAsignaturas, 'empty' => true,
                        'class' => 'form-control select2','data-width' => '100%','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->hidden('activa', ['value' => '1']);
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
