<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SituacionEstudiante Entity
 *
 * @property int $id
 * @property int $estudiante_id
 * @property int $programa_id
 * @property int $asignatura_id
 * @property int $periodo_id
 * @property string|null $seccion
 * @property string|null $calificacion
 * @property string|null $responsable
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Estudiante $estudiante
 * @property \App\Model\Entity\Programa $programa
 * @property \App\Model\Entity\Asignatura $asignatura
 * @property \App\Model\Entity\Periodo $periodo
 */
class SituacionEstudiante extends Entity
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
        'estudiante_id' => true,
        'programa_id' => true,
        'asignatura_id' => true,
        'periodo_id' => true,
        'seccion' => true,
        'calificacion' => true,
        'responsable' => true,
        'created' => true,
        'modified' => true,
        'estudiante' => true,
        'programa' => true,
        'asignatura' => true,
        'periodo' => true,
    ];
}
