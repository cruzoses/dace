<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FirmasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FirmasTable Test Case
 */
class FirmasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FirmasTable
     */
    public $Firmas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Firmas',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Firmas') ? [] : ['className' => FirmasTable::class];
        $this->Firmas = TableRegistry::getTableLocator()->get('Firmas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Firmas);

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
