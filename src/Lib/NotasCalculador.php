<?php
namespace App\Lib;

/**
 * NotasCalculador
 *
 * Cálculo normalizado de totales y nota final por curso.
 *
 * Agrupa las evaluaciones por indicador y aplica el porcentaje del indicador
 * para acotar la contribución de cada proceso a su peso real en la asignatura.
 * De esta forma, aunque el plan de evaluación de un indicador sume más que su
 * porcentaje (planes legados mal formados), la nota final nunca supera la escala.
 */
class NotasCalculador
{
    /**
     * Convierte un valor 0-100 a escala 1-20.
     *
     * @param float $nValor Valor entre 0 y 100
     * @return int Calificación en escala 1-20
     */
    public static function aEscala20($nValor)
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

    /**
     * Normaliza una calificación a escala 0-100 según su máximo permitido.
     *
     * @param float $nNota Calificación en escala original
     * @param int $nEscala 1 = 1-20; 2 y 3 = 1-porcentaje (máx = ponderación)
     * @param float $nMax Máximo permitido para la evaluación (20 o ponderación)
     * @return float Calificación normalizada 0-100
     */
    public static function normalizarNota($nNota, $nEscala, $nMax)
    {
        $nMax = (float)$nMax;
        if ($nMax <= 0) {
            return 0;
        }
        if ((int)$nEscala === 1) {
            return ($nNota / 20) * 100;
        }
        return ($nNota / $nMax) * 100;
    }

