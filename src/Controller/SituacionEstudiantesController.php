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
}
