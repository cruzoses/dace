<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Curso Entity
 *
 * @property int $id
 * @property int $sede_id
 * @property int $periodo_id
 * @property int $carrera_id
 * @property string $programas
 * @property int $trayecto_id
 * @property int $asignatura_id
 * @property string $profesores
 * @property int $docente_id
 * @property string $seccion
 * @property int $cupos
 * @property int|null $aula_id
 * @property string $horario
 * @property bool $activo
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Sede $sede
 * @property \App\Model\Entity\Periodo $periodo
 * @property \App\Model\Entity\Carrera $carrera
 * @property \App\Model\Entity\Trayecto $trayecto
 * @property \App\Model\Entity\Asignatura $asignatura
 * @property \App\Model\Entity\Docente $docente
 * @property \App\Model\Entity\Aula $aula
 * @property \App\Model\Entity\EstudianteCurso[] $estudiante_cursos
 * @property \App\Model\Entity\IndicadorCurso[] $indicador_cursos
*/
class Curso extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
    */
    protected $_accessible = [
        'sede_id' => true,
        'periodo_id' => true,
        'carrera_id' => true,
        'programas' => true,
        'trayecto_id' => true,
        'asignatura_id' => true,
        'profesores' => true,
        'docente_id' => true,
        'seccion' => true,
        'cupos' => true,
        'aula_id' => true,
        'horario' => true,
        'activo' => true,
        'created' => true,
        'modified' => true,
        'sede' => true,
        'periodo' => true,
        'carrera' => true,
        'trayecto' => true,
        'asignatura' => true,
        'docente' => true,
        'aula' => true,
        'estudiante_cursos' => true,
        'indicador_cursos' => true,
    ];

    protected function _setProfesores($value)
    {
        return is_array($value) ? implode(' ', $value) : $value;
    }

    protected function _setHorario($value)
    {
        return is_array($value) ? implode(' ', $value) : $value;
    }

    protected function _setProgramas($value)
    {
        return is_array($value) ? implode(' ', $value) : $value;
    }
}
