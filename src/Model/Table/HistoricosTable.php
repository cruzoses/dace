<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Historicos Model
 *
 * @property \App\Model\Table\EstudiantesTable&\Cake\ORM\Association\BelongsTo $Estudiantes
 * @property \App\Model\Table\PeriodosTable&\Cake\ORM\Association\BelongsTo $Periodos
 * @property \App\Model\Table\AsignaturasTable&\Cake\ORM\Association\BelongsTo $Asignaturas
 *
 * @method \App\Model\Entity\Historico get($primaryKey, $options = [])
 * @method \App\Model\Entity\Historico newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Historico[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Historico|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Historico saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Historico patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Historico[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Historico findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class HistoricosTable extends Table
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

        $this->setTable('historicos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Estudiantes', [
            'foreignKey' => 'estudiante_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Periodos', [
            'foreignKey' => 'periodo_id',
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
            ->scalar('calificacion')
            ->maxLength('calificacion', 10)
            ->requirePresence('calificacion', 'create')
            ->notEmptyString('calificacion');

        $validator
            ->scalar('seccion')
            ->maxLength('seccion', 20)
            ->requirePresence('seccion', 'create')
            ->notEmptyString('seccion');

        $validator
            ->scalar('responsable')
            ->maxLength('responsable', 50)
            ->requirePresence('responsable', 'create')
            ->notEmptyString('responsable');

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
        $rules->add($rules->existsIn(['estudiante_id'], 'Estudiantes'));
        $rules->add($rules->existsIn(['periodo_id'], 'Periodos'));
        $rules->add($rules->existsIn(['asignatura_id'], 'Asignaturas'));

        return $rules;
    }
}
