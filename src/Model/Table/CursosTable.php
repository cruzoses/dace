<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * Cursos Model
 *
 * @property \App\Model\Table\SedesTable&\Cake\ORM\Association\BelongsTo $Sedes
 * @property \App\Model\Table\PeriodosTable&\Cake\ORM\Association\BelongsTo $Periodos
 * @property \App\Model\Table\CarrerasTable&\Cake\ORM\Association\BelongsTo $Carreras
 * @property \App\Model\Table\TrayectosTable&\Cake\ORM\Association\BelongsTo $Trayectos
 * @property \App\Model\Table\AsignaturasTable&\Cake\ORM\Association\BelongsTo $Asignaturas
 * @property \App\Model\Table\DocentesTable&\Cake\ORM\Association\BelongsTo $Docentes
 * @property \App\Model\Table\AulasTable&\Cake\ORM\Association\BelongsTo $Aulas
 * @property \App\Model\Table\EstudianteCursosTable&\Cake\ORM\Association\HasMany $EstudianteCursos
 * @property \App\Model\Table\IndicadorCursosTable&\Cake\ORM\Association\HasMany $IndicadorCursos
 *
 * @method \App\Model\Entity\Curso get($primaryKey, $options = [])
 * @method \App\Model\Entity\Curso newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Curso[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Curso|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Curso saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Curso patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Curso[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Curso findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CursosTable extends AppTable
{
    protected $searchFields = [
        'id' => ['type' => 'int', 'label' => 'No. de Id', 'class' => 'isNumeric', 'prepend' => '<i class="fa fa-asterisk"></i>'],
        'sede_id' => [
            'type' => 'select',
            'label' => 'Sede',
            'options' => [],
            'empty' => '-- Sede --',
            'prepend' => '<i class="fa fa-asterisk"></i>',
        ],
        'periodo_id' => [
            'type' => 'select',
            'label' => 'Periodo',
            'options' => [],
            'empty' => '-- Periodo --',
            'prepend' => '<i class="fa fa-asterisk"></i>',
        ],
        'carrera_id' => [
            'type' => 'select',
            'label' => 'Carrera',
            'options' => [],
            'empty' => '-- Carrera --',
            'prepend' => '<i class="fa fa-asterisk"></i>',
        ],
        'trayecto_id' => [
            'type' => 'select',
            'label' => 'Trayecto',
            'options' => [],
            'empty' => '-- Trayecto --',
            'prepend' => '<i class="fa fa-asterisk"></i>',
        ],
        'asignatura_id' => [
            'type' => 'select',
            'label' => 'Asignatura',
            'options' => [],
            'empty' => '-- Asignatura --',
            'prepend' => '<i class="fa fa-asterisk"></i>',
        ],
        'docente_id' => [
            'type' => 'select',
            'label' => 'Docente',
            'options' => [],
            'empty' => '-- Docente --',
            'prepend' => '<i class="fa fa-asterisk"></i>',
        ],
        'profesores' => ['type' => 'text', 'label' => 'Profesores', 'class' => 'isUpper', 'prepend' => '<i class="fa fa-asterisk"></i>'],
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

        $this->setTable('cursos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Sedes', [
            'foreignKey' => 'sede_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Periodos', [
            'foreignKey' => 'periodo_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Carreras', [
            'foreignKey' => 'carrera_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Trayectos', [
            'foreignKey' => 'trayecto_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Asignaturas', [
            'foreignKey' => 'asignatura_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Docentes', [
            'foreignKey' => 'docente_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Aulas', [
            'foreignKey' => 'aula_id',
        ]);
        $this->hasMany('EstudianteCursos', [
            'foreignKey' => 'curso_id',
        ]);
        $this->hasMany('IndicadorCursos', [
            'foreignKey' => 'curso_id',
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
            ->scalar('profesores')
            ->maxLength('profesores', 40)
            ->requirePresence('profesores', 'create')
            ->notEmptyString('profesores');

        $validator
            ->scalar('seccion')
            ->maxLength('seccion', 20)
            ->requirePresence('seccion', 'create')
            ->notEmptyString('seccion');

        $validator
            ->requirePresence('cupos', 'create')
            ->notEmptyString('cupos');

        $validator
            ->scalar('horario')
            ->maxLength('horario', 60)
            ->requirePresence('horario', 'create')
            ->notEmptyString('horario');

        $validator
            ->scalar('programas')
            ->maxLength('programas', 100)
            ->allowEmptyString('programas');

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
        $rules->add($rules->existsIn(['sede_id'], 'Sedes'));
        $rules->add($rules->existsIn(['periodo_id'], 'Periodos'));
        $rules->add($rules->existsIn(['carrera_id'], 'Carreras'));
        $rules->add($rules->existsIn(['trayecto_id'], 'Trayectos'));
        $rules->add($rules->existsIn(['asignatura_id'], 'Asignaturas'));
        $rules->add($rules->existsIn(['docente_id'], 'Docentes'));
        $rules->add($rules->existsIn(['aula_id'], 'Aulas'));

        return $rules;
    }
}
