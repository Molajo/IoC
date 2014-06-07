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
     * @since  1.0.0
     */
    protected $schedule;

    /**
     * Container
     *
     * @var     object CommonApi\IoC\ContainerInterface
     * @since  1.0.0
     */
    protected $container;

    /**
     * Test Empty configuration
     *
     * @covers  Molajo\IoC\Schedule::__construct
     * @covers  Molajo\IoC\Schedule::scheduleFactoryMethod
     * @covers  Molajo\IoC\Schedule::processRequestQueue
     * @covers  Molajo\IoC\Schedule::processRequests
     * @covers  Molajo\IoC\Schedule::processNewRequestQueue
     * @covers  Molajo\IoC\Schedule::setProductRequest
     * @covers  Molajo\IoC\Schedule::setProductRequestWorkObject
     * @covers  Molajo\IoC\Schedule::getFactoryMethodNamespace
     * @covers  Molajo\IoC\Schedule::createFactoryMethod
     * @covers  Molajo\IoC\Schedule::setClassDependencies
     * @covers  Molajo\IoC\Schedule::satisfyDependencies
     * @covers  Molajo\IoC\Schedule::satisfyDependenciesUnset
     * @covers  Molajo\IoC\Schedule::satisfyDependency
     * @covers  Molajo\IoC\Schedule::addDependencyToQueue
     * @covers  Molajo\IoC\Schedule::setProcessRequestsArray
     * @covers  Molajo\IoC\Schedule::processFactoryModel
     * @covers  Molajo\IoC\Schedule::unsetProcessRequestsArray
     * @covers  Molajo\IoC\Schedule::processFactoryModelProductCreate
     * @covers  Molajo\IoC\Schedule::processFactoryModelRemoveContainerEntries
     * @covers  Molajo\IoC\Schedule::processFactoryModelSetContainerEntries
     * @covers  Molajo\IoC\Schedule::processFactoryModelArray
     * @covers  Molajo\IoC\Schedule::processFactoryModelScheduleRequests
     * @covers  Molajo\IoC\Schedule::processFactoryModelSetDependencyOfInstances
     * @covers  Molajo\IoC\Schedule::hasContainerEntry
     * @covers  Molajo\IoC\Schedule::getContainerEntryKey
     * @covers  Molajo\IoC\Schedule::getContainerEntry
     *
     * @covers  Molajo\IoC\FactoryMethodNamespace::__construct
     * @covers  Molajo\IoC\FactoryMethodNamespace::get
     * @covers  Molajo\IoC\FactoryMethodNamespace::processNamespaceOptions
     * @covers  Molajo\IoC\FactoryMethodNamespace::getFactoryNamespaceFolderFile
     * @covers  Molajo\IoC\FactoryMethodNamespace::testFactoryNamespaceFolderFile
     * @covers  Molajo\IoC\FactoryMethodNamespace::getFolderFile
     * @covers  Molajo\IoC\FactoryMethodNamespace::checkClassExists
     * @covers  Molajo\IoC\FactoryMethodNamespace::getLastFolder
     *
     * @covers  Molajo\IoC\FactoryMethodCreate::__construct
     * @covers  Molajo\IoC\FactoryMethodCreate::instantiateFactoryMethod
     * @covers  Molajo\IoC\FactoryMethodCreate::getFactoryMethodAdapter
     * @covers  Molajo\IoC\FactoryMethodCreate::getFactoryMethodController
     *
     * @covers  Molajo\IoC\ClassDependencies::__construct
     * @covers  Molajo\IoC\ClassDependencies::get
     * @covers  Molajo\IoC\ClassDependencies::getReflectionDependencies
     * @covers  Molajo\IoC\ClassDependencies::loadClassDependencies
     * @covers  Molajo\IoC\ClassDependencies::readFile
     * @covers  Molajo\IoC\ClassDependencies::processClassDependencyData
     *
     * @covers  Molajo\IoC\Container::__construct
     * @covers  Molajo\IoC\Container::has
     * @covers  Molajo\IoC\Container::get
     * @covers  Molajo\IoC\Container::set
     * @covers  Molajo\IoC\Container::remove
     * @covers  Molajo\IoC\Container::getKey
     * @covers  Molajo\IoC\Container::action
     * @covers  Molajo\IoC\Container::createNewKey
     * @covers  Molajo\IoC\Container::testAlias
     * @covers  Molajo\IoC\Container::testAliasKey
     * @covers  Molajo\IoC\Container::testContainerKey
     * @covers  Molajo\IoC\Container::getKeyNamespace
     * @covers  Molajo\IoC\Container::testLoop
     * @covers  Molajo\IoC\Container::setFactoryMethodNamespaces
     *
     * @return void
     * @since  1.0.0
     */
    public function setup()
    {
        $factory_method_aliases = readJsonFile(__DIR__ . '/Files/FactoryMethodAliases.json');
        $class_dependencies = __DIR__ . '/Files/ClassDependencies.json';
        $class_dependencies_filename = null;
        $standard_adapter_namespaces = null;

        $this->schedule = new Schedule(
            $this->container,
            $class_dependencies,
            $standard_adapter_namespaces
        );

        $a_stuff = new stdClass();
        $a_stuff->here = 'stuff in the container';

        $this->container->set('Molajo\\Factories\\User', $a_stuff);
        $this->container->set('Email', $a_stuff);
        $this->container->set('Noalias', $a_stuff);
    }

    /**
     * Test Empty configuration
     *
     * @covers  Molajo\IoC\Schedule::__construct
     * @covers  Molajo\IoC\Schedule::scheduleFactoryMethod
     * @covers  Molajo\IoC\Schedule::processRequestQueue
     * @covers  Molajo\IoC\Schedule::processRequests
     * @covers  Molajo\IoC\Schedule::processNewRequestQueue
     * @covers  Molajo\IoC\Schedule::setProductRequest
     * @covers  Molajo\IoC\Schedule::setProductRequestWorkObject
     * @covers  Molajo\IoC\Schedule::getFactoryMethodNamespace
     * @covers  Molajo\IoC\Schedule::createFactoryMethod
     * @covers  Molajo\IoC\Schedule::setClassDependencies
     * @covers  Molajo\IoC\Schedule::satisfyDependencies
     * @covers  Molajo\IoC\Schedule::satisfyDependenciesUnset
     * @covers  Molajo\IoC\Schedule::satisfyDependency
     * @covers  Molajo\IoC\Schedule::addDependencyToQueue
     * @covers  Molajo\IoC\Schedule::setProcessRequestsArray
     * @covers  Molajo\IoC\Schedule::processFactoryModel
     * @covers  Molajo\IoC\Schedule::unsetProcessRequestsArray
     * @covers  Molajo\IoC\Schedule::processFactoryModelProductCreate
     * @covers  Molajo\IoC\Schedule::processFactoryModelRemoveContainerEntries
     * @covers  Molajo\IoC\Schedule::processFactoryModelSetContainerEntries
     * @covers  Molajo\IoC\Schedule::processFactoryModelArray
     * @covers  Molajo\IoC\Schedule::processFactoryModelScheduleRequests
     * @covers  Molajo\IoC\Schedule::processFactoryModelSetDependencyOfInstances
     * @covers  Molajo\IoC\Schedule::hasContainerEntry
     * @covers  Molajo\IoC\Schedule::getContainerEntryKey
     * @covers  Molajo\IoC\Schedule::getContainerEntry
     *
     * @covers  Molajo\IoC\FactoryMethodNamespace::__construct
     * @covers  Molajo\IoC\FactoryMethodNamespace::get
     * @covers  Molajo\IoC\FactoryMethodNamespace::processNamespaceOptions
     * @covers  Molajo\IoC\FactoryMethodNamespace::getFactoryNamespaceFolderFile
     * @covers  Molajo\IoC\FactoryMethodNamespace::testFactoryNamespaceFolderFile
     * @covers  Molajo\IoC\FactoryMethodNamespace::getFolderFile
     * @covers  Molajo\IoC\FactoryMethodNamespace::checkClassExists
     * @covers  Molajo\IoC\FactoryMethodNamespace::getLastFolder
     *
     * @covers  Molajo\IoC\FactoryMethodCreate::__construct
     * @covers  Molajo\IoC\FactoryMethodCreate::instantiateFactoryMethod
     * @covers  Molajo\IoC\FactoryMethodCreate::getFactoryMethodAdapter
     * @covers  Molajo\IoC\FactoryMethodCreate::getFactoryMethodController
     *
     * @covers  Molajo\IoC\ClassDependencies::__construct
     * @covers  Molajo\IoC\ClassDependencies::get
     * @covers  Molajo\IoC\ClassDependencies::getReflectionDependencies
     * @covers  Molajo\IoC\ClassDependencies::loadClassDependencies
     * @covers  Molajo\IoC\ClassDependencies::readFile
     * @covers  Molajo\IoC\ClassDependencies::processClassDependencyData
     *
     * @covers  Molajo\IoC\Container::__construct
     * @covers  Molajo\IoC\Container::has
     * @covers  Molajo\IoC\Container::get
     * @covers  Molajo\IoC\Container::set
     * @covers  Molajo\IoC\Container::remove
     * @covers  Molajo\IoC\Container::getKey
     * @covers  Molajo\IoC\Container::testAlias
     * @covers  Molajo\IoC\Container::testAliasKey
     * @covers  Molajo\IoC\Container::testContainerKey
     * @covers  Molajo\IoC\Container::setFactoryMethodNamespaces
     * @covers  Molajo\IoC\Container::getKeyNamespace
     * @covers  Molajo\IoC\Container::action
     *
     * @return void
     * @since  1.0.0
     */
    public function testLoadedContainerEntries()
    {
        $a_stuff = new stdClass();
        $a_stuff->here = 'stuff in the container';

        $this->assertEquals($this->schedule->scheduleFactoryMethod('Molajo\\Factories\\User'), $a_stuff);
        $this->assertEquals($this->schedule->scheduleFactoryMethod('User'), $a_stuff);
    }

    /**
     * Test Empty configuration
     *
     * @covers  Molajo\IoC\Schedule::__construct
     * @covers  Molajo\IoC\Schedule::scheduleFactoryMethod
     * @covers  Molajo\IoC\Schedule::processRequestQueue
     * @covers  Molajo\IoC\Schedule::processRequests
     * @covers  Molajo\IoC\Schedule::processNewRequestQueue
     * @covers  Molajo\IoC\Schedule::setProductRequest
     * @covers  Molajo\IoC\Schedule::setProductRequestWorkObject
     * @covers  Molajo\IoC\Schedule::getFactoryMethodNamespace
     * @covers  Molajo\IoC\Schedule::createFactoryMethod
     * @covers  Molajo\IoC\Schedule::setClassDependencies
     * @covers  Molajo\IoC\Schedule::satisfyDependencies
     * @covers  Molajo\IoC\Schedule::satisfyDependenciesUnset
     * @covers  Molajo\IoC\Schedule::satisfyDependency
     * @covers  Molajo\IoC\Schedule::addDependencyToQueue
     * @covers  Molajo\IoC\Schedule::setProcessRequestsArray
     * @covers  Molajo\IoC\Schedule::processFactoryModel
     * @covers  Molajo\IoC\Schedule::unsetProcessRequestsArray
     * @covers  Molajo\IoC\Schedule::processFactoryModelProductCreate
     * @covers  Molajo\IoC\Schedule::processFactoryModelRemoveContainerEntries
     * @covers  Molajo\IoC\Schedule::processFactoryModelSetContainerEntries
     * @covers  Molajo\IoC\Schedule::processFactoryModelArray
     * @covers  Molajo\IoC\Schedule::processFactoryModelScheduleRequests
     * @covers  Molajo\IoC\Schedule::processFactoryModelSetDependencyOfInstances
     * @covers  Molajo\IoC\Schedule::hasContainerEntry
     * @covers  Molajo\IoC\Schedule::getContainerEntryKey
     * @covers  Molajo\IoC\Schedule::getContainerEntry
     *
     * @covers  Molajo\IoC\FactoryMethodNamespace::__construct
     * @covers  Molajo\IoC\FactoryMethodNamespace::get
     * @covers  Molajo\IoC\FactoryMethodNamespace::processNamespaceOptions
     * @covers  Molajo\IoC\FactoryMethodNamespace::getFactoryNamespaceFolderFile
     * @covers  Molajo\IoC\FactoryMethodNamespace::testFactoryNamespaceFolderFile
     * @covers  Molajo\IoC\FactoryMethodNamespace::getFolderFile
     * @covers  Molajo\IoC\FactoryMethodNamespace::checkClassExists
     * @covers  Molajo\IoC\FactoryMethodNamespace::getLastFolder
     *
     * @covers  Molajo\IoC\FactoryMethodCreate::__construct
     * @covers  Molajo\IoC\FactoryMethodCreate::instantiateFactoryMethod
     * @covers  Molajo\IoC\FactoryMethodCreate::getFactoryMethodAdapter
     * @covers  Molajo\IoC\FactoryMethodCreate::getFactoryMethodController
     *
     * @covers  Molajo\IoC\ClassDependencies::__construct
     * @covers  Molajo\IoC\ClassDependencies::get
     * @covers  Molajo\IoC\ClassDependencies::getReflectionDependencies
     * @covers  Molajo\IoC\ClassDependencies::loadClassDependencies
     * @covers  Molajo\IoC\ClassDependencies::readFile
     * @covers  Molajo\IoC\ClassDependencies::processClassDependencyData
     *
     * @covers  Molajo\IoC\Container::__construct
     * @covers  Molajo\IoC\Container::has
     * @covers  Molajo\IoC\Container::get
     * @covers  Molajo\IoC\Container::set
     * @covers  Molajo\IoC\Container::remove
     * @covers  Molajo\IoC\Container::getKey
     * @covers  Molajo\IoC\Container::testAlias
     * @covers  Molajo\IoC\Container::testAliasKey
     * @covers  Molajo\IoC\Container::testContainerKey
     * @covers  Molajo\IoC\Container::setFactoryMethodNamespaces
     * @covers  Molajo\IoC\Container::getKeyNamespace
     * @covers  Molajo\IoC\Container::action
     *
     * @return void
     * @since  1.0.0
     */
    public function testDispatcher()
    {
        $a_stuff = new stdClass();
        $a_stuff->here = 'stuff in the container';

        $class_instance = $this->schedule->scheduleFactoryMethod('Dispatcher');

        $this->assertEquals(get_class($class_instance), 'Molajo\Event\EventDispatcher');
    }
}
