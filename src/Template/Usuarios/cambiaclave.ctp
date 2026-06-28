<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Usuario $usuario
 */
?>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="box box-primary box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-key"></i>&nbsp;Cambiar Contrase&ntilde;a</h3>
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
            <?= $this->Form->create($usuario, ['role' => 'form']); ?>
            <div class="box-body">
                <div class="callout callout-info">
                    <i class="fa fa-info-circle"></i>&nbsp;Usuario: <strong><?= h($usuario->username) ?></strong>
                </div>
                <?php
                    echo $this->Form->control('password_actual', [
                        'type' => 'password',
                        'label' => 'Contrase&ntilde;a actual',
                        'required' => true,
                    ]);
                    echo $this->Form->control('password_nueva', [
                        'type' => 'password',
                        'label' => 'Contrase&ntilde;a nueva',
                        'required' => true,
                    ]);
                    echo $this->Form->control('password_confirmar', [
                        'type' => 'password',
                        'label' => 'Confirmar contrase&ntilde;a',
                        'required' => true,
                    ]);
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
            <?= $this->Form->end(); ?>
        </div>
    </div>
</div>
