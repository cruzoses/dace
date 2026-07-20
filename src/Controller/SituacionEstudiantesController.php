<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * SituacionEstudiantes Controller
 *
 * @property \App\Model\Table\SituacionEstudiantesTable $SituacionEstudiantes
 *
 * @method \App\Model\Entity\SituacionEstudiante[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SituacionEstudiantesController extends AppController
{

    /**
     * 
    */
	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
	}

    /**
     * 
    */
	public function isAuthorized($user = null)
	{
		return parent::isAuthorized($user);
	}
	
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
    */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Estudiantes', 'Programas', 'Asignaturas', 'Periodos'],
        ];
        $situacionEstudiantes = $this->paginate($this->SituacionEstudiantes);

        $this->set(compact('situacionEstudiantes'));
    }

    /**
     * View method
     *
     * @param string|null $id Situacion Estudiante id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $situacionEstudiante = $this->SituacionEstudiantes->get($id, [
            'contain' => ['Estudiantes', 'Programas', 'Asignaturas', 'Periodos'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS SituacionEstudiantes ' . json_encode($situacionEstudiante->toArray()));

        $this->set('situacionEstudiante', $situacionEstudiante);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $situacionEstudiante = $this->SituacionEstudiantes->newEntity();
        if ($this->request->is('post')) {
            $situacionEstudiante = $this->SituacionEstudiantes->patchEntity($situacionEstudiante, $this->request->getData());
            if ($this->SituacionEstudiantes->save($situacionEstudiante)) {
                $this->Flash->success(__('The {0} has been saved.', 'Situacion Estudiante'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS SituacionEstudiantes ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Situacion Estudiante'));
        }
        $estudiantes = $this->SituacionEstudiantes->Estudiantes->find('list', ['limit' => 200]);
        $programas = $this->SituacionEstudiantes->Programas->find('list', ['limit' => 200]);
        $asignaturas = $this->SituacionEstudiantes->Asignaturas->find('list', ['limit' => 200]);
        $periodos = $this->SituacionEstudiantes->Periodos->find('list', ['limit' => 200]);
        $this->set(compact('situacionEstudiante', 'estudiantes', 'programas', 'asignaturas', 'periodos'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Situacion Estudiante id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $situacionEstudiante = $this->SituacionEstudiantes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $situacionEstudiante = $this->SituacionEstudiantes->patchEntity($situacionEstudiante, $this->request->getData());
            if ($this->SituacionEstudiantes->save($situacionEstudiante)) {
                $this->Flash->success(__('The {0} has been saved.', 'Situacion Estudiante'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS SituacionEstudiantes ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Situacion Estudiante'));
        }
        $estudiantes = $this->SituacionEstudiantes->Estudiantes->find('list', ['limit' => 200]);
        $programas = $this->SituacionEstudiantes->Programas->find('list', ['limit' => 200]);
        $asignaturas = $this->SituacionEstudiantes->Asignaturas->find('list', ['limit' => 200]);
        $periodos = $this->SituacionEstudiantes->Periodos->find('list', ['limit' => 200]);
        $this->set(compact('situacionEstudiante', 'estudiantes', 'programas', 'asignaturas', 'periodos'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Situacion Estudiante id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $situacionEstudiante = $this->SituacionEstudiantes->get($id);
        if ($this->SituacionEstudiantes->delete($situacionEstudiante)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Situacion Estudiante'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS SituacionEstudiantes ' . json_encode($situacionEstudiante->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Situacion Estudiante'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Carga el formulario de calificación vía AJAX.
     *
     * @param string|null $id Situacion Estudiante id.
     * @return \Cake\Http\Response|null
     */
    public function califica($id = null)
    {
        $this->request->allowMethod(['ajax', 'get']);

        $situacionEstudiante = $this->SituacionEstudiantes->get($id, [
            'contain' => ['Asignaturas'],
        ]);

        $this->Auditorias->registrar('CONSULTA',
            'CALIFICACION - CONSULTA ASIGNATURA ID: ' . $situacionEstudiante->asignatura_id
            . ', ESTUDIANTE ID: ' . $situacionEstudiante->estudiante_id
        );

        $periodos = $this->SituacionEstudiantes->Periodos->find('list')
            ->where(['Periodos.activo' => 1])
            ->order(['Periodos.codigo' => 'DESC']);

        $userAlias = $this->Auth->user('alias');
        $tipoCalificacion = (int)$situacionEstudiante->asignatura->calificacion;

        $this->set(compact('situacionEstudiante', 'periodos', 'userAlias', 'tipoCalificacion'));
        $this->viewBuilder()->setLayout('ajax');
    }

    /**
     * Guarda la calificación de una asignatura vía AJAX.
     *
     * @return \Cake\Http\Response|null JSON response.
     */
    public function guardarCalifica()
    {
        $this->request->allowMethod(['ajax', 'post']);

        $id = $this->request->getData('id');
        $tipoCalificacion = $this->request->getData('tipo_calificacion');
        $calificacion = trim($this->request->getData('calificacion'));
        $seccion = trim($this->request->getData('seccion', ''));
        $periodoId = $this->request->getData('periodo_id');
        $responsable = $this->Auth->user('alias');

        if (empty($calificacion)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'La calificación es requerida.'
                ]));
        }

        if (empty($seccion)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'La sección es requerida cuando se registra una calificación.'
                ]));
        }

        if (empty($periodoId)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'El período es requerido cuando se registra una calificación.'
                ]));
        }

        if ((int)$tipoCalificacion === 1) {
            $calificacion = strtoupper($calificacion);
            if (!in_array($calificacion, ['A', 'R'])) {
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'message' => 'La calificación cualitativa solo permite A (Aprobado) o R (Reprobado).'
                    ]));
            }
        } else {
            if (!is_numeric($calificacion) || (float)$calificacion < 1 || (float)$calificacion > 20) {
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'message' => 'La calificación cuantitativa debe ser un valor entre 1 y 20.'
                    ]));
            }
        }

        $situacionEstudiante = $this->SituacionEstudiantes->get($id, [
            'contain' => ['Asignaturas'],
        ]);
        $calificacionAnterior = $situacionEstudiante->calificacion;

        $situacionEstudiante = $this->SituacionEstudiantes->patchEntity(
            $situacionEstudiante,
            $this->request->getData()
        );
        $situacionEstudiante->calificacion = $calificacion;
        $situacionEstudiante->responsable = $responsable;

        $notaISA = (int)$tipoCalificacion === 1 ? (strtoupper($calificacion) === 'A' ? 20 : 0) : (float)$calificacion;
        $creditosAsig = (int)$situacionEstudiante->asignatura->creditos;
        $situacionEstudiante->acumulado = (int)($notaISA * $creditosAsig);

        if ($this->SituacionEstudiantes->save($situacionEstudiante)) {
            $evento = empty($calificacionAnterior) ? 'REGISTRA' : 'MODIFICA';
            $this->Auditorias->registrar($evento,
                'CALIFICACION - ASIGNATURA ID: ' . $situacionEstudiante->asignatura_id
                . ', ESTUDIANTE ID: ' . $situacionEstudiante->estudiante_id
                . ', PROGRAMA ID: ' . $situacionEstudiante->programa_id
                . ', TIPO: ' . ((int)$tipoCalificacion === 1 ? 'CUALITATIVA' : 'CUANTITATIVA')
                . ', NOTA ANTERIOR: ' . ($calificacionAnterior ?: 'S/N')
                . ', NOTA NUEVA: ' . $calificacion
                . ', SECCION: ' . $situacionEstudiante->seccion
                . ', PERIODO ID: ' . $situacionEstudiante->periodo_id
                . ', RESPONSABLE: ' . $responsable
            );

            $periodoNombre = '';
            if ($situacionEstudiante->has('periodo')) {
                $periodoNombre = $situacionEstudiante->periodo->nombre;
            } elseif ($situacionEstudiante->periodo_id) {
                $periodosTable = TableRegistry::getTableLocator()->get('Periodos');
                $periodo = $periodosTable->get($situacionEstudiante->periodo_id);
                $periodoNombre = $periodo->nombre;
            }

            $estudianteId = $situacionEstudiante->estudiante_id;
            $programaId = $situacionEstudiante->programa_id;

            $programasTable = TableRegistry::getTableLocator()->get('EstudianteProgramas');
            $ep = $programasTable->find()
                ->where(['EstudianteProgramas.estudiante_id' => $estudianteId, 'EstudianteProgramas.programa_id' => $programaId])
                ->contain(['Programas'])
                ->first();

            $totalCreditosPrograma = $ep ? (int)$ep->programa->creditos : 0;
            $notaMinimaPrograma = $ep ? (float)$ep->programa->nota_minima : 0;

            $asignaturas = $this->SituacionEstudiantes->find()
                ->where([
                    'SituacionEstudiantes.estudiante_id' => $estudianteId,
                    'SituacionEstudiantes.programa_id' => $programaId,
                ])
                ->contain(['Asignaturas'])
                ->toArray();

            $mallasTable = TableRegistry::getTableLocator()->get('Mallas');
            $mallas = $mallasTable->find()
                ->where(['Mallas.programa_id' => $programaId])
                ->toArray();
            $mallasPorAsignatura = [];
            foreach ($mallas as $m) {
                $mallasPorAsignatura[$m->asignatura_id] = $m;
            }

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
                $esCual = $asig->has('asignatura') && (int)$asig->asignatura->calificacion === 1;
                if ($esCual) {
                    $aprobada = strtoupper($asig->calificacion) === 'A';
                    $notaISA = strtoupper($asig->calificacion) === 'A' ? 20 : 0;
                } else {
                    $nm = $notaMinimaPrograma;
                    if (isset($mallasPorAsignatura[$asig->asignatura_id]) && !empty($mallasPorAsignatura[$asig->asignatura_id]->nota_minima)) {
                        $nm = (float)$mallasPorAsignatura[$asig->asignatura_id]->nota_minima;
                    }
                    $aprobada = (float)$asig->calificacion >= $nm;
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
                    $notaIRA = $esCual ? $notaISA : (float)$asig->calificacion;
                    $iraNumerador += $notaIRA * $creditosAsig;
                }
                $iraDenominador += $creditosAsig * (int)$asig->cursada;
            }

            $porcentajeAprobado = $totalCreditosPrograma > 0
                ? round(($totalCreditosAprobados / $totalCreditosPrograma) * 100, 1)
                : 0;

            $isa = $isaDenominador > 0 ? round($isaNumerador / $isaDenominador, 5) : 0;
            $ira = $iraDenominador > 0 ? round($iraNumerador / $iraDenominador, 5) : 0;

            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => true,
                    'message' => 'Calificación guardada correctamente.',
                    'data' => [
                        'id' => $situacionEstudiante->id,
                        'calificacion' => $calificacion,
                        'seccion' => $situacionEstudiante->seccion,
                        'periodo' => $periodoNombre,
                        'responsable' => $responsable,
                        'tipo_calificacion' => (int)$tipoCalificacion,
                        'programa_id' => $programaId,
                        'totalCreditosAprobados' => $totalCreditosAprobados,
                        'totalAsignaturasAprobadas' => $totalAsignaturasAprobadas,
                        'porcentajeAprobado' => $porcentajeAprobado,
                        'isa' => $isa,
                        'ira' => $ira,
                    ]
                ]));
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'success' => false,
                'errors' => $situacionEstudiante->getErrors(),
                'message' => 'Error al guardar la calificación.'
            ]));
    }

    /**
     * Elimina la calificación de una asignatura vía AJAX.
     *
     * @param string|null $id Situacion Estudiante id.
     * @return \Cake\Http\Response|null JSON response.
     */
    public function eliminarCalifica($id = null)
    {
        $this->request->allowMethod(['ajax', 'post']);

        $situacionEstudiante = $this->SituacionEstudiantes->get($id);

        $calificacionEliminada = $situacionEstudiante->calificacion;
        $responsable = $this->Auth->user('alias');

        $situacionEstudiante->calificacion = null;
        $situacionEstudiante->seccion = null;
        $situacionEstudiante->periodo_id = null;
        $situacionEstudiante->responsable = $responsable;
        $situacionEstudiante->acumulado = null;

        if ($this->SituacionEstudiantes->save($situacionEstudiante)) {
            $this->Auditorias->registrar('ELIMINA',
                'CALIFICACION ELIMINADA - ASIGNATURA ID: ' . $situacionEstudiante->asignatura_id
                . ', ESTUDIANTE ID: ' . $situacionEstudiante->estudiante_id
                . ', PROGRAMA ID: ' . $situacionEstudiante->programa_id
                . ', NOTA ELIMINADA: ' . $calificacionEliminada
                . ', RESPONSABLE: ' . $responsable
            );

            $estudianteId = $situacionEstudiante->estudiante_id;
            $programaId = $situacionEstudiante->programa_id;

            $programasTable = TableRegistry::getTableLocator()->get('EstudianteProgramas');
            $ep = $programasTable->find()
                ->where(['EstudianteProgramas.estudiante_id' => $estudianteId, 'EstudianteProgramas.programa_id' => $programaId])
                ->contain(['Programas'])
                ->first();

            $totalCreditosPrograma = $ep ? (int)$ep->programa->creditos : 0;

            $asignaturas = $this->SituacionEstudiantes->find()
                ->where([
                    'SituacionEstudiantes.estudiante_id' => $estudianteId,
                    'SituacionEstudiantes.programa_id' => $programaId,
                ])
                ->contain(['Asignaturas'])
                ->toArray();

            $iraNumerador = 0;
            $iraDenominador = 0;
            foreach ($asignaturas as $asig) {
                if (empty($asig->calificacion)) {
                    continue;
                }
                $creditosAsig = (int)$asig->asignatura->creditos;
                if (!empty($asig->acumulado) && (int)$asig->acumulado > 0) {
                    $iraNumerador += (int)$asig->acumulado;
                } else {
                    $esCual = $asig->has('asignatura') && (int)$asig->asignatura->calificacion === 1;
                    $notaIRA = $esCual ? (strtoupper($asig->calificacion) === 'A' ? 20 : 0) : (float)$asig->calificacion;
                    $iraNumerador += $notaIRA * $creditosAsig;
                }
                $iraDenominador += $creditosAsig * (int)$asig->cursada;
            }
            $ira = $iraDenominador > 0 ? round($iraNumerador / $iraDenominador, 5) : 0;

            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => true,
                    'message' => 'Calificación eliminada correctamente.',
                    'data' => [
                        'programa_id' => $programaId,
                        'ira' => $ira,
                    ]
                ]));
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'success' => false,
                'message' => 'Error al eliminar la calificación.'
            ]));
    }
}
