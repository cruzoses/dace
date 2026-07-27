<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Cake\Event\Event;

/**
 * IndicadorCursos Controller
 *
 * @property \App\Model\Table\IndicadorCursosTable $IndicadorCursos
 *
 * @method \App\Model\Entity\IndicadorCurso[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
*/
class IndicadorCursosController extends AppController
{    
    /**
     * 
    */
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

    private function _getOpcionesPorcentaje($nFrecuencia)
    {
        switch ((int)$nFrecuencia) {
            case 1: return [100 => '100%'];
            case 2: return [50 => '50%'];
            case 3: return [30 => '30%', 40 => '40%'];
            default: return [];
        }
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
            ->contain(['Asignaturas'])
            ->first();

        if (!$oCurso) 
        {
            return $this->redirect(['action' => 'homepage']);
        }

        $this->paginate = [
            'contain' => ['Cursos', 'Indicadores'],
        ];

        $aEscala = Configure::read('aEscala');
        $indicadorCursos = $this->paginate($this->IndicadorCursos,[
            'conditions' => ['IndicadorCursos.curso_id' => $nCursoId]
        ]);

        $this->set(compact('oCurso', 'indicadorCursos', 'nCursoId', 'aEscala'));
    }

    /**
     * View method
     *
     * @param string|null $id Indicador Curso id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $indicadorCurso = $this->IndicadorCursos->get($id, [
            'contain' => ['Cursos', 'Indicadores', 'ContenidosCursos'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS IndicadorCursos ' . json_encode($indicadorCurso->toArray()));

        $this->set('indicadorCurso', $indicadorCurso);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add($nCursoId)
    {
        $oCurso = TableRegistry::getTableLocator()->get('Cursos')->find()
            ->where(['Cursos.id' => $nCursoId])
            ->contain(['Asignaturas'])
            ->first();

        $indicadorCurso = $this->IndicadorCursos->newEntity();
        if ($this->request->is('post')) 
        {
            $indicadorCurso = $this->IndicadorCursos->patchEntity($indicadorCurso, $this->request->getData());
            if ($this->IndicadorCursos->save($indicadorCurso)) {
                $this->Flash->success(__('The {0} has been saved.', 'Indicador Curso'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS IndicadorCursos ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index',$oCurso->id]);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Indicador Curso'));
        }
        $aEscala = Configure::read('aEscala');
        $aPorcentajes = $this->_getOpcionesPorcentaje($oCurso->asignatura->frecuencia);
        $cursos = $this->IndicadorCursos->Cursos->find('list')->where(['Cursos.id' => $nCursoId])->first();
        $indicadores = $this->IndicadorCursos->Indicadores->find('list', ['limit' => 200]);
        $this->set(compact('indicadorCurso', 'cursos', 'indicadores','aEscala','oCurso','aPorcentajes'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Indicador Curso id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $indicadorCurso = $this->IndicadorCursos->get($id, [
            'contain' => []
        ]);

        $oCurso = TableRegistry::getTableLocator()->get('Cursos')->find()
            ->where(['Cursos.id' => $indicadorCurso->curso_id])
            ->contain(['Asignaturas'])
            ->first();

        if ($this->request->is(['patch', 'post', 'put'])) 
        {
            $indicadorCurso = $this->IndicadorCursos->patchEntity($indicadorCurso, $this->request->getData());
            if ($this->IndicadorCursos->save($indicadorCurso)) {
                $this->Flash->success(__('The {0} has been saved.', 'Indicador Curso'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS IndicadorCursos ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index',$indicadorCurso->curso_id]);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Indicador Curso'));
        }
        $aEscala = Configure::read('aEscala');
        $aPorcentajes = $this->_getOpcionesPorcentaje($oCurso->asignatura->frecuencia);
        $cursos = $this->IndicadorCursos->Cursos->find('list')->where(['Cursos.id' => $oCurso->id])->first();
        $indicadores = $this->IndicadorCursos->Indicadores->find('list', ['limit' => 200]);
        $this->set(compact('indicadorCurso', 'cursos', 'indicadores','oCurso','aEscala','aPorcentajes'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Indicador Curso id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $indicadorCurso = $this->IndicadorCursos->get($id);
        if ($this->IndicadorCursos->delete($indicadorCurso)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Indicador Curso'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS IndicadorCursos ' . json_encode($indicadorCurso->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Indicador Curso'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
