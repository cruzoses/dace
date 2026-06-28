<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * RolsUsuarios Controller
 *
 * @property \App\Model\Table\RolsUsuariosTable $RolsUsuarios
 *
 * @method \App\Model\Entity\RolsUsuario[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RolsUsuariosController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Rols', 'Usuarios'],
        ];
        $rolsUsuarios = $this->paginate($this->RolsUsuarios);

        $this->set(compact('rolsUsuarios'));
    }

    /**
     * View method
     *
     * @param string|null $id Rols Usuario id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $rolsUsuario = $this->RolsUsuarios->get($id, [
            'contain' => ['Rols', 'Usuarios'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS RolsUsuario ' . json_encode($rolsUsuario->toArray()));

        $this->set('rolsUsuario', $rolsUsuario);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $rolsUsuario = $this->RolsUsuarios->newEntity();
        if ($this->request->is('post')) {
            $rolsUsuario = $this->RolsUsuarios->patchEntity($rolsUsuario, $this->request->getData());
            if ($this->RolsUsuarios->save($rolsUsuario)) {
                $this->Flash->success(__('The {0} has been saved.', 'Rols Usuario'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS RolsUsuario ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Rols Usuario'));
        }
        $rols = $this->RolsUsuarios->Rols->find('list', ['limit' => 200]);
        $usuarios = $this->RolsUsuarios->Usuarios->find('list', ['limit' => 200]);
        $this->set(compact('rolsUsuario', 'rols', 'usuarios'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Rols Usuario id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $rolsUsuario = $this->RolsUsuarios->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $rolsUsuario = $this->RolsUsuarios->patchEntity($rolsUsuario, $this->request->getData());
            if ($this->RolsUsuarios->save($rolsUsuario)) {
                $this->Flash->success(__('The {0} has been saved.', 'Rols Usuario'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS RolsUsuario ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Rols Usuario'));
        }
        $rols = $this->RolsUsuarios->Rols->find('list', ['limit' => 200]);
        $usuarios = $this->RolsUsuarios->Usuarios->find('list', ['limit' => 200]);
        $this->set(compact('rolsUsuario', 'rols', 'usuarios'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Rols Usuario id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $rolsUsuario = $this->RolsUsuarios->get($id);
        if ($this->RolsUsuarios->delete($rolsUsuario)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Rols Usuario'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS RolsUsuario ' . json_encode($rolsUsuario->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Rols Usuario'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
