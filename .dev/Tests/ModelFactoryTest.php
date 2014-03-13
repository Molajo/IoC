<?php
/**
 * Test Class
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\Tests;

use Molajo\Factories\ConfigurationMock;
use Molajo\IoC\FactoryMethod;
use Molajo\Factories\ConfigurationMock\ConfigurationMockFactoryMethod;
use CommonApi\IoC\FactoryMethodInterface;

/**
 * Initialise
 *
 * @return  object
 * @since   1.0
 */
class FactoryMethodTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var $ioc \Molajo\IoC\Container
     */
    protected $factory_method_adapter_adapter;

    /**
     * Setup
     *
     * @return  $this
     * @since   1.0
     */
    protected function setUp()
    {
        $configuration                        = new ConfigurationMockFactoryMethod();
        $this->factory_method_adapter_adapter = new FactoryMethod($configuration);

        return $this;
    }

    /**
     * @covers Molajo\Ioc\FactoryMethod::getNamespace
     */
    public function testgetNamespace()
    {
        $this->assertEquals('Molajo\\ConfigurationMock', $this->factory_method_adapter_adapter->getNamespace());

        return $this;
    }

    /**
     * @covers Molajo\Ioc\FactoryMethod::getOptions
     */
    public function testgetOptions()
    {
        $this->assertEquals(array('dog' => 'food'), $this->factory_method_adapter_adapter->getOptions());

        return $this;
    }

    /**
     * @covers Molajo\Ioc\FactoryMethod::getStoreContainerEntryIndicator
     */
    public function testgetStoreContainerEntryIndicator()
    {
        $this->assertEquals(true, $this->factory_method_adapter_adapter->getStoreContainerEntryIndicator());

        return $this;
    }

    /**
     * @covers Molajo\Ioc\FactoryMethod::setDependencies
     */
    public function testsetDependencies()
    {
        $this->assertEquals(array(), $this->factory_method_adapter_adapter->setDependencies());

        return $this;
    }

    /**
     * @covers Molajo\Ioc\FactoryMethod::onBeforeInstantiation
     */
    public function testonBeforeInstantiation()
    {
        $test = is_object($this->factory_method_adapter_adapter->onBeforeInstantiation());
        $this->assertEquals(true, $test);

        return $this;
    }

    /**
     * @covers Molajo\Ioc\FactoryMethod::instantiateClass
     */
    public function testinstantiateClass()
    {
        $test = is_object($this->factory_method_adapter_adapter->instantiateClass());
        $this->assertEquals(true, $test);

        return $this;
    }

    /**
     * @covers Molajo\Ioc\FactoryMethod::onAfterInstantiation
     */
    public function testonAfterInstantiation()
    {
        $test = is_object($this->factory_method_adapter_adapter->onAfterInstantiation());
        $this->assertEquals(true, $test);

        return $this;
    }

    /**
     * Tear Down
     *
     * @return  $this
     * @since   1.0
     */
    protected function tearDown()
    {
    }
}
