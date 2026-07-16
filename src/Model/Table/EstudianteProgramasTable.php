<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EstudianteProgramas Model
 *
 * @property \App\Model\Table\EstudiantesTable&\Cake\ORM\Association\BelongsTo $Estudiantes
 * @property \App\Model\Table\ProgramasTable&\Cake\ORM\Association\BelongsTo $Programas
 * @property \App\Model\Table\CarrerasTable&\Cake\ORM\Association\BelongsTo $Carreras
 * @property \App\Model\Table\SedesTable&\Cake\ORM\Association\BelongsTo $Sedes
 *
 * @method \App\Model\Entity\EstudiantePrograma get($primaryKey, $options = [])
 * @method \App\Model\Entity\EstudiantePrograma newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\EstudiantePrograma[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EstudiantePrograma|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EstudiantePrograma saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EstudiantePrograma patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EstudiantePrograma[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\EstudiantePrograma findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
*/
class EstudianteProgramasTable extends AppTable
{
    protected $searchFields = [
        'cedula'      => ['type' => 'text', 'label' => 'Cédula Estudiante', 'class' => 'form-control', 'prepend' => '<i class="fa fa-asterisk"></i>', 'filterField' => 'Estudiantes.cedula'],
        'expediente'  => ['type' => 'text', 'label' => 'No. Expediente', 'class' => 'form-control', 'prepend' => '<i class="fa fa-asterisk"></i>', 'filterField' => 'Estudiantes.expediente'],
        'estudiante_id' => ['type' => 'int', 'label' => 'No. Estudiante', 'class' => 'form-control isNumeric', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'carrera_id'  => ['type' => 'select', 'label' => 'Carrera', 'options' => [], 'empty' => '-- Carrera --', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'programa_id' => ['type' => 'select', 'label' => 'Programa', 'options' => [], 'empty' => '-- Programa --', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'id'          => ['type' => 'int', 'label' => 'No. de Id', 'class' => 'form-control isNumeric', 'prepend' => '<i class="fa fa-asterisk"></i>'],
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

        $this->setTable('estudiante_programas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Estudiantes', [
            'foreignKey' => 'estudiante_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Sedes', [
            'foreignKey' => 'sede_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Programas', [
            'foreignKey' => 'programa_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Carreras', [
            'foreignKey' => 'carrera_id',
            'joinType' => 'INNER',
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
            ->date('fecha_egreso')
            ->allowEmptyDate('fecha_egreso');

        $validator
            ->scalar('cohorte')
            ->maxLength('cohorte', 20)
            ->allowEmptyString('cohorte');

        $validator
            ->numeric('indice')
            ->allowEmptyString('indice');

        $validator
            ->boolean('culminado')
            ->requirePresence('culminado', 'create')
            ->notEmptyString('culminado');

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
        $rules->add($rules->existsIn(['estudiante_id'], 'Estudiantes'));
        $rules->add($rules->existsIn(['sede_id'], 'Sedes'));
        $rules->add($rules->existsIn(['programa_id'], 'Programas'));
        $rules->add($rules->existsIn(['carrera_id'], 'Carreras'));
        $rules->add($rules->isUnique(
            ['estudiante_id', 'carrera_id', 'programa_id'],
            'Este estudiante ya tiene asignado este programa en esta carrera.'
        ));

        return $rules;
    }
}
