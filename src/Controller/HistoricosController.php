<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Historicos Controller
 *
 * @property \App\Model\Table\HistoricosTable $Historicos
 *
 * @method \App\Model\Entity\Historico[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class HistoricosController extends AppController
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
            'contain' => ['Estudiantes', 'Periodos', 'Asignaturas'],
        ];
        $historicos = $this->paginate($this->Historicos);

        $this->set(compact('historicos'));
    }

    /**
     * View method
     *
     * @param string|null $id Historico id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $historico = $this->Historicos->get($id, [
            'contain' => ['Estudiantes', 'Periodos', 'Asignaturas'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Historicos ' . json_encode($historico->toArray()));

        $this->set('historico', $historico);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $historico = $this->Historicos->newEntity();
        if ($this->request->is('post')) {
            $historico = $this->Historicos->patchEntity($historico, $this->request->getData());
            if ($this->Historicos->save($historico)) {
                $this->Flash->success(__('The {0} has been saved.', 'Historico'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Historicos ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Historico'));
        }
        $estudiantes = $this->Historicos->Estudiantes->find('list', ['limit' => 200]);
        $periodos = $this->Historicos->Periodos->find('list', ['limit' => 200]);
        $asignaturas = $this->Historicos->Asignaturas->find('list', ['limit' => 200]);
        $this->set(compact('historico', 'estudiantes', 'periodos', 'asignaturas'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Historico id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $historico = $this->Historicos->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $historico = $this->Historicos->patchEntity($historico, $this->request->getData());
            if ($this->Historicos->save($historico)) {
                $this->Flash->success(__('The {0} has been saved.', 'Historico'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Historicos ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Historico'));
        }
        $estudiantes = $this->Historicos->Estudiantes->find('list', ['limit' => 200]);
        $periodos = $this->Historicos->Periodos->find('list', ['limit' => 200]);
        $asignaturas = $this->Historicos->Asignaturas->find('list', ['limit' => 200]);
        $this->set(compact('historico', 'estudiantes', 'periodos', 'asignaturas'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Historico id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $historico = $this->Historicos->get($id);
        if ($this->Historicos->delete($historico)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Historico'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Historicos ' . json_encode($historico->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Historico'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
