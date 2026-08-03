<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Cake\Event\Event;

/**
 * Profesores Controller
 *
 * @method \App\Model\Entity\Docente[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProfesoresController extends AppController
{
    public $paginate = [];

	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
	}

	public function isAuthorized($user = null)
	{
        if( isset( $user['activo'] ) && isset( $user['rols'] ) && $user['activo'] )
        {
            if ( $this->tienePermiso([4,5,6]) ) {
                return true;
            }            
        }
		return parent::isAuthorized($user);
	}

    public function index()
    {   
        $userId = $this->Auth->user('id');
        $docente = TableRegistry::getTableLocator()->get('Docentes')->find()
            ->where(['Docentes.usuario_id' => $userId])
            ->contain(['Departamentos','Usuarios'])
            ->first();

        if (!$docente) 
        {
            $this->Flash->error(__('No se encontró un docente asociado a su usuario.'));
            return $this->redirect(['action' => 'homepage']);
        }

        $periodos = TableRegistry::getTableLocator()->get('Periodos')->find('list', [
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
            $cursos = TableRegistry::getTableLocator()->get('Cursos')->find()
                ->where([
                    'Cursos.docente_id' => $docente->id,
                    'Cursos.periodo_id' => $periodoId,
                ])
                ->contain(['Asignaturas', 'Carreras', 'Trayectos', 'Sedes', 'Aulas', 'Periodos'])
                ->order(['Cursos.id' => 'DESC'])
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

    public function listadeclase($nCursoId = null)
    {
        $oCurso = TableRegistry::getTableLocator()->get('Cursos')->find()
            ->where(['Cursos.id' => $nCursoId])
            ->contain(['Sedes','Periodos','Carreras','Trayectos','Asignaturas','Docentes'])
            ->first();

        $aEstudiantes = $this->paginate('EstudianteCursos', [
            'conditions' => ['EstudianteCursos.curso_id' => $nCursoId],
            'contain' => ['Estudiantes'],
            'order' => ['Estudiantes.apellidos' => 'ASC', 'Estudiantes.nombres' => 'ASC'],
        ]);

        $lCargaNota = $oCurso->has('periodo') ? $oCurso->periodo->califica : false;
        $this->set(compact('oCurso', 'aEstudiantes','lCargaNota'));
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
        return $this->redirect(['controller' => 'CursoNotas', 'action' => 'grilla', $cursoId]);
    }

    /**
     * Copia el plan completo (indicadores y evaluaciones) de un curso de origen
     * del mismo docente hacia el curso de destino.
     *
     * @param int|null $nCursoId Id del curso de destino.
     * @return \Cake\Http\Response|null
     */
    public function importarPlan($nCursoId = null)
    {
        $this->request->allowMethod(['post']);

        $docente = $this->_getDocenteActual();
        if (!$docente) {
            $this->Flash->error(__('No se encontró un docente asociado a su usuario.'));
            return $this->redirect(['controller' => 'Profesores', 'action' => 'index']);
        }

        $cursosTable = TableRegistry::getTableLocator()->get('Cursos');
        $oCursoDestino = $cursosTable->find()
            ->where(['Cursos.id' => $nCursoId, 'Cursos.docente_id' => $docente->id])
            ->first();

        if (!$oCursoDestino) {
            $this->Flash->error(__('El curso destino no existe o no le corresponde a su usuario.'));
            return $this->redirect(['controller' => 'Profesores', 'action' => 'index']);
        }

        $nOrigenId = (int)$this->request->getData('curso_origen_id');
        $oCursoOrigen = $cursosTable->find()
            ->where(['Cursos.id' => $nOrigenId, 'Cursos.docente_id' => $docente->id])
            ->first();

        if (!$oCursoOrigen) {
            $this->Flash->error(__('Debe seleccionar un curso de origen válido.'));
            return $this->redirect(['controller' => 'IndicadorCursos', 'action' => 'index', $nCursoId]);
        }

        if ($oCursoOrigen->id == $oCursoDestino->id) {
            $this->Flash->error(__('El curso de origen y destino deben ser diferentes.'));
            return $this->redirect(['controller' => 'IndicadorCursos', 'action' => 'index', $nCursoId]);
        }

        $indicadorCursosTable = TableRegistry::getTableLocator()->get('IndicadorCursos');
        $contenidoCursosTable = TableRegistry::getTableLocator()->get('ContenidoCursos');

        $nIndicadoresDestino = $indicadorCursosTable->find()
            ->where(['curso_id' => $oCursoDestino->id])
            ->count();
        if ($nIndicadoresDestino > 0) {
            $this->Flash->error(__('El curso destino ya tiene indicadores registrados.'));
            return $this->redirect(['controller' => 'IndicadorCursos', 'action' => 'index', $nCursoId]);
        }

        $aIndicadoresOrigen = $indicadorCursosTable->find()
            ->where(['curso_id' => $oCursoOrigen->id])
            ->contain(['ContenidosCursos' => ['sort' => ['ContenidosCursos.id' => 'ASC']]])
            ->order(['IndicadorCursos.id' => 'ASC'])
            ->toArray();

        if (empty($aIndicadoresOrigen)) {
            $this->Flash->warning(__('El curso de origen no tiene indicadores registrados.'));
            return $this->redirect(['controller' => 'IndicadorCursos', 'action' => 'index', $nCursoId]);
        }

        $conn = $indicadorCursosTable->getConnection();
        $nIndicadoresCopiados = 0;
        $nContenidosCopiados = 0;
        $aErrores = [];

        $conn->transactional(function () use (
            $oCursoDestino, $aIndicadoresOrigen,
            $indicadorCursosTable, $contenidoCursosTable,
            &$nIndicadoresCopiados, &$nContenidosCopiados, &$aErrores
        ) {
            $mapIndicadorCurso = [];
            foreach ($aIndicadoresOrigen as $oIndicador) {
                $nuevo = $indicadorCursosTable->newEntity([
                    'curso_id' => $oCursoDestino->id,
                    'indicador_id' => $oIndicador->indicador_id,
                    'desde' => $oIndicador->desde,
                    'hasta' => $oIndicador->hasta,
                    'escala_nota' => $oIndicador->escala_nota,
                    'porcentaje' => $oIndicador->porcentaje,
                ]);
                if (!$indicadorCursosTable->save($nuevo)) {
                    $aErrores[] = 'Indicador ' . $oIndicador->indicador_id . ': ' . $this->_erroresToText($nuevo->getErrors());
                    return false;
                }
                $mapIndicadorCurso[$oIndicador->id] = $nuevo->id;
                $nIndicadoresCopiados++;
            }

            foreach ($aIndicadoresOrigen as $oIndicador) {
                foreach ($oIndicador->contenidos_cursos as $oContenido) {
                    $nuevo = $contenidoCursosTable->newEntity([
                        'indicador_curso_id' => $mapIndicadorCurso[$oIndicador->id],
                        'fecha' => $oContenido->fecha,
                        'descripcion' => $oContenido->descripcion,
                        'detalle' => $oContenido->detalle,
                        'ponderacion' => $oContenido->ponderacion,
                        'activo' => $oContenido->activo,
                    ]);
                    if (!$contenidoCursosTable->save($nuevo)) {
                        $aErrores[] = 'Evaluación ' . $oContenido->id . ': ' . $this->_erroresToText($nuevo->getErrors());
                        return false;
                    }
                    $nContenidosCopiados++;
                }
            }

            return true;
        });

        if (!empty($aErrores)) {
            foreach ($aErrores as $sError) {
                $this->Flash->error($sError);
            }
            return $this->redirect(['controller' => 'IndicadorCursos', 'action' => 'index', $nCursoId]);
        }

        $this->Auditorias->registrar('REGISTRA', 'IMPORTA PLAN - Desde curso ' . $oCursoOrigen->id . ' hacia curso ' . $oCursoDestino->id . ': ' . $nIndicadoresCopiados . ' indicadores y ' . $nContenidosCopiados . ' evaluaciones');
        $this->Flash->success(__('Se importaron {0} indicadores y {1} evaluaciones del curso {2} al curso {3}.',
            $nIndicadoresCopiados, $nContenidosCopiados, $oCursoOrigen->id, $oCursoDestino->id));

        return $this->redirect(['controller' => 'IndicadorCursos', 'action' => 'index', $nCursoId]);
    }

    /**
     * Copia solo las evaluaciones (contenidos) de un curso de origen hacia el
     * curso de destino, emparejando los indicadores por indicador_id.
     *
     * @param int|null $nCursoId Id del curso de destino.
     * @return \Cake\Http\Response|null
     */
    public function importarContenidos($nCursoId = null)
    {
        $this->request->allowMethod(['post']);

        $docente = $this->_getDocenteActual();
        if (!$docente) {
            $this->Flash->error(__('No se encontró un docente asociado a su usuario.'));
            return $this->redirect(['controller' => 'Profesores', 'action' => 'index']);
        }

        $cursosTable = TableRegistry::getTableLocator()->get('Cursos');
        $oCursoDestino = $cursosTable->find()
            ->where(['Cursos.id' => $nCursoId, 'Cursos.docente_id' => $docente->id])
            ->first();

        if (!$oCursoDestino) {
            $this->Flash->error(__('El curso destino no existe o no le corresponde a su usuario.'));
            return $this->redirect(['controller' => 'Profesores', 'action' => 'index']);
        }

        $nOrigenId = (int)$this->request->getData('curso_origen_id');
        $oCursoOrigen = $cursosTable->find()
            ->where(['Cursos.id' => $nOrigenId, 'Cursos.docente_id' => $docente->id])
            ->first();

        if (!$oCursoOrigen) {
            $this->Flash->error(__('Debe seleccionar un curso de origen válido.'));
            return $this->redirect(['controller' => 'ContenidoCursos', 'action' => 'index', $nCursoId]);
        }

        if ($oCursoOrigen->id == $oCursoDestino->id) {
            $this->Flash->error(__('El curso de origen y destino deben ser diferentes.'));
            return $this->redirect(['controller' => 'ContenidoCursos', 'action' => 'index', $nCursoId]);
        }

        $indicadorCursosTable = TableRegistry::getTableLocator()->get('IndicadorCursos');
        $contenidoCursosTable = TableRegistry::getTableLocator()->get('ContenidoCursos');

        $aIndicadoresDestino = $indicadorCursosTable->find()
            ->where(['curso_id' => $oCursoDestino->id])
            ->toArray();
        if (empty($aIndicadoresDestino)) {
            $this->Flash->error(__('El curso destino no tiene indicadores definidos.'));
            return $this->redirect(['controller' => 'IndicadorCursos', 'action' => 'index', $nCursoId]);
        }

        $aDestinoIndicadorCursoIds = array_map(function ($o) {
            return $o->id;
        }, $aIndicadoresDestino);

        $nContenidosDestino = $contenidoCursosTable->find()
            ->where(['indicador_curso_id IN' => $aDestinoIndicadorCursoIds])
            ->count();
        if ($nContenidosDestino > 0) {
            $this->Flash->error(__('El curso destino ya tiene evaluaciones registradas.'));
            return $this->redirect(['controller' => 'ContenidoCursos', 'action' => 'index', $nCursoId]);
        }

        $aIndicadoresOrigen = $indicadorCursosTable->find()
            ->where(['curso_id' => $oCursoOrigen->id])
            ->toArray();
        if (empty($aIndicadoresOrigen)) {
            $this->Flash->warning(__('El curso de origen no tiene indicadores registrados.'));
            return $this->redirect(['controller' => 'ContenidoCursos', 'action' => 'index', $nCursoId]);
        }

        $aOrigenIndicadorCursoIds = array_map(function ($o) {
            return $o->id;
        }, $aIndicadoresOrigen);

        $aContenidosOrigen = $contenidoCursosTable->find()
            ->where(['indicador_curso_id IN' => $aOrigenIndicadorCursoIds])
            ->order(['ContenidoCursos.id' => 'ASC'])
            ->toArray();
        if (empty($aContenidosOrigen)) {
            $this->Flash->warning(__('El curso de origen no tiene evaluaciones registradas.'));
            return $this->redirect(['controller' => 'ContenidoCursos', 'action' => 'index', $nCursoId]);
        }

        $mapIndicadorOrigen = [];
        foreach ($aIndicadoresOrigen as $o) {
            $mapIndicadorOrigen[$o->id] = $o->indicador_id;
        }
        $mapIndicadorDestino = [];
        foreach ($aIndicadoresDestino as $o) {
            $mapIndicadorDestino[$o->indicador_id] = $o->id;
        }

        $aFaltantes = [];
        foreach ($mapIndicadorOrigen as $nIndicadorId) {
            if (!isset($mapIndicadorDestino[$nIndicadorId])) {
                $aFaltantes[] = $nIndicadorId;
            }
        }
        if (!empty($aFaltantes)) {
            $this->Flash->error(__('El curso destino no tiene los indicadores requeridos: {0}', implode(', ', $aFaltantes)));
            return $this->redirect(['controller' => 'ContenidoCursos', 'action' => 'index', $nCursoId]);
        }

        $conn = $contenidoCursosTable->getConnection();
        $nCopiados = 0;
        $aErrores = [];

        $conn->transactional(function () use (
            $aContenidosOrigen, $mapIndicadorOrigen, $mapIndicadorDestino,
            $contenidoCursosTable, &$nCopiados, &$aErrores
        ) {
            foreach ($aContenidosOrigen as $oContenido) {
                $nIndicadorId = $mapIndicadorOrigen[$oContenido->indicador_curso_id];
                $nuevo = $contenidoCursosTable->newEntity([
                    'indicador_curso_id' => $mapIndicadorDestino[$nIndicadorId],
                    'fecha' => $oContenido->fecha,
                    'descripcion' => $oContenido->descripcion,
                    'detalle' => $oContenido->detalle,
                    'ponderacion' => $oContenido->ponderacion,
                    'activo' => $oContenido->activo,
                ]);
                if (!$contenidoCursosTable->save($nuevo)) {
                    $aErrores[] = 'Evaluación ' . $oContenido->id . ': ' . $this->_erroresToText($nuevo->getErrors());
                    return false;
                }
                $nCopiados++;
            }

            return true;
        });

        if (!empty($aErrores)) {
            foreach ($aErrores as $sError) {
                $this->Flash->error($sError);
            }
            return $this->redirect(['controller' => 'ContenidoCursos', 'action' => 'index', $nCursoId]);
        }

        $this->Auditorias->registrar('REGISTRA', 'IMPORTA EVALUACIONES - Desde curso ' . $oCursoOrigen->id . ' hacia curso ' . $oCursoDestino->id . ': ' . $nCopiados . ' evaluaciones');
        $this->Flash->success(__('Se importaron {0} evaluaciones del curso {1} al curso {2}.', $nCopiados, $oCursoOrigen->id, $oCursoDestino->id));

        return $this->redirect(['controller' => 'ContenidoCursos', 'action' => 'index', $nCursoId]);
    }

    private function _getDocenteActual()
    {
        $userId = $this->Auth->user('id');
        if (!$userId) {
            return null;
        }
        return TableRegistry::getTableLocator()->get('Docentes')->find()
            ->where(['Docentes.usuario_id' => $userId])
            ->first();
    }

    private function _erroresToText($aErrores)
    {
        $aMensajes = [];
        array_walk_recursive($aErrores, function ($sMensaje) use (&$aMensajes) {
            if (is_string($sMensaje)) {
                $aMensajes[] = $sMensaje;
            }
        });
        return implode(' ', $aMensajes);
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