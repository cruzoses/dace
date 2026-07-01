<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Docente Entity
 *
 * @property int $id
 * @property int $cedula
 * @property string $nombres
 * @property string $apellidos
 * @property \Cake\I18n\FrozenDate $fecha_nacimiento
 * @property string $sexo
 * @property string $email
 * @property string|null $telefonos
 * @property int|null $departamento_id
 * @property string $token
 * @property int|null $usuario_id
 * @property bool $activo
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Departamento $departamento
 * @property \App\Model\Entity\Usuario $usuario
 * @property \App\Model\Entity\Curso[] $cursos
 */
class Docente extends Entity
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
        'cedula' => true,
        'nombres' => true,
        'apellidos' => true,
        'fecha_nacimiento' => true,
        'sexo' => true,
        'email' => true,
        'telefonos' => true,
        'departamento_id' => true,
        'token' => true,
        'usuario_id' => true,
        'activo' => true,
        'created' => true,
        'modified' => true,
        'departamento' => true,
        'usuario' => true,
        'cursos' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'token',
    ];
}
