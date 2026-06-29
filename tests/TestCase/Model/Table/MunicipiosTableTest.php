<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MunicipiosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MunicipiosTable Test Case
 */
class MunicipiosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MunicipiosTable
     */
    public $Municipios;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Municipios',
        'app.Estados',
        'app.Estudiantes',
        'app.Parroquias',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Municipios') ? [] : ['className' => MunicipiosTable::class];
        $this->Municipios = TableRegistry::getTableLocator()->get('Municipios', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Municipios);

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
