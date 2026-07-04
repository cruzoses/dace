<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * Docentes Model
 *
 * @property \App\Model\Table\DepartamentosTable&\Cake\ORM\Association\BelongsTo $Departamentos
 * @property \App\Model\Table\UsuariosTable&\Cake\ORM\Association\BelongsTo $Usuarios
 * @property \App\Model\Table\CursosTable&\Cake\ORM\Association\HasMany $Cursos
 *
 * @method \App\Model\Entity\Docente get($primaryKey, $options = [])
 * @method \App\Model\Entity\Docente newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Docente[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Docente|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Docente saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Docente patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Docente[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Docente findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DocentesTable extends AppTable
{
    protected $searchFields = [
        'id' => ['type' => 'int', 'label' => 'No. de Id', 'class' => 'isNumeric', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'cedula' => ['type' => 'int', 'label' => 'Cédula', 'class' => 'isNumeric', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'nombres' => ['type' => 'text', 'label' => 'Nombres', 'class' => 'isUpper', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'apellidos' => ['type' => 'text', 'label' => 'Apellidos', 'class' => 'isUpper', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'departamento_id' => [
            'type' => 'select',
            'label' => 'Departamento',
            'options' => [],
            'empty' => '-- Departamento --',
            'prepend' => '<i class="fa fa-asterisk"></i>'            
        ],
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

        $this->setTable('docentes');
        $this->setDisplayField('codename');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Departamentos', [
            'foreignKey' => 'departamento_id',
        ]);
        $this->belongsTo('Usuarios', [
            'foreignKey' => 'usuario_id',
        ]);
        $this->hasMany('Cursos', [
            'foreignKey' => 'docente_id',
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
            ->notEmptyDate('fecha_nacimiento');

        $validator
            ->scalar('sexo')
            ->maxLength('sexo', 1)
            ->requirePresence('sexo', 'create')
            ->notEmptyString('sexo');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('telefonos')
            ->maxLength('telefonos', 50)
            ->allowEmptyString('telefonos');

        $validator
            ->scalar('token')
            ->maxLength('token', 10)
            ->requirePresence('token', 'create')
            ->notEmptyString('token');

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
        $rules->add($rules->isUnique(['cedula'], 'Ya existe un docente con este número de cédula.'));
        $rules->add($rules->isUnique(['email'], 'Ya existe un docente con este correo electrónico.'));
        $rules->add($rules->existsIn(['departamento_id'], 'Departamentos'));
        $rules->add($rules->existsIn(['usuario_id'], 'Usuarios'));

        return $rules;
    }
}
