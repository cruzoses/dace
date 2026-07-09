<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Periodos Model
 *
 * @property \App\Model\Table\CursosTable&\Cake\ORM\Association\HasMany $Cursos
 * @property \App\Model\Table\HistoricosTable&\Cake\ORM\Association\HasMany $Historicos
 *
 * @method \App\Model\Entity\Periodo get($primaryKey, $options = [])
 * @method \App\Model\Entity\Periodo newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Periodo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Periodo|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Periodo saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Periodo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Periodo[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Periodo findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PeriodosTable extends AppTable
{
    protected $searchFields = [
        'id' => ['type' => 'int', 'label' => 'No. de Id', 'class' => 'form-control isNumeric', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'codigo' => ['type' => 'exact', 'label' => 'Código', 'class' => 'form-control isUpper', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'nombre' => ['type' => 'text', 'label' => 'Nombre', 'class' => 'form-control isUpper', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'lapso' => ['type' => 'int', 'label' => 'Año', 'class' => 'form-control isNumeric', 'prepend' => '<i class="fa fa-asterisk"></i>'],
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

        $this->setTable('periodos');
        $this->setDisplayField('codename');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Cursos', [
            'foreignKey' => 'periodo_id',
        ]);
        $this->hasMany('Historicos', [
            'foreignKey' => 'periodo_id',
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
            ->requirePresence('lapso', 'create')
            ->notEmptyString('lapso');

        $validator
            ->requirePresence('nota_minima', 'create')
            ->notEmptyString('nota_minima');

        $validator
            ->date('inicio')
            ->requirePresence('inicio', 'create')
            ->notEmptyDate('inicio');

        $validator
            ->date('cierre')
            ->requirePresence('cierre', 'create')
            ->notEmptyDate('cierre');

        $validator
            ->boolean('califica')
            ->requirePresence('califica', 'create')
            ->notEmptyString('califica');

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
        $rules->add($rules->isUnique(['codigo'], 'Ya existe un periodo con este código.'));
        $rules->add($rules->isUnique(['nombre'], 'Ya existe un periodo con este nombre.'));

        return $rules;
    }
}
