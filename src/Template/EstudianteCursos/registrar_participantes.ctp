<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Curso $curso
 * @var array $trayectos
 */
?>
<div class="modal-header bg-aqua">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title"><i class="fa fa-users"></i>&nbsp;Registrar Participantes — <?= h($curso->asignatura->nombre ?? '') ?> <?= h($curso->seccion) ?></h4>
</div>

<div class="modal-body">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#tab-trayecto" aria-controls="tab-trayecto" role="tab" data-toggle="tab">
                <i class="fa fa-exchange"></i>&nbsp;Trayecto Anterior
            </a>
        </li>
        <li role="presentation">
            <a href="#tab-excel" aria-controls="tab-excel" role="tab" data-toggle="tab">
                <i class="fa fa-file-excel-o"></i>&nbsp;Archivo Excel
            </a>
        </li>
    </ul>

    <div class="tab-content" style="margin-top:15px;">
        <div role="tabpanel" class="tab-pane active" id="tab-trayecto">
            <input type="hidden" id="rp-curso-id" value="<?= $curso->id ?>">
            <input type="hidden" id="rp-curso-cupos" value="<?= (int)$curso->cupos ?>">

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="rp-trayecto-origen"><strong>Trayecto de Origen</strong></label>
                        <select id="rp-trayecto-origen" class="form-control">
                            <option value="">-- Seleccione Trayecto --</option>
                            <?php foreach ($trayectos as $id => $codigo): ?>
                                <option value="<?= $id ?>"><?= h($codigo) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4" style="padding-top:25px;">
                    <button type="button" class="btn bg-navy" id="rp-btn-cargar-trayecto" disabled>
                        <i class="fa fa-search"></i>&nbsp;Buscar
                    </button>
                </div>
            </div>

            <div id="rp-resultado-trayecto" style="display:none;">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-users"></i>&nbsp;Estudiantes del Trayecto</h3>
                        <span class="badge bg-blue" id="rp-contador-trayecto">0</span>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-condensed" id="tbl-rp-trayecto">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:30px;">
                                        <input type="checkbox" id="rp-check-todos-trayecto" title="Seleccionar todos">
                                    </th>
                                    <th>Cédula</th>
                                    <th>Nombre</th>
                                    <th>Expediente</th>
                                    <th class="text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div role="tabpanel" class="tab-pane" id="tab-excel">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="rp-archivo"><strong>Archivo Excel (.xlsx / .xls)</strong></label>
                        <input type="file" id="rp-archivo" accept=".xlsx,.xls" class="form-control">
                    </div>
                </div>
                <div class="col-md-4" style="padding-top:25px;">
                    <button type="button" class="btn bg-olive" id="rp-btn-procesar-excel" disabled>
                        <i class="fa fa-upload"></i>&nbsp;Procesar
                    </button>
                </div>
            </div>

            <div id="rp-resultado-excel" style="display:none;">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-file-excel-o"></i>&nbsp;Resultados del Archivo</h3>
                        <span class="badge bg-green" id="rp-validos-contador">0</span>
                        <span class="badge bg-red" id="rp-rechazados-contador">0</span>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-condensed" id="tbl-rp-excel">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:30px;">
                                        <input type="checkbox" id="rp-check-todos-excel" title="Seleccionar todos">
                                    </th>
                                    <th>Fila</th>
                                    <th>Cédula</th>
                                    <th>Nombre</th>
                                    <th>Expediente</th>
                                    <th class="text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="rp-alerta" style="display:none;" class="alert alert-warning">
        <i class="fa fa-exclamation-triangle"></i>&nbsp;<span id="rp-alerta-texto"></span>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
        <i class="fa fa-times"></i>&nbsp;Cancelar
    </button>
    <button type="button" class="btn bg-maroon" id="rp-btn-registrar" disabled>
        <i class="fa fa-save"></i>&nbsp;Registrar Participantes
    </button>
</div>

