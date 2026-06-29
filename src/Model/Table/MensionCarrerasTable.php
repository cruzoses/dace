<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * MensionCarreras Model
 *
 * @property \App\Model\Table\CarrerasTable&\Cake\ORM\Association\HasMany $Carreras
 *
 * @method \App\Model\Entity\MensionCarrera get($primaryKey, $options = [])
 * @method \App\Model\Entity\MensionCarrera newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MensionCarrera[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MensionCarrera|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MensionCarrera saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MensionCarrera patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MensionCarrera[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MensionCarrera findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MensionCarrerasTable extends AppTable
{
    protected $searchFields = [
        'nombre' => ['type' => 'text', 'label' => 'Nombre', 'class' => 'form-control isUpper'],
        'activa' => ['type' => 'select', 'label' => 'Activa', 'class' => 'form-control select2', 'options' => [1 => 'Sí', 0 => 'No'], 'empty' => '-- Seleccione --'],
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

        $this->setTable('mension_carreras');
        $this->setDisplayField('nombre');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Carreras', [
            'foreignKey' => 'mension_carrera_id',
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
            ->scalar('nombre')
            ->maxLength('nombre', 80)
            ->requirePresence('nombre', 'create')
            ->notEmptyString('nombre');

        $validator
            ->boolean('activa')
            ->requirePresence('activa', 'create')
            ->notEmptyString('activa');

        return $validator;
    }
}