    /**
     * Calcula el total y la nota final por estudiante para un curso.
     *
     * Si la sumatoria de ponderaciones del plan de evaluación (ContenidoCursos)
     * es mayor a 100 (plan sobrecargado/malformado), cada indicador aporta a lo
     * sumo su porcentaje: contribución = score_i * P_i / 100, y el total se
     * normaliza a 0-100 dividiendo entre la suma de porcentajes de los
     * indicadores con al menos una evaluación calificada.
     *
     * Si la sumatoria es <= 100 (plan bien formado) se respetan los contenidos
     * y sus escalas: total = Σ nota * (ponderacion / 100); el final es
     * round(total) cuando todas las evaluaciones son escala 1 (1-20), o
     * aEscala20(Σ normalizada * (ponderacion / 100)) en escalas 2/3 o mixto.
     *
     * @param int $nTipoCalificacion 0 = cuantitativa, 1 = cualitativa (A/R)
     * @param array $aContenidos Evaluaciones con ->id, ->ponderacion, ->indicador_curso_id
     *                           y ->indicador_curso (->escala_nota, ->porcentaje)
     * @param array $aNotasMap [$estudianteId][$contenidoId] => calificacion
     * @return array [$estudianteId => ['total' => float, 'final' => string, 'porIndicador' => array]]
     */
    public static function calcularTotales($nTipoCalificacion, array $aContenidos, array $aNotasMap)
    {
        $nSumaPlan = 0;
        foreach ($aContenidos as $oCont) {
            $nSumaPlan += (float)$oCont->ponderacion;
        }
        $bSobreCargado = $nSumaPlan > 100;

        $aPorIndicador = [];
        $aIndPorcentaje = [];
        foreach ($aContenidos as $oCont) {
            $nIndId = (int)$oCont->indicador_curso_id;
            $aPorIndicador[$nIndId][] = $oCont;
            if (!isset($aIndPorcentaje[$nIndId]) && isset($oCont->indicador_curso) && $oCont->indicador_curso) {
                $aIndPorcentaje[$nIndId] = (float)$oCont->indicador_curso->porcentaje;
            }
        }

        if (empty($aPorIndicador)) {
            return [];
        }

        $aResultado = [];
        foreach ($aNotasMap as $nEstId => $aNotasEstudiante) {
            if ((int)$nTipoCalificacion === 1) {
                $nA = 0;
                $nR = 0;
                foreach ($aNotasEstudiante as $sVal) {
                    $sVal = strtoupper(trim((string)$sVal));
                    if ($sVal === 'A') {
                        $nA++;
                    } elseif ($sVal === 'R') {
                        $nR++;
                    }
                }
                if ($nA + $nR === 0) {
                    continue;
                }
                $aResultado[$nEstId] = [
                    'total' => 0,
                    'final' => ($nA >= $nR) ? 'A' : 'R',
                    'porIndicador' => [],
                ];
                continue;
            }

            if (!$bSobreCargado) {
                $nTotalNat = 0;
                $nTotalNorm = 0;
                $bCompleto = false;
                $bMixto = false;
                $nPrimeraEscala = 0;

                foreach ($aContenidos as $oCont) {
                    $sVal = isset($aNotasEstudiante[$oCont->id]) ? $aNotasEstudiante[$oCont->id] : '';
                    if (trim($sVal) === '') {
                        continue;
                    }
                    $nNota = (float)$sVal;
                    if (!is_numeric($sVal)) {
                        continue;
                    }
                    $nEscala = isset($oCont->indicador_curso) && $oCont->indicador_curso
                        ? (int)$oCont->indicador_curso->escala_nota
                        : 1;
                    $nPond = (float)$oCont->ponderacion;
                    $nMax = ($nEscala === 2 || $nEscala === 3) ? $nPond : 20;

                    $bCompleto = true;

                    if ($nPrimeraEscala === 0) {
                        $nPrimeraEscala = $nEscala;
                    } elseif ($nEscala !== $nPrimeraEscala) {
                        $bMixto = true;
                    }

                    $nTotalNat += $nNota * ($nPond / 100);
                    $nTotalNorm += self::normalizarNota($nNota, $nEscala, $nMax) * ($nPond / 100);
                }

                if (!$bCompleto) {
                    continue;
                }

                if (!$bMixto && $nPrimeraEscala === 1) {
                    $aResultado[$nEstId] = [
                        'total' => round($nTotalNat, 2),
                        'final' => (string)round($nTotalNat),
                        'porIndicador' => [],
                    ];
                } else {
                    $aResultado[$nEstId] = [
                        'total' => round($nTotalNorm, 2),
                        'final' => (string)self::aEscala20($nTotalNorm),
                        'porIndicador' => [],
                    ];
                }

                continue;
            }

            $nSumaPesos = 0;
            $nTotal = 0;
            $aDetalle = [];

            foreach ($aPorIndicador as $nIndId => $aEvals) {
                $nScoreNumerador = 0;
                $nPesoSum = 0;
                $bGradado = false;

                foreach ($aEvals as $oCont) {
                    $sVal = isset($aNotasEstudiante[$oCont->id]) ? $aNotasEstudiante[$oCont->id] : '';
                    if (trim($sVal) === '') {
                        continue;
                    }
                    $nNota = (float)$sVal;
                    if (!is_numeric($sVal)) {
                        continue;
                    }
                    $nEscala = isset($oCont->indicador_curso) && $oCont->indicador_curso
                        ? (int)$oCont->indicador_curso->escala_nota
                        : 1;
                    $nPond = (float)$oCont->ponderacion;
                    $nMax = ($nEscala === 2 || $nEscala === 3) ? $nPond : 20;

                    $nNorm = self::normalizarNota($nNota, $nEscala, $nMax);
                    $nScoreNumerador += $nNorm * $nPond;
                    $nPesoSum += $nPond;
                    $bGradado = true;
                }

                if (!$bGradado || $nPesoSum <= 0) {
                    continue;
                }

                $nScoreInd = $nScoreNumerador / $nPesoSum;
                $nPorcentajeInd = isset($aIndPorcentaje[$nIndId]) ? $aIndPorcentaje[$nIndId] : 0;
                $nContribucion = $nScoreInd * ($nPorcentajeInd / 100);

                $nSumaPesos += $nPorcentajeInd;
                $nTotal += $nContribucion;
                $aDetalle[$nIndId] = [
                    'score' => round($nScoreInd, 2),
                    'porcentaje' => $nPorcentajeInd,
                    'contribucion' => round($nContribucion, 2),
                ];
            }

            if ($nSumaPesos <= 0) {
                continue;
            }

            $nTotalNormalizado = ($nTotal / $nSumaPesos) * 100;

            $aResultado[$nEstId] = [
                'total' => round($nTotalNormalizado, 2),
                'final' => (string)self::aEscala20($nTotalNormalizado),
                'porIndicador' => $aDetalle,
            ];
        }

        return $aResultado;
    }
}
