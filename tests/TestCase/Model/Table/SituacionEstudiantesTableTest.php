<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SituacionEstudiantesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SituacionEstudiantesTable Test Case
 */
class SituacionEstudiantesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SituacionEstudiantesTable
     */
    public $SituacionEstudiantes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.SituacionEstudiantes',
        'app.Estudiantes',
        'app.Programas',
        'app.Asignaturas',
        'app.Periodos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('SituacionEstudiantes') ? [] : ['className' => SituacionEstudiantesTable::class];
        $this->SituacionEstudiantes = TableRegistry::getTableLocator()->get('SituacionEstudiantes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SituacionEstudiantes);

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
