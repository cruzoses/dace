<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Historico $historico
 */
?>
<div class="row">
    <div class="col-md-12">    
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Historicos</h3>
		        <div class="box-tools pull-right">
			        <?= $this->Html->link('<i class="fa fa-close"></i>',
				        ['action' => 'index'],
				        ['class'=>'btn btn-box-tool','title'=>'cerrar','escape'=>false]);
			        ?>
		        </div>
            </div>                    
            <?= $this->Form->create($historico, [
                'role' => 'form', 
                'align' => [
                    'sm' => ['left' => 6, 'middle' => 6, 'right' => 12],
                    'md' => ['left' => 2,'middle' => 9,'right' => 1]
                ],
                'class' => 'horizontal']); 
            ?>
            <div class="box-body">
                <?php
                    echo $this->Form->control('estudiante_id', ['prepend' => '<i class="fa fa-asterisk"></i>','class' => 'isUpper','options' => $estudiantes]);
                    echo $this->Form->control('periodo_id', ['prepend' => '<i class="fa fa-asterisk"></i>','class' => 'isUpper','options' => $periodos]);
                    echo $this->Form->control('asignatura_id', ['prepend' => '<i class="fa fa-asterisk"></i>','class' => 'isUpper','options' => $asignaturas]);
                    echo $this->Form->control('calificacion', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('seccion', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('responsable', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
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
