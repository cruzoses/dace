<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Actos Controller
 *
 * @property \App\Model\Table\ActosTable $Actos
 *
 * @method \App\Model\Entity\Acto[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
*/
class ActosController extends AppController
{

	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
	}

	public function isAuthorized($user = null)
	{
        if( isset( $user['activo'] ) && isset( $user['rols'] ) && $user['activo'] )
        {
            if ($this->tienePermiso([2,3])) {
                return true;
            }            
        }
		return parent::isAuthorized($user);
	}
	
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
    */
    public function index()
    {
        $conditions = $this->Actos->formatConditions($this->request->getQueryParams());
        $this->paginate['conditions'] = $conditions;
        $this->paginate['order'] = ['Actos.id' => 'desc'];
        $this->paginate['limit'] = 10;

        $actos = $this->paginate($this->Actos);
        $filtros = $this->request->getQuery();

        $searchFields = $this->Actos->getSearchFields();

        $this->set(compact('actos', 'filtros', 'searchFields'));
    }

    /**
     * View method
     *
     * @param string|null $id Acto id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $acto = $this->Actos->get($id, [
            'contain' => ['Graduandos'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Actos ' . json_encode($acto->toArray()));

        $this->set('acto', $acto);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $acto = $this->Actos->newEntity();
        if ($this->request->is('post')) {
            $acto = $this->Actos->patchEntity($acto, $this->request->getData());
            if ($this->Actos->save($acto)) {
                $this->Flash->success(__('The {0} has been saved.', 'Acto'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Actos ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Acto'));
        }
        $this->set(compact('acto'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Acto id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $acto = $this->Actos->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $acto = $this->Actos->patchEntity($acto, $this->request->getData());
            if ($this->Actos->save($acto)) {
                $this->Flash->success(__('The {0} has been saved.', 'Acto'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Actos ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Acto'));
        }
        $this->set(compact('acto'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Acto id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $acto = $this->Actos->get($id);
        if ($this->Actos->delete($acto)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Acto'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Actos ' . json_encode($acto->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Acto'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
