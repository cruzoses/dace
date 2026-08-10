<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Graduandos Model
 *
 * @property \App\Model\Table\ActosTable&\Cake\ORM\Association\BelongsTo $Actos
 * @property \App\Model\Table\CarrerasTable&\Cake\ORM\Association\BelongsTo $Carreras
 * @property \App\Model\Table\ProgramasTable&\Cake\ORM\Association\BelongsTo $Programas
 * @property \App\Model\Table\EstudiantesTable&\Cake\ORM\Association\BelongsTo $Estudiantes
 *
 * @method \App\Model\Entity\Graduando get($primaryKey, $options = [])
 * @method \App\Model\Entity\Graduando newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Graduando[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Graduando|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Graduando saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Graduando patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Graduando[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Graduando findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GraduandosTable extends AppTable
{
    protected $searchFields = [
        'institucion' => ['type' => 'int', 'label' => 'Institución', 'class' => 'form-control', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'acto_id' => ['type' => 'select', 'label' => 'Acto', 'prepend' => '<i class="fa fa-asterisk"></i>', 'empty' => '-- Todos --'],
        'carrera_id' => ['type' => 'select', 'label' => 'Carrera', 'prepend' => '<i class="fa fa-asterisk"></i>', 'empty' => '-- Todas --'],
        'programa_id' => ['type' => 'select', 'label' => 'Programa', 'prepend' => '<i class="fa fa-asterisk"></i>', 'empty' => '-- Todos --'],
        'estudiante_id' => ['type' => 'select', 'label' => 'Estudiante', 'prepend' => '<i class="fa fa-asterisk"></i>', 'empty' => '-- Todos --'],
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

        $this->setTable('graduandos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Actos', [
            'foreignKey' => 'acto_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Carreras', [
            'foreignKey' => 'carrera_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Programas', [
            'foreignKey' => 'programa_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Estudiantes', [
            'foreignKey' => 'estudiante_id',
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
            ->requirePresence('institucion', 'create')
            ->notEmptyString('institucion');

        $validator
            ->numeric('indice')
            ->requirePresence('indice', 'create')
            ->notEmptyString('indice');

        $validator
            ->requirePresence('solicitud', 'create')
            ->notEmptyString('solicitud');

        $validator
            ->scalar('control')
            ->maxLength('control', 10)
            ->allowEmptyString('control');

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
        $rules->add($rules->existsIn(['acto_id'], 'Actos'));
        $rules->add($rules->existsIn(['carrera_id'], 'Carreras'));
        $rules->add($rules->existsIn(['programa_id'], 'Programas'));
        $rules->add($rules->existsIn(['estudiante_id'], 'Estudiantes'));

        return $rules;
    }
}
