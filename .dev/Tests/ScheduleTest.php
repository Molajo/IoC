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
     * @covers  Molajo\IoC\Schedule::scheduleFactoryMethod
     * @covers  Molajo\IoC\Schedule::processRequestQueue
     * @covers  Molajo\IoC\Schedule::processRequests
     * @covers  Molajo\IoC\Schedule::processNewRequestQueue
     * @covers  Molajo\IoC\Schedule\Request::setProductRequest
     * @covers  Molajo\IoC\Schedule\Request::setProductRequestWorkObject
     * @covers  Molajo\IoC\Schedule\Request::getNamespace
     * @covers  Molajo\IoC\Schedule\Request::createFactoryMethod
     * @covers  Molajo\IoC\Schedule\Request::setClassDependencies
     * @covers  Molajo\IoC\Schedule\Dependency::satisfyDependencies
     * @covers  Molajo\IoC\Schedule\Dependency::satisfyDependenciesUnset
     * @covers  Molajo\IoC\Schedule\Dependency::satisfyDependency
     * @covers  Molajo\IoC\Schedule\Dependency::addDependencyToQueue
     * @covers  Molajo\IoC\Schedule\Dependency::setProcessRequestsArray
     * @covers  Molajo\IoC\Schedule\Create::processFactoryModel
     * @covers  Molajo\IoC\Schedule\Create::unsetProcessRequestsArray
     * @covers  Molajo\IoC\Schedule\Create::processFactoryModelProductCreate
     * @covers  Molajo\IoC\Schedule\Create::processFactoryModelRemoveContainerEntries
     * @covers  Molajo\IoC\Schedule\Create::processFactoryModelSetContainerEntries
     * @covers  Molajo\IoC\Schedule\Create::processFactoryModelArray
     * @covers  Molajo\IoC\Schedule\Create::processFactoryModelScheduleRequests
     * @covers  Molajo\IoC\Schedule\Create::processFactoryModelSetDependencyOfInstances
     * @covers  Molajo\IoC\Schedule\Base::__construct
     * @covers  Molajo\IoC\Schedule\Base::hasContainerEntry
     * @covers  Molajo\IoC\Schedule\Base::getContainerEntryKey
     * @covers  Molajo\IoC\Schedule\Base::getContainerEntry
     * @covers  Molajo\IoC\Schedule\Base::createContainer
     * @covers  Molajo\IoC\Schedule\Base::createNamespace
     * @covers  Molajo\IoC\Schedule\Base::createClassDependencies
     *
     * @covers  Molajo\IoC\Product\SetNamespace::__construct
     * @covers  Molajo\IoC\Product\SetNamespace::get
     * @covers  Molajo\IoC\Product\SetNamespace::processNamespaceOptions
     * @covers  Molajo\IoC\Product\SetNamespace::getFactoryNamespaceFolderFile
     * @covers  Molajo\IoC\Product\SetNamespace::testFactoryNamespaceFolderFile
     * @covers  Molajo\IoC\Product\SetNamespace::getFolderFile
     * @covers  Molajo\IoC\Product\SetNamespace::checkClassExists
     * @covers  Molajo\IoC\Product\SetNamespace::getLastFolder
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
     * @covers  Molajo\IoC\Container::testLoopEvaluate
     * @covers  Molajo\IoC\Container::setNamespaces
     *
     * @return void
     * @since  1.0.0
     */
    public function setup()
    {
        $factory_method_aliases = readJsonFile(__DIR__ . '/Files/FactoryMethodAliases.json');
        $class_dependencies_filename = __DIR__ . '/Files/ClassDependencies.json';
        $standard_adapter_namespaces = 'Molajo\\IoC\\FactoryMethod\\Standard';

        $this->schedule = new Schedule(
            $factory_method_aliases,
            $class_dependencies_filename,
            $standard_adapter_namespaces
        );
    }

    /**
     * @covers  Molajo\IoC\Schedule::scheduleFactoryMethod
     * @covers  Molajo\IoC\Schedule::processRequestQueue
     * @covers  Molajo\IoC\Schedule::processRequests
     * @covers  Molajo\IoC\Schedule::processNewRequestQueue
     * @covers  Molajo\IoC\Schedule\Request::setProductRequest
     * @covers  Molajo\IoC\Schedule\Request::setProductRequestWorkObject
     * @covers  Molajo\IoC\Schedule\Request::getNamespace
     * @covers  Molajo\IoC\Schedule\Request::createFactoryMethod
     * @covers  Molajo\IoC\Schedule\Request::setClassDependencies
     * @covers  Molajo\IoC\Schedule\Dependency::satisfyDependencies
     * @covers  Molajo\IoC\Schedule\Dependency::satisfyDependenciesUnset
     * @covers  Molajo\IoC\Schedule\Dependency::satisfyDependency
     * @covers  Molajo\IoC\Schedule\Dependency::addDependencyToQueue
     * @covers  Molajo\IoC\Schedule\Dependency::setProcessRequestsArray
     * @covers  Molajo\IoC\Schedule\Create::processFactoryModel
     * @covers  Molajo\IoC\Schedule\Create::unsetProcessRequestsArray
     * @covers  Molajo\IoC\Schedule\Create::processFactoryModelProductCreate
     * @covers  Molajo\IoC\Schedule\Create::processFactoryModelRemoveContainerEntries
     * @covers  Molajo\IoC\Schedule\Create::processFactoryModelSetContainerEntries
     * @covers  Molajo\IoC\Schedule\Create::processFactoryModelArray
     * @covers  Molajo\IoC\Schedule\Create::processFactoryModelScheduleRequests
     * @covers  Molajo\IoC\Schedule\Create::processFactoryModelSetDependencyOfInstances
     * @covers  Molajo\IoC\Schedule\Base::__construct
     * @covers  Molajo\IoC\Schedule\Base::hasContainerEntry
     * @covers  Molajo\IoC\Schedule\Base::getContainerEntryKey
     * @covers  Molajo\IoC\Schedule\Base::getContainerEntry
     * @covers  Molajo\IoC\Schedule\Base::createContainer
     * @covers  Molajo\IoC\Schedule\Base::createNamespace
     * @covers  Molajo\IoC\Schedule\Base::createClassDependencies
     *
     * @covers  Molajo\IoC\Product\SetNamespace::__construct
     * @covers  Molajo\IoC\Product\SetNamespace::get
     * @covers  Molajo\IoC\Product\SetNamespace::processNamespaceOptions
     * @covers  Molajo\IoC\Product\SetNamespace::getFactoryNamespaceFolderFile
     * @covers  Molajo\IoC\Product\SetNamespace::testFactoryNamespaceFolderFile
     * @covers  Molajo\IoC\Product\SetNamespace::getFolderFile
     * @covers  Molajo\IoC\Product\SetNamespace::checkClassExists
     * @covers  Molajo\IoC\Product\SetNamespace::getLastFolder
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
     * @covers  Molajo\IoC\Container::setNamespaces
     * @covers  Molajo\IoC\Container::getKeyNamespace
     * @covers  Molajo\IoC\Container::action
     *
     * @return void
     * @since  1.0.0
     */
    public function testStandardConfiguration()
    {
        $a_stuff = new stdClass();
        $a_stuff->here = 'stuff in the container';

        $class_instance = $this->schedule->scheduleFactoryMethod(
            'EventDispatcher',
            array('product_namespace' => 'Molajo\Event\EventDispatcher')
        );

        $this->assertEquals(get_class($class_instance), 'Molajo\Event\EventDispatcher');
    }

    /**
     * Test Empty configuration
     *
     * @covers  Molajo\IoC\Schedule::scheduleFactoryMethod
     * @covers  Molajo\IoC\Schedule::processRequestQueue
     * @covers  Molajo\IoC\Schedule::processRequests
     * @covers  Molajo\IoC\Schedule::processNewRequestQueue
     * @covers  Molajo\IoC\Schedule\Request::setProductRequest
     * @covers  Molajo\IoC\Schedule\Request::setProductRequestWorkObject
     * @covers  Molajo\IoC\Schedule\Request::getNamespace
     * @covers  Molajo\IoC\Schedule\Request::createFactoryMethod
     * @covers  Molajo\IoC\Schedule\Request::setClassDependencies
     * @covers  Molajo\IoC\Schedule\Dependency::satisfyDependencies
     * @covers  Molajo\IoC\Schedule\Dependency::satisfyDependenciesUnset
     * @covers  Molajo\IoC\Schedule\Dependency::satisfyDependency
     * @covers  Molajo\IoC\Schedule\Dependency::addDependencyToQueue
     * @covers  Molajo\IoC\Schedule\Dependency::setProcessRequestsArray
     * @covers  Molajo\IoC\Schedule\Create::processFactoryModel
     * @covers  Molajo\IoC\Schedule\Create::unsetProcessRequestsArray
     * @covers  Molajo\IoC\Schedule\Create::processFactoryModelProductCreate
     * @covers  Molajo\IoC\Schedule\Create::processFactoryModelRemoveContainerEntries
     * @covers  Molajo\IoC\Schedule\Create::processFactoryModelSetContainerEntries
     * @covers  Molajo\IoC\Schedule\Create::processFactoryModelArray
     * @covers  Molajo\IoC\Schedule\Create::processFactoryModelScheduleRequests
     * @covers  Molajo\IoC\Schedule\Create::processFactoryModelSetDependencyOfInstances
     * @covers  Molajo\IoC\Schedule\Base::__construct
     * @covers  Molajo\IoC\Schedule\Base::hasContainerEntry
     * @covers  Molajo\IoC\Schedule\Base::getContainerEntryKey
     * @covers  Molajo\IoC\Schedule\Base::getContainerEntry
     * @covers  Molajo\IoC\Schedule\Base::createContainer
     * @covers  Molajo\IoC\Schedule\Base::createNamespace
     * @covers  Molajo\IoC\Schedule\Base::createClassDependencies
     *
     * @covers  Molajo\IoC\Product\SetNamespace::__construct
     * @covers  Molajo\IoC\Product\SetNamespace::get
     * @covers  Molajo\IoC\Product\SetNamespace::processNamespaceOptions
     * @covers  Molajo\IoC\Product\SetNamespace::getFactoryNamespaceFolderFile
     * @covers  Molajo\IoC\Product\SetNamespace::testFactoryNamespaceFolderFile
     * @covers  Molajo\IoC\Product\SetNamespace::getFolderFile
     * @covers  Molajo\IoC\Product\SetNamespace::checkClassExists
     * @covers  Molajo\IoC\Product\SetNamespace::getLastFolder
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
     * @covers  Molajo\IoC\Container::setNamespaces
     * @covers  Molajo\IoC\Container::getKeyNamespace
     * @covers  Molajo\IoC\Container::action
     *
     * @return void
     * @since  1.0.0
     */
    public function testHasDependencies()
    {
        $a_stuff = new stdClass();
        $a_stuff->here = 'stuff in the container';

        $class_instance = $this->schedule->scheduleFactoryMethod('Dispatcher');

        $this->assertEquals(get_class($class_instance), 'Molajo\Event\Dispatcher');
    }
}
