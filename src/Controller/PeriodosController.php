<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Periodos Controller
 *
 * @property \App\Model\Table\PeriodosTable $Periodos
 *
 * @method \App\Model\Entity\Periodo[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PeriodosController extends AppController
{

	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
	}

	public function isAuthorized($user)
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
        $conditions = $this->Periodos->formatConditions($this->request->getQueryParams());
        $this->paginate['conditions'] = $conditions;

        $periodos = $this->paginate($this->Periodos,['order' => ['Periodos.id' => 'DESC']]);
        $filtros = $this->request->getQuery();

        $searchFields = $this->Periodos->getSearchFields();
        $this->set(compact('periodos', 'filtros', 'searchFields'));
    }

    /**
     * View method
     *
     * @param string|null $id Periodo id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $periodo = $this->Periodos->get($id, [
            'contain' => ['Cursos', 'Historicos'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Periodos ' . json_encode($periodo->toArray()));

        $this->set('periodo', $periodo);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $periodo = $this->Periodos->newEntity();
        if ($this->request->is('post')) {
            $periodo = $this->Periodos->patchEntity($periodo, $this->request->getData());
            if ($this->Periodos->save($periodo)) {
                $this->Flash->success(__('The {0} has been saved.', 'Periodo'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Periodos ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Periodo'));
        }
        $this->set(compact('periodo'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Periodo id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $periodo = $this->Periodos->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $periodo = $this->Periodos->patchEntity($periodo, $this->request->getData());
            if ($this->Periodos->save($periodo)) {
                $this->Flash->success(__('The {0} has been saved.', 'Periodo'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Periodos ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Periodo'));
        }
        $this->set(compact('periodo'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Periodo id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $periodo = $this->Periodos->get($id);
        if ($this->Periodos->delete($periodo)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Periodo'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Periodos ' . json_encode($periodo->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Periodo'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
