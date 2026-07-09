<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Indicadores Controller
 *
 * @property \App\Model\Table\IndicadoresTable $Indicadores
 *
 * @method \App\Model\Entity\Indicadore[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class IndicadoresController extends AppController
{

	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
	}

	public function isAuthorized($user = null)
	{
        if( isset( $user['activo'] ) && isset( $user['rols'] ) && $user['activo'] && $this->tienePermiso([1,2,3]) )
        {
            return true;
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
        $indicadores = $this->paginate($this->Indicadores);

        $this->set(compact('indicadores'));
    }

    /**
     * View method
     *
     * @param string|null $id Indicadore id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $indicadore = $this->Indicadores->get($id, [
            'contain' => [],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Indicadores ' . json_encode($indicadore->toArray()));

        $this->set('indicadore', $indicadore);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $indicadore = $this->Indicadores->newEntity();
        if ($this->request->is('post')) {
            $indicadore = $this->Indicadores->patchEntity($indicadore, $this->request->getData());
            if ($this->Indicadores->save($indicadore)) {
                $this->Flash->success(__('The {0} has been saved.', 'Indicadore'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Indicadores ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Indicadore'));
        }
        $this->set(compact('indicadore'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Indicadore id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $indicadore = $this->Indicadores->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $indicadore = $this->Indicadores->patchEntity($indicadore, $this->request->getData());
            if ($this->Indicadores->save($indicadore)) {
                $this->Flash->success(__('The {0} has been saved.', 'Indicadore'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Indicadores ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Indicadore'));
        }
        $this->set(compact('indicadore'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Indicadore id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $indicadore = $this->Indicadores->get($id);
        if ($this->Indicadores->delete($indicadore)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Indicadore'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Indicadores ' . json_encode($indicadore->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Indicadore'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
