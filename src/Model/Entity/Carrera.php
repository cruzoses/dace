<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Carrera Entity
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property int $mension_carrera_id
 * @property string $titulo_otorgado
 * @property bool $activa
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\MensionCarrera $mension_carrera
 * @property \App\Model\Entity\Curso[] $cursos
 * @property \App\Model\Entity\Programa[] $programas
 * @property \App\Model\Entity\Sede[] $sedes
*/
class Carrera extends Entity
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
        'mension_carrera_id' => true,
        'titulo_otorgado' => true,
        'activa' => true,
        'created' => true,
        'modified' => true,
        'mension_carrera' => true,
        'cursos' => true,
        'programas' => true,
        'sedes' => true,
    ];
    protected $_virtual = [
        'codename',
    ];

    protected function _getCodename()
    {
        return $this->codigo .' : '. $this->nombre;
    }
}
