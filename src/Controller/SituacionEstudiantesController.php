<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

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
	public function isAuthorized($user)
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
        $responsable = $this->Auth->user('alias');

        if (empty($calificacion)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'La calificación es requerida.'
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

        $situacionEstudiante = $this->SituacionEstudiantes->get($id);
        $calificacionAnterior = $situacionEstudiante->calificacion;

        $situacionEstudiante = $this->SituacionEstudiantes->patchEntity(
            $situacionEstudiante,
            $this->request->getData()
        );
        $situacionEstudiante->calificacion = $calificacion;
        $situacionEstudiante->responsable = $responsable;

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

            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => true,
                    'message' => 'Calificación guardada correctamente.'
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

        if ($this->SituacionEstudiantes->save($situacionEstudiante)) {
            $this->Auditorias->registrar('ELIMINA',
                'CALIFICACION ELIMINADA - ASIGNATURA ID: ' . $situacionEstudiante->asignatura_id
                . ', ESTUDIANTE ID: ' . $situacionEstudiante->estudiante_id
                . ', PROGRAMA ID: ' . $situacionEstudiante->programa_id
                . ', NOTA ELIMINADA: ' . $calificacionEliminada
                . ', RESPONSABLE: ' . $responsable
            );

            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => true,
                    'message' => 'Calificación eliminada correctamente.'
                ]));
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'success' => false,
                'message' => 'Error al eliminar la calificación.'
            ]));
    }
}
