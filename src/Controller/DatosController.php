<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * Datos Controller
 *
 * @method \App\Model\Entity\Dato[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
*/
class DatosController extends AppController
{

	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
	}

	public function isAuthorized($user)
	{
        if( isset( $user['activo'] ) && isset( $user['rols'] ) && $user['activo'] && $this->tienePermiso([2,3]) )
        {
            return true;
        }
		return parent::isAuthorized($user);
	}

    public function index()
    {
    }

    public function students()
    {
        $this->loadModel('Estudiantes');
        $conditions = $this->Estudiantes->formatConditions($this->request->getQueryParams());
        $this->paginate = [
            'conditions' => $conditions,
        ];
        $estudiantes = $this->paginate($this->Estudiantes, ['order' => ['Estudiantes.cedula' => 'ASC']]);

        if ($estudiantes->count() == 1) {
            return $this->redirect(['action' => 'estudiante', $estudiantes->first()->id]);
        }

        $filtros = $this->request->getQuery();
        $searchFields = $this->Estudiantes->getSearchFields();

        $this->set(compact('estudiantes', 'filtros', 'searchFields'));
    }

    public function estudiante($id)
    {
        //$this->loadModel('Estudiantes');
        $estudiante = TableRegistry::getTableLocator()->get('Estudiantes')->get($id,[
            //$estudiante = $this->Estudiantes->get($id, [
            'contain' => ['Paises', 'Estados', 'Municipios', 'Parroquias', 'Usuarios', 'EstudianteCursos', 'EstudianteProgramas', 
            'Graduandos', 'Historicos', 'NotasCursos', 'SituacionEstudiantes'],
        ]);
        $aGeneros = Configure::read('aGeneros');
        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Estudiantes ' . json_encode($estudiante->toArray()));

        $this->set(compact('aGeneros'));
        $this->set('estudiante', $estudiante);
    }

    public function rendimiento()
    {
        
    }

    public function programas($estudianteId = null)
    {
        $programas = TableRegistry::getTableLocator()->get('EstudianteProgramas')->find('all', [
            'conditions' => ['EstudianteProgramas.estudiante_id' => $estudianteId],
            'contain' => ['Estudiantes', 'Carreras', 'Programas', 'Sedes'] 
        ])
        //->where(['EstudianteProgramas.estudiante_id' => $estudianteId])
        ->toArray();

        $this->set(compact('programas', 'estudianteId'));
        $this->set('_serialize', ['programas']); // Para que se pueda serializar a JSON si se requiere
        $this->viewBuilder()->setLayout('ajax'); // Usar un layout vacío para las llamadas AJAX
    }
}
