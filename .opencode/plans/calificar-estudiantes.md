# Plan: Cargar Calificación/Recuperación/Definitiva desde Cursos/view

## Archivos a modificar/crear

| Acción | Archivo |
|--------|---------|
| **MODIFICAR** | `src/Controller/AppController.php` |
| **MODIFICAR** | `src/Controller/CursoNotasController.php` |
| **CREAR** | `src/Template/Cursos/calificar.ctp` |
| **MODIFICAR** | `src/Controller/CursosController.php` |
| **MODIFICAR** | `src/Template/Cursos/view.ctp` |

---

## 1. AppController.php — Agregar `_resolverNotaMinima()` como protegido

Insertar **antes de** `_normalizarNota()` (~línea 495):

```php
protected function _resolverNotaMinima($oCurso)
{
    $mallasTable = TableRegistry::getTableLocator()->get('Mallas');
    $oMalla = $mallasTable->find()
        ->where([
            'Mallas.asignatura_id' => $oCurso->asignatura_id,
            'Mallas.carrera_id' => $oCurso->carrera_id,
        ])
        ->first();

    if ($oMalla && !empty($oMalla->nota_minima)) {
        return (int)$oMalla->nota_minima;
    }

    if (!empty($oCurso->asignatura->nota_minima)) {
        return (int)$oCurso->asignatura->nota_minima;
    }

    if (!empty($oCurso->programas)) {
        $aProgramaIds = array_filter(explode(' ', $oCurso->programas));
        if (!empty($aProgramaIds)) {
            $programasTable = TableRegistry::getTableLocator()->get('Programas');
            $oPrograma = $programasTable->get((int)reset($aProgramaIds));
            if (!empty($oPrograma->nota_minima)) {
                return (int)$oPrograma->nota_minima;
            }
        }
    }

    return 10;
}
```

---

## 2. CursoNotasController.php — Delegar a parent

Cambiar en línea 85:
```php
private function _resolverNotaMinima($oCurso)
```
→
```php
protected function _resolverNotaMinima($oCurso)
```

Eliminar TODO el cuerpo (líneas 86-115) y reemplazar por:
```php
{
    return parent::_resolverNotaMinima($oCurso);
}
```

---

## 3. CREAR: `src/Template/Cursos/calificar.ctp`

```php
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
                    <button id="btnGuardar" class="btn btn-sm <?= $sColorBtn ?>">
                        <i class="fa fa-save"></i>&nbsp;Guardar <?= $sTitulo ?>
                    </button>
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
                            $bAprobado = ($nTipoCalificacion == 0 && is_numeric($sValor) && $sValor >= $sNotaMinima)
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
```

---

## 4. CursosController.php — Implementar `calificar()`

Reemplazar el método actual (líneas 229-244) con:

```php
public function calificar()
{
    $nCursoId = $this->request->getQuery('nCursoId');
    $sNota = $this->request->getQuery('sNota');

    if (!in_array($sNota, ['calificacion', 'recuperacion', 'definitiva'])) {
        $this->Flash->error('Tipo de nota inválido.');
        return $this->redirect(['action' => 'index']);
    }

    $oCurso = $this->Cursos->get($nCursoId, [
        'contain' => ['Asignaturas', 'Periodos', 'Docentes'],
    ]);

    if (!$oCurso) {
        $this->Flash->error('Curso no encontrado.');
        return $this->redirect(['action' => 'index']);
    }

    $nTipoCalificacion = (int)($oCurso->asignatura->calificacion ?? 0);
    $nNotaMinima = $this->_resolverNotaMinima($oCurso);
    $bReadonly = $oCurso->cerrado == 1;

    if ($this->request->is('post') || $this->request->is('ajax')) {
        $this->autoRender = false;
        $this->response = $this->response->withType('application/json');

        $notas = $this->request->getData('notas');
        if (empty($notas)) {
            $this->response = $this->response->withStringBody(json_encode([
                'success' => false, 'message' => 'No se recibieron notas.'
            ]));
            return $this->response;
        }

        if ($bReadonly) {
            $this->response = $this->response->withStringBody(json_encode([
                'success' => false, 'message' => 'El curso está cerrado.'
            ]));
            return $this->response;
        }

        $estudianteCursosTable = TableRegistry::getTableLocator()->get('EstudianteCursos');
        $errors = [];

        foreach ($notas as $ecId => $valor) {
            $valor = trim($valor);

            if ($nTipoCalificacion == 0) {
                if (!preg_match('/^\d+(\.\d{1,2})?$/', $valor)) {
                    $errors[] = "El valor '$valor' no es un número válido (máx. 2 decimales).";
                    continue;
                }
                $nValor = (float)$valor;
                if ($nValor < 1 || $nValor > 20) {
                    $errors[] = "El valor '$valor' debe estar entre 1 y 20.";
                    continue;
                }
            } else {
                $valor = strtoupper($valor);
                if (!in_array($valor, ['A', 'R'])) {
                    $errors[] = "El valor '$valor' debe ser A o R.";
                    continue;
                }
            }

            $entity = $estudianteCursosTable->get($ecId);
            $entity->{$sNota} = ($nTipoCalificacion == 0) ? $valor : strtoupper($valor);
            $entity->responsable = $this->_getUsuarioActual();

            if ($estudianteCursosTable->save($entity)) {
                $this->Auditorias->registrar('MODIFICA',
                    "{$sNota}: EstudianteCurso #{$ecId} = {$valor} (Curso #{$nCursoId})");
            } else {
                $errors[] = "Error al guardar registro #{$ecId}.";
            }
        }

        if (empty($errors)) {
            $this->response = $this->response->withStringBody(json_encode([
                'success' => true, 'message' => 'Notas guardadas correctamente.'
            ]));
        } else {
            $this->response = $this->response->withStringBody(json_encode([
                'success' => false,
                'message' => 'Se guardaron con errores: ' . implode(' ', $errors)
            ]));
        }

        return $this->response;
    }

    $estudianteCursosTable = TableRegistry::getTableLocator()->get('EstudianteCursos');
    $query = $estudianteCursosTable->find()
        ->contain(['Estudiantes'])
        ->where(['curso_id' => $nCursoId, 'activo' => 1])
        ->order(['Estudiantes.apellidos' => 'ASC', 'Estudiantes.nombres' => 'ASC']);

    $estudianteCursos = $this->paginate($query);

    $this->set(compact('curso', 'estudianteCursos', 'sNota', 'nTipoCalificacion', 'nNotaMinima', 'bReadonly'));
    $this->set('curso', $oCurso);
}
```

Asegurar que `use Cake\ORM\TableRegistry;` esté al inicio del archivo.

---

## 5. view.ctp — Corregir etiquetas de botones (líneas 113-126)

| Línea | Actual | Cambiar a |
|-------|--------|-----------|
| 114 | `'D'` (calificación) | `'C'` |
| 118 | `'R'` (recuperación) | `'R'` (igual) |
| 122 | `'D'` (definitiva) | `'D'` (igual) |

Específicamente, línea 114:
```php
<?= $this->Html->link('C',
    ['action' => 'calificar','?' => ['nCursoId' => $curso->id, 'sNota' => 'calificacion'] ],
    ['class' => 'btn bg-purple btn-xs']);
?>
```
