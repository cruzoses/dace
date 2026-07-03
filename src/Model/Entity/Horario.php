<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Horario Entity
 *
 * @property int $id
 * @property int $sede_id
 * @property int $periodo_id
 * @property string $codigo
 * @property int $dia
 * @property int $turno
 * @property string $desde
 * @property string $hasta
 * @property bool $activo
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Sede $sede
 * @property \App\Model\Entity\Periodo $periodo
 */
class Horario extends Entity
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
        'codigo' => true,
        'dia' => true,
        'turno' => true,
        'desde' => true,
        'hasta' => true,
        'activo' => true,
        'created' => true,
        'modified' => true,
        'sede' => true,
        'periodo' => true,
    ];
}
