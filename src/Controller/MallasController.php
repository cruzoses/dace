<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Mallas Controller
 *
 * @property \App\Model\Table\MallasTable $Mallas
 *
 * @method \App\Model\Entity\Malla[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
*/
class MallasController extends AppController
{

	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
	}

	public function isAuthorized($user = null)
	{
        if( isset( $user['activo'] ) && isset( $user['rols'] ) && $user['activo'] && $this->tienePermiso([2,3]) )
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
        $conditions = $this->Mallas->formatConditions($this->request->getQueryParams());
        $this->paginate = [
            'contain' => ['Carreras', 'Programas', 'Trayectos', 'Asignaturas'],
            'conditions' => $conditions,
        ];
        $mallas = $this->paginate($this->Mallas,['order' => ['Mallas.id' => 'ASC']]);
        $filtros = $this->request->getQuery();
        $searchFields = $this->Mallas->getSearchFields();

        $searchFields['carrera_id']['options'] = $this->Mallas->Carreras->find('list')->where(['Carreras.activa' => 1])->order(['Carreras.nombre' => 'ASC'])->toArray();
        $searchFields['programa_id']['options'] = $this->Mallas->Programas->find('list')->where(['Programas.activo' => 1])->order(['Programas.nombre' => 'ASC'])->toArray();
        $searchFields['trayecto_id']['options'] = $this->Mallas->Trayectos->find('list')->where(['Trayectos.activo' => 1])->order(['Trayectos.id' => 'ASC'])->toArray();
        $searchFields['asignatura_id']['options'] = $this->Mallas->Asignaturas->find('list')->where(['Asignaturas.activa' => 1])->order(['Asignaturas.nombre' => 'ASC'])->toArray();

        $this->set(compact('mallas', 'filtros', 'searchFields'));
    }

    /**
     * View method
     *
     * @param string|null $id Malla id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $malla = $this->Mallas->get($id, [
            'contain' => ['Carreras', 'Programas', 'Trayectos', 'Asignaturas'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Mallas ' . json_encode($malla->toArray()));

        $this->set('malla', $malla);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $malla = $this->Mallas->newEntity();
        if ($this->request->is('post')) 
        {
            $malla = $this->Mallas->patchEntity($malla, $this->request->getData());
            if ($this->Mallas->save($malla)) 
            {
                $this->Flash->success(__('The {0} has been saved.', 'Malla'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Mallas ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Malla'));
        }
        $carreras = $this->Mallas->Carreras->find('list')->where(['Carreras.activa' => 1])->order(['Carreras.nombre' => 'ASC']);
        $programas = $this->Mallas->Programas->find('list')->where(['Programas.activo' => 1])->order(['Programas.nombre' => 'ASC']);
        $trayectos = $this->Mallas->Trayectos->find('list')->where(['Trayectos.activo' => 1])->order(['Trayectos.id' => 'ASC']);
        $asignaturas = $this->Mallas->Asignaturas->find('list')->where(['Asignaturas.activa' => 1])->order(['Asignaturas.nombre' => 'ASC']);
        $this->set(compact('malla', 'carreras', 'programas', 'trayectos', 'asignaturas'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Malla id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $malla = $this->Mallas->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) 
        {
            $malla = $this->Mallas->patchEntity($malla, $this->request->getData());
            if ($this->Mallas->save($malla)) 
            {
                $this->Flash->success(__('The {0} has been saved.', 'Malla'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Mallas ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Malla'));
        }
        $carreras = $this->Mallas->Carreras->find('list')->where(['Carreras.activa' => 1])->order(['Carreras.nombre' => 'ASC']);
        $programas = $this->Mallas->Programas->find('list')->where(['Programas.activo' => 1])->order(['Programas.nombre' => 'ASC']);
        $trayectos = $this->Mallas->Trayectos->find('list')->where(['Trayectos.activo' => 1])->order(['Trayectos.nombre' => 'ASC']);
        $asignaturas = $this->Mallas->Asignaturas->find('list')->where(['Asignaturas.activa' => 1])->order(['Asignaturas.nombre' => 'ASC']);
        $this->set(compact('malla', 'carreras', 'programas', 'trayectos', 'asignaturas'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Malla id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $malla = $this->Mallas->get($id);
        if ($this->Mallas->delete($malla)) 
        {
            $this->Flash->success(__('The {0} has been deleted.', 'Malla'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Mallas ' . json_encode($malla->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Malla'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Mallas agrupadas por carrera, programa y trayecto con cantidad de asignaturas
     *
     * @return \Cake\Http\Response|null
     */
    public function agrupadas()
    {
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            unset($data['_csrfToken']);
            $queryParams = array_map('trim', array_filter($data));
            return $this->redirect(['action' => 'agrupadas', '?' => $queryParams]);
        }

        $conditions = $this->Mallas->formatConditions($this->request->getQueryParams());

        $query = $this->Mallas->find();
        $query->select([
            'carrera_id' => 'Mallas.carrera_id',
            'programa_id' => 'Mallas.programa_id',
            'trayecto_id' => 'Mallas.trayecto_id',
            'total_asignaturas' => $query->func()->count('Mallas.asignatura_id'),
            'carrera_codigo' => 'Carreras.codigo',
            'programa_codigo' => 'Programas.codigo',
            'trayecto_codigo' => 'Trayectos.codigo',
        ])
        ->join([
            'Carreras' => [
                'table' => 'carreras',
                'type' => 'INNER',
                'conditions' => 'Carreras.id = Mallas.carrera_id',
            ],
            'Programas' => [
                'table' => 'programas',
                'type' => 'INNER',
                'conditions' => 'Programas.id = Mallas.programa_id',
            ],
            'Trayectos' => [
                'table' => 'trayectos',
                'type' => 'INNER',
                'conditions' => 'Trayectos.id = Mallas.trayecto_id',
            ],
        ])
        ->where($conditions)
        ->group(['Mallas.carrera_id', 'Mallas.programa_id', 'Mallas.trayecto_id'])
        ->order(['Mallas.carrera_id' => 'ASC', 'Mallas.programa_id' => 'ASC', 'Mallas.trayecto_id' => 'ASC']);

        $mallasAgrupadas = $this->paginate($query);

        $filtros = $this->request->getQuery();
        $searchFields = array_intersect_key($this->Mallas->getSearchFields(), array_flip(['carrera_id', 'programa_id', 'trayecto_id']));

        $searchFields['carrera_id']['options'] = $this->Mallas->Carreras->find('list')->where(['Carreras.activa' => 1])->order(['Carreras.nombre' => 'ASC'])->toArray();
        $searchFields['programa_id']['options'] = $this->Mallas->Programas->find('list')->where(['Programas.activo' => 1])->order(['Programas.nombre' => 'ASC'])->toArray();
        $searchFields['trayecto_id']['options'] = $this->Mallas->Trayectos->find('list')->where(['Trayectos.activo' => 1])->order(['Trayectos.id' => 'ASC'])->toArray();

        $this->set(compact('mallasAgrupadas', 'filtros', 'searchFields'));
    }

    /**
     * Get programas by carrera_id (AJAX)
     *
     * @return \Cake\Http\Response
    */
    public function getProgramas()
    {
        $this->request->allowMethod(['ajax', 'get']);
        $carrera_id = $this->request->getQuery('carrera_id');

        $programas = [];
        if ($carrera_id) {
            $programas = $this->Mallas->Programas->find('list', ['limit' => 200])
                ->where(['carrera_id' => $carrera_id, 'activo' => 1])
                ->toArray();
        }

        $this->set(compact('programas'));
        $this->set('_serialize', ['programas']);
    }
}
