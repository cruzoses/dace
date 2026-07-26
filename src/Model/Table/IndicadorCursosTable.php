<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * IndicadorCursos Model
 *
 * @property \App\Model\Table\CursosTable&\Cake\ORM\Association\BelongsTo $Cursos
 * @property \App\Model\Table\IndicadoresTable&\Cake\ORM\Association\BelongsTo $Indicadores
 * @property \App\Model\Table\ContenidosCursosTable&\Cake\ORM\Association\HasMany $ContenidosCursos
 *
 * @method \App\Model\Entity\IndicadorCurso get($primaryKey, $options = [])
 * @method \App\Model\Entity\IndicadorCurso newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\IndicadorCurso[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\IndicadorCurso|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\IndicadorCurso saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\IndicadorCurso patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\IndicadorCurso[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\IndicadorCurso findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class IndicadorCursosTable extends AppTable
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

        $this->setTable('indicador_cursos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Cursos', [
            'foreignKey' => 'curso_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Indicadores', [
            'foreignKey' => 'indicador_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('ContenidosCursos', [
            'foreignKey' => 'indicador_curso_id',
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
            ->date('desde')
            ->requirePresence('desde', 'create')
            ->notEmptyDate('desde');

        $validator
            ->date('hasta')
            ->requirePresence('hasta', 'create')
            ->notEmptyDate('hasta');

        $validator
            ->requirePresence('escala_nota', 'create')
            ->notEmptyString('escala_nota');

        $validator
            ->requirePresence('porcentaje', 'create')
            ->notEmptyString('porcentaje');

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
        $rules->add($rules->existsIn(['curso_id'], 'Cursos'));
        $rules->add($rules->existsIn(['indicador_id'], 'Indicadores'));

        $rules->add($rules->isUnique(
            ['curso_id', 'indicador_id'],
            'Ya existe un registro con estas datos.'
        ));

        $rules->add(function ($entity, $options) {
            $table = $options['repository'];
            $cursosTable = TableRegistry::getTableLocator()->get('Cursos');

            $curso = $cursosTable->get($entity->curso_id, ['contain' => ['Asignaturas']]);
            $nFrecuencia = (int)$curso->asignatura->frecuencia;

            $query = $table->find()
                ->where(['curso_id' => $entity->curso_id]);

            if ($entity->id) {
                $query->where(['id !=' => $entity->id]);
            }

            $aExistentes = $query->extract('porcentaje')->toArray();
            $nTotalActual = array_sum($aExistentes);
            $nNuevoPorcentaje = (int)$entity->porcentaje;
            $nTotal = $nTotalActual + $nNuevoPorcentaje;
            $nCantidad = count($aExistentes) + 1;

            switch ($nFrecuencia) {
                case 1:
                    if ($nCantidad > 1) {
                        $options['errors'][] = 'Solo se permite 1 indicador para frecuencia TRIMESTRAL.';
                        return false;
                    }
                    if ($nNuevoPorcentaje != 100) {
                        $options['errors'][] = 'Para TRIMESTRAL el porcentaje debe ser 100%.';
                        return false;
                    }
                    break;

                case 2:
                    if ($nCantidad > 2) {
                        $options['errors'][] = 'Solo se permiten 2 indicadores para frecuencia SEMESTRAL.';
                        return false;
                    }
                    if ($nNuevoPorcentaje != 50) {
                        $options['errors'][] = 'Para SEMESTRAL el porcentaje debe ser 50%.';
                        return false;
                    }
                    break;

                case 3:
                    if ($nCantidad > 3) {
                        $options['errors'][] = 'Solo se permiten 3 indicadores para frecuencia ANUALIZADA.';
                        return false;
                    }
                    if ($nTotal > 100) {
                        $options['errors'][] = 'El total de porcentajes no puede exceder 100%.';
                        return false;
                    }
                    $aPermitidos = [30, 40];
                    if (!in_array($nNuevoPorcentaje, $aPermitidos)) {
                        $options['errors'][] = 'Para ANUALIZADA solo se permiten porcentajes de 30% o 40%.';
                        return false;
                    }
                    if (count($aExistentes) == 2 && $nTotal != 100) {
                        $nFaltante = 100 - $nTotalActual;
                        $options['errors'][] = 'El tercer indicador debe ser ' . $nFaltante . '% para completar 100%.';
                        return false;
                    }
                    break;
            }

            return true;
        }, 'validarPorcentajePorFrecuencia');

        return $rules;
    }
}
