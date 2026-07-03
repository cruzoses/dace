<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Periodo Entity
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property int $lapso
 * @property int $nota_minima
 * @property \Cake\I18n\FrozenDate $inicio
 * @property \Cake\I18n\FrozenDate $cierre
 * @property bool $califica
 * @property bool $activo
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Curso[] $cursos
 * @property \App\Model\Entity\Historico[] $historicos
 */
class Periodo extends Entity
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
        'lapso' => true,
        'nota_minima' => true,
        'inicio' => true,
        'cierre' => true,
        'califica' => true,
        'activo' => true,
        'created' => true,
        'modified' => true,
        'cursos' => true,
        'historicos' => true,
    ];

    protected $_virtual = [
        'codename',
    ];

    protected function _getCodename()
    {
        return $this->codigo .' : '. $this->nombre;
    }
}
