<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * MensionCarreras Controller
 *
 * @property \App\Model\Table\MensionCarrerasTable $MensionCarreras
 *
 * @method \App\Model\Entity\MensionCarrera[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MensionCarrerasController extends AppController
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
        $mensionCarreras = $this->paginate($this->MensionCarreras);

        $this->set(compact('mensionCarreras'));
    }

    /**
     * View method
     *
     * @param string|null $id Mension Carrera id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $mensionCarrera = $this->MensionCarreras->get($id, [
            'contain' => ['Carreras'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS MensionCarreras ' . json_encode($mensionCarrera->toArray()));

        $this->set('mensionCarrera', $mensionCarrera);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $mensionCarrera = $this->MensionCarreras->newEntity();
        if ($this->request->is('post')) {
            $mensionCarrera = $this->MensionCarreras->patchEntity($mensionCarrera, $this->request->getData());
            if ($this->MensionCarreras->save($mensionCarrera)) {
                $this->Flash->success(__('The {0} has been saved.', 'Mension Carrera'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS MensionCarreras ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Mension Carrera'));
        }
        $this->set(compact('mensionCarrera'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Mension Carrera id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $mensionCarrera = $this->MensionCarreras->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $mensionCarrera = $this->MensionCarreras->patchEntity($mensionCarrera, $this->request->getData());
            if ($this->MensionCarreras->save($mensionCarrera)) {
                $this->Flash->success(__('The {0} has been saved.', 'Mension Carrera'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS MensionCarreras ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Mension Carrera'));
        }
        $this->set(compact('mensionCarrera'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Mension Carrera id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $mensionCarrera = $this->MensionCarreras->get($id);
        if ($this->MensionCarreras->delete($mensionCarrera)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Mension Carrera'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS MensionCarreras ' . json_encode($mensionCarrera->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Mension Carrera'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
