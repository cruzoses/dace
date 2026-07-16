<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Estudiante $estudiante
 * @var array $aOrigen
 * @var array $aGenero
 * @var array $aEdoCivil
 * @var array $paises
 * @var array $estados
 * @var array $municipios
 * @var array $parroquias
 * @var array $aPeriodos
 * @var array $aCarreras
 * @var array $aSedes
*/
?>
<div class="row">
    <div class="col-md-12">    
        <div class="box box-purple box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Registrar Estudiante</h3>
		        <div class="box-tools pull-right">
			        <?= $this->Html->link('<i class="fa fa-times"></i>',
				        ['action' => 'index'],
				        ['class'=>'btn btn-box-tool','title'=>'cerrar','escape'=>false]);
			        ?>
		        </div>
            </div>                    
            <?= $this->Form->create($estudiante, [
                'role' => 'form', 
                'align' => [
                    'sm' => ['left' => 6, 'middle' => 6, 'right' => 12],
                    'md' => ['left' => 2,'middle' => 9,'right' => 1]
                ],
                'class' => 'horizontal']); 
            ?>
            <div class="box-body">
                <?php
                    echo $this->Form->control('origen', ['type' => 'select', 'options' => $aOrigen, 'empty' => true,
                        'class' => 'form-control select2', 'data-width' => '100%', 'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('cedula', ['type' => 'text', 'label' => 'No. de Cédula',
                        'placeholder' => 'No. de Cédula', 
                        'class' => 'isNumeric','prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('nombres', ['class' => 'isUpper', 'placeholder' => 'Nombres del Estudiante',
                        'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('apellidos', ['class' => 'isUpper', 'placeholder' => 'Apellidos del Estudiante',
                        'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('fecha_nacimiento', ['type' => 'text', 'class' => 'datepicker',
                        'placeholder' => 'Formato dd-mm-yyyy',
                        'prepend' => '<i class="fa fa-asterisk"></i>', 'append' => '<i class="fa fa-calendar"></i>']
                    );
                    echo $this->Form->control('sexo', ['type' => 'select', 'options' => $aGenero, 'empty' => true,
                        'class' => 'form-control select2', 'data-width' => '100%', 'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('estado_civil', ['type' => 'select', 'options' => $aEdoCivil, 'empty' => true,
                        'class' => 'form-control select2', 'data-width' => '100%', 'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('discapacitado', ['type' => 'select', 'options' => array(0 => 'NO', 1 => 'SI'), 'empty' => true,
                        'class' => 'form-control select2', 'data-width' => '100%', 'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('etnia', ['type' => 'select', 'options' => array(0 => 'NO', 1 => 'SI'), 'empty' => true,
                        'class' => 'form-control select2', 'data-width' => '100%', 'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                ?>
                <h4 class="box-sub-title-info">Datos de Ubicaci&oacute;n</h4>
                <?php
                    echo $this->Form->control('lugar_nacimiento', ['rows' => 1,
                        'placeholder' => 'Lugar de nacimiento',
                        'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('pais_id', ['type' => 'select', 'options' => $paises, 'empty' => true,
                        'class' => 'form-control select2', 'data-width' => '100%', 'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('estado_id', ['type' => 'select', 'options' => $estados, 'empty' => true,
                        'class' => 'form-control select2', 'data-width' => '100%', 'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('municipio_id', ['type' => 'select', 'options' => $municipios, 'empty' => true,
                        'class' => 'form-control select2', 'data-width' => '100%', 'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('parroquia_id', ['type' => 'select', 'options' => $parroquias, 'empty' => true,
                        'class' => 'form-control select2', 'data-width' => '100%', 'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('direccion', ['rows' => 2,'class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('telefonos', ['type' => 'tel',  'pattern' => '[0-9]{3}-[0-9]{2}-[0-9]{3}',
                        'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('email', ['class' => 'isLower','prepend' => '<i class="fa fa-asterisk"></i>']);
                ?>
                <h4 class="box-sub-title-info">Informaci&oacute;n Acad&eacute;mica</h4>
                <?php
                    echo $this->Form->control('asignado', ['label' => 'Asignación OPSU', 'type' => 'select',
                        'options' => array(0 => 'NO', 1 => 'SI'), 'empty' => true,
                        'class' => 'form-control select2', 'data-width' => '100%', 'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('codigo_opsu', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('fecha_notas', ['type' => 'text', 'class' => 'datepicker',
                        'prepend' => '<i class="fa fa-asterisk"></i>', 'append' => '<i class="fa fa-calendar"></i>']
                    );
                    echo $this->Form->control('codigo_notas', ['label' => 'Código de Notas','class' => 'isUpper',
                        'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('fecha_titulo', ['type' => 'text', 'class' => 'datepicker',
                        'prepend' => '<i class="fa fa-asterisk"></i>', 'append' => '<i class="fa fa-calendar"></i>']
                    );
                    echo $this->Form->control('codigo_titulo', ['label' => 'Código de Título', 'class' => 'isUpper',
                        'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                ?>
                <h4 class="box-sub-title-info">Oferta Acad&eacute;mica</h4>
                <?php
                    echo $this->Form->control('periodo', ['label' => 'Perído Inicial',
                        'type' => 'select', 'options' => $aPeriodos, 'empty' => true, 'required' => true,
                        'class' => 'form-control select2', 'data-width' => '100%', 'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('carrera', ['type' => 'select', 'options' => $aCarreras, 'empty' => true, 'required' => true,
                        'class' => 'form-control select2', 'data-width' => '100%', 'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->control('sede', ['type' => 'select', 'options' => $aSedes, 'empty' => true, 'required' => true,
                        'class' => 'form-control select2', 'data-width' => '100%', 'prepend' => '<i class="fa fa-asterisk"></i>']
                    );
                    echo $this->Form->hidden('expediente');
                    echo $this->Form->hidden('token');
                    echo $this->Form->hidden('usuario_id');
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
<?= $this->Html->script('estudiantes') ?>
<script>
var ESTUDIANTES_ESTADOS_URL = '<?= $this->Url->build(['controller' => 'Estudiantes', 'action' => 'getEstados']) ?>';
var ESTUDIANTES_MUNICIPIOS_URL = '<?= $this->Url->build(['controller' => 'Estudiantes', 'action' => 'getMunicipios']) ?>';
var ESTUDIANTES_PARROQUIAS_URL = '<?= $this->Url->build(['controller' => 'Estudiantes', 'action' => 'getParroquias']) ?>';

var promises = [];
var initialPais = $('#pais-id').val();
if (initialPais) {
    promises.push(cargarEstados(initialPais, null));
}

$.when.apply($, promises).done(function () {
    $('#pais-id').on('change', function () {
        cargarEstados($(this).val(), null);
    });
    $('#pais-id').on('select2:select', function () {
        cargarEstados($(this).val(), null);
    });

    $('#estado-id').on('change', function () {
        cargarMunicipios($(this).val(), null);
    });
    $('#estado-id').on('select2:select', function () {
        cargarMunicipios($(this).val(), null);
    });

    $('#municipio-id').on('change', function () {
        cargarParroquias($(this).val(), null);
    });
    $('#municipio-id').on('select2:select', function () {
        cargarParroquias($(this).val(), null);
    });
});
</script>
<?php $this->end(); ?>
