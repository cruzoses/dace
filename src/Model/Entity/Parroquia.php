<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Parroquia Entity
 *
 * @property int $id
 * @property int $municipio_id
 * @property string $nombre
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Municipio $municipio
 * @property \App\Model\Entity\Estudiante[] $estudiantes
 */
class Parroquia extends Entity
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
        'municipio_id' => true,
        'nombre' => true,
        'created' => true,
        'modified' => true,
        'municipio' => true,
        'estudiantes' => true,
    ];
}
