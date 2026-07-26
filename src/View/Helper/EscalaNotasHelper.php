<?php
namespace App\View\Helper;

use Cake\View\Helper;

/**
 * EscalaNotas Helper
 *
 * Convierte calificaciones entre escalas (1-20, 1-100, etc.)
 */
class EscalaNotasHelper extends Helper
{
    /**
     * Normaliza una calificacion a escala 0-100 segun la escala de origen.
     *
     * @param float $nNota Calificacion en escala original
     * @param int $nEscala 1=1-20, 2=1-porcentaje, 3=1-100
     * @param int $nPorcentaje Maximo permitido (solo aplica para escala 2)
     * @return float Calificacion normalizada 0-100
     */
    public function normalizar($nNota, $nEscala, $nPorcentaje = 100)
    {
        switch ((int)$nEscala) {
            case 1:
                return ($nNota / 20) * 100;
            case 2:
                return ($nNota / $nPorcentaje) * 100;
            case 3:
                return $nNota;
            default:
                return 0;
        }
    }

    /**
     * Convierte un valor 0-100 a escala 1-20.
     * Misma logica que AppController::escaladenotas().
     *
     * @param float $nValor Valor entre 0 y 100
     * @return int Calificacion en escala 1-20
     */
    public function aEscala20($nValor)
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
     * Calcula la nota final promediando un arreglo de calificaciones.
     *
     * @param array $aCalificaciones Array de ['nota', 'ponderacion', 'escala', 'porcentaje']
     * @return array ['total' => float 0-100, 'final' => int 1-20]
     */
    public function calcular(array $aCalificaciones)
    {
        $nTotal = 0;
        foreach ($aCalificaciones as $aCal) {
            $nNota = (float)$aCal['nota'];
            $nPonderacion = (float)$aCal['ponderacion'];
            $nEscala = (int)$aCal['escala'];
            $nPorcentaje = (int)($aCal['porcentaje'] ?? 100);

            $nNormalizado = $this->normalizar($nNota, $nEscala, $nPorcentaje);
            $nTotal += $nNormalizado * ($nPonderacion / 100);
        }

        return [
            'total' => round($nTotal, 2),
            'final' => $this->aEscala20($nTotal),
        ];
    }
}
