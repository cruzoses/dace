<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Carreras Model
 *
 * @property \App\Model\Table\MensionCarrerasTable&\Cake\ORM\Association\BelongsTo $MensionCarreras
 * @property \App\Model\Table\CursosTable&\Cake\ORM\Association\HasMany $Cursos
 * @property &\Cake\ORM\Association\HasMany $EstudianteProgramas
 * @property \App\Model\Table\MallasTable&\Cake\ORM\Association\HasMany $Mallas
 * @property \App\Model\Table\ProgramasTable&\Cake\ORM\Association\HasMany $Programas
 * @property \App\Model\Table\SedesTable&\Cake\ORM\Association\BelongsToMany $Sedes
 *
 * @method \App\Model\Entity\Carrera get($primaryKey, $options = [])
 * @method \App\Model\Entity\Carrera newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Carrera[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Carrera|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Carrera saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Carrera patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Carrera[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Carrera findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
*/
class CarrerasTable extends AppTable
{
    protected $searchFields = [
        'id' => ['type' => 'int', 'label' => 'No. de ID', 'class' => 'form-control isNumeric', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'codigo' => ['type' => 'exact', 'label' => 'Código', 'class' => 'form-control isUpper', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'nombre' => ['type' => 'text', 'label' => 'Nombre', 'class' => 'form-control isUpper', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'activa' => ['type' => 'select', 'options' => [0 => 'NO', 1 => 'SI'],'label' => 'Activa', 'prepend' => '<i class="fa fa-asterisk"></i>', 'empty' => true],
        'mension_carrera_id' => ['type' => 'select', 'label' => 'Mención', 'prepend' => '<i class="fa fa-asterisk"></i>', 'empty' => '-- Todas --'],
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

        $this->setTable('carreras');
        $this->setDisplayField('codename');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('MensionCarreras', [
            'foreignKey' => 'mension_carrera_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Cursos', [
            'foreignKey' => 'carrera_id',
        ]);
        $this->hasMany('EstudianteProgramas', [
            'foreignKey' => 'carrera_id',
        ]);
        $this->hasMany('Mallas', [
            'foreignKey' => 'carrera_id',
        ]);
        $this->hasMany('Programas', [
            'foreignKey' => 'carrera_id',
        ]);
        $this->belongsToMany('Sedes', [
            'foreignKey' => 'carrera_id',
            'targetForeignKey' => 'sede_id',
            'joinTable' => 'sedes_carreras',
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
            ->scalar('titulo_otorgado')
            ->maxLength('titulo_otorgado', 80)
            ->requirePresence('titulo_otorgado', 'create')
            ->notEmptyString('titulo_otorgado');

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
        $rules->add($rules->existsIn(['mension_carrera_id'], 'MensionCarreras'));

        return $rules;
    }
}
