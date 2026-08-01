<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Rols Controller
 *
 * @property \App\Model\Table\RolsTable $Rols
 *
 * @method \App\Model\Entity\Rol[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
*/
class RolsController extends AppController
{

	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
	}

	public function isAuthorized($user = null)
	{
		return parent::isAuthorized($user);
	}
	
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
    */
    public function index()
    {
        $rols = $this->paginate($this->Rols);

        $this->set(compact('rols'));
    }

    /**
     * View method
     *
     * @param string|null $id Rol id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $rol = $this->Rols->get($id);

        $query = $this->Rols->Usuarios->find()
            ->matching('Rols', function ($q) use ($id) {
                return $q->where(['Rols.id' => $id]);
            });

        $nTotalUsuarios = $query->count();

        $usuarios = $this->paginate($query->order(['Usuarios.id' => 'ASC']));

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Rol ' . json_encode([
            'id' => $rol->id,
            'nombre' => $rol->nombre,
            'activo' => $rol->activo,
            'created' => $rol->created,
            'modified' => $rol->modified,
            'total_usuarios' => $nTotalUsuarios,
        ]));

        $this->set(compact('rol', 'usuarios', 'nTotalUsuarios'));
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $rol = $this->Rols->newEntity();
        if ($this->request->is('post')) {
            $rol = $this->Rols->patchEntity($rol, $this->request->getData());
            if ($this->Rols->save($rol)) {
                $this->Flash->success(__('The {0} has been saved.', 'Rol'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Rol ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Rol'));
        }
        $usuarios = $this->Rols->Usuarios->find('list', ['limit' => 200]);
        $this->set(compact('rol', 'usuarios'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Rol id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $rol = $this->Rols->get($id, [
            'contain' => ['Usuarios']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $rol = $this->Rols->patchEntity($rol, $this->request->getData());
            if ($this->Rols->save($rol)) {
                $this->Flash->success(__('The {0} has been saved.', 'Rol'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Rol ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Rol'));
        }
        $usuarios = $this->Rols->Usuarios->find('list', ['limit' => 200]);
        $this->set(compact('rol', 'usuarios'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Rol id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $rol = $this->Rols->get($id);
        if ($this->Rols->delete($rol)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Rol'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Rol ' . json_encode($rol->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Rol'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
