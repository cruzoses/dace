<?php use Cake\Core\Configure; ?>

<?= $this->Html->css('login'); ?>

<div class="row">

    <div class="col-md-3 text-center hidden-xs">
        <?= $this->Html->image('logos/logouptbal.png',['class' => 'img-responsive center-block','width' => '256','height' => '256','style' => 'margin-top: 40px']);?>
        <h3><strong><?= Configure::read('Sistema.Siglas');?></strong></h3>
    </div>

    <div class="col-xs-12 col-sm-8 col-md-6">
            <div class="row"><?= $this->Flash->render(); ?></div>
            <div class="text-center">
                <span class="visible-xs">
                    <h3><strong><?= Configure::read('Sistema.Siglas')?></strong></h3>
                </span>
                <span><h3><strong>INGRESO AL SISTEMA</strong></h3></span>
            </div>

            <?= $this->Form->create('Usuario',['url' => ['controller' => 'usuarios', 'action' => 'login']]);?>

			<fieldset>

				<hr class="colorgraph">
				<div class="form-group">
                    <?= $this->Form->input('username',['label' => false, 'type' => 'text', 'div' => false, 
                        'class' => 'form-control input-lg','placeholder' => 'Usuario' ]); 
                    ?>
				</div>

				<div class="form-group">
                    <?= $this->Form->input('password',['label' => false, 'type' => 'password', 'div' => false, 
                        'class' => 'form-control input-lg','placeholder' => 'Contraseña']);
                    ?>
				</div>

				<span class="button-checkbox">
					<button type="button" class="btn" data-color="info">&nbsp;Recordarme</button>
                    <input type="checkbox" name="remember_me" id="remember_me" checked="checked" class="hidden">
					<a href="<?= $this->Url->build('/nuevaclave')?>" class="btn btn-link pull-right">Olvid&oacute; su contrase&ntilde;a?</a>
				</span>

				<hr class="colorgraph">
				<div class="row">
					<div class="col-xs-6 col-sm-6 col-md-6">
						<?= $this->Form->button('<i class ="fa fa-lock"></i>&nbsp;Ingresar',
							['type' =>'submit','class' => 'btn btn-lg btn-success btn-block','escape' => false]);
						?>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6">
						<?= $this->Html->link('<i class ="fa fa-home"></i>&nbsp;Regresar',
                            $this->Url->build('/'),
                            ['class' => 'btn btn-lg btn-danger btn-block','escape' => false]);
                        ?>						    
					</div>
				</div>

			</fieldset>

        <?php echo $this->Form->end(); ?>

	</div>

    <div class="col-md-3 text-center hidden-xs">
        <?= $this->Html->image('logos/logouptbal.png',['class' => 'img-responsive center-block','width' => '256','height' => '256','style' => 'margin-top: 40px']);?>
        <h3><strong><?= Configure::read('Sistema.Siglas'); ?></strong></h3>
    </div>

</div>

<?= $this->Html->script('login'); ?>

<script>
    $(document).ready(function () {
        $('#username').focus();
    });
</script>
