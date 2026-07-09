<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Auditorias Controller
 *
 * @property \App\Model\Table\AuditoriasTable $Auditorias
 *
 * @method \App\Model\Entity\Auditoria[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AuditoriasController extends AppController
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
        $this->paginate = [
            'contain' => ['Usuarios'],
        ];
        $auditorias = $this->paginate($this->Auditorias);

        $this->set(compact('auditorias'));
    }

    /**
     * View method
     *
     * @param string|null $id Auditoria id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $auditoria = $this->Auditorias->get($id, [
            'contain' => ['Usuarios'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Auditorias ' . json_encode($auditoria->toArray()));

        $this->set('auditoria', $auditoria);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $auditoria = $this->Auditorias->newEntity();
        if ($this->request->is('post')) {
            $auditoria = $this->Auditorias->patchEntity($auditoria, $this->request->getData());
            if ($this->Auditorias->save($auditoria)) {
                $this->Flash->success(__('The {0} has been saved.', 'Auditoria'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Auditorias ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Auditoria'));
        }
        $usuarios = $this->Auditorias->Usuarios->find('list', ['limit' => 200]);
        $this->set(compact('auditoria', 'usuarios'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Auditoria id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $auditoria = $this->Auditorias->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $auditoria = $this->Auditorias->patchEntity($auditoria, $this->request->getData());
            if ($this->Auditorias->save($auditoria)) {
                $this->Flash->success(__('The {0} has been saved.', 'Auditoria'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Auditorias ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Auditoria'));
        }
        $usuarios = $this->Auditorias->Usuarios->find('list', ['limit' => 200]);
        $this->set(compact('auditoria', 'usuarios'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Auditoria id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $auditoria = $this->Auditorias->get($id);
        if ($this->Auditorias->delete($auditoria)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Auditoria'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Auditorias ' . json_encode($auditoria->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Auditoria'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
