<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

class ApiController extends AppController
{
    private $apiToken = null;

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['login']);
        $this->viewBuilder()->setLayout(null);
        $this->autoRender = false;
        $this->response = $this->response->withType('application/json');
        $this->apiToken = $this->request->getHeaderLine('X-API-Token');
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
    }

    private function _validateToken()
    {
        if (empty($this->apiToken)) {
            $this->_respond(['error' => 'Token requerido'], 401);
            return false;
        }

        $usuariosTable = TableRegistry::getTableLocator()->get('Usuarios');
        $user = $usuariosTable->find()
            ->contain(['Rols'])
            ->where(['Usuarios.api_token' => $this->apiToken, 'Usuarios.activo' => 1])
            ->first();

        if (!$user) {
            $this->_respond(['error' => 'Token inválido'], 401);
            return false;
        }

        return $user;
    }

    private function _respond($data, $statusCode = 200)
    {
        $this->response = $this->response->withStatus($statusCode);
        $this->response = $this->response->withStringBody(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $this->response;
    }

    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);

                $token = bin2hex(random_bytes(32));
                $usuariosTable = TableRegistry::getTableLocator()->get('Usuarios');
                $usuario = $usuariosTable->get($user['id']);
                $usuario->api_token = $token;
                $usuariosTable->save($usuario);

                $this->Auditorias->registrar('INGRESA', 'Ingresa al sistema desde App Android');

                $userData = $usuariosTable->get($user['id'], ['contain' => ['Rols']]);

                return $this->_respond([
                    'success' => true,
                    'token' => $token,
                    'user' => [
                        'id' => $userData->id,
                        'cedula' => $userData->cedula,
                        'nombres' => $userData->nombres,
                        'apellidos' => $userData->apellidos,
                        'email' => $userData->email,
                        'username' => $userData->username,
                        'sexo' => $userData->sexo,
                        'foto' => $userData->foto,
                        'roles' => $userData->rols,
                    ]
                ]);
            }
            return $this->_respond(['success' => false, 'error' => 'Usuario o contraseña incorrectos'], 401);
        }
        return $this->_respond(['error' => 'Método no permitido'], 405);
    }

    public function logout()
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        $usuariosTable = TableRegistry::getTableLocator()->get('Usuarios');
        $usuario = $usuariosTable->get($user->id);
        $usuario->api_token = null;
        $usuariosTable->save($usuario);

        $this->Auditorias->registrar('SALE', 'Sale del sistema desde App Android');

        return $this->_respond(['success' => true]);
    }

    public function profile()
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        return $this->_respond([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'cedula' => $user->cedula,
                'nombres' => $user->nombres,
                'apellidos' => $user->apellidos,
                'email' => $user->email,
                'username' => $user->username,
                'sexo' => $user->sexo,
                'foto' => $user->foto,
                'roles' => $user->rols,
            ]
        ]);
    }

    public function dashboard()
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        $estudiantesTable = TableRegistry::getTableLocator()->get('Estudiantes');
        $docentesTable = TableRegistry::getTableLocator()->get('Docentes');
        $cursosTable = TableRegistry::getTableLocator()->get('Cursos');
        $sedesTable = TableRegistry::getTableLocator()->get('Sedes');

        $totalEstudiantes = $estudiantesTable->find()->where(['activo' => 1])->count();
        $totalDocentes = $docentesTable->find()->where(['activo' => 1])->count();
        $totalCursos = $cursosTable->find()->where(['activo' => 1])->count();
        $totalSedes = $sedesTable->find()->where(['activa' => 1])->count();

        return $this->_respond([
            'success' => true,
            'data' => [
                'total_estudiantes' => $totalEstudiantes,
                'total_docentes' => $totalDocentes,
                'total_cursos' => $totalCursos,
                'total_sedes' => $totalSedes,
            ]
        ]);
    }

    // ==================== ESTUDIANTES ====================

    public function estudiantes()
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        $estudiantesTable = TableRegistry::getTableLocator()->get('Estudiantes');
        $query = $estudiantesTable->find()
            ->contain(['Paises', 'Estados', 'Municipios', 'Parroquias'])
            ->order(['Estudiantes.id' => 'DESC'])
            ->limit(100);

        $conditions = [];
        $search = $this->request->getQuery('search');
        if ($search) {
            $conditions[] = [
                'OR' => [
                    'Estudiantes.cedula LIKE' => "%$search%",
                    'Estudiantes.nombres LIKE' => "%$search%",
                    'Estudiantes.apellidos LIKE' => "%$search%",
                    'Estudiantes.expediente LIKE' => "%$search%",
                ]
            ];
        }
        $activo = $this->request->getQuery('activo');
        if ($activo !== null) {
            $conditions['Estudiantes.activo'] = (int)$activo;
        }
        if (!empty($conditions)) {
            $query->where($conditions);
        }

        $estudiantes = $query->toArray();

        return $this->_respond(['success' => true, 'data' => $estudiantes]);
    }

    public function estudianteView($id)
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        $estudiantesTable = TableRegistry::getTableLocator()->get('Estudiantes');
        $estudiante = $estudiantesTable->get($id, [
            'contain' => ['Paises', 'Estados', 'Municipios', 'Parroquias']
        ]);

        return $this->_respond(['success' => true, 'data' => $estudiante]);
    }

    public function estudianteGuardar()
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        if (!$this->request->is('post') && !$this->request->is('put')) {
            return $this->_respond(['error' => 'Método no permitido'], 405);
        }

        $estudiantesTable = TableRegistry::getTableLocator()->get('Estudiantes');
        $data = $this->request->getData();

        $id = $data['id'] ?? null;
        if ($id) {
            $estudiante = $estudiantesTable->get($id);
            $estudiantesTable->patchEntity($estudiante, $data);
            $evento = 'MODIFICA';
        } else {
            $estudiante = $estudiantesTable->newEntity($data);
            $evento = 'REGISTRA';
        }

        if ($estudiantesTable->save($estudiante)) {
            $this->Auditorias->registrar($evento, 'API - ESTUDIANTE ID: ' . $estudiante->id);
            return $this->_respond(['success' => true, 'data' => $estudiante]);
        }

        return $this->_respond(['success' => false, 'error' => $estudiante->getErrors()], 400);
    }

    public function estudianteEliminar($id)
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        $estudiantesTable = TableRegistry::getTableLocator()->get('Estudiantes');
        $estudiante = $estudiantesTable->get($id);
        $estudiante->activo = 0;

        if ($estudiantesTable->save($estudiante)) {
            $this->Auditorias->registrar('ELIMINA', 'API - ESTUDIANTE ID: ' . $id);
            return $this->_respond(['success' => true]);
        }

        return $this->_respond(['success' => false, 'error' => 'No se pudo eliminar'], 400);
    }

    // ==================== DOCENTES ====================

    public function docentes()
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        $docentesTable = TableRegistry::getTableLocator()->get('Docentes');
        $query = $docentesTable->find()
            ->contain(['Departamentos'])
            ->order(['Docentes.id' => 'DESC'])
            ->limit(100);

        $search = $this->request->getQuery('search');
        if ($search) {
            $query->where([
                'OR' => [
                    'Docentes.cedula LIKE' => "%$search%",
                    'Docentes.nombres LIKE' => "%$search%",
                    'Docentes.apellidos LIKE' => "%$search%",
                ]
            ]);
        }

        return $this->_respond(['success' => true, 'data' => $query->toArray()]);
    }

    public function docenteView($id)
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        $docentesTable = TableRegistry::getTableLocator()->get('Docentes');
        $docente = $docentesTable->get($id, ['contain' => ['Departamentos']]);

        return $this->_respond(['success' => true, 'data' => $docente]);
    }

    public function docenteGuardar()
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        if (!$this->request->is('post') && !$this->request->is('put')) {
            return $this->_respond(['error' => 'Método no permitido'], 405);
        }

        $docentesTable = TableRegistry::getTableLocator()->get('Docentes');
        $data = $this->request->getData();

        $id = $data['id'] ?? null;
        if ($id) {
            $docente = $docentesTable->get($id);
            $docentesTable->patchEntity($docente, $data);
            $evento = 'MODIFICA';
        } else {
            $docente = $docentesTable->newEntity($data);
            $evento = 'REGISTRA';
        }

        if ($docentesTable->save($docente)) {
            $this->Auditorias->registrar($evento, 'API - DOCENTE ID: ' . $docente->id);
            return $this->_respond(['success' => true, 'data' => $docente]);
        }

        return $this->_respond(['success' => false, 'error' => $docente->getErrors()], 400);
    }

    public function docenteEliminar($id)
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        $docentesTable = TableRegistry::getTableLocator()->get('Docentes');
        $docente = $docentesTable->get($id);
        $docente->activo = 0;

        if ($docentesTable->save($docente)) {
            $this->Auditorias->registrar('ELIMINA', 'API - DOCENTE ID: ' . $id);
            return $this->_respond(['success' => true]);
        }

        return $this->_respond(['success' => false, 'error' => 'No se pudo eliminar'], 400);
    }

    // ==================== CURSOS ====================

    public function cursos()
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        $cursosTable = TableRegistry::getTableLocator()->get('Cursos');
        $query = $cursosTable->find()
            ->contain(['Sedes', 'Periodos', 'Carreras', 'Trayectos', 'Asignaturas', 'Docentes', 'Aulas'])
            ->order(['Cursos.id' => 'DESC'])
            ->limit(100);

        $search = $this->request->getQuery('search');
        if ($search) {
            $query->where([
                'OR' => [
                    'Cursos.seccion LIKE' => "%$search%",
                ]
            ]);
        }

        return $this->_respond(['success' => true, 'data' => $query->toArray()]);
    }

    public function cursoView($id)
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        $cursosTable = TableRegistry::getTableLocator()->get('Cursos');
        $curso = $cursosTable->get($id, [
            'contain' => ['Sedes', 'Periodos', 'Carreras', 'Trayectos', 'Asignaturas', 'Docentes', 'Aulas']
        ]);

        return $this->_respond(['success' => true, 'data' => $curso]);
    }

    public function cursoGuardar()
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        if (!$this->request->is('post') && !$this->request->is('put')) {
            return $this->_respond(['error' => 'Método no permitido'], 405);
        }

        $cursosTable = TableRegistry::getTableLocator()->get('Cursos');
        $data = $this->request->getData();

        $id = $data['id'] ?? null;
        if ($id) {
            $curso = $cursosTable->get($id);
            $cursosTable->patchEntity($curso, $data);
            $evento = 'MODIFICA';
        } else {
            $curso = $cursosTable->newEntity($data);
            $evento = 'REGISTRA';
        }

        if ($cursosTable->save($curso)) {
            $this->Auditorias->registrar($evento, 'API - CURSO ID: ' . $curso->id);
            return $this->_respond(['success' => true, 'data' => $curso]);
        }

        return $this->_respond(['success' => false, 'error' => $curso->getErrors()], 400);
    }

    public function cursoEliminar($id)
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        $cursosTable = TableRegistry::getTableLocator()->get('Cursos');
        $curso = $cursosTable->get($id);
        $curso->activo = 0;

        if ($cursosTable->save($curso)) {
            $this->Auditorias->registrar('ELIMINA', 'API - CURSO ID: ' . $id);
            return $this->_respond(['success' => true]);
        }

        return $this->_respond(['success' => false, 'error' => 'No se pudo eliminar'], 400);
    }

    // ==================== HORARIOS ====================

    public function horarios()
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        $horariosTable = TableRegistry::getTableLocator()->get('Horarios');
        $query = $horariosTable->find()
            ->contain(['Sedes', 'Periodos'])
            ->order(['Horarios.id' => 'DESC'])
            ->limit(100);

        $periodoId = $this->request->getQuery('periodo_id');
        if ($periodoId) {
            $query->where(['Horarios.periodo_id' => $periodoId]);
        }
        $sedeId = $this->request->getQuery('sede_id');
        if ($sedeId) {
            $query->where(['Horarios.sede_id' => $sedeId]);
        }

        return $this->_respond(['success' => true, 'data' => $query->toArray()]);
    }

    public function horarioView($id)
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        $horariosTable = TableRegistry::getTableLocator()->get('Horarios');
        $horario = $horariosTable->get($id, ['contain' => ['Sedes', 'Periodos']]);

        return $this->_respond(['success' => true, 'data' => $horario]);
    }

    public function horarioGuardar()
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        if (!$this->request->is('post') && !$this->request->is('put')) {
            return $this->_respond(['error' => 'Método no permitido'], 405);
        }

        $horariosTable = TableRegistry::getTableLocator()->get('Horarios');
        $data = $this->request->getData();

        $id = $data['id'] ?? null;
        if ($id) {
            $horario = $horariosTable->get($id);
            $horariosTable->patchEntity($horario, $data);
            $evento = 'MODIFICA';
        } else {
            $horario = $horariosTable->newEntity($data);
            $evento = 'REGISTRA';
        }

        if ($horariosTable->save($horario)) {
            $this->Auditorias->registrar($evento, 'API - HORARIO ID: ' . $horario->id);
            return $this->_respond(['success' => true, 'data' => $horario]);
        }

        return $this->_respond(['success' => false, 'error' => $horario->getErrors()], 400);
    }

    public function horarioEliminar($id)
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        $horariosTable = TableRegistry::getTableLocator()->get('Horarios');
        $horario = $horariosTable->get($id);
        $horario->activo = 0;

        if ($horariosTable->save($horario)) {
            $this->Auditorias->registrar('ELIMINA', 'API - HORARIO ID: ' . $id);
            return $this->_respond(['success' => true]);
        }

        return $this->_respond(['success' => false, 'error' => 'No se pudo eliminar'], 400);
    }

    // ==================== CATÁLOGOS ====================

    public function periodos()
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        $table = TableRegistry::getTableLocator()->get('Periodos');
        $data = $table->find()->where(['activo' => 1])->order(['id' => 'DESC'])->toArray();
        return $this->_respond(['success' => true, 'data' => $data]);
    }

    public function sedes()
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        $table = TableRegistry::getTableLocator()->get('Sedes');
        $data = $table->find()->where(['activa' => 1])->order(['nombre' => 'ASC'])->toArray();
        return $this->_respond(['success' => true, 'data' => $data]);
    }

    public function programas()
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        $table = TableRegistry::getTableLocator()->get('Programas');
        $query = $table->find()->contain(['Carreras', 'Subsistemas'])->where(['Programas.activo' => 1])->order(['Programas.nombre' => 'ASC']);

        $carreraId = $this->request->getQuery('carrera_id');
        if ($carreraId) {
            $query->where(['Programas.carrera_id' => $carreraId]);
        }

        return $this->_respond(['success' => true, 'data' => $query->toArray()]);
    }

    public function asignaturas()
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        $table = TableRegistry::getTableLocator()->get('Asignaturas');
        $data = $table->find()->where(['activa' => 1])->contain(['GrupoAsignaturas'])->order(['Asignaturas.nombre' => 'ASC'])->toArray();
        return $this->_respond(['success' => true, 'data' => $data]);
    }

    public function aulas()
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        $table = TableRegistry::getTableLocator()->get('Aulas');
        $query = $table->find()->contain(['Sedes'])->where(['Aulas.condicion' => 1])->order(['Aulas.nombre' => 'ASC']);

        $sedeId = $this->request->getQuery('sede_id');
        if ($sedeId) {
            $query->where(['Aulas.sede_id' => $sedeId]);
        }

        return $this->_respond(['success' => true, 'data' => $query->toArray()]);
    }

    public function carreras()
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        $table = TableRegistry::getTableLocator()->get('Carreras');
        $data = $table->find()->where(['activa' => 1])->contain(['MensionCarreras'])->order(['Carreras.nombre' => 'ASC'])->toArray();
        return $this->_respond(['success' => true, 'data' => $data]);
    }

    public function trayectos()
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        $table = TableRegistry::getTableLocator()->get('Trayectos');
        $data = $table->find()->where(['activo' => 1])->order(['nombre' => 'ASC'])->toArray();
        return $this->_respond(['success' => true, 'data' => $data]);
    }
}
