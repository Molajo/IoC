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
     * @covers  Molajo\IoC\Product\ClassDependencies::__construct
     * @covers  Molajo\IoC\Product\ClassDependencies::get
     * @covers  Molajo\IoC\Product\ClassDependencies::getReflectionDependencies
     * @covers  Molajo\IoC\Product\ClassDependencies::loadClassDependencies
     * @covers  Molajo\IoC\Product\ClassDependencies::readFile
     * @covers  Molajo\IoC\Product\ClassDependencies::processClassDependencyData
     *
     * @covers  Molajo\IoC\Product\Create::__construct
     * @covers  Molajo\IoC\Product\Create::instantiateFactoryMethod
     * @covers  Molajo\IoC\Product\Create::getAdapter
     * @covers  Molajo\IoC\Product\Create::getController
     *
     * @covers  Molajo\IoC\FactoryMethod\Controller::__construct
     * @covers  Molajo\IoC\FactoryMethod\Controller::getNamespace
     * @covers  Molajo\IoC\FactoryMethod\Controller::getOptions
     * @covers  Molajo\IoC\FactoryMethod\Controller::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\FactoryMethod\Controller::setDependencies
     * @covers  Molajo\IoC\FactoryMethod\Controller::removeDependency
     * @covers  Molajo\IoC\FactoryMethod\Controller::setDependencyValue
     * @covers  Molajo\IoC\FactoryMethod\Controller::getRemainingDependencyCount
     * @covers  Molajo\IoC\FactoryMethod\Controller::onBeforeInstantiation
     * @covers  Molajo\IoC\FactoryMethod\Controller::instantiateClass
     * @covers  Molajo\IoC\FactoryMethod\Controller::onAfterInstantiation
     * @covers  Molajo\IoC\FactoryMethod\Controller::getProductValue
     * @covers  Molajo\IoC\FactoryMethod\Controller::removeContainerEntries
     * @covers  Molajo\IoC\FactoryMethod\Controller::setContainerEntries
     * @covers  Molajo\IoC\FactoryMethod\Controller::scheduleFactories
     *
     * @covers  Molajo\IoC\FactoryMethod\Base::setDependencies
     * @covers  Molajo\IoC\FactoryMethod\Base::setDependencyUsingReflection
     * @covers  Molajo\IoC\FactoryMethod\Base::setDependencyUsingReflectionInterface
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiation
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationDependencyValues
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationReflectionLoop
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationReflection
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationVerifyDependency
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationVerifyOptions
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::instantiateClass
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::processReflectionDependencies
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::instantiateClassNotStaticTryCatch
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::instantiateClassNotStatic
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::instantiateStatic
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::onAfterInstantiation
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::getProductValue
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::getProductValueStatic
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::getProductValueInstance
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::getProductValueProperties
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::getProductValueDoNotSave
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::removeContainerEntries
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::setContainerEntries
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::scheduleFactories
     * @covers  Molajo\IoC\FactoryMethod\Adapter::__construct
     * @covers  Molajo\IoC\FactoryMethod\Adapter::setConstructorOptions
     * @covers  Molajo\IoC\FactoryMethod\Adapter::getNamespace
     * @covers  Molajo\IoC\FactoryMethod\Adapter::getOptions
     * @covers  Molajo\IoC\FactoryMethod\Adapter::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\FactoryMethod\Adapter::readFile
     * @covers  Molajo\IoC\FactoryMethod\Adapter::readFileIntoArray
     * @covers  Molajo\IoC\FactoryMethod\Adapter::sortObject
     * @covers  Molajo\IoC\FactoryMethod\Adapter::sortObjectLoadIntoArray
     * @covers  Molajo\IoC\FactoryMethod\Adapter::sortObjectLoadSortedArrayIntoObject
     *
     * @return void
     * @since   1.0.0
     */
    public function setup()
    {
        $class_dependencies = __DIR__ . '/Files/ClassDependencies.json';

        $class                    = 'Molajo\\IoC\\Product\\ClassDependencies';
        $this->class_dependencies = new $class($class_dependencies);
    }

    /**
     * @covers  Molajo\IoC\Product\ClassDependencies::__construct
     * @covers  Molajo\IoC\Product\ClassDependencies::get
     * @covers  Molajo\IoC\Product\ClassDependencies::getReflectionDependencies
     * @covers  Molajo\IoC\Product\ClassDependencies::loadClassDependencies
     * @covers  Molajo\IoC\Product\ClassDependencies::readFile
     * @covers  Molajo\IoC\Product\ClassDependencies::processClassDependencyData
     *
     * @covers  Molajo\IoC\Product\Create::__construct
     * @covers  Molajo\IoC\Product\Create::instantiateFactoryMethod
     * @covers  Molajo\IoC\Product\Create::getAdapter
     * @covers  Molajo\IoC\Product\Create::getController
     *
     * @covers  Molajo\IoC\FactoryMethod\Controller::__construct
     * @covers  Molajo\IoC\FactoryMethod\Controller::getNamespace
     * @covers  Molajo\IoC\FactoryMethod\Controller::getOptions
     * @covers  Molajo\IoC\FactoryMethod\Controller::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\FactoryMethod\Controller::setDependencies
     * @covers  Molajo\IoC\FactoryMethod\Controller::removeDependency
     * @covers  Molajo\IoC\FactoryMethod\Controller::setDependencyValue
     * @covers  Molajo\IoC\FactoryMethod\Controller::getRemainingDependencyCount
     * @covers  Molajo\IoC\FactoryMethod\Controller::onBeforeInstantiation
     * @covers  Molajo\IoC\FactoryMethod\Controller::instantiateClass
     * @covers  Molajo\IoC\FactoryMethod\Controller::onAfterInstantiation
     * @covers  Molajo\IoC\FactoryMethod\Controller::getProductValue
     * @covers  Molajo\IoC\FactoryMethod\Controller::removeContainerEntries
     * @covers  Molajo\IoC\FactoryMethod\Controller::setContainerEntries
     * @covers  Molajo\IoC\FactoryMethod\Controller::scheduleFactories
     *
     * @covers  Molajo\IoC\FactoryMethod\Base::setDependencies
     * @covers  Molajo\IoC\FactoryMethod\Base::setDependencyUsingReflection
     * @covers  Molajo\IoC\FactoryMethod\Base::setDependencyUsingReflectionInterface
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiation
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationDependencyValues
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationReflectionLoop
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationReflection
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationVerifyDependency
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationVerifyOptions
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::instantiateClass
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::processReflectionDependencies
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::instantiateClassNotStaticTryCatch
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::instantiateClassNotStatic
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::instantiateStatic
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::onAfterInstantiation
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::getProductValue
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::getProductValueStatic
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::getProductValueInstance
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::getProductValueProperties
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::getProductValueDoNotSave
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::removeContainerEntries
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::setContainerEntries
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::scheduleFactories
     * @covers  Molajo\IoC\FactoryMethod\Adapter::__construct
     * @covers  Molajo\IoC\FactoryMethod\Adapter::setConstructorOptions
     * @covers  Molajo\IoC\FactoryMethod\Adapter::getNamespace
     * @covers  Molajo\IoC\FactoryMethod\Adapter::getOptions
     * @covers  Molajo\IoC\FactoryMethod\Adapter::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\FactoryMethod\Adapter::readFile
     * @covers  Molajo\IoC\FactoryMethod\Adapter::readFileIntoArray
     * @covers  Molajo\IoC\FactoryMethod\Adapter::sortObject
     * @covers  Molajo\IoC\FactoryMethod\Adapter::sortObjectLoadIntoArray
     * @covers  Molajo\IoC\FactoryMethod\Adapter::sortObjectLoadSortedArrayIntoObject
     *
     * @return  void
     * @since   1.0.0
     */
    public function testClassWithReflectionDependencies()
    {
        $options                             = array();
        $options['product_name']             = 'Dispatcher';
        $options['container_key']            = 'Molajo\\Events\\Dispatcher';
        $options['ioc_id']                   = 1;
        $options['factory_method_namespace'] = 'Molajo\\Factories\\Dispatcher\\DispatcherFactoryMethod';

        $class  = 'Molajo\\IoC\\Product\\Create';
        $create = new $class($options);

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
     * @covers  Molajo\IoC\Product\ClassDependencies::__construct
     * @covers  Molajo\IoC\Product\ClassDependencies::get
     * @covers  Molajo\IoC\Product\ClassDependencies::getReflectionDependencies
     * @covers  Molajo\IoC\Product\ClassDependencies::loadClassDependencies
     * @covers  Molajo\IoC\Product\ClassDependencies::readFile
     * @covers  Molajo\IoC\Product\ClassDependencies::processClassDependencyData
     *
     * @covers  Molajo\IoC\Product\Create::__construct
     * @covers  Molajo\IoC\Product\Create::instantiateFactoryMethod
     * @covers  Molajo\IoC\Product\Create::getAdapter
     * @covers  Molajo\IoC\Product\Create::getController
     *
     * @covers  Molajo\IoC\FactoryMethod\Controller::__construct
     * @covers  Molajo\IoC\FactoryMethod\Controller::getNamespace
     * @covers  Molajo\IoC\FactoryMethod\Controller::getOptions
     * @covers  Molajo\IoC\FactoryMethod\Controller::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\FactoryMethod\Controller::setDependencies
     * @covers  Molajo\IoC\FactoryMethod\Controller::removeDependency
     * @covers  Molajo\IoC\FactoryMethod\Controller::setDependencyValue
     * @covers  Molajo\IoC\FactoryMethod\Controller::getRemainingDependencyCount
     * @covers  Molajo\IoC\FactoryMethod\Controller::onBeforeInstantiation
     * @covers  Molajo\IoC\FactoryMethod\Controller::instantiateClass
     * @covers  Molajo\IoC\FactoryMethod\Controller::onAfterInstantiation
     * @covers  Molajo\IoC\FactoryMethod\Controller::getProductValue
     * @covers  Molajo\IoC\FactoryMethod\Controller::removeContainerEntries
     * @covers  Molajo\IoC\FactoryMethod\Controller::setContainerEntries
     * @covers  Molajo\IoC\FactoryMethod\Controller::scheduleFactories
     *
     * @covers  Molajo\IoC\FactoryMethod\Base::setDependencies
     * @covers  Molajo\IoC\FactoryMethod\Base::setDependencyUsingReflection
     * @covers  Molajo\IoC\FactoryMethod\Base::setDependencyUsingReflectionInterface
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiation
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationDependencyValues
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationReflectionLoop
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationReflection
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationVerifyDependency
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationVerifyOptions
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::instantiateClass
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::processReflectionDependencies
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::instantiateClassNotStaticTryCatch
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::instantiateClassNotStatic
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::instantiateStatic
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::onAfterInstantiation
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::getProductValue
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::getProductValueStatic
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::getProductValueInstance
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::getProductValueProperties
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::getProductValueDoNotSave
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::removeContainerEntries
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::setContainerEntries
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::scheduleFactories
     * @covers  Molajo\IoC\FactoryMethod\Adapter::__construct
     * @covers  Molajo\IoC\FactoryMethod\Adapter::setConstructorOptions
     * @covers  Molajo\IoC\FactoryMethod\Adapter::getNamespace
     * @covers  Molajo\IoC\FactoryMethod\Adapter::getOptions
     * @covers  Molajo\IoC\FactoryMethod\Adapter::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\FactoryMethod\Adapter::readFile
     * @covers  Molajo\IoC\FactoryMethod\Adapter::readFileIntoArray
     * @covers  Molajo\IoC\FactoryMethod\Adapter::sortObject
     * @covers  Molajo\IoC\FactoryMethod\Adapter::sortObjectLoadIntoArray
     * @covers  Molajo\IoC\FactoryMethod\Adapter::sortObjectLoadSortedArrayIntoObject
     *
     * @return  void
     * @since   1.0.0
     */
    public function testClassWithoutReflectionDependencies()
    {
        $options                             = array();
        $options['product_name']             = 'EventDispatcher';
        $options['container_key']            = 'Molajo\\Events\\EventDispatcher';
        $options['ioc_id']                   = 1;
        $options['factory_method_namespace'] = 'Molajo\\IoC\\FactoryMethod\\Standard';

        $class  = 'Molajo\\IoC\\Product\\Create';
        $create = new $class($options);

        $work_object                 = new stdClass();
        $work_object->options        = $options;
        $work_object->factory_method = $create->instantiateFactoryMethod();

        $work_object = $this->class_dependencies->get($work_object);

        $this->assertEquals(count($work_object->reflection), 0);
        $this->assertEquals(count($work_object->dependencies), 0);
    }

    /**
     * @covers  Molajo\IoC\Product\ClassDependencies::__construct
     * @covers  Molajo\IoC\Product\ClassDependencies::get
     * @covers  Molajo\IoC\Product\ClassDependencies::getReflectionDependencies
     * @covers  Molajo\IoC\Product\ClassDependencies::loadClassDependencies
     * @covers  Molajo\IoC\Product\ClassDependencies::readFile
     * @covers  Molajo\IoC\Product\ClassDependencies::processClassDependencyData
     *
     * @covers  Molajo\IoC\Product\Create::__construct
     * @covers  Molajo\IoC\Product\Create::instantiateFactoryMethod
     * @covers  Molajo\IoC\Product\Create::getAdapter
     * @covers  Molajo\IoC\Product\Create::getController
     *
     * @covers  Molajo\IoC\FactoryMethod\Controller::__construct
     * @covers  Molajo\IoC\FactoryMethod\Controller::getNamespace
     * @covers  Molajo\IoC\FactoryMethod\Controller::getOptions
     * @covers  Molajo\IoC\FactoryMethod\Controller::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\FactoryMethod\Controller::setDependencies
     * @covers  Molajo\IoC\FactoryMethod\Controller::removeDependency
     * @covers  Molajo\IoC\FactoryMethod\Controller::setDependencyValue
     * @covers  Molajo\IoC\FactoryMethod\Controller::getRemainingDependencyCount
     * @covers  Molajo\IoC\FactoryMethod\Controller::onBeforeInstantiation
     * @covers  Molajo\IoC\FactoryMethod\Controller::instantiateClass
     * @covers  Molajo\IoC\FactoryMethod\Controller::onAfterInstantiation
     * @covers  Molajo\IoC\FactoryMethod\Controller::getProductValue
     * @covers  Molajo\IoC\FactoryMethod\Controller::removeContainerEntries
     * @covers  Molajo\IoC\FactoryMethod\Controller::setContainerEntries
     * @covers  Molajo\IoC\FactoryMethod\Controller::scheduleFactories
     *
     * @covers  Molajo\IoC\FactoryMethod\Base::setDependencies
     * @covers  Molajo\IoC\FactoryMethod\Base::setDependencyUsingReflection
     * @covers  Molajo\IoC\FactoryMethod\Base::setDependencyUsingReflectionInterface
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiation
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationDependencyValues
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationReflectionLoop
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationReflection
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationVerifyDependency
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationVerifyOptions
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::instantiateClass
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::processReflectionDependencies
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::instantiateClassNotStaticTryCatch
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::instantiateClassNotStatic
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::instantiateStatic
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::onAfterInstantiation
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::getProductValue
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::getProductValueStatic
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::getProductValueInstance
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::getProductValueProperties
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::getProductValueDoNotSave
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::removeContainerEntries
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::setContainerEntries
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::scheduleFactories
     * @covers  Molajo\IoC\FactoryMethod\Adapter::__construct
     * @covers  Molajo\IoC\FactoryMethod\Adapter::setConstructorOptions
     * @covers  Molajo\IoC\FactoryMethod\Adapter::getNamespace
     * @covers  Molajo\IoC\FactoryMethod\Adapter::getOptions
     * @covers  Molajo\IoC\FactoryMethod\Adapter::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\FactoryMethod\Adapter::readFile
     * @covers  Molajo\IoC\FactoryMethod\Adapter::readFileIntoArray
     * @covers  Molajo\IoC\FactoryMethod\Adapter::sortObject
     * @covers  Molajo\IoC\FactoryMethod\Adapter::sortObjectLoadIntoArray
     * @covers  Molajo\IoC\FactoryMethod\Adapter::sortObjectLoadSortedArrayIntoObject
     *
     * @return  void
     * @since   1.0.0
     */
    public function testClasswEmptyReflectionFile()
    {
        $class_dependencies = __DIR__ . '/Files/FactoryMethodAliasesEmpty.json';

        $class                    = 'Molajo\\IoC\\Product\\ClassDependencies';
        $this->class_dependencies = new $class($class_dependencies);

        $options                             = array();
        $options['product_name']             = 'Dispatcher';
        $options['container_key']            = 'Molajo\\Events\\Dispatcher';
        $options['ioc_id']                   = 1;
        $options['factory_method_namespace'] = 'Molajo\\Factories\\Dispatcher\\DispatcherFactoryMethod';

        $class  = 'Molajo\\IoC\\Product\\Create';
        $create = new $class($options);

        $work_object                 = new stdClass();
        $work_object->options        = $options;
        $work_object->factory_method = $create->instantiateFactoryMethod();

        $work_object = $this->class_dependencies->get($work_object);

        $this->assertEquals(count($work_object->reflection), 0);
        $this->assertEquals(count($work_object->dependencies), 0);
    }

    /**
     * @covers  Molajo\IoC\Product\ClassDependencies::__construct
     * @covers  Molajo\IoC\Product\ClassDependencies::get
     * @covers  Molajo\IoC\Product\ClassDependencies::getReflectionDependencies
     * @covers  Molajo\IoC\Product\ClassDependencies::loadClassDependencies
     * @covers  Molajo\IoC\Product\ClassDependencies::readFile
     * @covers  Molajo\IoC\Product\ClassDependencies::processClassDependencyData
     *
     * @covers  Molajo\IoC\Product\Create::__construct
     * @covers  Molajo\IoC\Product\Create::instantiateFactoryMethod
     * @covers  Molajo\IoC\Product\Create::getAdapter
     * @covers  Molajo\IoC\Product\Create::getController
     *
     * @covers  Molajo\IoC\FactoryMethod\Controller::__construct
     * @covers  Molajo\IoC\FactoryMethod\Controller::getNamespace
     * @covers  Molajo\IoC\FactoryMethod\Controller::getOptions
     * @covers  Molajo\IoC\FactoryMethod\Controller::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\FactoryMethod\Controller::setDependencies
     * @covers  Molajo\IoC\FactoryMethod\Controller::removeDependency
     * @covers  Molajo\IoC\FactoryMethod\Controller::setDependencyValue
     * @covers  Molajo\IoC\FactoryMethod\Controller::getRemainingDependencyCount
     * @covers  Molajo\IoC\FactoryMethod\Controller::onBeforeInstantiation
     * @covers  Molajo\IoC\FactoryMethod\Controller::instantiateClass
     * @covers  Molajo\IoC\FactoryMethod\Controller::onAfterInstantiation
     * @covers  Molajo\IoC\FactoryMethod\Controller::getProductValue
     * @covers  Molajo\IoC\FactoryMethod\Controller::removeContainerEntries
     * @covers  Molajo\IoC\FactoryMethod\Controller::setContainerEntries
     * @covers  Molajo\IoC\FactoryMethod\Controller::scheduleFactories
     *
     * @covers  Molajo\IoC\FactoryMethod\Base::setDependencies
     * @covers  Molajo\IoC\FactoryMethod\Base::setDependencyUsingReflection
     * @covers  Molajo\IoC\FactoryMethod\Base::setDependencyUsingReflectionInterface
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiation
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationDependencyValues
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationReflectionLoop
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationReflection
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationVerifyDependency
     * @covers  Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationVerifyOptions
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::instantiateClass
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::processReflectionDependencies
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::instantiateClassNotStaticTryCatch
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::instantiateClassNotStatic
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::instantiateStatic
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::onAfterInstantiation
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::getProductValue
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::getProductValueStatic
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::getProductValueInstance
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::getProductValueProperties
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::getProductValueDoNotSave
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::removeContainerEntries
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::setContainerEntries
     * @covers  Molajo\IoC\FactoryMethod\Instantiate::scheduleFactories
     * @covers  Molajo\IoC\FactoryMethod\Adapter::__construct
     * @covers  Molajo\IoC\FactoryMethod\Adapter::setConstructorOptions
     * @covers  Molajo\IoC\FactoryMethod\Adapter::getNamespace
     * @covers  Molajo\IoC\FactoryMethod\Adapter::getOptions
     * @covers  Molajo\IoC\FactoryMethod\Adapter::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\FactoryMethod\Adapter::readFile
     * @covers  Molajo\IoC\FactoryMethod\Adapter::readFileIntoArray
     * @covers  Molajo\IoC\FactoryMethod\Adapter::sortObject
     * @covers  Molajo\IoC\FactoryMethod\Adapter::sortObjectLoadIntoArray
     * @covers  Molajo\IoC\FactoryMethod\Adapter::sortObjectLoadSortedArrayIntoObject
     *
     * @return  void
     * @since   1.0.0
     */
    public function testClassNoInputReflectionFile()
    {
        $class_dependencies       = null;
        $class                    = 'Molajo\\IoC\\Product\\ClassDependencies';
        $this->class_dependencies = new $class($class_dependencies);

        $options                             = array();
        $options['product_name']             = 'Dispatcher';
        $options['container_key']            = 'Molajo\\Events\\Dispatcher';
        $options['ioc_id']                   = 1;
        $options['factory_method_namespace'] = 'Molajo\\Factories\\Dispatcher\\DispatcherFactoryMethod';

        $class  = 'Molajo\\IoC\\Product\\Create';
        $create = new $class($options);

        $work_object                 = new stdClass();
        $work_object->options        = $options;
        $work_object->factory_method = $create->instantiateFactoryMethod();

        $work_object = $this->class_dependencies->get($work_object);

        $this->assertEquals(count($work_object->reflection), 0);
        $this->assertEquals(count($work_object->dependencies), 0);
    }
}
