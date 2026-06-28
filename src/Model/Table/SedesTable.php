<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

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
class SedesTable extends Table
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

        $this->setTable('sedes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Cursos', [
            'foreignKey' => 'sede_id',
        ]);
        $this->hasMany('EstudianteProgramas', [
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
            ->boolean('activa')
            ->requirePresence('activa', 'create')
            ->notEmptyString('activa');

        return $validator;
    }
}
