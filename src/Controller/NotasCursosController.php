<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;

/**
 * NotasCursos Controller
 *
 * @property \App\Model\Table\NotasCursosTable $NotasCursos
 *
 * @method \App\Model\Entity\NotasCurso[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class NotasCursosController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    public function isAuthorized($user = null)
    {
        if (isset($user['activo']) && isset($user['rols']) && $user['activo']) {
            if ($this->tienePermiso([1, 2, 3, 5])) {
                return true;
            }
        }
        return parent::isAuthorized($user);
    }

    /**
     * Grilla de calificaciones para un curso.
     *
     * @param int|string $nCursoId
     * @return \Cake\Http\Response|null
     */
    public function grilla($nCursoId = null)
    {
        $cursosTable = TableRegistry::getTableLocator()->get('Cursos');
        $oCurso = $cursosTable->find()
            ->where(['Cursos.id' => $nCursoId])
            ->contain(['Sedes', 'Periodos', 'Carreras', 'Trayectos', 'Asignaturas', 'Docentes'])
            ->first();

        if (!$oCurso) {
            $this->Flash->error('Curso no encontrado.');
            return $this->redirect(['controller' => 'Profesores', 'action' => 'index']);
        }

        $bCalifica = (bool)$oCurso->periodo->califica;

        $aIndicadorCursoIds = TableRegistry::getTableLocator()->get('IndicadorCursos')->find()
            ->where(['curso_id' => $nCursoId])
            ->extract('id')
            ->toArray();

        if (empty($aIndicadorCursoIds)) {
            $this->Flash->info('No tiene indicadores definidos. Registre el plan de evaluación primero.');
            return $this->redirect(['controller' => 'IndicadorCursos', 'action' => 'index', $nCursoId]);
        }

        $aEstudiantes = TableRegistry::getTableLocator()->get('EstudianteCursos')->find()
            ->where([
                'EstudianteCursos.curso_id' => $nCursoId,
                'EstudianteCursos.activo' => 1,
            ])
            ->contain(['Estudiantes'])
            ->order(['Estudiantes.apellidos' => 'ASC', 'Estudiantes.nombres' => 'ASC'])
            ->toArray();

        $aEvaluaciones = $this->NotasCursos->ContenidoCursos->find()
            ->where(['indicador_curso_id IN' => $aIndicadorCursoIds])
            ->contain(['IndicadorCursos'])
            ->order(['IndicadorCursos.id' => 'ASC', 'ContenidoCursos.fecha' => 'ASC'])
            ->toArray();

        $nTipoCalificacion = (int)$oCurso->asignatura->calificacion;

        $this->set(compact('oCurso', 'aEstudiantes', 'aEvaluaciones', 'nTipoCalificacion', 'nCursoId', 'bCalifica'));
    }

    /**
     * Guarda notas vía AJAX.
     *
     * @return \Cake\Http\Response
     */
    public function guardar()
    {
        $this->request->allowMethod(['ajax', 'post']);

        try {
            $aNotas = $this->request->getData('notas');
            $nTipoCalificacion = (int)$this->request->getData('tipo_calificacion');
            $sResponsable = $this->Auth->user('alias');

            if (empty($aNotas) || !is_array($aNotas)) {
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'message' => 'No se recibieron calificaciones para guardar.'
                    ]));
            }

            $aErrores = [];
            $nGuardadas = 0;

            $notasTable = $this->NotasCursos;
            $contenidoTable = $notasTable->ContenidoCursos;
            $indicadorTable = TableRegistry::getTableLocator()->get('IndicadorCursos');

            foreach ($aNotas as $aNota) {
                $nContenidoCursoId = (int)($aNota['contenido_curso_id'] ?? 0);
                $nEstudianteId = (int)($aNota['estudiante_id'] ?? 0);
                $sCalificacion = trim($aNota['calificacion'] ?? '');

                if (empty($sCalificacion) || $nContenidoCursoId === 0 || $nEstudianteId === 0) {
                    continue;
                }

                $oContenidoCurso = $contenidoTable->get($nContenidoCursoId, [
                    'contain' => ['IndicadorCursos'],
                ]);

                $oIndicadorCurso = $indicadorTable->get($oContenidoCurso->indicador_curso_id);
                $nEscalaNota = (int)$oIndicadorCurso->escala_nota;
                $nPorcentaje = (int)$oIndicadorCurso->porcentaje;

                if ((int)$nTipoCalificacion === 1) {
                    $sCalificacion = strtoupper($sCalificacion);
                    if (!in_array($sCalificacion, ['A', 'R'])) {
                        $aErrores[] = "Estudiante #{$nEstudianteId}, Evaluación #{$nContenidoCursoId}: La calificación cualitativa solo permite A (Aprobado) o R (Reprobado).";
                        continue;
                    }
                } else {
                    if (!is_numeric($sCalificacion)) {
                        $aErrores[] = "Estudiante #{$nEstudianteId}, Evaluación #{$nContenidoCursoId}: La calificación debe ser numérica.";
                        continue;
                    }

                    $nCalificacion = (float)$sCalificacion;
                    $bValida = false;

                    if ($nEscalaNota === 1) {
                        $bValida = ($nCalificacion >= 1 && $nCalificacion <= 20);
                        if (!$bValida) {
                            $aErrores[] = "Estudiante #{$nEstudianteId}, Evaluación #{$nContenidoCursoId}: La calificación debe estar entre 1 y 20 (Escala 1-20).";
                        }
                    } elseif ($nEscalaNota === 2) {
                        $bValida = ($nCalificacion >= 1 && $nCalificacion <= $nPorcentaje);
                        if (!$bValida) {
                            $aErrores[] = "Estudiante #{$nEstudianteId}, Evaluación #{$nContenidoCursoId}: La calificación debe estar entre 1 y {$nPorcentaje} (Escala 1-{$nPorcentaje}).";
                        }
                    } elseif ($nEscalaNota === 3) {
                        $bValida = ($nCalificacion >= 1 && $nCalificacion <= 100);
                        if (!$bValida) {
                            $aErrores[] = "Estudiante #{$nEstudianteId}, Evaluación #{$nContenidoCursoId}: La calificación debe estar entre 1 y 100 (Escala 1-100).";
                        }
                    }

                    if (!$bValida) {
                        continue;
                    }
                }

                $oNota = $notasTable->findOrCreate(
                    [
                        'contenido_curso_id' => $nContenidoCursoId,
                        'estudiante_id' => $nEstudianteId,
                    ],
                    function ($entity) use ($sCalificacion, $sResponsable) {
                        $entity->calificacion = $sCalificacion;
                        $entity->responsable = $sResponsable;
                    }
                );

                if ($oNota->isNew() || $oNota->isDirty('calificacion')) {
                    $oNota->calificacion = $sCalificacion;
                    $oNota->responsable = $sResponsable;
                }

                if ($notasTable->save($oNota)) {
                    $nGuardadas++;
                } else {
                    $aErroresVal = $oNota->getErrors();
                    if (!empty($aErroresVal)) {
                        foreach ($aErroresVal as $aCampo => $aMensajes) {
                            foreach ($aMensajes as $sMsg) {
                                $aErrores[] = "Estudiante #{$nEstudianteId}, Evaluación #{$nContenidoCursoId}: {$sMsg}";
                            }
                        }
                    } else {
                        $aErrores[] = "Estudiante #{$nEstudianteId}, Evaluación #{$nContenidoCursoId}: Error al guardar.";
                    }
                }
            }

            if (!empty($aErrores)) {
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'message' => 'Se guardaron ' . $nGuardadas . ' calificación(es), pero hubo errores:',
                        'errores' => $aErrores
                    ]));
            }

            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => true,
                    'message' => $nGuardadas . ' calificación(es) guardada(s) correctamente.'
                ]));

        } catch (\Exception $e) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'Error del servidor: ' . $e->getMessage()
                ]));
        }
    }

    /**
     * Obtiene las notas existentes para un curso vía AJAX.
     *
     * @param int|string $nCursoId
     * @return \Cake\Http\Response
     */
    public function getNotas($nCursoId = null)
    {
        try {
            $this->request->allowMethod(['ajax', 'get']);

            $aIndicadorCursoIds = TableRegistry::getTableLocator()->get('IndicadorCursos')->find()
                ->where(['curso_id' => $nCursoId])
                ->extract('id')
                ->toArray();

            $aContenidoCursoIds = $this->NotasCursos->ContenidoCursos->find()
                ->where(['indicador_curso_id IN' => $aIndicadorCursoIds])
                ->extract('id')
                ->toArray();

            $aNotas = [];
            if (!empty($aContenidoCursoIds)) {
                $aNotasQuery = $this->NotasCursos->find()
                    ->where(['contenido_curso_id IN' => $aContenidoCursoIds])
                    ->toArray();

                foreach ($aNotasQuery as $oNota) {
                    $nEstId = (int)$oNota->estudiante_id;
                    $nContId = (int)$oNota->contenido_curso_id;
                    if (!isset($aNotas[$nEstId])) {
                        $aNotas[$nEstId] = [];
                    }
                    $aNotas[$nEstId][$nContId] = $oNota->calificacion;
                }
            }

            return $this->response->withType('application/json')
                ->withStringBody(json_encode($aNotas));

        } catch (\Exception $e) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([]));
        }
    }
}
