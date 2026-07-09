<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SituacionEstudiantesFixture
 */
class SituacionEstudiantesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'estudiante_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'programa_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'asignatura_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'periodo_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'seccion' => ['type' => 'string', 'length' => 20, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'calificacion' => ['type' => 'string', 'length' => 5, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'responsable' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'IX_Situacion_Academica' => ['type' => 'index', 'columns' => ['estudiante_id'], 'length' => []],
            'IX_Situacion_Programa' => ['type' => 'index', 'columns' => ['programa_id'], 'length' => []],
            'IX_Situacion_Asignatura' => ['type' => 'index', 'columns' => ['asignatura_id'], 'length' => []],
            'IX_Situacion_Periodo' => ['type' => 'index', 'columns' => ['periodo_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'pfk_asignatura_situacion' => ['type' => 'foreign', 'columns' => ['asignatura_id'], 'references' => ['asignaturas', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
            'pfk_estudiante_situacion' => ['type' => 'foreign', 'columns' => ['estudiante_id'], 'references' => ['estudiantes', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
            'pfk_periodo_situacion' => ['type' => 'foreign', 'columns' => ['periodo_id'], 'references' => ['periodos', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
            'pfk_programa_situacion' => ['type' => 'foreign', 'columns' => ['programa_id'], 'references' => ['programas', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
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
                'estudiante_id' => 1,
                'programa_id' => 1,
                'asignatura_id' => 1,
                'periodo_id' => 1,
                'seccion' => 'Lorem ipsum dolor ',
                'calificacion' => 'Lor',
                'responsable' => 'Lorem ipsum dolor sit amet',
                'created' => '2026-07-09 22:16:13',
                'modified' => '2026-07-09 22:16:13',
            ],
        ];
        parent::init();
    }
}
