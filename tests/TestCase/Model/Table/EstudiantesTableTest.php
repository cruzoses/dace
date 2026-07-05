<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EstudiantesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EstudiantesTable Test Case
 */
class EstudiantesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\EstudiantesTable
     */
    public $Estudiantes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Estudiantes',
        'app.Paises',
        'app.Estados',
        'app.Municipios',
        'app.Parroquias',
        'app.Usuarios',
        'app.EstudianteCursos',
        'app.EstudianteProgramas',
        'app.Graduandos',
        'app.Historicos',
        'app.NotasCursos',
        'app.SituacionEstudiantes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Estudiantes') ? [] : ['className' => EstudiantesTable::class];
        $this->Estudiantes = TableRegistry::getTableLocator()->get('Estudiantes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Estudiantes);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
