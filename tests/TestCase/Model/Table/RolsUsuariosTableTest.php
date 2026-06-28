<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RolsUsuariosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RolsUsuariosTable Test Case
 */
class RolsUsuariosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\RolsUsuariosTable
     */
    public $RolsUsuarios;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.RolsUsuarios',
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
        $config = TableRegistry::getTableLocator()->exists('RolsUsuarios') ? [] : ['className' => RolsUsuariosTable::class];
        $this->RolsUsuarios = TableRegistry::getTableLocator()->get('RolsUsuarios', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RolsUsuarios);

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
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
