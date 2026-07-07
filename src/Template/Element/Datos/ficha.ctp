<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Estudiante $estudiante
*/
use Cake\Core\Configure;

?>
<div class="col-md-12">

    <?php if( isset($showOptions) && $showOptions ) : ?>
        <div class="box no-shadow no-border no-bg">
            <table>
                <tr>
                    <td class="pad"><strong>Estudiante</strong></td>
                    <td>
                        <?= $this->Html->link('Datos',
                            array('action' => 'view',$estudiante->id),
                            array('class' => 'btn btn-info btn-sm menuh','role' => 'button')); 
                        ?>
                        <?= $this->Html->link('Histórico',
                            array('action' => 'historico',$estudiante->id),
                            array('class' => 'btn bg-olive btn-sm menuh','title' => 'Histórico de Notas','role' => 'button')); 
                        ?>
                        <?= $this->Html->link('Notas de Lapso',
                            array('action' => 'evaluaciones',$estudiante->id),
                            array('class' => 'btn btn-warning btn-sm menuh','title' => 'Notas de Lapso','role' => 'button')); 
                        ?>
                        <?= $this->Html->link('Programas',
                            array('action' => 'programas',$estudiante->id),
                            array('class' => 'btn bg-maroon btn-sm menuh','title' => 'Notas de Lapso','role' => 'button')); 
                        ?>
                        <?= $this->Html->link('Situación',/*'#'*/ 
                            array('action' => 'situacion',$estudiante->id),
                            array('class' => 'btn bg-navy btn-sm menuh','title' => 'Situación Académica',
                            'role' => 'button','id'=>'laSituacion')); 
                        ?>
                        <?php 
                            if( isset($historep) && $historep  ){
                                echo $this->Html->link('<i class="fa fa-print"></i>&nbsp;Imprimir Hostórico',
                                array(
                                    'controller' => 'reportes',
                                    'action' => 'historialacademico', $estudiante->id
                                ),
                                array('class' => 'btn bg-orange btn-sm menuh','escape' => false));
                            }
                        ?>
                        <?= $this->Html->link('Inscripciones',
                            array( 'controller' => 'estudiante_cursos', 'action' => 'index',$estudiante->id),
                            array('class' => 'btn btn-primary btn-sm menuh','title' => 'Inscripciones','role' => 'button')); 
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
            <?= $this->Html->link('Generar Historico',
                ['action' => 'rendimiento', '?' => ['EstudianteId' => $estudiante->id]],
                ['class' => 'btn bg-navy','escape' => false])
            ?>
            <div id="resultado" class="oculto">
                <?= $this->Html->image('site/loading.gif');?>
            </div>
        </div>

    </div>

    <div class="text-center oculto" id="Cargando">
        <?= $this->Html->image('site/load.gif');?>
    </div>

    <div class="progress progress-striped oculto" id="Indicador">
        <div class="progress-bar progress-bar-success" role="progressbar" style="width:100%">100%</div>
    </div>

</div>