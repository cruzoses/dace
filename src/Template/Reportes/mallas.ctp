<div class="row">
    <div class="col-xs-12">
        <div class="box box-success box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Reporte de Mallas Curriculares</h3>
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
                    <?= $this->Form->control('carrera_id', [
                        'label' => 'Seleccione la Carrera',
                        'type' => 'select',
                        'options' => $carreras,
                        'empty' => '-- Seleccione una Carrera --',
                        'class' => 'form-control select2',
                        'data-width' => '100%',
                    ]) ?>
                    <?= $this->Form->control('programa_id', [
                        'label' => 'Seleccione el Programa (opcional)',
                        'type' => 'select',
                        'options' => [],
                        'empty' => '-- Todos los Programas --',
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
                <?= $this->Html->link('<i class="fa fa-close"></i>&nbsp;Cerrar',
                    '/', ['class' => 'btn bg-maroon', 'escape' => false])
                ?>
            </div>
            <?= $this->Form->end(); ?>
        </div>
    </div>
</div>

<?php $this->start('script'); ?>
<?= $this->Html->script('mallas') ?>
<script>
var MALLAS_PROGRAMAS_URL = '<?= $this->Url->build(['controller' => 'Reportes', 'action' => 'getProgramas']) ?>';
$(document).ready(initMallasReporte);
</script>
<?php $this->end(); ?>
