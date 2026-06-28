<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Sede $sede
 */
?>
<div class="row">
    <div class="col-md-12">    
        <div class="box box-primary box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Editar Sede</h3>
		        <div class="box-tools pull-right">
			        <button type="button" class="btn btn-box-tool" data-widget="collapse">
				        <i class="fa fa-minus"></i>
			        </button>
			        <?= $this->Html->link('<i class="fa fa-close"></i>',
				        ['action' => 'index'],
				        ['class'=>'btn btn-box-tool','title'=>'cerrar','escape'=>false]);
			        ?>
		        </div>
            </div>                    
            <?= $this->Form->create($sede, [
                'role' => 'form', 
                'align' => [
                    'sm' => ['left' => 6, 'middle' => 6, 'right' => 12],
                    'md' => ['left' => 2,'middle' => 9,'right' => 1]
                ],
                'class' => 'horizontal']); 
            ?>
            <div class="box-body">
                <?php
                    echo $this->Form->control('codigo', [
                        'label' => 'Código','class' => 'isUpper', 'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('nombre', ['class' => 'isUpper', 'prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('direccion', [
                        'label' => 'Dirección','rows' => 2, 'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('telefonos', [
                        'label' => 'Teléfonos','prepend' => '<i class="fa fa-phone"></i>']
                    );
                    echo $this->Form->control('responsable', ['class' => 'isUpper', 'prepend' => '<i class="fa fa-user"></i>']);
                    echo $this->Form->control('carreras._ids', ['prepend' => '<i class="fa fa-asterisk"></i>','options' => $carreras]);
                    echo $this->Form->control('principal', ['label' => 'Sede Principal']);
                    echo $this->Form->control('activa', ['prepend' => '<i class="fa fa-asterisk"></i>']);
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
