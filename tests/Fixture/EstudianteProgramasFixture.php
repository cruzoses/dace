<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EstudianteProgramasFixture
 */
class EstudianteProgramasFixture extends TestFixture
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
        'sede_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'programa_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'fecha_egreso' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'cohorte' => ['type' => 'string', 'length' => 20, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'indice' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'culminado' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'activo' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'IX_Programa_Estudiante' => ['type' => 'index', 'columns' => ['estudiante_id'], 'length' => []],
            'IX_Estudiante_Programa' => ['type' => 'index', 'columns' => ['programa_id'], 'length' => []],
            'IX_Estudiante_Sede' => ['type' => 'index', 'columns' => ['sede_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'pfk_estudiante_programa' => ['type' => 'foreign', 'columns' => ['estudiante_id'], 'references' => ['estudiantes', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
            'pfk_programa_estudiante' => ['type' => 'foreign', 'columns' => ['programa_id'], 'references' => ['programas', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
            'pfk_sede_estudiante' => ['type' => 'foreign', 'columns' => ['sede_id'], 'references' => ['sedes', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
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
                'sede_id' => 1,
                'programa_id' => 1,
                'fecha_egreso' => '2026-07-05',
                'cohorte' => 'Lorem ipsum dolor ',
                'indice' => 1,
                'culminado' => 1,
                'activo' => 1,
                'created' => '2026-07-05 20:50:40',
                'modified' => '2026-07-05 20:50:40',
            ],
        ];
        parent::init();
    }
}
