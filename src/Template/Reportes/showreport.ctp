<?php if (!empty($noData)): ?>
<?php $this->Html->scriptBlock("
Swal.fire({
    title: 'Sin datos',
    text: 'No hay informaci\u00F3n para mostrar.',
    icon: 'info'
}).then(function() {
    window.location.href = '{$this->request->referer('/')}';
});
", ['block' => 'scriptBottom']); ?>
<?php else: ?>
<div class="box box-warning">
    <div class="box-header with-border">
		<h3 class="box-title"><i class="fa fa-file-pdf-o"></i>&nbsp;Visor de Reporte</h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse" title="Contraer">
				<i class="fa fa-minus"></i>
            </button>
			<?= $this->Html->link('<i class="fa fa-close"></i>',
				['action' => 'homepage'],['class'=>'btn btn-box-tool','title'=>'Cerrar','escape'=>false]);
			?>
        </div>
	</div>
    <div class="box-body">
		<div class="embed-responsive embed-responsive-16by9">
			<iframe class="embed-responsive-item" src="<?php print($sFileName);?>"></iframe>
		</div>
	</div>
    <div class="box-footer text-center">
		<?= $this->Html->link('<i class="fa fa-power-off"></i>&nbsp;Volver',
			['action' => 'homepage'],
			['class' => 'btn bg-maroon btn-flat', 'escape' => false]); 
		?>
    </div>
</div>
<?php endif; ?>
