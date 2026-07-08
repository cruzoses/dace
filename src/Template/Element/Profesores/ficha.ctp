<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Docente $docente
*/
?>
<div class="col-md-12">

    <?php if( isset($showOptions) && $showOptions ) : ?>
        <div class="box no-shadow no-border no-bg">
            <table>
                <tr>
                    <td class="pad"><strong>Docente</strong></td>
                    <td>
                        <?= $this->Html->link('Datos',
                            ['action' => 'profesor', $docente->id],
                            ['id' => 'btnDatos', 'class' => 'btn btn-info btn-sm menuh btnTools', 'role' => 'button']);
                        ?>
                        <?= $this->Html->link('Cursos',
                            ['action' => 'cursos', $docente->id],
                            ['id' => 'btnCursos', 'class' => 'btn bg-maroon btn-sm menuh btnTools', 'title' => 'Cursos Asignados', 'role' => 'button']);
                        ?>
                    </td>
                </tr>
            </table>
        </div>
    <?php endif; ?>

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-eye"></i>&nbsp;Datos del Docente</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
                <?= $this->Html->link('<i class="fas fa-sign-in-alt"></i>',
                    ['action' => 'homepage'],
                    ['class' => 'btn btn-box-tool', 'title' => 'cerrar', 'escape' => false]);
                ?>
            </div>
        </div>

        <div class="box-body table-responsive no-padding">
            <table class="table table-bordered table-condensed text-center">
                <tr>
                    <th class="bg-gray">Cédula</th>
                    <th class="bg-gray">Nombre</th>
                    <th class="bg-gray">Departamento</th>
                    <th rowspan="4" style="width:95px" class="avatar no-padding">
                        <?= $this->Html->image('site/usuario.jpg', ['class' => 'avatar img-responsive', 'alt' => 'Foto']) ?>
                    </th>
                </tr>
                <tr>
                    <td><?= $this->Number->format($docente->cedula) ?></td>
                    <td><?= h($docente->full_name) ?></td>
                    <td><?= $docente->has('departamento') ? h($docente->departamento->nombre) : '' ?></td>
                </tr>
                <tr>
                    <th class="bg-gray">Correo Electrónico</th>
                    <th class="bg-gray">Teléfono</th>
                    <th class="bg-gray">Usuario</th>
                </tr>
                <tr>
                    <td><?= h($docente->email) ?></td>
                    <td><?= h($docente->telefonos) ?></td>
                    <td><?= $docente->has('usuario') ? h($docente->usuario->username) : '' ?></td>
                </tr>
            </table>
        </div>
    </div>

</div>