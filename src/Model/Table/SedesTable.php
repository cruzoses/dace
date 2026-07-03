<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\EntityInterface;

/**
 * Sedes Model
 *
 * @property \App\Model\Table\CursosTable&\Cake\ORM\Association\HasMany $Cursos
 * @property \App\Model\Table\EstudianteProgramasTable&\Cake\ORM\Association\HasMany $EstudianteProgramas
 * @property \App\Model\Table\CarrerasTable&\Cake\ORM\Association\BelongsToMany $Carreras
 *
 * @method \App\Model\Entity\Sede get($primaryKey, $options = [])
 * @method \App\Model\Entity\Sede newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Sede[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Sede|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Sede saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Sede patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Sede[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Sede findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SedesTable extends AppTable
{
    protected $searchFields = [
        'id' => ['type' => 'int', 'label' => 'No. de ID', 'class' => 'form-control isNumeric', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'codigo' => ['type' => 'exact', 'label' => 'Código', 'class' => 'form-control isUpper', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'nombre' => ['type' => 'text', 'label' => 'Nombre', 'class' => 'form-control isUpper', 'prepend' => '<i class="fa fa-asterisk"></i>'],
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

        $this->setTable('sedes');
        $this->setDisplayField('codename');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Aulas', [
            'foreignKey' => 'sede_id',
        ]);

        $this->hasMany('Cursos', [
            'foreignKey' => 'sede_id',
        ]);

        $this->hasMany('EstudianteProgramas', [
            'foreignKey' => 'sede_id',
        ]);
        $this->hasMany('Horarios', [
            'foreignKey' => 'sede_id',
        ]);
        
        $this->belongsToMany('Carreras', [
            'foreignKey' => 'sede_id',
            'targetForeignKey' => 'carrera_id',
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
            ->maxLength('codigo', 10)
            ->requirePresence('codigo', 'create')
            ->notEmptyString('codigo');

        $validator
            ->scalar('nombre')
            ->maxLength('nombre', 80)
            ->requirePresence('nombre', 'create')
            ->notEmptyString('nombre');

        $validator
            ->scalar('direccion')
            ->requirePresence('direccion', 'create')
            ->notEmptyString('direccion');

        $validator
            ->scalar('telefonos')
            ->maxLength('telefonos', 40)
            ->allowEmptyString('telefonos');

        $validator
            ->scalar('responsable')
            ->maxLength('responsable', 50)
            ->requirePresence('responsable', 'create')
            ->notEmptyString('responsable');

        $validator
            ->boolean('principal')
            ->requirePresence('principal', 'create')
            ->notEmptyString('principal');

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
        $rules->add($rules->isUnique(['codigo'], 'Ya existe una sede con este código.'));
        $rules->add($rules->isUnique(['nombre'], 'Ya existe una sede on este nombre.'));
        $rules->add(function (EntityInterface $entity, array $options) {
            if ($entity->principal) 
            {
                $count = $this->find()
                    ->where(['principal' => true])
                    ->count();

                if (!$entity->isNew() && $entity->getOriginal('principal')) {
                    $count--;
                }

                if ($count > 0) {
                    return 'Ya existe una sede principal. Solo puede haber una.';
                }
            }
            return true;
        }, 'uniquePrincipal', ['errorField' => 'principal']);

        return $rules;
    }
}
