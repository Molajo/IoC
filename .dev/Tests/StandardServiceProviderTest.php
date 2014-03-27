<?php
/**
 * Test Class
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\Tests;

use Molajo\StandardMock;
use Molajo\IoC\StandardFactoryMethod;
use Molajo\IoC\FactoryMethod;
use Molajo\Factories\ConfigurationMock\ConfigurationMockFactoryMethod;
use CommonApi\IoC\FactoryInterface;

/**
 * Initialise
 *
 * @return  object
 * @since   1.0
 */
class StandardFactoryMethodTest extends \PHPUnit_Framework_TestCase
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
        $options                             = array();
        $options['product_name']             = 'StandardMock';
        $options['product_namespace']        = 'Molajo\\StandardMock';
        $options['store_instance_indicator'] = true;
        $factory_method_adapter              = new StandardFactoryMethod($options);

        $this->factory_method_adapter_adapter = new FactoryMethod($factory_method_adapter);
        return $this;
    }

    /**
     * @covers Molajo\IoC\StandardFactoryMethod::getNamespace
     */
    public function testgetNamespace()
    {
        $this->assertEquals('Molajo\\StandardMock', $this->factory_method_adapter_adapter->getNamespace());

        return $this;
    }

    /**
     * @covers Molajo\IoC\StandardFactoryMethod::getOptions
     */
    public function testgetOptions()
    {
        $this->assertEquals(array(), $this->factory_method_adapter_adapter->getOptions());

        return $this;
    }

    /**
     * @covers Molajo\IoC\StandardFactoryMethod::getStoreContainerEntryIndicator
     */
    public function testgetStoreContainerEntryIndicator()
    {
        $this->assertEquals(true, $this->factory_method_adapter_adapter->getStoreContainerEntryIndicator());

        return $this;
    }

    /**
     * @covers Molajo\IoC\StandardFactoryMethod::setDependencies
     */
    public function testsetDependencies()
    {
        $this->assertEquals(array(), $this->factory_method_adapter_adapter->setDependencies());

        return $this;
    }

    /**
     * @covers Molajo\IoC\StandardFactoryMethod::onBeforeInstantiation
     */
    public function testonBeforeInstantiation()
    {
        $test = is_object($this->factory_method_adapter_adapter->onBeforeInstantiation());
        $this->assertEquals(true, $test);

        return $this;
    }

    /**
     * @covers Molajo\IoC\StandardFactoryMethod::instantiateClass
     */
    public function testinstantiateClass()
    {
        $test = is_object($this->factory_method_adapter_adapter->instantiateClass());
        $this->assertEquals(true, $test);

        return $this;
    }

    /**
     * @covers Molajo\IoC\StandardFactoryMethod::onAfterInstantiation
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
