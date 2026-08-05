<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Actos Model
 *
 * @property \App\Model\Table\GraduandosTable&\Cake\ORM\Association\HasMany $Graduandos
 *
 * @method \App\Model\Entity\Acto get($primaryKey, $options = [])
 * @method \App\Model\Entity\Acto newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Acto[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Acto|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Acto saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Acto patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Acto[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Acto findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ActosTable extends AppTable
{
    protected $searchFields = [
        'id' => ['type' => 'int', 'label' => 'No. de ID', 'class' => 'form-control isNumeric', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'nombre' => ['type' => 'text', 'label' => 'Nombre de la Promoción', 'class' => 'form-control isUpper', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'cohorte' => ['type' => 'text', 'label' => 'Cohorte', 'class' => 'form-control isUpper', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'lapso' => ['type' => 'int', 'label' => 'Lapso', 'class' => 'form-control isNumeric', 'prepend' => '<i class="fa fa-asterisk"></i>'],
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

        $this->setTable('actos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Graduandos', [
            'foreignKey' => 'acto_id',
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
            ->maxLength('nombre', 100)
            ->requirePresence('nombre', 'create')
            ->notEmptyString('nombre');

        $validator
            ->scalar('cohorte')
            ->maxLength('cohorte', 20)
            ->requirePresence('cohorte', 'create')
            ->notEmptyString('cohorte');

        $validator
            ->requirePresence('lapso', 'create')
            ->notEmptyString('lapso');

        $validator
            ->date('fecha')
            ->requirePresence('fecha', 'create')
            ->notEmptyDate('fecha');

        $validator
            ->boolean('activo')
            ->notEmptyString('activo');

        return $validator;
    }
}
