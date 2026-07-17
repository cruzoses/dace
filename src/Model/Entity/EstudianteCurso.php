<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EstudianteCurso Entity
 *
 * @property int $id
 * @property int $curso_id
 * @property int $estudiante_id
 * @property string|null $calificacion
 * @property string|null $recuperacion
 * @property string|null $definitiva
 * @property string $responsable
 * @property string|null $observacion
 * @property bool $activo
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Curso $curso
 * @property \App\Model\Entity\Estudiante $estudiante
 */
class EstudianteCurso extends Entity
{
    protected $_accessible = [
        'curso_id' => true,
        'estudiante_id' => true,
        'calificacion' => true,
        'recuperacion' => true,
        'definitiva' => true,
        'responsable' => true,
        'observacion' => true,
        'activo' => true,
        'created' => true,
        'modified' => true,
        'curso' => true,
        'estudiante' => true,
    ];
}
