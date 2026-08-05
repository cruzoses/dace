<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Lib\NotasCalculador;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;

class ApiController extends AppController
{
    private $apiToken = null;

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Captcha', ['preset' => 'Default']);
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow();
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

    // ==================== CAPTCHA ====================

    public function captcha()
    {
        $captchaId = $this->Captcha->generate();
        $config = $this->Captcha->getCaptchaConfig($captchaId);
        $stored = $this->request->getSession()->read('Captcha.' . $captchaId);
        $code = $stored['code'] ?? '';
        $codeDisplay = $stored['code_display'] ?? $code;

        $captcha = new \App\Lib\SecurimageCaptcha();
        $captcha->image_width         = isset($config['image_width']) ? $config['image_width'] : 215;
        $captcha->image_height        = isset($config['image_height']) ? $config['image_height'] : 80;
        $captcha->font_ratio          = isset($config['font_ratio']) ? $config['font_ratio'] : 0.4;
        $captcha->image_bg_color      = isset($config['image_bg_color']) ? $config['image_bg_color'] : '#ffffff';
        $captcha->text_color          = isset($config['text_color']) ? $config['text_color'] : '#707070';
        $captcha->line_color          = isset($config['line_color']) ? $config['line_color'] : '#707070';
        $captcha->noise_color         = isset($config['noise_color']) ? $config['noise_color'] : '#707070';
        $captcha->num_lines           = isset($config['num_lines']) ? $config['num_lines'] : 5;
        $captcha->noise_level         = isset($config['noise_level']) ? $config['noise_level'] : 2;
        $captcha->perturbation        = isset($config['perturbation']) ? $config['perturbation'] : 0.85;
        $captcha->use_random_spaces   = isset($config['use_random_spaces']) ? $config['use_random_spaces'] : false;
        $captcha->use_text_angles     = isset($config['use_text_angles']) ? $config['use_text_angles'] : false;
        $captcha->use_random_baseline = isset($config['use_random_baseline']) ? $config['use_random_baseline'] : false;
        $captcha->use_random_boxes    = isset($config['use_random_boxes']) ? $config['use_random_boxes'] : false;
        $captcha->background_directory = isset($config['background_directory']) ? $config['background_directory'] : null;
        $captcha->image_signature     = isset($config['image_signature']) ? $config['image_signature'] : '';
        $captcha->signature_color     = isset($config['signature_color']) ? $config['signature_color'] : '#707070';

        if (!empty($config['ttf_files'])) {
            $captcha->ttf_file = $config['ttf_files'];
        }

        $imageData = $captcha->generate($code, $codeDisplay);
        $imageBase64 = 'data:image/png;base64,' . base64_encode($imageData);

        return $this->_respond([
            'success' => true,
            'captcha_id' => $captchaId,
            'question' => $codeDisplay,
            'image_base64' => $imageBase64,
        ]);
    }

    // ==================== REGISTRO ====================

    public function registroEstudiante()
    {
        if (!$this->request->is('post')) {
            return $this->_respond(['error' => 'Método no permitido'], 405);
        }

        $data = $this->request->getData();

        $captchaId = $data['captcha_id'] ?? null;
        $captchaCode = $data['captcha_code'] ?? null;
        if (empty($captchaId) || !$this->Captcha->validate($captchaCode, $captchaId)) {
            return $this->_respond(['success' => false, 'error' => 'Código captcha incorrecto.'], 400);
        }

        if (empty($data['password_confirmar']) || $data['password'] !== $data['password_confirmar']) {
            return $this->_respond(['success' => false, 'error' => 'Las contraseñas no coinciden.'], 400);
        }

        $cedula = $data['cedula'] ?? null;
        $token = $data['token'] ?? null;
        $expediente = $data['expediente'] ?? null;
        if (empty($cedula) || empty($token) || empty($expediente)) {
            return $this->_respond(['success' => false, 'error' => 'Debe ingresar su cédula, número de expediente y clave de registro.'], 400);
        }

        $usuariosTable = TableRegistry::getTableLocator()->get('Usuarios');
        $rolEstudiante = $usuariosTable->Rols->findByNombre('ESTUDIANTE')->first();
        if (!$rolEstudiante) {
            return $this->_respond(['success' => false, 'error' => 'El rol ESTUDIANTE no está configurado.'], 500);
        }

        $estudiante = $usuariosTable->Estudiantes->find()
            ->where(['cedula' => $cedula, 'usuario_id IS' => null, 'activo' => 1])
            ->first();

        if (!$estudiante) {
            return $this->_respond(['success' => false, 'error' => 'La cédula ingresada no está registrada como estudiante o ya tiene un usuario asociado.'], 400);
        }

        if ($estudiante->expediente !== $expediente || $estudiante->token !== $token) {
            return $this->_respond(['success' => false, 'error' => 'El número de expediente o la clave de registro no son correctos.'], 400);
        }

        unset($data['token'], $data['expediente'], $data['password_confirmar'], $data['CaptchaCode'], $data['captcha_id'], $data['captcha_code'], $data['rols']);
        $data['activo'] = 1;

        $usuario = $usuariosTable->newEntity($data);
        if (!$usuariosTable->save($usuario)) {
            $msg = 'No se pudo completar el registro.';
            foreach ($usuario->getErrors() as $field => $fieldErrors) {
                $msg .= ' ' . $field . ': ' . implode(', ', (array)$fieldErrors);
            }
            return $this->_respond(['success' => false, 'error' => $msg], 400);
        }

        $usuariosTable->Rols->link($usuario, [$rolEstudiante]);
        $estudiante->usuario_id = $usuario->id;
        $usuariosTable->Estudiantes->save($estudiante);

        $this->Auditorias->registrar('REGISTRA', 'Registro estudiante autónomo para usuario ' . $usuario->username);

        return $this->_respond(['success' => true, 'message' => 'Registro exitoso. Ya puede ingresar al sistema.']);
    }

