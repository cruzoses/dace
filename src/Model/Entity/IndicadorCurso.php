<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * IndicadorCurso Entity
 *
 * @property int $id
 * @property int $curso_id
 * @property int $indicador_id
 * @property \Cake\I18n\FrozenDate $desde
 * @property \Cake\I18n\FrozenDate $hasta
 * @property int $escala_nota
 * @property int $porcentaje
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Curso $curso
 * @property \App\Model\Entity\Indicadore $indicadore
 * @property \App\Model\Entity\ContenidosCurso[] $contenidos_cursos
 */
class IndicadorCurso extends Entity
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
        'curso_id' => true,
        'indicador_id' => true,
        'desde' => true,
        'hasta' => true,
        'escala_nota' => true,
        'porcentaje' => true,
        'created' => true,
        'modified' => true,
        'curso' => true,
        'indicadore' => true,
        'contenidos_cursos' => true,
    ];
}
