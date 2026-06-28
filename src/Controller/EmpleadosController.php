<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Empleados Controller
 *
 * @property \App\Model\Table\EmpleadosTable $Empleados
 *
 * @method \App\Model\Entity\Empleado[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EmpleadosController extends AppController
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
            'contain' => ['Usuarios'],
        ];
        $empleados = $this->paginate($this->Empleados);
        $this->set(compact('empleados'));
    }

    /**
     * View method
     *
     * @param string|null $id Empleado id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $empleado = $this->Empleados->get($id, [
            'contain' => ['Usuarios'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Empleados ' . json_encode($empleado->toArray()));

        $this->set('empleado', $empleado);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $empleado = $this->Empleados->newEntity();
        if ($this->request->is('post')) {
            $empleado = $this->Empleados->patchEntity($empleado, $this->request->getData());
            if ($this->Empleados->save($empleado)) {
                $this->Flash->success(__('The {0} has been saved.', 'Empleado'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Empleados ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Empleado'));
        }
        $usuarios = $this->Empleados->Usuarios->find('list', ['limit' => 200]);
        $this->set(compact('empleado', 'usuarios'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Empleado id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $empleado = $this->Empleados->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $empleado = $this->Empleados->patchEntity($empleado, $this->request->getData());
            if ($this->Empleados->save($empleado)) {
                $this->Flash->success(__('The {0} has been saved.', 'Empleado'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Empleados ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Empleado'));
        }
        $usuarios = $this->Empleados->Usuarios->find('list', ['limit' => 200]);
        $this->set(compact('empleado', 'usuarios'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Empleado id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $empleado = $this->Empleados->get($id);
        if ($this->Empleados->delete($empleado)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Empleado'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Empleados ' . json_encode($empleado->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Empleado'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
