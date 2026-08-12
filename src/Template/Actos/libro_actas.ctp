<div class="row">
    <div class="col-md-12">
        <div class="box box-purple box-solid">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-book"></i>&nbsp;Libro de Actas</h3>
                <div class="box-tools pull-right">
			        <button type="button" class="btn btn-box-tool" data-widget="collapse">
				        <i class="fa fa-minus"></i>
			        </button>
                    <?= $this->Html->link('<i class="fa fa-times"></i>',
                        ['action' => 'homepage'], ['class'=>'btn btn-box-tool','escape' => false]) 
                    ?>
                </div>
            </div>
            <?= $this->Form->create(null, ['role' => 'form', 
                'id' => 'formGenerarListado',
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
                    ?>
                </fieldset>
            </div>
            <div class="box-footer">
                <?= $this->Form->button('<i class="fa fa-cog"></i>&nbsp;Generar Listado',
			        ['type' => 'submit','class'=>'btn btn-success btn-flat pull-left','escape'=>false,'id'=>'btnGenerarListado']);
		        ?>
		        <?= $this->Html->link('<i class="fa fa-power-off"></i>&nbsp;Cerrar Ventana',
                    ['action' => 'homepage'],
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
                        <tfoot class="no-padding">
                            <tr>
                                <td colspan="7" class="text-center">
                                    <div class="paginator">
                                        <ul class="pagination pagination-sm">
                                            <?= $this->Paginator->first('<i class="fa fa-angle-double-left"></i>', ['class' => 'btn btn-sm', 'escape' => false]) ?>
                                            <?= $this->Paginator->prev('<i class="fa fa-angle-left"></i>', ['class' => 'btn btn-sm', 'escape' => false]) ?>
                                            <?= $this->Paginator->numbers(['before' => '', 'after' => '']) ?>
                                            <?= $this->Paginator->next('<i class="fa fa-angle-right"></i>', ['class' => 'btn btn-sm', 'escape' => false]) ?>
                                            <?= $this->Paginator->last('<i class="fa fa-angle-double-right"></i>', ['class' => 'btn btn-sm', 'escape' => false]) ?>
                                        </ul>
                                        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
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

<?php $this->Html->scriptBlock("
    \$('#btnGenerarListado').on('click', function(e) {
        e.preventDefault();
        var \$form = \$('#formGenerarListado');

        Swal.fire({
            title: 'Generando listado',
            html: 'Asignando n\u00FAmeros de control...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: function() {
                Swal.showLoading();
                \$.ajax({
                    url: \$form.attr('action'),
                    type: 'POST',
                    data: \$form.serialize(),
                    dataType: 'json'
                })
                .done(function(data) {
                    if (data.success) {
                        Swal.fire({
                            title: 'Completado',
                            html: data.message,
                            icon: 'success',
                            timer: 2500,
                            timerProgressBar: true,
                            allowOutsideClick: false,
                            showConfirmButton: false
                        }).then(function() {
                            window.location.href = data.redirect;
                        });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .fail(function() {
                    Swal.fire('Error', 'No se pudo generar el listado.', 'error');
                });
            }
        });
    });
", ['block' => 'scriptBottom']); ?>