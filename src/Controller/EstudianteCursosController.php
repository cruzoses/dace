<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class EstudianteCursosController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    public function isAuthorized($user = null)
    {
        if (isset($user['activo']) && isset($user['rols']) && $user['activo'] && $this->tienePermiso([1, 2, 3])) {
            return true;
        }
        return parent::isAuthorized($user);
    }

    public function index($estudianteId = null)
    {
        $this->viewBuilder()->setLayout('ajax');

        $estudiantesTable = TableRegistry::getTableLocator()->get('Estudiantes');
        $estudiante = $estudiantesTable->get($estudianteId);

        $programasTable = TableRegistry::getTableLocator()->get('EstudianteProgramas');
        $programas = $programasTable->find()
            ->contain(['Programas', 'Estudiantes'])
            ->where([
                'EstudianteProgramas.estudiante_id' => $estudianteId,
                'EstudianteProgramas.activo' => 1,
            ])
            ->order(['EstudianteProgramas.id' => 'ASC'])
            ->toArray();

        $inscripciones = $this->EstudianteCursos->find()
            ->contain([
                'Cursos.Carreras',
                'Cursos.Trayectos',
                'Cursos.Asignaturas',
            ])
            ->where(['EstudianteCursos.estudiante_id' => $estudianteId])
            ->order(['EstudianteCursos.id' => 'DESC'])
            ->toArray();

        $this->set(compact('estudiante', 'programas', 'inscripciones'));
    }

    public function inscribir($estudianteId = null, $carreraId = null)
    {
        $this->request->allowMethod(['ajax', 'get']);

        $estudiantesTable = TableRegistry::getTableLocator()->get('Estudiantes');
        $estudiante = $estudiantesTable->get($estudianteId);

        $carrerasTable = TableRegistry::getTableLocator()->get('Carreras');
        $carrera = $carrerasTable->get($carreraId);

        $periodos = TableRegistry::getTableLocator()->get('Periodos')
            ->find('list')
            ->where(['Periodos.activo' => 1])
            ->order(['Periodos.id' => 'DESC'])
            ->toArray();

        $this->set(compact('estudiante', 'carrera', 'periodos'));
        $this->viewBuilder()->setLayout('ajax');
    }

    public function inscribirCurso()
    {
        $this->request->allowMethod(['ajax', 'post']);
        $this->autoRender = false;

        $data = $this->request->getData();
        $estudianteId = $data['estudiante_id'];
        $cursoIds = $data['curso_id'] ?? [];
        $responsable = $this->_getUsuarioActual();

        if (empty($cursoIds)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'Debe seleccionar al menos un curso.',
                ]));
        }

        $cursosTable = TableRegistry::getTableLocator()->get('Cursos');
        $inscritos = 0;
        $yaInscrito = 0;
        $sinCupos = 0;

        foreach ($cursoIds as $cursoId) {
            $existe = $this->EstudianteCursos->find()
                ->where([
                    'EstudianteCursos.curso_id' => $cursoId,
                    'EstudianteCursos.estudiante_id' => $estudianteId,
                ])
                ->count();

            if ($existe > 0) {
                $yaInscrito++;
                continue;
            }

            $curso = $cursosTable->get($cursoId);
            $ocupados = $this->EstudianteCursos->find()
                ->where([
                    'EstudianteCursos.curso_id' => $cursoId,
                    'EstudianteCursos.activo' => 1,
                ])
                ->count();

            if ($ocupados >= $curso->cupos) {
                $sinCupos++;
                continue;
            }

            $entity = $this->EstudianteCursos->newEntity();
            $entity->curso_id = $cursoId;
            $entity->estudiante_id = $estudianteId;
            $entity->responsable = $responsable;
            $entity->activo = 1;

            if ($this->EstudianteCursos->save($entity)) {
                $inscritos++;
            }
        }

        $this->Auditorias->registrar('REGISTRA', "INSCRIBE ESTUDIANTE #$estudianteId EN " . $inscritos . " CURSO(S)");

        $mensaje = "$inscritos curso(s) inscrito(s) correctamente.";
        if ($yaInscrito > 0) {
            $mensaje .= " $yaInscrito ya estaba(n) inscrito(s).";
        }
        if ($sinCupos > 0) {
            $mensaje .= " $sinCupos sin cupos disponibles.";
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'success' => $inscritos > 0,
                'message' => $mensaje,
                'inscritos' => $inscritos,
                'ya_inscrito' => $yaInscrito,
                'sin_cupos' => $sinCupos,
            ]));
    }

    public function eliminar($id = null)
    {
        $this->request->allowMethod(['ajax', 'post']);
        $this->autoRender = false;

        $registro = $this->EstudianteCursos->get($id);
        $estudianteId = $registro->estudiante_id;

        if ($this->EstudianteCursos->delete($registro)) {
            $this->Auditorias->registrar('ELIMINA', "ELIMINA INSCRIPCION #$id DEL ESTUDIANTE #$estudianteId");

            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => true,
                    'message' => 'Inscripción eliminada.',
                ]));
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'success' => false,
                'message' => 'Error al eliminar la inscripción.',
            ]));
    }

    public function eliminarSeleccionados()
    {
        $this->request->allowMethod(['ajax', 'post']);
        $this->autoRender = false;

        $data = $this->request->getData();
        $ids = $data['ids'] ?? [];

        if (empty($ids)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'No se seleccionaron inscripciones.',
                ]));
        }

        $eliminados = 0;
        foreach ($ids as $id) {
            $registro = $this->EstudianteCursos->get($id);
            if ($this->EstudianteCursos->delete($registro)) {
                $eliminados++;
            }
        }

        $this->Auditorias->registrar('ELIMINA', "ELIMINA $eliminados INSCRIPCIONES EN LOTE");

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'success' => $eliminados > 0,
                'message' => "$eliminados inscripción(es) eliminada(s).",
                'eliminados' => $eliminados,
            ]));
    }

    public function getCursos()
    {
        $this->request->allowMethod(['ajax', 'get']);
        $this->autoRender = false;

        $periodoId = $this->request->getQuery('periodo_id');
        $carreraId = $this->request->getQuery('carrera_id');
        $estudianteId = $this->request->getQuery('estudiante_id');

        $cursos = [];
        if ($periodoId && $carreraId) {
            $cursosTable = TableRegistry::getTableLocator()->get('Cursos');

            $yaInscritos = $this->EstudianteCursos->find()
                ->select(['EstudianteCursos.curso_id'])
                ->where(['EstudianteCursos.estudiante_id' => $estudianteId])
                ->extract('curso_id')
                ->toArray();

            $query = $cursosTable->find()
                ->contain(['Asignaturas', 'Docentes', 'Trayectos'])
                ->where([
                    'Cursos.periodo_id' => $periodoId,
                    'Cursos.carrera_id' => $carreraId,
                    'Cursos.activo' => 1,
                ])
                ->order(['Cursos.id' => 'DESC']);

            if (!empty($yaInscritos)) {
                $query->where(['Cursos.id NOT IN' => $yaInscritos]);
            }

            foreach ($query->toArray() as $curso) {
                $ocupados = $this->EstudianteCursos->find()
                    ->where([
                        'EstudianteCursos.curso_id' => $curso->id,
                        'EstudianteCursos.activo' => 1,
                    ])
                    ->count();

                $cursos[] = [
                    'id' => $curso->id,
                    'asignatura' => $curso->asignatura->codename,
                    'seccion' => $curso->seccion,
                    'trayecto' => $curso->trayecto->codename,
                    'docente' => $curso->docente->name,
                    'cupos' => $curso->cupos,
                    'disponibles' => max(0, $curso->cupos - $ocupados),
                ];
            }
        }

        $this->response = $this->response->withType('application/json');
        $this->response = $this->response->withStringBody(json_encode(['cursos' => $cursos]));
        return $this->response;
    }

    public function getCupos()
    {
        $this->request->allowMethod(['ajax', 'get']);
        $this->autoRender = false;

        $cursoId = $this->request->getQuery('curso_id');
        $cuposDisponibles = 0;

        if ($cursoId) {
            $cursosTable = TableRegistry::getTableLocator()->get('Cursos');
            $curso = $cursosTable->get($cursoId);

            $inscritos = $this->EstudianteCursos->find()
                ->where([
                    'EstudianteCursos.curso_id' => $cursoId,
                    'EstudianteCursos.activo' => 1,
                ])
                ->count();

            $cuposDisponibles = max(0, $curso->cupos - $inscritos);
        }

        $this->response = $this->response->withType('application/json');
        $this->response = $this->response->withStringBody(json_encode(['cupos_disponibles' => $cuposDisponibles]));
        return $this->response;
    }

    private function _getUsuarioActual()
    {
        $user = $this->Auth->user();
        if ($user) {
            $usuariosTable = TableRegistry::getTableLocator()->get('Usuarios');
            $usuario = $usuariosTable->get($user['id']);
            return $usuario->alias;
        }
        return '';
    }
}
