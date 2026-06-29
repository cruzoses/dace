<div class="row">
	<div class="col-md-12">
		<div class="box box-info box-solid">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-info"></i>&nbsp;Paises</h3>
				<div class="box-tools pull-right">
			        <button type="button" class="btn btn-box-tool" data-widget="collapse">
				        <i class="fa fa-minus"></i>
			        </button>
			        <?= $this->Html->link('<i class="fa fa-close"></i>',
				        ['action' => 'index'],['class'=>'btn btn-box-tool','title'=>'cerrar','escape'=>false]);
			        ?>
		        </div>
        	</div>        
        	<div class="box-body">
          		<dl class="dl-horizontal">
            		<dt scope="row"><?= __('Id') ?></dt>
            		<dd><?= $this->Number->format($pais->id) ?></dd>
            		<dt scope="row"><?= __('Nombre') ?></dt>
            		<dd><?= h($pais->nombre) ?></dd>
            		<dt scope="row"><?= __('Created') ?></dt>
            		<dd><?= h($pais->created->format('d-m-Y g:i a')) ?></dd>
            		<dt scope="row"><?= __('Modified') ?></dt>
            		<dd><?= h($pais->modified->format('d-m-Y g:i a')) ?></dd>
          		</dl>
        	</div>
			<div class="box-footer">
                <?= $this->Html->link('<i class="fa fa-pencil"></i>&nbsp;'.__('Edit'), 
                    ['action' => 'edit',$pais->id], ['class'=>'btn btn-success pull-left','escape' => false]) 
                ?>
                <?= $this->Html->link('<i class="fa fa-close"></i>&nbsp;'.__('Go Back'),
                    ['action' => 'index'], ['class'=>'btn bg-maroon pull-right','escape' => false]) 
                ?>
			</div>
      	</div>
    </div>
</div>