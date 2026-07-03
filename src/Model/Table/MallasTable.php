<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Mallas Model
 *
 * @property \App\Model\Table\ProgramasTable&\Cake\ORM\Association\BelongsTo $Programas
 * @property \App\Model\Table\TrayectosTable&\Cake\ORM\Association\BelongsTo $Trayectos
 * @property \App\Model\Table\AsignaturasTable&\Cake\ORM\Association\BelongsTo $Asignaturas
 *
 * @method \App\Model\Entity\Malla get($primaryKey, $options = [])
 * @method \App\Model\Entity\Malla newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Malla[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Malla|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Malla saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Malla patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Malla[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Malla findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MallasTable extends Table
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

        $this->setTable('mallas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Programas', [
            'foreignKey' => 'programa_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Trayectos', [
            'foreignKey' => 'trayecto_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Asignaturas', [
            'foreignKey' => 'asignatura_id',
            'joinType' => 'INNER',
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
            ->scalar('nota_minima')
            ->maxLength('nota_minima', 10)
            ->allowEmptyString('nota_minima');

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
        $rules->add($rules->existsIn(['programa_id'], 'Programas'));
        $rules->add($rules->existsIn(['trayecto_id'], 'Trayectos'));
        $rules->add($rules->existsIn(['asignatura_id'], 'Asignaturas'));

        return $rules;
    }
}
