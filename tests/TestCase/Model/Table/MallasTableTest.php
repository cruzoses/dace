<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MallasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MallasTable Test Case
 */
class MallasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MallasTable
     */
    public $Mallas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Mallas',
        'app.Programas',
        'app.Trayectos',
        'app.Asignaturas',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Mallas') ? [] : ['className' => MallasTable::class];
        $this->Mallas = TableRegistry::getTableLocator()->get('Mallas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Mallas);

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
