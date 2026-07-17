<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Estudiante $estudiante
 * @var \App\Model\Entity\Carrera $carrera
 * @var array $periodos
 */
?>
<div class="modal-header bg-aqua">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title"><i class="fa fa-plus-circle"></i>&nbsp;Inscribir Curso — <?= h($estudiante->full_name) ?></h4>
</div>

<div class="modal-body">
    <form id="form-inscribir">
        <input type="hidden" name="estudiante_id" id="hid-estudiante-id" value="<?= $estudiante->id ?>">
        <input type="hidden" id="hid-carrera-id" value="<?= $carrera->id ?>">

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label"><strong>Carrera</strong></label>
                    <p class="form-control-static"><?= h($carrera->codigo) ?> — <?= h($carrera->nombre) ?></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="sel-periodo"><strong>Período</strong></label>
                    <select name="periodo_id" id="sel-periodo" class="form-control select2">
                        <option value="">-- Seleccione Período --</option>
                        <?php foreach ($periodos as $id => $codigo): ?>
                            <option value="<?= $id ?>"><?= h($codigo) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-list"></i>&nbsp;Cursos Disponibles</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-condensed" id="tbl-cursos-disponibles">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:30px;">
                                        <input type="checkbox" id="check-todos-cursos" title="Seleccionar todos">
                                    </th>
                                    <th class="text-center">Curso</th>
                                    <th>Trayecto</th>
                                    <th class="text-center">Sección</th>
                                    <th>Asignatura</th>
                                    <th class="text-center">Cupos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Seleccione un período para ver los cursos disponibles.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
        <i class="fa fa-times"></i>&nbsp;Cancelar
    </button>
    <button type="button" class="btn bg-olive" id="btn-inscribir-cursos">
        <i class="fa fa-save"></i>&nbsp;Inscribir
    </button>
</div>


