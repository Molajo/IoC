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
     * @covers Molajo\Service\Type\Application::close
     */
    public function testGetService()
    {
        $results = $this->ioc->getService('Cache');

        $this->assertEquals(1, $results->foo);
        $this->assertEquals(2, $results->bar);
        $this->assertEquals(5, $results->baz);
        $this->assertTrue(is_object($results->configuration));

        return $this;
    }

    /**
     * @covers Molajo\Service\Type\Application::close
     */
    public function DetService()
    {
        $service = 'Cache';
        $options = array();
        $results = $this->ioc->getService($service, $options);

        $this->assertEquals(true, is_object($results));

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
