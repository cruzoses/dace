<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Auditoria Entity
 *
 * @property int $id
 * @property int $usuario_id
 * @property \Cake\I18n\FrozenTime $fecha
 * @property string $evento
 * @property string $detalle
 * @property string $host
 * @property string $agente
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Usuario $usuario
 */
class Auditoria extends Entity
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
        'usuario_id' => true,
        'fecha' => true,
        'evento' => true,
        'detalle' => true,
        'host' => true,
        'agente' => true,
        'created' => true,
        'modified' => true,
        'usuario' => true,
    ];
}
