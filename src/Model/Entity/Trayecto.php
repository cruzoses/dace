<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Trayecto Entity
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property bool $activo
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Curso[] $cursos
 * @property \App\Model\Entity\Malla[] $mallas
 */
class Trayecto extends Entity
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
        'codigo' => true,
        'nombre' => true,
        'activo' => true,
        'created' => true,
        'modified' => true,
        'cursos' => true,
        'mallas' => true,
    ];

    protected $_virtual = [
        'codename',
    ];

    protected function _getCodename()
    {
        return $this->codigo .' : '. $this->nombre;
    }
}
