<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;

/**
 * ContenidoCursos Controller
 *
 * @property \App\Model\Table\ContenidoCursosTable $ContenidoCursos
 *
 * @method \App\Model\Entity\ContenidoCurso[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ContenidoCursosController extends AppController
{
    protected $aPorcentajes = [
        '5'  => '5 %',
        '10' => '10 %',
        '15' => '15 %',
        '20' => '20 %',
        '25' => '25 %'
    ];

	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
	}

	public function isAuthorized($user = null)
	{
        if( isset( $user['activo'] ) && isset( $user['rols'] ) && $user['activo'] )
        {
            if ( $this->tienePermiso([4,5,6]) ) {
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
    public function index($nCursoId = null)
    {
        $oCurso = TableRegistry::getTableLocator()->get('Cursos')->find()
            ->where(['Cursos.id' => $nCursoId])
            ->contain(['Sedes','Periodos','Carreras','Trayectos','Asignaturas','Docentes'])
            ->first();

        if (!$oCurso) {
            return $this->redirect(['action' => 'homepage']);
        }

        if (!$this->_getIndicadoresInCurso($nCursoId)) {
            $this->Flash->info('No tiene indicadores definidos. Por favor registrelos');
            return $this->redirect(['controller' => 'IndicadorCursos', 'action' => 'index', $nCursoId]);
        }

        $aIndicadorCursoIds = TableRegistry::getTableLocator()->get('IndicadorCursos')->find()
            ->where(['curso_id' => $nCursoId])
            ->extract('id')
            ->toArray();

        $this->paginate = [
            'contain' => ['IndicadorCursos'],
            'conditions' => ['indicador_curso_id IN' => $aIndicadorCursoIds],
        ];

        $contenidoCursos = $this->paginate($this->ContenidoCursos);

        $nFrecuencia = (int)$oCurso->asignatura->frecuencia;
        $aLimites = [1 => 1, 2 => 2, 3 => 3];
        $nIndicadoresDefinidos = count($aIndicadorCursoIds);
        $nIndicadoresMin = $aLimites[$nFrecuencia];
        $nIndicadoresMax = $aLimites[$nFrecuencia];

        $oQueryPonderacion = $this->ContenidoCursos->find()
            ->where(['indicador_curso_id IN' => $aIndicadorCursoIds]);
        $nPorcentajeDefinido = (int)$oQueryPonderacion->select([
            'total' => $oQueryPonderacion->func()->sum('ponderacion')
        ])->first()->total;

        $nEvaluacionesDefinidas = (int)$this->ContenidoCursos->find()
            ->where(['indicador_curso_id IN' => $aIndicadorCursoIds])
            ->count();

        $this->set(compact('contenidoCursos','oCurso','nCursoId','nPorcentajeDefinido',
            'nIndicadoresMin','nIndicadoresMax','nIndicadoresDefinidos',
            'nEvaluacionesDefinidas'));
    }

    /**
     * View method
     *
     * @param string|null $id Contenido Curso id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $contenidoCurso = $this->ContenidoCursos->get($id, [
            'contain' => ['IndicadorCursos', 'NotasCursos'],
        ]);

        $nCursoId = $contenidoCurso->indicador_curso->curso_id;

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS ContenidoCursos ' . json_encode($contenidoCurso->toArray()));

        $this->set(compact('contenidoCurso', 'nCursoId'));
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($nCursoId)
    {
        $contenidoCurso = $this->ContenidoCursos->newEntity();
        if ($this->request->is('post')) {
            $contenidoCurso = $this->ContenidoCursos->patchEntity($contenidoCurso, $this->request->getData());
            if ($this->ContenidoCursos->save($contenidoCurso)) {
                $this->Flash->success(__('The {0} has been saved.', 'Contenido Curso'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS ContenidoCursos ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index', $nCursoId]);
            }
            $aErrores = $contenidoCurso->getErrors();
            if (!empty($aErrores)) {
                foreach ($aErrores as $aCampo => $aMensajes) {
                    foreach ($aMensajes as $sMensaje) {
                        $this->Flash->error($sMensaje);
                    }
                }
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Contenido Curso'));
            }
        }
        $indicadorCursos = $this->_getIndicadoresByCurso($nCursoId);
        $this->set('aPorcentajes', $this->aPorcentajes);
        $this->set(compact('contenidoCurso', 'indicadorCursos', 'nCursoId'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Contenido Curso id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $contenidoCurso = $this->ContenidoCursos->get($id, [
            'contain' => ['IndicadorCursos'],
        ]);

        $nCursoId = $contenidoCurso->indicador_curso->curso_id;

        if ($this->request->is(['patch', 'post', 'put'])) {
            $contenidoCurso = $this->ContenidoCursos->patchEntity($contenidoCurso, $this->request->getData());
            if ($this->ContenidoCursos->save($contenidoCurso)) {
                $this->Flash->success(__('The {0} has been saved.', 'Contenido Curso'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS ContenidoCursos ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index', $nCursoId]);
            }
            $aErrores = $contenidoCurso->getErrors();
            if (!empty($aErrores)) {
                foreach ($aErrores as $aCampo => $aMensajes) {
                    foreach ($aMensajes as $sMensaje) {
                        $this->Flash->error($sMensaje);
                    }
                }
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Contenido Curso'));
            }
        }
        $indicadorCursos = $this->_getIndicadoresByCurso($nCursoId);
        $this->set('aPorcentajes', $this->aPorcentajes);
        $this->set(compact('contenidoCurso', 'indicadorCursos', 'nCursoId'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Contenido Curso id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $contenidoCurso = $this->ContenidoCursos->get($id, [
            'contain' => ['IndicadorCursos'],
        ]);

        $nCursoId = $contenidoCurso->indicador_curso->curso_id;

        if ($this->ContenidoCursos->delete($contenidoCurso)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Contenido Curso'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS ContenidoCursos ' . json_encode($contenidoCurso->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Contenido Curso'));
        }

        return $this->redirect(['action' => 'index', $nCursoId]);
    }

    private function _getIndicadoresInCurso($nCursoId)
    {
        $oIndicadores = TableRegistry::getTableLocator()->get('IndicadorCursos')->find()
            ->where(['IndicadorCursos.curso_id' => $nCursoId])
            ->count();
        return (bool) $oIndicadores > 0;
    }

    private function _getIndicadoresByCurso($nCursoId)
    {
        $aIndicadores = TableRegistry::getTableLocator()->get('IndicadorCursos')->find()
            ->where(['IndicadorCursos.curso_id' => $nCursoId])
            ->contain(['Indicadores'])
            ->toArray();

        $result = [];
        foreach ($aIndicadores as $oIndicador) {
            $result[$oIndicador->id] = $oIndicador->indicadore->nombre . ' (' . $oIndicador->porcentaje . '%)';
        }
        return $result;
    }
}