    public function registroDocente()
    {
        if (!$this->request->is('post')) {
            return $this->_respond(['error' => 'Método no permitido'], 405);
        }

        $data = $this->request->getData();

        $captchaId = $data['captcha_id'] ?? null;
        $captchaCode = $data['captcha_code'] ?? null;
        if (empty($captchaId) || !$this->Captcha->validate($captchaCode, $captchaId)) {
            return $this->_respond(['success' => false, 'error' => 'Código captcha incorrecto.'], 400);
        }

        if (empty($data['password_confirmar']) || $data['password'] !== $data['password_confirmar']) {
            return $this->_respond(['success' => false, 'error' => 'Las contraseñas no coinciden.'], 400);
        }

        $cedula = $data['cedula'] ?? null;
        $token = $data['token'] ?? null;
        if (empty($cedula) || empty($token)) {
            return $this->_respond(['success' => false, 'error' => 'Debe ingresar su cédula y clave de registro.'], 400);
        }

        $usuariosTable = TableRegistry::getTableLocator()->get('Usuarios');
        $rolDocente = $usuariosTable->Rols->findByNombre('DOCENTE')->first();
        if (!$rolDocente) {
            return $this->_respond(['success' => false, 'error' => 'El rol DOCENTE no está configurado.'], 500);
        }

        $docente = $usuariosTable->Docentes->find()
            ->where(['cedula' => $cedula, 'usuario_id IS' => null, 'activo' => 1])
            ->first();

        if (!$docente) {
            return $this->_respond(['success' => false, 'error' => 'La cédula ingresada no está registrada como docente o ya tiene un usuario asociado.'], 400);
        }

        if ($docente->token !== $token) {
            return $this->_respond(['success' => false, 'error' => 'La clave de registro no es correcta.'], 400);
        }

        unset($data['token'], $data['password_confirmar'], $data['CaptchaCode'], $data['captcha_id'], $data['captcha_code'], $data['rols']);
        $data['activo'] = 1;

        $usuario = $usuariosTable->newEntity($data);
        if (!$usuariosTable->save($usuario)) {
            $msg = 'No se pudo completar el registro.';
            foreach ($usuario->getErrors() as $field => $fieldErrors) {
                $msg .= ' ' . $field . ': ' . implode(', ', (array)$fieldErrors);
            }
            return $this->_respond(['success' => false, 'error' => $msg], 400);
        }

        $usuariosTable->Rols->link($usuario, [$rolDocente]);
        $docente->usuario_id = $usuario->id;
        $usuariosTable->Docentes->save($docente);

        $this->Auditorias->registrar('REGISTRA', 'Registro docente autónomo para usuario ' . $usuario->username);

        return $this->_respond(['success' => true, 'message' => 'Registro exitoso. Ya puede ingresar al sistema.']);
    }

    // ==================== RECUPERAR CLAVE ====================

    public function recuperarClave()
    {
        if (!$this->request->is('post')) {
            return $this->_respond(['error' => 'Método no permitido'], 405);
        }

        $email = $this->request->getData('email');
        if (empty($email)) {
            return $this->_respond(['success' => false, 'error' => 'Debe ingresar su correo electrónico.'], 400);
        }

        $usuariosTable = TableRegistry::getTableLocator()->get('Usuarios');
        $usuario = $usuariosTable->findByEmail($email)->first();

        if ($usuario && $usuario->activo) {
            $nuevaClave = substr(bin2hex(random_bytes(8)), 0, 10);
            $usuario = $usuariosTable->patchEntity($usuario, ['password' => $nuevaClave]);

            if ($usuariosTable->save($usuario)) {
                $this->Auditorias->registrar('RECUPERA', 'Recupera contraseña para usuario ' . $usuario->username);

                $profile = Configure::read('App.emailProfile', 'default');
                $emailObj = new Email($profile);
                try {
                    $emailObj->setTo($usuario->email)
                        ->emailFormat('both')
                        ->setSubject('Recuperación de contraseña - SACE UPTBAL')
                        ->setTemplate('usuario_nueva_clave')
                        ->setViewVars([
                            'usuario' => $usuario,
                            'nuevaClave' => $nuevaClave,
                        ])
                        ->send();
                } catch (\Exception $e) {
                    $this->log('Error al enviar email de recuperación a ' . $usuario->email . ': ' . $e->getMessage(), 'error');
                }
            }
        }

        return $this->_respond(['success' => true, 'message' => 'Si el correo existe, recibirá una nueva contraseña.']);
    }

