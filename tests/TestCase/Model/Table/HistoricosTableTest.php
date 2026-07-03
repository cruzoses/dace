<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HistoricosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HistoricosTable Test Case
 */
class HistoricosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\HistoricosTable
     */
    public $Historicos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Historicos',
        'app.Estudiantes',
        'app.Periodos',
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
        $config = TableRegistry::getTableLocator()->exists('Historicos') ? [] : ['className' => HistoricosTable::class];
        $this->Historicos = TableRegistry::getTableLocator()->get('Historicos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Historicos);

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
