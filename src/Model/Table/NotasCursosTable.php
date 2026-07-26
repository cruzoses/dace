<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * NotasCursos Model
 *
 * @property \App\Model\Table\ContenidoCursosTable&\Cake\ORM\Association\BelongsTo $ContenidoCursos
 * @property \App\Model\Table\EstudiantesTable&\Cake\ORM\Association\BelongsTo $Estudiantes
 *
 * @method \App\Model\Entity\NotasCurso get($primaryKey, $options = [])
 * @method \App\Model\Entity\NotasCurso newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\NotasCurso[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\NotasCurso|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\NotasCurso saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\NotasCurso patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\NotasCurso[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\NotasCurso findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class NotasCursosTable extends AppTable
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('curso_notas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ContenidoCursos', [
            'foreignKey' => 'contenido_curso_id',
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
            ->requirePresence('calificacion', 'create')
            ->notEmptyString('calificacion');

        $validator
            ->scalar('responsable')
            ->maxLength('responsable', 50)
            ->requirePresence('responsable', 'create')
            ->notEmptyString('responsable');

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['contenido_curso_id'], 'ContenidoCursos'));
        $rules->add($rules->existsIn(['estudiante_id'], 'Estudiantes'));
        $rules->add($rules->isUnique(
            ['contenido_curso_id', 'estudiante_id'],
            'Ya existe una calificación para este estudiante en esta evaluación.'
        ));

        return $rules;
    }
}
