<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * EstudianteProgramas Controller
 *
 * @property \App\Model\Table\EstudianteProgramasTable $EstudianteProgramas
 *
 * @method \App\Model\Entity\EstudiantePrograma[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EstudianteProgramasController extends AppController
{

	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
	}

	public function isAuthorized($user)
	{
        if( isset( $user['activo'] ) && isset( $user['rols'] ) && $user['activo'] && $this->tienePermiso([2,3]) )
        {
            return true;
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
        $this->paginate = [
            'contain' => ['Estudiantes', 'Sedes', 'Programas'],
        ];
        $estudianteProgramas = $this->paginate($this->EstudianteProgramas);

        $this->set(compact('estudianteProgramas'));
    }

    /**
     * View method
     *
     * @param string|null $id Estudiante Programa id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $estudiantePrograma = $this->EstudianteProgramas->get($id, [
            'contain' => ['Estudiantes', 'Sedes', 'Programas'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS EstudianteProgramas ' . json_encode($estudiantePrograma->toArray()));

        $this->set('estudiantePrograma', $estudiantePrograma);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function nuevo($id = null)
    {
        $estudiantePrograma = $this->EstudianteProgramas->newEntity();

        $estudiantesTable = TableRegistry::getTableLocator()->get('Estudiantes');
        $estudiante = $estudiantesTable->get($id);
        $estudiantePrograma->estudiante_id = $estudiante->id;

        if ($this->request->is('post'))
        {
            $estudiantePrograma = $this->EstudianteProgramas->patchEntity($estudiantePrograma, $this->request->getData());
            if ($this->EstudianteProgramas->save($estudiantePrograma)) {
                $this->Flash->success(__('Programa registrado correctamente.'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA PROGRAMA A EstudianteProgramas ' . json_encode($this->request->getData()));

                return $this->redirect(['controller' => 'Estudiantes', 'action' => 'view', $estudiante->id]);
            }
            $this->Flash->error(__('No se pudo guardar el programa. Intente de nuevo.'));
        }
        $sedes = $this->EstudianteProgramas->Sedes->find('list', ['limit' => 200]);
        $carreras = $this->EstudianteProgramas->Programas->Carreras->find('list', [
            'conditions' => ['Carreras.activa' => 1],
            'order' => ['Carreras.id' => 'ASC']
        ]);
        $this->set(compact('estudiantePrograma', 'estudiante', 'sedes', 'carreras'));
    }

    public function getProgramasByCarrera()
    {
        $this->request->allowMethod(['ajax', 'get']);
        $carrera_id = $this->request->getQuery('carrera_id');

        $programas = [];
        if ($carrera_id) {
            $programas = $this->EstudianteProgramas->Programas->find('list', ['limit' => 200])
                ->where(['carrera_id' => $carrera_id, 'Programas.activo' => 1])
                ->order(['Programas.id' => 'DESC'])
                ->toArray();
        }

        $this->set(compact('programas'));
        $this->set('_serialize', ['programas']);
    }

    public function add()
    {
        $estudiantePrograma = $this->EstudianteProgramas->newEntity();
        if ($this->request->is('post')) 
        {
            $estudiantePrograma = $this->EstudianteProgramas->patchEntity($estudiantePrograma, $this->request->getData());
            if ($this->EstudianteProgramas->save($estudiantePrograma)) 
            {
                $this->Flash->success(__('The {0} has been saved.', 'Estudiante Programa'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS EstudianteProgramas ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Estudiante Programa'));
        }
        $estudiantes = $this->EstudianteProgramas->Estudiantes->find('list', ['limit' => 200]);
        $sedes = $this->EstudianteProgramas->Sedes->find('list', ['limit' => 200]);
        $programas = $this->EstudianteProgramas->Programas->find('list', ['limit' => 200]);
        $this->set(compact('estudiantePrograma', 'estudiantes', 'sedes', 'programas'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Estudiante Programa id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $estudiantePrograma = $this->EstudianteProgramas->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $estudiantePrograma = $this->EstudianteProgramas->patchEntity($estudiantePrograma, $this->request->getData());
            if ($this->EstudianteProgramas->save($estudiantePrograma)) {
                $this->Flash->success(__('The {0} has been saved.', 'Estudiante Programa'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS EstudianteProgramas ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Estudiante Programa'));
        }
        $estudiantes = $this->EstudianteProgramas->Estudiantes->find('list', ['limit' => 200]);
        $sedes = $this->EstudianteProgramas->Sedes->find('list', ['limit' => 200]);
        $programas = $this->EstudianteProgramas->Programas->find('list', ['limit' => 200]);
        $this->set(compact('estudiantePrograma', 'estudiantes', 'sedes', 'programas'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Estudiante Programa id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $estudiantePrograma = $this->EstudianteProgramas->get($id);
        if ($this->EstudianteProgramas->delete($estudiantePrograma)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Estudiante Programa'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS EstudianteProgramas ' . json_encode($estudiantePrograma->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Estudiante Programa'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
