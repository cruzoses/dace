<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MallasFixture
 */
class MallasFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'programa_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'trayecto_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'asignatura_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'nota_minima' => ['type' => 'string', 'length' => 10, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'IX_Malla_Programa' => ['type' => 'index', 'columns' => ['programa_id'], 'length' => []],
            'IX_Malla_Asignatura' => ['type' => 'index', 'columns' => ['asignatura_id'], 'length' => []],
            'IX_Malla_Trayecto' => ['type' => 'index', 'columns' => ['trayecto_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'pfk_asignatura_malla' => ['type' => 'foreign', 'columns' => ['asignatura_id'], 'references' => ['asignaturas', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
            'pfk_programa_malla' => ['type' => 'foreign', 'columns' => ['programa_id'], 'references' => ['programas', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
            'pfk_trayecto_malla' => ['type' => 'foreign', 'columns' => ['trayecto_id'], 'references' => ['trayectos', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
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
                'programa_id' => 1,
                'trayecto_id' => 1,
                'asignatura_id' => 1,
                'nota_minima' => 'Lorem ip',
                'created' => '2026-07-03 22:36:01',
                'modified' => '2026-07-03 22:36:01',
            ],
        ];
        parent::init();
    }
}
