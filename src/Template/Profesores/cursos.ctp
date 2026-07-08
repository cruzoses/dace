<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Cursos Asignados</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool btn-cerrar-ajax">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover table-striped table-condensed">
            <thead>
                <tr>
                    <th>Período</th>
                    <th>Sección</th>
                    <th>Asignatura</th>
                    <th>Carrera</th>
                    <th>Trayecto</th>
                    <th>Sede</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($cursos)): ?>
                    <?php foreach ($cursos as $curso): ?>
                        <tr>
                            <td><?= h($curso->periodo->nombre ?? '') ?></td>
                            <td><?= h($curso->seccion) ?></td>
                            <td><?= h($curso->asignatura->nombre ?? '') ?></td>
                            <td><?= h($curso->carrera->codigo ?? '') ?></td>
                            <td class="text-center"><?= h($curso->trayecto->codigo ?? '') ?></td>
                            <td><?= h($curso->sede->nombre ?? '') ?></td>
                            <td class="text-center">
                                <div class="btn-group btn-group-xs">
                                    <?= $this->Html->link('<i class="fa fa-tasks"></i>',
                                        ['action' => 'indicadores', $curso->id],
                                        ['class' => 'btn btn-warning', 'escape' => false, 'title' => 'Indicadores'])
                                    ?>
                                    <?= $this->Html->link('<i class="fa fa-calendar"></i>',
                                        ['action' => 'planEvaluacion', $curso->id],
                                        ['class' => 'btn bg-olive', 'escape' => false, 'title' => 'Plan de Evaluación'])
                                    ?>
                                    <?= $this->Html->link('<i class="fa fa-edit"></i>',
                                        ['action' => 'cargaNotas', $curso->id],
                                        ['class' => 'btn bg-navy', 'escape' => false, 'title' => 'Carga de Notas'])
                                    ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No hay cursos asignados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>