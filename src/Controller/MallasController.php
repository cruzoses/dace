<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Mallas Controller
 *
 * @property \App\Model\Table\MallasTable $Mallas
 *
 * @method \App\Model\Entity\Malla[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MallasController extends AppController
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
            'contain' => ['Programas', 'Trayectos', 'Asignaturas'],
        ];
        $mallas = $this->paginate($this->Mallas);

        $this->set(compact('mallas'));
    }

    /**
     * View method
     *
     * @param string|null $id Malla id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $malla = $this->Mallas->get($id, [
            'contain' => ['Programas', 'Trayectos', 'Asignaturas'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Mallas ' . json_encode($malla->toArray()));

        $this->set('malla', $malla);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $malla = $this->Mallas->newEntity();
        if ($this->request->is('post')) {
            $malla = $this->Mallas->patchEntity($malla, $this->request->getData());
            if ($this->Mallas->save($malla)) {
                $this->Flash->success(__('The {0} has been saved.', 'Malla'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Mallas ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Malla'));
        }
        $programas = $this->Mallas->Programas->find('list', ['limit' => 200]);
        $trayectos = $this->Mallas->Trayectos->find('list', ['limit' => 200]);
        $asignaturas = $this->Mallas->Asignaturas->find('list', ['limit' => 200]);
        $this->set(compact('malla', 'programas', 'trayectos', 'asignaturas'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Malla id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $malla = $this->Mallas->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $malla = $this->Mallas->patchEntity($malla, $this->request->getData());
            if ($this->Mallas->save($malla)) {
                $this->Flash->success(__('The {0} has been saved.', 'Malla'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Mallas ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Malla'));
        }
        $programas = $this->Mallas->Programas->find('list', ['limit' => 200]);
        $trayectos = $this->Mallas->Trayectos->find('list', ['limit' => 200]);
        $asignaturas = $this->Mallas->Asignaturas->find('list', ['limit' => 200]);
        $this->set(compact('malla', 'programas', 'trayectos', 'asignaturas'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Malla id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $malla = $this->Mallas->get($id);
        if ($this->Mallas->delete($malla)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Malla'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Mallas ' . json_encode($malla->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Malla'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
