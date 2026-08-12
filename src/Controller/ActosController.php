<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Routing\Router;

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

        $actos = $this->paginate($this->Actos, [
            'contain' => ['Graduandos'],
        ]);
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
        if ($this->request->is(['patch', 'post', 'put'])) 
        {
            $acto = $this->Actos->patchEntity($acto, $this->request->getData());
            if ($this->Actos->save($acto)) 
            {
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

    public function libroActas()
    {
        $graduandos = null;
        $graduandosQuery = null;
        $filtros = [];
        $totalGraduandos = 0;

        if ($this->request->is('post')) 
        {
            $nInstitucion = $this->request->getData('institucion');
            $nActoId = $this->request->getData('promocion');
            $nCarreraId = $this->request->getData('carrera');

            if (empty($nInstitucion) || empty($nActoId)) {
                $this->Flash->error(__('Debe seleccionar la Promoción y la Institución.'));
                return $this->redirect(['action' => 'libroActas']);
            }

            $aCarreras = $this->_carrerasDelProceso($nInstitucion, $nActoId);
            $aCarrerasFiltro = [];
            foreach ($aCarreras as $oCarrera) {
                if (empty($nCarreraId) || (int)$oCarrera->id === (int)$nCarreraId) {
                    $aCarrerasFiltro[] = $oCarrera;
                }
            }

            $total = 0;
            foreach ($aCarrerasFiltro as $oCarrera) {
                $contador = 1;
                $oGraduandos = $this->Actos->Graduandos->find()
                    ->where([
                        'Graduandos.institucion' => $nInstitucion,
                        'Graduandos.acto_id' => $nActoId,
                        'Graduandos.carrera_id' => $oCarrera->id,
                    ])
                    ->contain(['Estudiantes'])
                    ->order(['Estudiantes.apellidos' => 'ASC', 'Estudiantes.nombres' => 'ASC'])
                    ->all();

                foreach ($oGraduandos as $oGraduando) {
                    $oGraduando = $this->Actos->Graduandos->patchEntity($oGraduando, [
                        'control' => sprintf('%02d', $contador),
                    ]);
                    $contador++;
                    if ($this->Actos->Graduandos->save($oGraduando)) {
                        $total++;
                    }
                }
            }

            $this->Auditorias->registrar('GENERA', 'GENERA LOS NUMEROS DE CONTROL Graduandos ' . json_encode([
                'institucion' => $nInstitucion,
                'acto_id' => $nActoId,
                'carrera_id' => $nCarreraId ? (int)$nCarreraId : null,
                'total' => $total,
            ]));

            $sMensaje = $total > 0
                ? __('Se generaron {0} número(s) de control.', $total)
                : __('No se encontraron graduandos para los criterios seleccionados.');

            $graduandosQuery = $this->_listadoGraduandos($nInstitucion, $nActoId, $nCarreraId);
            $filtros = [
                'promocion' => $nActoId,
                'institucion' => $nInstitucion,
                'carrera' => $nCarreraId ?: '',
            ];
            $this->request->getSession()->write('LibroActas.Filtros', $filtros);

            if ($this->request->is('ajax')) {
                $this->response = $this->response
                    ->withType('json')
                    ->withStringBody(json_encode([
                        'success' => true,
                        'message' => $sMensaje,
                        'redirect' => Router::url(['action' => 'libroActas', '?' => ['page' => 1]]),
                    ]));
                return $this->response;
            }

            if ($total > 0) {
                $this->Flash->success($sMensaje);
            } else {
                $this->Flash->warning($sMensaje);
            }
        } else {
            $filtros = $this->request->getSession()->read('LibroActas.Filtros') ?: [];
            $nPagina = $this->request->getQuery('page');
            $sSort = $this->request->getQuery('sort');
            $sDirection = $this->request->getQuery('direction');

            if (!empty($filtros) && ($nPagina || $sSort || $sDirection)) {
                $graduandosQuery = $this->_listadoGraduandos(
                    $filtros['institucion'],
                    $filtros['promocion'],
                    !empty($filtros['carrera']) ? $filtros['carrera'] : null
                );
            } else {
                $filtros = [];
            }
        }

        if ($graduandosQuery !== null) {
            $this->paginate = [
                'limit' => 10,
                'sortWhitelist' => [
                    'Carreras.nivel',
                    'Carreras.nombre',
                    'Estudiantes.apellidos',
                    'Estudiantes.nombres',
                    'Graduandos.control',
                    'Graduandos.indice',
                    'Graduandos.id',
                ],
                'order' => [
                    'Carreras.nivel' => 'ASC',
                    'Carreras.nombre' => 'ASC',
                    'Estudiantes.apellidos' => 'ASC',
                    'Estudiantes.nombres' => 'ASC',
                ],
            ];
            $graduandos = $this->paginate($graduandosQuery);
            $nPaging = $this->request->getParam('paging', []);
            $totalGraduandos = !empty($nPaging['Graduandos']['count']) ? $nPaging['Graduandos']['count'] : 0;
        }

        $aInstituciones = Configure::read('aInstituciones');
        $aPromociones = $this->Actos->find('list')->where(['Actos.activo' => 1])->toArray();
        $this->set(compact('aPromociones', 'aInstituciones', 'graduandos', 'filtros', 'totalGraduandos'));
    }

    public function carreras()
    {
        $this->request->allowMethod(['get']);
        $nActoId = $this->request->getQuery('acto_id');
        $nInstitucion = $this->request->getQuery('institucion');

        $aCarreras = [];
        if (!empty($nActoId) && !empty($nInstitucion)) {
            foreach ($this->_carrerasDelProceso($nInstitucion, $nActoId) as $oCarrera) {
                $aCarreras[] = [
                    'id' => $oCarrera->id,
                    'nombre' => $oCarrera->nombre,
                ];
            }
        }

        $this->set('carreras', $aCarreras);
        $this->set('_serialize', ['carreras']);
        $this->viewBuilder()->disableAutoLayout();
        $this->response = $this->response->withType('application/json');
    }

    /**
     * Obtiene las carreras registradas en graduandos para una institución y un acto,
     * ordenadas por nivel y nombre.
     *
     * @param int $nInstitucion
     * @param int $nActoId
     * @return array
     */
    protected function _carrerasDelProceso($nInstitucion, $nActoId)
    {
        $aIds = $this->Actos->Graduandos->find()
            ->distinct(['Graduandos.carrera_id'])
            ->where([
                'Graduandos.institucion' => $nInstitucion,
                'Graduandos.acto_id' => $nActoId,
            ])
            ->all()
            ->extract('carrera_id')
            ->toList();

        if (empty($aIds)) {
            return [];
        }

        return $this->Actos->Graduandos->Carreras->find()
            ->where(['Carreras.id IN' => $aIds])
            ->order(['Carreras.nivel' => 'ASC', 'Carreras.nombre' => 'ASC'])
            ->all()
            ->toArray();
    }

    /**
     * Obtiene los graduandos de una institución y un acto (y opcionalmente una carrera)
     * con su carrera y estudiante, ordenados por carrera (nivel, nombre) y apellidos/nombres.
     *
     * @param int $nInstitucion
     * @param int $nActoId
     * @param int|null $nCarreraId
     * @return \Cake\ORM\Query
     */
    protected function _listadoGraduandos($nInstitucion, $nActoId, $nCarreraId = null)
    {
        $conditions = [
            'Graduandos.institucion' => $nInstitucion,
            'Graduandos.acto_id' => $nActoId,
        ];
        if (!empty($nCarreraId)) {
            $conditions['Graduandos.carrera_id'] = $nCarreraId;
        }

        return $this->Actos->Graduandos->find()
            ->where($conditions)
            ->innerJoinWith('Carreras')
            ->leftJoinWith('Estudiantes')
            ->contain(['Estudiantes', 'Carreras']);
    }
}
