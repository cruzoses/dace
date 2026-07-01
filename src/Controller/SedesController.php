<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Sedes Controller
 *
 * @property \App\Model\Table\SedesTable $Sedes
 *
 * @method \App\Model\Entity\Sede[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SedesController extends AppController
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
        $conditions = $this->Sedes->formatConditions($this->request->getQueryParams());
        $this->paginate['conditions'] = $conditions;

        $sedes = $this->paginate($this->Sedes);
        $filtros = $this->request->getQuery();

        $searchFields = $this->Sedes->getSearchFields();
        $this->set(compact('sedes', 'filtros', 'searchFields'));
    }

    /**
     * View method
     *
     * @param string|null $id Sede id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $sede = $this->Sedes->get($id, [
            'contain' => ['Carreras', 'Cursos', 'EstudianteProgramas'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Sedes ' . json_encode($sede->toArray()));

        $this->set('sede', $sede);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $sede = $this->Sedes->newEntity();
        if ($this->request->is('post')) {
            $sede = $this->Sedes->patchEntity($sede, $this->request->getData());
            if ($this->Sedes->save($sede)) {
                $this->Flash->success(__('The {0} has been saved.', 'Sede'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Sedes ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Sede'));
        }
        $carreras = $this->Sedes->Carreras->find('list', ['limit' => 200]);
        $this->set(compact('sede', 'carreras'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Sede id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $sede = $this->Sedes->get($id, [
            'contain' => ['Carreras']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sede = $this->Sedes->patchEntity($sede, $this->request->getData());
            if ($this->Sedes->save($sede)) {
                $this->Flash->success(__('The {0} has been saved.', 'Sede'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Sedes ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Sede'));
        }
        $carreras = $this->Sedes->Carreras->find('list', ['limit' => 200]);
        $this->set(compact('sede', 'carreras'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Sede id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sede = $this->Sedes->get($id);
        if ($this->Sedes->delete($sede)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Sede'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Sedes ' . json_encode($sede->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Sede'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
