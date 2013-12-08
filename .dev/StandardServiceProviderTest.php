<?php
/**
 * Test Class
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Tests;

use Molajo\Service\ConfigurationMock;
use Molajo\IoC\StandardServiceProvider;
use Molajo\Service\ConfigurationMock\ConfigurationMockServiceProvider;
use CommonApi\IoC\ServiceProviderInterface;

/**
 * Initialise
 *
 * @return  object
 * @since   1.0
 */
class StandardServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var $ioc \Molajo\IoC\Container
     */
    protected $service_provider_adapter;

    /**
     * Setup
     *
     * @return  $this
     * @since   1.0
     */
    protected function setUp()
    {
        $options = array();
        $options['name'] = 'ConfigurationMock';
        $options['namespace'] = 'Molajo\ConfigurationMock';
        $this->service_provider_adapter = new StandardServiceProvider($configuration);

        return $this;
    }

    /**
     * @covers Molajo\Ioc\StandardServiceProvider::getServiceName
     */
    public function testgetServiceName()
    {
        $this->assertEquals('ConfigurationMock', $this->service_provider_adapter->getServiceName());

        return $this;
    }

    /**
     * @covers Molajo\Ioc\StandardServiceProvider::getServiceNamespace
     */
    public function testgetServiceNamespace()
    {
        $this->assertEquals('Molajo\\ConfigurationMock', $this->service_provider_adapter->getServiceNamespace());

        return $this;
    }

    /**
     * @covers Molajo\Ioc\StandardServiceProvider::getServiceOptions
     */
    public function testgetServiceOptions()
    {
        $this->assertEquals(array('dog' => 'food'), $this->service_provider_adapter->getServiceOptions());

        return $this;
    }

    /**
     * @covers Molajo\Ioc\StandardServiceProvider::getStoreInstanceIndicator
     */
    public function testgetStoreInstanceIndicator()
    {
        $this->assertEquals(true, $this->service_provider_adapter->getStoreInstanceIndicator());

        return $this;
    }

    /**
     * @covers Molajo\Ioc\StandardServiceProvider::setDependencies
     */
    public function testsetDependencies()
    {
        $this->assertEquals(array(), $this->service_provider_adapter->setDependencies());

        return $this;
    }

    /**
     * @covers Molajo\Ioc\StandardServiceProvider::onBeforeInstantiation
     */
    public function testonBeforeInstantiation()
    {
        $test = is_object($this->service_provider_adapter->onBeforeInstantiation());
        $this->assertEquals(true, $test);

        return $this;
    }

    /**
     * @covers Molajo\Ioc\StandardServiceProvider::instantiateService
     */
    public function testinstantiateService()
    {
        $test = is_object($this->service_provider_adapter->instantiateService());
        $this->assertEquals(true, $test);

        return $this;
    }

    /**
     * @covers Molajo\Ioc\StandardServiceProvider::onAfterInstantiation
     */
    public function testonAfterInstantiation()
    {
        $test = is_object($this->service_provider_adapter->onAfterInstantiation());
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
