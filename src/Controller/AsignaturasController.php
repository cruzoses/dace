<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;

/**
 * Asignaturas Controller
 *
 * @property \App\Model\Table\AsignaturasTable $Asignaturas
 *
 * @method \App\Model\Entity\Asignatura[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AsignaturasController extends AppController
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
        $conditions = $this->Asignaturas->formatConditions($this->request->getQueryParams());
        $this->paginate['conditions'] = $conditions;
        $this->paginate['contain'] = ['GrupoAsignaturas'];

        $asignaturas = $this->paginate($this->Asignaturas);
        $filtros = $this->request->getQuery();

        $searchFields = $this->Asignaturas->getSearchFields();
        $searchFields['grupo_asignatura_id']['options'] = $this->Asignaturas->GrupoAsignaturas->find('list', ['limit' => 200])->toArray();

        $this->set(compact('asignaturas', 'filtros', 'searchFields'));
    }

    /**
     * View method
     *
     * @param string|null $id Asignatura id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $asignatura = $this->Asignaturas->get($id, [
            'contain' => ['GrupoAsignaturas', 'Historicos', 'Mallas'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Asignaturas ' . json_encode($asignatura->toArray()));

        $this->set('asignatura', $asignatura);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $asignatura = $this->Asignaturas->newEntity();
        if ($this->request->is('post')) 
        {
            $asignatura = $this->Asignaturas->patchEntity($asignatura, $this->request->getData());
            if ($this->Asignaturas->save($asignatura)) 
            {
                $this->Flash->success(__('The {0} has been saved.', 'Asignatura'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Asignaturas ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Asignatura'));
        }
        $aFrecuencia = Configure::read('aFrecuencia');
        $aTipoNota = Configure::read('aTipoNota');
        $grupoAsignaturas = $this->Asignaturas->GrupoAsignaturas->find('list', ['limit' => 200]);
        $this->set(compact('asignatura', 'aFrecuencia', 'aTipoNota', 'grupoAsignaturas'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Asignatura id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $asignatura = $this->Asignaturas->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) 
        {
            $asignatura = $this->Asignaturas->patchEntity($asignatura, $this->request->getData());
            if ($this->Asignaturas->save($asignatura)) 
            {
                $this->Flash->success(__('The {0} has been saved.', 'Asignatura'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Asignaturas ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Asignatura'));
        }
        $aFrecuencia = Configure::read('aFrecuencia');
        $aTipoNota = Configure::read('aTipoNota');
        $grupoAsignaturas = $this->Asignaturas->GrupoAsignaturas->find('list', ['limit' => 200]);
        $this->set(compact('asignatura', 'aFrecuencia', 'aTipoNota', 'grupoAsignaturas'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Asignatura id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $asignatura = $this->Asignaturas->get($id);
        if ($this->Asignaturas->delete($asignatura)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Asignatura'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Asignaturas ' . json_encode($asignatura->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Asignatura'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
