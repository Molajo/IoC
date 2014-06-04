<?php
/**
 * Schedule Test
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\IoC;

use CommonApi\Exception\RuntimeException;
use CommonApi\IoC\ScheduleInterface;
use CommonApi\IoC\ContainerInterface;
use stdClass;
use PHPUnit_Framework_TestCase;

require_once __DIR__ . '/Files/jsonRead.php';

/**
 * Schedule Test
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
class ScheduleTest extends PHPUnit_Framework_TestCase
{
    /**
     * Object to test
     *
     * @var     object CommonApi\IoC\ScheduleInterface
     * @since   1.0
     */
    protected $schedule;

    /**
     * Container
     *
     * @var     object CommonApi\IoC\ContainerInterface
     * @since   1.0
     */
    protected $container;

    /**
     * Test Empty configuration
     *
     * @covers  Molajo\IoC\Container::__construct
     * @covers  Molajo\IoC\Container::has
     * @covers  Molajo\IoC\Container::get
     * @covers  Molajo\IoC\Container::set
     * @covers  Molajo\IoC\Container::remove
     *
     * @covers  Molajo\IoC\Schedule::__construct
     * @covers  Molajo\IoC\Schedule::scheduleFactoryMethod
     * @covers  Molajo\IoC\Schedule::setWorkObject
     * @covers  Molajo\IoC\Schedule::instantiateFactoryMethod
     * @covers  Molajo\IoC\Schedule::setContainerKey
     * @covers  Molajo\IoC\Schedule::completeRequest
     * @covers  Molajo\IoC\Schedule::satisfyDependency
     * @covers  Molajo\IoC\Schedule::getFactoryMethod
     * @covers  Molajo\IoC\Schedule::getFactoryMethodAdapter
     * @covers  Molajo\IoC\Schedule::hasContainerEntry
     * @covers  Molajo\IoC\Schedule::getContainerEntry
     * @covers  Molajo\IoC\Schedule::loadClassDependencies
     *
     * @return void
     * @since   1.0
     */
    public function setup()
    {
        $aliases = readJsonFile(__DIR__ . '/Files/FactoryMethodAliases.json');
        $this->container = new Container($aliases);

        $a_stuff = new stdClass();
        $a_stuff->here = 'stuff in the container';

        $this->container->set('Molajo\\Factories\\Dispatcher', $a_stuff);
        $this->container->set('Email', $a_stuff);
        $this->container->set('Noalias', $a_stuff);

        $class_dependencies_filename = null;
        $standard_adapter_namespaces = null;

        $this->schedule = new Schedule(
            $this->container,
            $class_dependencies_filename,
            $standard_adapter_namespaces
        );
    }

    /**
     * Test Empty configuration
     *
     * @covers  Molajo\IoC\Container::__construct
     * @covers  Molajo\IoC\Container::has
     * @covers  Molajo\IoC\Container::get
     * @covers  Molajo\IoC\Container::set
     * @covers  Molajo\IoC\Container::remove
     *
     * @covers  Molajo\IoC\Schedule::__construct
     * @covers  Molajo\IoC\Schedule::scheduleFactoryMethod
     * @covers  Molajo\IoC\Schedule::setWorkObject
     * @covers  Molajo\IoC\Schedule::instantiateFactoryMethod
     * @covers  Molajo\IoC\Schedule::setContainerKey
     * @covers  Molajo\IoC\Schedule::completeRequest
     * @covers  Molajo\IoC\Schedule::satisfyDependency
     * @covers  Molajo\IoC\Schedule::getFactoryMethod
     * @covers  Molajo\IoC\Schedule::getFactoryMethodAdapter
     * @covers  Molajo\IoC\Schedule::hasContainerEntry
     * @covers  Molajo\IoC\Schedule::getContainerEntry
     * @covers  Molajo\IoC\Schedule::loadClassDependencies
     *
     * @return void
     * @since   1.0
     */
    public function testLoadedContainerEntries()
    {
        $a_stuff = new stdClass();
        $a_stuff->here = 'stuff in the container';

        $this->assertEquals($this->schedule->scheduleFactoryMethod('Molajo\\Factories\\Dispatcher'), $a_stuff);
        $this->assertEquals($this->schedule->scheduleFactoryMethod('Dispatcher'), $a_stuff);
    }
}
