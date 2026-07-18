<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Cake\Event\Event;

/**
 * Estudiantes Controller
 *
 * @property \App\Model\Table\EstudiantesTable $Estudiantes
 *
 * @method \App\Model\Entity\Estudiante[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
*/
class EstudiantesController extends AppController
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
        $conditions = $this->Estudiantes->formatConditions($this->request->getQueryParams());
        $this->paginate = [
            'contain' => ['Paises', 'Estados', 'Municipios', 'Parroquias', 'Usuarios'],
            'conditions' => $conditions,
        ];
        $estudiantes = $this->paginate($this->Estudiantes);
        $filtros = $this->request->getQuery();
        $searchFields = $this->Estudiantes->getSearchFields();

        $this->set(compact('estudiantes', 'filtros', 'searchFields'));
    }

    /**
     * View method
     *
     * @param string|null $id Estudiante id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $estudiante = $this->Estudiantes->get($id, [
            'contain' => ['Paises', 'Estados', 'Municipios', 'Parroquias', 'Usuarios', 'EstudianteCursos', 'EstudianteProgramas', 
            'Graduandos', 'Historicos', 'NotasCursos', 'SituacionEstudiantes'],
        ]);
        $aGeneros = Configure::read('aGeneros');
        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Estudiantes ' . json_encode($estudiante->toArray()));

        $aPeriodo = TableRegistry::getTableLocator()->get('Periodos')->find()
            ->where(['Periodos.id' => $estudiante->periodo])
            ->first();
        $this->set(compact('aGeneros','aPeriodo'));
        $this->set('estudiante', $estudiante);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $estudiante = $this->Estudiantes->newEntity();
        if ($this->request->is('post')) 
        {
            $data = $this->request->getData();
            $estudiante = $this->Estudiantes->patchEntity($estudiante, $data);
            $this->Estudiantes->getConnection()->begin();
            if ($this->Estudiantes->save($estudiante)) 
            {
                $expediente = $this->Estudiantes->generarExpediente(
                    $estudiante->fecha_nacimiento,
                    $estudiante->periodo
                );
                $estudiante->expediente = $expediente;
                if ($this->Estudiantes->save($estudiante)) {
                    $this->Estudiantes->getConnection()->commit();
                    $this->Flash->success(__('The {0} has been saved.', 'Estudiante'));
                    $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Estudiantes ' . json_encode($data));

                    return $this->redirect(['action' => 'index']);
                }
            }
            $this->Estudiantes->getConnection()->rollback();
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Estudiante'));
        }
        $estados = [];
        $municipios = [];
        $parroquias = [];
        $sToken = $this->generateToken();
        $aOrigen = Configure::read('aTipoDoc');
        $aGenero = Configure::read('aGeneros');
        $aEdoCivil = Configure::read('aEstadoCivil');
        $aSedes = TableRegistry::getTableLocator()->get('Sedes')->find('list')->where(['Sedes.activa' => 1])->toArray();
        $aPeriodos = TableRegistry::getTableLocator()->get('Periodos')->find('list')->where(['Periodos.activo' => 1])
            ->order(['Periodos.id' => 'DESC'])->toArray();
        $aCarreras = TableRegistry::getTableLocator()->get('Carreras')->find('list')->where(['Carreras.activa' => 1])->toArray();
        $paises = $this->Estudiantes->Paises->find('list', ['limit' => 200]);
        $usuarios = $this->Estudiantes->Usuarios->find('list', ['limit' => 200]);
        $this->set(compact('sToken','aOrigen', 'aGenero', 'aEdoCivil','aSedes','aPeriodos','aCarreras'));
        $this->set(compact('estudiante', 'paises', 'estados', 'municipios', 'parroquias', 'usuarios'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Estudiante id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $estudiante = $this->Estudiantes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $estudiante = $this->Estudiantes->patchEntity($estudiante, $this->request->getData());
            if ($this->Estudiantes->save($estudiante)) {
                $this->Flash->success(__('The {0} has been saved.', 'Estudiante'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Estudiantes ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Estudiante'));
        }
        $estados = [];
        $municipios = [];
        $parroquias = [];
        $sToken = $this->generateToken();
        $aOrigen = Configure::read('aTipoDoc');
        $aGenero = Configure::read('aGeneros');
        $aEdoCivil = Configure::read('aEstadoCivil');
        $aSedes = TableRegistry::getTableLocator()->get('Sedes')->find('list')->where(['Sedes.activa' => 1])->toArray();
        $aPeriodos = TableRegistry::getTableLocator()->get('Periodos')->find('list')->where(['Periodos.activo' => 1])
            ->order(['Periodos.id' => 'DESC'])->toArray();
        $aCarreras = TableRegistry::getTableLocator()->get('Carreras')->find('list')->where(['Carreras.activa' => 1])->toArray();
        $paises = $this->Estudiantes->Paises->find('list', ['limit' => 200]);
        $usuarios = $this->Estudiantes->Usuarios->find('list', ['limit' => 200]);
        $this->set(compact('sToken','aOrigen', 'aGenero', 'aEdoCivil','aSedes','aPeriodos','aCarreras'));
        $this->set(compact('estudiante', 'paises', 'estados', 'municipios', 'parroquias', 'usuarios'));

    }


    /**
     * Get estados by pais_id (AJAX)
     */
    public function getEstados()
    {
        $this->request->allowMethod(['ajax', 'get']);
        $this->autoRender = false;
        $pais_id = $this->request->getQuery('pais_id');

        $estados = [];
        if ($pais_id) {
            $estados = $this->Estudiantes->Estados->find('list', ['limit' => 200])
                ->where(['pais_id' => $pais_id])
                ->order(['nombre' => 'ASC'])
                ->toArray();
        }

        $this->response = $this->response->withType('application/json');
        $this->response = $this->response->withStringBody(json_encode(['estados' => $estados]));
        return $this->response;
    }

    /**
     * Get municipios by estado_id (AJAX)
     */
    public function getMunicipios()
    {
        $this->request->allowMethod(['ajax', 'get']);
        $this->autoRender = false;
        $estado_id = $this->request->getQuery('estado_id');

        $municipios = [];
        if ($estado_id) {
            $municipios = $this->Estudiantes->Municipios->find('list', ['limit' => 200])
                ->where(['estado_id' => $estado_id])
                ->order(['nombre' => 'ASC'])
                ->toArray();
        }

        $this->response = $this->response->withType('application/json');
        $this->response = $this->response->withStringBody(json_encode(['municipios' => $municipios]));
        return $this->response;
    }

    /**
     * Get parroquias by municipio_id (AJAX)
     */
    public function getParroquias()
    {
        $this->request->allowMethod(['ajax', 'get']);
        $this->autoRender = false;
        $municipio_id = $this->request->getQuery('municipio_id');

        $parroquias = [];
        if ($municipio_id) {
            $parroquias = $this->Estudiantes->Parroquias->find('list', ['limit' => 200])
                ->where(['municipio_id' => $municipio_id])
                ->order(['nombre' => 'ASC'])
                ->toArray();
        }

        $this->response = $this->response->withType('application/json');
        $this->response = $this->response->withStringBody(json_encode(['parroquias' => $parroquias]));
        return $this->response;
    }


    /**
     * Delete method
     *
     * @param string|null $id Estudiante id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $estudiante = $this->Estudiantes->get($id);
        if ($this->Estudiantes->delete($estudiante)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Estudiante'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Estudiantes ' . json_encode($estudiante->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Estudiante'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
