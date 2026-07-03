<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Programa Entity
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property int $carrera_id
 * @property int $subsistema_id
 * @property string $nota_minima
 * @property int $creditos
 * @property bool $activo
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Carrera $carrera
 * @property \App\Model\Entity\Subsistema $subsistema
 * @property \App\Model\Entity\Curso[] $cursos
 * @property \App\Model\Entity\EstudiantePrograma[] $estudiante_programas
 * @property \App\Model\Entity\Malla[] $mallas
 */
class Programa extends Entity
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
        'carrera_id' => true,
        'subsistema_id' => true,
        'nota_minima' => true,
        'creditos' => true,
        'activo' => true,
        'created' => true,
        'modified' => true,
        'carrera' => true,
        'subsistema' => true,
        'cursos' => true,
        'estudiante_programas' => true,
        'mallas' => true,
    ];
}
