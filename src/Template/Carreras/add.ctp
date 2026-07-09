<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Carrera $carrera
 * @var array $mensionCarreras
 * @var array $sedes
*/
?>
<div class="row">
    <div class="col-md-12">    
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Registrar Carrera</h3>
		        <div class="box-tools pull-right">
			        <?= $this->Html->link('<i class="fa fa-times"></i>',
				        ['action' => 'index'],
				        ['class'=>'btn btn-box-tool','title'=>'cerrar','escape'=>false]);
			        ?>
		        </div>
            </div>                    
            <?= $this->Form->create($carrera, [
                'role' => 'form', 
                'align' => [
                    'sm' => ['left' => 6, 'middle' => 6, 'right' => 12],
                    'md' => ['left' => 2,'middle' => 9,'right' => 1]
                ],
                'class' => 'horizontal']); 
            ?>
            <div class="box-body">
                <?php
                    echo $this->Form->control('codigo', ['type' => 'text', 'Label' => 'Código',
                        'class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('nombre', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('mension_carrera_id', ['type' => 'select', 'options' => $mensionCarreras, 'empty' => true, 
                        'label' => 'Mensión Carrera', 'class' => 'form-control select2', 'data-width' => '100%',
                        'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('titulo_otorgado', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);                    
                    echo $this->Form->control('sedes._ids', ['prepend' => '<i class="fa fa-asterisk"></i>','options' => $sedes]);
                    echo $this->Form->control('activa', ['type' => 'checkbox', 'value' => 1, 'checked' => true]); 
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
