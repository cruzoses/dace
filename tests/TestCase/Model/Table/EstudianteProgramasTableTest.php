<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EstudianteProgramasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EstudianteProgramasTable Test Case
 */
class EstudianteProgramasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\EstudianteProgramasTable
     */
    public $EstudianteProgramas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.EstudianteProgramas',
        'app.Estudiantes',
        'app.Sedes',
        'app.Programas',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('EstudianteProgramas') ? [] : ['className' => EstudianteProgramasTable::class];
        $this->EstudianteProgramas = TableRegistry::getTableLocator()->get('EstudianteProgramas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EstudianteProgramas);

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
