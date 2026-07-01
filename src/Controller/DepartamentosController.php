<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Departamentos Controller
 *
 * @property \App\Model\Table\DepartamentosTable $Departamentos
 *
 * @method \App\Model\Entity\Departamento[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DepartamentosController extends AppController
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
        $departamentos = $this->paginate($this->Departamentos);

        $this->set(compact('departamentos'));
    }

    /**
     * View method
     *
     * @param string|null $id Departamento id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $departamento = $this->Departamentos->get($id, [
            'contain' => ['Docentes'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Departamentos ' . json_encode($departamento->toArray()));

        $this->set('departamento', $departamento);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $departamento = $this->Departamentos->newEntity();
        if ($this->request->is('post')) {
            $departamento = $this->Departamentos->patchEntity($departamento, $this->request->getData());
            if ($this->Departamentos->save($departamento)) {
                $this->Flash->success(__('The {0} has been saved.', 'Departamento'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Departamentos ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Departamento'));
        }
        $this->set(compact('departamento'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Departamento id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $departamento = $this->Departamentos->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $departamento = $this->Departamentos->patchEntity($departamento, $this->request->getData());
            if ($this->Departamentos->save($departamento)) {
                $this->Flash->success(__('The {0} has been saved.', 'Departamento'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Departamentos ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Departamento'));
        }
        $this->set(compact('departamento'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Departamento id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $departamento = $this->Departamentos->get($id);
        if ($this->Departamentos->delete($departamento)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Departamento'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Departamentos ' . json_encode($departamento->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Departamento'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
