<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Periodo $periodo
 */
?>
<div class="row">
    <div class="col-md-12">    
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Registrar Periodo</h3>
		        <div class="box-tools pull-right">
			        <?= $this->Html->link('<i class="fa fa-times"></i>',
				        ['action' => 'index'],
				        ['class'=>'btn btn-box-tool','title'=>'cerrar','escape'=>false]);
			        ?>
		        </div>
            </div>                    
            <?= $this->Form->create($periodo, [
                'role' => 'form', 
                'align' => [
                    'sm' => ['left' => 6, 'middle' => 6, 'right' => 12],
                    'md' => ['left' => 2,'middle' => 9,'right' => 1]
                ],
                'class' => 'horizontal']); 
            ?>
            <div class="box-body">
                <?php
                    echo $this->Form->control('codigo', ['type' => 'text','label' => 'Código',
                        'class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('nombre', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('lapso', ['type' => 'text', 'label' => 'Año',
                        'class' => 'isNumeric yearlist', 'prepend' => '<i class="fa fa-calendar"></i>']
                    );
                    echo $this->Form->control('nota_minima', ['type' => 'text', 'label' => 'Nota Mínima',
                        'class' => 'isNumeric','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('inicio', ['label' => 'Fecha de Inicio', 'type' => 'text',
                        'class' => 'calendario','prepend' => '<i class="fa fa-calendar"></i>']
                    );
                    echo $this->Form->control('cierre', ['type' => 'text', 'label' => 'Fecha de Cierre',
                        'class' => 'calendario','prepend' => '<i class="fa fa-calendar"></i>']
                    );
                    echo $this->Form->hidden('califica', ['type' => 'checkbox','value' => 1]);
                    echo $this->Form->hidden('activo', ['type' => 'checkbox','value' => 1]);
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
