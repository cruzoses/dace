<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EstudiantePrograma Entity
 *
 * @property int $id
 * @property int $estudiante_id
 * @property int $programa_id
 * @property int $carrera_id
 * @property int $sede_id
 * @property \Cake\I18n\FrozenDate|null $fecha_egreso
 * @property string|null $cohorte
 * @property float|null $indice
 * @property bool $culminado
 * @property string|null $observacion
 * @property bool $activo
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Estudiante $estudiante
 * @property \App\Model\Entity\Programa $programa
 * @property \App\Model\Entity\Carrera $carrera
 * @property \App\Model\Entity\Sede $sede
*/
class EstudiantePrograma extends Entity
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
        'estudiante_id' => true,
        'programa_id' => true,
        'carrera_id' => true,
        'sede_id' => true,
        'fecha_egreso' => true,
        'cohorte' => true,
        'indice' => true,
        'culminado' => true,
        'observacion' => true,
        'activo' => true,
        'created' => true,
        'modified' => true,
        'estudiante' => true,
        'programa' => true,
        'carrera' => true,
        'sede' => true,
    ];
}
