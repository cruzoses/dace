<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * ContenidoCursos Model
 *
 * @property \App\Model\Table\IndicadorCursosTable&\Cake\ORM\Association\BelongsTo $IndicadorCursos
 * @property \App\Model\Table\NotasCursosTable&\Cake\ORM\Association\HasMany $NotasCursos
 *
 * @method \App\Model\Entity\ContenidoCurso get($primaryKey, $options = [])
 * @method \App\Model\Entity\ContenidoCurso newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ContenidoCurso[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ContenidoCurso|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ContenidoCurso saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ContenidoCurso patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ContenidoCurso[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ContenidoCurso findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ContenidoCursosTable extends AppTable
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

        $this->setTable('contenido_cursos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('IndicadorCursos', [
            'foreignKey' => 'indicador_curso_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('NotasCursos', [
            'foreignKey' => 'contenido_curso_id',
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
            ->date('fecha')
            ->requirePresence('fecha', 'create')
            ->notEmptyDate('fecha');

        $validator
            ->scalar('descripcion')
            ->maxLength('descripcion', 50)
            ->requirePresence('descripcion', 'create')
            ->notEmptyString('descripcion');

        $validator
            ->scalar('detalle')
            ->minLength('detalle', 30, 'La descripción debe tener al menos 30 caracteres.')
            ->requirePresence('detalle', 'create')
            ->notEmptyString('detalle');

        $validator
            ->requirePresence('ponderacion', 'create')
            ->notEmptyString('ponderacion');

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
        $rules->add($rules->existsIn(['indicador_curso_id'], 'IndicadorCursos'));

        $rules->add(function ($entity, $options) {
            $table = $options['repository'];

            $indicadorCursosTable = TableRegistry::getTableLocator()->get('IndicadorCursos');
            $oIndicadorCurso = $indicadorCursosTable->get($entity->indicador_curso_id);
            $nPorcentajeIndicador = (int)$oIndicadorCurso->porcentaje;

            $query = $table->find()
                ->where([
                    'indicador_curso_id' => $entity->indicador_curso_id,
                    'activo' => true
                ]);

            if ($entity->id) {
                $query->where(['id !=' => $entity->id]);
            }

            $nSumaExistente = (int)$query->select([
                'total' => $query->func()->sum('ponderacion')
            ])->first()->total;

            $nNuevaPonderacion = (int)$entity->ponderacion;
            $nTotal = $nSumaExistente + $nNuevaPonderacion;

            if ($nTotal > $nPorcentajeIndicador) {
                $nDisponible = $nPorcentajeIndicador - $nSumaExistente;
                $entity->setError('ponderacion', [
                    'La ponderación (' . $nNuevaPonderacion . '%) excede el límite del indicador. Disponible: ' . $nDisponible . '% de ' . $nPorcentajeIndicador . '%'
                ]);
                return false;
            }

            return true;
        }, 'validarPonderacionPorIndicador');

        return $rules;
    }
}
