<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Aulas Model
 *
 * @property &\Cake\ORM\Association\HasMany $Cursos
 *
 * @method \App\Model\Entity\Aula get($primaryKey, $options = [])
 * @method \App\Model\Entity\Aula newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Aula[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Aula|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Aula saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Aula patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Aula[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Aula findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AulasTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('aulas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Cursos', [
            'foreignKey' => 'aula_id',
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
            ->integer('sede')
            ->notEmptyString('sede');

        $validator
            ->scalar('codigo')
            ->maxLength('codigo', 20)
            ->requirePresence('codigo', 'create')
            ->notEmptyString('codigo');

        $validator
            ->scalar('nombre')
            ->maxLength('nombre', 80)
            ->requirePresence('nombre', 'create')
            ->notEmptyString('nombre');

        $validator
            ->requirePresence('capacidad', 'create')
            ->notEmptyString('capacidad');

        $validator
            ->scalar('ubicacion')
            ->maxLength('ubicacion', 80)
            ->requirePresence('ubicacion', 'create')
            ->notEmptyString('ubicacion');

        $validator
            ->boolean('condicion')
            ->notEmptyString('condicion');

        return $validator;
    }
}
