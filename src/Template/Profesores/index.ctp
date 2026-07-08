<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Docente $docente
 * @var array $periodos
 * @var int $periodoId
 * @var array $cursos
 */
?>
<div class="content">
    <div class="row">
        <?= $this->element('Profesores/ficha',['docente' => $docente, 'showOptions' => false]); ?>
    </div>
    <div class="margin box no-shadow no-border no-bg" role="group">
        <strong>Períodos</strong>
        <?php $nPeriodo = 0 ?>
        <?php $totalPeriodos = count($periodos) ?>
        <?php $inlineLimit = 3 ?>
        <?php foreach ($periodos as $id => $codigo): ?>
            <?php $nPeriodo++ ?>
            <?php if( $nPeriodo <= $inlineLimit ) : ?>
            <?= $this->Html->link(h($codigo),
                ['action' => 'index', '?' => ['periodo_id' => $id]],
                ['class' => 'btn btn-sm ' . ($id == $periodoId ? 'btn-primary' : 'btn-default')])
            ?>
            <?php elseif( $nPeriodo == ($inlineLimit + 1) ) : ?>
                <div class="btn-group">
                    <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown">
                        Más períodos <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><?= $this->Html->link(h($codigo), ['action' => 'index', '?' => ['periodo_id' => $id]]) ?></li>
            <?php else : ?>
                        <li><?= $this->Html->link(h($codigo), ['action' => 'index', '?' => ['periodo_id' => $id]]) ?></li>
            <?php endif; ?>
        <?php endforeach; ?>
        <?php if( $totalPeriodos > $inlineLimit ) : ?>
                    </ul>
                </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-dashboard"></i>&nbsp;Panel del Docente</h3>
                    <div class="box-tools pull-right">
                        <?= $this->Html->link('<i class="fa fa-user"></i>&nbsp;' . h($docente->full_name),
                            ['action' => 'profesor', $docente->id],
                            ['class' => 'btn btn-info btn-sm', 'escape' => false, 'title' => 'Ver perfil'])
                        ?>
                    </div>
                </div>
                <div class="box-body">
                    <?php if (!empty($cursos)): ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-condensed">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th class="text-center">Asignación</th>
                                                <th>Período</th>
                                                <th>Carrera</th>
                                                <th>Asignatura</th>
                                                <th>Nombre de la Asignatura</th>
                                                <th class="text-center">Créditos</th>
                                                <th class="text-center">Sección</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $contador = 0; ?>
                                            <?php foreach ($cursos as $curso): ?>
                                            <?php $contador++; ?>
                                            <tr>
                                                <td class="text-center"><?= $contador ?></td>
                                                <td class="text-center"><?= $curso->id ?></td>
                                                <td><?= h($curso->periodo->codigo ?? '') ?></td>
                                                <td><?= h($curso->carrera->codigo ?? '') ?></td>
                                                <td><?= h($curso->asignatura->codigo ?? '') ?></td>
                                                <td><?= h($curso->asignatura->nombre ?? '') ?></td>
                                                <td class="text-center"><?= $curso->asignatura->creditos ?? '' ?></td>
                                                <td class="text-center">
                                                    <?= $this->Html->link(h($curso->seccion),
                                                        '#',
                                                        ['class' => 'btn btn-xs btn-default', 'escape' => false])
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center">
                            <i class="fa fa-info-circle"></i>&nbsp;No hay cursos asignados para este período.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>