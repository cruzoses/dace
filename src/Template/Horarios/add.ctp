<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Horario $horario
 */
?>
<div class="row">
    <div class="col-md-12">    
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Horarios</h3>
		        <div class="box-tools pull-right">
			        <?= $this->Html->link('<i class="fa fa-close"></i>',
				        ['action' => 'index'],
				        ['class'=>'btn btn-box-tool','title'=>'cerrar','escape'=>false]);
			        ?>
		        </div>
            </div>                    
            <?= $this->Form->create($horario, [
                'role' => 'form', 
                'align' => [
                    'sm' => ['left' => 6, 'middle' => 6, 'right' => 12],
                    'md' => ['left' => 2,'middle' => 9,'right' => 1]
                ],
                'class' => 'horizontal']); 
            ?>
            <div class="box-body">
                <?php
                    echo $this->Form->control('sede_id', ['type' => 'select', 'options' => $sedes, 'empty' => true,
                        'class' => 'form-control select2', 'data-widh' => '100%','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('periodo_id', ['type' => 'select', 'options' => $periodos, 'empty' => true,
                        'class' => 'form-control select2', 'data-widh' => '100%','prepend' => '<i class="fa fa-calendar"></i>']
                    );
                    echo $this->Form->control('dia', ['type' => 'select', 'options' => $aDias, 'empty' => true,
                        'class' => 'form-control select2', 'data-widh' => '100%','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('turno', ['type' => 'select', 'options' => $aTurnos, 'empty' => true,
                        'class' => 'form-control select2', 'data-widh' => '100%','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('desde', ['type' => 'text', 'empty' => true,
                        'class' => 'form-control timepicker','prepend' => '<i class="fa fa-clock-o"></i>']
                    );
                    echo $this->Form->control('hasta', ['type' => 'text', 'empty' => true,
                        'class' => 'form-control timepicker','prepend' => '<i class="fa fa-clock-o"></i>']
                    );
                    echo $this->Form->control('codigo', ['label' => 'Código',
                        'class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>', 'readonly' => true]
                    );
                    echo $this->Form->hidden('activo', ['type' => 'checkbox', 'value' => '1']);
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
<script>
var aDias = <?= json_encode($aDias) ?>;
var aTurnos = <?= json_encode($aTurnos) ?>;
</script>
<?= $this->Html->script('horarios') ?>
