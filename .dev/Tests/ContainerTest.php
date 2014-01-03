<?php
/**
 * Test Class
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\Tests;

use Molajo\ConfigurationMock;
use Molajo\CacheMock;
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
     * @var $ioc \Molajo\IoC\Container
     */
    protected $container;

    /**
     * Setup
     *
     * @return  $this
     * @since   1.0
     */
    protected function setUp()
    {
        $this->container = new ContainerMock();
    }

    /**
     * Instantiates service within GetService
     *
     * @covers Molajo\Ioc\Container::getService
     */
    public function testSetServiceCacheMock()
    {
        $configuration = new ConfigurationMock();

        $options = array();
        if (isset($this->options['foo'])) {
            $options['foo'] = $this->options['foo'];
        }
        if (isset($this->options['bar'])) {
            $options['bar'] = $this->options['bar'];
        }
        if (isset($this->options['baz'])) {
            $options['baz'] = $this->options['baz'];
        }
        $options['configuration'] = $configuration;

        $cache = new CacheMock($options);

        $container_contents = $this->container->setService('Cache', $cache);

        if (isset($container_contents['cache'])) {
            $results = true;
        } else {
            $results = false;
        }

        $this->assertEquals(true, $results);

        return $this;
    }

    /**
     * Instantiates service within GetService
     *
     * @covers Molajo\Ioc\Container::getService
     */
    public function testGetServiceCacheMock()
    {
        $configuration = new ConfigurationMock();

        $options = array();
        if (isset($this->options['foo'])) {
            $options['foo'] = $this->options['foo'];
        }
        if (isset($this->options['bar'])) {
            $options['bar'] = $this->options['bar'];
        }
        if (isset($this->options['baz'])) {
            $options['baz'] = $this->options['baz'];
        }
        $options['configuration'] = $configuration;

        $cache = new CacheMock($options);

        $this->container->setService('Cache', $cache);

        $cache = $this->container->getService('Cache');

        $this->assertEquals(1, $cache->foo);
        $this->assertEquals(2, $cache->bar);
        $this->assertEquals(3, $cache->baz);
        $this->assertTrue(is_object($cache->configuration));

        return $this;
    }

    /**
     * Instantiates service within GetService
     *
     * @covers Molajo\Ioc\Container::getService
     */
    public function testCloneServiceCacheMock()
    {
        $configuration = new ConfigurationMock();

        $options = array();
        if (isset($this->options['foo'])) {
            $options['foo'] = $this->options['foo'];
        }
        if (isset($this->options['bar'])) {
            $options['bar'] = $this->options['bar'];
        }
        if (isset($this->options['baz'])) {
            $options['baz'] = $this->options['baz'];
        }
        $options['configuration'] = $configuration;

        $cache = new CacheMock($options);

        $this->container->setService('Cache', $cache);

        $cache = $this->container->cloneService('Cache');

        $this->assertEquals(1, $cache->foo);
        $this->assertEquals(2, $cache->bar);
        $this->assertEquals(3, $cache->baz);
        $this->assertTrue(is_object($cache->configuration));

        return $this;
    }

    /**
     * Instantiates service within GetService
     *
     * @covers Molajo\Ioc\Container::getService
     */
    public function testRemoveServiceCacheMock()
    {
        $configuration = new ConfigurationMock();

        $options = array();
        if (isset($this->options['foo'])) {
            $options['foo'] = $this->options['foo'];
        }
        if (isset($this->options['bar'])) {
            $options['bar'] = $this->options['bar'];
        }
        if (isset($this->options['baz'])) {
            $options['baz'] = $this->options['baz'];
        }
        $options['configuration'] = $configuration;

        $cache = new CacheMock($options);

        $this->container->setService('Cache', $cache);

        $container_contents = $this->container->removeService('Cache');

        if (isset($container_contents['cache'])) {
            $results = false;
        } else {
            $results = true;
        }

        $this->assertEquals(true, $results);

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

class ContainerMock extends Container
{
    /**
     * Set the existing service instance with the passed in object
     *
     * @param   string      $container_key
     * @param   object      $instance
     * @param   null|string $alias
     *
     * @return  $this
     * @since   1.0
     */
    public function setService($container_key, $instance = null, $alias = null)
    {
        parent::setService($container_key, $instance, $alias);

        return $this->container_registry;
    }

    /**
     * Remove the existing service instance
     *
     * @param   string $container_key
     *
     * @return  $this
     * @since   1.0
     */
    public function removeService($container_key)
    {
        parent::removeService($container_key);

        return $this->container_registry;
    }
}

