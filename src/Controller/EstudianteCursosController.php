<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use PhpOffice\PhpSpreadsheet\IOFactory;

class EstudianteCursosController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    public function isAuthorized($user = null)
    {
        if (isset($user['activo']) && isset($user['rols']) && $user['activo'] && $this->tienePermiso([1, 2, 3])) {
            return true;
        }
        return parent::isAuthorized($user);
    }

    public function index($estudianteId = null)
    {
        $this->viewBuilder()->setLayout('ajax');

        $estudiantesTable = TableRegistry::getTableLocator()->get('Estudiantes');
        $estudiante = $estudiantesTable->get($estudianteId);

        $programasTable = TableRegistry::getTableLocator()->get('EstudianteProgramas');
        $programas = $programasTable->find()
            ->contain(['Programas', 'Estudiantes'])
            ->where([
                'EstudianteProgramas.estudiante_id' => $estudianteId,
                'EstudianteProgramas.activo' => 1,
            ])
            ->order(['EstudianteProgramas.id' => 'ASC'])
            ->toArray();

        $inscripciones = $this->EstudianteCursos->find()
            ->contain([
                'Cursos.Carreras',
                'Cursos.Trayectos',
                'Cursos.Asignaturas',
            ])
            ->where(['EstudianteCursos.estudiante_id' => $estudianteId])
            ->order(['EstudianteCursos.id' => 'DESC'])
            ->toArray();

        $this->set(compact('estudiante', 'programas', 'inscripciones'));
    }

    public function inscribir($estudianteId = null, $carreraId = null)
    {
        $this->request->allowMethod(['ajax', 'get']);

        $estudiantesTable = TableRegistry::getTableLocator()->get('Estudiantes');
        $estudiante = $estudiantesTable->get($estudianteId);

        $carrerasTable = TableRegistry::getTableLocator()->get('Carreras');
        $carrera = $carrerasTable->get($carreraId);

        $periodos = TableRegistry::getTableLocator()->get('Periodos')
            ->find('list')
            ->where(['Periodos.activo' => 1])
            ->order(['Periodos.id' => 'DESC'])
            ->toArray();

        $this->set(compact('estudiante', 'carrera', 'periodos'));
        $this->viewBuilder()->setLayout('ajax');
    }

    public function inscribirCurso()
    {
        $this->request->allowMethod(['ajax', 'post']);
        $this->autoRender = false;

        $data = $this->request->getData();
        $estudianteId = $data['estudiante_id'];
        $cursoIds = $data['curso_id'] ?? [];
        $responsable = $this->_getUsuarioActual();

        if (empty($cursoIds)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'Debe seleccionar al menos un curso.',
                ]));
        }

        $cursosTable = TableRegistry::getTableLocator()->get('Cursos');
        $inscritos = 0;
        $yaInscrito = 0;
        $sinCupos = 0;

        foreach ($cursoIds as $cursoId) {
            $existe = $this->EstudianteCursos->find()
                ->where([
                    'EstudianteCursos.curso_id' => $cursoId,
                    'EstudianteCursos.estudiante_id' => $estudianteId,
                ])
                ->count();

            if ($existe > 0) {
                $yaInscrito++;
                continue;
            }

            $curso = $cursosTable->get($cursoId);
            $ocupados = $this->EstudianteCursos->find()
                ->where([
                    'EstudianteCursos.curso_id' => $cursoId,
                    'EstudianteCursos.activo' => 1,
                ])
                ->count();

            if ($ocupados >= $curso->cupos) {
                $sinCupos++;
                continue;
            }

            $entity = $this->EstudianteCursos->newEntity();
            $entity->curso_id = $cursoId;
            $entity->estudiante_id = $estudianteId;
            $entity->responsable = $responsable;
            $entity->activo = 1;

            if ($this->EstudianteCursos->save($entity)) {
                $inscritos++;
            }
        }

        $this->Auditorias->registrar('REGISTRA', "INSCRIBE ESTUDIANTE #$estudianteId EN " . $inscritos . " CURSO(S)");

        $mensaje = "$inscritos curso(s) inscrito(s) correctamente.";
        if ($yaInscrito > 0) {
            $mensaje .= " $yaInscrito ya estaba(n) inscrito(s).";
        }
        if ($sinCupos > 0) {
            $mensaje .= " $sinCupos sin cupos disponibles.";
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'success' => $inscritos > 0,
                'message' => $mensaje,
                'inscritos' => $inscritos,
                'ya_inscrito' => $yaInscrito,
                'sin_cupos' => $sinCupos,
            ]));
    }

    public function eliminar($id = null)
    {
        $this->request->allowMethod(['ajax', 'post']);
        $this->autoRender = false;

        $registro = $this->EstudianteCursos->get($id);
        $estudianteId = $registro->estudiante_id;

        if ($this->EstudianteCursos->delete($registro)) {
            $this->Auditorias->registrar('ELIMINA', "ELIMINA INSCRIPCION #$id DEL ESTUDIANTE #$estudianteId");

            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => true,
                    'message' => 'Inscripción eliminada.',
                ]));
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'success' => false,
                'message' => 'Error al eliminar la inscripción.',
            ]));
    }

    public function eliminarSeleccionados()
    {
        $this->request->allowMethod(['ajax', 'post']);
        $this->autoRender = false;

        $data = $this->request->getData();
        $ids = $data['ids'] ?? [];

        if (empty($ids)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'No se seleccionaron inscripciones.',
                ]));
        }

        $eliminados = 0;
        foreach ($ids as $id) {
            $registro = $this->EstudianteCursos->get($id);
            if ($this->EstudianteCursos->delete($registro)) {
                $eliminados++;
            }
        }

        $this->Auditorias->registrar('ELIMINA', "ELIMINA $eliminados INSCRIPCIONES EN LOTE");

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'success' => $eliminados > 0,
                'message' => "$eliminados inscripción(es) eliminada(s).",
                'eliminados' => $eliminados,
            ]));
    }

    public function getCursos()
    {
        $this->request->allowMethod(['ajax', 'get']);
        $this->autoRender = false;

        $periodoId = $this->request->getQuery('periodo_id');
        $carreraId = $this->request->getQuery('carrera_id');
        $estudianteId = $this->request->getQuery('estudiante_id');

        $cursos = [];
        if ($periodoId && $carreraId) {
            $cursosTable = TableRegistry::getTableLocator()->get('Cursos');

            $yaInscritos = $this->EstudianteCursos->find()
                ->select(['EstudianteCursos.curso_id'])
                ->where(['EstudianteCursos.estudiante_id' => $estudianteId])
                ->extract('curso_id')
                ->toArray();

            $query = $cursosTable->find()
                ->contain(['Asignaturas', 'Docentes', 'Trayectos'])
                ->where([
                    'Cursos.periodo_id' => $periodoId,
                    'Cursos.carrera_id' => $carreraId,
                    'Cursos.activo' => 1,
                ])
                ->order(['Cursos.id' => 'DESC']);

            if (!empty($yaInscritos)) {
                $query->where(['Cursos.id NOT IN' => $yaInscritos]);
            }

            foreach ($query->toArray() as $curso) {
                $ocupados = $this->EstudianteCursos->find()
                    ->where([
                        'EstudianteCursos.curso_id' => $curso->id,
                        'EstudianteCursos.activo' => 1,
                    ])
                    ->count();

                $cursos[] = [
                    'id' => $curso->id,
                    'asignatura' => $curso->asignatura->codename,
                    'seccion' => $curso->seccion,
                    'trayecto' => $curso->trayecto->codename,
                    'docente' => $curso->docente->name,
                    'cupos' => $curso->cupos,
                    'disponibles' => max(0, $curso->cupos - $ocupados),
                ];
            }
        }

        $this->response = $this->response->withType('application/json');
        $this->response = $this->response->withStringBody(json_encode(['cursos' => $cursos]));
        return $this->response;
    }

    public function getInscripciones($estudianteId = null)
    {
        $this->request->allowMethod(['ajax', 'get']);
        $this->autoRender = false;

        $inscripciones = $this->EstudianteCursos->find()
            ->contain([
                'Cursos.Carreras',
                'Cursos.Trayectos',
                'Cursos.Asignaturas',
            ])
            ->where(['EstudianteCursos.estudiante_id' => $estudianteId])
            ->order(['EstudianteCursos.id' => 'DESC'])
            ->toArray();

        $html = $this->_renderInscripcionesBox($inscripciones, $estudianteId);

        $this->response = $this->response->withType('html');
        $this->response = $this->response->withStringBody($html);
        return $this->response;
    }

    private function _renderInscripcionesBox($inscripciones, $estudianteId)
    {
        $count = count($inscripciones);
        $html = '<div class="box box-info box-solid">';
        $html .= '<div class="box-header with-border">';
        $html .= '<h3 class="box-title"><i class="fa fa-list"></i>&nbsp;Cursos Inscritos (' . $count . ')</h3>';
        $html .= '<div class="box-tools pull-right">';
        $html .= '<button type="button" class="btn btn-danger btn-xs" id="btn-eliminar-seleccionados" style="display:none;">';
        $html .= '<i class="fa fa-trash"></i>&nbsp;Eliminar seleccionados (<span id="count-seleccionados">0</span>)';
        $html .= '</button></div></div>';
        $html .= '<div class="box-body table-responsive no-padding">';
        $html .= '<table class="table table-hover table-condensed" id="tabla-inscripciones">';
        $html .= '<thead><tr>';
        $html .= '<th class="text-center" style="width:30px;"><input type="checkbox" id="check-todos" title="Seleccionar todos"></th>';
        $html .= '<th class="text-center">Curso</th><th>Carrera</th><th>Trayecto</th>';
        $html .= '<th class="text-center">Sección</th><th>Asignatura</th>';
        $html .= '<th class="text-center">Fecha</th><th>Responsable</th>';
        $html .= '</tr></thead><tbody>';

        if ($count > 0) {
            foreach ($inscripciones as $ins) {
                $html .= '<tr>';
                $html .= '<td class="text-center"><input type="checkbox" class="check-inscripcion" value="' . $ins->id . '"></td>';
                $html .= '<td class="text-center">' . $ins->curso->id . '</td>';
                $html .= '<td>' . ($ins->curso->has('carrera') ? h($ins->curso->carrera->codigo) : '') . '</td>';
                $html .= '<td>' . ($ins->curso->has('trayecto') ? h($ins->curso->trayecto->codigo) : '') . '</td>';
                $html .= '<td class="text-center">';
                $cursoUrl = $this->Url->build(['controller' => 'Cursos', 'action' => 'view', $ins->curso->id]);
                $html .= '<a href="' . $cursoUrl . '" class="btn btn-default btn-xs">' . h($ins->curso->seccion) . '</a>';
                $html .= '</td>';
                $html .= '<td>' . ($ins->curso->has('asignatura') ? h($ins->curso->asignatura->codename) : '') . '</td>';
                $html .= '<td class="text-center">' . $ins->created->format('d/m/Y') . '</td>';
                $html .= '<td>' . h($ins->responsable) . '</td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr><td colspan="8" class="text-center text-muted">No hay cursos inscritos para este estudiante.</td></tr>';
        }

        $html .= '</tbody></table></div></div>';
        return $html;
    }

    public function getCupos()
    {
        $this->request->allowMethod(['ajax', 'get']);
        $this->autoRender = false;

        $cursoId = $this->request->getQuery('curso_id');
        $cuposDisponibles = 0;

        if ($cursoId) {
            $cursosTable = TableRegistry::getTableLocator()->get('Cursos');
            $curso = $cursosTable->get($cursoId);

            $inscritos = $this->EstudianteCursos->find()
                ->where([
                    'EstudianteCursos.curso_id' => $cursoId,
                    'EstudianteCursos.activo' => 1,
                ])
                ->count();

            $cuposDisponibles = max(0, $curso->cupos - $inscritos);
        }

        $this->response = $this->response->withType('application/json');
        $this->response = $this->response->withStringBody(json_encode(['cupos_disponibles' => $cuposDisponibles]));
        return $this->response;
    }

    public function registrarParticipantes($cursoId = null)
    {
        $this->request->allowMethod(['ajax', 'get']);
        $this->viewBuilder()->setLayout('ajax');

        $cursosTable = TableRegistry::getTableLocator()->get('Cursos');
        $curso = $cursosTable->get($cursoId, [
            'contain' => ['Carreras', 'Trayectos', 'Asignaturas', 'EstudianteCursos'],
        ]);

        $carreraId = $curso->carrera_id;

        $cursosMismaCarrera = $cursosTable->find()
            ->select(['trayecto_id'])
            ->where(['Cursos.carrera_id' => $carreraId, 'Cursos.activo' => 1])
            ->distinct(['trayecto_id'])
            ->extract('trayecto_id')
            ->toArray();

        $trayectos = [];
        if (!empty($cursosMismaCarrera)) {
            $trayectosTable = TableRegistry::getTableLocator()->get('Trayectos');
            $trayectos = $trayectosTable->find('list')
                ->where(['Trayectos.id IN' => $cursosMismaCarrera])
                ->order(['Trayectos.codigo' => 'ASC'])
                ->toArray();
        }

        $this->set(compact('curso', 'trayectos'));
    }

    public function getEstudiantesTrayecto()
    {
        $this->request->allowMethod(['ajax', 'get']);
        $this->autoRender = false;

        $cursoId = $this->request->getQuery('curso_id');
        $trayectoOrigenId = $this->request->getQuery('trayecto_origen_id');

        $cursosTable = TableRegistry::getTableLocator()->get('Cursos');
        $curso = $cursosTable->get($cursoId);
        $carreraId = $curso->carrera_id;
        $periodoId = $curso->periodo_id;

        $cursosOrigen = $cursosTable->find('list')
            ->where([
                'Cursos.carrera_id' => $carreraId,
                'Cursos.periodo_id' => $periodoId,
                'Cursos.trayecto_id' => $trayectoOrigenId,
                'Cursos.activo' => 1,
            ])
            ->extract('id')
            ->toArray();

        $estudiantes = [];
        if (!empty($cursosOrigen)) {
            $inscritos = $this->EstudianteCursos->find()
                ->contain(['Estudiantes'])
                ->where(['EstudianteCursos.curso_id IN' => $cursosOrigen])
                ->group(['EstudianteCursos.estudiante_id'])
                ->toArray();

            $yaInscritos = $this->EstudianteCursos->find()
                ->where(['EstudianteCursos.curso_id' => $cursoId])
                ->extract('estudiante_id')
                ->toArray();

            $epTable = TableRegistry::getTableLocator()->get('EstudianteProgramas');

            foreach ($inscritos as $ec) {
                if (in_array($ec->estudiante_id, $yaInscritos)) {
                    continue;
                }
                $tienePrograma = $epTable->find()->where([
                    'EstudianteProgramas.estudiante_id' => $ec->estudiante_id,
                    'EstudianteProgramas.carrera_id' => $carreraId,
                    'EstudianteProgramas.activo' => 1,
                ])->count() > 0;

                $estudiantes[] = [
                    'id' => $ec->estudiante->id,
                    'cedula' => $ec->estudiante->cedula,
                    'nombre' => $ec->estudiante->nombres . ' ' . $ec->estudiante->apellidos,
                    'expediente' => $ec->estudiante->expediente,
                    'tiene_programa' => $tienePrograma,
                ];
            }
        }

        $this->response = $this->response->withType('application/json');
        $this->response = $this->response->withStringBody(json_encode(['estudiantes' => $estudiantes]));
        return $this->response;
    }

    public function procesarExcel()
    {
        $this->autoRender = false;

        $cursoId = $this->request->getData('curso_id');

        if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
            $errCode = isset($_FILES['archivo']) ? $_FILES['archivo']['error'] : -1;
            return $this->_jsonResponse(false, 'No se pudo subir el archivo. Codigo: ' . $errCode, [], []);
        }

        $tmpName = $_FILES['archivo']['tmp_name'];
        $originalName = $_FILES['archivo']['name'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (!in_array($ext, ['xlsx', 'xls'])) {
            return $this->_jsonResponse(false, 'Formato no soportado. Use .xlsx o .xls', [], []);
        }

        try {
            $spreadsheet = IOFactory::load($tmpName);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
        } catch (\Exception $e) {
            return $this->_jsonResponse(false, 'Error al leer el archivo: ' . $e->getMessage(), [], []);
        }

        if (count($rows) < 2) {
            return $this->_jsonResponse(false, 'El archivo está vacío o no tiene datos.', [], []);
        }

        $header = array_map('strtolower', array_map('trim', $rows[0]));
        $requeridas = ['cedula', 'nombres', 'apellidos', 'fecha_nacimiento', 'sexo', 'expediente'];
        $faltantes = array_diff($requeridas, $header);

        if (!empty($faltantes)) {
            return $this->_jsonResponse(false, 'Columnas faltantes: ' . implode(', ', $faltantes), [], []);
        }

        $colMap = [];
        foreach ($requeridas as $col) {
            $colMap[$col] = array_search($col, $header);
        }

        $cursosTable = TableRegistry::getTableLocator()->get('Cursos');
        $curso = $cursosTable->get($cursoId);
        $carreraId = $curso->carrera_id;

        $estudiantesTable = TableRegistry::getTableLocator()->get('Estudiantes');
        $epTable = TableRegistry::getTableLocator()->get('EstudianteProgramas');

        $yaInscritos = $this->EstudianteCursos->find()
            ->where(['EstudianteCursos.curso_id' => $cursoId])
            ->extract('estudiante_id')
            ->toArray();

        $validos = [];
        $rechazados = [];

        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            $fila = $i + 1;

            $cedulaRaw = $row[$colMap['cedula']] ?? '';
            $cedula = is_numeric($cedulaRaw) ? (int)$cedulaRaw : trim($cedulaRaw);
            $nombres = strtoupper(trim($row[$colMap['nombres']] ?? ''));
            $apellidos = strtoupper(trim($row[$colMap['apellidos']] ?? ''));
            $fechaNac = trim($row[$colMap['fecha_nacimiento']] ?? '');
            $sexo = strtoupper(trim($row[$colMap['sexo']] ?? ''));
            $expedienteRaw = $row[$colMap['expediente']] ?? '';
            $expediente = is_numeric($expedienteRaw) ? (int)$expedienteRaw : strtoupper(trim($expedienteRaw));

            if (empty($cedula) || $nombres === '' || $apellidos === '' || $fechaNac === '' || $sexo === '' || $expediente === '') {
                $campos = [];
                if (empty($cedula)) $campos[] = 'cedula';
                if ($nombres === '') $campos[] = 'nombres';
                if ($apellidos === '') $campos[] = 'apellidos';
                if ($fechaNac === '') $campos[] = 'fecha_nacimiento';
                if ($sexo === '') $campos[] = 'sexo';
                if ($expediente === '') $campos[] = 'expediente';
                $rechazados[] = ['fila' => $fila, 'cedula' => (string)$cedula, 'nombre' => "$nombres $apellidos", 'error' => 'Campos vacios: ' . implode(', ', $campos)];
                continue;
            }

            $estudiante = $estudiantesTable->find()->where(['cedula' => $cedula])->first();

            if (!$estudiante) {
                $rechazados[] = ['fila' => $fila, 'cedula' => (string)$cedula, 'nombre' => "$nombres $apellidos", 'error' => 'Cedula ' . $cedula . ' no encontrada en base de datos'];
                continue;
            }

            $errores = [];
            if (strtoupper(trim($estudiante->nombres)) !== $nombres) {
                $errores[] = 'nombres (DB: ' . strtoupper($estudiante->nombres) . ' | Excel: ' . $nombres . ')';
            }
            if (strtoupper(trim($estudiante->apellidos)) !== $apellidos) {
                $errores[] = 'apellidos (DB: ' . strtoupper($estudiante->apellidos) . ' | Excel: ' . $apellidos . ')';
            }
            $sexoDb = strtoupper(trim($estudiante->sexo));
            if ($sexoDb !== $sexo) {
                $errores[] = 'sexo (DB: ' . $sexoDb . ' | Excel: ' . $sexo . ')';
            }
            $expDb = is_numeric($estudiante->expediente) ? (int)$estudiante->expediente : strtoupper(trim($estudiante->expediente));
            if ($expDb !== $expediente) {
                $errores[] = 'expediente (DB: ' . $expDb . ' | Excel: ' . $expediente . ')';
            }

            $fechaDb = $estudiante->fecha_nacimiento instanceof \Cake\I18n\FrozenDate
                ? $estudiante->fecha_nacimiento->format('Y-m-d')
                : (string)$estudiante->fecha_nacimiento;
            $fechaExcel = $this->_normalizeDate($fechaNac);
            if ($fechaDb !== $fechaExcel) {
                $errores[] = 'fecha_nacimiento (DB: ' . $fechaDb . ' | Excel: ' . $fechaExcel . ')';
            }

            if (!empty($errores)) {
                $rechazados[] = ['fila' => $fila, 'cedula' => (string)$cedula, 'nombre' => $estudiante->nombres . ' ' . $estudiante->apellidos, 'error' => 'Datos no coinciden: ' . implode(' | ', $errores)];
                continue;
            }

            $tienePrograma = $epTable->find()->where([
                'EstudianteProgramas.estudiante_id' => $estudiante->id,
                'EstudianteProgramas.carrera_id' => $carreraId,
                'EstudianteProgramas.activo' => 1,
            ])->count() > 0;

            if (!$tienePrograma) {
                $rechazados[] = ['fila' => $fila, 'cedula' => (string)$cedula, 'nombre' => $estudiante->nombres . ' ' . $estudiante->apellidos, 'error' => 'Sin programa activo en carrera #' . $carreraId];
                continue;
            }

            if (in_array($estudiante->id, $yaInscritos)) {
                $rechazados[] = ['fila' => $fila, 'cedula' => (string)$cedula, 'nombre' => $estudiante->nombres . ' ' . $estudiante->apellidos, 'error' => 'Ya inscrito en este curso'];
                continue;
            }

            $validos[] = [
                'estudiante_id' => $estudiante->id,
                'cedula' => (string)$estudiante->cedula,
                'nombre' => $estudiante->nombres . ' ' . $estudiante->apellidos,
                'expediente' => $estudiante->expediente,
            ];
        }

        $logDir = ROOT . DS . 'tmp' . DS . 'logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0777, true);
        }
        $logFile = $logDir . DS . 'excel_import_' . date('Ymd_His') . '.log';
        $log = "=== PROCESAMIENTO EXCEL ===\n";
        $log .= "Archivo: $originalName\n";
        $log .= "Curso: #$cursoId (Carrera: #$carreraId)\n";
        $log .= "Total filas (sin header): " . (count($rows) - 1) . "\n";
        $log .= "Validos: " . count($validos) . "\n";
        $log .= "Rechazados: " . count($rechazados) . "\n\n";
        foreach ($rechazados as $r) {
            $log .= "Fila {$r['fila']} | Cedula: {$r['cedula']} | {$r['nombre']} | ERROR: {$r['error']}\n";
        }
        @file_put_contents($logFile, $log);

        return $this->_jsonResponse(true, 'Procesado', $validos, $rechazados);
    }

    public function registrarLote()
    {
        $this->request->allowMethod(['ajax', 'post']);
        $this->autoRender = false;

        $cursoId = $this->request->getData('curso_id');
        $estudianteIds = $this->request->getData('estudiante_ids') ?? [];
        $responsable = $this->_getUsuarioActual();

        if (empty($estudianteIds)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'Debe seleccionar al menos un estudiante.',
                ]));
        }

        $cursosTable = TableRegistry::getTableLocator()->get('Cursos');
        $curso = $cursosTable->get($cursoId);
        $inscritos = 0;
        $yaInscrito = 0;
        $sinCupos = 0;
        $errores = [];

        foreach ($estudianteIds as $estudianteId) {
            $existe = $this->EstudianteCursos->find()
                ->where([
                    'EstudianteCursos.curso_id' => $cursoId,
                    'EstudianteCursos.estudiante_id' => $estudianteId,
                ])->count();

            if ($existe > 0) {
                $yaInscrito++;
                continue;
            }

            $ocupados = $this->EstudianteCursos->find()
                ->where([
                    'EstudianteCursos.curso_id' => $cursoId,
                    'EstudianteCursos.activo' => 1,
                ])->count();

            if ($ocupados >= $curso->cupos) {
                $sinCupos++;
                continue;
            }

            $entity = $this->EstudianteCursos->newEntity();
            $entity->curso_id = $cursoId;
            $entity->estudiante_id = $estudianteId;
            $entity->responsable = $responsable;
            $entity->activo = 1;

            if ($this->EstudianteCursos->save($entity)) {
                $this->Auditorias->registrar('REGISTRA', "INSCRIBE ESTUDIANTE #$estudianteId EN CURSO #$cursoId");
                $inscritos++;
            } else {
                $errores[] = "Error al inscribir estudiante #$estudianteId";
            }
        }

        $mensaje = "$inscritos curso(s) inscrito(s) correctamente.";
        if ($yaInscrito > 0) {
            $mensaje .= " $yaInscrito ya estaba(n) inscrito(s).";
        }
        if ($sinCupos > 0) {
            $mensaje .= " $sinCupos sin cupos disponibles.";
        }

        $this->response = $this->response->withType('application/json');
        $this->response = $this->response->withStringBody(json_encode([
            'success' => $inscritos > 0,
            'message' => $mensaje,
            'inscritos' => $inscritos,
            'ya_inscrito' => $yaInscrito,
            'sin_cupos' => $sinCupos,
            'errores' => $errores,
        ]));
        return $this->response;
    }

    private function _jsonResponse($success, $message, $validos, $rechazados)
    {
        $this->response = $this->response->withType('application/json');
        $this->response = $this->response->withStringBody(json_encode([
            'success' => $success,
            'message' => $message,
            'validos' => $validos,
            'rechazados' => $rechazados,
            'total_validos' => count($validos),
            'total_rechazados' => count($rechazados),
        ]));
        return $this->response;
    }

    private function _normalizeDate($dateStr)
    {
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateStr)) {
            return $dateStr;
        }
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dateStr)) {
            $parts = explode('/', $dateStr);
            return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        }
        if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $dateStr)) {
            $parts = explode('-', $dateStr);
            return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        }
        return $dateStr;
    }

    private function _getUsuarioActual()
    {
        $user = $this->Auth->user();
        if ($user) {
            $usuariosTable = TableRegistry::getTableLocator()->get('Usuarios');
            $usuario = $usuariosTable->get($user['id']);
            return $usuario->alias;
        }
        return '';
    }
}
