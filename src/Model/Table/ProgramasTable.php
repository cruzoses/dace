<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Programas Model
 *
 * @property \App\Model\Table\CarrerasTable&\Cake\ORM\Association\BelongsTo $Carreras
 * @property \App\Model\Table\SubsistemasTable&\Cake\ORM\Association\BelongsTo $Subsistemas
 * @property \App\Model\Table\CursosTable&\Cake\ORM\Association\HasMany $Cursos
 * @property \App\Model\Table\EstudianteProgramasTable&\Cake\ORM\Association\HasMany $EstudianteProgramas
 * @property \App\Model\Table\MallasTable&\Cake\ORM\Association\HasMany $Mallas
 *
 * @method \App\Model\Entity\Programa get($primaryKey, $options = [])
 * @method \App\Model\Entity\Programa newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Programa[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Programa|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Programa saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Programa patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Programa[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Programa findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ProgramasTable extends AppTable
{
    protected $searchFields = [
        'id' => ['type' => 'int', 'label' => 'No. de ID', 'class' => 'form-control isNumeric', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'codigo' => ['type' => 'exact', 'label' => 'Código', 'class' => 'form-control isUpper', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'nombre' => ['type' => 'text', 'label' => 'Nombre', 'class' => 'form-control isUpper', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'carrera_id' => ['type' => 'select', 'label' => 'Carrera', 'prepend' => '<i class="fa fa-asterisk"></i>', 'empty' => '-- Todas --'],
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

        $this->setTable('programas');
        $this->setDisplayField('codename');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Carreras', [
            'foreignKey' => 'carrera_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Subsistemas', [
            'foreignKey' => 'subsistema_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('EstudianteProgramas', [
            'foreignKey' => 'programa_id',
        ]);
        $this->hasMany('Mallas', [
            'foreignKey' => 'programa_id',
        ]);
        $this->hasMany('SituacionEstudiantes', [
            'foreignKey' => 'programa_id',
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
            ->maxLength('nombre', 80)
            ->requirePresence('nombre', 'create')
            ->notEmptyString('nombre');

        $validator
            ->scalar('nota_minima')
            ->maxLength('nota_minima', 10)
            ->requirePresence('nota_minima', 'create')
            ->notEmptyString('nota_minima');

        $validator
            ->requirePresence('creditos', 'create')
            ->notEmptyString('creditos');

        $validator
            ->boolean('pasantia')
            ->requirePresence('pasantia', 'create')
            ->notEmptyString('pasantia');

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
        $rules->add($rules->isUnique(['codigo'], 'Ya existe una programa con este código.'));
        $rules->add($rules->existsIn(['carrera_id'], 'Carreras'));
        $rules->add($rules->existsIn(['subsistema_id'], 'Subsistemas'));

        return $rules;
    }
}
