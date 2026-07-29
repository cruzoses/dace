<?php
$sTitulo = '';
$sColorBtn = '';
switch ($sNota) {
    case 'calificacion': $sTitulo = 'Calificación'; $sColorBtn = 'bg-purple'; break;
    case 'recuperacion': $sTitulo = 'Recuperación'; $sColorBtn = 'bg-olive'; break;
    case 'definitiva':   $sTitulo = 'Definitiva';   $sColorBtn = 'bg-maroon'; break;
}
$this->assign('title', $sTitulo);
?>
<style>
.nota-input:disabled,
.nota-input[readonly] {
    background-color: transparent !important;
    border-color: transparent !important;
    box-shadow: none !important;
    text-align: center;
    font-weight: bold;
    cursor: default;
}
select.nota-input:disabled {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    text-align: center;
}
.nota-aprobada { color: #0073b7 !important; }
.nota-reprobada { color: #dd4b39 !important; }
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-sace box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-edit"></i>&nbsp;Cargar <?= $sTitulo ?></h3>
            </div>
            <div class="box-body">
                <dl class="dl-horizontal">
                    <dt>Asignatura</dt>
                    <dd><?= h($curso->asignatura->codename ?? '') ?></dd>
                    <dt>Sección</dt>
                    <dd><?= h($curso->seccion) ?></dd>
                    <dt>Docente</dt>
                    <dd><?= h($curso->docente->codename ?? '') ?></dd>
                    <dt>Tipo</dt>
                    <dd><?= $nTipoCalificacion == 0 ? 'Cuantitativa (1-20)' : 'Cualitativa (A/R)' ?></dd>
                    <dt>Nota Mínima</dt>
                    <dd><?= $nNotaMinima ?></dd>
                </dl>
            </div>
        </div>

        <div class="box box-default box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-users"></i>&nbsp;Estudiantes</h3>
                <div class="box-tools pull-right">
                    <?php if (!$bReadonly): ?>
                    <button id="btnGuardar" class="btn btn-sm <?= $sColorBtn ?>">
                        <i class="fa fa-save"></i>&nbsp;Guardar <?= $sTitulo ?>
                    </button>
                    <?php endif; ?>
                    <?= $this->Html->link('<i class="fa fa-arrow-left"></i>&nbsp;Volver',
                        ['action' => 'view', $curso->id],
                        ['class' => 'btn btn-default btn-sm', 'escape' => false]) ?>
                </div>
            </div>
            <div class="box-body table-responsive no-padding">
                <?php if (!empty($estudianteCursos)): ?>
                <table class="table table-bordered table-hover table-condensed" id="tbl-calificar">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:40px;">No.</th>
                            <th>Cédula</th>
                            <th>Estudiante</th>
                            <th class="text-center" style="width:120px;"><?= $sTitulo ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $nPagina = (int)$this->request->getQuery('page', 1);
                        $nLimite = 20;
                        $nOffset = ($nPagina - 1) * $nLimite;
                        $sNotaMinima = (int)$nNotaMinima;
                        ?>
                        <?php foreach ($estudianteCursos as $i => $ec): ?>
                        <?php
                            $sValor = $ec->{$sNota} ?? '';
                            $bAprobado = ($nTipoCalificacion == 0 && is_numeric($sValor) && (float)$sValor >= $sNotaMinima)
                                || ($nTipoCalificacion == 1 && strtoupper($sValor) === 'A');
                            $sClase = '';
                            if ($sValor !== '') {
                                $sClase = $bAprobado ? 'nota-aprobada' : 'nota-reprobada';
                            }
                        ?>
                        <tr>
                            <td class="text-center"><?= ($nOffset + $i + 1) ?></td>
                            <td><?= $ec->has('estudiante') ? $this->Number->format($ec->estudiante->cedula) : '' ?></td>
                            <td><?= $ec->has('estudiante') ? h($ec->estudiante->full_name) : '' ?></td>
                            <td class="text-center">
                                <?php if ($nTipoCalificacion == 0): ?>
                                <input type="text"
                                    class="form-control input-sm nota-input <?= $sClase ?>"
                                    data-id="<?= $ec->id ?>"
                                    value="<?= h($sValor) ?>"
                                    inputmode="decimal"
                                    maxlength="5"
                                    <?= $bReadonly ? 'readonly' : '' ?>>
                                <?php else: ?>
                                <select class="form-control input-sm nota-input <?= $sClase ?>"
                                    data-id="<?= $ec->id ?>"
                                    <?= $bReadonly ? 'disabled' : '' ?>>
                                    <option value=""></option>
                                    <option value="A" <?= $sValor === 'A' ? 'selected' : '' ?>>A</option>
                                    <option value="R" <?= $sValor === 'R' ? 'selected' : '' ?>>R</option>
                                </select>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p class="text-center text-muted">No hay estudiantes inscritos en este curso.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if (!$bReadonly): ?>
<script>
$(document).ready(function() {
    <?php if ($nTipoCalificacion == 0): ?>
    var regex = /^\d*(\.\d{0,2})?$/;
    $(document).on('input', '.nota-input', function() {
        var val = $(this).val();
        if (!regex.test(val)) {
            $(this).val(val.slice(0, -1));
        }
    });

    $(document).on('change', '.nota-input', function() {
        var val = parseFloat($(this).val());
        if (isNaN(val) || val < 1) $(this).val('1');
        else if (val > 20) $(this).val('20');
        var nota = parseFloat($(this).val());
        $(this).removeClass('nota-aprobada nota-reprobada');
        if (!isNaN(nota)) {
            $(this).addClass(nota >= <?= $nNotaMinima ?> ? 'nota-aprobada' : 'nota-reprobada');
        }
    });
    <?php else: ?>
    $(document).on('change', '.nota-input', function() {
        $(this).removeClass('nota-aprobada nota-reprobada');
        if ($(this).val() === 'A') $(this).addClass('nota-aprobada');
        else if ($(this).val() === 'R') $(this).addClass('nota-reprobada');
    });
    <?php endif; ?>

    $('#btnGuardar').click(function() {
        var $btn = $(this);
        var notas = {};
        $('.nota-input').each(function() {
            var val = $(this).val();
            if (val !== '') {
                notas[$(this).data('id')] = val;
            }
        });

        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>&nbsp;Guardando...');

        $.ajax({
            url: '<?= $this->Url->build(['action' => 'calificar']) ?>',
            type: 'POST',
            data: {
                nCursoId: <?= $curso->id ?>,
                sNota: '<?= $sNota ?>',
                notas: notas
            },
            dataType: 'json',
            success: function(resp) {
                if (resp.success) {
                    toastr.success(resp.message || 'Notas guardadas correctamente.');
                    setTimeout(function() { location.reload(); }, 800);
                } else {
                    toastr.error(resp.message || 'Error al guardar notas.');
                    $btn.prop('disabled', false).html('<i class="fa fa-save"></i>&nbsp;Guardar <?= $sTitulo ?>');
                }
            },
            error: function() {
                toastr.error('Error de conexión.');
                $btn.prop('disabled', false).html('<i class="fa fa-save"></i>&nbsp;Guardar <?= $sTitulo ?>');
            }
        });
    });
});
</script>
<?php endif; ?>
