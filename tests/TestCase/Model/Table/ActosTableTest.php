<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ActosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ActosTable Test Case
 */
class ActosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ActosTable
     */
    public $Actos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Actos',
        'app.Graduandos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Actos') ? [] : ['className' => ActosTable::class];
        $this->Actos = TableRegistry::getTableLocator()->get('Actos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Actos);

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
