<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Estudiantes Model
 *
 * @property \App\Model\Table\PaisesTable&\Cake\ORM\Association\BelongsTo $Paises
 * @property \App\Model\Table\EstadosTable&\Cake\ORM\Association\BelongsTo $Estados
 * @property \App\Model\Table\MunicipiosTable&\Cake\ORM\Association\BelongsTo $Municipios
 * @property \App\Model\Table\ParroquiasTable&\Cake\ORM\Association\BelongsTo $Parroquias
 * @property \App\Model\Table\UsuariosTable&\Cake\ORM\Association\BelongsTo $Usuarios
 * @property \App\Model\Table\EstudianteCursosTable&\Cake\ORM\Association\HasMany $EstudianteCursos
 * @property \App\Model\Table\EstudianteProgramasTable&\Cake\ORM\Association\HasMany $EstudianteProgramas
 * @property \App\Model\Table\GraduandosTable&\Cake\ORM\Association\HasMany $Graduandos
 * @property \App\Model\Table\HistoricosTable&\Cake\ORM\Association\HasMany $Historicos
 * @property \App\Model\Table\NotasCursosTable&\Cake\ORM\Association\HasMany $NotasCursos
 * @property \App\Model\Table\SituacionEstudiantesTable&\Cake\ORM\Association\HasMany $SituacionEstudiantes
 *
 * @method \App\Model\Entity\Estudiante get($primaryKey, $options = [])
 * @method \App\Model\Entity\Estudiante newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Estudiante[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Estudiante|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Estudiante saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Estudiante patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Estudiante[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Estudiante findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EstudiantesTable extends AppTable
{
    protected $searchFields = [
        'expediente' => ['type' => 'text', 'label' => 'Expediente', 'class' => 'form-control'],
        'cedula'     => ['type' => 'exact', 'label' => 'Cédula', 'class' => 'form-control isNumeric'],
        'apellidos'  => ['type' => 'text', 'label' => 'Apellidos', 'class' => 'form-control isUpper'],
        'nombres'    => ['type' => 'text', 'label' => 'Nombres', 'class' => 'form-control isUpper'],
        'id'         => ['type' => 'int', 'label' => 'ID', 'class' => 'form-control isNumeric'],
    ];
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('estudiantes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Paises', [
            'foreignKey' => 'pais_id',
        ]);
        $this->belongsTo('Estados', [
            'foreignKey' => 'estado_id',
        ]);
        $this->belongsTo('Municipios', [
            'foreignKey' => 'municipio_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Parroquias', [
            'foreignKey' => 'parroquia_id',
        ]);
        $this->belongsTo('Usuarios', [
            'foreignKey' => 'usuario_id',
        ]);
        $this->hasMany('EstudianteCursos', [
            'foreignKey' => 'estudiante_id',
        ]);
        $this->hasMany('EstudianteProgramas', [
            'foreignKey' => 'estudiante_id',
        ]);
        $this->hasMany('Graduandos', [
            'foreignKey' => 'estudiante_id',
        ]);
        $this->hasMany('Historicos', [
            'foreignKey' => 'estudiante_id',
        ]);
        $this->hasMany('NotasCursos', [
            'foreignKey' => 'estudiante_id',
        ]);
        $this->hasMany('SituacionEstudiantes', [
            'foreignKey' => 'estudiante_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('origen')
            ->maxLength('origen', 1)
            ->requirePresence('origen', 'create')
            ->notEmptyString('origen');

        $validator
            ->integer('cedula')
            ->requirePresence('cedula', 'create')
            ->notEmptyString('cedula');

        $validator
            ->scalar('nombres')
            ->maxLength('nombres', 50)
            ->requirePresence('nombres', 'create')
            ->notEmptyString('nombres');

        $validator
            ->scalar('apellidos')
            ->maxLength('apellidos', 50)
            ->requirePresence('apellidos', 'create')
            ->notEmptyString('apellidos');

        $validator
            ->date('fecha_nacimiento')
            ->requirePresence('fecha_nacimiento', 'create')
            ->notEmptyDate('fecha_nacimiento')
            ->add('fecha_nacimiento', 'notToday', [
                'rule' => function ($value, $context) {
                    $fecha = date('Y-m-d', strtotime(str_replace('/', '-', $value)));
                    return $fecha !== date('Y-m-d');
                },
                'message' => 'La fecha de nacimiento no puede ser igual a la fecha actual.',
            ]);

        $validator
            ->scalar('sexo')
            ->maxLength('sexo', 1)
            ->requirePresence('sexo', 'create')
            ->notEmptyString('sexo');

        $validator
            ->scalar('estado_civil')
            ->maxLength('estado_civil', 1)
            ->requirePresence('estado_civil', 'create')
            ->notEmptyString('estado_civil');

        $validator
            ->boolean('discapacitado')
            ->requirePresence('discapacitado', 'create')
            ->notEmptyString('discapacitado');

        $validator
            ->boolean('etnia')
            ->requirePresence('etnia', 'create')
            ->notEmptyString('etnia');

        $validator
            ->scalar('direccion')
            ->requirePresence('direccion', 'create')
            ->notEmptyString('direccion');

        $validator
            ->scalar('telefonos')
            ->maxLength('telefonos', 50)
            ->allowEmptyString('telefonos');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('lugar_nacimiento')
            ->allowEmptyString('lugar_nacimiento');

        $validator
            ->boolean('asignado')
            ->allowEmptyString('asignado');

        $validator
            ->scalar('codigo_opsu')
            ->maxLength('codigo_opsu', 20)
            ->allowEmptyString('codigo_opsu');

        $validator
            ->date('fecha_notas')
            ->allowEmptyDate('fecha_notas');

        $validator
            ->scalar('codigo_notas')
            ->maxLength('codigo_notas', 20)
            ->allowEmptyString('codigo_notas');

        $validator
            ->date('fecha_titulo')
            ->allowEmptyDate('fecha_titulo');

        $validator
            ->scalar('codigo_titulo')
            ->maxLength('codigo_titulo', 20)
            ->allowEmptyString('codigo_titulo');

        $validator
            ->scalar('acta_nacimiento')
            ->maxLength('acta_nacimiento', 20)
            ->allowEmptyString('acta_nacimiento');

        $validator
            ->integer('periodo')
            ->requirePresence('periodo', 'create')
            ->notEmptyString('periodo');

        $validator
            ->integer('carrera')
            ->requirePresence('carrera', 'create')
            ->notEmptyString('carrera');

        $validator
            ->integer('sede')
            ->requirePresence('sede', 'create')
            ->notEmptyString('sede');

        $validator
            ->scalar('expediente')
            ->maxLength('expediente', 20)
            ->allowEmptyString('expediente');

        $validator
            ->scalar('token')
            ->maxLength('token', 10)
            ->allowEmptyString('token');

        $validator
            ->boolean('activo')
            ->requirePresence('activo', 'create')
            ->notEmptyString('activo');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['cedula'],'Ya existe un estudiante con este número de cédula.'));
        $rules->add($rules->isUnique(['email'],'Ya existe un estudiante con este correo electrónico.'));
        $rules->add($rules->existsIn(['pais_id'], 'Paises'));
        $rules->add($rules->existsIn(['estado_id'], 'Estados'));
        $rules->add($rules->existsIn(['municipio_id'], 'Municipios'));
        $rules->add($rules->existsIn(['parroquia_id'], 'Parroquias'));
        $rules->add($rules->existsIn(['usuario_id'], 'Usuarios'));

        return $rules;
    }

    public function generarExpediente($fechaNacimiento, $periodoId)
    {
        $anioActual = date('Y');
        $prefijo = $anioActual[0] . $anioActual[2] . $anioActual[3];
        $anioNac = date('Y', strtotime(str_replace('/', '-', $fechaNacimiento)));
        $base = $prefijo . $anioNac;

        $last = $this->find()
            ->select(['seq' => 'COALESCE(MAX(CAST(RIGHT(expediente, 5) AS UNSIGNED)), 0) + 1'])
            ->where(['expediente LIKE' => $base . '%', 'periodo' => $periodoId])
            ->epilog('FOR UPDATE')
            ->first();

        return $base . str_pad($last->seq, 5, '0', STR_PAD_LEFT);
    }
}
