<?php
/**
 * Factory Method Namespace Test
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\IoC;

use PHPUnit_Framework_TestCase;

/**
 * Factory Method Namespace Test
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
class FactoryMethodCreateTest extends PHPUnit_Framework_TestCase
{
    /**
     * Pass in Full Factory Method Namespace
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
     * @covers  Molajo\IoC\FactoryMethodBase::setDependencies
     * @covers  Molajo\IoC\FactoryMethodBase::setDependencyUsingReflection
     * @covers  Molajo\IoC\FactoryMethodBase::setDependencyUsingReflectionInterface
     * @covers  Molajo\IoC\FactoryMethodBase::onBeforeInstantiation
     * @covers  Molajo\IoC\FactoryMethodBase::onBeforeInstantiationDependencyValues
     * @covers  Molajo\IoC\FactoryMethodBase::onBeforeInstantiationReflectionLoop
     * @covers  Molajo\IoC\FactoryMethodBase::onBeforeInstantiationReflection
     * @covers  Molajo\IoC\FactoryMethodBase::onBeforeInstantiationVerifyDependency
     * @covers  Molajo\IoC\FactoryMethodBase::onBeforeInstantiationVerifyOptions
     * @covers  Molajo\IoC\FactoryMethodInstantiate::instantiateClass
     * @covers  Molajo\IoC\FactoryMethodInstantiate::processReflectionDependencies
     * @covers  Molajo\IoC\FactoryMethodInstantiate::instantiateClassNotStaticTryCatch
     * @covers  Molajo\IoC\FactoryMethodInstantiate::instantiateClassNotStatic
     * @covers  Molajo\IoC\FactoryMethodInstantiate::instantiateStatic
     * @covers  Molajo\IoC\FactoryMethodInstantiate::onAfterInstantiation
     * @covers  Molajo\IoC\FactoryMethodInstantiate::getProductValue
     * @covers  Molajo\IoC\FactoryMethodInstantiate::getProductValueStatic
     * @covers  Molajo\IoC\FactoryMethodInstantiate::getProductValueInstance
     * @covers  Molajo\IoC\FactoryMethodInstantiate::getProductValueProperties
     * @covers  Molajo\IoC\FactoryMethodInstantiate::getProductValueDoNotSave
     * @covers  Molajo\IoC\FactoryMethodInstantiate::removeContainerEntries
     * @covers  Molajo\IoC\FactoryMethodInstantiate::setContainerEntries
     * @covers  Molajo\IoC\FactoryMethodInstantiate::scheduleFactories
     * @covers  Molajo\IoC\FactoryMethodAdapter::__construct
     * @covers  Molajo\IoC\FactoryMethodAdapter::setConstructorOptions
     * @covers  Molajo\IoC\FactoryMethodAdapter::getNamespace
     * @covers  Molajo\IoC\FactoryMethodAdapter::getOptions
     * @covers  Molajo\IoC\FactoryMethodAdapter::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\FactoryMethodAdapter::readFile
     * @covers  Molajo\IoC\FactoryMethodAdapter::readFileIntoArray
     * @covers  Molajo\IoC\FactoryMethodAdapter::sortObject
     * @covers  Molajo\IoC\FactoryMethodAdapter::sortObjectLoadIntoArray
     * @covers  Molajo\IoC\FactoryMethodAdapter::sortObjectLoadSortedArrayIntoObject
     *
     * @return void
     * @since  1.0.0
     */
    public function testNormalNamespace()
    {
        $options                    = array();
        $options['product_name']    = 'Dispatcher';
        $options['container_key']   = 'Molajo\\Factories\\Dispatcher';
        $options['ioc_id']          = 1;
        $options['factory_method_namespace']
            = $options['container_key'] . '\\' . 'DispatcherFactoryMethod';

        $create = new FactoryMethodCreate($options);
        $results = $create->instantiateFactoryMethod();

//file_put_contents(__DIR__ . '/DispatcherFactoryMethod.txt', serialize($results));

        $this->assertEquals(file_get_contents(__DIR__ . '/DispatcherFactoryMethod.txt'),
            serialize($create->instantiateFactoryMethod()));
    }

    /**
     * Pass in Standard Factory Method Namespace
     *
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
     * @covers  Molajo\IoC\FactoryMethodBase::setDependencies
     * @covers  Molajo\IoC\FactoryMethodBase::setDependencyUsingReflection
     * @covers  Molajo\IoC\FactoryMethodBase::setDependencyUsingReflectionInterface
     * @covers  Molajo\IoC\FactoryMethodBase::onBeforeInstantiation
     * @covers  Molajo\IoC\FactoryMethodBase::onBeforeInstantiationDependencyValues
     * @covers  Molajo\IoC\FactoryMethodBase::onBeforeInstantiationReflectionLoop
     * @covers  Molajo\IoC\FactoryMethodBase::onBeforeInstantiationReflection
     * @covers  Molajo\IoC\FactoryMethodBase::onBeforeInstantiationVerifyDependency
     * @covers  Molajo\IoC\FactoryMethodBase::onBeforeInstantiationVerifyOptions
     * @covers  Molajo\IoC\FactoryMethodInstantiate::instantiateClass
     * @covers  Molajo\IoC\FactoryMethodInstantiate::processReflectionDependencies
     * @covers  Molajo\IoC\FactoryMethodInstantiate::instantiateClassNotStaticTryCatch
     * @covers  Molajo\IoC\FactoryMethodInstantiate::instantiateClassNotStatic
     * @covers  Molajo\IoC\FactoryMethodInstantiate::instantiateStatic
     * @covers  Molajo\IoC\FactoryMethodInstantiate::onAfterInstantiation
     * @covers  Molajo\IoC\FactoryMethodInstantiate::getProductValue
     * @covers  Molajo\IoC\FactoryMethodInstantiate::getProductValueStatic
     * @covers  Molajo\IoC\FactoryMethodInstantiate::getProductValueInstance
     * @covers  Molajo\IoC\FactoryMethodInstantiate::getProductValueProperties
     * @covers  Molajo\IoC\FactoryMethodInstantiate::getProductValueDoNotSave
     * @covers  Molajo\IoC\FactoryMethodInstantiate::removeContainerEntries
     * @covers  Molajo\IoC\FactoryMethodInstantiate::setContainerEntries
     * @covers  Molajo\IoC\FactoryMethodInstantiate::scheduleFactories
     * @covers  Molajo\IoC\FactoryMethodAdapter::__construct
     * @covers  Molajo\IoC\FactoryMethodAdapter::setConstructorOptions
     * @covers  Molajo\IoC\FactoryMethodAdapter::getNamespace
     * @covers  Molajo\IoC\FactoryMethodAdapter::getOptions
     * @covers  Molajo\IoC\FactoryMethodAdapter::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\FactoryMethodAdapter::readFile
     * @covers  Molajo\IoC\FactoryMethodAdapter::readFileIntoArray
     * @covers  Molajo\IoC\FactoryMethodAdapter::sortObject
     * @covers  Molajo\IoC\FactoryMethodAdapter::sortObjectLoadIntoArray
     * @covers  Molajo\IoC\FactoryMethodAdapter::sortObjectLoadSortedArrayIntoObject
     *
     * @return void
     * @since  1.0.0
     */
    public function testStandardFactoryMethodNamespace()
    {
        $options                    = array();
        $options['product_name']    = 'Standard';
        $options['container_key']   = 'Molajo\\IoC\\StandardFactoryMethod';
        $options['ioc_id']          = 1;
        $options['factory_method_namespace'] = $options['container_key'];

        $create = new FactoryMethodCreate($options);
        $results = $create->instantiateFactoryMethod();

//file_put_contents(__DIR__ . '/StandardFactoryMethod.txt', serialize($results));

        $this->assertEquals(file_get_contents(__DIR__ . '/StandardFactoryMethod.txt'),
            serialize($create->instantiateFactoryMethod()));
    }

    /**
     * Pass in Standard Factory Method Namespace
     *
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
     * @covers  Molajo\IoC\FactoryMethodBase::setDependencies
     * @covers  Molajo\IoC\FactoryMethodBase::setDependencyUsingReflection
     * @covers  Molajo\IoC\FactoryMethodBase::setDependencyUsingReflectionInterface
     * @covers  Molajo\IoC\FactoryMethodBase::onBeforeInstantiation
     * @covers  Molajo\IoC\FactoryMethodBase::onBeforeInstantiationDependencyValues
     * @covers  Molajo\IoC\FactoryMethodBase::onBeforeInstantiationReflectionLoop
     * @covers  Molajo\IoC\FactoryMethodBase::onBeforeInstantiationReflection
     * @covers  Molajo\IoC\FactoryMethodBase::onBeforeInstantiationVerifyDependency
     * @covers  Molajo\IoC\FactoryMethodBase::onBeforeInstantiationVerifyOptions
     * @covers  Molajo\IoC\FactoryMethodInstantiate::instantiateClass
     * @covers  Molajo\IoC\FactoryMethodInstantiate::processReflectionDependencies
     * @covers  Molajo\IoC\FactoryMethodInstantiate::instantiateClassNotStaticTryCatch
     * @covers  Molajo\IoC\FactoryMethodInstantiate::instantiateClassNotStatic
     * @covers  Molajo\IoC\FactoryMethodInstantiate::instantiateStatic
     * @covers  Molajo\IoC\FactoryMethodInstantiate::onAfterInstantiation
     * @covers  Molajo\IoC\FactoryMethodInstantiate::getProductValue
     * @covers  Molajo\IoC\FactoryMethodInstantiate::getProductValueStatic
     * @covers  Molajo\IoC\FactoryMethodInstantiate::getProductValueInstance
     * @covers  Molajo\IoC\FactoryMethodInstantiate::getProductValueProperties
     * @covers  Molajo\IoC\FactoryMethodInstantiate::getProductValueDoNotSave
     * @covers  Molajo\IoC\FactoryMethodInstantiate::removeContainerEntries
     * @covers  Molajo\IoC\FactoryMethodInstantiate::setContainerEntries
     * @covers  Molajo\IoC\FactoryMethodInstantiate::scheduleFactories
     * @covers  Molajo\IoC\FactoryMethodAdapter::__construct
     * @covers  Molajo\IoC\FactoryMethodAdapter::setConstructorOptions
     * @covers  Molajo\IoC\FactoryMethodAdapter::getNamespace
     * @covers  Molajo\IoC\FactoryMethodAdapter::getOptions
     * @covers  Molajo\IoC\FactoryMethodAdapter::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\FactoryMethodAdapter::readFile
     * @covers  Molajo\IoC\FactoryMethodAdapter::readFileIntoArray
     * @covers  Molajo\IoC\FactoryMethodAdapter::sortObject
     * @covers  Molajo\IoC\FactoryMethodAdapter::sortObjectLoadIntoArray
     * @covers  Molajo\IoC\FactoryMethodAdapter::sortObjectLoadSortedArrayIntoObject
     *
     * @expectedException \CommonApi\Exception\RuntimeException
     * @expectedExceptionMessage IoC FactoryMethodCreate::getFactoryMethodAdapter Class does not exist: Molajo\IoC\DoesNotExist
     *
     * @return void
     * @since  1.0.0
     */
    public function testClassDoesNotExist()
    {
        $options                    = array();
        $options['product_name']    = 'Standard';
        $options['container_key']   = 'Molajo\\IoC\\DoesNotExist';
        $options['ioc_id']          = 1;
        $options['factory_method_namespace'] = $options['container_key'];

        $create = new FactoryMethodCreate($options);
        $create->instantiateFactoryMethod();
    }
}
