<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * EstudianteCursos Model
 *
 * @property \App\Model\Table\CursosTable&\Cake\ORM\Association\BelongsTo $Cursos
 * @property \App\Model\Table\EstudiantesTable&\Cake\ORM\Association\BelongsTo $Estudiantes
 *
 * @method \App\Model\Entity\EstudianteCurso get($primaryKey, $options = [])
 * @method \App\Model\Entity\EstudianteCurso newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\EstudianteCurso[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EstudianteCurso|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EstudianteCurso saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EstudianteCurso patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EstudianteCurso[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\EstudianteCurso findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EstudianteCursosTable extends AppTable
{
    protected $searchFields = [];

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('estudiante_cursos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Cursos', [
            'foreignKey' => 'curso_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Estudiantes', [
            'foreignKey' => 'estudiante_id',
            'joinType' => 'INNER',
        ]);
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('calificacion')
            ->maxLength('calificacion', 10)
            ->allowEmptyString('calificacion');

        $validator
            ->scalar('recuperacion')
            ->maxLength('recuperacion', 10)
            ->allowEmptyString('recuperacion');

        $validator
            ->scalar('definitiva')
            ->maxLength('definitiva', 20)
            ->allowEmptyString('definitiva');

        $validator
            ->scalar('responsable')
            ->maxLength('responsable', 50)
            ->requirePresence('responsable', 'create')
            ->notEmptyString('responsable');

        $validator
            ->scalar('observacion')
            ->allowEmptyString('observacion');

        $validator
            ->boolean('activo')
            ->requirePresence('activo', 'create')
            ->notEmptyString('activo');

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['curso_id'], 'Cursos'));
        $rules->add($rules->existsIn(['estudiante_id'], 'Estudiantes'));

        return $rules;
    }
}
