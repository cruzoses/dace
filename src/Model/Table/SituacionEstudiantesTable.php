<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
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
            ->integer('cursada')
            ->allowEmptyString('cursada');

        $validator
            ->integer('acumulado')
            ->allowEmptyString('acumulado');

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

    public function registrarDesdeMalla($estudianteId, $programaId, $carreraId, $periodoId)
    {
        \Cake\Log\Log::write('debug', 'registrarDesdeMalla INICIO: est=' . $estudianteId . ' prog=' . $programaId . ' carr=' . $carreraId . ' per=' . $periodoId);

        $existe = $this->find()
            ->where([
                'estudiante_id' => $estudianteId,
                'programa_id' => $programaId,
            ])
            ->count();

        \Cake\Log\Log::write('debug', 'registrarDesdeMalla registros existentes: ' . $existe);

        if ($existe > 0) {
            \Cake\Log\Log::write('debug', 'registrarDesdeMalla YA EXISTEN registros, se omite');
            return;
        }

        $mallasTable = TableRegistry::getTableLocator()->get('Mallas');
        $mallas = $mallasTable->find()
            ->where([
                'carrera_id' => $carreraId,
                'programa_id' => $programaId,
            ])
            ->toArray();

        \Cake\Log\Log::write('debug', 'registrarDesdeMalla mallas encontradas: ' . count($mallas));

        if (empty($mallas)) {
            \Cake\Log\Log::write('debug', 'registrarDesdeMalla NO HAY MALLAS para carrera=' . $carreraId . ' programa=' . $programaId);
            return;
        }

        foreach ($mallas as $malla) {
            $situacion = $this->newEntity();
            $situacion->estudiante_id = $estudianteId;
            $situacion->programa_id = $programaId;
            $situacion->asignatura_id = $malla->asignatura_id;
            $situacion->trayecto_id = $malla->trayecto_id;
            $situacion->periodo_id = $periodoId;
            $situacion->cursada = 1;
            $situacion->acumulado = 1;
            $result = $this->save($situacion, ['checkRules' => false]);
            if (!$result) {
                \Cake\Log\Log::write('error', 'registrarDesdeMalla SAVE FALLÓ: est=' . $estudianteId . ' prog=' . $programaId . ' asig=' . $malla->asignatura_id . ' errors=' . json_encode($situacion->getErrors()));
            }
        }

        \Cake\Log\Log::write('debug', 'registrarDesdeMalla FIN');
    }
}
