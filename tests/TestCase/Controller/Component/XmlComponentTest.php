<?php
namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\XmlComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\XmlComponent Test Case
 */
class XmlComponentTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Controller\Component\XmlComponent
     */
    public $Xml;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->Xml = new XmlComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Xml);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
