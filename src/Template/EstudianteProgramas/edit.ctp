<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\EstudiantePrograma $estudiantePrograma
 * @var array $sedes
 * @var array $carreras
 * @var array $periodos
 * @var Object $estudiante
*/
?>
<div class="box box-warning box-solid">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Modificar Programa - <?= h($estudiante->cedula) ?> <?= h($estudiante->apellidos) ?> <?= h($estudiante->nombres) ?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-dismiss="modal">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>
    <?= $this->Form->create($estudiantePrograma, [
        'id' => 'edit-programa-form',
        'role' => 'form',
        'align' => [
            'sm' => ['left' => 6, 'middle' => 6, 'right' => 12],
            'md' => ['left' => 2,'middle' => 9,'right' => 1]
        ],
        'class' => 'horizontal']);
    ?>
    <div class="box-body">
        <?php
            echo $this->Form->hidden('estudiante_id');
            echo $this->Form->control('sede_id', ['prepend' => '<i class="fa fa-asterisk"></i>','class' => 'isUpper','options' => $sedes]);
            echo $this->Form->control('periodo_id', [
                'label' => 'Período de Inscripción',
                'type' => 'select',
                'options' => $periodos,
                'empty' => '-- Seleccione --',
                'class' => 'form-control select2',
                'data-width' => '100%',
                'prepend' => '<i class="fa fa-asterisk"></i>',
            ]);
            echo $this->Form->control('carrera_id', [
                'label' => 'Carrera',
                'type' => 'select',
                'options' => $carreras,
                'empty' => '-- Seleccione --',
                'class' => 'form-control select2',
                'data-width' => '100%',
                'prepend' => '<i class="fa fa-asterisk"></i>',
                'default' => $estudiantePrograma->carrera_id,
            ]);
            echo $this->Form->control('programa_id', [
                'type' => 'select',
                'options' => [],
                'empty' => '-- Seleccione --',
                'class' => 'form-control select2',
                'data-width' => '100%',
                'prepend' => '<i class="fa fa-asterisk"></i>',
                'disabled' => true,
            ]);
            echo $this->Form->control('fecha_egreso', ['type' => 'text','class' => 'calendario','prepend' => '<i class="fa fa-asterisk"></i>','empty' => true]);
            echo $this->Form->control('cohorte', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
            echo $this->Form->control('isa', ['class' => 'isDouble','prepend' => '<i class="fa fa-asterisk"></i>']);
            echo $this->Form->control('ira', ['class' => 'isDouble','prepend' => '<i class="fa fa-asterisk"></i>']);
            echo $this->Form->hidden('culminado', ['value' => 0]);
            echo $this->Form->hidden('congelado', ['value' => 0]);
            echo $this->Form->hidden('activo', ['value' => 1]);
        ?>
    </div>
    <div class="box-footer">
        <?= $this->Form->button('<i class="fa fa-save"></i>&nbsp;Guardar',
            ['type' => 'submit','class'=>'btn btn-success btn-flat pull-left','escape'=>false]);
        ?>
        <button type="button" class="btn bg-maroon btn-flat pull-right" data-dismiss="modal">
            <i class="fa fa-power-off"></i>&nbsp;Cerrar
        </button>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
<script>
$(document).ready(function() {
    var carreraId = <?= json_encode($estudiantePrograma->carrera_id) ?>;
    var programaId = <?= json_encode($estudiantePrograma->programa_id) ?>;

    function cargarProgramas() {
        if (carreraId) {
            $('#carrera-id').val(carreraId);
            var $programa = $('#programa-id');
            $.ajax({
                url: '<?= $this->Url->build(['controller' => 'EstudianteProgramas', 'action' => 'getProgramasByCarrera']) ?>',
                type: 'GET',
                data: { carrera_id: carreraId },
                dataType: 'json',
                beforeSend: function() {
                    $programa.empty().append('<option value="">Cargando...</option>').prop('disabled', true);
                },
                success: function(response) {
                    $programa.empty().append('<option value="">-- Seleccione --</option>');
                    $.each(response.programas, function(key, value) {
                        $programa.append('<option value="' + key + '">' + value + '</option>');
                    });
                    $programa.prop('disabled', false);
                    if (programaId) {
                        $programa.val(programaId);
                    }
                },
                error: function() {
                    $programa.empty().append('<option value="">-- Error al cargar --</option>').prop('disabled', true);
                }
            });
        }
    }

    cargarProgramas();

    $('#carrera-id').on('change', function() {
        carreraId = $(this).val();
        var $programa = $('#programa-id');
        if (carreraId) {
            $.ajax({
                url: '<?= $this->Url->build(['controller' => 'EstudianteProgramas', 'action' => 'getProgramasByCarrera']) ?>',
                type: 'GET',
                data: { carrera_id: carreraId },
                dataType: 'json',
                beforeSend: function() {
                    $programa.empty().append('<option value="">Cargando...</option>').prop('disabled', true);
                },
                success: function(response) {
                    $programa.empty().append('<option value="">-- Seleccione --</option>');
                    $.each(response.programas, function(key, value) {
                        $programa.append('<option value="' + key + '">' + value + '</option>');
                    });
                    $programa.prop('disabled', false);
                },
                error: function() {
                    $programa.empty().append('<option value="">-- Error al cargar --</option>').prop('disabled', true);
                }
            });
        } else {
            $programa.empty().append('<option value="">-- Seleccione una carrera primero --</option>').prop('disabled', true);
        }
    });
});
</script>