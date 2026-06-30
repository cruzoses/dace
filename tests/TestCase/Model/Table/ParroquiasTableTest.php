<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ParroquiasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ParroquiasTable Test Case
 */
class ParroquiasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ParroquiasTable
     */
    public $Parroquias;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Parroquias',
        'app.Municipios',
        'app.Estudiantes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Parroquias') ? [] : ['className' => ParroquiasTable::class];
        $this->Parroquias = TableRegistry::getTableLocator()->get('Parroquias', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Parroquias);

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
