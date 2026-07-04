<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Malla Entity
 *
 * @property int $id
 * @property int $programa_id
 * @property int $trayecto_id
 * @property int $asignatura_id
 * @property string|null $nota_minima
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Programa $programa
 * @property \App\Model\Entity\Trayecto $trayecto
 * @property \App\Model\Entity\Asignatura $asignatura
 */
class Malla extends Entity
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
        'carrera_id' => true,
        'programa_id' => true,
        'trayecto_id' => true,
        'asignatura_id' => true,
        'nota_minima' => true,
        'created' => true,
        'modified' => true,
        'programa' => true,
        'trayecto' => true,
        'asignatura' => true,
    ];
}
