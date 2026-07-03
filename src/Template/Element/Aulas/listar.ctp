<div class="content">
    <div class="box box-warning box-solid">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-building"></i>&nbsp;Reporte de Aulas</h3>
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
        <?= $this->Form->create(null, ['type' => 'get', 'role' => 'form', 'id' => 'reportBuilder',
            'url' => ['controller' => 'Reportes','action' => 'listarAulas'],
            'align' => [
                'sm' => ['left' => 6, 'middle' => 6, 'right' => 12],
                'md' => ['left' => 3, 'middle' => 7, 'right' => 2]
            ],
            'class' => 'horizontal']);
        ?>
        <div class="box-body">
            <fieldset>
                <?= $this->Form->control('sede_id', ['type' => 'select','label' => 'Seleccione la Sede', 'options' => $sedes, 
                    'empty' => true, 'class' => 'form-control select2','data-width' => '100%',]) 
                ?>
            </fieldset>
        </div>
        <div class="box-footer text-center">
            <?= $this->Form->button('<i class="fa fa-file-pdf-o"></i>&nbsp;Generar Reporte', [
                'type' => 'submit', 'class' => 'btn btn-success','escape' => false]) 
            ?>
            <?= $this->Html->link('<i class="fa fa-close"></i>&nbsp;Cerrar',
                ['action' => 'index'], ['class' => 'btn bg-maroon', 'escape' => false])
            ?>
        </div>
        <?= $this->Form->end(); ?>
    </div>
</div>