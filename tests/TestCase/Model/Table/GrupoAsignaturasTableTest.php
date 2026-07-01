<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GrupoAsignaturasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GrupoAsignaturasTable Test Case
 */
class GrupoAsignaturasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\GrupoAsignaturasTable
     */
    public $GrupoAsignaturas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.GrupoAsignaturas',
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
        $config = TableRegistry::getTableLocator()->exists('GrupoAsignaturas') ? [] : ['className' => GrupoAsignaturasTable::class];
        $this->GrupoAsignaturas = TableRegistry::getTableLocator()->get('GrupoAsignaturas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->GrupoAsignaturas);

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
