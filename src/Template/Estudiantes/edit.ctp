<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Estudiante $estudiante
 * @var array $aPeriodos
 * @var array $aCarreras
 * @var array $aSedes
 */
?>
<div class="row">
    <div class="col-md-12">    
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Estudiantes</h3>
		        <div class="box-tools pull-right">
			        <?= $this->Html->link('<i class="fa fa-close"></i>',
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
                    echo $this->Form->control('origen', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('cedula', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('nombres', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('apellidos', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('fecha_nacimiento', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('sexo', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('estado_civil', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('discapacitado', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('etnia', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('direccion', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('telefonos', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('email', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('lugar_nacimiento', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
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
                    echo $this->Form->control('asignado', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('codigo_opsu', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('fecha_notas', ['type' => 'text','class' => 'calendario','prepend' => '<i class="fa fa-asterisk"></i>','empty' => true]);
                    echo $this->Form->control('codigo_notas', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('fecha_titulo', ['type' => 'text','class' => 'calendario','prepend' => '<i class="fa fa-asterisk"></i>','empty' => true]);
                    echo $this->Form->control('codigo_titulo', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
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
                    echo $this->Form->control('token', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
                    echo $this->Form->control('usuario_id', ['prepend' => '<i class="fa fa-asterisk"></i>','class' => 'isUpper','options' => $usuarios, 'empty' => true]);
                    echo $this->Form->control('activo', ['class' => 'isUpper','prepend' => '<i class="fa fa-asterisk"></i>']);
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
var initialPais = '<?= h($estudiante->pais_id) ?>';
var initialEstado = '<?= h($estudiante->estado_id) ?>';
var initialMunicipio = '<?= h($estudiante->municipio_id) ?>';
var initialParroquia = '<?= h($estudiante->parroquia_id) ?>';

if (initialPais) {
    promises.push(cargarEstados(initialPais, initialEstado));
}
if (initialEstado) {
    promises.push(cargarMunicipios(initialEstado, initialMunicipio));
}
if (initialMunicipio) {
    promises.push(cargarParroquias(initialMunicipio, initialParroquia));
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
