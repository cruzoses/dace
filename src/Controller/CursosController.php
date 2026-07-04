<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Cursos Controller
 *
 * @property \App\Model\Table\CursosTable $Cursos
 *
 * @method \App\Model\Entity\Curso[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CursosController extends AppController
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
        $conditions = $this->Cursos->formatConditions($this->request->getQueryParams());
        $this->paginate = [
            'contain' => ['Sedes', 'Periodos', 'Carreras', 'Programas', 'Trayectos', 'Asignaturas', 'Docentes', 'Aulas'],
            'conditions' => $conditions,
        ];
        $cursos = $this->paginate($this->Cursos);
        $filtros = $this->request->getQuery();
        $searchFields = $this->Cursos->getSearchFields();

        $searchFields['sede_id']['options'] = $this->Cursos->Sedes->find('list', ['limit' => 200])->where(['activa' => 1])->order(['id' => 'ASC'])->toArray();
        $searchFields['periodo_id']['options'] = $this->Cursos->Periodos->find('list', ['limit' => 200])->where(['activo' => 1])->order(['id' => 'DESC'])->toArray();
        $searchFields['carrera_id']['options'] = $this->Cursos->Carreras->find('list', ['limit' => 200])->where(['activa' => 1])->order(['id' => 'ASC'])->toArray();
        $searchFields['trayecto_id']['options'] = $this->Cursos->Trayectos->find('list', ['limit' => 200])->where(['activo' => 1])->toArray();
        $searchFields['asignatura_id']['options'] = $this->Cursos->Asignaturas->find('list', ['limit' => 200])->where(['activa' => 1])->toArray();
        $searchFields['docente_id']['options'] = $this->Cursos->Docentes->find('list')->where(['activo' => 1])->toArray();

        $this->set(compact('cursos', 'filtros', 'searchFields'));
    }

    /**
     * View method
     *
     * @param string|null $id Curso id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $curso = $this->Cursos->get($id, [
            'contain' => ['Sedes', 'Periodos', 'Carreras', 'Programas', 'Trayectos', 'Asignaturas', 'Docentes', 'Aulas', 'EstudianteCursos', 'IndicadorCursos'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Cursos ' . json_encode($curso->toArray()));

        $this->set('curso', $curso);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $curso = $this->Cursos->newEntity();
        if ($this->request->is('post')) 
        {
            $data = $this->request->getData();
            if ( !empty( $data['profesores']) && is_array($data['profesores'])) 
            {
                $data['profesores'] = implode(' ', $data['profesores']);
            }
            $curso = $this->Cursos->patchEntity($curso, $data);
            //$curso = $this->Cursos->patchEntity($curso, $this->request->getData());
            if ($this->Cursos->save($curso)) 
            {
                $this->Flash->success(__('The {0} has been saved.', 'Curso'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Cursos ' . json_encode($data));
                //$this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Cursos ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Curso'));
        }
        $sedes = $this->Cursos->Sedes->find('list', ['limit' => 200])->where(['activa' => 1])->order(['id' => 'ASC']);
        $periodos = $this->Cursos->Periodos->find('list', ['limit' => 200])->where(['activo' => 1])->order(['id' => 'DESC']);
        $carreras = $this->Cursos->Carreras->find('list', ['limit' => 200])->where(['activa' => 1])->order(['id' => 'ASC']);
        $programas = $this->Cursos->Programas->find('list', ['limit' => 200])->where(['activo' => 1])->order(['id' => 'DESC']);
        $trayectos = $this->Cursos->Trayectos->find('list', ['limit' => 200])->where(['activo' => 1]);
        $asignaturas = $this->Cursos->Asignaturas->find('list', ['limit' => 200])->where(['activa' => 1]);
        $docentes = $this->Cursos->Docentes->find('list')->where(['activo' => 1]);
        $profesores = $this->Cursos->Docentes->find('list', [
            'keyField' => 'cedula',
            'valueField' => 'codename'
        ])->where(['activo' => 1])->toArray();

        $aulas = $this->Cursos->Aulas->find('list')->where(['condicion' => 1]);
        $horarios = [];
        $this->set(compact('curso', 'sedes', 'periodos', 'carreras', 'programas', 'trayectos', 'asignaturas', 'docentes', 'aulas', 'horarios', 'profesores'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Curso id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $curso = $this->Cursos->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) 
        {
            $data = $this->request->getData();
            if ( !empty( $data['profesores']) && is_array($data['profesores'])) 
            {
                $data['profesores'] = implode(' ', $data['profesores']);
            }
            $curso = $this->Cursos->patchEntity($curso, $data);
            //$curso = $this->Cursos->patchEntity($curso, $this->request->getData());
            if ($this->Cursos->save($curso)) 
            {
                $this->Flash->success(__('The {0} has been saved.', 'Curso'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Cursos ' . json_encode($data));
                //º$this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Cursos ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Curso'));
        } else {
			$aProfesores = !empty($curso->profesores) ? explode(' ', $curso->profesores) : [];
			$aHorarios = !empty($curso->horario) ? explode(' ', $curso->horario) : [];
            $this->request = $this->request->withData('profesores', $aProfesores)
                ->withData('horario', $aHorarios);
        }
        $sedes = $this->Cursos->Sedes->find('list', ['limit' => 200])->where(['activa' => 1])->order(['id' => 'ASC']);
        $periodos = $this->Cursos->Periodos->find('list', ['limit' => 200])->where(['activo' => 1])->order(['id' => 'DESC']);
        $carreras = $this->Cursos->Carreras->find('list', ['limit' => 200])->where(['activa' => 1])->order(['id' => 'ASC']);
        $programas = $this->Cursos->Programas->find('list', ['limit' => 200])->where(['activo' => 1])->order(['id' => 'DESC']);
        $trayectos = $this->Cursos->Trayectos->find('list', ['limit' => 200])->where(['activo' => 1]);
        $asignaturas = [];
        if (!empty($curso->programa_id) && !empty($curso->trayecto_id)) {
            $asignaturas = $this->Cursos->Asignaturas->find('list', ['limit' => 200])
                ->matching('Mallas', function ($q) use ($curso) {
                    return $q->where([
                        'Mallas.programa_id' => $curso->programa_id,
                        'Mallas.trayecto_id' => $curso->trayecto_id,
                    ]);
                })
                ->where(['Asignaturas.activa' => 1])
                ->toArray();
        }
        $docentes = $this->Cursos->Docentes->find('list')->where(['activo' => 1]);
        $profesores = $this->Cursos->Docentes->find('list', [
            'keyField' => 'cedula',
            'valueField' => 'codename'
        ])->where(['activo' => 1])->toArray();

        $aulas = $this->Cursos->Aulas->find('list')->where(['condicion' => 1]);
        $horarios = [];
        if (!empty($curso->sede_id) && !empty($curso->periodo_id)) {
            $this->loadModel('Horarios');
            $horarios = $this->Horarios->find('list', [
                'keyField' => 'codigo',
                'valueField' => 'codigo'
            ])->where([
                'sede_id' => $curso->sede_id,
                'periodo_id' => $curso->periodo_id,
                'activo' => 1
            ])->order(['Horarios.dia', 'Horarios.desde'])->toArray();
        }
        $this->set(compact('curso', 'sedes', 'periodos', 'carreras', 'programas', 'trayectos', 'asignaturas', 'docentes', 'aulas', 'horarios', 'profesores'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Curso id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $curso = $this->Cursos->get($id);
        if ($this->Cursos->delete($curso)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Curso'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Cursos ' . json_encode($curso->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Curso'));
        }

        return $this->redirect(['action' => 'index']);
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
            $programas = $this->Cursos->Programas->find('list', ['limit' => 200])
                ->where(['carrera_id' => $carrera_id, 'activo' => 1])
                ->toArray();
        }

        $this->set(compact('programas'));
        $this->set('_serialize', ['programas']);
    }

    /**
     * Get asignaturas by programa_id + trayecto_id from Mallas (AJAX)
     *
     * @return \Cake\Http\Response
    */
    public function getAsignaturas()
    {
        $this->request->allowMethod(['ajax', 'get']);
        $programa_id = $this->request->getQuery('programa_id');
        $trayecto_id = $this->request->getQuery('trayecto_id');

        $asignaturas = [];
        if ($programa_id && $trayecto_id) {
            $asignaturas = $this->Cursos->Asignaturas->find('list', ['limit' => 200])
                ->matching('Mallas', function ($q) use ($programa_id, $trayecto_id) {
                    return $q->where([
                        'Mallas.programa_id' => $programa_id,
                        'Mallas.trayecto_id' => $trayecto_id,
                    ]);
                })
                ->where(['Asignaturas.activa' => 1])
                ->toArray();
        }

        $this->set(compact('asignaturas'));
        $this->set('_serialize', ['asignaturas']);
    }

    /**
     * Get horarios by sede_id + periodo_id (AJAX)
     *
     * @return \Cake\Http\Response
    */
    public function getHorarios()
    {
        $this->request->allowMethod(['ajax', 'get']);
        $sede_id = $this->request->getQuery('sede_id');
        $periodo_id = $this->request->getQuery('periodo_id');

        $horarios = [];
        if ($sede_id && $periodo_id) {
            $this->loadModel('Horarios');
            $horarios = $this->Horarios->find('list', [
                'keyField' => 'codigo',
                'valueField' => 'codigo'
            ])->where([
                'sede_id' => $sede_id,
                'periodo_id' => $periodo_id,
                'activo' => 1
            ])->order(['Horarios.dia', 'Horarios.desde'])->toArray();
        }

        $this->set(compact('horarios'));
        $this->set('_serialize', ['horarios']);
    }

}
