<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Programas Controller
 *
 * @property \App\Model\Table\ProgramasTable $Programas
 *
 * @method \App\Model\Entity\Programa[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProgramasController extends AppController
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
            'contain' => ['Carreras', 'Subsistemas'],
        ];
        $programas = $this->paginate($this->Programas);

        $this->set(compact('programas'));
    }

    /**
     * View method
     *
     * @param string|null $id Programa id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $programa = $this->Programas->get($id, [
            'contain' => ['Carreras', 'Subsistemas', 'Cursos', 'EstudianteProgramas', 'Mallas'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Programas ' . json_encode($programa->toArray()));

        $this->set('programa', $programa);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $programa = $this->Programas->newEntity();
        if ($this->request->is('post')) {
            $programa = $this->Programas->patchEntity($programa, $this->request->getData());
            if ($this->Programas->save($programa)) {
                $this->Flash->success(__('The {0} has been saved.', 'Programa'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Programas ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Programa'));
        }
        $carreras = $this->Programas->Carreras->find('list', ['limit' => 200]);
        $subsistemas = $this->Programas->Subsistemas->find('list', ['limit' => 200]);
        $this->set(compact('programa', 'carreras', 'subsistemas'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Programa id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $programa = $this->Programas->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $programa = $this->Programas->patchEntity($programa, $this->request->getData());
            if ($this->Programas->save($programa)) {
                $this->Flash->success(__('The {0} has been saved.', 'Programa'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Programas ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Programa'));
        }
        $carreras = $this->Programas->Carreras->find('list', ['limit' => 200]);
        $subsistemas = $this->Programas->Subsistemas->find('list', ['limit' => 200]);
        $this->set(compact('programa', 'carreras', 'subsistemas'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Programa id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $programa = $this->Programas->get($id);
        if ($this->Programas->delete($programa)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Programa'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Programas ' . json_encode($programa->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Programa'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
