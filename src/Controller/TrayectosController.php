<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Trayectos Controller
 *
 * @property \App\Model\Table\TrayectosTable $Trayectos
 *
 * @method \App\Model\Entity\Trayecto[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TrayectosController extends AppController
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
        $trayectos = $this->paginate($this->Trayectos);

        $this->set(compact('trayectos'));
    }

    /**
     * View method
     *
     * @param string|null $id Trayecto id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $trayecto = $this->Trayectos->get($id, [
            'contain' => ['Cursos', 'Mallas'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Trayectos ' . json_encode($trayecto->toArray()));

        $this->set('trayecto', $trayecto);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $trayecto = $this->Trayectos->newEntity();
        if ($this->request->is('post')) {
            $trayecto = $this->Trayectos->patchEntity($trayecto, $this->request->getData());
            if ($this->Trayectos->save($trayecto)) {
                $this->Flash->success(__('The {0} has been saved.', 'Trayecto'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Trayectos ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Trayecto'));
        }
        $this->set(compact('trayecto'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Trayecto id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $trayecto = $this->Trayectos->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $trayecto = $this->Trayectos->patchEntity($trayecto, $this->request->getData());
            if ($this->Trayectos->save($trayecto)) {
                $this->Flash->success(__('The {0} has been saved.', 'Trayecto'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Trayectos ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Trayecto'));
        }
        $this->set(compact('trayecto'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Trayecto id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $trayecto = $this->Trayectos->get($id);
        if ($this->Trayectos->delete($trayecto)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Trayecto'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Trayectos ' . json_encode($trayecto->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Trayecto'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
