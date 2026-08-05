<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * GraduandosFixture
 */
class GraduandosFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'institucion' => ['type' => 'smallinteger', 'length' => 6, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'acto_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'carrera_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'programa_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'estudiante_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'indice' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'solicitud' => ['type' => 'smallinteger', 'length' => 6, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'control' => ['type' => 'string', 'length' => 10, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'IX_Graduandos_Actos' => ['type' => 'index', 'columns' => ['acto_id'], 'length' => []],
            'IX_Grado_Estudiante' => ['type' => 'index', 'columns' => ['estudiante_id'], 'length' => []],
            'IX_Graduando_Carrera' => ['type' => 'index', 'columns' => ['carrera_id'], 'length' => []],
            'IX_Graduando_Programa' => ['type' => 'index', 'columns' => ['programa_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'pfk_Estudiante_grado' => ['type' => 'foreign', 'columns' => ['estudiante_id'], 'references' => ['estudiantes', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
            'pfk_acto_graduandos' => ['type' => 'foreign', 'columns' => ['acto_id'], 'references' => ['actos', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
            'pfk_carrera_graduando' => ['type' => 'foreign', 'columns' => ['carrera_id'], 'references' => ['carreras', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
            'pfk_programa_graduando' => ['type' => 'foreign', 'columns' => ['programa_id'], 'references' => ['programas', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
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
                'institucion' => 1,
                'acto_id' => 1,
                'carrera_id' => 1,
                'programa_id' => 1,
                'estudiante_id' => 1,
                'indice' => 1,
                'solicitud' => 1,
                'control' => 'Lorem ip',
                'created' => '2026-08-05 15:27:48',
                'modified' => '2026-08-05 15:27:48',
            ],
        ];
        parent::init();
    }
}
