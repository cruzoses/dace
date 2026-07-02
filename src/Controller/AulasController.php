<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;

/**
 * Aulas Controller
 *
 * @property \App\Model\Table\AulasTable $Aulas
 *
 * @method \App\Model\Entity\Aula[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AulasController extends AppController
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
        $conditions = $this->Aulas->formatConditions($this->request->getQueryParams());
        $this->paginate['conditions'] = $conditions;

        $aulas = $this->paginate($this->Aulas);
        $filtros = $this->request->getQuery();

        $sedes = TableRegistry::getTableLocator()->get('Sedes')->find('list', ['limit' => 200])->toArray();
        $searchFields = $this->Aulas->getSearchFields();
        $searchFields['sede']['options'] = $sedes;
        $this->set(compact('aulas', 'filtros', 'searchFields'));
    }

    /**
     * View method
     *
     * @param string|null $id Aula id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $aula = $this->Aulas->get($id, [
            'contain' => [],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Aulas ' . json_encode($aula->toArray()));

        $this->set('aula', $aula);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $aula = $this->Aulas->newEntity();
        if ($this->request->is('post')) {
            $aula = $this->Aulas->patchEntity($aula, $this->request->getData());
            if ($this->Aulas->save($aula)) {
                $this->Flash->success(__('The {0} has been saved.', 'Aula'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Aulas ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Aula'));
        }
        $this->set(compact('aula'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Aula id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $aula = $this->Aulas->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $aula = $this->Aulas->patchEntity($aula, $this->request->getData());
            if ($this->Aulas->save($aula)) {
                $this->Flash->success(__('The {0} has been saved.', 'Aula'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Aulas ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Aula'));
        }
        $this->set(compact('aula'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Aula id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $aula = $this->Aulas->get($id);
        if ($this->Aulas->delete($aula)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Aula'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Aulas ' . json_encode($aula->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Aula'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
