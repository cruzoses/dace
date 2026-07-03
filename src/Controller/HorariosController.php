<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;

/**
 * Horarios Controller
 *
 * @property \App\Model\Table\HorariosTable $Horarios
 *
 * @method \App\Model\Entity\Horario[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class HorariosController extends AppController
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
        $conditions = $this->Horarios->formatConditions($this->request->getQueryParams());
        $this->paginate = [
            'conditions' => $conditions,
            'contain' => ['Sedes', 'Periodos'],
        ];
        $horarios = $this->paginate($this->Horarios);
        $filtros = $this->request->getQuery();

        $searchFields = $this->Horarios->getSearchFields();
        $aDias = Configure::read('aDias');
        $aTurnos = Configure::read('aTurnos');
        $searchFields['sede_id']['options'] = $this->Horarios->Sedes->find('list', ['limit' => 200])->where(['Sedes.activa' => 1])->toArray();
        $searchFields['periodo_id']['options'] = $this->Horarios->Periodos->find('list', ['limit' => 200])->where(['Periodos.activo' => 1])->toArray();
        $searchFields['dia']['options'] = $aDias;
        $searchFields['turno']['options'] = $aTurnos;

        $this->set(compact('horarios', 'filtros', 'searchFields', 'aDias', 'aTurnos'));
    }

    /**
     * View method
     *
     * @param string|null $id Horario id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $horario = $this->Horarios->get($id, [
            'contain' => ['Sedes', 'Periodos'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Horarios - ID: ' . $horario->id . ', Código: ' . $horario->codigo);

        $aDias = Configure::read('aDias');
        $aTurnos = Configure::read('aTurnos');
        $this->set(compact('horario', 'aDias', 'aTurnos'));
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $horario = $this->Horarios->newEntity();
        if ($this->request->is('post')) 
        {
            $data = $this->request->getData();
            $data['codigo'] = $this->generarCodigo($data);
            $horario = $this->Horarios->patchEntity($horario, $data);
            if ($this->Horarios->save($horario)) 
            {
                $this->Flash->success(__('The {0} has been saved.', 'Horario'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Horarios - ID: ' . $horario->id . ', Código: ' . $horario->codigo);

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Horario'));
        }
        $aDias = Configure::read('aDias');
        $aTurnos = Configure::read('aTurnos');
        $sedes = $this->Horarios->Sedes->find('list', ['limit' => 200])->where(['Sedes.activa' => 1]);
        $periodos = $this->Horarios->Periodos->find('list', ['limit' => 200])->where(['Periodos.activo' => 1])->order(['Periodos.id' => 'DESC']);
        $this->set(compact('horario', 'sedes', 'periodos', 'aDias', 'aTurnos'));
    }

    private function generarCodigo($data)
    {
        $aDias = Configure::read('aDias');
        $aTurnos = Configure::read('aTurnos');

        $diaTexto = $aDias[$data['dia']] ?? '';
        $turnoTexto = $aTurnos[$data['turno']] ?? '';

        $prefijoDia = mb_strtoupper(mb_substr($diaTexto, 0, 2));
        $prefijoTurno = mb_strtoupper(mb_substr($turnoTexto, 0, 2));

        $desde = str_replace(' ', '', $data['desde']);
        $hasta = str_replace(' ', '', $data['hasta']);
        return $prefijoDia . $prefijoTurno . $desde . 'A' . $hasta;
    }


    /**
     * Edit method
     *
     * @param string|null $id Horario id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $horario = $this->Horarios->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) 
        {
            $data = $this->request->getData();
            $data['codigo'] = $this->generarCodigo($data);
            $horario = $this->Horarios->patchEntity($horario, $data);
            if ($this->Horarios->save($horario)) 
            {
                $this->Flash->success(__('The {0} has been saved.', 'Horario'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Horarios - ID: ' . $horario->id . ', Código: ' . $horario->codigo);

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Horario'));
        }
        $aDias = Configure::read('aDias');
        $aTurnos = Configure::read('aTurnos');
        $sedes = $this->Horarios->Sedes->find('list', ['limit' => 200])->where(['Sedes.activa' => 1]);
        $periodos = $this->Horarios->Periodos->find('list', ['limit' => 200])->where(['Periodos.activo' => 1]);
        $this->set(compact('horario', 'sedes', 'periodos', 'aDias', 'aTurnos'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Horario id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $horario = $this->Horarios->get($id);
        if ($this->Horarios->delete($horario)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Horario'));
                $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Horarios - ID: ' . $horario->id . ', Código: ' . $horario->codigo);
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Horario'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
