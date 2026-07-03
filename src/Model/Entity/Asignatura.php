<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Asignatura Entity
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property int $horas_teoricas
 * @property int $horas_practicas
 * @property int $frecuencia
 * @property int $creditos
 * @property float $costo
 * @property string|null $requisitos
 * @property string|null $convalidacion
 * @property int|null $grupo_asignatura_id
 * @property bool $activa
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\GrupoAsignatura $grupo_asignatura
 * @property \App\Model\Entity\Historico[] $historicos
 * @property \App\Model\Entity\Malla[] $mallas
 */
class Asignatura extends Entity
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
        'horas_teoricas' => true,
        'horas_practicas' => true,
        'frecuencia' => true,
        'creditos' => true,
        'costo' => true,
        'requisitos' => true,
        'convalidacion' => true,
        'grupo_asignatura_id' => true,
        'activa' => true,
        'created' => true,
        'modified' => true,
        'grupo_asignatura' => true,
        'historicos' => true,
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
