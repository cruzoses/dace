<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Carreras Controller
 *
 * @property \App\Model\Table\CarrerasTable $Carreras
 *
 * @method \App\Model\Entity\Carrera[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CarrerasController extends AppController
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
        $conditions = $this->Carreras->formatConditions($this->request->getQueryParams());
        $this->paginate['conditions'] = $conditions;
        $this->paginate['contain'] = ['MensionCarreras'];

        $carreras = $this->paginate($this->Carreras);
        $filtros = $this->request->getQuery();

        $searchFields = $this->Carreras->getSearchFields();
        $searchFields['mension_carrera_id']['options'] = $this->Carreras->MensionCarreras->find('list', ['limit' => 200])->toArray();

        $this->set(compact('carreras', 'filtros', 'searchFields'));
    }

    /**
     * View method
     *
     * @param string|null $id Carrera id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $carrera = $this->Carreras->get($id, [
            'contain' => ['MensionCarreras', 'Sedes', 'Cursos', 'Programas'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Carreras ' . json_encode($carrera->toArray()));

        $this->set('carrera', $carrera);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $carrera = $this->Carreras->newEntity();
        if ($this->request->is('post')) {
            $carrera = $this->Carreras->patchEntity($carrera, $this->request->getData());
            if ($this->Carreras->save($carrera)) {
                $this->Flash->success(__('The {0} has been saved.', 'Carrera'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Carreras ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Carrera'));
        }
        $mensionCarreras = $this->Carreras->MensionCarreras->find('list', ['limit' => 200]);
        $sedes = $this->Carreras->Sedes->find('list', ['limit' => 200]);
        $this->set(compact('carrera', 'mensionCarreras', 'sedes'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Carrera id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $carrera = $this->Carreras->get($id, [
            'contain' => ['Sedes']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $carrera = $this->Carreras->patchEntity($carrera, $this->request->getData());
            if ($this->Carreras->save($carrera)) {
                $this->Flash->success(__('The {0} has been saved.', 'Carrera'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Carreras ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Carrera'));
        }
        $mensionCarreras = $this->Carreras->MensionCarreras->find('list', ['limit' => 200]);
        $sedes = $this->Carreras->Sedes->find('list', ['limit' => 200]);
        $this->set(compact('carrera', 'mensionCarreras', 'sedes'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Carrera id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $carrera = $this->Carreras->get($id);
        if ($this->Carreras->delete($carrera)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Carrera'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Carreras ' . json_encode($carrera->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Carrera'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
