<div class="row">
    <div class="col-md-12">
        <div class="box box-purple box-solid">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Actas de Grado</h3>
                <div class="box-tools pull-right">
			        <button type="button" class="btn btn-box-tool" data-widget="collapse">
				        <i class="fa fa-minus"></i>
			        </button>
                    <?= $this->Html->link('<i class="fa fa-times"></i>',
                        ['controller' => 'Actos', 'action' => 'homepage'], ['class'=>'btn btn-box-tool','escape' => false]) 
                    ?>
                </div>
            </div>
            <?= $this->Form->create(null, ['role' => 'form', 
                'id' => 'formActaGrado',
                'align' => [
                    'sm' => ['left' => 6, 'middle' => 6, 'right' => 12],
                    'md' => ['left' => 2,'middle' => 9,'right' => 1]
                ],
                'class' => 'horizontal']); 
            ?>
            <div class="box-body">
                <fieldset>
                    <?php 
                        echo $this->Form->control('promocion', [
                            'label' => 'Promoción',
                            'type' => 'select',
                            'options' => $aPromociones,
                            'empty' => true,
                            'value' => !empty($filtros['promocion']) ? $filtros['promocion'] : '',
                            'class' => 'form-control select2',
                            'prepend' => '<i class="fa fa-asterisk"></i>',
                            'required' => true
                        ]);
                        echo $this->Form->control('institucion', [
                            'label' => 'Institución',
                            'type' => 'select',
                            'options' => $aInstituciones,
                            'empty' => true,
                            'value' => !empty($filtros['institucion']) ? $filtros['institucion'] : '',
                            'class' => 'form-control select2',
                            'prepend' => '<i class="fa fa-asterisk"></i>',
                            'required' => true
                        ]);
                        echo $this->Form->control('carrera', [
                            'label' => 'Carrera',
                            'type' => 'select',
                            'options' => [],
                            'empty' => '-- Todas --',
                            'value' => !empty($filtros['carrera']) ? $filtros['carrera'] : '',
                            'class' => 'form-control select2',
                            'prepend' => '<i class="fa fa-asterisk"></i>'
                        ]);
                        echo $this->Form->control('fecha_firma', [
                            'label' => 'Fecha de Firma',
                            'type' => 'text',
                            'class' => 'calendario',
                            'append' => '<i class="fa fa-calendar"></i>',
                            'prepend' => '<i class="fa fa-asterisk"></i>'
                        ]);
                        echo $this->Form->control('usar_credencial', [
                            'label' => 'Usar Credencial del Programa',
                            'type' => 'checkbox'
                        ]);
                    ?>
                </fieldset>
            </div>
            <div class="box-footer">
                <?= $this->Form->button('<i class="fa fa-cog"></i>&nbsp;Generar Acta',
			        ['type' => 'submit','class'=>'btn btn-success btn-flat pull-left','escape'=>false]);
		        ?>
		        <?= $this->Html->link('<i class="fa fa-power-off"></i>&nbsp;Cerrar',
                    ['controller' => 'Actos', 'action' => 'homepage'],
			        ['class' => 'btn bg-maroon btn-flat pull-right','escape' => false]); 
		        ?>        
            </div>
        </div>
        <?= $this->Form->end();?>
    </div>
</div>

<?php if (!empty($graduandos) && $totalGraduandos > 0): ?>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-sace box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-list-alt"></i>&nbsp;Listado Generado
                        <small>&nbsp;(<?= $totalGraduandos ?> graduando(s))</small>
                    </h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th class="text-center">N.º</th>
                                <th>Carrera</th>
                                <th class="text-center">Control</th>
                                <th class="text-center">Cédula</th>
                                <th>Apellidos</th>
                                <th>Nombres</th>
                                <th class="text-center">Índice</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $nNro = (($this->Paginator->current() - 1) * $this->Paginator->param('perPage')) + 1; ?>
                            <?php foreach ($graduandos as $oGraduando): ?>
                                <tr>
                                    <td class="text-center"><?= $nNro++ ?></td>
                                    <td><?= $oGraduando->has('carrera') ? h($oGraduando->carrera->codename) : '' ?></td>
                                    <td class="text-center"><b><?= h($oGraduando->control) ?></b></td>
                                    <td class="text-center"><?= $oGraduando->has('estudiante') ? h($oGraduando->estudiante->cedula) : '' ?></td>
                                    <td><?= $oGraduando->has('estudiante') ? h($oGraduando->estudiante->apellidos) : '' ?></td>
                                    <td><?= $oGraduando->has('estudiante') ? h($oGraduando->estudiante->nombres) : '' ?></td>
                                    <td class="text-center"><?= h($oGraduando->indice) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
$(document).ready(function() {
    function cargarCarreras() {
        var actoId = $('#promocion').val();
        var institucion = $('#institucion').val();
        var carreraSel = $('#carrera').val();

        if (!actoId || !institucion) {
            return;
        }

        $.ajax({
            url: '<?= $this->Url->build(['controller' => 'actos', 'action' => 'carreras']) ?>',
            type: 'GET',
            data: { acto_id: actoId, institucion: institucion },
            dataType: 'json',
            success: function(response) {
                var $select = $('#carrera');
                var actual = carreraSel;
                $select.children('option:not(:first)').remove();
                if (response.carreras) {
                    $.each(response.carreras, function(i, carrera) {
                        $select.append($('<option>', { value: carrera.id, text: carrera.nombre }));
                    });
                    if (actual) {
                        $select.val(actual);
                    }
                }
                $select.trigger('change');
            },
            error: function() {
                toastr.error('Error al cargar las carreras.');
            }
        });
    }

    $('#promocion, #institucion').on('change', function() {
        $('#carrera').children('option:not(:first)').remove();
        cargarCarreras();
    });

    var actoIdInit = $('#promocion').val();
    var institucionInit = $('#institucion').val();
    if (actoIdInit && institucionInit) {
        cargarCarreras();
    }
});
</script>