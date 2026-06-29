<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Estados Controller
 *
 * @property \App\Model\Table\EstadosTable $Estados
 *
 * @method \App\Model\Entity\Estado[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EstadosController extends AppController
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
            'contain' => ['Paises'],
        ];
        $estados = $this->paginate($this->Estados);

        $this->set(compact('estados'));
    }

    /**
     * View method
     *
     * @param string|null $id Estado id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $estado = $this->Estados->get($id, [
            'contain' => ['Paises', 'Estudiantes', 'Municipios'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Estados ' . json_encode($estado->toArray()));

        $this->set('estado', $estado);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $estado = $this->Estados->newEntity();
        if ($this->request->is('post')) {
            $estado = $this->Estados->patchEntity($estado, $this->request->getData());
            if ($this->Estados->save($estado)) {
                $this->Flash->success(__('The {0} has been saved.', 'Estado'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Estados ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Estado'));
        }
        $paises = $this->Estados->Paises->find('list', ['limit' => 200]);
        $this->set(compact('estado', 'paises'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Estado id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $estado = $this->Estados->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $estado = $this->Estados->patchEntity($estado, $this->request->getData());
            if ($this->Estados->save($estado)) {
                $this->Flash->success(__('The {0} has been saved.', 'Estado'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Estados ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Estado'));
        }
        $paises = $this->Estados->Paises->find('list', ['limit' => 200]);
        $this->set(compact('estado', 'paises'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Estado id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $estado = $this->Estados->get($id);
        if ($this->Estados->delete($estado)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Estado'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Estados ' . json_encode($estado->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Estado'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
