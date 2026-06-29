<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MensionCarrerasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MensionCarrerasTable Test Case
 */
class MensionCarrerasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MensionCarrerasTable
     */
    public $MensionCarreras;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.MensionCarreras',
        'app.Carreras',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('MensionCarreras') ? [] : ['className' => MensionCarrerasTable::class];
        $this->MensionCarreras = TableRegistry::getTableLocator()->get('MensionCarreras', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MensionCarreras);

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
