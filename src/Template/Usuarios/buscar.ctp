<div class="box box-warning box-solid">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-user"></i>&nbsp;Buscar Usuario</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse">
				<i class="fa fa-minus"></i>
			</button>
            <?= $this->Html->link('<i class="fa fa-sign-out"></i>',
                ['controller' => 'bienvenidos','action' => 'display'],
                ['class' => 'btn btn-box-tool','escape' => false])
            ?>
		</div>
    </div>
    <?= $this->Form->create(null,['role' => 'form',
        'align' => [
            'sm' => ['left' => 6,'middle' => 6,'right' => 12],
            'md' => ['left' => 2,'middle' => 9,'right' => 1]
        ],
        'class' => 'horizontal']); 
    ?>
    <div class="box-body">
        <?= $this->Form->control('cedula',['label' => 'Cédula','class' => 'isNumber',
            'prepend' => '<i class ="fa fa-hand-o-right"></i>']);
        ?>
        <?= $this->Form->control('nombres',['class' => 'isUpper',
            'prepend' => '<i class ="fa fa-hand-o-right"></i>']);
        ?>
        <?= $this->Form->control('apellidos',['class' => 'isUpper',
            'prepend' => '<i class ="fa fa-hand-o-right"></i>']);
        ?>
    </div>
    <div class="box-footer text-center">
        <?= $this->Form->button('<i class="fa fa-search"></i>&nbsp;Buscar',[
            'type' => 'submit','class' => 'btn btn-success']);
        ?>
        <?= $this->Form->button('<i class="fa fa-eraser"></i>&nbsp;Limpiar',[
            'type' => 'reset', 'id' => 'btnReset','class' => 'btn btn-danger']);
        ?>
    </div>
    <?= $this->Form->end(); ?>
</div>
