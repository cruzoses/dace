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

	public function isAuthorized($user = null)
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
        $conditions = $this->EstudianteProgramas->formatConditions($this->request->getQueryParams());
        $this->paginate = [
            'contain' => ['Estudiantes', 'Carreras', 'Programas', 'Sedes'],
            'conditions' => $conditions,
        ];
        $estudianteProgramas = $this->paginate($this->EstudianteProgramas);
        $filtros = $this->request->getQuery();
        $searchFields = $this->EstudianteProgramas->getSearchFields();
        $searchFields['carrera_id']['options'] = $this->EstudianteProgramas->Carreras->find('list', [
            'conditions' => ['Carreras.activa' => 1],
            'order' => ['Carreras.nombre' => 'ASC']
        ])->toArray();
        $searchFields['programa_id']['options'] = $this->EstudianteProgramas->Programas->find('list', [
            'conditions' => ['Programas.activo' => 1],
            'order' => ['Programas.nombre' => 'ASC']
        ])->toArray();

        $this->set(compact('estudianteProgramas', 'filtros', 'searchFields'));
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
            'contain' => ['Estudiantes', 'Carreras', 'Programas', 'Sedes'],
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

    public function add($estudianteId = null)
    {
        $estudiantePrograma = $this->EstudianteProgramas->newEntity();

        if ($estudianteId) {
            $estudiantesTable = TableRegistry::getTableLocator()->get('Estudiantes');
            $estudiante = $estudiantesTable->get($estudianteId);
            $estudiantePrograma->estudiante_id = $estudiante->id;
        }

        if ($this->request->is('post'))
        {
            $estudiantePrograma = $this->EstudianteProgramas->patchEntity($estudiantePrograma, $this->request->getData());
            if ($this->EstudianteProgramas->save($estudiantePrograma))
            {
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA PROGRAMA A EstudianteProgramas ' . json_encode($this->request->getData()));

                if ($this->request->is('ajax')) {
                    return $this->response->withType('application/json')
                        ->withStringBody(json_encode(['success' => true, 'message' => 'Programa registrado correctamente.']));
                }

                $this->Flash->success(__('Programa registrado correctamente.'));
                return $this->redirect(['controller' => 'Estudiantes', 'action' => 'view', $estudiantePrograma->estudiante_id]);
            }

            if ($this->request->is('ajax')) {
                $errors = $estudiantePrograma->getErrors();
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode(['success' => false, 'errors' => $errors, 'message' => 'Error al registrar el programa.']));
            }

            $this->Flash->error(__('No se pudo guardar el programa. Intente de nuevo.'));
        }
        $sedes = $this->EstudianteProgramas->Sedes->find('list', ['limit' => 200]);
        $carreras = $this->EstudianteProgramas->Programas->Carreras->find('list', [
            'conditions' => ['Carreras.activa' => 1],
            'order' => ['Carreras.id' => 'ASC']
        ]);
        $this->set(compact('estudiantePrograma', 'sedes', 'carreras'));

        if ($estudianteId) {
            $this->set('estudiante', $estudiante);
        }

        if ($this->request->is('ajax') || $estudianteId) {
            $this->viewBuilder()->setLayout('ajax');
        }
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

        $estudiantesTable = TableRegistry::getTableLocator()->get('Estudiantes');
        $estudiante = $estudiantesTable->get($estudiantePrograma->estudiante_id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $estudiantePrograma = $this->EstudianteProgramas->patchEntity($estudiantePrograma, $this->request->getData());
            if ($this->EstudianteProgramas->save($estudiantePrograma)) {
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA PROGRAMA A EstudianteProgramas ' . json_encode($this->request->getData()));

                if ($this->request->is('ajax')) {
                    return $this->response->withType('application/json')
                        ->withStringBody(json_encode(['success' => true, 'message' => 'Programa modificado correctamente.']));
                }

                $this->Flash->success(__('Programa modificado correctamente.'));
                return $this->redirect(['controller' => 'Estudiantes', 'action' => 'view', $estudiante->id]);
            }

            if ($this->request->is('ajax')) {
                $errors = $estudiantePrograma->getErrors();
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode(['success' => false, 'errors' => $errors, 'message' => 'Error al modificar el programa.']));
            }

            $this->Flash->error(__('No se pudo modificar el programa. Intente de nuevo.'));
        }
        $sedes = $this->EstudianteProgramas->Sedes->find('list', ['limit' => 200]);
        $carreras = $this->EstudianteProgramas->Programas->Carreras->find('list', [
            'conditions' => ['Carreras.activa' => 1],
            'order' => ['Carreras.id' => 'ASC']
        ]);
        $this->set(compact('estudiantePrograma', 'estudiante', 'sedes', 'carreras'));

        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setLayout('ajax');
        }
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

    /**
     * Delete method
     *
     * @param string|null $id Estudiante Programa id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function eliminar($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $estudiantePrograma = $this->EstudianteProgramas->get($id);
        $estudianteId = $estudiantePrograma->estudiante_id;        
        if ($this->EstudianteProgramas->delete($estudiantePrograma)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Estudiante Programa'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS EstudianteProgramas ' . json_encode($estudiantePrograma->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Estudiante Programa'));
        }

        return $this->redirect(['controller' => 'datos', 'action' => 'estudiante',$estudianteId]);
    }
}
