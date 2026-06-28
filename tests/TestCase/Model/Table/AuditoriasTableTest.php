<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AuditoriasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AuditoriasTable Test Case
 */
class AuditoriasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AuditoriasTable
     */
    public $Auditorias;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Auditorias',
        'app.Usuarios',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Auditorias') ? [] : ['className' => AuditoriasTable::class];
        $this->Auditorias = TableRegistry::getTableLocator()->get('Auditorias', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Auditorias);

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
