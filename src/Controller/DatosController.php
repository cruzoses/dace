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
        $estudiantesTable = TableRegistry::getTableLocator()->get('Estudiantes');

        $conditions = $estudiantesTable->formatConditions($this->request->getQueryParams());
        $this->paginate = [
            'conditions' => $conditions,
        ];
        $estudiantes = $this->paginate($estudiantesTable);
        $filtros = $this->request->getQuery();
        $searchFields = $estudiantesTable->getSearchFields();

        $this->set(compact('estudiantes', 'filtros', 'searchFields'));
    }
}
