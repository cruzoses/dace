<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\EstudiantePrograma $estudiantePrograma
 * @var array $sedes
 * @var array $carreras
 * @var Object $estudiante
*/
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Registrar Programa - <?= h($estudiante->cedula) ?> <?= h($estudiante->apellidos) ?> <?= h($estudiante->nombres) ?></h3>
		        <div class="box-tools pull-right">
			        <?= $this->Html->link('<i class="fa fa-close"></i>',
				        ['controller' => 'estudiantes','action' => 'index'],
				        ['class'=>'btn btn-box-tool','title'=>'cerrar','escape'=>false]);
			        ?>
		        </div>
            </div>
            <?= $this->Form->create($estudiantePrograma, [
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
                    echo $this->Form->control('carrera_id', [
                        'label' => 'Carrera',
                        'type' => 'select',
                        'options' => $carreras,
                        'empty' => '-- Seleccione --',
                        'class' => 'form-control select2',
                        'data-width' => '100%',
                        'prepend' => '<i class="fa fa-asterisk"></i>',
                    ]);
                    echo $this->Form->control('programa_id', [
                        'type' => 'select',
                        'options' => [],
                        'empty' => '-- Seleccione una carrera primero --',                        
                        'class' => 'form-control select2',
                        'data-width' => '100%',                        
                        'prepend' => '<i class="fa fa-asterisk"></i>',
                        'disabled' => true,
                    ]);
                    echo $this->Form->hidden('culminado', ['value' => 0]);
                    echo $this->Form->hidden('activo', ['value' => 1]);
                ?>
            </div>
            <div class="box-footer">
		        <?= $this->Form->button('<i class="fa fa-save"></i>&nbsp;Guardar',
			        ['type' => 'submit','class'=>'btn btn-success btn-flat pull-left','escape'=>false]);
		        ?>
		        <?= $this->Html->link('<i class="fa fa-power-off"></i>&nbsp;Cerrar',
			        ['controller' => 'Estudiantes', 'action' => 'view', $estudiante->id],
			        ['class' => 'btn bg-maroon btn-flat pull-right','escape' => false]);
		        ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<?php $this->Html->scriptStart(['block' => 'scriptBottom']); ?>
$(document).ready(function() {
    $('#carrera-id').on('change', function() {
        var carreraId = $(this).val();
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
<?php $this->Html->scriptEnd(); ?>
