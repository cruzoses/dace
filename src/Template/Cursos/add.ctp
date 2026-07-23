<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Curso $curso
 */
?>
<div class="row">

    <div class="col-md-12">    
        <div class="box box-purple box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Registrar Curso</h3>
		        <div class="box-tools pull-right">
			        <?= $this->Html->link('<i class="fa fa-times"></i>',
				        ['action' => 'index'],
				        ['class'=>'btn btn-box-tool','title'=>'cerrar','escape'=>false]);
			        ?>
		        </div>
            </div>                    
            <?= $this->Form->create($curso, [
                'role' => 'form', 
                'align' => [
                    'sm' => ['left' => 6, 'middle' => 6, 'right' => 12],
                    'md' => ['left' => 2,'middle' => 9,'right' => 1]
                ],
                'class' => 'horizontal']); 
            ?>
            <div class="box-body">
                <?php
                    echo $this->Form->control('sede_id', ['type' => 'select', 'options' => $sedes, 'empty' => true,
                        'class' => 'form-control select2', 'data-width' => '100%','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('periodo_id', ['type' => 'select', 'options' => $periodos, 'empty' => true,
                        'class' => 'form-control select2', 'data-width' => '100%','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('carrera_id', ['type' => 'select', 'options' => $carreras, 'empty' => true,
                        'class' => 'form-control select2', 'data-width' => '100%','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->hidden('programas', ['id' => 'programas-input']);
                    echo '<div class="form-group"><label class="control-label col-sm-6 col-md-2">Programas</label><div class="col-sm-6 col-md-9"><div id="programas-checkbox"></div></div></div>';
                    echo $this->Form->control('trayecto_id', ['type' => 'select', 'options' => $trayectos, 'empty' => true,
                        'class' => 'form-control select2', 'data-width' => '100%','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
					echo $this->Form->control('asignatura_id', ['type' => 'select', 'options' => $asignaturas, 'empty' => true,
                        'class' => 'form-control select2', 'data-width' => '100%', 'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('profesores', ['type' => 'select', 'options' => $profesores, 'empty' => true,
                        'label' => 'Docentes Asignados', 'class' => 'form-control select2 multiValue', 'data-width' => '100%',
                        'multiple' => 'multiple', 'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('docente_id', ['type' => 'select', 'options' => $docentes, 'empty' => true,
                        'class' => 'form-control select2', 'data-width' => '100%','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('seccion', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('cupos', ['type' => 'text',
                        'class' => 'isNumeric','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('aula_id', ['type' => 'select', 'options' => $aulas, 'empty' => true, 
                    'class' => 'form-control select2', 'data-width' => '100%','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('horario', ['type' => 'select', 'options' => $horarios, 'empty' => true,
                        'label' => 'Horario Asignado', 'class' => 'form-control select2 multiValue', 'data-width' => '100%',
                        'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->hidden('cerrado', ['type' => 'checkbox', 'value' => 1, 'checked' => true]);
                    echo $this->Form->hidden('activo', ['type' => 'checkbox', 'value' => 1, 'checked' => true]);
                ?>
            </div>            
            <div class="box-footer">
		        <?= $this->Form->button('<i class="fa fa-save"></i>&nbsp;Guardar',
			        ['type' => 'submit','class'=>'btn btn-success btn-flat pull-left','escape'=>false]); 
		        ?>
		        <?= $this->Html->link('<i class="fa fa-power-off"></i>&nbsp;Cerrar',
			        ['action' => 'index'],['class' => 'btn bg-maroon btn-flat pull-right','escape' => false]); 
		        ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
	
</div>

<?php $this->start('script'); ?>
<?= $this->Html->script('cursos') ?>
<script>
var CURSOS_PROGRAMAS_URL = '<?= $this->Url->build(['controller' => 'Cursos', 'action' => 'getProgramas']) ?>';
var CURSOS_ASIGNATURAS_URL = '<?= $this->Url->build(['controller' => 'Cursos', 'action' => 'getAsignaturas']) ?>';
var CURSOS_HORARIOS_URL = '<?= $this->Url->build(['controller' => 'Cursos', 'action' => 'getHorarios']) ?>';
var CURSOS_AULAS_URL = '<?= $this->Url->build(['controller' => 'Cursos', 'action' => 'getAulas']) ?>';
var CURSOS_PROGRAMAS_ACTUAL = '<?= h($curso->programas) ?>';

initCursos();

window.cargarProgramas = function (carreraId, selectedValues) {
    if (!carreraId) {
        $('#programas-checkbox').empty();
        return;
    }
    $.ajax({
        url: CURSOS_PROGRAMAS_URL,
        type: 'GET',
        data: { carrera_id: carreraId },
        dataType: 'json',
        beforeSend: function () {
            $('#programas-checkbox').empty().html('<span>Cargando...</span>');
        }
    }).done(function (response) {
        var $container = $('#programas-checkbox');
        $container.empty();
        var selected = selectedValues || [];
        $.each(response.programas, function (value, text) {
            var checked = selected.indexOf(String(value)) !== -1;
            $container.append(
                $('<div>').addClass('checkbox').append(
                    $('<label>').append(
                        $('<input>').attr({
                            type: 'checkbox',
                            value: value
                        }).prop('checked', checked),
                        ' ' + text
                    )
                )
            );
        });
    }).fail(function (jqXHR, textStatus, errorThrown) {
        $('#programas-checkbox').empty().html('<span>Error al cargar programas: ' + textStatus + '</span>');
    });
};

var initialCarrera = $('#carrera-id').val();
var initialProgramas = CURSOS_PROGRAMAS_ACTUAL ? CURSOS_PROGRAMAS_ACTUAL.split(' ') : [];
if (initialCarrera && initialProgramas.length) {
    window.cargarProgramas(initialCarrera, initialProgramas);
}

window._onCarreraChange = function () {
    window.cargarProgramas($(this).val());
};
$('#carrera-id').on('change', window._onCarreraChange);
$('#carrera-id').on('select2:select', window._onCarreraChange);

$('form').on('submit', function () {
    var checked = [];
    $('#programas-checkbox input:checked').each(function () {
        checked.push($(this).val());
    });
    $('#programas-input').val(checked.join(' '));
});
</script>
<?php $this->end(); ?>
