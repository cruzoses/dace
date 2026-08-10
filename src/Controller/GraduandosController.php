<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Core\Configure;

/**
 * Graduandos Controller
 *
 * @property \App\Model\Table\GraduandosTable $Graduandos
 *
 * @method \App\Model\Entity\Graduando[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
*/
class GraduandosController extends AppController
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
        $conditions = $this->Graduandos->formatConditions($this->request->getQueryParams());
        $this->paginate = [
            'contain' => ['Actos', 'Carreras', 'Programas', 'Estudiantes'],
            'conditions' => $conditions,
            'order' => ['Graduandos.id' => 'DESC'],
        ];
        $graduandos = $this->paginate($this->Graduandos);
        $filtros = $this->request->getQuery();
        $searchFields = $this->Graduandos->getSearchFields();

        $searchFields['acto_id']['options'] = $this->Graduandos->Actos->find('list', [
            'keyField' => 'id',
            'valueField' => function ($acto) {
                return $acto->nombre . ' (' . $acto->cohorte . ')';
            },
            'conditions' => ['Actos.activo' => 1],
            'order' => ['Actos.id' => 'DESC'],
        ])->toArray();

        $searchFields['carrera_id']['options'] = $this->Graduandos->Carreras->find('list', [
            'conditions' => ['Carreras.activa' => 1],
            'order' => ['Carreras.nombre' => 'ASC'],
        ])->toArray();

        $searchFields['programa_id']['options'] = $this->Graduandos->Programas->find('list', [
            'conditions' => ['Programas.activo' => 1],
            'order' => ['Programas.nombre' => 'ASC'],
        ])->toArray();

        $searchFields['estudiante_id']['options'] = $this->Graduandos->Estudiantes->find('list', [
            'order' => ['Estudiantes.apellidos' => 'ASC', 'Estudiantes.nombres' => 'ASC'],
        ])->toArray();

        $this->set(compact('graduandos', 'filtros', 'searchFields'));
    }

    /**
     * View method
     *
     * @param string|null $id Graduando id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $graduando = $this->Graduandos->get($id, [
            'contain' => ['Actos', 'Carreras', 'Programas', 'Estudiantes'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Graduandos ' . json_encode($graduando->toArray()));

        $this->set('graduando', $graduando);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $graduando = $this->Graduandos->newEntity();

        $estudianteId = $this->request->getQuery('estudiante_id');
        $programaId = $this->request->getQuery('programa_id');

        if ($this->request->is('post')) {
            $estudianteId = $estudianteId ?: $this->request->getData('estudiante_id');
            $programaId = $programaId ?: $this->request->getData('programa_id');

            $graduando = $this->Graduandos->patchEntity($graduando, $this->request->getData());
            if ($this->Graduandos->save($graduando)) {
                $this->Flash->success(__('The {0} has been saved.', 'Graduando'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Graduandos ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Graduando'));
        }

        $estudiante = null;
        $programa = null;
        $carrera = null;
        if (!empty($estudianteId) && !empty($programaId)) {
            $estudiantePrograma = $this->Graduandos->Estudiantes->EstudianteProgramas->find()
                ->where([
                    'EstudianteProgramas.estudiante_id' => $estudianteId,
                    'EstudianteProgramas.programa_id' => $programaId,
                ])
                ->contain(['Estudiantes', 'Programas' => ['Carreras']])
                ->first();

            if ($estudiantePrograma) {
                $estudiante = $estudiantePrograma->estudiante;
                $programa = $estudiantePrograma->programa;
                $carrera = $programa->carrera;
            } else {
                $estudiante = $this->Graduandos->Estudiantes->get($estudianteId);
                $programa = $this->Graduandos->Programas->get($programaId, ['contain' => ['Carreras']]);
                $carrera = $programa->carrera;
            }
        }

        $actos = $this->Graduandos->Actos->find('list', ['limit' => 200]);
        $instituciones = Configure::read('aInstituciones');
        $solicitudes = [1 => 'ACTO SOLEMNE', 2 => 'SECRETARIA'];

        $this->set(compact('graduando', 'estudiante', 'programa', 'carrera', 'actos', 'instituciones', 'solicitudes'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Graduando id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $graduando = $this->Graduandos->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $graduando = $this->Graduandos->patchEntity($graduando, $this->request->getData());
            if ($this->Graduandos->save($graduando)) {
                $this->Flash->success(__('The {0} has been saved.', 'Graduando'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Graduandos ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Graduando'));
        }
        $actos = $this->Graduandos->Actos->find('list', ['limit' => 200]);
        $carreras = $this->Graduandos->Carreras->find('list', ['limit' => 200]);
        $programas = $this->Graduandos->Programas->find('list', ['limit' => 200]);
        $estudiantes = $this->Graduandos->Estudiantes->find('list', ['limit' => 200]);
        $this->set(compact('graduando', 'actos', 'carreras', 'programas', 'estudiantes'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Graduando id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $graduando = $this->Graduandos->get($id);
        if ($this->Graduandos->delete($graduando)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Graduando'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Graduandos ' . json_encode($graduando->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Graduando'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
