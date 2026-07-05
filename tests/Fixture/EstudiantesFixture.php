<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EstudiantesFixture
 */
class EstudiantesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'origen' => ['type' => 'string', 'length' => 1, 'fixed' => true, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null],
        'cedula' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'nombres' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'apellidos' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'fecha_nacimiento' => ['type' => 'date', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'sexo' => ['type' => 'string', 'length' => 1, 'fixed' => true, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null],
        'estado_civil' => ['type' => 'string', 'length' => 1, 'fixed' => true, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null],
        'discapacitado' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'etnia' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'direccion' => ['type' => 'text', 'length' => null, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null],
        'telefonos' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'email' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'lugar_nacimiento' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null],
        'pais_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'estado_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'municipio_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'parroquia_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'asignado' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'codigo_opsu' => ['type' => 'string', 'length' => 20, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'fecha_notas' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'codigo_notas' => ['type' => 'string', 'length' => 20, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'fecha_titulo' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'codigo_titulo' => ['type' => 'string', 'length' => 20, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'expediente' => ['type' => 'string', 'length' => 20, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'token' => ['type' => 'string', 'length' => 10, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_spanish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'usuario_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'activo' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'IX_Estudiante_Pais' => ['type' => 'index', 'columns' => ['pais_id'], 'length' => []],
            'IX_Estudiante_Estado' => ['type' => 'index', 'columns' => ['estado_id'], 'length' => []],
            'IX_Estudiante_Municipio' => ['type' => 'index', 'columns' => ['municipio_id'], 'length' => []],
            'IX_Estudiante_Parroquia' => ['type' => 'index', 'columns' => ['parroquia_id'], 'length' => []],
            'IX_Estudiante_Usuario' => ['type' => 'index', 'columns' => ['usuario_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'pfk_estado_estudiante' => ['type' => 'foreign', 'columns' => ['estado_id'], 'references' => ['estados', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
            'pfk_municipio_estudiante' => ['type' => 'foreign', 'columns' => ['municipio_id'], 'references' => ['municipios', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
            'pfk_pais_estudiante' => ['type' => 'foreign', 'columns' => ['pais_id'], 'references' => ['paises', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
            'pfk_parroquia_estudiante' => ['type' => 'foreign', 'columns' => ['parroquia_id'], 'references' => ['parroquias', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
            'pfk_usuario_estudiante' => ['type' => 'foreign', 'columns' => ['usuario_id'], 'references' => ['usuarios', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
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
                'origen' => 'L',
                'cedula' => 1,
                'nombres' => 'Lorem ipsum dolor sit amet',
                'apellidos' => 'Lorem ipsum dolor sit amet',
                'fecha_nacimiento' => '2026-07-05',
                'sexo' => 'L',
                'estado_civil' => 'L',
                'discapacitado' => 1,
                'etnia' => 1,
                'direccion' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'telefonos' => 'Lorem ipsum dolor sit amet',
                'email' => 'Lorem ipsum dolor sit amet',
                'lugar_nacimiento' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'pais_id' => 1,
                'estado_id' => 1,
                'municipio_id' => 1,
                'parroquia_id' => 1,
                'asignado' => 1,
                'codigo_opsu' => 'Lorem ipsum dolor ',
                'fecha_notas' => '2026-07-05',
                'codigo_notas' => 'Lorem ipsum dolor ',
                'fecha_titulo' => '2026-07-05',
                'codigo_titulo' => 'Lorem ipsum dolor ',
                'expediente' => 'Lorem ipsum dolor ',
                'token' => 'Lorem ip',
                'usuario_id' => 1,
                'activo' => 1,
                'created' => '2026-07-05 02:37:27',
                'modified' => '2026-07-05 02:37:27',
            ],
        ];
        parent::init();
    }
}
