<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Lib\NotasCalculador;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Cake\Event\Event;

/**
 * Datos Controller
 *
 * @method \App\Model\Entity\Dato[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
*/
class DatosController extends AppController
{

	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
	}

	public function isAuthorized($user = null)
	{
        if( isset( $user['activo'] ) && isset( $user['rols'] ) && $user['activo'] && $this->tienePermiso([2,3]) )
        {
            return true;
        }
		return parent::isAuthorized($user);
	}

    public function index()
    {
    }

    public function students()
    {
        $this->loadModel('Estudiantes');
        $conditions = $this->Estudiantes->formatConditions($this->request->getQueryParams());
        $this->paginate = [
            'conditions' => $conditions,
        ];
        $estudiantes = $this->paginate($this->Estudiantes, ['order' => ['Estudiantes.cedula' => 'ASC']]);

        if ($estudiantes->count() == 1) {
            return $this->redirect(['action' => 'estudiante', $estudiantes->first()->id]);
        }

        $filtros = $this->request->getQuery();
        $searchFields = $this->Estudiantes->getSearchFields();

        $this->set(compact('estudiantes', 'filtros', 'searchFields'));
    }

    public function estudiante($id)
    {
        $estudiante = TableRegistry::getTableLocator()->get('Estudiantes')->get($id,[
            'contain' => ['Paises', 'Estados', 'Municipios', 'Parroquias', 'Usuarios', 'EstudianteCursos', 'EstudianteProgramas', 
            'Graduandos', 'Historicos', 'CursoNotas', 'SituacionEstudiantes'],
        ]);
        $aGeneros = Configure::read('aGeneros');
        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Estudiantes ' . json_encode($estudiante->toArray()));

        $this->set(compact('aGeneros'));
        $this->set('estudiante', $estudiante);
    }

    public function rendimiento(){}

    /**
     * Consulta las notas por periodo del estudiante (vista Ficha -> Notas de Lapso).
     *
     * @param int|null $id
     * @return \Cake\Http\Response|null
     */
    public function evaluaciones($id = null)
    {
        if (!$id) {
            $this->Flash->error(__('No se especificó el estudiante.'));
            return $this->redirect(['action' => 'index']);
        }

        $estudiantesTable = TableRegistry::getTableLocator()->get('Estudiantes');
        $estudiante = $estudiantesTable->get($id, ['contain' => ['Usuarios']]);

        if (!$estudiante) {
            $this->Flash->error(__('No se encontró el estudiante.'));
            return $this->redirect(['action' => 'index']);
        }

        $estudianteCursosTable = TableRegistry::getTableLocator()->get('EstudianteCursos');
        $ecs = $estudianteCursosTable->find()
            ->where([
                'EstudianteCursos.estudiante_id' => $id,
                'Cursos.activo' => 1,
            ])
            ->contain(['Cursos' => ['Asignaturas', 'Periodos']])
            ->order(['Cursos.periodo_id' => 'DESC', 'Cursos.id' => 'ASC'])
            ->toArray();

        $indicadorCursosTable = TableRegistry::getTableLocator()->get('IndicadorCursos');
        $cursoNotasTable = TableRegistry::getTableLocator()->get('CursoNotas');

        $aProgramaIds = [];
        foreach ($ecs as $ec) {
            if (!empty($ec->curso->programas)) {
                foreach (array_filter(explode(' ', $ec->curso->programas)) as $nPid) {
                    $aProgramaIds[] = (int)$nPid;
                }
            }
        }
        $aProgramaIds = array_unique($aProgramaIds);

        $aProgramas = [];
        if (!empty($aProgramaIds)) {
            $programasTable = TableRegistry::getTableLocator()->get('Programas');
            $aProgramas = $programasTable->find('list', ['keyField' => 'id', 'valueField' => 'codigo'])
                ->where(['Programas.id IN' => $aProgramaIds])
                ->toArray();
        }

        $periodos = [];
        foreach ($ecs as $ec) {
            $curso = $ec->curso;

            $sProgramaCodigo = '';
            if (!empty($curso->programas)) {
                $aIds = array_filter(explode(' ', $curso->programas));
                $nPid = (int)reset($aIds);
                $sProgramaCodigo = isset($aProgramas[$nPid]) ? $aProgramas[$nPid] : '';
            }

            $contenidoIds = [];
            $aContenidos = [];
            $indicadores = $indicadorCursosTable->find()
                ->where(['curso_id' => $curso->id])
                ->contain(['ContenidosCursos'])
                ->toArray();

            foreach ($indicadores as $ind) {
                if (empty($ind->contenidos_cursos)) {
                    continue;
                }
                foreach ($ind->contenidos_cursos as $cc) {
                    if ((int)$cc->activo === 1) {
                        $contenidoIds[] = $cc->id;
                        $cc->indicador_curso = $ind;
                        $aContenidos[] = $cc;
                    }
                }
            }

            $notas = [];
            if (!empty($contenidoIds)) {
                $notas = $cursoNotasTable->find()
                    ->where([
                        'CursoNotas.estudiante_id' => $id,
                        'CursoNotas.contenido_curso_id IN' => $contenidoIds,
                    ])
                    ->contain(['ContenidoCursos' => ['IndicadorCursos']])
                    ->order(['ContenidoCursos.fecha' => 'ASC'])
                    ->toArray();
            }

            $aNotasMap = [];
            foreach ($notas as $oNota) {
                $aNotasMap[(int)$oNota->estudiante_id][(int)$oNota->contenido_curso_id] = $oNota->calificacion;
            }

            $oResumen = null;
            if (!empty($aContenidos)) {
                $aTotales = NotasCalculador::calcularTotales(
                    (int)$curso->asignatura->calificacion,
                    $aContenidos,
                    $aNotasMap
                );
                if (isset($aTotales[$id])) {
                    $oResumen = $aTotales[$id];
                }
            }

            $periodoId = $curso->periodo_id;
            if (!isset($periodos[$periodoId])) {
                $periodos[$periodoId] = [
                    'periodo' => $curso->periodo,
                    'cursos' => [],
                ];
            }
            $periodos[$periodoId]['cursos'][] = [
                'ec' => $ec,
                'curso' => $curso,
                'notas' => $notas,
                'programa_codigo' => $sProgramaCodigo,
                'total' => $oResumen ? $oResumen['total'] : null,
                'final' => $oResumen ? $oResumen['final'] : null,
                'por_indicador' => $oResumen ? $oResumen['porIndicador'] : [],
            ];
        }

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LAS NOTAS DE LAPSO DE LA FICHA Estudiantes ' . json_encode($estudiante->toArray()));
        $this->set('title', 'Notas de Lapso');
        $this->set(compact('estudiante', 'periodos'));
        $this->viewBuilder()->setLayout('ajax');
    }

    public function programas($estudianteId = null)
    {
        $programas = TableRegistry::getTableLocator()->get('EstudianteProgramas')->find('all', [
            'conditions' => ['EstudianteProgramas.estudiante_id' => $estudianteId, 'EstudianteProgramas.congelado' => 0],
            'contain' => ['Estudiantes', 'Carreras', 'Programas', 'Sedes'] 
        ])
        //->where(['EstudianteProgramas.estudiante_id' => $estudianteId])
        ->toArray();

        $situacionEstudiantesTable = TableRegistry::getTableLocator()->get('SituacionEstudiantes');
        foreach ($programas as $programa) {
            $situacionEstudiantesTable->registrarDesdeMalla(
                $estudianteId,
                $programa->programa_id,
                $programa->carrera_id,
                $programa->periodo_id
            );
        }

        $this->set(compact('programas', 'estudianteId'));
        $this->set('_serialize', ['programas']); // Para que se pueda serializar a JSON si se requiere
        $this->viewBuilder()->setLayout('ajax'); // Usar un layout vacío para las llamadas AJAX
    }

    public function situacion($estudianteId = null, $programaId = null)
    {
        $estudiantesTable = TableRegistry::getTableLocator()->get('Estudiantes');
        $estudiante = $estudiantesTable->get($estudianteId);

        $programasTable = TableRegistry::getTableLocator()->get('EstudianteProgramas');
        $programasQuery = $programasTable->find()
            ->where(['EstudianteProgramas.estudiante_id' => $estudianteId])
            ->contain(['Carreras', 'Programas']);

        if ($programaId) {
            $programasQuery->where(['EstudianteProgramas.programa_id' => $programaId]);
        }

        $programas = $programasQuery->toArray();

        $situacionEstudiantesTable = TableRegistry::getTableLocator()->get('SituacionEstudiantes');
        foreach ($programas as $programa) {
            $situacionEstudiantesTable->registrarDesdeMalla(
                $estudianteId,
                $programa->programa_id,
                $programa->carrera_id,
                $programa->periodo_id
            );
        }

        $situaciones = [];
        foreach ($programas as $programa) {
            $asignaturasTable = TableRegistry::getTableLocator()->get('SituacionEstudiantes');
            $asignaturas = $asignaturasTable->find()
                ->where([
                    'SituacionEstudiantes.estudiante_id' => $estudianteId,
                    'SituacionEstudiantes.programa_id' => $programa->programa_id,
                ])
                ->contain(['Asignaturas', 'Trayectos', 'Periodos'])
                ->order(['SituacionEstudiantes.programa_id' => 'ASC', 'SituacionEstudiantes.trayecto_id' => 'ASC', 'SituacionEstudiantes.asignatura_id' => 'ASC'])
                ->toArray();

            $mallasTable = TableRegistry::getTableLocator()->get('Mallas');
            $mallas = $mallasTable->find()
                ->where(['Mallas.programa_id' => $programa->programa_id])
                ->toArray();
            $mallasPorAsignatura = [];
            foreach ($mallas as $m) {
                $mallasPorAsignatura[$m->asignatura_id] = $m;
            }

            $notaMinimaPrograma = (float)$programa->programa->nota_minima;
            $totalCreditosPrograma = (int)$programa->programa->creditos;
            $totalAsignaturas = count($asignaturas);
            $totalCreditosAprobados = 0;
            $totalAsignaturasAprobadas = 0;
            $isaNumerador = 0;
            $isaDenominador = 0;
            $iraNumerador = 0;
            $iraDenominador = 0;

            foreach ($asignaturas as $asig) {
                if (empty($asig->calificacion)) {
                    continue;
                }
                $esCualitativa = $asig->has('asignatura') && (int)$asig->asignatura->calificacion === 1;
                if ($esCualitativa) {
                    $aprobada = strtoupper($asig->calificacion) === 'A';
                    $notaISA = strtoupper($asig->calificacion) === 'A' ? 20 : 0;
                } else {
                    $notaMinima = $notaMinimaPrograma;
                    if (isset($mallasPorAsignatura[$asig->asignatura_id]) && !empty($mallasPorAsignatura[$asig->asignatura_id]->nota_minima)) {
                        $notaMinima = (float)$mallasPorAsignatura[$asig->asignatura_id]->nota_minima;
                    }
                    $aprobada = (float)$asig->calificacion >= $notaMinima;
                    $notaISA = (float)$asig->calificacion;
                }
                if ($aprobada) {
                    $totalCreditosAprobados += (int)$asig->asignatura->creditos;
                    $totalAsignaturasAprobadas++;
                }
                $creditosAsig = (int)$asig->asignatura->creditos;
                $isaNumerador += $notaISA * $creditosAsig;
                $isaDenominador += $creditosAsig;

                if (!empty($asig->acumulado) && (int)$asig->acumulado > 0) {
                    $iraNumerador += (int)$asig->acumulado;
                } else {
                    $notaIRA = $esCualitativa ? $notaISA : (float)$asig->calificacion;
                    $iraNumerador += $notaIRA * $creditosAsig;
                }
                $iraDenominador += $creditosAsig * (int)$asig->cursada;
            }

            $porcentajeAprobado = $totalCreditosPrograma > 0
                ? round(($totalCreditosAprobados / $totalCreditosPrograma) * 100, 1)
                : 0;

            $isa = $isaDenominador > 0 ? round($isaNumerador / $isaDenominador, 5) : 0;
            $ira = $iraDenominador > 0 ? round($iraNumerador / $iraDenominador, 5) : 0;

            $situaciones[] = [
                'programa' => $programa,
                'asignaturas' => $asignaturas,
                'mallasPorAsignatura' => $mallasPorAsignatura,
                'totalCreditosPrograma' => $totalCreditosPrograma,
                'totalAsignaturas' => $totalAsignaturas,
                'totalCreditosAprobados' => $totalCreditosAprobados,
                'totalAsignaturasAprobadas' => $totalAsignaturasAprobadas,
                'porcentajeAprobado' => $porcentajeAprobado,
                'isa' => $isa,
                'ira' => $ira,
            ];
        }
        $this->set('title', 'Situación');
        $this->set(compact('estudiante', 'situaciones'));
        $this->viewBuilder()->setLayout('ajax');
    }

    public function actualizarsituacion($id = null)
    {
        if (!$id) {
            if ($this->request->is('ajax')) {
                $this->response = $this->response
                    ->withType('json')
                    ->withStringBody(json_encode(['success' => false, 'message' => __('No se especificó el estudiante.')]));
                return $this->response;
            }
            $this->Flash->error(__('No se especificó el estudiante.'));
            return $this->redirect(['action' => 'index']);
        }

        $estudiantesTable = TableRegistry::getTableLocator()->get('Estudiantes');
        $estudiante = $estudiantesTable->get($id);

        $estudianteProgramasTable = TableRegistry::getTableLocator()->get('EstudianteProgramas');
        $programas = $estudianteProgramasTable->find()
            ->where(['estudiante_id' => $id, 'congelado' => 0])
            ->toArray();

        $situacionTable = TableRegistry::getTableLocator()->get('SituacionEstudiantes');

        $totalProgramas = count($programas);
        $totalActualizados = 0;

        foreach ($programas as $prog) {
            $situacionTable->registrarDesdeMalla(
                $id,
                $prog->programa_id,
                $prog->carrera_id,
                $prog->periodo_id
            );

            $totalActualizados += $situacionTable->sincronizarDesdeHistorico($id, $prog->programa_id);
        }

        if ($this->request->is('ajax')) {
            $this->response = $this->response
                ->withType('json')
                ->withStringBody(json_encode([
                    'success' => true,
                    'message' => __('Situación académica actualizada. Programas: {0}, Asignaturas actualizadas: {1}.', $totalProgramas, $totalActualizados),
                    'redirect' => \Cake\Routing\Router::url(['action' => 'estudiante', $id]),
                ]));
            return $this->response;
        }

        $this->Flash->success(__('Situación académica actualizada. Programas: {0}, Asignaturas actualizadas: {1}.', $totalProgramas, $totalActualizados));

        return $this->redirect(['action' => 'estudiante', $id]);
    }

    public function facilitadores()
    {
        $oTableDocentes = TableRegistry::getTableLocator()->get('Docentes');
        $conditions = $oTableDocentes->formatConditions($this->request->getQueryParams());

        $this->paginate = [
            'contain' => ['Departamentos', 'Usuarios','Cursos'],
            'conditions' => $conditions,
        ];

        $docentes = $this->paginate($oTableDocentes);
        $filtros = $this->request->getQuery();
        $searchFields = $oTableDocentes->getSearchFields();

        $this->set(compact('docentes', 'filtros', 'searchFields'));
    }

    public function facilitador(){}
}
