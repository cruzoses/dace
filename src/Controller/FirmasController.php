<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Firmas Controller
 *
 * @property \App\Model\Table\FirmasTable $Firmas
 *
 * @method \App\Model\Entity\Firma[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FirmasController extends AppController
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
        $firmas = $this->paginate($this->Firmas);

        $this->set(compact('firmas'));
    }

    /**
     * View method
     *
     * @param string|null $id Firma id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $firma = $this->Firmas->get($id, [
            'contain' => [],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Firmas ' . json_encode($firma->toArray()));

        $this->set('firma', $firma);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $firma = $this->Firmas->newEntity();
        if ($this->request->is('post')) {
            $firma = $this->Firmas->patchEntity($firma, $this->request->getData());
            if ($this->Firmas->save($firma)) {
                $this->Flash->success(__('The {0} has been saved.', 'Firma'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Firmas ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Firma'));
        }
        $this->set(compact('firma'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Firma id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $firma = $this->Firmas->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $firma = $this->Firmas->patchEntity($firma, $this->request->getData());
            if ($this->Firmas->save($firma)) {
                $this->Flash->success(__('The {0} has been saved.', 'Firma'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Firmas ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Firma'));
        }
        $this->set(compact('firma'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Firma id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $firma = $this->Firmas->get($id);
        if ($this->Firmas->delete($firma)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Firma'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Firmas ' . json_encode($firma->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Firma'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
