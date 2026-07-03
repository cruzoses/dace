<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SubsistemasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SubsistemasTable Test Case
 */
class SubsistemasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SubsistemasTable
     */
    public $Subsistemas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Subsistemas',
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
        $config = TableRegistry::getTableLocator()->exists('Subsistemas') ? [] : ['className' => SubsistemasTable::class];
        $this->Subsistemas = TableRegistry::getTableLocator()->get('Subsistemas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Subsistemas);

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
