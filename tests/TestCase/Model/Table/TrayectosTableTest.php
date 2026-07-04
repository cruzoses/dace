<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TrayectosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TrayectosTable Test Case
 */
class TrayectosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TrayectosTable
     */
    public $Trayectos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Trayectos',
        'app.Cursos',
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
        $config = TableRegistry::getTableLocator()->exists('Trayectos') ? [] : ['className' => TrayectosTable::class];
        $this->Trayectos = TableRegistry::getTableLocator()->get('Trayectos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Trayectos);

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
