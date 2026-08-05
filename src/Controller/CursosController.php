<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Lib\CierreActas;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * Cursos Controller
 *
 * @property \App\Model\Table\CursosTable $Cursos
 *
 * @method \App\Model\Entity\Curso[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
*/
class CursosController extends AppController
{

	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
	}

	public function isAuthorized($user = null)
	{
        if( isset( $user['activo'] ) && isset( $user['rols'] ) && $user['activo'] )
        {
            if ($this->tienePermiso([2,3])) 
            {
                return true;
            }            
        }
		return parent::isAuthorized($user);
	}
	
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
    */
    public function index()
    {
        $conditions = $this->Cursos->formatConditions($this->request->getQueryParams());
        $this->paginate = [
            'contain' => ['Sedes', 'Periodos', 'Carreras', 'Trayectos', 'Asignaturas', 'Docentes', 'Aulas'],
            'conditions' => $conditions,
        ];
        $cursos = $this->paginate($this->Cursos,['order' => ['Cursos.id' => 'DESC']]);
        $filtros = $this->request->getQuery();
        $searchFields = $this->Cursos->getSearchFields();

        $searchFields['sede_id']['options'] = $this->Cursos->Sedes->find('list')->where(['activa' => 1])->order(['id' => 'ASC'])->toArray();
        $searchFields['periodo_id']['options'] = $this->Cursos->Periodos->find('list')->where(['activo' => 1])->order(['id' => 'DESC'])->toArray();
        $searchFields['carrera_id']['options'] = $this->Cursos->Carreras->find('list')->where(['activa' => 1])->order(['id' => 'DESC'])->toArray();
        $searchFields['trayecto_id']['options'] = $this->Cursos->Trayectos->find('list')->where(['activo' => 1])->toArray();
        $searchFields['asignatura_id']['options'] = $this->Cursos->Asignaturas->find('list')->where(['activa' => 1])->toArray();
        $searchFields['docente_id']['options'] = $this->Cursos->Docentes->find('list')->where(['activo' => 1])->toArray();

        $this->set(compact('cursos', 'filtros', 'searchFields'));
    }

    /**
     * View method
     *
     * @param string|null $id Curso id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $curso = $this->Cursos->get($id, [
            'contain' => ['Sedes', 'Periodos', 'Carreras', 'Trayectos', 'Asignaturas', 'Docentes', 'Aulas',
                'IndicadorCursos'],
        ]);

        $nombresProgramas = [];
        if (!empty($curso->programas)) {
            $programasTable = TableRegistry::getTableLocator()->get('Programas');
            $ids = explode(' ', $curso->programas);
            $programasList = $programasTable->find('list')
                ->where(['id IN' => $ids])
                ->toArray();
            foreach ($ids as $idP) {
                if (isset($programasList[$idP])) {
                    $nombresProgramas[] = $programasList[$idP];
                }
            }
        }
        $curso->programas = implode(', ', $nombresProgramas);

        $estudianteCursosTable = TableRegistry::getTableLocator()->get('EstudianteCursos');
        $query = $estudianteCursosTable->find()
            ->contain(['Estudiantes'])
            ->where(['EstudianteCursos.curso_id' => $id])
            ->order(['Estudiantes.apellidos' => 'ASC', 'Estudiantes.nombres' => 'ASC']);

        $estudianteCursos = $this->paginate($query);

        $nTipoCalificacion = (int)($curso->asignatura->calificacion ?? 0);
        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Cursos ' . json_encode($curso->toArray()));
        $this->set('curso', $curso);
        $this->set('estudianteCursos', $estudianteCursos);
        $this->set('nTotalEstudiantes', $estudianteCursosTable->find()->where(['curso_id' => $id])->count());
        $this->set('nTipoCalificacion', $nTipoCalificacion);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $curso = $this->Cursos->newEntity();
        if ($this->request->is('post')) 
        {
            $data = $this->request->getData();
            if (!empty($data['profesores']) && is_array($data['profesores'])) 
            {
                $data['profesores'] = implode(' ', $data['profesores']);
            }
            if (!empty($data['horario']) && is_array($data['horario'])) 
            {
                $data['horario'] = implode(' ', $data['horario']);
            }
            $curso = $this->Cursos->patchEntity($curso, $data);
            if ($this->Cursos->save($curso)) 
            {
                $this->Flash->success(__('The {0} has been saved.', 'Curso'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Cursos ' . json_encode($data));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Curso'));
        }
        $sedes = $this->Cursos->Sedes->find('list')->where(['activa' => 1])->order(['id' => 'ASC']);
        $periodos = $this->Cursos->Periodos->find('list')->where(['activo' => 1])->order(['id' => 'DESC']);
        $carreras = $this->Cursos->Carreras->find('list')->where(['activa' => 1])->order(['id' => 'ASC']);
        $trayectos = $this->Cursos->Trayectos->find('list')->where(['activo' => 1]);
        $asignaturas = $this->Cursos->Asignaturas->find('list')->where(['activa' => 1]);
        $docentes = $this->Cursos->Docentes->find('list')->where(['activo' => 1]);
        $profesores = $this->Cursos->Docentes->find('list', [
            'keyField' => 'cedula',
            'valueField' => 'codename'
        ])->where(['activo' => 1])->toArray();

        $aulas = [];
        $horariosData = $this->_getHorariosAll();
        $horarios = $horariosData['simple'];
        $horariosJson = json_encode($horariosData['full']);
        $this->set(compact('curso', 'sedes', 'periodos', 'carreras', 'trayectos', 'asignaturas', 'docentes', 'aulas',
            'horarios', 'profesores', 'horariosJson')
        );
    }

    /**
     * Edit method
     *
     * @param string|null $id Curso id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $curso = $this->Cursos->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) 
        {
            $data = $this->request->getData();
            if (!empty($data['profesores']) && is_array($data['profesores'])) 
            {
                $data['profesores'] = implode(' ', $data['profesores']);
            }
            if (!empty($data['horario']) && is_array($data['horario'])) 
            {
                $data['horario'] = implode(' ', $data['horario']);
            }
            $curso = $this->Cursos->patchEntity($curso, $data);
            if ($this->Cursos->save($curso)) 
            {
                $this->Flash->success(__('The {0} has been saved.', 'Curso'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Cursos ' . json_encode($data));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Curso'));
        } else {
			$aProfesores = !empty($curso->profesores) ? explode(' ', $curso->profesores) : [];
			$aHorarios = !empty($curso->horario) ? explode(' ', $curso->horario) : [];
            $this->request = $this->request->withData('profesores', $aProfesores)
                ->withData('horario', $aHorarios);
        }
        $sedes = $this->Cursos->Sedes->find('list', ['limit' => 200])->where(['activa' => 1])->order(['id' => 'ASC']);
        $periodos = $this->Cursos->Periodos->find('list', ['limit' => 200])->where(['activo' => 1])->order(['id' => 'DESC']);
        $carreras = $this->Cursos->Carreras->find('list', ['limit' => 200])->where(['activa' => 1])->order(['id' => 'ASC']);
        $trayectos = $this->Cursos->Trayectos->find('list', ['limit' => 200])->where(['activo' => 1]);
        $asignaturas = $this->Cursos->Asignaturas->find('list')->where(['activa' => 1]);
        $docentes = $this->Cursos->Docentes->find('list')->where(['activo' => 1]);
        $profesores = $this->Cursos->Docentes->find('list', [
            'keyField' => 'cedula',
            'valueField' => 'codename'
        ])->where(['activo' => 1])->toArray();

        $aulas = [];
        $horariosData = $this->_getHorariosAll();
        $horarios = $horariosData['simple'];
        $horariosJson = json_encode($horariosData['full']);
        $this->set(compact('curso', 'sedes', 'periodos', 'carreras', 'trayectos', 'asignaturas', 'docentes', 'aulas', 'horarios', 'profesores', 'horariosJson'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Curso id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $curso = $this->Cursos->get($id);
        if ($this->Cursos->delete($curso)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Curso'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Cursos ' . json_encode($curso->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Curso'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function calificar()
    {
        $nCursoId = $this->request->getQuery('nCursoId') ?: $this->request->getData('nCursoId');
        $sNota = $this->request->getQuery('sNota') ?: $this->request->getData('sNota');

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
                $entity->responsable = $this->Auth->user('alias');

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
            ->where(['EstudianteCursos.curso_id' => $nCursoId, 'EstudianteCursos.activo' => 1])
            ->order(['Estudiantes.apellidos' => 'ASC', 'Estudiantes.nombres' => 'ASC']);

        $estudianteCursos = $query->toArray();

        $this->set(compact('estudianteCursos', 'sNota', 'nTipoCalificacion', 'nNotaMinima', 'bReadonly'));
        $this->set('curso', $oCurso);
    }

    /**
     * Cierre masivo de actas de notas (UI).
     *
     * GET: muestra los cursos de un periodo con su estado.
     * POST/AJAX: cierra el acta de un curso o de todos los cursos pendientes de un periodo.
     *
     * @return \Cake\Http\Response|null
     */
    public function cierreActas()
    {
        $periodosTable = TableRegistry::getTableLocator()->get('Periodos');

        if ($this->request->is(['post', 'ajax'])) {
            $this->request->allowMethod(['post', 'ajax']);
            $this->autoRender = false;
            $this->response = $this->response->withType('application/json');

            $nPeriodoId = (int)$this->request->getData('periodo_id');
            $nCursoId = (int)$this->request->getData('curso_id');
            $nDocenteId = (int)$this->request->getData('docente_id');
            $bDryRun = (bool)$this->request->getData('dry_run');

            try {
                if ($nCursoId) {
                    $aResultado = CierreActas::cerrarCurso($nCursoId, $bDryRun);
                    $this->Auditorias->registrar(
                        'CIERRA ACTA',
                        "Cierre de acta del curso #{$nCursoId} ({$aResultado['asignatura']} - Sección {$aResultado['seccion']}): " .
                        "estado {$aResultado['estado']}, actualizados {$aResultado['actualizados']}, con total {$aResultado['con_total']}"
                    );

                    return $this->response->withStringBody(json_encode([
                        'success' => true,
                        'message' => $this->_mensajeCurso($aResultado),
                        'resultado' => $aResultado,
                    ]));
                }

                if ($nPeriodoId) {
                    $aResultado = CierreActas::cerrarPeriodo($nPeriodoId, $bDryRun, false, null, $nDocenteId ?: null);
                    $s = $aResultado['resumen'];
                    $this->Auditorias->registrar(
                        'CIERRA ACTA',
                        "Cierre masivo de actas del periodo #{$nPeriodoId} ({$aResultado['periodo']['codigo']}): " .
                        "cursos {$s['total_cursos']}, cerrados {$s['cerrados']}, ya cerrados {$s['ya_cerrados']}, " .
                        "saltados {$s['saltados']}, actualizados {$s['actualizados']}"
                    );

                    return $this->response->withStringBody(json_encode([
                        'success' => true,
                        'message' => $this->_mensajePeriodo($aResultado),
                        'resultado' => $aResultado,
                    ]));
                }

                return $this->response->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'Debe indicar un periodo o un curso.',
                ]));
            } catch (\Exception $e) {
                return $this->response->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'Error del servidor: ' . $e->getMessage(),
                ]));
            }
        }

        $periodos = $periodosTable->find('list', [
            'keyField' => 'id',
            'valueField' => 'codename',
        ])
        ->where(['Periodos.activo' => 1, 'Periodos.califica' => 1])
        ->order(['Periodos.id' => 'DESC'])
        ->toArray();

        $periodoId = $this->request->getQuery('periodo_id');
        if (!$periodoId || !isset($periodos[$periodoId])) {
            $periodoId = $periodos ? array_key_first($periodos) : null;
        }

        $cursos = [];
        $aInfo = [];
        $aDocentes = [];
        $nTotalCursos = 0;
        $nPendientesPeriodo = 0;
        $oPeriodo = null;
        $docenteId = null;
        if ($periodoId) {
            $oPeriodo = $periodosTable->get($periodoId);

            $cursosTable = TableRegistry::getTableLocator()->get('Cursos');

            $docentesTable = TableRegistry::getTableLocator()->get('Docentes');
            $aDocenteIdList = $cursosTable->find()
                ->select(['docente_id'])
                ->where(['periodo_id' => $periodoId, 'docente_id IS NOT' => null])
                ->distinct()
                ->extract('docente_id')
                ->toArray();
            if (!empty($aDocenteIdList)) {
                foreach ($docentesTable->find()
                    ->select(['Docentes.id', 'Docentes.cedula', 'Docentes.nombres', 'Docentes.apellidos'])
                    ->where(['Docentes.activo' => 1, 'Docentes.id IN' => $aDocenteIdList])
                    ->order(['Docentes.apellidos' => 'ASC', 'Docentes.nombres' => 'ASC'])
                    ->toArray() as $oDocente) {
                    $aDocentes[$oDocente->id] = $oDocente->name;
                }
            }

            $sDocenteId = $this->request->getQuery('docente_id');
            if ($sDocenteId && isset($aDocentes[(int)$sDocenteId])) {
                $docenteId = (int)$sDocenteId;
            }

            $aCond = ['Cursos.periodo_id' => $periodoId];
            if ($docenteId) {
                $aCond['Cursos.docente_id'] = $docenteId;
            }

            $aPeriodoCursos = $cursosTable->find()
                ->select(['id', 'cerrado'])
                ->where($aCond)
                ->toArray();
            $nTotalCursos = count($aPeriodoCursos);

            $aPeriodoCursoIds = [];
            $aCerrado = [];
            foreach ($aPeriodoCursos as $oCursoBasico) {
                $aPeriodoCursoIds[] = (int)$oCursoBasico->id;
                $aCerrado[(int)$oCursoBasico->id] = (int)$oCursoBasico->cerrado;
            }

            if (!empty($aPeriodoCursoIds)) {
                $ecTable = TableRegistry::getTableLocator()->get('EstudianteCursos');
                $aConteoEstudiantes = $ecTable->find()
                    ->select(['curso_id', 'total' => $ecTable->find()->func()->count('*')])
                    ->where(['curso_id IN' => $aPeriodoCursoIds, 'activo' => 1])
                    ->group('curso_id')
                    ->toArray();
                $aEstudiantesPorCurso = [];
                foreach ($aConteoEstudiantes as $fila) {
                    $aEstudiantesPorCurso[(int)$fila->curso_id] = (int)$fila->total;
                }

                $icTable = TableRegistry::getTableLocator()->get('IndicadorCursos');
                $aIndicadoresPorCurso = [];
                foreach ($icTable->find()
                    ->select(['curso_id', 'id'])
                    ->where(['curso_id IN' => $aPeriodoCursoIds])
                    ->toArray() as $oInd) {
                    $aIndicadoresPorCurso[$oInd->curso_id][] = $oInd->id;
                }

                $ccTable = TableRegistry::getTableLocator()->get('ContenidoCursos');
                $aEvalPorCurso = [];
                foreach ($aIndicadoresPorCurso as $nCurso => $aIndIds) {
                    $aEvalPorCurso[$nCurso] = $ccTable->find()
                        ->where(['indicador_curso_id IN' => $aIndIds])
                        ->count();
                }

                foreach ($aPeriodoCursoIds as $nCursoId) {
                    $nEstudiantes = $aEstudiantesPorCurso[$nCursoId] ?? 0;
                    $nEvaluaciones = $aEvalPorCurso[$nCursoId] ?? 0;
                    $aInfo[$nCursoId] = [
                        'n_estudiantes' => $nEstudiantes,
                        'n_evaluaciones' => $nEvaluaciones,
                    ];
                    if ($aCerrado[$nCursoId] === 0 && $nEvaluaciones > 0 && $nEstudiantes > 0) {
                        $nPendientesPeriodo++;
                    }
                }
            }

            $query = $cursosTable->find()
                ->where($aCond)
                ->contain(['Asignaturas', 'Docentes', 'Periodos'])
                ->order(['Cursos.id' => 'ASC']);

            $cursos = $this->paginate($query, ['limit' => 50]);
        }

        $this->set(compact('periodos', 'periodoId', 'docenteId', 'aDocentes', 'oPeriodo', 'cursos', 'aInfo', 'nTotalCursos', 'nPendientesPeriodo'));
    }

    /**
     * @param array $aR
     * @return string
     */
    private function _mensajeCurso(array $aR)
    {
        if ($aR['estado'] === 'OK' || $aR['estado'] === 'PARCIAL') {
            return "Acta del curso #{$aR['curso_id']} ({$aR['asignatura']} - Sección {$aR['seccion']}) cerrada. " .
                "Se actualizó la calificación de {$aR['actualizados']} estudiante(s). " .
                ($aR['sin_total'] ? "Sin total: {$aR['sin_total']} estudiante(s)." : '');
        }

        return "Curso #{$aR['curso_id']}: {$aR['motivo']}";
    }

    /**
     * @param array $aResultado
     * @return string
     */
    private function _mensajePeriodo(array $aResultado)
    {
        $s = $aResultado['resumen'];

        return "Cierre del periodo {$aResultado['periodo']['codigo']} completado: " .
            "{$s['cerrados']} curso(s) cerrado(s), {$s['ya_cerrados']} ya cerrado(s), " .
            "{$s['saltados']} sin datos, {$s['actualizados']} calificación(es) actualizada(s).";
    }

    /**
     * Get programas by carrera_id (AJAX)
     *
     * @return \Cake\Http\Response
    */
    public function getProgramas()
    {
        $this->request->allowMethod(['ajax', 'get']);
        $this->autoRender = false;
        $carrera_id = $this->request->getQuery('carrera_id');

        $programas = [];
        if ($carrera_id) {
            $programasTable = TableRegistry::getTableLocator()->get('Programas');
            $programas = $programasTable->find('list', ['limit' => 200])
                ->where(['carrera_id' => $carrera_id, 'activo' => 1])
                ->toArray();
        }

        $this->response->withType('application/json');
        $this->response = $this->response->withStringBody(json_encode(['programas' => $programas]));
        return $this->response;
    }

    /**
     * Get asignaturas by programa_id + trayecto_id from Mallas (AJAX)
     *
     * @return \Cake\Http\Response
    */
    public function getAsignaturas()
    {
        $this->request->allowMethod(['ajax', 'get']);
        $this->autoRender = false;
        $programa_ids = $this->request->getQuery('programa_ids');
        $trayecto_id = $this->request->getQuery('trayecto_id');

        $asignaturas = [];
        if (!empty($programa_ids) && $trayecto_id) {
            $programa_ids = is_array($programa_ids) ? $programa_ids : explode(',', $programa_ids);
            $asignaturas = $this->Cursos->Asignaturas->find('list', ['limit' => 200])
                ->matching('Mallas', function ($q) use ($programa_ids, $trayecto_id) {
                    return $q->where([
                        'Mallas.programa_id IN' => $programa_ids,
                        'Mallas.trayecto_id' => $trayecto_id,
                    ]);
                })
                ->where(['Asignaturas.activa' => 1])
                ->toArray();
        }

        $this->response = $this->response->withType('application/json');
        $this->response = $this->response->withStringBody(json_encode(['asignaturas' => $asignaturas]));
        return $this->response;
    }

    /**
     * Get horarios by sede_id + periodo_id (AJAX)
     *
     * @return \Cake\Http\Response
    */
    public function getHorarios()
    {
        $this->request->allowMethod(['ajax', 'get']);
        $this->autoRender = false;
        $sede_id = $this->request->getQuery('sede_id');
        $periodo_id = $this->request->getQuery('periodo_id');

        $horarios = [];
        if ($sede_id && $periodo_id) {
            $horariosTable = TableRegistry::getTableLocator()->get('Horarios');
            $horarios = $horariosTable->find('list', [
                'keyField' => 'codigo',
                'valueField' => 'codigo'
            ])->where([
                'sede_id' => $sede_id,
                'periodo_id' => $periodo_id,
                'activo' => 1
            ])->order(['Horarios.dia', 'Horarios.desde'])->toArray();
        }

        $this->response = $this->response->withType('application/json');
        $this->response = $this->response->withStringBody(json_encode(['horarios' => $horarios]));
        return $this->response;
    }

    public function getAulas()
    {
        $this->request->allowMethod(['ajax', 'get']);
        $this->autoRender = false;
        $sede_id = $this->request->getQuery('sede_id');

        $aulas = [];
        if ($sede_id) {
            $aulas = $this->Cursos->Aulas->find('list', ['limit' => 200])
                ->where(['sede_id' => $sede_id, 'condicion' => 1])
                ->toArray();
        }

        $this->response = $this->response->withType('application/json');
        $this->response = $this->response->withStringBody(json_encode(['aulas' => $aulas]));
        return $this->response;
    }

    private function _getHorariosAll()
    {
        $horariosTable = TableRegistry::getTableLocator()->get('Horarios');
        $year = date('Y');

        $simple = $horariosTable->find('list', [
            'keyField' => 'codigo',
            'valueField' => 'codigo',
        ])->where([
            'YEAR(created)' => $year,
            'activo' => 1,
        ])->order(['dia' => 'ASC', 'desde' => 'ASC'])->toArray();

        $query = $horariosTable->find()
            ->select(['codigo', 'sede_id', 'periodo_id'])
            ->where([
                'YEAR(created)' => $year,
                'activo' => 1,
            ])
            ->order(['dia' => 'ASC', 'desde' => 'ASC'])
            ->all();

        $full = [];
        foreach ($query as $row) {
            $full[$row->codigo] = [
                'codigo' => $row->codigo,
                'sede_id' => (string)$row->sede_id,
                'periodo_id' => (string)$row->periodo_id,
            ];
        }

        return ['simple' => $simple, 'full' => $full];
    }

}
