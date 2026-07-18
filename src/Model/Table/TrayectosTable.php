<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Trayectos Model
 *
 * @property \App\Model\Table\CursosTable&\Cake\ORM\Association\HasMany $Cursos
 * @property \App\Model\Table\MallasTable&\Cake\ORM\Association\HasMany $Mallas
 *
 * @method \App\Model\Entity\Trayecto get($primaryKey, $options = [])
 * @method \App\Model\Entity\Trayecto newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Trayecto[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Trayecto|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Trayecto saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Trayecto patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Trayecto[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Trayecto findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
*/
class TrayectosTable extends AppTable
{
    protected $searchFields = [
        'codigo' => ['type' => 'text', 'label' => 'Codigo', 'class' => 'form-control'],
        'nombre' => ['type' => 'text', 'label' => 'Nombre', 'class' => 'form-control'],
        'activo' => [
            'type' => 'select',
            'label' => 'Activo',
            'options' => [1 => 'SI', 0 => 'NO'],
            'empty' => '-- Activo --',
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

        $this->setTable('trayectos');
        $this->setDisplayField('codename');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Cursos', [
            'foreignKey' => 'trayecto_id',
        ]);
        $this->hasMany('Mallas', [
            'foreignKey' => 'trayecto_id',
        ]);
        $this->hasMany('SituacionEstudiantes', [
            'foreignKey' => 'trayecto_id',
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
            ->scalar('codigo')
            ->maxLength('codigo', 20)
            ->requirePresence('codigo', 'create')
            ->notEmptyString('codigo');

        $validator
            ->scalar('nombre')
            ->maxLength('nombre', 50)
            ->requirePresence('nombre', 'create')
            ->notEmptyString('nombre');

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
        $rules->add($rules->isUnique(['codigo'], 'Ya existe una registro con este código.'));
        $rules->add($rules->isUnique(['nombre'], 'Ya existe una registro con este nombre.'));

        return $rules;
    }
}