    // ==================== SOLICITAR TOKEN DE REGISTRO ====================

    public function solicitarToken()
    {
        if (!$this->request->is('post')) {
            return $this->_respond(['error' => 'Método no permitido'], 405);
        }

        $data = $this->request->getData();

        $captchaId = $data['captcha_id'] ?? null;
        $captchaCode = $data['captcha_code'] ?? null;
        if (empty($captchaId) || !$this->Captcha->validate($captchaCode, $captchaId)) {
            return $this->_respond(['success' => false, 'error' => 'Código captcha incorrecto.'], 400);
        }

        $cedula = $data['cedula'] ?? null;
        $fechaNacimiento = $data['fecha_nacimiento'] ?? null;
        $email = $data['email'] ?? null;
        if (empty($cedula) || empty($fechaNacimiento) || empty($email)) {
            return $this->_respond(['success' => false, 'error' => 'Debe ingresar su cédula, fecha de nacimiento y correo electrónico.'], 400);
        }

        $usuariosTable = TableRegistry::getTableLocator()->get('Usuarios');

        $estudiante = $usuariosTable->Estudiantes->find()
            ->where(['cedula' => $cedula])
            ->order(['Estudiantes.id' => 'DESC'])
            ->first();

        $fechaRegistrada = $estudiante && $estudiante->fecha_nacimiento ? $estudiante->fecha_nacimiento->format('Y-m-d') : null;
        $emailValido = $estudiante && !empty($estudiante->email)
            && strtolower(trim($estudiante->email)) === strtolower(trim($email));

        if ($estudiante && $emailValido && !empty($fechaRegistrada) && $fechaRegistrada === $fechaNacimiento) {
            $estudiante->token = $this->generateToken();

            if ($usuariosTable->Estudiantes->save($estudiante)) {
                $this->_enviarTokenEstudiante($estudiante);
                $this->Auditorias->registrar('SOLICITA', 'Solicitud de clave de registro para estudiante ' . $estudiante->id);
            }
        }

        return $this->_respond(['success' => true, 'message' => 'Si los datos son correctos, recibirá su número de expediente y clave de registro en su correo electrónico.']);
    }

