<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SituacionEstudiante $situacionEstudiante
 * @var array $periodos
 * @var string $userAlias
 * @var int $tipoCalificacion 0=CUANTITATIVA, 1=CUALITATIVA
*/
?>
<?= $this->Form->create(null, [
    'id' => 'form-calificar',
    'url' => ['controller' => 'SituacionEstudiantes', 'action' => 'guardarCalifica'],
]) ?>
<?= $this->Form->hidden('id', ['value' => $situacionEstudiante->id]) ?>
<?= $this->Form->hidden('tipo_calificacion', ['value' => $tipoCalificacion]) ?>

<div class="form-group">
    <label class="control-label">Asignatura</label>
    <p class="form-control-static">
        <?= h($situacionEstudiante->asignatura->codigo) ?> - <?= h($situacionEstudiante->asignatura->nombre) ?>
        <?php if ($tipoCalificacion == 1): ?>
            <span class="label label-info">CUALITATIVA</span>
        <?php else: ?>
            <span class="label label-default">CUANTITATIVA</span>
        <?php endif; ?>
    </p>
</div>

<div class="form-group">
    <label class="control-label">Calificación <span class="text-danger">*</span></label>
    <?php if ($tipoCalificacion == 1): ?>
        <?= $this->Form->control('calificacion', [
            'type' => 'select',
            'label' => false,
            'class' => 'form-control',
            'options' => [
                'A' => 'A - APROBADO',
                'R' => 'R - REPROBADO'
            ],
            'empty' => '-- Seleccione --',
            'value' => $situacionEstudiante->calificacion,
            'required' => true,
            'div' => false
        ]) ?>
    <?php else: ?>
        <?= $this->Form->control('calificacion', [
            'type' => 'number',
            'label' => false,
            'class' => 'form-control',
            'min' => 1,
            'max' => 20,
            'step' => 0.5,
            'value' => $situacionEstudiante->calificacion,
            'required' => true,
            'placeholder' => '1 - 20',
            'div' => false
        ]) ?>
    <?php endif; ?>
</div>

<div class="form-group">
    <label class="control-label">Sección <span class="text-danger">*</span></label>
    <?= $this->Form->control('seccion', [
        'label' => false,
        'class' => 'form-control',
        'value' => $situacionEstudiante->seccion,
        'placeholder' => 'Ej: A, B, Única',
        'required' => true,
        'div' => false
    ]) ?>
</div>

<div class="form-group">
    <label class="control-label">Período <span class="text-danger">*</span></label>
    <?= $this->Form->control('periodo_id', [
        'type' => 'select',
        'label' => false,
        'options' => $periodos,
        'empty' => '-- Seleccione --',
        'class' => 'form-control select2',
        'data-width' => '100%',
        'value' => $situacionEstudiante->periodo_id,
        'required' => true,
        'div' => false
    ]) ?>
</div>

<div class="form-group">
    <label class="control-label">Responsable</label>
    <p class="form-control-static"><?= h($userAlias) ?></p>
</div>

<div class="box-footer" style="margin-top: 15px;">
    <?= $this->Form->button('<i class="fa fa-save"></i>&nbsp;Guardar',
        ['type' => 'submit', 'class' => 'btn btn-success btn-flat pull-left', 'escape' => false]) ?>
    <button type="button" class="btn btn-danger btn-flat pull-right" data-dismiss="modal">
        <i class="fa fa-times"></i>&nbsp;Cancelar
    </button>
</div>
<?= $this->Form->end() ?>
