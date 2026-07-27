<div class="row">
    <div class="col-xs-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-file-pdf-o"></i>&nbsp;ACTA DE NOTAS</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered table-condensed">
                            <tr>
                                <th class="bg-gray" style="width:120px;">Asignatura</th>
                                <td><?= h($oCurso->asignatura->nombre) ?></td>
                            </tr>
                            <tr>
                                <th class="bg-gray">Secci&oacute;n</th>
                                <td><?= h($oCurso->seccion) ?></td>
                            </tr>
                            <tr>
                                <th class="bg-gray">Docente</th>
                                <td><?= $oCurso->has('docente') ? h($oCurso->docente->codename) : '—' ?></td>
                            </tr>
                            <tr>
                                <th class="bg-gray">Periodo</th>
                                <td><?= h($oCurso->periodo->codename) ?></td>
                            </tr>
                            <tr>
                                <th class="bg-gray">Frecuencia</th>
                                <td>
                                    <?php
                                    $aFreq = Configure::read('aFrecuencia');
                                    echo $aFreq[$nFrecuencia] ?? '—';
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div class="box box-default">
                            <div class="box-header with-border">
                                <h3 class="box-title">Seleccionar Proceso a Imprimir</h3>
                            </div>
                            <div class="box-body">
                                <div class="list-group">
                                    <?php foreach ($aProcesos as $sKey => $sLabel) : ?>
                                        <?= $this->Html->link(
                                            '<i class="fa fa-file-pdf-o"></i>&nbsp;&nbsp;' . h($sLabel),
                                            ['action' => 'listarActadeNotas', $nCursoId, '?' => ['proceso' => $sKey]],
                                            ['class' => 'list-group-item', 'escape' => false]
                                        ) ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer text-center">
                <?= $this->Html->link('<i class="fa fa-arrow-left"></i>&nbsp;Volver',
                    ['controller' => 'profesores', 'action' => 'listadeclase', $nCursoId],
                    ['class' => 'btn bg-maroon', 'escape' => false]) ?>
            </div>
        </div>
    </div>
</div>
