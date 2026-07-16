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
    'align' => [
        'sm' => ['left' => 5, 'middle' => 7, 'right' => 12]
    ]
]) ?>
<?= $this->Form->hidden('id', ['value' => $situacionEstudiante->id]) ?>
<?= $this->Form->hidden('tipo_calificacion', ['value' => $tipoCalificacion]) ?>

<div class="form-group">
    <label class="col-sm-5 control-label">Asignatura</label>
    <div class="col-sm-7">
        <p class="form-control-static">
            <?= h($situacionEstudiante->asignatura->codigo) ?> - <?= h($situacionEstudiante->asignatura->nombre) ?>
            <?php if ($tipoCalificacion == 1): ?>
                <span class="label label-info">CUALITATIVA</span>
            <?php else: ?>
                <span class="label label-default">CUANTITATIVA</span>
            <?php endif; ?>
        </p>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-5 control-label">Calificación <span class="text-danger">*</span></label>
    <div class="col-sm-7">
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
</div>

<div class="form-group">
    <label class="col-sm-5 control-label">Sección</label>
    <div class="col-sm-7">
        <?= $this->Form->control('seccion', [
            'label' => false,
            'class' => 'form-control',
            'value' => $situacionEstudiante->seccion,
            'placeholder' => 'Ej: A, B, Única',
            'required' => true,
            'div' => false
        ]) ?>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-5 control-label">Período <span class="text-danger">*</span></label>
    <div class="col-sm-7">
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
</div>

<div class="form-group">
    <label class="col-sm-5 control-label">Responsable</label>
    <div class="col-sm-7">
        <p class="form-control-static">
            <?= h($userAlias) ?>
        </p>
    </div>
</div>

<div class="box-footer" style="margin-top: 15px;">
    <?= $this->Form->button('<i class="fa fa-save"></i>&nbsp;Guardar',
        ['type' => 'submit', 'class' => 'btn btn-success btn-flat pull-left', 'escape' => false]) ?>
    <button type="button" class="btn btn-danger btn-flat pull-right" data-dismiss="modal">
        <i class="fa fa-times"></i>&nbsp;Cancelar
    </button>
</div>
<?= $this->Form->end() ?>
