<div class="row">
    <div class="col-xs-12">
        <div class="box box-success box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-building"></i>&nbsp;Reporte de Aulas</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <?= $this->Form->create(null, ['type' => 'get',
                'role' => 'form',
                'align' => [
                    'sm' => ['left' => 6, 'middle' => 6, 'right' => 12],
                    'md' => ['left' => 3, 'middle' => 7, 'right' => 2]
                ],
                'class' => 'horizontal']);
            ?>
            <div class="box-body">
                <fieldset>
                    <?= $this->Form->control('sede_id', [
                        'label' => 'Seleccione la Sede',
                        'type' => 'select',
                        'options' => $sedes,
                        'empty' => '-- Todas las Sedes --',
                        'class' => 'form-control select2',
                        'data-width' => '100%',
                    ]) ?>
                </fieldset>
            </div>
            <div class="box-footer text-center">
                <?= $this->Form->button('<i class="fa fa-file-pdf-o"></i>&nbsp;Generar Reporte', [
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'escape' => false
                ]) ?>
                <?= $this->Html->link('<i class="fa fa-times"></i>&nbsp;Cerrar',
                    '/', ['class' => 'btn bg-maroon', 'escape' => false])
                ?>
            </div>
            <?= $this->Form->end(); ?>
        </div>
    </div>
</div>
