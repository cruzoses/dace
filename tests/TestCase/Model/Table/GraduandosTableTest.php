<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GraduandosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GraduandosTable Test Case
 */
class GraduandosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\GraduandosTable
     */
    public $Graduandos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Graduandos',
        'app.Actos',
        'app.Carreras',
        'app.Programas',
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
        $config = TableRegistry::getTableLocator()->exists('Graduandos') ? [] : ['className' => GraduandosTable::class];
        $this->Graduandos = TableRegistry::getTableLocator()->get('Graduandos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Graduandos);

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
