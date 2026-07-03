<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Subsistemas Controller
 *
 * @property \App\Model\Table\SubsistemasTable $Subsistemas
 *
 * @method \App\Model\Entity\Subsistema[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SubsistemasController extends AppController
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
        $subsistemas = $this->paginate($this->Subsistemas);

        $this->set(compact('subsistemas'));
    }

    /**
     * View method
     *
     * @param string|null $id Subsistema id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $subsistema = $this->Subsistemas->get($id, [
            'contain' => ['Programas'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Subsistemas ' . json_encode($subsistema->toArray()));

        $this->set('subsistema', $subsistema);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $subsistema = $this->Subsistemas->newEntity();
        if ($this->request->is('post')) {
            $subsistema = $this->Subsistemas->patchEntity($subsistema, $this->request->getData());
            if ($this->Subsistemas->save($subsistema)) {
                $this->Flash->success(__('The {0} has been saved.', 'Subsistema'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Subsistemas ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Subsistema'));
        }
        $this->set(compact('subsistema'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Subsistema id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $subsistema = $this->Subsistemas->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $subsistema = $this->Subsistemas->patchEntity($subsistema, $this->request->getData());
            if ($this->Subsistemas->save($subsistema)) {
                $this->Flash->success(__('The {0} has been saved.', 'Subsistema'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Subsistemas ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Subsistema'));
        }
        $this->set(compact('subsistema'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Subsistema id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $subsistema = $this->Subsistemas->get($id);
        if ($this->Subsistemas->delete($subsistema)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Subsistema'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Subsistemas ' . json_encode($subsistema->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Subsistema'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
