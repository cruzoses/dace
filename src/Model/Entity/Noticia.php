<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Noticia Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenDate $fecha
 * @property string $titulo
 * @property string $contenido
 * @property int|null $usuario_id
 * @property bool $activa
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Usuario $usuario
 */
class Noticia extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'fecha' => true,
        'titulo' => true,
        'contenido' => true,
        'usuario_id' => true,
        'activa' => true,
        'created' => true,
        'modified' => true,
        'usuario' => true,
    ];
}
