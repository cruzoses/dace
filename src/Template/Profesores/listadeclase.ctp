<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">
            <i class="fa fa-book"></i>&nbsp;LISTA DE CLASE&nbsp;<b><?= $oCurso->periodo->codename ?></b>
        </h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse">
				<i class="fa fa-minus"></i>
			</button>
			<?= $this->Html->link('<i class="fas fa-sign-out-alt"></i>',
				['action' => 'index', $oCurso->id],
				['class' => 'btn btn-box-tool', 'title' => 'cerrar', 'escape' => false]);
			?>
        </div>
    </div>
    <div class="box-body table-responsive">
        <table class="table table-bordered table-condensed">
            <tr>
                <th class="bg-gray text-center">Asignatura Id</th>
                <th class="bg-gray text-center">C&oacute;digo</th>
                <th class="bg-gray text-center">Cr&eacute;ditos</th>
                <th class="bg-gray text-center">Asignatura</th>
                <th class="bg-gray text-center">Secci&oacute;n</th>
            </tr>
            <tr>
                <td class="text-center"><?= $oCurso->asignatura->id ?></td>
                <td class="text-center"><?= $oCurso->asignatura->codigo ?></td>
                <td class="text-center"><?= $oCurso->asignatura->creditos ?></td>
                <td class="text-center"><?= $oCurso->asignatura->nombre ?></td>
                <td class="text-center"><?= $oCurso->seccion ?></td>
            </tr>
            <tr>
                <th class="bg-gray text-left" colspan="4">Profesor</th>
                <th class="bg-gray text-center" colspan="1">Asignaci&oacute;n</th>
            </tr>
            <tr>
                <td class="text-left" colspan="4"><?= $oCurso->has('docente') ? $oCurso->docente->codename : ''?> </td>
                <td class="text-center"><?= $this->Number->format($oCurso->id) ?></td>
            </tr>
        </table>
    </div>
</div>

<?php if( count($aEstudiantes) > 0) : ?>

    <div class="box no-shadow no-border no-bg">
        <?= $this->Html->link('<i class="fa fa-calculator"></i>&nbsp;Indicadores',
            ['controller' => 'IndicadorCursos', 'action' => 'index',$oCurso->id],
            ['class' => 'btn btn-sm bg-maroon','escape' => false]);
        ?>&nbsp;
        <?= $this->Html->link('<i class="fa fa-cog"></i>&nbsp;Plan de Evaluación',
            ['controller' => 'ContenidoCursos', 'action' => 'index',$oCurso->id],
            ['class' => 'btn btn-sm bg-olive','escape' => false]);
        ?>&nbsp;
        <?= $this->Html->link('<i class="far fa-newspaper"></i>&nbsp;Acta de Notas',
            ['#'],
            //['controller' => 'profesores', 'action' => 'index',$oCurso->id],
            ['id' => 'cargaNotas', 'class' => 'btn btn-sm bg-navy','escape' => false]);
        ?>&nbsp;        
        <?php /*echo $this->Html->link('<i class="fa fa-newspaper-o"></i>&nbsp;Acta de Notas',
            array('action' => 'actadenotas',$aCurso['Curso']['id'],$aDocente['Docente']['id']),
            array('class' => 'btn btn-sm bg-orange','escape' => false));*/
        ?>&nbsp;
        <?php echo $this->Html->link('<i class="fa fa-list"></i>&nbsp;Cursos Asignados',
            ['controller' => 'profesores', 'action' => 'index',$oCurso->id],
            ['class' => 'btn btn-sm btn-primary','escape' => false]);
        ?>&nbsp;
    </div>    
    <div class="oculto text-center" id="procesando">
        <?= $this->Html->image('site/load.gif')?>
    </div>
    <div class="box box-primary">
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover table-bordered table-condensed table-striped">
                <thead>
                    <tr>
                        <td class="table-sub-title" colspan="7">
                            <i class="fa fa-user"></i>&nbsp;Participantes&nbsp;
                            <div class="box-tools pull-right">
                            <?= $this->Html->link('<i class="fa fa-print"></i>&nbsp;Imprimir',
                                ['controller' => 'Reportes', 'action' => 'listarParticipantes', $oCurso->id],
                                ['class' => 'btn btn-default btn-sm', 'escape' => false, 'title' => 'Imprimir Lista'])
                            ?>&nbsp;&nbsp;
                            <?= $this->Html->link('<i class="fas fa-file-excel"></i>&nbsp;Exportar',
                                ['controller' => 'Archivos', 'action' => 'exportarParticipantes', $oCurso->id],
                                ['class' => 'btn btn-default btn-sm', 'escape' => false, 'title' => 'Exportar Lista'])
                            ?>
                        </div>
                </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-gray text-center" style colspan="1">No.</th>                        
                        <th class="bg-gray text-center" style colspan="1">C&eacute;dula</th>
                        <th class="bg-gray text-center" style colspan="1">Datos del Estudiante </th>
                        <th class="bg-gray text-center" style colspan="1">Expediente</th>
                        <th class="bg-gray text-center" style colspan="1">Inscrito Por</th>
                        <th class="bg-gray text-center" style colspan="1">Fecha</th>
                        <th class="bg-gray text-center" style colspan="1">Activo</th>
                    </tr>
                </thead>
                <?php $nIdx = 1 ?>
                <tbody>
                    <?php foreach ($aEstudiantes as $key) : ?>
                        <tr>
                            <td class="text-center" style colspan="1">
                                <?php echo $nIdx;?>
                            </td>
                            <td class=" text-center" style colspan="1">
                                <?= $this->Number->format($key->estudiante->cedula);?>
                            </td>
                            <td style colspan="1">
                                <?= h($key->estudiante->full_name) ?>
                                <?php if( !empty( $key->estudiante->email) ) : ?>
                                    <br><b>Correo:</b>
                                    <a href="mailto:<?= $key->estudiante->email?>"><?= $key->estudiante->email?></a>
                                    &nbsp;<b>Tel:</b>&nbsp;<?= $key->estudiante->telefonos ?>
                                <?php endif; ?>
                            </td>
                            <td style colspan="1">
                                <?= $this->Number->format($key->estudiante->expediente);?>
                            </td>
                            <td class="text-center" style colspan="1">
                                <?= h($key->responsable)?>
                            </td>
                            <td class="text-center" style colspan="1">
                                <?= $key->created ?>
                            </td>
                            <td class="text-center" style colspan="1">
                                <?= h($key->activo) ? 'SI' : 'NO' ?>
                            </td>
                        </tr>
                        <?php $nIdx++; ?>
                    <?php endforeach; ?>
                </tbody>
                    <tfoot class="no-padding">
                        <tr>
                            <td colspan="7" class="text-center">
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
            
        </div>

    </div>
<?php else : ?>
    <div class="callout callout-info">
        <i class="fa fa-info-circle"></i>&nbsp;<strong>No tiene estudiantes registrados</strong>
    </div>
<?php endif; ?>

<script>
	$(document).ready(function () {
		$('#modulo').text("Docente > Lista de Clase");
        $('#cargaNotas').click(function (e) {
            e.preventDefault();
            let nCurso = "<?= $oCurso->id ?>";
            let sUrl = basePath + "notasCursos/grilla/" + nCurso;
            $('#procesando').show();
            window.location.replace(sUrl);
        });        
	});

</script>