<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Graduandos Controller
 *
 * @property \App\Model\Table\GraduandosTable $Graduandos
 *
 * @method \App\Model\Entity\Graduando[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GraduandosController extends AppController
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
            'contain' => ['Actos', 'Carreras', 'Programas', 'Estudiantes'],
        ];
        $graduandos = $this->paginate($this->Graduandos);

        $this->set(compact('graduandos'));
    }

    /**
     * View method
     *
     * @param string|null $id Graduando id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $graduando = $this->Graduandos->get($id, [
            'contain' => ['Actos', 'Carreras', 'Programas', 'Estudiantes'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Graduandos ' . json_encode($graduando->toArray()));

        $this->set('graduando', $graduando);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $graduando = $this->Graduandos->newEntity();
        if ($this->request->is('post')) {
            $graduando = $this->Graduandos->patchEntity($graduando, $this->request->getData());
            if ($this->Graduandos->save($graduando)) {
                $this->Flash->success(__('The {0} has been saved.', 'Graduando'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Graduandos ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Graduando'));
        }
        $actos = $this->Graduandos->Actos->find('list', ['limit' => 200]);
        $carreras = $this->Graduandos->Carreras->find('list', ['limit' => 200]);
        $programas = $this->Graduandos->Programas->find('list', ['limit' => 200]);
        $estudiantes = $this->Graduandos->Estudiantes->find('list', ['limit' => 200]);
        $this->set(compact('graduando', 'actos', 'carreras', 'programas', 'estudiantes'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Graduando id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $graduando = $this->Graduandos->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $graduando = $this->Graduandos->patchEntity($graduando, $this->request->getData());
            if ($this->Graduandos->save($graduando)) {
                $this->Flash->success(__('The {0} has been saved.', 'Graduando'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Graduandos ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Graduando'));
        }
        $actos = $this->Graduandos->Actos->find('list', ['limit' => 200]);
        $carreras = $this->Graduandos->Carreras->find('list', ['limit' => 200]);
        $programas = $this->Graduandos->Programas->find('list', ['limit' => 200]);
        $estudiantes = $this->Graduandos->Estudiantes->find('list', ['limit' => 200]);
        $this->set(compact('graduando', 'actos', 'carreras', 'programas', 'estudiantes'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Graduando id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $graduando = $this->Graduandos->get($id);
        if ($this->Graduandos->delete($graduando)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Graduando'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Graduandos ' . json_encode($graduando->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Graduando'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
