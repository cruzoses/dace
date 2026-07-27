<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ContenidoCurso Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenDate $fecha
 * @property string $descripcion
 * @property string $detalle
 * @property int $ponderacion
 * @property int $indicador_curso_id
 * @property bool $activo
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\IndicadorCurso $indicador_curso
 * @property \App\Model\Entity\CursoNota[] $curso_notas
 */
class ContenidoCurso extends Entity
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
        'fecha' => true,
        'descripcion' => true,
        'detalle' => true,
        'ponderacion' => true,
        'indicador_curso_id' => true,
        'activo' => true,
        'created' => true,
        'modified' => true,
        'indicador_curso' => true,
        'curso_notas' => true,
    ];
}
