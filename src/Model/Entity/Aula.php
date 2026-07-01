<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Aula Entity
 *
 * @property int $id
 * @property int $sede
 * @property string $codigo
 * @property string $nombre
 * @property int $capacidad
 * @property string $ubicacion
 * @property bool $condicion
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 */
class Aula extends Entity
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
        'sede' => true,
        'codigo' => true,
        'nombre' => true,
        'capacidad' => true,
        'ubicacion' => true,
        'condicion' => true,
        'created' => true,
        'modified' => true,
    ];
}
