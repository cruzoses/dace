<?php 
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Rol $rol
 * @var \Cake\Collection\CollectionInterface|string[] $usuarios
 * @var int $nTotalUsuarios
 * @var string $sClass 
 */
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-sace box-solid">
            <div class="box-header with-border">
                <i class="fa fa-info"></i>
                <h3 class="box-title">Tipo de Usuario</h3>
		        <div class="box-tools pull-right">
			        <button type="button" class="btn btn-box-tool" data-widget="collapse">
				        <i class="fa fa-minus"></i>
			        </button>
			        <?= $this->Html->link('<i class="fa fa-times"></i>',
				        ['action' => 'index'],
				        ['class'=>'btn btn-box-tool','title'=>'cerrar','escape'=>false]);
			        ?>
		        </div>
            </div>
            <div class="box-body table-responsive">
                <dl class="dl-horizontal">
                    <dt scope="row"><?= __('No. de Id') ?></dt>
                    <dd><?= $this->Number->format($rol->id) ?></dd>
                    <dt scope="row"><?= __('Nombre') ?></dt>
                    <dd><?= h($rol->nombre) ?></dd>
                    <dt scope="row"><?= __('Created') ?></dt>
                    <dd><?= h($rol->created) ?></dd>
                    <dt scope="row"><?= __('Modified') ?></dt>
                    <dd><?= h($rol->modified) ?></dd>
                    <dt scope="row"><?= __('Activo') ?></dt>
                    <dd><?= $rol->activo ? __('Yes') : __('No'); ?></dd>
                </dl>
            </div>
            <div class="box-footer"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-user"></i>
                <h3 class="box-title">Usuarios Registrados
                    <small class="label label-primary"><?= $this->Number->format($nTotalUsuarios) ?></small>
                </h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <?php if (!empty($usuarios)): ?>
                    <table class="table table-hover">
                        <tr>
                            <th scope="col"><?= __('Id') ?></th>
                            <th scope="col"><?= __('Cédula') ?></th>
                            <th scope="col"><?= __('Nombres') ?></th>
                            <th scope="col"><?= __('Apellidos') ?></th>
                            <th scope="col" class="text-center"><?= __('Fecha Nacimiento') ?></th>
                            <th scope="col" class="text-center"><?= __('Sexo') ?></th>
                            <th scope="col" class="text-center"><?= __('Activo') ?></th>
                            <th scope="col" class="text-center"><?= __('Created') ?></th>
                            <th scope="col" class="text-center"><?= __('Modified') ?></th>
                        </tr>
                        <?php foreach ($usuarios as $usuario): ?>
                            <?php $sClass = ($usuario->activo) ? 'label-success' : 'label-danger'; ?>
                            <tr>
                                <td><?= $this->Number->format($usuario->id) ?></td>
                                <td><?= $this->Number->format($usuario->cedula) ?></td>
                                <td><?= h($usuario->nombres) ?></td>
                                <td><?= h($usuario->apellidos) ?></td>
                                <td class="text-center"><?= h($usuario->fecha_nacimiento) ?></td>
                                <td class="text-center"><?= h($usuario->sexo) ?></td>
                                <td class="text-center">
                                    <span class="label <?= $sClass ?>">
                                        <?= h($usuario->activo)  ? 'SI' : 'NO'  ?>
                                    </span>
                                </td>
                                <td class="text-center"><?= h($usuario->created) ?></td>
                                <td class="text-center"><?= h($usuario->modified) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tfoot class="no-padding">
                            <tr>
                                <td colspan="9" class="text-center">
                                    <div class="paginator">
                                        <ul class="pagination pagination-sm">
                                            <?= $this->Paginator->first('<i class="fa fa-angle-double-left"></i>',['class' => 'btn btn-sm','escape' => false]) ?>
                                            <?= $this->Paginator->prev('<i class="fa fa-angle-left"></i>',['class' => 'btn btn-sm','escape' => false]) ?>
                                            <?= $this->Paginator->numbers(['before' => '','after' => '']) ?>
                                            <?= $this->Paginator->next('<i class="fa fa-angle-right"></i>',['class' => 'btn btn-sm','escape' => false]) ?>
                                            <?= $this->Paginator->last('<i class="fa fa-angle-double-right"></i>',['class' => 'btn btn-sm','escape' => false]) ?>
                                        </ul>
                                        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                <?php else: ?>
                    <p class="text-center text-muted">No hay usuarios asociados a este rol.</p>
                <?php endif; ?>
            </div>
            <div class="box-footer"></div>
        </div>
    </div>
</div>
