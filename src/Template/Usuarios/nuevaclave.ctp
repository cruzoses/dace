<?php use Cake\Core\Configure; ?>

<?= $this->Html->css('login'); ?>

<div class="row">

    <div class="col-md-3 text-center hidden-xs">
        <?= $this->Html->image('logos/logouptbal.png',['class' => 'img-responsive center-block','width' => '256','height' => '256','style' => 'margin-top: 40px']);?>
        <h3><strong><?= Configure::read('Sistema.Siglas');?></strong></h3>
    </div>

    <div class="col-xs-12 col-sm-8 col-md-6">

            <div class="text-center">
                <span class="visible-xs">
                    <h3><strong><?= Configure::read('Sistema.Siglas')?></strong></h3>
                </span>
                <span><h3><strong>RECUPERAR CONTRASEÑA</strong></h3></span>
            </div>

            <div class="row"><?= $this->Flash->render('auth'); ?></div>
            <div class="row"><?= $this->Flash->render(); ?></div>

            <?= $this->Form->create(null,['url' => ['controller' => 'usuarios', 'action' => 'nuevaclave']]);?>

            <fieldset>

                <hr class="colorgraph">
                <div class="form-group">
                    <?= $this->Form->input('email',['label' => false, 'type' => 'email', 'div' => false,
                        'class' => 'form-control input-lg','placeholder' => 'Correo electrónico','prepend' => '<i class="fa fa-envelope"></i>',]);
                    ?>
                </div>

                <hr class="colorgraph">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <?= $this->Form->button('<i class ="fa fa-send"></i>&nbsp;Enviar',
                            ['type' =>'submit','class' => 'btn btn-lg btn-success btn-block','escape' => false]);
                        ?>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <?= $this->Html->link('<i class ="fa fa-arrow-left"></i>&nbsp;Volver al login',
                            $this->Url->build(['controller' => 'usuarios', 'action' => 'login']),
                            ['class' => 'btn btn-lg btn-primary btn-block','escape' => false]);
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
        $('#email').focus();
    });
</script>
