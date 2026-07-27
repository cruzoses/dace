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

            $aContenidoCursos = $this->NotasCursos->ContenidoCursos->find()
                ->where(['indicador_curso_id IN' => $aIndicadorCursoIds])
                ->contain(['IndicadorCursos'])
                ->toArray();

            if (empty($aContenidoCursos)) {
                return $this->_jsonError('No hay evaluaciones definidas en el plan de evaluación.');
            }

            $aNotas = $this->NotasCursos->find()
                ->where(['contenido_curso_id IN' => array_map(function ($o) { return $o->id; }, $aContenidoCursos)])
                ->toArray();

            $aNotasMap = [];
            foreach ($aNotas as $oNota) {
                $nEstId = (int)$oNota->estudiante_id;
                $nContId = (int)$oNota->contenido_curso_id;
                $aNotasMap[$nEstId][$nContId] = $oNota->calificacion;
            }

            $nActualizados = 0;

            foreach ($aEstudiantes as $oEc) {
                $nEstId = (int)$oEc->estudiante_id;
                $sFinal = '';

                if ($nTipoCalificacion === 1) {
                    $nA = 0;
                    $nR = 0;
                    foreach ($aContenidoCursos as $oCont) {
                        $sVal = $aNotasMap[$nEstId][$oCont->id] ?? '';
                        $sVal = strtoupper(trim($sVal));
                        if ($sVal === 'A') $nA++;
                        elseif ($sVal === 'R') $nR++;
                    }
                    $sFinal = ($nA + $nR === 0) ? '' : ($nA >= $nR ? 'A' : 'R');
                } else {
                    $nTotalNat = 0;
                    $nTotalNorm = 0;
                    $bCompleto = false;
                    $bMixto = false;
                    $nPrimeraEscala = 0;

                    foreach ($aContenidoCursos as $oCont) {
                        $sVal = $aNotasMap[$nEstId][$oCont->id] ?? '';
                        if (trim($sVal) === '') continue;

                        $nNota = (float)$sVal;
                        $nEscala = (int)$oCont->indicador_curso->escala_nota;
                        $nPonderacion = (int)$oCont->ponderacion;
                        $nMaxNota = ($nEscala == 2 || $nEscala == 3) ? $nPonderacion : 20;

                        $bCompleto = true;

                        if ($nPrimeraEscala === 0) {
                            $nPrimeraEscala = $nEscala;
                        } elseif ($nEscala !== $nPrimeraEscala) {
                            $bMixto = true;
                        }

                        $nTotalNat += $nNota * ($nPonderacion / 100);

                        $nNorm = $this->_normalizar($nNota, $nEscala, $nMaxNota);
                        $nTotalNorm += $nNorm * ($nPonderacion / 100);
                    }

                    if (!$bCompleto) continue;

                    if (!$bMixto && $nPrimeraEscala === 1) {
                        $sFinal = (string)round($nTotalNat);
                    } else {
                        $sFinal = (string)$this->_aEscala20($nTotalNorm);
                    }
                }

                if ($sFinal !== '') {
                    $oEc->calificacion = $sFinal;
                    $oEc->responsable = $sResponsable;
                    if ($ecTable->save($oEc)) {
                        $nActualizados++;
                    }
                }
            }

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

    private function _normalizar($nNota, $nEscala, $nPorcentaje = 100)
    {
        switch ((int)$nEscala) {
            case 1: return ($nNota / 20) * 100;
            case 2: return ($nNota / $nPorcentaje) * 100;
            case 3: return $nNota;
            default: return 0;
        }
    }

    private function _aEscala20($nValor)
    {
        $nValor = max(1, min(100, round($nValor)));
        if ($nValor <= 5)  return 1;
        if ($nValor <= 10) return 2;
        if ($nValor <= 15) return 3;
        if ($nValor <= 20) return 4;
        if ($nValor <= 25) return 5;
        if ($nValor <= 30) return 6;
        if ($nValor <= 35) return 7;
        if ($nValor <= 40) return 8;
        if ($nValor <= 45) return 9;
        if ($nValor <= 50) return 10;
        if ($nValor <= 55) return 11;
        if ($nValor <= 60) return 12;
        if ($nValor <= 65) return 13;
        if ($nValor <= 70) return 14;
        if ($nValor <= 75) return 15;
        if ($nValor <= 80) return 16;
        if ($nValor <= 85) return 17;
        if ($nValor <= 90) return 18;
        if ($nValor <= 95) return 19;
        return 20;
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
