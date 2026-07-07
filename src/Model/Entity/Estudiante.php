<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Estudiante Entity
 *
 * @property int $id
 * @property string $origen
 * @property int $cedula
 * @property string $nombres
 * @property string $apellidos
 * @property \Cake\I18n\FrozenDate $fecha_nacimiento
 * @property string $sexo
 * @property string $estado_civil
 * @property bool $discapacitado
 * @property bool $etnia
 * @property string $direccion
 * @property string|null $telefonos
 * @property string $email
 * @property string|null $lugar_nacimiento
 * @property int|null $pais_id
 * @property int|null $estado_id
 * @property int $municipio_id
 * @property int|null $parroquia_id
 * @property bool|null $asignado
 * @property string|null $codigo_opsu
 * @property \Cake\I18n\FrozenDate|null $fecha_notas
 * @property string|null $codigo_notas
 * @property \Cake\I18n\FrozenDate|null $fecha_titulo
 * @property string|null $codigo_titulo
 * @property string|null $acta_nacimiento
 * @property int $periodo
 * @property int $carrera
 * @property int $sede
 * @property string $expediente
 * @property string|null $token
 * @property int|null $usuario_id
 * @property bool $activo
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Paise $paise
 * @property \App\Model\Entity\Estado $estado
 * @property \App\Model\Entity\Municipio $municipio
 * @property \App\Model\Entity\Parroquia $parroquia
 * @property \App\Model\Entity\Usuario $usuario
 * @property \App\Model\Entity\EstudianteCurso[] $estudiante_cursos
 * @property \App\Model\Entity\EstudiantePrograma[] $estudiante_programas
 * @property \App\Model\Entity\Graduando[] $graduandos
 * @property \App\Model\Entity\Historico[] $historicos
 * @property \App\Model\Entity\NotasCurso[] $notas_cursos
 * @property \App\Model\Entity\SituacionEstudiante[] $situacion_estudiantes
 */
class Estudiante extends Entity
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
        'origen' => true,
        'cedula' => true,
        'nombres' => true,
        'apellidos' => true,
        'fecha_nacimiento' => true,
        'sexo' => true,
        'estado_civil' => true,
        'discapacitado' => true,
        'etnia' => true,
        'direccion' => true,
        'telefonos' => true,
        'email' => true,
        'lugar_nacimiento' => true,
        'pais_id' => true,
        'estado_id' => true,
        'municipio_id' => true,
        'parroquia_id' => true,
        'asignado' => true,
        'codigo_opsu' => true,
        'fecha_notas' => true,
        'codigo_notas' => true,
        'fecha_titulo' => true,
        'codigo_titulo' => true,
        'acta_nacimiento' => true,
        'periodo' => true,
        'carrera' => true,
        'sede' => true,
        'expediente' => true,
        'token' => true,
        'usuario_id' => true,
        'activo' => true,
        'created' => true,
        'modified' => true,
        'paise' => true,
        'estado' => true,
        'municipio' => true,
        'parroquia' => true,
        'usuario' => true,
        'estudiante_cursos' => true,
        'estudiante_programas' => true,
        'graduandos' => true,
        'historicos' => true,
        'notas_cursos' => true,
        'situacion_estudiantes' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'token',
    ];

    protected $_virtual = [
        'full_name',
    ];

    protected function _getFullName()
    {
        return $this->nombres .' '. $this->apellidos;
    }

    protected function _getExpedienteFormateado()
    {
        $exp = $this->expediente;
        if (!$exp /*|| strlen($exp) < 12*/) {
            return $exp ?? '';
        } elseif( strlen($exp) == 9){
            return substr($exp, 0, 3) . '.' . substr($exp, 3, 5) . '.' . substr($exp, 8);
        } elseif( strlen($exp) == 12){
            return substr($exp, 0, 3) . '.' . substr($exp, 3, 7) . '.' . substr($exp, -1);
        }
        return substr($exp, 0, 3) . '.' . substr($exp, 3, 4) . '.' . substr($exp, 7);
    }
}
