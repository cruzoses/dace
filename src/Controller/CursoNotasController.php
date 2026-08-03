<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Lib\NotasCalculador;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;

/**
 * CursoNotas Controller
 *
 * @property \App\Model\Table\CursoNotasTable $CursoNotas
 *
 * @method \App\Model\Entity\CursoNota[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CursoNotasController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    public function isAuthorized($user = null)
    {
        if (isset($user['activo']) && isset($user['rols']) && $user['activo']) {
            if ($this->tienePermiso([2, 3, 5])) {
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

        $aEvaluaciones = $this->CursoNotas->ContenidoCursos->find()
            ->where(['indicador_curso_id IN' => $aIndicadorCursoIds])
            ->contain(['IndicadorCursos'])
            ->order(['IndicadorCursos.id' => 'ASC', 'ContenidoCursos.fecha' => 'ASC'])
            ->toArray();

        $nTipoCalificacion = (int)$oCurso->asignatura->calificacion;

        $nNotaMinima = $this->_resolverNotaMinima($oCurso);

        $this->set(compact('oCurso', 'aEstudiantes', 'aEvaluaciones', 'nTipoCalificacion', 'nCursoId', 'bCalifica', 'nNotaMinima'));
    }

    protected function _resolverNotaMinima($oCurso)
    {
        return parent::_resolverNotaMinima($oCurso);
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

            $notasTable = $this->CursoNotas;
            $contenidoTable = $notasTable->ContenidoCursos;
            $indicadorTable = TableRegistry::getTableLocator()->get('IndicadorCursos');
            $estudiantesTable = TableRegistry::getTableLocator()->get('Estudiantes');

            foreach ($aNotas as $aNota) {
                $nContenidoCursoId = (int)($aNota['contenido_curso_id'] ?? 0);
                $nEstudianteId = (int)($aNota['estudiante_id'] ?? 0);
                $sCalificacion = trim($aNota['calificacion'] ?? '');

                if ($nContenidoCursoId === 0 || $nEstudianteId === 0) {
                    continue;
                }

                $oEstudiante = $estudiantesTable->get($nEstudianteId);
                $sEstudianteNombre = $oEstudiante->apellidos . ', ' . $oEstudiante->nombres;
                $sEstudianteCedula = $oEstudiante->cedula;

                $oExistente = $notasTable->find()
                    ->where([
                        'contenido_curso_id' => $nContenidoCursoId,
                        'estudiante_id' => $nEstudianteId,
                    ])
                    ->first();

                if (empty($sCalificacion)) {
                    if ($oExistente) {
                        $sCalifAnterior = $oExistente->calificacion;
                        if ($notasTable->delete($oExistente)) {
                            $nGuardadas++;
                            $this->Auditorias->registrar(
                                'ELIMINA',
                                "Elimina calificacion [{$sCalifAnterior}] del estudiante {$sEstudianteNombre} (C.I. {$sEstudianteCedula}) en la Evaluacion #{$nContenidoCursoId}"
                            );
                        } else {
                            $aErrores[] = "Estudiante #{$nEstudianteId}, Evaluación #{$nContenidoCursoId}: Error al eliminar.";
                        }
                    }
                    continue;
                }

                $oContenidoCurso = $contenidoTable->get($nContenidoCursoId, [
                    'contain' => ['IndicadorCursos'],
                ]);

                $oIndicadorCurso = $indicadorTable->get($oContenidoCurso->indicador_curso_id);
                $nEscalaNota = (int)$oIndicadorCurso->escala_nota;
                $nPonderacion = (int)$oContenidoCurso->ponderacion;

                if ((int)$nTipoCalificacion === 1) {
                    $sCalificacion = strtoupper($sCalificacion);
                    if (!in_array($sCalificacion, ['A', 'R'])) {
                        $aErrores[] = "Estudiante #{$nEstudianteId}, Evaluación #{$nContenidoCursoId}: La calificación cualitativa solo permite A (Aprobado) o R (Reprobado).";
                        continue;
                    }
                } else {
                    if (!is_numeric($sCalificacion) || !preg_match('/^\d+(\.\d{1,2})?$/', $sCalificacion)) {
                        $aErrores[] = "Estudiante #{$nEstudianteId}, Evaluación #{$nContenidoCursoId}: La calificación debe ser numérica con máximo 2 decimales.";
                        continue;
                    }

                    $nCalificacion = (float)$sCalificacion;
                    $bValida = false;

                    if ($nEscalaNota === 1) {
                        $bValida = ($nCalificacion >= 1 && $nCalificacion <= 20);
                        if (!$bValida) {
                            $aErrores[] = "Estudiante #{$nEstudianteId}, Evaluación #{$nContenidoCursoId}: La calificación debe estar entre 1 y 20 (Escala 1-20).";
                        }
                    } elseif ($nEscalaNota === 2 || $nEscalaNota === 3) {
                        $bValida = ($nCalificacion >= 1 && $nCalificacion <= $nPonderacion);
                        if (!$bValida) {
                            $aErrores[] = "Estudiante #{$nEstudianteId}, Evaluación #{$nContenidoCursoId}: La calificación debe estar entre 1 y {$nPonderacion} (Ponderación: {$nPonderacion}%).";
                        }
                    }

                    if (!$bValida) {
                        continue;
                    }
                }

                if ($oExistente) {
                    $sCalifAnterior = $oExistente->calificacion;
                    $oExistente->calificacion = $sCalificacion;
                    $oExistente->responsable = $sResponsable;
                    if ($notasTable->save($oExistente)) {
                        $nGuardadas++;
                        if ($sCalifAnterior !== $sCalificacion) {
                            $this->Auditorias->registrar(
                                'MODIFICA',
                                "Modifica calificacion [{$sCalifAnterior}] -> [{$sCalificacion}] del estudiante {$sEstudianteNombre} (C.I. {$sEstudianteCedula}) en la Evaluacion #{$nContenidoCursoId}"
                            );
                        }
                    } else {
                        $aErroresVal = $oExistente->getErrors();
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
                } else {
                    $oNota = $notasTable->newEntity([
                        'contenido_curso_id' => $nContenidoCursoId,
                        'estudiante_id' => $nEstudianteId,
                        'calificacion' => $sCalificacion,
                        'responsable' => $sResponsable,
                        'procesada' => false
                    ]);

                    if ($notasTable->save($oNota)) {
                        $nGuardadas++;
                        $this->Auditorias->registrar(
                            'REGISTRA',
                            "Registra calificacion [{$sCalificacion}] al estudiante {$sEstudianteNombre} (C.I. {$sEstudianteCedula}) en la Evaluacion #{$nContenidoCursoId}"
                        );
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
     * Cierra el acta: calcula nota final por estudiante y actualiza estudiante_cursos.calificacion.
     *
     * @return \Cake\Http\Response
     */
    public function cerrarActa()
    {
        $this->request->allowMethod(['ajax', 'post']);

        try {
            $nCursoId = (int)$this->request->getData('curso_id');
            $sResponsable = $this->Auth->user('alias');

            if (empty($nCursoId)) {
                return $this->_jsonError('Curso no especificado.');
            }

            $cursosTable = TableRegistry::getTableLocator()->get('Cursos');
            $oCurso = $cursosTable->get($nCursoId, ['contain' => ['Asignaturas']]);
            if (!$oCurso) {
                return $this->_jsonError('Curso no encontrado.');
            }

            $nTipoCalificacion = (int)$oCurso->asignatura->calificacion;

            $ecTable = TableRegistry::getTableLocator()->get('EstudianteCursos');
            $aEstudiantes = $ecTable->find()
                ->where([
                    'EstudianteCursos.curso_id' => $nCursoId,
                    'EstudianteCursos.activo' => 1,
                ])
                ->toArray();

            if (empty($aEstudiantes)) {
                return $this->_jsonError('No hay estudiantes inscritos en este curso.');
            }

            $aIndicadorCursoIds = TableRegistry::getTableLocator()->get('IndicadorCursos')->find()
                ->where(['curso_id' => $nCursoId])
                ->extract('id')
                ->toArray();

            $aContenidoCursos = $this->CursoNotas->ContenidoCursos->find()
                ->where(['indicador_curso_id IN' => $aIndicadorCursoIds])
                ->contain(['IndicadorCursos'])
                ->toArray();

            if (empty($aContenidoCursos)) {
                return $this->_jsonError('No hay evaluaciones definidas en el plan de evaluación.');
            }

            $aNotas = $this->CursoNotas->find()
                ->where(['contenido_curso_id IN' => array_map(function ($o) { return $o->id; }, $aContenidoCursos)])
                ->toArray();

            $aNotasMap = [];
            foreach ($aNotas as $oNota) {
                $nEstId = (int)$oNota->estudiante_id;
                $nContId = (int)$oNota->contenido_curso_id;
                $aNotasMap[$nEstId][$nContId] = $oNota->calificacion;
            }

            $aTotales = NotasCalculador::calcularTotales($nTipoCalificacion, $aContenidoCursos, $aNotasMap);

            $nActualizados = 0;

            foreach ($aEstudiantes as $oEc) {
                $nEstId = (int)$oEc->estudiante_id;
                if (!isset($aTotales[$nEstId])) {
                    continue;
                }
                $oEc->calificacion = $aTotales[$nEstId]['final'];
                $oEc->responsable = $sResponsable;
                if ($ecTable->save($oEc)) {
                    $nActualizados++;
                }
            }

            $oCurso->cerrado = 1;
            $cursosTable->save($oCurso);

            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => true,
                    'message' => "Acta cerrada. Se actualizó la calificación de {$nActualizados} estudiante(s)."
                ]));

        } catch (\Exception $e) {
            return $this->_jsonError('Error del servidor: ' . $e->getMessage());
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

            $aContenidoCursoIds = $this->CursoNotas->ContenidoCursos->find()
                ->where(['indicador_curso_id IN' => $aIndicadorCursoIds])
                ->extract('id')
                ->toArray();

            $aNotas = [];
            if (!empty($aContenidoCursoIds)) {
                $aNotasQuery = $this->CursoNotas->find()
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

    private function _jsonError($sMessage)
    {
        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'success' => false,
                'message' => $sMessage
            ]));
    }
}
