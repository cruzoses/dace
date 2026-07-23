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
        $conditions = $this->Horarios->formatConditions($this->request->getQueryParams());
        $this->paginate = [
            'conditions' => $conditions,
            'contain' => ['Sedes', 'Periodos'],
        ];
        $horarios = $this->paginate($this->Horarios,['order' => ['Horarios.id' => 'DESC']]);
        $filtros = $this->request->getQuery();

        $searchFields = $this->Horarios->getSearchFields();
        $aDias = Configure::read('aDias');
        $aTurnos = Configure::read('aTurnos');
        $searchFields['sede_id']['options'] = $this->Horarios->Sedes->find('list')->where(['Sedes.activa' => 1])->toArray();
        $searchFields['periodo_id']['options'] = $this->Horarios->Periodos->find('list')->where(['Periodos.activo' => 1])->toArray();
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

    /**
     * Copiar method
     *
     * Copia todos los horarios de un periodo origen a un periodo destino.
     * @return \Cake\Http\Response|null Redirects to index on success, renders view otherwise.
    */
    public function copiar()
    {
        $aDias = Configure::read('aDias');
        $aTurnos = Configure::read('aTurnos');

        if ($this->request->is('post'))
        {
            $origenId = $this->request->getData('origen_id');
            $destinoId = $this->request->getData('destino_id');
            $sedeId = $this->request->getData('sede_id');

            if (empty($origenId) || empty($destinoId) || empty($sedeId))
            {
                $this->Flash->error(__('Debe seleccionar la sede, el periodo origen y el periodo destino.'));
                return $this->redirect(['action' => 'copiar']);
            }

            if ($origenId == $destinoId)
            {
                $this->Flash->error(__('El periodo origen y destino deben ser diferentes.'));
                return $this->redirect(['action' => 'copiar']);
            }

            $origen = $this->Horarios->Periodos->get($origenId);
            $destino = $this->Horarios->Periodos->get($destinoId);

            $horariosOrigen = $this->Horarios->find()
                ->where(['periodo_id' => $origenId, 'sede_id' => $sedeId])
                ->toArray();

            if (empty($horariosOrigen))
            {
                $this->Flash->warning(__('No hay horarios registrados en el periodo {0} para la sede seleccionada.', $origen->codename));
                return $this->redirect(['action' => 'copiar']);
            }

            $copiados = 0;
            $errores = 0;
            foreach ($horariosOrigen as $h)
            {
                $nuevo = $this->Horarios->newEntity();
                $data = [
                    'sede_id' => $h->sede_id,
                    'periodo_id' => $destinoId,
                    'dia' => $h->dia,
                    'turno' => $h->turno,
                    'desde' => $h->desde,
                    'hasta' => $h->hasta,
                    'codigo' => $this->generarCodigo([
                        'dia' => $h->dia,
                        'turno' => $h->turno,
                        'desde' => $h->desde,
                        'hasta' => $h->hasta,
                    ]),
                    'activo' => $h->activo,
                ];
                $nuevo = $this->Horarios->patchEntity($nuevo, $data);
                if ($this->Horarios->save($nuevo))
                {
                    $copiados++;
                    $this->Auditorias->registrar('REGISTRA', 'COPIA HORARIO - ID: ' . $nuevo->id . ', Código: ' . $nuevo->codigo . ' desde periodo ' . $origenId . ' hacia periodo ' . $destinoId);
                }
                else
                {
                    $errores++;
                }
            }

            $this->Flash->success(__('Se copiaron {0} horario(s) del periodo {1} al periodo {2}.', $copiados, $origen->codename, $destino->codename));
            if ($errores > 0)
            {
                $this->Flash->warning(__('{0} horario(s) no pudieron ser copiados.', $errores));
            }

            return $this->redirect(['action' => 'index']);
        }

        $periodos = $this->Horarios->Periodos->find('list', ['limit' => 200])
            //->where(['Periodos.activo' => 1])
            ->order(['Periodos.id' => 'DESC']);

        $sedes = $this->Horarios->Sedes->find('list', ['limit' => 200])
            ->where(['Sedes.activa' => 1]);

        $this->set(compact('periodos', 'sedes', 'aDias', 'aTurnos'));
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
