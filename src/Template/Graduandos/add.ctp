<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Graduando $graduando
 */
?>
<div class="row">
    <div class="col-md-12">    
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Graduandos</h3>
		        <div class="box-tools pull-right">
			        <?= $this->Html->link('<i class="fa fa-times"></i>',
				        ['action' => 'index'],
				        ['class'=>'btn btn-box-tool','title'=>'cerrar','escape'=>false]);
			        ?>
		        </div>
            </div>                    
            <?= $this->Form->create($graduando, [
                'role' => 'form', 
                'align' => [
                    'sm' => ['left' => 6, 'middle' => 6, 'right' => 12],
                    'md' => ['left' => 2,'middle' => 9,'right' => 1]
                ],
                'class' => 'horizontal']); 
            ?>
            <div class="box-body">
                <?php
                    echo $this->Form->hidden('estudiante_id', ['value' => $estudiante ? $estudiante->id : '']);
                    echo $this->Form->hidden('carrera_id', ['value' => $carrera ? $carrera->id : '']);
                    echo $this->Form->hidden('programa_id', ['value' => $programa ? $programa->id : '']);

                    echo $this->Form->control('estudiante', [
                        'type' => 'text',
                        'label' => 'Estudiante',
                        'value' => $estudiante ? $estudiante->full_name : '',
                        'disabled' => true,
                    ]);
                    echo $this->Form->control('carrera', [
                        'type' => 'text',
                        'label' => 'Carrera',
                        'value' => $carrera ? $carrera->codename : '',
                        'disabled' => true,
                    ]);
                    echo $this->Form->control('programa', [
                        'type' => 'text',
                        'label' => 'Programa',
                        'value' => $programa ? $programa->codename : '',
                        'disabled' => true,
                    ]);
                    echo $this->Form->control('institucion', [
                        'options' => $instituciones,
                        'prepend' => '<i class="fa fa-asterisk"></i>',
                    ]);
                    echo $this->Form->control('solicitud', [
                        'options' => $solicitudes,
                        'prepend' => '<i class="fa fa-asterisk"></i>',
                    ]);
                    echo $this->Form->control('acto_id', ['prepend' => '<i class="fa fa-asterisk"></i>','class' => 'isUpper','options' => $actos]);
                    echo $this->Form->control('indice', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('control', ['class' => 'isUpper isNumGuion','prepend' => '<i class="fa fa-asterisk"></i>']);
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
