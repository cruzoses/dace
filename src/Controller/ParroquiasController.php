<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Parroquias Controller
 *
 * @property \App\Model\Table\ParroquiasTable $Parroquias
 *
 * @method \App\Model\Entity\Parroquia[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ParroquiasController extends AppController
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
        $conditions = $this->Parroquias->formatConditions($this->request->getQueryParams());
        $this->paginate = [
            'contain' => ['Municipios'],
            'conditions' => $conditions,
        ];
        $parroquias = $this->paginate($this->Parroquias);
        $filtros = $this->request->getQuery();
        $searchFields = $this->Parroquias->getSearchFields();
        $searchFields['municipio_id']['options'] = $this->Parroquias->Municipios->find('list')->toArray();
        $this->set(compact('parroquias', 'filtros', 'searchFields'));
    }

    /**
     * View method
     *
     * @param string|null $id Parroquia id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $parroquia = $this->Parroquias->get($id, [
            'contain' => ['Municipios', 'Estudiantes'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Parroquias ' . json_encode($parroquia->toArray()));

        $this->set('parroquia', $parroquia);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $parroquia = $this->Parroquias->newEntity();
        if ($this->request->is('post')) {
            $parroquia = $this->Parroquias->patchEntity($parroquia, $this->request->getData());
            if ($this->Parroquias->save($parroquia)) {
                $this->Flash->success(__('The {0} has been saved.', 'Parroquia'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Parroquias ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Parroquia'));
        }
        $municipios = $this->Parroquias->Municipios->find('list', ['limit' => 200]);
        $this->set(compact('parroquia', 'municipios'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Parroquia id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $parroquia = $this->Parroquias->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $parroquia = $this->Parroquias->patchEntity($parroquia, $this->request->getData());
            if ($this->Parroquias->save($parroquia)) {
                $this->Flash->success(__('The {0} has been saved.', 'Parroquia'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Parroquias ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Parroquia'));
        }
        $municipios = $this->Parroquias->Municipios->find('list', ['limit' => 200]);
        $this->set(compact('parroquia', 'municipios'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Parroquia id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $parroquia = $this->Parroquias->get($id);
        if ($this->Parroquias->delete($parroquia)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Parroquia'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Parroquias ' . json_encode($parroquia->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Parroquia'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