    public function autoRegistroEstudiante()
    {
        if (!$this->request->is('post')) {
            return $this->_respond(['error' => 'Método no permitido'], 405);
        }

        $data = $this->request->getData();

        $captchaId = $data['captcha_id'] ?? null;
        $captchaCode = $data['captcha_code'] ?? null;
        if (empty($captchaId) || !$this->Captcha->validate($captchaCode, $captchaId)) {
            return $this->_respond(['success' => false, 'error' => 'Código captcha incorrecto.'], 400);
        }

        if (empty($data['password_confirmar']) || $data['password'] !== $data['password_confirmar']) {
            return $this->_respond(['success' => false, 'error' => 'Las contraseñas no coinciden.'], 400);
        }

        $cedula = $data['cedula'] ?? null;
        $fechaNacimiento = $data['fecha_nacimiento'] ?? null;
        $email = $data['email'] ?? null;
        $token = $data['token'] ?? null;
        $expediente = $data['expediente'] ?? null;
        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;
        if (empty($cedula) || empty($fechaNacimiento) || empty($email) || empty($token) || empty($expediente) || empty($username) || empty($password)) {
            return $this->_respond(['success' => false, 'error' => 'Debe completar todos los datos de validación.'], 400);
        }

        $usuariosTable = TableRegistry::getTableLocator()->get('Usuarios');
        $rolEstudiante = $usuariosTable->Rols->findByNombre('ESTUDIANTE')->first();
        if (!$rolEstudiante) {
            return $this->_respond(['success' => false, 'error' => 'El rol ESTUDIANTE no está configurado.'], 500);
        }

        $estudiante = $usuariosTable->Estudiantes->find()
            ->where(['cedula' => $cedula])
            ->andWhere(function ($exp) {
                return $exp->or_(['usuario_id IS' => null, 'usuario_id' => '']);
            })
            ->order(['Estudiantes.id' => 'DESC'])
            ->first();

        $fechaRegistrada = $estudiante && $estudiante->fecha_nacimiento ? $estudiante->fecha_nacimiento->format('Y-m-d') : null;
        $datosValidos = $estudiante
            && !empty($estudiante->email) && strtolower(trim($estudiante->email)) === strtolower(trim($email))
            && !empty($fechaRegistrada) && $fechaRegistrada === $fechaNacimiento
            && $estudiante->expediente === $expediente
            && $estudiante->token === $token;

        if (!$datosValidos) {
            return $this->_respond(['success' => false, 'error' => 'Los datos de validación no son correctos. Revise su correo o solicite una nueva clave de registro.'], 400);
        }

        $usuarioData = [
            'username' => $username,
            'password' => $password,
            'cedula' => $estudiante->cedula,
            'nombres' => $estudiante->nombres,
            'apellidos' => $estudiante->apellidos,
            'email' => $estudiante->email,
            'sexo' => $estudiante->sexo,
            'fecha_nacimiento' => $estudiante->fecha_nacimiento ? $estudiante->fecha_nacimiento->format('Y-m-d') : null,
            'activo' => 1,
        ];

        if (!empty($estudiante->telefonos)) {
            $usuarioData['telefonos'] = $estudiante->telefonos;
        }

        $usuario = $usuariosTable->newEntity($usuarioData);
        if (!$usuariosTable->save($usuario)) {
            $msg = 'No se pudo completar el registro.';
            foreach ($usuario->getErrors() as $field => $fieldErrors) {
                $msg .= ' ' . $field . ': ' . implode(', ', (array)$fieldErrors);
            }
            return $this->_respond(['success' => false, 'error' => $msg], 400);
        }

        $usuariosTable->Rols->link($usuario, [$rolEstudiante]);
        $estudiante->usuario_id = $usuario->id;
        $usuariosTable->Estudiantes->save($estudiante);

        $tokenApi = bin2hex(random_bytes(32));
        $usuario->api_token = $tokenApi;
        $usuariosTable->save($usuario);

        $userData = $usuariosTable->get($usuario->id, ['contain' => ['Rols']]);

        $this->Auditorias->registrar('REGISTRA', 'Auto-registro estudiante para usuario ' . $usuario->username);

        return $this->_respond([
            'success' => true,
            'message' => 'Registro exitoso. Ya puede ingresar al sistema.',
            'token' => $tokenApi,
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
            ],
        ]);
    }

    private function _enviarTokenEstudiante($estudiante)
    {
        $profile = Configure::read('App.emailProfile', 'default');
        $email = new Email($profile);
        try {
            $email->setTo($estudiante->email)
                ->emailFormat('both')
                ->setSubject('Clave de registro - SACE UPTBAL')
                ->setTemplate('estudiante_token')
                ->setViewVars(['estudiante' => $estudiante])
                ->attachments([
                    'logo.png' => [
                        'file' => WWW_ROOT . 'img' . DS . 'logos' . DS . 'logouptbal.png',
                        'mimetype' => 'image/png',
                        'contentId' => '734h3r38',
                    ]
                ])
                ->send();
            return true;
        } catch (\Exception $e) {
            $this->log('Error al enviar email a ' . $estudiante->email . ': ' . $e->getMessage(), 'error');
            return false;
        }
    }

    // ==================== ESTUDIANTE (módulos de la app) ====================

    private function _resolverEstudiante($usuarioId, $cedula)
    {
        $estudiantesTable = TableRegistry::getTableLocator()->get('Estudiantes');

        $estudiante = $estudiantesTable->find()
            ->where(['usuario_id' => $usuarioId])
            ->first();

        if ($estudiante) {
            return $estudiante;
        }

        return $estudiantesTable->find()
            ->where(['cedula' => $cedula])
            ->first();
    }

    private function _estudianteCompacto($estudiante)
    {
        return [
            'id' => $estudiante->id,
            'cedula' => $estudiante->cedula,
            'nombres' => $estudiante->nombres,
            'apellidos' => $estudiante->apellidos,
            'fecha_nacimiento' => $estudiante->fecha_nacimiento ? $estudiante->fecha_nacimiento->format('Y-m-d') : null,
            'sexo' => $estudiante->sexo,
            'email' => $estudiante->email,
            'telefonos' => $estudiante->telefonos,
            'expediente' => $estudiante->expediente,
            'activo' => (int)$estudiante->activo,
        ];
    }

    public function meEstudiante()
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        $estudiante = $this->_resolverEstudiante($user->id, $user->cedula);
        if (!$estudiante) {
            return $this->_respond(['success' => false, 'error' => 'No se encontró un registro de estudiante para este usuario.'], 404);
        }

        return $this->_respond(['success' => true, 'data' => $this->_estudianteCompacto($estudiante)]);
    }

    public function situacion()
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        $estudiante = $this->_resolverEstudiante($user->id, $user->cedula);
        if (!$estudiante) {
            return $this->_respond(['success' => false, 'error' => 'No se encontró un registro de estudiante para este usuario.'], 404);
        }

        $data = $this->_calcularSituacion($estudiante->id);
        $this->Auditorias->registrar('CONSULTA', 'API - Situación académica del estudiante ' . $estudiante->id);

        return $this->_respond(['success' => true, 'data' => $data]);
    }

    private function _calcularSituacion($estudianteId)
    {
        $estudianteProgramasTable = TableRegistry::getTableLocator()->get('EstudianteProgramas');
        $programas = $estudianteProgramasTable->find()
            ->where(['EstudianteProgramas.estudiante_id' => $estudianteId, 'EstudianteProgramas.congelado' => 0])
            ->contain(['Carreras', 'Programas'])
            ->order(['EstudianteProgramas.id' => 'DESC'])
            ->toArray();

        $situaciones = [];
        foreach ($programas as $programa) {
            $situacionEstudiantesTable = TableRegistry::getTableLocator()->get('SituacionEstudiantes');
            try {
                $situacionEstudiantesTable->registrarDesdeMalla(
                    $estudianteId,
                    $programa->programa_id,
                    $programa->carrera_id,
                    $programa->periodo_id
                );
            } catch (\Exception $e) {
                $this->log('API situacion registrarDesdeMalla: ' . $e->getMessage(), 'error');
            }

            $asignaturas = $situacionEstudiantesTable->find()
                ->where([
                    'SituacionEstudiantes.estudiante_id' => $estudianteId,
                    'SituacionEstudiantes.programa_id' => $programa->programa_id,
                ])
                ->contain(['Asignaturas', 'Trayectos', 'Periodos'])
                ->order([
                    'SituacionEstudiantes.trayecto_id' => 'ASC',
                    'SituacionEstudiantes.asignatura_id' => 'ASC',
                ])
                ->toArray();

            $mallasTable = TableRegistry::getTableLocator()->get('Mallas');
            $mallas = $mallasTable->find()
                ->where(['Mallas.programa_id' => $programa->programa_id])
                ->toArray();
            $mallasPorAsignatura = [];
            foreach ($mallas as $m) {
                $mallasPorAsignatura[$m->asignatura_id] = $m;
            }

            $notaMinimaPrograma = (float)$programa->programa->nota_minima;
            $totalCreditosPrograma = (int)$programa->programa->creditos;
            $totalAsignaturas = count($asignaturas);
            $totalCreditosAprobados = 0;
            $totalAsignaturasAprobadas = 0;
            $isaNumerador = 0;
            $isaDenominador = 0;
            $iraNumerador = 0;
            $iraDenominador = 0;
            $rows = [];

            foreach ($asignaturas as $asig) {
                $esCualitativa = $asig->has('asignatura') && (int)$asig->asignatura->calificacion === 1;
                $notaMinimaFila = $notaMinimaPrograma;
                if (isset($mallasPorAsignatura[$asig->asignatura_id]) && !empty($mallasPorAsignatura[$asig->asignatura_id]->nota_minima)) {
                    $notaMinimaFila = (float)$mallasPorAsignatura[$asig->asignatura_id]->nota_minima;
                }

                $aprobada = false;
                if (!empty($asig->calificacion)) {
                    if ($esCualitativa) {
                        $aprobada = strtoupper($asig->calificacion) === 'A';
                    } else {
                        $aprobada = (float)$asig->calificacion >= $notaMinimaFila;
                    }
                }

                if (!empty($asig->calificacion)) {
                    if ($esCualitativa) {
                        $notaISA = strtoupper($asig->calificacion) === 'A' ? 20 : 0;
                    } else {
                        $notaISA = (float)$asig->calificacion;
                    }
                    if ($aprobada) {
                        $totalCreditosAprobados += (int)$asig->asignatura->creditos;
                        $totalAsignaturasAprobadas++;
                    }
                    $creditosAsig = (int)$asig->asignatura->creditos;
                    $isaNumerador += $notaISA * $creditosAsig;
                    $isaDenominador += $creditosAsig;

                    if (!empty($asig->acumulado) && (int)$asig->acumulado > 0) {
                        $iraNumerador += (int)$asig->acumulado;
                    } else {
                        $notaIRA = $esCualitativa ? $notaISA : (float)$asig->calificacion;
                        $iraNumerador += $notaIRA * $creditosAsig;
                    }
                    $iraDenominador += $creditosAsig * (int)$asig->cursada;
                }

                $rows[] = [
                    'id' => $asig->id,
                    'trayecto' => $asig->has('trayecto') ? $asig->trayecto->codigo : null,
                    'asignatura_id' => $asig->asignatura_id,
                    'asignatura_codigo' => $asig->has('asignatura') ? $asig->asignatura->codigo : null,
                    'asignatura_nombre' => $asig->has('asignatura') ? $asig->asignatura->nombre : null,
                    'creditos' => $asig->has('asignatura') ? (int)$asig->asignatura->creditos : 0,
                    'calificacion' => $asig->calificacion,
                    'seccion' => !empty($asig->calificacion) ? $asig->seccion : null,
                    'periodo' => !empty($asig->calificacion) && $asig->has('periodo') ? $asig->periodo->codigo : null,
                    'responsable' => !empty($asig->calificacion) ? $asig->responsable : null,
                    'cualitativa' => $esCualitativa ? 1 : 0,
                    'nota_minima' => $notaMinimaFila,
                    'aprobada' => $aprobada ? 1 : 0,
                ];
            }

            $porcentajeAprobado = $totalCreditosPrograma > 0
                ? round(($totalCreditosAprobados / $totalCreditosPrograma) * 100, 1)
                : 0;

            $isa = $isaDenominador > 0 ? round($isaNumerador / $isaDenominador, 5) : 0;
            $ira = $iraDenominador > 0 ? round($iraNumerador / $iraDenominador, 5) : 0;

            $situaciones[] = [
                'programa_id' => $programa->programa_id,
                'programa' => [
                    'id' => $programa->programa->id,
                    'codename' => $programa->programa->codename,
                    'nombre' => $programa->programa->nombre,
                    'nota_minima' => $notaMinimaPrograma,
                    'creditos' => $totalCreditosPrograma,
                ],
                'carrera' => [
                    'id' => $programa->carrera->id,
                    'codigo' => $programa->carrera->codigo,
                    'nombre' => $programa->carrera->nombre,
                ],
                'resumen' => [
                    'creditos_programa' => $totalCreditosPrograma,
                    'total_asignaturas' => $totalAsignaturas,
                    'creditos_aprobados' => $totalCreditosAprobados,
                    'asignaturas_aprobadas' => $totalAsignaturasAprobadas,
                    'porcentaje_aprobado' => $porcentajeAprobado,
                    'isa' => $isa,
                    'ira' => $ira,
                ],
                'asignaturas' => $rows,
            ];
        }

        return $situaciones;
    }

    public function notasLapso()
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        $estudiante = $this->_resolverEstudiante($user->id, $user->cedula);
        if (!$estudiante) {
            return $this->_respond(['success' => false, 'error' => 'No se encontró un registro de estudiante para este usuario.'], 404);
        }

        $estudianteCursosTable = TableRegistry::getTableLocator()->get('EstudianteCursos');
        $ecs = $estudianteCursosTable->find()
            ->where([
                'EstudianteCursos.estudiante_id' => $estudiante->id,
                'Cursos.activo' => 1,
            ])
            ->contain(['Cursos' => ['Asignaturas', 'Periodos', 'Docentes', 'Aulas']])
            ->order(['Cursos.id' => 'DESC'])
            ->toArray();

        $indicadorCursosTable = TableRegistry::getTableLocator()->get('IndicadorCursos');
        $cursoNotasTable = TableRegistry::getTableLocator()->get('CursoNotas');

        $data = [];
        foreach ($ecs as $ec) {
            $curso = $ec->curso;

            $indicadores = $indicadorCursosTable->find()
                ->where(['curso_id' => $curso->id])
                ->contain(['Indicadores', 'ContenidosCursos'])
                ->order(['IndicadorCursos.id' => 'ASC'])
                ->toArray();

            $contenidoIds = [];
            $contenidos = [];
            $aContenidos = [];
            foreach ($indicadores as $ind) {
                if (!empty($ind->contenidos_cursos)) {
                    foreach ($ind->contenidos_cursos as $cc) {
                        if ((int)$cc->activo !== 1) {
                            continue;
                        }
                        $contenidoIds[] = $cc->id;
                        $contenidos[$cc->id] = [
                            'descripcion' => $cc->descripcion,
                            'detalle' => $cc->detalle,
                            'fecha' => $cc->fecha ? $cc->fecha->format('Y-m-d') : null,
                            'ponderacion' => $cc->ponderacion,
                            'escala_nota' => $ind->escala_nota,
                            'indicador' => $ind->has('indicador') ? $ind->indicador->nombre : null,
                        ];
                        $cc->indicador_curso = $ind;
                        $aContenidos[] = $cc;
                    }
                }
            }

            $notas = [];
            if (!empty($contenidoIds)) {
                $notas = $cursoNotasTable->find()
                    ->where([
                        'estudiante_id' => $estudiante->id,
                        'contenido_curso_id IN' => $contenidoIds,
                    ])
                    ->toArray();
            }

            $notasMap = [];
            $aNotasMap = [];
            foreach ($notas as $n) {
                $notasMap[(int)$n->contenido_curso_id] = $n->calificacion;
                $aNotasMap[(int)$n->estudiante_id][(int)$n->contenido_curso_id] = $n->calificacion;
            }

            $resumen = null;
            if (!empty($aContenidos)) {
                $aTotales = NotasCalculador::calcularTotales(
                    (int)$curso->asignatura->calificacion,
                    $aContenidos,
                    $aNotasMap
                );
                if (isset($aTotales[$estudiante->id])) {
                    $resumen = $aTotales[$estudiante->id];
                }
            }

            $evaluaciones = [];
            foreach ($contenidoIds as $cid) {
                $contenido = $contenidos[$cid];
                $contenido['id'] = $cid;
                $contenido['nota'] = $notasMap[$cid] ?? null;
                $evaluaciones[] = $contenido;
            }

            $data[] = [
                'id' => $ec->id,
                'curso_id' => $curso->id,
                'seccion' => $curso->seccion,
                'horario' => $curso->horario,
                'asignatura' => [
                    'id' => $curso->asignatura->id,
                    'codigo' => $curso->asignatura->codigo,
                    'nombre' => $curso->asignatura->nombre,
                    'creditos' => (int)$curso->asignatura->creditos,
                ],
                'periodo' => [
                    'id' => $curso->periodo->id,
                    'codigo' => $curso->periodo->codigo,
                    'lapso' => $curso->periodo->lapso,
                ],
                'docente' => $curso->docente ? $curso->docente->nombres . ' ' . $curso->docente->apellidos : null,
                'calificacion' => $ec->calificacion,
                'recuperacion' => $ec->recuperacion,
                'definitiva' => $ec->definitiva,
                'observacion' => $ec->observacion,
                'activo' => (int)$ec->activo,
                'evaluaciones' => $evaluaciones,
                'resumen' => $resumen ? [
                    'total' => $resumen['total'],
                    'final' => $resumen['final'],
                    'por_indicador' => $resumen['porIndicador'],
                ] : null,
            ];
        }

        $this->Auditorias->registrar('CONSULTA', 'API - Notas de lapso del estudiante ' . $estudiante->id);

        return $this->_respond(['success' => true, 'data' => $data]);
    }

    public function inscripciones()
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        $estudiante = $this->_resolverEstudiante($user->id, $user->cedula);
        if (!$estudiante) {
            return $this->_respond(['success' => false, 'error' => 'No se encontró un registro de estudiante para este usuario.'], 404);
        }

        $estudianteProgramasTable = TableRegistry::getTableLocator()->get('EstudianteProgramas');
        $programas = $estudianteProgramasTable->find()
            ->where(['EstudianteProgramas.estudiante_id' => $estudiante->id])
            ->contain(['Carreras', 'Programas', 'Sedes', 'Periodos'])
            ->order(['EstudianteProgramas.id' => 'DESC'])
            ->toArray();

        $data = [];
        foreach ($programas as $p) {
            $data[] = [
                'id' => $p->id,
                'carrera_id' => $p->carrera_id,
                'carrera' => $p->has('carrera') ? [
                    'codigo' => $p->carrera->codigo,
                    'nombre' => $p->carrera->nombre,
                ] : null,
                'programa_id' => $p->programa_id,
                'programa' => $p->has('programa') ? [
                    'codename' => $p->programa->codename,
                    'nombre' => $p->programa->nombre,
                    'nota_minima' => $p->programa->nota_minima,
                ] : null,
                'sede_id' => $p->sede_id,
                'sede' => $p->has('sede') ? $p->sede->nombre : null,
                'periodo_id' => $p->periodo_id,
                'periodo' => $p->has('periodo') ? [
                    'codigo' => $p->periodo->codigo,
                    'lapso' => $p->periodo->lapso,
                ] : null,
                'fecha_egreso' => $p->fecha_egreso ? $p->fecha_egreso->format('Y-m-d') : null,
                'cohorte' => $p->cohorte,
                'isa' => $p->isa,
                'ira' => $p->ira,
                'culminado' => (int)$p->culminado,
                'congelado' => (int)$p->congelado,
                'activo' => (int)$p->activo,
            ];
        }

        $this->Auditorias->registrar('CONSULTA', 'API - Inscripciones del estudiante ' . $estudiante->id);

        return $this->_respond(['success' => true, 'data' => $data]);
    }

    public function historicos()
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        $estudiante = $this->_resolverEstudiante($user->id, $user->cedula);
        if (!$estudiante) {
            return $this->_respond(['success' => false, 'error' => 'No se encontró un registro de estudiante para este usuario.'], 404);
        }

        $historicosTable = TableRegistry::getTableLocator()->get('Historicos');
        $historicos = $historicosTable->find()
            ->where(['Historicos.estudiante_id' => $estudiante->id])
            ->contain(['Periodos', 'Asignaturas'])
            ->order(['Historicos.periodo_id' => 'DESC', 'Historicos.id' => 'DESC'])
            ->limit(500)
            ->toArray();

        $data = [];
        foreach ($historicos as $h) {
            $data[] = [
                'id' => $h->id,
                'periodo' => $h->has('periodo') ? [
                    'codigo' => $h->periodo->codigo,
                    'lapso' => $h->periodo->lapso,
                ] : null,
                'asignatura' => $h->has('asignatura') ? [
                    'codigo' => $h->asignatura->codigo,
                    'nombre' => $h->asignatura->nombre,
                ] : null,
                'calificacion' => $h->calificacion,
                'seccion' => $h->seccion,
                'responsable' => $h->responsable,
                'created' => $h->created ? $h->created->format('Y-m-d') : null,
            ];
        }

        $this->Auditorias->registrar('CONSULTA', 'API - Históricos del estudiante ' . $estudiante->id);

        return $this->_respond(['success' => true, 'data' => $data]);
    }

    // ==================== NOTICIAS ====================

    public function noticias()
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        $noticiasTable = TableRegistry::getTableLocator()->get('Noticias');
        $noticias = $noticiasTable->find()
            ->where(['Noticias.activa' => 1])
            ->contain(['Usuarios'])
            ->order(['Noticias.fecha' => 'DESC', 'Noticias.id' => 'DESC'])
            ->limit(100)
            ->toArray();

        $data = [];
        foreach ($noticias as $n) {
            $data[] = $this->_noticiaCompacta($n);
        }

        return $this->_respond(['success' => true, 'data' => $data]);
    }

    public function noticiaView($id)
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        $noticiasTable = TableRegistry::getTableLocator()->get('Noticias');
        $noticia = $noticiasTable->find()
            ->where(['Noticias.id' => $id, 'Noticias.activa' => 1])
            ->contain(['Usuarios'])
            ->first();

        if (!$noticia) {
            return $this->_respond(['success' => false, 'error' => 'Noticia no encontrada.'], 404);
        }

        return $this->_respond(['success' => true, 'data' => $this->_noticiaCompacta($noticia)]);
    }

    private function _noticiaCompacta($n)
    {
        return [
            'id' => $n->id,
            'fecha' => $n->fecha ? $n->fecha->format('Y-m-d') : null,
            'titulo' => $n->titulo,
            'contenido' => $n->contenido,
            'autor' => $n->has('usuario') ? $n->usuario->nombres . ' ' . $n->usuario->apellidos : null,
        ];
    }

    // ==================== PERFIL ====================

    public function perfilUpdate()
    {
        $user = $this->_validateToken();
        if (!$user) return $this->response;

        if (!$this->request->is(['post', 'put', 'patch'])) {
            return $this->_respond(['error' => 'Método no permitido'], 405);
        }

        $data = $this->request->getData();

        $usuariosTable = TableRegistry::getTableLocator()->get('Usuarios');
        $usuario = $usuariosTable->get($user->id);

        $datosGuardar = [];
        foreach (['twitter', 'instagram', 'facebook'] as $campo) {
            if (array_key_exists($campo, $data)) {
                $datosGuardar[$campo] = $data[$campo];
            }
        }

        if (!empty($data['foto'])) {
            $resultado = $this->_guardarFoto($data['foto'], $usuario->id);
            if ($resultado === false) {
                return $this->_respond(['success' => false, 'error' => 'Formato de foto no válido. Use jpg, jpeg, png o gif.'], 400);
            }
            if (is_string($resultado)) {
                $datosGuardar['foto'] = $resultado;
            }
        }

        if (empty($datosGuardar)) {
            return $this->_respond(['success' => false, 'error' => 'No hay datos para actualizar.'], 400);
        }

        $usuario = $usuariosTable->patchEntity($usuario, $datosGuardar);
        if (!$usuariosTable->save($usuario)) {
            $msg = 'No se pudo guardar el perfil.';
            foreach ($usuario->getErrors() as $field => $fieldErrors) {
                $msg .= ' ' . $field . ': ' . implode(', ', (array)$fieldErrors);
            }
            return $this->_respond(['success' => false, 'error' => $msg], 400);
        }

        $this->Auditorias->registrar('MODIFICA', 'MODIFICA PERFIL API usuario ' . $usuario->username);

        return $this->_respond([
            'success' => true,
            'message' => 'Perfil actualizado correctamente.',
            'user' => [
                'id' => $usuario->id,
                'cedula' => $usuario->cedula,
                'nombres' => $usuario->nombres,
                'apellidos' => $usuario->apellidos,
                'email' => $usuario->email,
                'username' => $usuario->username,
                'sexo' => $usuario->sexo,
                'foto' => $usuario->foto,
                'twitter' => $usuario->twitter,
                'instagram' => $usuario->instagram,
                'facebook' => $usuario->facebook,
            ],
        ]);
    }

    private function _guardarFoto($fotoData, $userId)
    {
        $raw = null;
        $mime = null;

        if (preg_match('#^data:(image/[a-z]+);base64,#i', $fotoData, $m)) {
            $mime = strtolower($m[1]);
            $raw = base64_decode(substr($fotoData, strpos($fotoData, ',') + 1));
        } else {
            $raw = base64_decode($fotoData);
            if ($raw === false || $raw === '') {
                return false;
            }
            $fInfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $fInfo->buffer($raw);
        }

        if ($raw === false || $raw === '') {
            return false;
        }

        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
        ];

        if (!isset($allowed[$mime])) {
            return false;
        }

        if (strlen($raw) > 2 * 1024 * 1024) {
            return false;
        }

        $ext = $allowed[$mime];
        $fotoDir = WWW_ROOT . 'img' . DS . 'fotos';
        if (!is_dir($fotoDir)) {
            @mkdir($fotoDir, 0777, true);
        }

        $filename = 'foto' . $userId . '.' . $ext;
        if (file_put_contents($fotoDir . DS . $filename, $raw)) {
            return $filename;
        }

        return null;
    }
}
