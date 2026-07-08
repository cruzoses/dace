<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * Profesores Controller
 *
 * @method \App\Model\Entity\Docente[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProfesoresController extends AppController
{

	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
	}

	public function isAuthorized($user)
	{
        if( isset( $user['activo'] ) && isset( $user['rols'] ) && $user['activo'] && $this->tienePermiso([4,5,6]) )
        {
            return true;
        }
		return parent::isAuthorized($user);
	}

    public function index()
    {
        $this->loadModel('Docentes');

        $userId = $this->Auth->user('id');
        $docente = TableRegistry::getTableLocator()->get('Docentes')->find() //$this->Docentes->find()
            ->where(['Docentes.usuario_id' => $userId])
            ->contain(['Departamentos','Usuarios'])
            ->first();

        if (!$docente) 
        {
            $this->Flash->error(__('No se encontró un docente asociado a su usuario.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'display', 'home']);
        }

        $periodos = $this->Docentes->Cursos->Periodos->find('list', [
            'keyField' => 'id',
            'valueField' => 'codigo',
            'order' => ['Periodos.id' => 'DESC'],
        ])
        ->matching('Cursos', function ($q) use ($docente) {
            return $q->where(['Cursos.docente_id' => $docente->id]);
        })
        ->toArray();

        $periodoId = $this->request->getQuery('periodo_id');
        if (!$periodoId || !isset($periodos[$periodoId])) 
        {
            $periodoId = array_key_first($periodos);
        }

        $cursos = [];
        if ($periodoId) {
            $cursos = $this->Docentes->Cursos->find()
                ->where([
                    'Cursos.docente_id' => $docente->id,
                    'Cursos.periodo_id' => $periodoId,
                ])
                ->contain(['Asignaturas', 'Carreras', 'Trayectos', 'Sedes', 'Aulas', 'Periodos'])
                ->order(['Cursos.seccion' => 'ASC'])
                ->toArray();
        }

        $this->set(compact('docente', 'periodos', 'periodoId', 'cursos'));
    }

    public function profesor($id)
    {
        $docente = TableRegistry::getTableLocator()->get('Docentes')->get($id, [
            'contain' => ['Departamentos', 'Usuarios', 'Cursos'],
        ]);
        $aGeneros = Configure::read('aGeneros');
        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Docentes ' . json_encode($docente->toArray()));

        $this->set(compact('aGeneros'));
        $this->set('docente', $docente);
    }

    public function indicadores($cursoId)
    {
        $this->viewBuilder()->setLayout('ajax');
    }

    public function planEvaluacion($cursoId)
    {
        $this->viewBuilder()->setLayout('ajax');
    }

    public function cargaNotas($cursoId)
    {
        $this->viewBuilder()->setLayout('ajax');
    }

    public function cursos($profesorId)
    {
        $this->loadModel('Docentes');
        $cursos = $this->Docentes->Cursos->find()
            ->where(['Cursos.docente_id' => $profesorId])
            ->contain(['Asignaturas', 'Carreras', 'Trayectos', 'Sedes', 'Periodos'])
            ->order(['Cursos.periodo_id' => 'DESC', 'Cursos.seccion' => 'ASC'])
            ->toArray();

        $this->set(compact('cursos'));
        $this->viewBuilder()->setLayout('ajax');
    }
}