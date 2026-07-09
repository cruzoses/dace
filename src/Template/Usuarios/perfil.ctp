<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Usuario $usuario
 * @var array $aGeneros
 * @var array $rols
*/
?>
<?= $this->Html->css('fileinput/css/fileinput.min', ['block' => true]); ?>
<div class="row">
    <div class="col-md-12">    
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-user"></i>&nbsp;Mi Perfil</h3>
		        <div class="box-tools pull-right">
			        <button type="button" class="btn btn-box-tool" data-widget="collapse">
				        <i class="fa fa-minus"></i>
			        </button>
			        <?= $this->Html->link('<i class="fa fa-times"></i>',
				        ['action' => 'homepage'],
				        ['class'=>'btn btn-box-tool','title'=>'cerrar','escape'=>false]);
			        ?>
		        </div>
            </div>                    
            <?= $this->Form->create($usuario, [
                'type' => 'file',
                'role' => 'form',
                'align' => [
                    'sm' => ['left' => 6, 'middle' => 6, 'right' => 12],
                    'md' => ['left' => 2,'middle' => 9,'right' => 1]
                ],
                'class' => 'horizontal']); 
            ?>
            <div class="box-body">
                <div class="callout callout-info">
                    <i class="fa fa-info-circle"></i>&nbsp;Usuario: <strong><?= h($usuario->alias) ?></strong>
                </div>

                <div class="row">
                    <div class="col-sm-4 text-center">
                        <?php $fotoUrl = !empty($usuario->foto) ? $this->Url->image('fotos/' . $usuario->foto, ['fullBase' => true]) : ''; ?>
                        <?= $this->Form->control('foto', [
                            'label' => 'Foto de perfil',
                            'type' => 'file',
                            'id' => 'foto',
                        ]); ?>
                    </div>
                    <div class="col-sm-8">
                        <fieldset>
                            <legend>Redes Sociales</legend>
                            <?= $this->Form->control('twitter', [
                                'label' => 'Twitter',
                                'type' => 'text',
                                'class' => 'form-control',
                                'placeholder' => '@usuario',
                                'prepend' => '<i class="fa fa-twitter"></i>',
                            ]); ?>
                            <?= $this->Form->control('instagram', [
                                'label' => 'Instagram',
                                'type' => 'text',
                                'class' => 'form-control',
                                'placeholder' => '@usuario',
                                'prepend' => '<i class="fa fa-instagram"></i>',
                            ]); ?>
                            <?= $this->Form->control('facebook', [
                                'label' => 'Facebook',
                                'type' => 'text',
                                'class' => 'form-control',
                                'placeholder' => 'https://facebook.com/usuario',
                                'prepend' => '<i class="fa fa-facebook"></i>',
                            ]); ?>
                        </fieldset>
                    </div>
                </div>
            </div>            
            <div class="box-footer">
		        <?= $this->Form->button('<i class="fa fa-save"></i>&nbsp;Guardar',
			        ['type' => 'submit','class'=>'btn btn-success btn-flat pull-left','escape'=>false]); 
		        ?>
		        <?= $this->Html->link('<i class="fa fa-power-off"></i>&nbsp;Cerrar',
			        ['action' => 'homepage'],['class' => 'btn bg-maroon btn-flat pull-right','escape' => false]); 
		        ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<?= $this->Html->script('fileinput/js/fileinput.min', ['block' => true]); ?>
<?php $this->append('scriptBottom'); ?>
<script>
$(document).ready(function() {
    $('#foto').fileinput({
        showUpload: false,
        showCaption: true,
        showRemove: true,
        showPreview: true,
        browseLabel: 'Seleccionar',
        removeLabel: 'Eliminar',
        msgPlaceholder: 'Seleccione una foto...',
        allowedFileExtensions: ['jpg', 'jpeg', 'png', 'gif'],
        maxFileSize: 1,
        <?php if (!empty($usuario->foto)): ?>
        initialPreview: ['<?= $this->Url->image('fotos/' . $usuario->foto, ['fullBase' => true]) ?>'],
        initialPreviewAsData: true,
        initialPreviewConfig: [{type: 'image', caption: '<?= $usuario->foto ?>'}],
        overwriteInitial: true,
        <?php endif; ?>
    });
});
</script>
<?php $this->end(); ?>
