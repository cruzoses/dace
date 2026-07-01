<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AsignaturasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AsignaturasTable Test Case
 */
class AsignaturasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AsignaturasTable
     */
    public $Asignaturas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Asignaturas',
        'app.GrupoAsignaturas',
        'app.Historicos',
        'app.Mallas',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Asignaturas') ? [] : ['className' => AsignaturasTable::class];
        $this->Asignaturas = TableRegistry::getTableLocator()->get('Asignaturas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Asignaturas);

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
