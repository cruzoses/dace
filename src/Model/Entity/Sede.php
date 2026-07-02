<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Sede Entity
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property string $direccion
 * @property string|null $telefonos
 * @property string $responsable
 * @property bool $principal
 * @property bool $activa
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Curso[] $cursos
 * @property \App\Model\Entity\EstudiantePrograma[] $estudiante_programas
 * @property \App\Model\Entity\Carrera[] $carreras
 */
class Sede extends Entity
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
        'direccion' => true,
        'telefonos' => true,
        'responsable' => true,
        'principal' => true,
        'activa' => true,
        'created' => true,
        'modified' => true,
        'cursos' => true,
        'estudiante_programas' => true,
        'carreras' => true,
        'codename' => true,
    ];

    protected $_virtual = [
        'codename',
    ];

    protected function _getCodename()
    {
        return $this->codigo .' : '. $this->nombre;
    }

}
