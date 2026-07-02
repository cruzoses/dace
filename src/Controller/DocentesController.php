<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\Core\Configure;

/**
 * Docentes Controller
 *
 * @property \App\Model\Table\DocentesTable $Docentes
 *
 * @method \App\Model\Entity\Docente[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DocentesController extends AppController
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
        $conditions = $this->Docentes->formatConditions($this->request->getQueryParams());
        $this->paginate = [
            'contain' => ['Departamentos', 'Usuarios'],
            'conditions' => $conditions,
        ];
        $docentes = $this->paginate($this->Docentes);
        $filtros = $this->request->getQuery();
        $searchFields = $this->Docentes->getSearchFields();

        $searchFields['departamento_id']['options'] = $this->Docentes->Departamentos->find('list', ['limit' => 200])->toArray();

        $this->set(compact('docentes', 'filtros', 'searchFields'));
    }

    /**
     * View method
     *
     * @param string|null $id Docente id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $docente = $this->Docentes->get($id, [
            'contain' => ['Departamentos', 'Usuarios', 'Cursos'],
        ]);

        $this->Auditorias->registrar('CONSULTA', 'CONSULTA LOS DATOS Docentes ' . json_encode($docente->toArray()));

        $this->set('docente', $docente);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    public function add()
    {
        $docente = $this->Docentes->newEntity();
        if ($this->request->is('post')) 
        {
            $docente = $this->Docentes->patchEntity($docente, $this->request->getData());
            if ($this->Docentes->save($docente)) 
            {
                $this->Flash->success(__('The {0} has been saved.', 'Docente'));
                $this->Auditorias->registrar('REGISTRA', 'REGISTRA LOS DATOS Docentes ' . json_encode($this->request->getData()));

                if (!$this->enviarTokenPorEmail($docente)) {
                    $this->Flash->warning(__('El docente fue registrado pero no se pudo enviar el token por correo a {0}.', $docente->email));
                } else {
                    $this->Flash->success(__('Token de registro enviado a {0}.', $docente->email));
                }

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Docente'));
        }
        $departamentos = $this->Docentes->Departamentos->find('list', ['limit' => 200]);
        $usuarios = $this->Docentes->Usuarios->find('list', ['limit' => 200]);
        $aGeneros = Configure::read('aGeneros');
        $sToken = $this->generateToken();
        $this->set(compact('docente', 'departamentos', 'usuarios', 'aGeneros', 'sToken'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Docente id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
    */
    public function edit($id = null)
    {
        $docente = $this->Docentes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $docente = $this->Docentes->patchEntity($docente, $this->request->getData());
            if ($this->Docentes->save($docente)) {
                $this->Flash->success(__('The {0} has been saved.', 'Docente'));
                $this->Auditorias->registrar('MODIFICA', 'MODIFICA LOS DATOS Docentes ' . json_encode($this->request->getData()));

                if (!empty($docente->email) && $this->request->getData('enviar_token')) {
                    if (!$this->enviarTokenPorEmail($docente)) {
                        $this->Flash->warning(__('No se pudo enviar el token por correo a {0}.', $docente->email));
                    } else {
                        $this->Flash->success(__('Token de registro reenviado a {0}.', $docente->email));
                    }
                }

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Docente'));
        }
        $departamentos = $this->Docentes->Departamentos->find('list', ['limit' => 200]);
        $usuarios = $this->Docentes->Usuarios->find('list', ['limit' => 200]);
        $aGeneros = Configure::read('aGeneros');
        $sToken = $this->generateToken();
        $this->set(compact('docente', 'departamentos', 'usuarios', 'aGeneros', 'sToken'));
    }


    /**
     * Envía el token de registro por correo electrónico al docente.
     *
     * @param \App\Model\Entity\Docente $docente
     * @return bool True si se envió correctamente, false en caso contrario.
     */
    private function enviarTokenPorEmail($docente)
    {
        $profile = Configure::read('App.emailProfile', 'default');
        $email = new Email($profile);
        try {
            $email->setTo($docente->email)
                ->emailFormat('both')
                ->setSubject('Token de registro - SACE UPTBAL')
                ->setTemplate('docente_token')
                ->setViewVars(['docente' => $docente])
                ->attachments([
                    'logo.png' => [
                        'file' => WWW_ROOT . 'img' .DS. 'logos' .DS. 'logouptbal.png', 
                        'mimetype' => 'image/png',
                        'contentId' => '734h3r38',
                    ]
                ])
                ->send();
            return true;
        } catch (\Exception $e) {
            $this->log('Error al enviar email a ' . $docente->email . ': ' . $e->getMessage(), 'error');
            return false;
        }
    }

    /**
     * Reenvía el token de registro por correo electrónico al docente.
     *
     * @param string|null $id Docente id.
     * @return \Cake\Http\Response|null
     */
    public function reenviarToken($id = null)
    {
        $this->request->allowMethod(['post']);
        $docente = $this->Docentes->get($id);

        if ($this->enviarTokenPorEmail($docente)) {
            $this->Flash->success(__('Token reenviado correctamente a {0}.', $docente->email));
        } else {
            $this->Flash->error(__('No se pudo reenviar el token a {0}. Intente de nuevo.', $docente->email));
        }

        return $this->redirect(['action' => 'view', $id]);
    }

    /**
     * Delete method
     *
     * @param string|null $id Docente id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $docente = $this->Docentes->get($id);
        if ($this->Docentes->delete($docente)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Docente'));
            $this->Auditorias->registrar('ELIMINA', 'ELIMINA LOS DATOS Docentes ' . json_encode($docente->toArray()));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Docente'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
