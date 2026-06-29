<div class="content">
    <div class="box box-warning box-solid">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Buscar</h3>
		    <div class="box-tools pull-right">
			    <?= $this->Html->link('<i class="fa fa-close"></i>',
				    ['action' => 'index'],
				    ['class'=>'btn btn-box-tool','title'=>'cerrar','escape'=>false]);
			    ?>
		    </div>
        </div>                    
        <?= $this->Form->create(null, ['role' => 'form', 
            'align' => [
                'sm' => ['left' => 6, 'middle' => 6, 'right' => 12],
                'md' => ['left' => 2,'middle' => 9,'right' => 1]
            ],
            'class' => 'horizontal']); 
        ?>
        <div class="box-body">
            <fieldset>
                <?php
                ?>
            </fieldset>
        </div>            
        <div class="box-footer text-center">
            <?= $this->Form->button('<i class="fa fa-search"></i>&nbsp;Buscar',['type' => 'submit',
                'class' => 'btn btn-success btn-sm','escape' => false]); 
            ?>
            <?= $this->Form->button('<i class="fa fa-eraser"></i>&nbsp;Limpiar',['type' => 'reset',
                'class' => 'btn btn-danger btn-sm','escape' => false]); 
            ?>
        </div>
        <?= $this->Form->end(); ?>
    </div>
</div>
