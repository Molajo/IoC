<?php
/**
 * Test Class
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Tests;

use Molajo\Services\Cache;
use Molajo\Services\Configuration;
use Molajo\IoC\Container;

/**
 * Initialise
 *
 * @return  object
 * @since   1.0
 */
class ContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var object
     */
    protected $ioc;

    /**
     * Setup
     *
     * @return  $this
     * @since   1.0
     */
    protected function setUp()
    {
        $connect       = $this;
        $getService    = function ($service, $options = array()) use ($connect) {
            return $connect->getService($service, $options);
        };
        $setService    = function ($service, $instance) use ($connect) {
            return $connect->setService($service, $instance);
        };
        $cloneService  = function ($service) use ($connect) {
            return $connect->cloneService($service);
        };
        $removeService = function ($service) use ($connect) {
            return $connect->removeService($service);
        };

        $services_folder = 'Molajo\\Services';

        $this->ioc = new Container($getService, $setService, $cloneService, $removeService, $services_folder);
    }

    /**
     * Instantiates service within GetService
     * @covers Molajo\Ioc\Container::getService
     */
    public function testGetService1()
    {
        $results = $this->ioc->getService('Cache');

        $this->assertEquals(1, $results->foo);
        $this->assertEquals(2, $results->bar);
        $this->assertEquals(5, $results->baz);
        $this->assertTrue(is_object($results->configuration));

        return $this;
    }

    /**
     * Sets option values
     * @covers Molajo\Ioc\Container::getService
     */
    public function testGetService2()
    {
        $options = array();
        $options['foo'] = 10;
        $options['bar'] = 20;
        $options['baz'] = 30;

        $results = $this->ioc->getService('Cache', $options);

        $this->assertEquals(10, $results->foo);
        $this->assertEquals(20, $results->bar);
        $this->assertEquals(5, $results->baz);
        $this->assertTrue(is_object($results->configuration));

        return $this;
    }

    /**
     * Get the Configuration Class - stores the instance, check if exists
     * @covers Molajo\Ioc\Container::getService
     */
    public function testGetService3()
    {
        $results = $this->ioc->getService('Configuration');

        $options = array();
        $options['if_exists'] = true;
        $second = $this->ioc->getService('Configuration', $options);

        $this->assertTrue(is_object($results));
        $this->assertTrue(is_object($second));
        $this->assertEquals($second, $results);

        return $this;
    }

    /**
     * Get the Configuration Class if exists - does not exist so null should be returned
     * @covers Molajo\Ioc\Container::getService
     */
    public function testGetService4()
    {
        $options = array();
        $options['if_exists'] = true;
        $results = $this->ioc->getService('Configuration', $options);

        $this->assertFalse(is_object($results));
        $this->assertEquals(null, $results);

        return $this;
    }

    /**
     * Get the Configuration Class - stores the instance, clone
     * @covers Molajo\Ioc\Container::cloneService
     */
    public function testCloneService1()
    {
        $results = $this->ioc->getService('Configuration');

        $second = $this->ioc->cloneService('Configuration');

        $this->assertTrue(is_object($results));
        $this->assertTrue(is_object($second));
        $this->assertEquals($second, $results);

        return $this;
    }

    /**
     * Get the Configuration Class - store the instance, remove
     * @covers Molajo\Ioc\Container::removeService
     */
    public function testSetService1()
    {
        $config = $this->ioc->getService('Configuration');

        $this->ioc->setService('Cache', $config);

        $second = $this->ioc->getService('Cache');

        $this->assertTrue(is_object($config));
        $this->assertTrue(is_object($second));
        $this->assertEquals($second, $config);

        return $this;
    }

    /**
     * Get the Configuration Class - store the instance, remove
     * @covers Molajo\Ioc\Container::removeService
     */
    public function testRemoveService1()
    {
        $results = $this->ioc->getService('Configuration');

        $second = $this->ioc->removeService('Configuration');

        $options = array();
        $options['if_exists'] = true;
        $ifexists = $this->ioc->getService('Configuration', $options);

        $this->assertFalse(is_object($ifexists));
        $this->assertEquals(null, $ifexists);

        return $this;
    }

    /**
     * Get the Configuration Service Class - store the instance
     * Test Standard Injector - Molajo/Configuration class
     * @covers Molajo\Ioc\Container::removeService
     */
    public function testStandardConfiguration()
    {
        $config = $this->ioc->getService('Configuration');

        $config2 = $this->ioc->getService('Molajo\\Configuration');

        $this->assertTrue(is_object($config));
        $this->assertTrue(is_object($config2));
        $this->assertEquals($config, $config2);

        return $this;
    }

    /**
     * Get the Configuration Class - store the instance, remove
     * @covers Molajo\Ioc\Container::removeService
     */
    public function testStandard()
    {
        $standard = $this->ioc->getService('Molajo\\Standard');

        $this->assertEquals(1, $standard->foo);
        $this->assertEquals(2, $standard->bar);
        $this->assertEquals(3, $standard->baz);

        return $this;
    }


    /**
     * Get the Configuration Class - store the instance, remove
     * @covers Molajo\Ioc\Container::removeService
     */
    public function testStandard2()
    {
        $options = array();
        $options['foo'] = 10;
        $options['bar'] = 20;
        $options['baz'] = 30;

        $standard = $this->ioc->getService('Molajo\\Standard', $options);

        $this->assertEquals(10, $standard->foo);
        $this->assertEquals(20, $standard->bar);
        $this->assertEquals(30, $standard->baz);

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

    /**
     * Get a service instance
     *
     * @param    string $service
     * @param    array  $options
     *
     * @results  null|object
     * @since    1.0
     * @throws   FrontControllerException
     */
    public function getService($service, $options = array())
    {
        return $this->ioc->getService($service, $options);
    }

    /**
     * Replace the existing service instance
     *
     * @param    string $service
     * @param    object $instance
     *
     * @results  $this
     * @since    1.0
     * @throws   FrontControllerException
     */
    public function setService($service, $instance = null)
    {
        $this->ioc->getService($service, $instance);

        return $this;
    }

    /**
     * Clone the existing service instance
     *
     * @param    string $service
     *
     * @results  null|object
     * @since    1.0
     * @throws   FrontControllerException
     */
    public function cloneService($service)
    {
        return $this->ioc->cloneService($service);
    }

    /**
     * Remove the existing service instance
     *
     * @param    string $service
     *
     * @results  $this
     * @since    1.0
     */
    public function removeService($service)
    {
        $this->ioc->removeService($service);

        return $this;
    }
}
