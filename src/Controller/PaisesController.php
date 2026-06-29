<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Paises Controller
 *
 * @property \App\Model\Table\PaisesTable $Paises
 *
 * @method \App\Model\Entity\Paise[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PaisesController extends AppController
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
        $conditions = $this->Paises->formatConditions($this->request->getQueryParams());
        $this->paginate['conditions'] = $conditions;
        $paises = $this->paginate($this->Paises);
        $filtros = $this->request->getQuery();
        $searchFields = $this->Paises->getSearchFields();
        $this->set(compact('paises', 'filtros', 'searchFields'));
    }

    /**
     * View method
     *
     * @param string|null $id Paise id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $pais = $this->Paises->get($id, [
            'contain' => ['Estados'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS DEl Pais ' . json_encode($pais->toArray()));
        $this->set('pais', $pais);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $paise = $this->Paises->newEntity();
        if ($this->request->is('post')) {
            $paise = $this->Paises->patchEntity($paise, $this->request->getData());
            if ($this->Paises->save($paise)) {
                $this->Flash->success(__('The {0} has been saved.', 'Paise'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Paises ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Paise'));
        }
        $this->set(compact('paise'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Paise id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $paise = $this->Paises->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $paise = $this->Paises->patchEntity($paise, $this->request->getData());
            if ($this->Paises->save($paise)) {
                $this->Flash->success(__('The {0} has been saved.', 'Paise'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Paises ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Paise'));
        }
        $this->set(compact('paise'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Paise id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $paise = $this->Paises->get($id);
        if ($this->Paises->delete($paise)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Paise'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Paises ' . json_encode($paise->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Paise'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
