<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Asignatura $estudiante
*/
use Cake\Core\Configure;
?>
<div class="content">
    <div class="row">
        <?= $this->element('Datos/ficha',['estudiante' => $estudiante, 'showOptions' => true]); ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-bordered table-condensed table-striped">
                        <tr>
                            <td class="table-sub-title" colspan="2">Datos Personales del Estudiante</td>
                        </tr>
                        <tr>
                            <th class="bg-gray  text-center col-lg-3" style colspan="1">Dato</th>
                            <th class="bg-gray  text-center" style colspan="1">Informaci&oacute;n</th>
                        </tr>
                        <tr>
                            <td class=" text-left" style colspan="1">No. de Id</td>
                            <td class=" text-left" style colspan="1"><?= $this->Number->format($estudiante->id);?></td>
                        </tr>
                        <tr>
                            <td class=" text-left" style colspan="1">N&uacute;mero de Expediente</td>
                            <td class=" text-left" style colspan="1"><?= $estudiante->expediente_formateado ?? $estudiante->expediente ?></td>
                        </tr>
                        <tr>
                            <td class=" text-left" style colspan="1">N&uacute;mero de C&eacute;dula </td>
                            <td class=" text-left" style colspan="1"><?= $this->Number->format($estudiante->cedula);?></td>
                        </tr>
                        <tr>
                            <td class=" text-left" style colspan="1">Apellidos</td>
                            <td class=" text-left" style colspan="1"><?= $estudiante->apellidos;?></td>
                        </tr>
                        <tr>
                            <td class=" text-left" style colspan="1">Nombres</td>
                            <td class=" text-left" style colspan="1"><?= $estudiante->nombres;?></td>
                        </tr>
                        <tr>
                            <td class=" text-left" style colspan="1">Sexo</td>
                            <td class=" text-left" style colspan="1"><?= Configure::read('aGeneros')[$estudiante->sexo];?></td>
                        </tr>
                        <tr>
                            <td class=" text-left" style colspan="1">Fecha Nacimiento</td>
                            <td class=" text-left" style colspan="1"><?= $estudiante->fecha_nacimiento;?></td>
                        </tr>
                        <tr>
                            <td class=" text-left" style colspan="1">Discapacitado</td>
                            <td class=" text-left" style colspan="1"><?= $estudiante->discapacitado ? 'SI' : 'NO';?></td>
                        </tr>
                        <tr>
                            <td class=" text-left" style colspan="1">Tel&eacute;fono</td>
                            <td class=" text-left" style colspan="1"><?= $estudiante->telefonos;?></td>
                        </tr>
                        <tr>
                            <td class=" text-left" style colspan="1">Correo Electr&oacute;nico</td>
                            <td class=" text-left" style colspan="1"><?= $estudiante->email;?></td>
                        </tr>
                        <tr>
                            <td class=" text-left" style colspan="1">Direcci&oacute;n</td>
                            <td class=" text-left" style colspan="1"><?= $estudiante->direccion;?></td>
                        </tr>
                    </table>
                </div>
                <div class="box-footer"></div>
            </div>
        </div>
    </div>
</div>