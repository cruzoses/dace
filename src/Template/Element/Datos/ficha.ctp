<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Estudiante $estudiante
*/
?>
<div class="col-md-12">

    <?php if( isset($showOptions) && $showOptions ) : ?>
        <div class="box no-shadow no-border no-bg">
            <table>
                <tr>
                    <td class="pad"><strong>Estudiante</strong></td>
                    <td>
                        <?= $this->Html->link('Datos',
                            ['action' => 'estudiante',$estudiante->id],
                            ['id' => 'btnDatos', 'class' => 'btn btn-info btn-sm menuh btnTools', 'role' => 'button']); 
                        ?>
                        <?= $this->Html->link('Programas',
                            ['action' => 'programas',$estudiante->id],
                            ['id' => 'btnProgramas','class' => 'btn bg-maroon btn-sm menuh btnTools','title' => 'Programas Asignados','role' => 'button']); 
                        ?>
                        <?= $this->Html->link('Histórico',
                            ['action' => 'historico',$estudiante->id],
                            ['class' => 'btn bg-olive btn-sm menuh btnTools','title' => 'Histórico de Notas','role' => 'button', 'id' => 'btnHistorico']); 
                        ?>
                        <?= $this->Html->link('Notas de Lapso',
                            ['action' => 'evaluaciones',$estudiante->id],
                            ['id' => 'btnNotas','class' => 'btn btn-warning btn-sm menuh btnTools','title' => 'Notas de Lapso','role' => 'button']); 
                        ?>
                        <?= $this->Html->link('Situación',/*'#'*/ 
                            ['action' => 'situacion',$estudiante->id],
                            ['id' => 'btnSituacion','class' => 'btn bg-navy btn-sm menuh btnTools','title' => 'Situación Académica','role' => 'button']); 
                        ?>
                        <?php 
                            if( isset($historep) && $historep  ){
                                echo $this->Html->link('<i class="fa fa-print"></i>&nbsp;Imprimir Hostórico',
                                ['controller' => 'reportes','action' => 'historialacademico', $estudiante->id],
                                ['id' => 'btnPrintHistorico','class' => 'btn bg-orange btn-sm menuh btnTools','escape' => false]);
                            }
                        ?>
                        <?= $this->Html->link('Inscripciones',
                            ['controller' => 'EstudianteCursos', 'action' => 'index',$estudiante->id],
                            ['id' => 'btnInscripcion','class' => 'btn btn-primary btn-sm menuh btnTools','title' => 'Inscripciones','role' => 'button']); 
                        ?>
                    </td>
                </tr>
            </table>
        </div>
    <?php endif; ?>

    <div class="box box-primary">

        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-eye"></i>&nbsp;Datos del Estudiante</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
                <?= $this->Html->link('<i class="fas fa-sign-in-alt"></i>',
                    ['controller' => 'datos', 'action' => 'index'],
                    ['class' => 'btn btn-box-tool', 'title' => 'cerrar', 'escape' => false]);
                ?>
            </div>
        </div>
        <div class="box-body no-padding">
            <table class="table table-bordered table-condensed text-center">
                <tr>
                    <th class="bg-gray">Expediente</th>
                    <th class="bg-gray">Documento Identidad</th>
                    <th width="30%" class="bg-gray">Nombre</th>
                    <th rowspan="4" style="width:95px" class="avatar no-padding">
                        <?= $this->Html->image('site/usuario.jpg',['class'=>'avatar img-responsive', 'alt'=> 'Foto']) ?>
                    </th>
                </tr>
                <tr>
                    <td><?= $estudiante->expediente_formateado ?? $estudiante->expediente ?></td>
                    <td><?= $this->Number->format($estudiante->cedula);?></td>
                    <td><?= $estudiante->full_name;?></td>
                </tr>
                <tr>
                    <th class="bg-gray">Usuario</th>
                    <th class="bg-gray">Correo Electr&oacute;nico</th>
                    <th class="bg-gray">Tel&eacute;fono</th>
                </tr>
                <tr>
                    <td><?= $estudiante->has('usuario') ? $estudiante->usuario->username : '' ?></td>
                    <td><?= $estudiante->email ?></td>
                    <td><?= $estudiante->telefonos ?></td>
                </tr>
            </table>
        </div>
        <div class="box-footer">
            <div id="resultado" class="oculto">
                <?= $this->Html->image('site/loading.gif');?>
            </div>
            <?= $this->Html->link('<i class="far fa-address-card"></i>&nbsp;Generar Historico',
                ['action' => 'rendimiento', '?' => ['EstudianteId' => $estudiante->id]],
                ['class' => 'btn bg-navy pull-left','escape' => false])
            ?>
            <?= $this->Html->link('<i class="far fa-list-alt"></i>&nbsp;Actualizar Situación',
                ['action' => 'actualizarsituacion', $estudiante->id],
                ['class' => 'btn bg-maroon pull-right','escape' => false])
            ?>
        </div>

    </div>

    <div class="text-center oculto" id="Cargando">
        <?= $this->Html->image('site/load.gif');?>
    </div>

    <div class="progress progress-striped oculto" id="Indicador">
        <div class="progress-bar progress-bar-success" role="progressbar" style="width:100%">100%</div>
    </div>

</div>