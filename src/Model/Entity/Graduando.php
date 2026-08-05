<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Graduando Entity
 *
 * @property int $id
 * @property int $institucion
 * @property int $acto_id
 * @property int $carrera_id
 * @property int $programa_id
 * @property int|null $estudiante_id
 * @property float $indice
 * @property int $solicitud
 * @property string|null $control
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Acto $acto
 * @property \App\Model\Entity\Carrera $carrera
 * @property \App\Model\Entity\Programa $programa
 * @property \App\Model\Entity\Estudiante $estudiante
 */
class Graduando extends Entity
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
        'institucion' => true,
        'acto_id' => true,
        'carrera_id' => true,
        'programa_id' => true,
        'estudiante_id' => true,
        'indice' => true,
        'solicitud' => true,
        'control' => true,
        'created' => true,
        'modified' => true,
        'acto' => true,
        'carrera' => true,
        'programa' => true,
        'estudiante' => true,
    ];
}
