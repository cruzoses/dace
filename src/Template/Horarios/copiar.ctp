<?php
/**
 * @var \App\View\AppView $this
 * @var array $periodos
 * @var array $sedes
*/
?>
<div class="row">
    <div class="col-md-12">    
        <div class="box box-sace box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-copy"></i>&nbsp;Copiar Horarios</h3>
		        <div class="box-tools pull-right">
			        <?= $this->Html->link('<i class="fa fa-times"></i>',
				        ['action' => 'index'],
				        ['class'=>'btn btn-box-tool','title'=>'cerrar','escape'=>false]);
			        ?>
		        </div>
            </div>                    
            <?= $this->Form->create(null, [
                'role' => 'form', 
                'align' => [
                    'sm' => ['left' => 6, 'middle' => 6, 'right' => 12],
                    'md' => ['left' => 2,'middle' => 9,'right' => 1]
                ],
                'class' => 'horizontal']); 
            ?>
            <div class="box-body">
                <div class="callout callout-info">
                    <i class="fa fa-info-circle"></i>&nbsp;
                    Seleccione el periodo de origen (del cual copiar los horarios) y el periodo destino (donde se crearán las copias).
                </div>
                <?php
                    echo $this->Form->control('sede_id', ['type' => 'select', 'options' => $sedes, 'empty' => true,
                        'label' => 'Sede','class' => 'form-control select2', 'data-widh' => '100%', 
                        'prepend' => '<i class="fa fa-building"></i>', 'required' => true]
                    );
                    echo $this->Form->control('origen_id', ['type' => 'select', 'options' => $periodos, 'empty' => true,
                        'label' => 'Periodo Origen', 'class' => 'form-control select2', 'data-widh' => '100%',
                        'prepend' => '<i class="fas fa-history"></i>', 'required' => true]
                    );
                    echo $this->Form->control('destino_id', ['type' => 'select', 'options' => $periodos, 'empty' => true,
                        'label' => 'Periodo Destino', 'class' => 'form-control select2', 'data-widh' => '100%',
                        'prepend' => '<i class="fas fa-clock"></i>', 'required' => true]
                    );
                ?>
            </div>            
            <div class="box-footer">
		        <?= $this->Form->button('<i class="fa fa-copy"></i>&nbsp;Copiar Horarios',
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
