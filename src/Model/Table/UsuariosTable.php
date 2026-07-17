<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * Usuarios Model
 *
 * @property \App\Model\Table\AuditoriasTable&\Cake\ORM\Association\HasMany $Auditorias
 * @property \App\Model\Table\DocentesTable&\Cake\ORM\Association\HasMany $Docentes
 * @property \App\Model\Table\EmpleadosTable&\Cake\ORM\Association\HasMany $Empleados
 * @property \App\Model\Table\EstudiantesTable&\Cake\ORM\Association\HasMany $Estudiantes
 * @property \App\Model\Table\NoticiasTable&\Cake\ORM\Association\HasMany $Noticias
 * @property \App\Model\Table\RolsTable&\Cake\ORM\Association\BelongsToMany $Rols
 *
 * @method \App\Model\Entity\Usuario get($primaryKey, $options = [])
 * @method \App\Model\Entity\Usuario newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Usuario[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Usuario|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Usuario saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Usuario patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Usuario[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Usuario findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsuariosTable extends AppTable
{
    protected $searchFields = [
        'id' => ['type' => 'int', 'label' => 'No. de ID', 'class' => 'form-control isNumeric', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'cedula' => ['type' => 'exact', 'label' => 'Cédula', 'class' => 'form-control isNumeric', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'nombres' => ['type' => 'text', 'label' => 'Nombres', 'class' => 'form-control isUpper', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'apellidos' => ['type' => 'text', 'label' => 'Apellidos', 'class' => 'form-control isUpper', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'email' => ['type' => 'text', 'label' => 'Email', 'class' => 'form-control isLower', 'prepend' => '<i class="fa fa-asterisk"></i>'],
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

        $this->setTable('usuarios');
        $this->setDisplayField('alias');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Auditorias', [
            'foreignKey' => 'usuario_id',
        ]);
        $this->hasMany('Docentes', [
            'foreignKey' => 'usuario_id',
        ]);
        $this->hasMany('Empleados', [
            'foreignKey' => 'usuario_id',
        ]);
        $this->hasMany('Estudiantes', [
            'foreignKey' => 'usuario_id',
        ]);
        $this->hasMany('Noticias', [
            'foreignKey' => 'usuario_id',
        ]);
        $this->belongsToMany('Rols', [
            'foreignKey' => 'usuario_id',
            'targetForeignKey' => 'rol_id',
            'joinTable' => 'rols_usuarios',
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
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('telefonos')
            ->maxLength('telefonos', 50)
            ->allowEmptyString('telefonos');

        $validator
            ->scalar('username')
            ->maxLength('username', 50)
            ->requirePresence('username', 'create')
            ->notEmptyString('username');

        $validator
            ->scalar('password')
            ->maxLength('password', 128)
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        $validator
            ->scalar('twitter')
            ->maxLength('twitter', 40)
            ->allowEmptyString('twitter');

        $validator
            ->scalar('facebook')
            ->maxLength('facebook', 40)
            ->allowEmptyString('facebook');

        $validator
            ->scalar('instagram')
            ->maxLength('instagram', 40)
            ->allowEmptyString('instagram');

        $validator
            ->scalar('foto')
            ->maxLength('foto', 50)
            ->allowEmptyString('foto');

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
        $rules->add($rules->isUnique(['cedula'], 'Ya existe un usuario con este número de cédula.'));
        $rules->add($rules->isUnique(['email'], 'Ya existe un usuario con este correo electrónico.'));
        $rules->add($rules->isUnique(['username'], 'Ya existe un usuario con este identificador.'));

        return $rules;
    }

    public function recoverPassword($id)
    {
        $user = $this->get($id);
        return $user->password;
    }

    public function findAuth(Query $query, array $options)
    {
        $query
            //->select(['id', 'username', 'password'])
            ->where(['Usuarios.activo' => 1])
            ->contain(['Rols']);
        return $query;
    }

}
