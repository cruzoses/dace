<?php
namespace App\Lib;

use App\Lib\NotasCalculador;
use Cake\ORM\TableRegistry;

/**
 * CierreActas
 *
 * Servicio para cerrar actas de notas de forma masiva. Replica la lógica de
 * CursoNotasController::cerrarActa pero es reutilizable desde un Shell (CLI)
 * o desde una UI (CursosController::cierreActas).
 *
 * Reglas de validación respetadas (mismas que la grilla / cerrarActa):
 *  - Solo cursos con cerrado = 0 (a menos que se pida recalcular).
 *  - Solo periodos con califica = 1.
 *  - Estudiantes activos (estudiante_cursos.activo = 1).
 *  - Cálculo de la nota final con App\Lib\NotasCalculador::calcularTotales
 *    (escala 1-20, escalas 2/3 = 1..ponderación, plan sobrecargado > 100,
 *    cualitativa A/R).
 *  - estudiante_cursos.responsable = name del docente del curso.
 */
class CierreActas
{
    /**
     * Cierra el acta de un curso.
     *
     * @param int $nCursoId
     * @param bool $bDryRun Solo reporta, no guarda.
     * @param bool $bRecalcular Permite procesar cursos ya cerrados.
     * @param string|null $sResponsableOverride Valor de responsable a usar (si es null usa el docente).
     * @return array
     */
    public static function cerrarCurso($nCursoId, $bDryRun = false, $bRecalcular = false, $sResponsableOverride = null)
    {
        $cursosTable = TableRegistry::getTableLocator()->get('Cursos');
        $oCurso = $cursosTable->find()
            ->where(['Cursos.id' => $nCursoId])
            ->contain(['Asignaturas', 'Periodos', 'Docentes'])
            ->first();

        $aBase = [
            'curso_id' => $nCursoId,
            'asignatura' => '',
            'seccion' => '',
            'docente' => '',
            'periodo' => '',
            'estado' => '',
            'motivo' => '',
            'actualizados' => 0,
            'con_total' => 0,
            'sin_total' => 0,
            'estudiantes' => 0,
            'evaluaciones' => 0,
            'detalle' => [],
        ];

        if (!$oCurso) {
            return array_merge($aBase, ['estado' => 'NO_EXISTE', 'motivo' => 'Curso no encontrado.']);
        }

        $aBase['asignatura'] = $oCurso->has('asignatura') ? $oCurso->asignatura->codigo . ' - ' . $oCurso->asignatura->nombre : '';
        $aBase['seccion'] = $oCurso->seccion;
        $aBase['docente'] = $oCurso->has('docente') && $oCurso->docente ? $oCurso->docente->name : '';
        $aBase['periodo'] = $oCurso->has('periodo') ? $oCurso->periodo->codigo : '';

        if ((int)$oCurso->cerrado === 1 && !$bRecalcular) {
            return array_merge($aBase, ['estado' => 'YA_CERRADO', 'motivo' => 'El curso ya está cerrado.']);
        }

        if ($oCurso->has('periodo') && (int)$oCurso->periodo->califica !== 1) {
            return array_merge($aBase, ['estado' => 'NO_CALIFICA', 'motivo' => 'El periodo del curso no califica.']);
        }

        $sResponsable = $sResponsableOverride ?: $aBase['docente'];
        if (empty($sResponsable)) {
            $sResponsable = 'SIN DOCENTE';
        }

        $ecTable = TableRegistry::getTableLocator()->get('EstudianteCursos');
        $aEstudiantes = $ecTable->find()
            ->where([
                'EstudianteCursos.curso_id' => $nCursoId,
                'EstudianteCursos.activo' => 1,
            ])
            ->toArray();

        $aBase['estudiantes'] = count($aEstudiantes);

        if (empty($aEstudiantes)) {
            return array_merge($aBase, ['estado' => 'SIN_ESTUDIANTES', 'motivo' => 'No hay estudiantes inscritos activos.']);
        }

        $indicadorCursosTable = TableRegistry::getTableLocator()->get('IndicadorCursos');
        $aIndicadorCursoIds = $indicadorCursosTable->find()
            ->where(['curso_id' => $nCursoId])
            ->extract('id')
            ->toArray();

        if (empty($aIndicadorCursoIds)) {
            return array_merge($aBase, ['estado' => 'SIN_INDICADORES', 'motivo' => 'El curso no tiene indicadores de evaluación.']);
        }

        $cursoNotasTable = TableRegistry::getTableLocator()->get('CursoNotas');
        $aContenidos = $cursoNotasTable->ContenidoCursos->find()
            ->where(['indicador_curso_id IN' => $aIndicadorCursoIds])
            ->contain(['IndicadorCursos'])
            ->toArray();

        if (empty($aContenidos)) {
            return array_merge($aBase, ['estado' => 'SIN_PLAN', 'motivo' => 'El curso no tiene plan de evaluación.']);
        }

        $aBase['evaluaciones'] = count($aContenidos);

        $aNotas = $cursoNotasTable->find()
            ->where(['contenido_curso_id IN' => array_map(function ($o) {
                return $o->id;
            }, $aContenidos)])
            ->toArray();

        if (empty($aNotas)) {
            return array_merge($aBase, ['estado' => 'SIN_NOTAS', 'motivo' => 'El curso no tiene calificaciones cargadas.']);
        }

        $aNotasMap = [];
        foreach ($aNotas as $oNota) {
            $aNotasMap[(int)$oNota->estudiante_id][(int)$oNota->contenido_curso_id] = $oNota->calificacion;
        }

        $nTipoCalificacion = (int)$oCurso->asignatura->calificacion;
        $aTotales = NotasCalculador::calcularTotales($nTipoCalificacion, $aContenidos, $aNotasMap);

        $aResultado = $aBase;
        $aResultado['estado'] = 'OK';

        if ($bDryRun) {
            $aDetalle = [];
            $nConTotal = 0;
            $nSinTotal = 0;
            foreach ($aEstudiantes as $oEc) {
                $nEstId = (int)$oEc->estudiante_id;
                if (isset($aTotales[$nEstId])) {
                    $nConTotal++;
                    $aDetalle[] = [
                        'estudiante_id' => $nEstId,
                        'anterior' => $oEc->calificacion,
                        'final' => $aTotales[$nEstId]['final'],
                    ];
                } else {
                    $nSinTotal++;
                }
            }
            $aResultado['con_total'] = $nConTotal;
            $aResultado['sin_total'] = $nSinTotal;
            $aResultado['detalle'] = $aDetalle;

            return $aResultado;
        }

        $conn = $ecTable->getConnection();
        $conn->begin();

        try {
            $nConTotal = 0;
            $nSinTotal = 0;
            $nActualizados = 0;
            $aErrores = [];

            foreach ($aEstudiantes as $oEc) {
                $nEstId = (int)$oEc->estudiante_id;
                if (!isset($aTotales[$nEstId])) {
                    $nSinTotal++;
                    continue;
                }
                $nConTotal++;
                $oEc->calificacion = $aTotales[$nEstId]['final'];
                $oEc->responsable = $sResponsable;
                if ($ecTable->save($oEc)) {
                    $nActualizados++;
                } else {
                    $aErrores[] = "Estudiante #{$nEstId}: error al guardar.";
                }
            }

            $oCurso->cerrado = 1;
            if (!$cursosTable->save($oCurso)) {
                $aErrores[] = 'Curso #' . $nCursoId . ': error al marcar como cerrado.';
            }

            $conn->commit();

            $aResultado['con_total'] = $nConTotal;
            $aResultado['sin_total'] = $nSinTotal;
            $aResultado['actualizados'] = $nActualizados;
            if (!empty($aErrores)) {
                $aResultado['estado'] = 'PARCIAL';
                $aResultado['motivo'] = implode(' | ', $aErrores);
            }

            return $aResultado;
        } catch (\Exception $e) {
            $conn->rollback();

            return array_merge($aBase, [
                'estado' => 'ERROR',
                'motivo' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Cierra el acta de todos los cursos de un periodo.
     *
     * @param int $nPeriodoId
     * @param bool $bDryRun Solo reporta, no guarda.
     * @param bool $bRecalcular Permite procesar cursos ya cerrados.
     * @param string|null $sResponsableOverride
     * @param int|null $nDocenteId Si se indica, solo cierra los cursos de ese docente.
     * @return array ['periodo' => id, 'cursos' => array de resultados, 'resumen' => [...]]
     */
    public static function cerrarPeriodo($nPeriodoId, $bDryRun = false, $bRecalcular = false, $sResponsableOverride = null, $nDocenteId = null)
    {
        $cursosTable = TableRegistry::getTableLocator()->get('Cursos');
        $oPeriodo = $cursosTable->Periodos->find()
            ->where(['Periodos.id' => $nPeriodoId])
            ->first();

        if (!$oPeriodo) {
            return [
                'periodo' => ['id' => $nPeriodoId, 'codigo' => '', 'nombre' => ''],
                'cursos' => [],
                'resumen' => [
                    'total_cursos' => 0,
                    'cerrados' => 0,
                    'ya_cerrados' => 0,
                    'saltados' => 0,
                    'actualizados' => 0,
                    'estudiantes' => 0,
                ],
            ];
        }

        $aCond = ['Cursos.periodo_id' => $nPeriodoId];
        if ($nDocenteId) {
            $aCond['Cursos.docente_id'] = $nDocenteId;
        }

        $aCursos = $cursosTable->find()
            ->where($aCond)
            ->order(['Cursos.id' => 'ASC'])
            ->extract('id')
            ->toArray();

        $aResultados = [];
        foreach ($aCursos as $nCursoId) {
            $aResultados[] = self::cerrarCurso($nCursoId, $bDryRun, $bRecalcular, $sResponsableOverride);
        }

        $nOk = 0;
        $nYaCerrado = 0;
        $nPendientes = 0;
        $nSaltados = 0;
        $nActualizados = 0;
        $nEstudiantes = 0;

        foreach ($aResultados as $r) {
            if ($r['estado'] === 'OK' || $r['estado'] === 'PARCIAL') {
                $nOk++;
            } elseif ($r['estado'] === 'YA_CERRADO') {
                $nYaCerrado++;
            } else {
                $nSaltados++;
            }
            if ($r['estado'] === 'OK') {
                $nPendientes++;
            }
            $nActualizados += (int)$r['actualizados'];
            $nEstudiantes += (int)$r['estudiantes'];
        }

        return [
            'periodo' => [
                'id' => $oPeriodo->id,
                'codigo' => $oPeriodo->codigo,
                'nombre' => $oPeriodo->nombre,
            ],
            'cursos' => $aResultados,
            'resumen' => [
                'total_cursos' => count($aResultados),
                'cerrados' => $nOk,
                'ya_cerrados' => $nYaCerrado,
                'saltados' => $nSaltados,
                'actualizados' => $nActualizados,
                'estudiantes' => $nEstudiantes,
            ],
        ];
    }
}
