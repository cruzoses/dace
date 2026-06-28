<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\TableRegistry;
use Cake\ORM\Entity;

/**
 * Usuario Entity
 *
 * @property int $id
 * @property int $cedula
 * @property string $nombres
 * @property string $apellidos
 * @property \Cake\I18n\FrozenDate $fecha_nacimiento
 * @property string $sexo
 * @property string $email
 * @property string|null $telefonos
 * @property string $username
 * @property string $password
 * @property bool $activo
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Auditoria[] $auditorias
 * @property \App\Model\Entity\Docente[] $docentes
 * @property \App\Model\Entity\Empleado[] $empleados
 * @property \App\Model\Entity\Estudiante[] $estudiantes
 * @property \App\Model\Entity\Noticia[] $noticias
 * @property \App\Model\Entity\Rol[] $rols
 */
class Usuario extends Entity
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
        'username' => true,
        'password' => true,
        'activo' => true,
        'created' => true,
        'modified' => true,
        'auditorias' => true,
        'docentes' => true,
        'empleados' => true,
        'estudiantes' => true,
        'noticias' => true,
        'rols' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
    */
    protected $_hidden = [
        'password',
    ];

    protected $_virtual = [
        'name',
        'alias'
    ];

    protected function _setPassword($password)
    {
        if ( strlen($password) > 0 ) 
        {
            return (new DefaultPasswordHasher)->hash($password);
        } else{
            $userId = $this->_properties['id'];
            $user = TableRegistry::get('Usuarios')->recoverPassword($userId);
            return $user;
        }
    }

    protected function _getName()
    {
        return $this->nombres .' '. $this->apellidos;
    }

    protected function _getAlias()
    {
        $aUno = explode(' ',$this->nombres);
        $aDos = explode(' ',$this->apellidos);
        return $aUno[0] .' '. $aDos[0];
    }
}
