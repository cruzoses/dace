<div class="row">
    <div class="col-xs-12">
        <div class="box box-success box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-lock"></i>&nbsp;Tipos de Usuarios</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="print">
                        <i class="fa fa-print"></i>
                    </button>
                </div>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th scope="col"><?= $this->Paginator->sort('id', 'Código') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('nombre', 'Nombre del Rol') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('activo', 'Estatus') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('created', 'Creado') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rols as $rol): ?>
                        <tr>
                            <td><?= $this->Number->format($rol->id) ?></td>
                            <td><?= h($rol->nombre) ?></td>
                            <td>
                                <?php if ($rol->activo): ?>
                                    <span class="label label-success">Activo</span>
                                <?php else: ?>
                                    <span class="label label-danger">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td><?= h($rol->created) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="no-padding">
                        <tr>
                            <td colspan="4" class="text-center">
                                <small class="text-muted">Total de roles: <?= $rols->count() ?></small>
                            </td>                            
                        </tr>                        
                    </tfoot>
                </table>
            </div>
            <div class="box-footer">                
                <?= $this->Html->link('<i class="fa fa-file-pdf-o"></i>&nbsp;PDF', ['action' => 'tipousuarios_pdf'], ['class' => 'btn btn-danger pull-left', 'escape' => false, 'target' => '_blank']) ?>
                <?= $this->Html->link('<i class="fa fa-times"></i>&nbsp;Cerrar', '/', ['class' => 'btn bg-maroon pull-right', 'escape' => false]) ?>
            </div>
        </div>
    </div>
</div>
