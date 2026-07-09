<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * GrupoAsignaturas Controller
 *
 * @property \App\Model\Table\GrupoAsignaturasTable $GrupoAsignaturas
 *
 * @method \App\Model\Entity\GrupoAsignatura[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GrupoAsignaturasController extends AppController
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
        $grupoAsignaturas = $this->paginate($this->GrupoAsignaturas);

        $this->set(compact('grupoAsignaturas'));
    }

    /**
     * View method
     *
     * @param string|null $id Grupo Asignatura id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $grupoAsignatura = $this->GrupoAsignaturas->get($id);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS GrupoAsignaturas - ID: ' . $grupoAsignatura->id . ', Nombre: ' . $grupoAsignatura->nombre);

        $asignaturasTable = TableRegistry::getTableLocator()->get('Asignaturas');

        $this->paginate = [
            'limit' => 20,
            'order' => [
                'Asignaturas.id' => 'asc'
            ],
            'conditions' => [
                'Asignaturas.grupo_asignatura_id' => $grupoAsignatura->id
            ]
        ];

        $asignaturas = $this->paginate($asignaturasTable);

        $this->set(compact('grupoAsignatura', 'asignaturas'));
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $grupoAsignatura = $this->GrupoAsignaturas->newEntity();
        if ($this->request->is('post')) {
            $grupoAsignatura = $this->GrupoAsignaturas->patchEntity($grupoAsignatura, $this->request->getData());
            if ($this->GrupoAsignaturas->save($grupoAsignatura)) {
                $this->Flash->success(__('The {0} has been saved.', 'Grupo Asignatura'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS GrupoAsignaturas ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Grupo Asignatura'));
        }
        $this->set(compact('grupoAsignatura'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Grupo Asignatura id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $grupoAsignatura = $this->GrupoAsignaturas->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $grupoAsignatura = $this->GrupoAsignaturas->patchEntity($grupoAsignatura, $this->request->getData());
            if ($this->GrupoAsignaturas->save($grupoAsignatura)) {
                $this->Flash->success(__('The {0} has been saved.', 'Grupo Asignatura'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS GrupoAsignaturas ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Grupo Asignatura'));
        }
        $this->set(compact('grupoAsignatura'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Grupo Asignatura id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $grupoAsignatura = $this->GrupoAsignaturas->get($id);
        if ($this->GrupoAsignaturas->delete($grupoAsignatura)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Grupo Asignatura'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS GrupoAsignaturas ' . json_encode($grupoAsignatura->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Grupo Asignatura'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
