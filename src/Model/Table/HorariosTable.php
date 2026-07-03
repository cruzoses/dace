<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Horarios Model
 *
 * @property \App\Model\Table\SedesTable&\Cake\ORM\Association\BelongsTo $Sedes
 * @property \App\Model\Table\PeriodosTable&\Cake\ORM\Association\BelongsTo $Periodos
 *
 * @method \App\Model\Entity\Horario get($primaryKey, $options = [])
 * @method \App\Model\Entity\Horario newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Horario[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Horario|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Horario saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Horario patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Horario[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Horario findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class HorariosTable extends AppTable
{
    protected $searchFields = [
        'id' => ['type' => 'int', 'label' => 'No. de ID', 'class' => 'form-control isNumeric', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'codigo' => ['type' => 'text', 'label' => 'Código', 'class' => 'form-control isUpper', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'sede_id' => ['type' => 'select', 'label' => 'Sede', 'prepend' => '<i class="fa fa-asterisk"></i>', 'empty' => '-- Todas --'],
        'periodo_id' => ['type' => 'select', 'label' => 'Periodo', 'prepend' => '<i class="fa fa-asterisk"></i>', 'empty' => '-- Todos --'],
        'dia' => ['type' => 'select', 'label' => 'Día', 'prepend' => '<i class="fa fa-asterisk"></i>', 'empty' => '-- Todos --'],
        'turno' => ['type' => 'select', 'label' => 'Turno', 'prepend' => '<i class="fa fa-asterisk"></i>', 'empty' => '-- Todos --'],
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

        $this->setTable('horarios');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Sedes', [
            'foreignKey' => 'sede_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Periodos', [
            'foreignKey' => 'periodo_id',
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
            ->scalar('codigo')
            ->maxLength('codigo', 20)
            ->requirePresence('codigo', 'create')
            ->notEmptyString('codigo');

        $validator
            ->requirePresence('dia', 'create')
            ->notEmptyString('dia');

        $validator
            ->requirePresence('turno', 'create')
            ->notEmptyString('turno');

        $validator
            ->scalar('desde')
            ->maxLength('desde', 20)
            ->requirePresence('desde', 'create')
            ->notEmptyString('desde');

        $validator
            ->scalar('hasta')
            ->maxLength('hasta', 20)
            ->requirePresence('hasta', 'create')
            ->notEmptyString('hasta');

        $validator
            ->boolean('activo')
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
        $rules->add($rules->existsIn(['sede_id'], 'Sedes'));
        $rules->add($rules->existsIn(['periodo_id'], 'Periodos'));
        $rules->add($rules->isUnique(['sede_id', 'periodo_id', 'codigo'], __('Ya existe un horario con esa sede, periodo y código.')));

        return $rules;
    }
}
