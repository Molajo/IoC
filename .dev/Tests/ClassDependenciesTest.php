<?php
/**
 * Class Dependencies Test
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\IoC;

use stdClass;
use PHPUnit_Framework_TestCase;

/**
 * Class Dependencies Test
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
class ClassDependenciesTest extends PHPUnit_Framework_TestCase
{
    /**
     * Class Dependencies by QCN
     *
     * @var    array
     * @since  1.0.0
     */
    protected $class_dependencies;

    /**
     * @covers  Molajo\IoC\ClassDependencies::__construct
     * @covers  Molajo\IoC\ClassDependencies::get
     * @covers  Molajo\IoC\ClassDependencies::getReflectionDependencies
     * @covers  Molajo\IoC\ClassDependencies::loadClassDependencies
     * @covers  Molajo\IoC\ClassDependencies::readFile
     * @covers  Molajo\IoC\ClassDependencies::processClassDependencyData
     *
     * @covers  Molajo\IoC\FactoryMethodCreate::__construct
     * @covers  Molajo\IoC\FactoryMethodCreate::instantiateFactoryMethod
     * @covers  Molajo\IoC\FactoryMethodCreate::getFactoryMethodAdapter
     * @covers  Molajo\IoC\FactoryMethodCreate::getFactoryMethodController
     *
     * @covers  Molajo\IoC\FactoryMethodController::__construct
     * @covers  Molajo\IoC\FactoryMethodController::getNamespace
     * @covers  Molajo\IoC\FactoryMethodController::getOptions
     * @covers  Molajo\IoC\FactoryMethodController::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\FactoryMethodController::setDependencies
     * @covers  Molajo\IoC\FactoryMethodController::removeDependency
     * @covers  Molajo\IoC\FactoryMethodController::setDependencyValue
     * @covers  Molajo\IoC\FactoryMethodController::getRemainingDependencyCount
     * @covers  Molajo\IoC\FactoryMethodController::onBeforeInstantiation
     * @covers  Molajo\IoC\FactoryMethodController::instantiateClass
     * @covers  Molajo\IoC\FactoryMethodController::onAfterInstantiation
     * @covers  Molajo\IoC\FactoryMethodController::getProductValue
     * @covers  Molajo\IoC\FactoryMethodController::removeContainerEntries
     * @covers  Molajo\IoC\FactoryMethodController::setContainerEntries
     * @covers  Molajo\IoC\FactoryMethodController::scheduleFactories
     *
     * @covers  Molajo\IoC\StandardFactoryMethod::__construct
     * @covers  Molajo\IoC\FactoryMethodBase::getNamespace
     * @covers  Molajo\IoC\FactoryMethodBase::getOptions
     * @covers  Molajo\IoC\FactoryMethodBase::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\FactoryMethodBase::setDependencies
     * @covers  Molajo\IoC\FactoryMethodBase::onBeforeInstantiation
     * @covers  Molajo\IoC\FactoryMethodBase::instantiateClass
     * @covers  Molajo\IoC\FactoryMethodBase::instantiateStatic
     * @covers  Molajo\IoC\FactoryMethodBase::onAfterInstantiation
     * @covers  Molajo\IoC\FactoryMethodBase::getProductValue
     * @covers  Molajo\IoC\FactoryMethodBase::removeContainerEntries
     * @covers  Molajo\IoC\FactoryMethodBase::setContainerEntries
     * @covers  Molajo\IoC\FactoryMethodBase::scheduleFactories
     * @covers  Molajo\IoC\FactoryMethodBase::readFile
     * @covers  Molajo\IoC\FactoryMethodBase::sortObject
     *
     * @return void
     * @since   1.0.0
     */
    public function setup()
    {
        $class_dependencies       = __DIR__ . '/Files/ClassDependencies.json';
        $this->class_dependencies = new ClassDependencies($class_dependencies);
    }

    /**
     * @covers  Molajo\IoC\ClassDependencies::__construct
     * @covers  Molajo\IoC\ClassDependencies::get
     * @covers  Molajo\IoC\ClassDependencies::getReflectionDependencies
     * @covers  Molajo\IoC\ClassDependencies::loadClassDependencies
     * @covers  Molajo\IoC\ClassDependencies::readFile
     * @covers  Molajo\IoC\ClassDependencies::processClassDependencyData
     *
     * @covers  Molajo\IoC\FactoryMethodCreate::__construct
     * @covers  Molajo\IoC\FactoryMethodCreate::instantiateFactoryMethod
     * @covers  Molajo\IoC\FactoryMethodCreate::getFactoryMethodAdapter
     * @covers  Molajo\IoC\FactoryMethodCreate::getFactoryMethodController
     *
     * @covers  Molajo\IoC\FactoryMethodController::__construct
     * @covers  Molajo\IoC\FactoryMethodController::getNamespace
     * @covers  Molajo\IoC\FactoryMethodController::getOptions
     * @covers  Molajo\IoC\FactoryMethodController::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\FactoryMethodController::setDependencies
     * @covers  Molajo\IoC\FactoryMethodController::removeDependency
     * @covers  Molajo\IoC\FactoryMethodController::setDependencyValue
     * @covers  Molajo\IoC\FactoryMethodController::getRemainingDependencyCount
     * @covers  Molajo\IoC\FactoryMethodController::onBeforeInstantiation
     * @covers  Molajo\IoC\FactoryMethodController::instantiateClass
     * @covers  Molajo\IoC\FactoryMethodController::onAfterInstantiation
     * @covers  Molajo\IoC\FactoryMethodController::getProductValue
     * @covers  Molajo\IoC\FactoryMethodController::removeContainerEntries
     * @covers  Molajo\IoC\FactoryMethodController::setContainerEntries
     * @covers  Molajo\IoC\FactoryMethodController::scheduleFactories
     *
     * @covers  Molajo\IoC\StandardFactoryMethod::__construct
     * @covers  Molajo\IoC\FactoryMethodBase::getNamespace
     * @covers  Molajo\IoC\FactoryMethodBase::getOptions
     * @covers  Molajo\IoC\FactoryMethodBase::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\FactoryMethodBase::setDependencies
     * @covers  Molajo\IoC\FactoryMethodBase::onBeforeInstantiation
     * @covers  Molajo\IoC\FactoryMethodBase::instantiateClass
     * @covers  Molajo\IoC\FactoryMethodBase::instantiateStatic
     * @covers  Molajo\IoC\FactoryMethodBase::onAfterInstantiation
     * @covers  Molajo\IoC\FactoryMethodBase::getProductValue
     * @covers  Molajo\IoC\FactoryMethodBase::removeContainerEntries
     * @covers  Molajo\IoC\FactoryMethodBase::setContainerEntries
     * @covers  Molajo\IoC\FactoryMethodBase::scheduleFactories
     * @covers  Molajo\IoC\FactoryMethodBase::readFile
     * @covers  Molajo\IoC\FactoryMethodBase::sortObject
     *
     * @return  void
     * @since  1.0.0
     */
    public function testClassWithReflectionDependencies()
    {
        $options                             = array();
        $options['product_name']             = 'Dispatcher';
        $options['container_key']            = 'Molajo\\Events\\Dispatcher';
        $options['ioc_id']                   = 1;
        $options['factory_method_namespace'] = 'Molajo\\Factories\\Dispatcher\\DispatcherFactoryMethod';

        $create = new FactoryMethodCreate($options);

        $work_object                 = new stdClass();
        $work_object->options        = $options;
        $work_object->factory_method = $create->instantiateFactoryMethod();

        $work_object = $this->class_dependencies->get($work_object);

        $this->assertEquals(count($work_object->reflection), 1);
        $this->assertEquals($work_object->reflection[0]->name, 'event_dispatcher');
        $this->assertEquals($work_object->reflection[0]->default_available, false);
        $this->assertEquals($work_object->reflection[0]->default_value, null);
        $this->assertEquals($work_object->reflection[0]->instance_of, 'CommonApi\Event\EventDispatcherInterface');
        $this->assertEquals($work_object->reflection[0]->is_instantiable, false);
        $this->assertEquals($work_object->reflection[0]->implemented_by[0], 'Molajo\Event\EventDispatcher');
        $this->assertEquals($work_object->reflection[0]->concrete, false);

        $this->assertEquals(count($work_object->dependencies), 1);
        $this->assertEquals(
            $work_object->dependencies['Event_dispatcher']['product_namespace'],
            'Molajo\Event\EventDispatcher'
        );
        $this->assertEquals(
            $work_object->dependencies['Event_dispatcher']['product_name'],
            'Event_dispatcher'
        );
    }

    /**
     * @covers  Molajo\IoC\ClassDependencies::__construct
     * @covers  Molajo\IoC\ClassDependencies::get
     * @covers  Molajo\IoC\ClassDependencies::getReflectionDependencies
     * @covers  Molajo\IoC\ClassDependencies::loadClassDependencies
     * @covers  Molajo\IoC\ClassDependencies::readFile
     * @covers  Molajo\IoC\ClassDependencies::processClassDependencyData
     *
     * @covers  Molajo\IoC\FactoryMethodCreate::__construct
     * @covers  Molajo\IoC\FactoryMethodCreate::instantiateFactoryMethod
     * @covers  Molajo\IoC\FactoryMethodCreate::getFactoryMethodAdapter
     * @covers  Molajo\IoC\FactoryMethodCreate::getFactoryMethodController
     *
     * @covers  Molajo\IoC\FactoryMethodController::__construct
     * @covers  Molajo\IoC\FactoryMethodController::getNamespace
     * @covers  Molajo\IoC\FactoryMethodController::getOptions
     * @covers  Molajo\IoC\FactoryMethodController::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\FactoryMethodController::setDependencies
     * @covers  Molajo\IoC\FactoryMethodController::removeDependency
     * @covers  Molajo\IoC\FactoryMethodController::setDependencyValue
     * @covers  Molajo\IoC\FactoryMethodController::getRemainingDependencyCount
     * @covers  Molajo\IoC\FactoryMethodController::onBeforeInstantiation
     * @covers  Molajo\IoC\FactoryMethodController::instantiateClass
     * @covers  Molajo\IoC\FactoryMethodController::onAfterInstantiation
     * @covers  Molajo\IoC\FactoryMethodController::getProductValue
     * @covers  Molajo\IoC\FactoryMethodController::removeContainerEntries
     * @covers  Molajo\IoC\FactoryMethodController::setContainerEntries
     * @covers  Molajo\IoC\FactoryMethodController::scheduleFactories
     *
     * @covers  Molajo\IoC\StandardFactoryMethod::__construct
     * @covers  Molajo\IoC\FactoryMethodBase::getNamespace
     * @covers  Molajo\IoC\FactoryMethodBase::getOptions
     * @covers  Molajo\IoC\FactoryMethodBase::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\FactoryMethodBase::setDependencies
     * @covers  Molajo\IoC\FactoryMethodBase::onBeforeInstantiation
     * @covers  Molajo\IoC\FactoryMethodBase::instantiateClass
     * @covers  Molajo\IoC\FactoryMethodBase::instantiateStatic
     * @covers  Molajo\IoC\FactoryMethodBase::onAfterInstantiation
     * @covers  Molajo\IoC\FactoryMethodBase::getProductValue
     * @covers  Molajo\IoC\FactoryMethodBase::removeContainerEntries
     * @covers  Molajo\IoC\FactoryMethodBase::setContainerEntries
     * @covers  Molajo\IoC\FactoryMethodBase::scheduleFactories
     * @covers  Molajo\IoC\FactoryMethodBase::readFile
     * @covers  Molajo\IoC\FactoryMethodBase::sortObject
     *
     * @return  void
     * @since  1.0.0
     */
    public function testClassWithoutReflectionDependencies()
    {
        $options                             = array();
        $options['product_name']             = 'EventDispatcher';
        $options['container_key']            = 'Molajo\\Events\\EventDispatcher';
        $options['ioc_id']                   = 1;
        $options['factory_method_namespace'] = 'Molajo\\IoC\\StandardFactoryMethod';

        $create = new FactoryMethodCreate($options);

        $work_object                 = new stdClass();
        $work_object->options        = $options;
        $work_object->factory_method = $create->instantiateFactoryMethod();

        $work_object = $this->class_dependencies->get($work_object);

        $this->assertEquals(count($work_object->reflection), 0);
        $this->assertEquals(count($work_object->dependencies), 0);
    }

    /**
     * @covers  Molajo\IoC\ClassDependencies::__construct
     * @covers  Molajo\IoC\ClassDependencies::get
     * @covers  Molajo\IoC\ClassDependencies::getReflectionDependencies
     * @covers  Molajo\IoC\ClassDependencies::loadClassDependencies
     * @covers  Molajo\IoC\ClassDependencies::readFile
     * @covers  Molajo\IoC\ClassDependencies::processClassDependencyData
     *
     * @covers  Molajo\IoC\FactoryMethodCreate::__construct
     * @covers  Molajo\IoC\FactoryMethodCreate::instantiateFactoryMethod
     * @covers  Molajo\IoC\FactoryMethodCreate::getFactoryMethodAdapter
     * @covers  Molajo\IoC\FactoryMethodCreate::getFactoryMethodController
     *
     * @covers  Molajo\IoC\FactoryMethodController::__construct
     * @covers  Molajo\IoC\FactoryMethodController::getNamespace
     * @covers  Molajo\IoC\FactoryMethodController::getOptions
     * @covers  Molajo\IoC\FactoryMethodController::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\FactoryMethodController::setDependencies
     * @covers  Molajo\IoC\FactoryMethodController::removeDependency
     * @covers  Molajo\IoC\FactoryMethodController::setDependencyValue
     * @covers  Molajo\IoC\FactoryMethodController::getRemainingDependencyCount
     * @covers  Molajo\IoC\FactoryMethodController::onBeforeInstantiation
     * @covers  Molajo\IoC\FactoryMethodController::instantiateClass
     * @covers  Molajo\IoC\FactoryMethodController::onAfterInstantiation
     * @covers  Molajo\IoC\FactoryMethodController::getProductValue
     * @covers  Molajo\IoC\FactoryMethodController::removeContainerEntries
     * @covers  Molajo\IoC\FactoryMethodController::setContainerEntries
     * @covers  Molajo\IoC\FactoryMethodController::scheduleFactories
     *
     * @covers  Molajo\IoC\StandardFactoryMethod::__construct
     * @covers  Molajo\IoC\FactoryMethodBase::getNamespace
     * @covers  Molajo\IoC\FactoryMethodBase::getOptions
     * @covers  Molajo\IoC\FactoryMethodBase::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\FactoryMethodBase::setDependencies
     * @covers  Molajo\IoC\FactoryMethodBase::onBeforeInstantiation
     * @covers  Molajo\IoC\FactoryMethodBase::instantiateClass
     * @covers  Molajo\IoC\FactoryMethodBase::instantiateStatic
     * @covers  Molajo\IoC\FactoryMethodBase::onAfterInstantiation
     * @covers  Molajo\IoC\FactoryMethodBase::getProductValue
     * @covers  Molajo\IoC\FactoryMethodBase::removeContainerEntries
     * @covers  Molajo\IoC\FactoryMethodBase::setContainerEntries
     * @covers  Molajo\IoC\FactoryMethodBase::scheduleFactories
     * @covers  Molajo\IoC\FactoryMethodBase::readFile
     * @covers  Molajo\IoC\FactoryMethodBase::sortObject
     *
     * @return  void
     * @since  1.0.0
     */
    public function testClasswEmptyReflectionFile()
    {
        $class_dependencies       = __DIR__ . '/Files/FactoryMethodAliasesEmpty.json';
        $this->class_dependencies = new ClassDependencies($class_dependencies);

        $options                             = array();
        $options['product_name']             = 'Dispatcher';
        $options['container_key']            = 'Molajo\\Events\\Dispatcher';
        $options['ioc_id']                   = 1;
        $options['factory_method_namespace'] = 'Molajo\\Factories\\Dispatcher\\DispatcherFactoryMethod';

        $create = new FactoryMethodCreate($options);

        $work_object                 = new stdClass();
        $work_object->options        = $options;
        $work_object->factory_method = $create->instantiateFactoryMethod();

        $work_object = $this->class_dependencies->get($work_object);

        $this->assertEquals(count($work_object->reflection), 0);
        $this->assertEquals(count($work_object->dependencies), 0);
    }

    /**
     * @covers  Molajo\IoC\ClassDependencies::__construct
     * @covers  Molajo\IoC\ClassDependencies::get
     * @covers  Molajo\IoC\ClassDependencies::getReflectionDependencies
     * @covers  Molajo\IoC\ClassDependencies::loadClassDependencies
     * @covers  Molajo\IoC\ClassDependencies::readFile
     * @covers  Molajo\IoC\ClassDependencies::processClassDependencyData
     *
     * @covers  Molajo\IoC\FactoryMethodCreate::__construct
     * @covers  Molajo\IoC\FactoryMethodCreate::instantiateFactoryMethod
     * @covers  Molajo\IoC\FactoryMethodCreate::getFactoryMethodAdapter
     * @covers  Molajo\IoC\FactoryMethodCreate::getFactoryMethodController
     *
     * @covers  Molajo\IoC\FactoryMethodController::__construct
     * @covers  Molajo\IoC\FactoryMethodController::getNamespace
     * @covers  Molajo\IoC\FactoryMethodController::getOptions
     * @covers  Molajo\IoC\FactoryMethodController::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\FactoryMethodController::setDependencies
     * @covers  Molajo\IoC\FactoryMethodController::removeDependency
     * @covers  Molajo\IoC\FactoryMethodController::setDependencyValue
     * @covers  Molajo\IoC\FactoryMethodController::getRemainingDependencyCount
     * @covers  Molajo\IoC\FactoryMethodController::onBeforeInstantiation
     * @covers  Molajo\IoC\FactoryMethodController::instantiateClass
     * @covers  Molajo\IoC\FactoryMethodController::onAfterInstantiation
     * @covers  Molajo\IoC\FactoryMethodController::getProductValue
     * @covers  Molajo\IoC\FactoryMethodController::removeContainerEntries
     * @covers  Molajo\IoC\FactoryMethodController::setContainerEntries
     * @covers  Molajo\IoC\FactoryMethodController::scheduleFactories
     *
     * @covers  Molajo\IoC\StandardFactoryMethod::__construct
     * @covers  Molajo\IoC\FactoryMethodBase::getNamespace
     * @covers  Molajo\IoC\FactoryMethodBase::getOptions
     * @covers  Molajo\IoC\FactoryMethodBase::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\FactoryMethodBase::setDependencies
     * @covers  Molajo\IoC\FactoryMethodBase::onBeforeInstantiation
     * @covers  Molajo\IoC\FactoryMethodBase::instantiateClass
     * @covers  Molajo\IoC\FactoryMethodBase::instantiateStatic
     * @covers  Molajo\IoC\FactoryMethodBase::onAfterInstantiation
     * @covers  Molajo\IoC\FactoryMethodBase::getProductValue
     * @covers  Molajo\IoC\FactoryMethodBase::removeContainerEntries
     * @covers  Molajo\IoC\FactoryMethodBase::setContainerEntries
     * @covers  Molajo\IoC\FactoryMethodBase::scheduleFactories
     * @covers  Molajo\IoC\FactoryMethodBase::readFile
     * @covers  Molajo\IoC\FactoryMethodBase::sortObject
     *
     * @return  void
     * @since  1.0.0
     */
    public function testClassNoInputReflectionFile()
    {
        $class_dependencies       = NULL;
        $this->class_dependencies = new ClassDependencies($class_dependencies);

        $options                             = array();
        $options['product_name']             = 'Dispatcher';
        $options['container_key']            = 'Molajo\\Events\\Dispatcher';
        $options['ioc_id']                   = 1;
        $options['factory_method_namespace'] = 'Molajo\\Factories\\Dispatcher\\DispatcherFactoryMethod';

        $create = new FactoryMethodCreate($options);

        $work_object                 = new stdClass();
        $work_object->options        = $options;
        $work_object->factory_method = $create->instantiateFactoryMethod();

        $work_object = $this->class_dependencies->get($work_object);

        $this->assertEquals(count($work_object->reflection), 0);
        $this->assertEquals(count($work_object->dependencies), 0);
    }
}
