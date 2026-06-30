<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

use Cake\Event\Event;
use ArrayObject;
use Cake\I18n\Time;


/**
 * Empleados Model
 *
 * @property \App\Model\Table\UsuariosTable&\Cake\ORM\Association\BelongsTo $Usuarios
 *
 * @method \App\Model\Entity\Empleado get($primaryKey, $options = [])
 * @method \App\Model\Entity\Empleado newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Empleado[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Empleado|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Empleado saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Empleado patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Empleado[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Empleado findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EmpleadosTable extends AppTable
{
    protected $searchFields = [
        'cedula' => ['type' => 'exact', 'label' => 'Cédula', 'class' => 'form-control isNumeric'],
        'nombres' => ['type' => 'text', 'label' => 'Nombres', 'class' => 'form-control isUpper'],
        'apellidos' => ['type' => 'text', 'label' => 'Apellidos', 'class' => 'form-control isUpper'],
        'email' => ['type' => 'text', 'label' => 'Email', 'class' => 'form-control isLower'],
        'sexo' => ['type' => 'select', 'label' => 'Sexo', 'class' => 'form-control select2', 'data-width' => '100%', 'options' => ['M' => 'Masculino', 'F' => 'Femenino'], 'empty' => true],
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

        $this->setTable('empleados');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Usuarios', [
            'foreignKey' => 'usuario_id',
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
        $rules->add($rules->isUnique(['cedula'], 'Ya existe un usuario con este número de cédula.'));
        $rules->add($rules->isUnique(['email'], 'Ya existe un usuario con este correo electrónico.'));
        $rules->add($rules->existsIn(['usuario_id'], 'Usuarios'));

        return $rules;
    }

    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        // Verifica si el campo de fecha existe en el request
        if ( isset( $data['fecha_nacimiento'] ) ) 
        {
            $fechaOriginal = str_replace('/', '-',$data['fecha_nacimiento']);

            // Si la fecha no está vacía, la convertimos
            if (!empty($fechaOriginal)) 
            {
                // Convierte el formato dd-mm-yyyy a yyyy-mm-dd
                $fechaFormateada = Time::createFromFormat('d-m-Y', $fechaOriginal);
                
                // Asigna el valor corregido para que CakePHP lo guarde correctamente
                $data['fecha_nacimiento'] = $fechaFormateada->format('Y-m-d');
            } 
        }
    }
}
