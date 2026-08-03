<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Lib\NotasCalculador;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Cake\Event\Event;

/**
 * Estudiantes Controller
 *
 * @property \App\Model\Table\EstudiantesTable $Estudiantes
 *
 * @method \App\Model\Entity\Estudiante[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
*/
class EstudiantesController extends AppController
{

	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
	}

	public function isAuthorized($user = null)
	{
        $aValues = $this->request->getParam('action');
		if (isset($user['activo']) && isset($user['rols']) && $user['activo'] ) 
        {
            if ( $this->tienePermiso([2,3]) ) 
            {
                return true;
            } elseif( $this->tienePermiso(9) && in_array($aValues,['situacion', 'notasLapso']) ) {
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
        $conditions = $this->Estudiantes->formatConditions($this->request->getQueryParams());
        $this->paginate = [
            'contain' => ['Paises', 'Estados', 'Municipios', 'Parroquias', 'Usuarios'],
            'conditions' => $conditions,
        ];
        $estudiantes = $this->paginate($this->Estudiantes);
        $filtros = $this->request->getQuery();
        $searchFields = $this->Estudiantes->getSearchFields();

        $this->set(compact('estudiantes', 'filtros', 'searchFields'));
    }

    /**
     * View method
     *
     * @param string|null $id Estudiante id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $estudiante = $this->Estudiantes->get($id, [
            'contain' => ['Paises', 'Estados', 'Municipios', 'Parroquias', 'Usuarios', 'EstudianteCursos', 'EstudianteProgramas', 
            'Graduandos', 'Historicos', 'CursoNotas', 'SituacionEstudiantes'],
        ]);
        $aGeneros = Configure::read('aGeneros');
        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Estudiantes ' . json_encode($estudiante->toArray()));

        $aPeriodo = TableRegistry::getTableLocator()->get('Periodos')->find()
            ->where(['Periodos.id' => $estudiante->periodo])
            ->first();
        $this->set(compact('aGeneros','aPeriodo'));
        $this->set('estudiante', $estudiante);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $estudiante = $this->Estudiantes->newEntity();
        if ($this->request->is('post')) 
        {
            $data = $this->request->getData();
            $estudiante = $this->Estudiantes->patchEntity($estudiante, $data);
            $this->Estudiantes->getConnection()->begin();
            if ($this->Estudiantes->save($estudiante)) 
            {
                $expediente = $this->Estudiantes->generarExpediente(
                    $estudiante->fecha_nacimiento,
                    $estudiante->periodo
                );
                $estudiante->expediente = $expediente;
                if ($this->Estudiantes->save($estudiante)) {
                    $this->Estudiantes->getConnection()->commit();
                    $this->Flash->success(__('The {0} has been saved.', 'Estudiante'));
                    $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Estudiantes ' . json_encode($data));

                    return $this->redirect(['action' => 'index']);
                }
            }
            $this->Estudiantes->getConnection()->rollback();
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Estudiante'));
        }
        $estados = [];
        $municipios = [];
        $parroquias = [];
        $sToken = $this->generateToken();
        $aOrigen = Configure::read('aTipoDoc');
        $aGenero = Configure::read('aGeneros');
        $aEdoCivil = Configure::read('aEstadoCivil');
        $aSedes = TableRegistry::getTableLocator()->get('Sedes')->find('list')->where(['Sedes.activa' => 1])->toArray();
        $aPeriodos = TableRegistry::getTableLocator()->get('Periodos')->find('list')->where(['Periodos.activo' => 1])
            ->order(['Periodos.id' => 'DESC'])->toArray();
        $aCarreras = TableRegistry::getTableLocator()->get('Carreras')->find('list')->where(['Carreras.activa' => 1])->toArray();
        $paises = $this->Estudiantes->Paises->find('list', ['limit' => 200]);
        $usuarios = $this->Estudiantes->Usuarios->find('list', ['limit' => 200]);
        $this->set(compact('sToken','aOrigen', 'aGenero', 'aEdoCivil','aSedes','aPeriodos','aCarreras'));
        $this->set(compact('estudiante', 'paises', 'estados', 'municipios', 'parroquias', 'usuarios'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Estudiante id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $estudiante = $this->Estudiantes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $estudiante = $this->Estudiantes->patchEntity($estudiante, $this->request->getData());
            if ($this->Estudiantes->save($estudiante)) {
                $this->Flash->success(__('The {0} has been saved.', 'Estudiante'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Estudiantes ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Estudiante'));
        }
        $estados = [];
        $municipios = [];
        $parroquias = [];
        $sToken = $this->generateToken();
        $aOrigen = Configure::read('aTipoDoc');
        $aGenero = Configure::read('aGeneros');
        $aEdoCivil = Configure::read('aEstadoCivil');
        $aSedes = TableRegistry::getTableLocator()->get('Sedes')->find('list')->where(['Sedes.activa' => 1])->toArray();
        $aPeriodos = TableRegistry::getTableLocator()->get('Periodos')->find('list')->where(['Periodos.activo' => 1])
            ->order(['Periodos.id' => 'DESC'])->toArray();
        $aCarreras = TableRegistry::getTableLocator()->get('Carreras')->find('list')->where(['Carreras.activa' => 1])->toArray();
        $paises = $this->Estudiantes->Paises->find('list', ['limit' => 200]);
        $usuarios = $this->Estudiantes->Usuarios->find('list', ['limit' => 200]);
        $this->set(compact('sToken','aOrigen', 'aGenero', 'aEdoCivil','aSedes','aPeriodos','aCarreras'));
        $this->set(compact('estudiante', 'paises', 'estados', 'municipios', 'parroquias', 'usuarios'));

    }

    /**
     * Delete method
     *
     * @param string|null $id Estudiante id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $estudiante = $this->Estudiantes->get($id);
        if ($this->Estudiantes->delete($estudiante)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Estudiante'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Estudiantes ' . json_encode($estudiante->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Estudiante'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function situacion($id = null)
    {
        if (!$id) 
        {
            $this->Flash->error(__('No se especificó el estudiante.'));
            return $this->redirect(['action' => 'homepage']);
        }

        $userActivo = $this->request->getSession()->read('Auth.User');

        if (isset($userActivo['estudiantes'][0]['id'])) 
        {
            $nId = (int)$userActivo['estudiantes'][0]['id'];
        }

        if ( isset($nId) && $nId !== (int)$id ) 
        {
            $this->Flash->error(__('No tiene permiso para ver la situación de este estudiante.'));
            return $this->redirect(['action' => 'homepage']);
        }

        $estudiante = $this->Estudiantes->get($id, [
            'contain' => ['Usuarios'],
        ]);

        $programasTable = TableRegistry::getTableLocator()->get('EstudianteProgramas');
        $programas = $programasTable->find()
            ->where(['EstudianteProgramas.estudiante_id' => $id])
            ->contain(['Carreras', 'Programas'])
            ->toArray();

        $situacionEstudiantesTable = TableRegistry::getTableLocator()->get('SituacionEstudiantes');
        foreach ($programas as $programa) {
            $situacionEstudiantesTable->registrarDesdeMalla(
                $id,
                $programa->programa_id,
                $programa->carrera_id,
                $programa->periodo_id
            );
        }

        $situaciones = [];
        foreach ($programas as $programa) {
            $asignaturas = $situacionEstudiantesTable->find()
                ->where([
                    'SituacionEstudiantes.estudiante_id' => $id,
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

            foreach ($asignaturas as $asig) 
            {
                if ( empty($asig->calificacion) ) 
                {
                    continue;
                }

                $esCualitativa = $asig->has('asignatura') && (int)$asig->asignatura->calificacion === 1;
                if ($esCualitativa) 
                {
                    $aprobada = strtoupper($asig->calificacion) === 'A';
                    $notaISA = strtoupper($asig->calificacion) === 'A' ? 20 : 0;
                } else {
                    $notaMinima = $notaMinimaPrograma;
                    if (isset($mallasPorAsignatura[$asig->asignatura_id]) && !empty($mallasPorAsignatura[$asig->asignatura_id]->nota_minima)) 
                    {
                        $notaMinima = (float)$mallasPorAsignatura[$asig->asignatura_id]->nota_minima;
                    }
                    $aprobada = (float)$asig->calificacion >= $notaMinima;
                    $notaISA = (float)$asig->calificacion;
                }
                if ($aprobada) 
                {
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

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS SITUACION Estudiantes ' . json_encode($estudiante->toArray()));
        $this->set('title', 'Situación');
        $this->set(compact('estudiante', 'situaciones'));
    }

    public function notasLapso()
    {
        $userActivo = $this->request->getSession()->read('Auth.User');
        $estudianteId = isset($userActivo['estudiantes'][0]['id']) ? (int)$userActivo['estudiantes'][0]['id'] : null;

        if (!$estudianteId) 
        {
            $this->Flash->error(__('No se encontró el estudiante asociado al usuario.'));
            return $this->redirect(['action' => 'index']);
        }

        $estudiante = $this->Estudiantes->get($estudianteId, [
            'contain' => ['Usuarios'],
        ]);

        $estudianteCursosTable = TableRegistry::getTableLocator()->get('EstudianteCursos');
        $ecs = $estudianteCursosTable->find()
            ->where([
                'EstudianteCursos.estudiante_id' => $estudianteId,
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

            foreach ($indicadores as $ind) 
            {
                if (empty($ind->contenidos_cursos)) 
                {
                    continue;
                }
                foreach ($ind->contenidos_cursos as $cc) 
                {
                    if ((int)$cc->activo === 1) {
                        $contenidoIds[] = $cc->id;
                        $cc->indicador_curso = $ind;
                        $aContenidos[] = $cc;
                    }
                }
            }

            $notas = [];
            if (!empty($contenidoIds)) 
            {
                $notas = $cursoNotasTable->find()
                    ->where([
                        'CursoNotas.estudiante_id' => $estudianteId,
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
                if (isset($aTotales[$estudianteId])) {
                    $oResumen = $aTotales[$estudianteId];
                }
            }

            $periodoId = $curso->periodo_id;
            if (!isset($periodos[$periodoId])) 
            {
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

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LAS NOTAS DE LAPSO Estudiantes ' . json_encode($estudiante->toArray()));
        $this->set('title', 'Notas de Lapso');
        $this->set(compact('estudiante', 'periodos'));
    }

    /**
     * Get estados by pais_id (AJAX)
    */
    public function getEstados()
    {
        $this->request->allowMethod(['ajax', 'get']);
        $this->autoRender = false;
        $pais_id = $this->request->getQuery('pais_id');

        $estados = [];
        if ($pais_id) 
        {
            $estados = $this->Estudiantes->Estados->find('list', ['limit' => 200])
                ->where(['pais_id' => $pais_id])
                ->order(['nombre' => 'ASC'])
                ->toArray();
        }

        $this->response = $this->response->withType('application/json');
        $this->response = $this->response->withStringBody(json_encode(['estados' => $estados]));
        return $this->response;
    }

    /**
     * Get municipios by estado_id (AJAX)
     */
    public function getMunicipios()
    {
        $this->request->allowMethod(['ajax', 'get']);
        $this->autoRender = false;
        $estado_id = $this->request->getQuery('estado_id');

        $municipios = [];
        if ($estado_id) {
            $municipios = $this->Estudiantes->Municipios->find('list', ['limit' => 200])
                ->where(['estado_id' => $estado_id])
                ->order(['nombre' => 'ASC'])
                ->toArray();
        }

        $this->response = $this->response->withType('application/json');
        $this->response = $this->response->withStringBody(json_encode(['municipios' => $municipios]));
        return $this->response;
    }

    /**
     * Get parroquias by municipio_id (AJAX)
     */
    public function getParroquias()
    {
        $this->request->allowMethod(['ajax', 'get']);
        $this->autoRender = false;
        $municipio_id = $this->request->getQuery('municipio_id');

        $parroquias = [];
        if ($municipio_id) {
            $parroquias = $this->Estudiantes->Parroquias->find('list', ['limit' => 200])
                ->where(['municipio_id' => $municipio_id])
                ->order(['nombre' => 'ASC'])
                ->toArray();
        }

        $this->response = $this->response->withType('application/json');
        $this->response = $this->response->withStringBody(json_encode(['parroquias' => $parroquias]));
        return $this->response;
    }

}
