<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CursosFixture
 */
class CursosFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'sede_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'periodo_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'carrera_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'programa_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'trayecto_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'asignatura_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'profesores' => ['type' => 'string', 'length' => 40, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'docente_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'seccion' => ['type' => 'string', 'length' => 20, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'cupos' => ['type' => 'smallinteger', 'length' => 6, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'aula_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'horario' => ['type' => 'string', 'length' => 60, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'activo' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'IX_Curso_Sede' => ['type' => 'index', 'columns' => ['sede_id'], 'length' => []],
            'IX_Curso_Periodo' => ['type' => 'index', 'columns' => ['periodo_id'], 'length' => []],
            'IX_Curso_Carrera' => ['type' => 'index', 'columns' => ['carrera_id'], 'length' => []],
            'IX_Curso_Docente' => ['type' => 'index', 'columns' => ['docente_id'], 'length' => []],
            'IX_Curso_Trayecto' => ['type' => 'index', 'columns' => ['trayecto_id'], 'length' => []],
            'IX_Curso_Programa' => ['type' => 'index', 'columns' => ['programa_id'], 'length' => []],
            'IX_Curso_Aula' => ['type' => 'index', 'columns' => ['aula_id'], 'length' => []],
            'IX_Curso_Asignatura' => ['type' => 'index', 'columns' => ['asignatura_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'pfk_asignatura_curso' => ['type' => 'foreign', 'columns' => ['asignatura_id'], 'references' => ['asignaturas', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
            'pfk_aula_curso' => ['type' => 'foreign', 'columns' => ['aula_id'], 'references' => ['aulas', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
            'pfk_carrera_curso' => ['type' => 'foreign', 'columns' => ['carrera_id'], 'references' => ['carreras', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
            'pfk_docente_curso' => ['type' => 'foreign', 'columns' => ['docente_id'], 'references' => ['docentes', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
            'pfk_periodo_curso' => ['type' => 'foreign', 'columns' => ['periodo_id'], 'references' => ['periodos', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
            'pfk_programa_curso' => ['type' => 'foreign', 'columns' => ['programa_id'], 'references' => ['programas', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
            'pfk_sede_curso' => ['type' => 'foreign', 'columns' => ['sede_id'], 'references' => ['sedes', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
            'pfk_trayecto_curso' => ['type' => 'foreign', 'columns' => ['trayecto_id'], 'references' => ['trayectos', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_spanish_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd
    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'sede_id' => 1,
                'periodo_id' => 1,
                'carrera_id' => 1,
                'programa_id' => 1,
                'trayecto_id' => 1,
                'asignatura_id' => 1,
                'profesores' => 'Lorem ipsum dolor sit amet',
                'docente_id' => 1,
                'seccion' => 'Lorem ipsum dolor ',
                'cupos' => 1,
                'aula_id' => 1,
                'horario' => 'Lorem ipsum dolor sit amet',
                'activo' => 1,
                'created' => '2026-07-04 00:51:27',
                'modified' => '2026-07-04 00:51:27',
            ],
        ];
        parent::init();
    }
}
