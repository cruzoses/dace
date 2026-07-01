<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Asignaturas Model
 *
 * @property \App\Model\Table\GrupoAsignaturasTable&\Cake\ORM\Association\BelongsTo $GrupoAsignaturas
 * @property \App\Model\Table\HistoricosTable&\Cake\ORM\Association\HasMany $Historicos
 * @property \App\Model\Table\MallasTable&\Cake\ORM\Association\HasMany $Mallas
 *
 * @method \App\Model\Entity\Asignatura get($primaryKey, $options = [])
 * @method \App\Model\Entity\Asignatura newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Asignatura[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Asignatura|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Asignatura saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Asignatura patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Asignatura[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Asignatura findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AsignaturasTable extends AppTable
{
    protected $searchFields = [
        'id' => ['type' => 'int', 'label' => 'No. de ID', 'class' => 'form-control isNumeric', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'codigo' => ['type' => 'exact', 'label' => 'Código', 'class' => 'form-control isUpper', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'nombre' => ['type' => 'text', 'label' => 'Nombre', 'class' => 'form-control isUpper', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'grupo_asignatura_id' => ['type' => 'select', 'label' => 'Grupo Asignatura', 'prepend' => '<i class="fa fa-asterisk"></i>', 'empty' => '-- Todos --'],
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

        $this->setTable('asignaturas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('GrupoAsignaturas', [
            'foreignKey' => 'grupo_asignatura_id',
        ]);
        $this->hasMany('Historicos', [
            'foreignKey' => 'asignatura_id',
        ]);
        $this->hasMany('Mallas', [
            'foreignKey' => 'asignatura_id',
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
            ->maxLength('nombre', 100)
            ->requirePresence('nombre', 'create')
            ->notEmptyString('nombre');

        $validator
            ->requirePresence('horas_teoricas', 'create')
            ->notEmptyString('horas_teoricas');

        $validator
            ->requirePresence('horas_practicas', 'create')
            ->notEmptyString('horas_practicas');

        $validator
            ->requirePresence('frecuencia', 'create')
            ->notEmptyString('frecuencia');

        $validator
            ->requirePresence('creditos', 'create')
            ->notEmptyString('creditos');

        $validator
            ->numeric('costo')
            ->requirePresence('costo', 'create')
            ->notEmptyString('costo');

        $validator
            ->scalar('requisitos')
            ->allowEmptyString('requisitos');

        $validator
            ->scalar('convalidacion')
            ->allowEmptyString('convalidacion');

        $validator
            ->boolean('activa')
            ->requirePresence('activa', 'create')
            ->notEmptyString('activa');

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
        $rules->add($rules->existsIn(['grupo_asignatura_id'], 'GrupoAsignaturas'));

        return $rules;
    }
}
