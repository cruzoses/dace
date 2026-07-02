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

    /**
     * 
    */
	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
	}

    /**
     * 
    */
	public function isAuthorized($user)
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
        $this->paginate = [
            'contain' => ['Sedes', 'Periodos', 'Carreras', 'Programas', 'Trayectos', 'Docentes', 'Aulas'],
        ];
        $cursos = $this->paginate($this->Cursos);

        $this->set(compact('cursos'));
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
            'contain' => ['Sedes', 'Periodos', 'Carreras', 'Programas', 'Trayectos', 'Docentes', 'Aulas', 'EstudianteCursos', 'IndicadorCursos'],
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
        if ($this->request->is('post')) {
            $curso = $this->Cursos->patchEntity($curso, $this->request->getData());
            if ($this->Cursos->save($curso)) {
                $this->Flash->success(__('The {0} has been saved.', 'Curso'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Cursos ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Curso'));
        }
        $sedes = $this->Cursos->Sedes->find('list', ['limit' => 200]);
        $periodos = $this->Cursos->Periodos->find('list', ['limit' => 200]);
        $carreras = $this->Cursos->Carreras->find('list', ['limit' => 200]);
        $programas = $this->Cursos->Programas->find('list', ['limit' => 200]);
        $trayectos = $this->Cursos->Trayectos->find('list', ['limit' => 200]);
        $docentes = $this->Cursos->Docentes->find('list', ['limit' => 200]);
        $aulas = $this->Cursos->Aulas->find('list', ['limit' => 200]);
        $this->set(compact('curso', 'sedes', 'periodos', 'carreras', 'programas', 'trayectos', 'docentes', 'aulas'));
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
        if ($this->request->is(['patch', 'post', 'put'])) {
            $curso = $this->Cursos->patchEntity($curso, $this->request->getData());
            if ($this->Cursos->save($curso)) {
                $this->Flash->success(__('The {0} has been saved.', 'Curso'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Cursos ' . json_encode($this->request->getData()));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Curso'));
        }
        $sedes = $this->Cursos->Sedes->find('list', ['limit' => 200]);
        $periodos = $this->Cursos->Periodos->find('list', ['limit' => 200]);
        $carreras = $this->Cursos->Carreras->find('list', ['limit' => 200]);
        $programas = $this->Cursos->Programas->find('list', ['limit' => 200]);
        $trayectos = $this->Cursos->Trayectos->find('list', ['limit' => 200]);
        $docentes = $this->Cursos->Docentes->find('list', ['limit' => 200]);
        $aulas = $this->Cursos->Aulas->find('list', ['limit' => 200]);
        $this->set(compact('curso', 'sedes', 'periodos', 'carreras', 'programas', 'trayectos', 'docentes', 'aulas'));
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
}
