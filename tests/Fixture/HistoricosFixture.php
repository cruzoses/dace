<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * HistoricosFixture
 */
class HistoricosFixture extends TestFixture
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
        'periodo_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'asignatura_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'calificacion' => ['type' => 'string', 'length' => 10, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'seccion' => ['type' => 'string', 'length' => 10, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'responsable' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'IX_Historico_Estudiante' => ['type' => 'index', 'columns' => ['estudiante_id'], 'length' => []],
            'IX_Historico_Periodo' => ['type' => 'index', 'columns' => ['periodo_id'], 'length' => []],
            'IX_HIstorico_Asignatura' => ['type' => 'index', 'columns' => ['asignatura_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'pfk_asignatura_historico' => ['type' => 'foreign', 'columns' => ['asignatura_id'], 'references' => ['asignaturas', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
            'pfk_estudiante_historico' => ['type' => 'foreign', 'columns' => ['estudiante_id'], 'references' => ['estudiantes', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
            'pfk_periodo_historico' => ['type' => 'foreign', 'columns' => ['periodo_id'], 'references' => ['periodos', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
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
                'periodo_id' => 1,
                'asignatura_id' => 1,
                'calificacion' => 'Lorem ip',
                'seccion' => 'Lorem ip',
                'responsable' => 'Lorem ipsum dolor sit amet',
                'created' => '2026-07-03 17:00:21',
                'modified' => '2026-07-03 17:00:21',
            ],
        ];
        parent::init();
    }
}
