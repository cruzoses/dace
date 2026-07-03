<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ProgramasFixture
 */
class ProgramasFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'codigo' => ['type' => 'string', 'length' => 20, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'nombre' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'carrera_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'subsistema_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'nota_minima' => ['type' => 'string', 'length' => 10, 'fixed' => true, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null],
        'creditos' => ['type' => 'smallinteger', 'length' => 6, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'activo' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'IX_Programa_Carrera' => ['type' => 'index', 'columns' => ['carrera_id'], 'length' => []],
            'IX_Programa_Sistema' => ['type' => 'index', 'columns' => ['subsistema_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'pfk_carrera_programa' => ['type' => 'foreign', 'columns' => ['carrera_id'], 'references' => ['carreras', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
            'pfk_subsistema_programa' => ['type' => 'foreign', 'columns' => ['subsistema_id'], 'references' => ['subsistemas', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
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
                'codigo' => 'Lorem ipsum dolor ',
                'nombre' => 'Lorem ipsum dolor sit amet',
                'carrera_id' => 1,
                'subsistema_id' => 1,
                'nota_minima' => 'Lorem ip',
                'creditos' => 1,
                'activo' => 1,
                'created' => '2026-07-03 04:00:27',
                'modified' => '2026-07-03 04:00:27',
            ],
        ];
        parent::init();
    }
}
