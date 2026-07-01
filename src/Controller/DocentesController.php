<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Docentes Controller
 *
 * @property \App\Model\Table\DocentesTable $Docentes
 *
 * @method \App\Model\Entity\Docente[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DocentesController extends AppController
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
        $conditions = $this->Docentes->formatConditions($this->request->getQueryParams());
        $this->paginate = [
            'contain' => ['Departamentos', 'Usuarios'],
            'conditions' => $conditions,
        ];
        $docentes = $this->paginate($this->Docentes);
        $filtros = $this->request->getQuery();
        $searchFields = $this->Docentes->getSearchFields();

        $searchFields['departamento_id']['options'] = $this->Docentes->Departamentos->find('list', ['limit' => 200])->toArray();

        $this->set(compact('docentes', 'filtros', 'searchFields'));
    }

    /**
     * View method
     *
     * @param string|null $id Docente id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $docente = $this->Docentes->get($id, [
            'contain' => ['Departamentos', 'Usuarios', 'Cursos'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Docentes ' . json_encode($docente->toArray()));

        $this->set('docente', $docente);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $docente = $this->Docentes->newEntity();
        if ($this->request->is('post')) {
            $docente = $this->Docentes->patchEntity($docente, $this->request->getData());
            if ($this->Docentes->save($docente)) {
                $this->Flash->success(__('The {0} has been saved.', 'Docente'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Docentes ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Docente'));
        }
        $departamentos = $this->Docentes->Departamentos->find('list', ['limit' => 200]);
        $usuarios = $this->Docentes->Usuarios->find('list', ['limit' => 200]);
        $this->set(compact('docente', 'departamentos', 'usuarios'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Docente id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $docente = $this->Docentes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $docente = $this->Docentes->patchEntity($docente, $this->request->getData());
            if ($this->Docentes->save($docente)) {
                $this->Flash->success(__('The {0} has been saved.', 'Docente'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Docentes ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Docente'));
        }
        $departamentos = $this->Docentes->Departamentos->find('list', ['limit' => 200]);
        $usuarios = $this->Docentes->Usuarios->find('list', ['limit' => 200]);
        $this->set(compact('docente', 'departamentos', 'usuarios'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Docente id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $docente = $this->Docentes->get($id);
        if ($this->Docentes->delete($docente)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Docente'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Docentes ' . json_encode($docente->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Docente'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
