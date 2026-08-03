<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CursoNota Entity
 *
 * @property int $id
 * @property int $contenido_curso_id
 * @property int $estudiante_id
 * @property string|null $calificacion
 * @property string $responsable
 * @property bool $procesada
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\ContenidoCurso $contenido_curso
 * @property \App\Model\Entity\Estudiante $estudiante
 */
class CursoNota extends Entity
{
    protected $_accessible = [
        'contenido_curso_id' => true,
        'estudiante_id' => true,
        'calificacion' => true,
        'responsable' => true,
        'procesada' => true,
        'created' => true,
        'modified' => true,
        'contenido_curso' => true,
        'estudiante' => true,
    ];
}
