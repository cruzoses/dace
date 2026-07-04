<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Curso $curso
 */
?>
<div class="row">
    <div class="col-md-12">    
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Editar Curso</h3>
		        <div class="box-tools pull-right">
			        <?= $this->Html->link('<i class="fa fa-close"></i>',
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
                    echo $this->Form->control('programa_id', ['type' => 'select', 'options' => $programas, 'empty' => true,
                        'class' => 'form-control select2', 'data-width' => '100%','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('trayecto_id', ['type' => 'select', 'options' => $trayectos, 'empty' => true,
                        'class' => 'form-control select2', 'data-width' => '100%','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
					echo $this->Form->control('asignatura_id', ['type' => 'select', 'options' => $asignaturas, 'empty' => true,
                        'class' => 'form-control select2', 'data-width' => '100%', 'prepend' => '<i class="fa fa-asterisk"></i>',
                        'value' => $curso->asignatura_id]
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
                        'class' => 'form-control select2 multiValue', 'data-width' => '100%',
                        'multiple' => 'multiple', 'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('activo', ['type' => 'checkbox', 'label' => 'Curso Activo']);
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
var CURSOS_HORARIO_ACTUAL = '<?= h($curso->horario) ?>';
var CURSOS_ASIGNATURA_ACTUAL = '<?= h($curso->asignatura_id) ?>';
$(document).ready(function() {
    initCursos();
    if (CURSOS_ASIGNATURA_ACTUAL) {
        $('#asignatura-id').val(CURSOS_ASIGNATURA_ACTUAL).trigger('change');
    }
});
</script>
<?php $this->end(); ?>
