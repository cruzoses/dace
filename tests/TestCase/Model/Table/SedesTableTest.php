<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SedesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SedesTable Test Case
 */
class SedesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SedesTable
     */
    public $Sedes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Sedes',
        'app.Cursos',
        'app.EstudianteProgramas',
        'app.Carreras',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Sedes') ? [] : ['className' => SedesTable::class];
        $this->Sedes = TableRegistry::getTableLocator()->get('Sedes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Sedes);

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
}
