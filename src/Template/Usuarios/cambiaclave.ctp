<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Usuario $usuario
 */
?>
<div class="row">
    <div class="col-md-12">    
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-key"></i>&nbsp;Cambiar Contrase&ntilde;a</h3>
		        <div class="box-tools pull-right">
			        <button type="button" class="btn btn-box-tool" data-widget="collapse">
				        <i class="fa fa-minus"></i>
			        </button>
			        <?= $this->Html->link('<i class="fa fa-close"></i>',
				        ['action' => 'homepage'],
				        ['class'=>'btn btn-box-tool','title'=>'cerrar','escape'=>false]);
			        ?>
		        </div>
            </div>                    
            <?= $this->Form->create($usuario, [
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
                <?php
                    echo $this->Form->control('password_actual', [
                        'type' => 'password',
                        'label' => 'Contraseña actual',
                        'prepend' => '<i class="fa fa-key"></i>',
                        'required' => true,                        
                    ]);
                    echo $this->Form->control('password_nueva', [
                        'type' => 'password',
                        'label' => 'Contraseña nueva',
                        'prepend' => '<i class="fa fa-asterisk"></i>',
                        'required' => true,
                    ]);
                    echo $this->Form->control('password_confirmar', [
                        'type' => 'password',
                        'label' => 'Confirmar contraseña',
                        'prepend' => '<i class="fa fa-asterisk"></i>',
                        'required' => true,
                    ]);
                ?>
                <hr>
                <div class="dace-captcha-wrapper text-center">
                    <?= $this->Captcha->render([
                        'captcha_id' => $captchaId,
                        'input_text' => __('Retype the characters from the picture:'),
                        'input_attributes' => ['class' => 'form-control', 'placeholder' => ''],
                        'image_attributes' => ['style' => 'display:inline;vertical-align:middle;'],
                    ]) ?>
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
