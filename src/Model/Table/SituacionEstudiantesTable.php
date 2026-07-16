<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SituacionEstudiantes Model
 *
 * @property \App\Model\Table\EstudiantesTable&\Cake\ORM\Association\BelongsTo $Estudiantes
 * @property \App\Model\Table\ProgramasTable&\Cake\ORM\Association\BelongsTo $Programas
 * @property \App\Model\Table\AsignaturasTable&\Cake\ORM\Association\BelongsTo $Asignaturas
 * @property \App\Model\Table\PeriodosTable&\Cake\ORM\Association\BelongsTo $Periodos
 *
 * @method \App\Model\Entity\SituacionEstudiante get($primaryKey, $options = [])
 * @method \App\Model\Entity\SituacionEstudiante newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SituacionEstudiante[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SituacionEstudiante|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SituacionEstudiante saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SituacionEstudiante patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SituacionEstudiante[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SituacionEstudiante findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SituacionEstudiantesTable extends Table
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

        $this->setTable('situacion_estudiantes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Estudiantes', [
            'foreignKey' => 'estudiante_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Programas', [
            'foreignKey' => 'programa_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Asignaturas', [
            'foreignKey' => 'asignatura_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Trayectos', [
            'foreignKey' => 'trayecto_id',
        ]);
        $this->belongsTo('Periodos', [
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
            ->integer('trayecto_id')
            ->allowEmptyString('trayecto_id');

        $validator
            ->scalar('seccion')
            ->maxLength('seccion', 20)
            ->allowEmptyString('seccion');

        $validator
            ->scalar('calificacion')
            ->maxLength('calificacion', 5)
            ->allowEmptyString('calificacion');

        $validator
            ->scalar('responsable')
            ->maxLength('responsable', 50)
            ->allowEmptyString('responsable');

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
        $rules->add($rules->existsIn(['estudiante_id'], 'Estudiantes'));
        $rules->add($rules->existsIn(['programa_id'], 'Programas'));
        $rules->add($rules->existsIn(['asignatura_id'], 'Asignaturas'));
        $rules->add($rules->existsIn(['trayecto_id'], 'Trayectos'));
        $rules->add($rules->existsIn(['periodo_id'], 'Periodos'));

        return $rules;
    }
}
