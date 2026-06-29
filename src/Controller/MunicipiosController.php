<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Municipios Controller
 *
 * @property \App\Model\Table\MunicipiosTable $Municipios
 *
 * @method \App\Model\Entity\Municipio[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
*/
class MunicipiosController extends AppController
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
        $conditions = $this->Municipios->formatConditions($this->request->getQueryParams());
        $this->paginate = [
            'contain' => ['Estados'],
            'conditions' => $conditions,
        ];
        $municipios = $this->paginate($this->Municipios);
        $filtros = $this->request->getQuery();
        $searchFields = $this->Municipios->getSearchFields();
        $searchFields['estado_id']['options'] = $this->Municipios->Estados->find('list')->toArray();
        $this->set(compact('municipios', 'filtros', 'searchFields'));
    }

    /**
     * View method
     *
     * @param string|null $id Municipio id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $municipio = $this->Municipios->get($id, [
            'contain' => ['Estados', 'Estudiantes', 'Parroquias'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Municipios ' . json_encode($municipio->toArray()));

        $this->set('municipio', $municipio);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $municipio = $this->Municipios->newEntity();
        if ($this->request->is('post')) {
            $municipio = $this->Municipios->patchEntity($municipio, $this->request->getData());
            if ($this->Municipios->save($municipio)) {
                $this->Flash->success(__('The {0} has been saved.', 'Municipio'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Municipios ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Municipio'));
        }
        $estados = $this->Municipios->Estados->find('list', ['limit' => 200]);
        $this->set(compact('municipio', 'estados'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Municipio id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $municipio = $this->Municipios->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $municipio = $this->Municipios->patchEntity($municipio, $this->request->getData());
            if ($this->Municipios->save($municipio)) {
                $this->Flash->success(__('The {0} has been saved.', 'Municipio'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Municipios ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Municipio'));
        }
        $estados = $this->Municipios->Estados->find('list', ['limit' => 200]);
        $this->set(compact('municipio', 'estados'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Municipio id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $municipio = $this->Municipios->get($id);
        if ($this->Municipios->delete($municipio)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Municipio'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Municipios ' . json_encode($municipio->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Municipio'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
