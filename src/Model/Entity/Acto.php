<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Acto Entity
 *
 * @property int $id
 * @property string $nombre
 * @property string $cohorte
 * @property int $lapso
 * @property \Cake\I18n\FrozenDate $fecha
 * @property bool $activo
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Graduando[] $graduandos
 */
class Acto extends Entity
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
        'nombre' => true,
        'cohorte' => true,
        'lapso' => true,
        'fecha' => true,
        'activo' => true,
        'created' => true,
        'modified' => true,
        'graduandos' => true,
    ];
}
