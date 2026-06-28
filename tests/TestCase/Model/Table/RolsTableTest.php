<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RolsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RolsTable Test Case
 */
class RolsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\RolsTable
     */
    public $Rols;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Rols',
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
        $config = TableRegistry::getTableLocator()->exists('Rols') ? [] : ['className' => RolsTable::class];
        $this->Rols = TableRegistry::getTableLocator()->get('Rols', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Rols);

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
