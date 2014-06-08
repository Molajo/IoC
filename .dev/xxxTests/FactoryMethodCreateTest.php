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
class CreateTest extends PHPUnit_Framework_TestCase
{
    /**
     * Pass in Full Factory Method Namespace
     *
     * @covers  Molajo\IoC\Create::__construct
     * @covers  Molajo\IoC\Create::instantiateFactoryMethod
     * @covers  Molajo\IoC\Create::getAdapter
     * @covers  Molajo\IoC\Create::getController
     *
     * @covers  Molajo\IoC\Controller::__construct
     * @covers  Molajo\IoC\Controller::getNamespace
     * @covers  Molajo\IoC\Controller::getOptions
     * @covers  Molajo\IoC\Controller::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\Controller::setDependencies
     * @covers  Molajo\IoC\Controller::removeDependency
     * @covers  Molajo\IoC\Controller::setDependencyValue
     * @covers  Molajo\IoC\Controller::getRemainingDependencyCount
     * @covers  Molajo\IoC\Controller::onBeforeInstantiation
     * @covers  Molajo\IoC\Controller::instantiateClass
     * @covers  Molajo\IoC\Controller::onAfterInstantiation
     * @covers  Molajo\IoC\Controller::getProductValue
     * @covers  Molajo\IoC\Controller::removeContainerEntries
     * @covers  Molajo\IoC\Controller::setContainerEntries
     * @covers  Molajo\IoC\Controller::scheduleFactories
     *
     * @covers  Molajo\IoC\Standard::__construct
     * @covers  Molajo\IoC\Base::setDependencies
     * @covers  Molajo\IoC\Base::setDependencyUsingReflection
     * @covers  Molajo\IoC\Base::setDependencyUsingReflectionInterface
     * @covers  Molajo\IoC\Base::onBeforeInstantiation
     * @covers  Molajo\IoC\Base::onBeforeInstantiationDependencyValues
     * @covers  Molajo\IoC\Base::onBeforeInstantiationReflectionLoop
     * @covers  Molajo\IoC\Base::onBeforeInstantiationReflection
     * @covers  Molajo\IoC\Base::onBeforeInstantiationVerifyDependency
     * @covers  Molajo\IoC\Base::onBeforeInstantiationVerifyOptions
     * @covers  Molajo\IoC\Instantiate::instantiateClass
     * @covers  Molajo\IoC\Instantiate::processReflectionDependencies
     * @covers  Molajo\IoC\Instantiate::instantiateClassNotStaticTryCatch
     * @covers  Molajo\IoC\Instantiate::instantiateClassNotStatic
     * @covers  Molajo\IoC\Instantiate::instantiateStatic
     * @covers  Molajo\IoC\Instantiate::onAfterInstantiation
     * @covers  Molajo\IoC\Instantiate::getProductValue
     * @covers  Molajo\IoC\Instantiate::getProductValueStatic
     * @covers  Molajo\IoC\Instantiate::getProductValueInstance
     * @covers  Molajo\IoC\Instantiate::getProductValueProperties
     * @covers  Molajo\IoC\Instantiate::getProductValueDoNotSave
     * @covers  Molajo\IoC\Instantiate::removeContainerEntries
     * @covers  Molajo\IoC\Instantiate::setContainerEntries
     * @covers  Molajo\IoC\Instantiate::scheduleFactories
     * @covers  Molajo\IoC\Adapter::__construct
     * @covers  Molajo\IoC\Adapter::setConstructorOptions
     * @covers  Molajo\IoC\Adapter::getNamespace
     * @covers  Molajo\IoC\Adapter::getOptions
     * @covers  Molajo\IoC\Adapter::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\Adapter::readFile
     * @covers  Molajo\IoC\Adapter::readFileIntoArray
     * @covers  Molajo\IoC\Adapter::sortObject
     * @covers  Molajo\IoC\Adapter::sortObjectLoadIntoArray
     * @covers  Molajo\IoC\Adapter::sortObjectLoadSortedArrayIntoObject
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

        $create = new Create($options);
        $results = $create->instantiateFactoryMethod();

//file_put_contents(__DIR__ . '/DispatcherFactoryMethod.txt', serialize($results));

        $this->assertEquals(file_get_contents(__DIR__ . '/DispatcherFactoryMethod.txt'),
            serialize($create->instantiateFactoryMethod()));
    }

    /**
     * Pass in Standard Factory Method Namespace
     *
     *
     * @covers  Molajo\IoC\Create::__construct
     * @covers  Molajo\IoC\Create::instantiateFactoryMethod
     * @covers  Molajo\IoC\Create::getAdapter
     * @covers  Molajo\IoC\Create::getController
     *
     * @covers  Molajo\IoC\Controller::__construct
     * @covers  Molajo\IoC\Controller::getNamespace
     * @covers  Molajo\IoC\Controller::getOptions
     * @covers  Molajo\IoC\Controller::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\Controller::setDependencies
     * @covers  Molajo\IoC\Controller::removeDependency
     * @covers  Molajo\IoC\Controller::setDependencyValue
     * @covers  Molajo\IoC\Controller::getRemainingDependencyCount
     * @covers  Molajo\IoC\Controller::onBeforeInstantiation
     * @covers  Molajo\IoC\Controller::instantiateClass
     * @covers  Molajo\IoC\Controller::onAfterInstantiation
     * @covers  Molajo\IoC\Controller::getProductValue
     * @covers  Molajo\IoC\Controller::removeContainerEntries
     * @covers  Molajo\IoC\Controller::setContainerEntries
     * @covers  Molajo\IoC\Controller::scheduleFactories
     *
     * @covers  Molajo\IoC\Standard::__construct
     * @covers  Molajo\IoC\Base::setDependencies
     * @covers  Molajo\IoC\Base::setDependencyUsingReflection
     * @covers  Molajo\IoC\Base::setDependencyUsingReflectionInterface
     * @covers  Molajo\IoC\Base::onBeforeInstantiation
     * @covers  Molajo\IoC\Base::onBeforeInstantiationDependencyValues
     * @covers  Molajo\IoC\Base::onBeforeInstantiationReflectionLoop
     * @covers  Molajo\IoC\Base::onBeforeInstantiationReflection
     * @covers  Molajo\IoC\Base::onBeforeInstantiationVerifyDependency
     * @covers  Molajo\IoC\Base::onBeforeInstantiationVerifyOptions
     * @covers  Molajo\IoC\Instantiate::instantiateClass
     * @covers  Molajo\IoC\Instantiate::processReflectionDependencies
     * @covers  Molajo\IoC\Instantiate::instantiateClassNotStaticTryCatch
     * @covers  Molajo\IoC\Instantiate::instantiateClassNotStatic
     * @covers  Molajo\IoC\Instantiate::instantiateStatic
     * @covers  Molajo\IoC\Instantiate::onAfterInstantiation
     * @covers  Molajo\IoC\Instantiate::getProductValue
     * @covers  Molajo\IoC\Instantiate::getProductValueStatic
     * @covers  Molajo\IoC\Instantiate::getProductValueInstance
     * @covers  Molajo\IoC\Instantiate::getProductValueProperties
     * @covers  Molajo\IoC\Instantiate::getProductValueDoNotSave
     * @covers  Molajo\IoC\Instantiate::removeContainerEntries
     * @covers  Molajo\IoC\Instantiate::setContainerEntries
     * @covers  Molajo\IoC\Instantiate::scheduleFactories
     * @covers  Molajo\IoC\Adapter::__construct
     * @covers  Molajo\IoC\Adapter::setConstructorOptions
     * @covers  Molajo\IoC\Adapter::getNamespace
     * @covers  Molajo\IoC\Adapter::getOptions
     * @covers  Molajo\IoC\Adapter::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\Adapter::readFile
     * @covers  Molajo\IoC\Adapter::readFileIntoArray
     * @covers  Molajo\IoC\Adapter::sortObject
     * @covers  Molajo\IoC\Adapter::sortObjectLoadIntoArray
     * @covers  Molajo\IoC\Adapter::sortObjectLoadSortedArrayIntoObject
     *
     * @return void
     * @since  1.0.0
     */
    public function testStandardNamespace()
    {
        $options                    = array();
        $options['product_name']    = 'Standard';
        $options['container_key']   = 'Molajo\\IoC\\FactoryMethod\\Standard';
        $options['ioc_id']          = 1;
        $options['factory_method_namespace'] = $options['container_key'];

        $create = new Create($options);
        $results = $create->instantiateFactoryMethod();

//file_put_contents(__DIR__ . '/Standard.txt', serialize($results));

        $this->assertEquals(file_get_contents(__DIR__ . '/Standard.txt'),
            serialize($create->instantiateFactoryMethod()));
    }

    /**
     * Pass in Standard Factory Method Namespace
     *
     *
     * @covers  Molajo\IoC\Create::__construct
     * @covers  Molajo\IoC\Create::instantiateFactoryMethod
     * @covers  Molajo\IoC\Create::getAdapter
     * @covers  Molajo\IoC\Create::getController
     *
     * @covers  Molajo\IoC\Controller::__construct
     * @covers  Molajo\IoC\Controller::getNamespace
     * @covers  Molajo\IoC\Controller::getOptions
     * @covers  Molajo\IoC\Controller::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\Controller::setDependencies
     * @covers  Molajo\IoC\Controller::removeDependency
     * @covers  Molajo\IoC\Controller::setDependencyValue
     * @covers  Molajo\IoC\Controller::getRemainingDependencyCount
     * @covers  Molajo\IoC\Controller::onBeforeInstantiation
     * @covers  Molajo\IoC\Controller::instantiateClass
     * @covers  Molajo\IoC\Controller::onAfterInstantiation
     * @covers  Molajo\IoC\Controller::getProductValue
     * @covers  Molajo\IoC\Controller::removeContainerEntries
     * @covers  Molajo\IoC\Controller::setContainerEntries
     * @covers  Molajo\IoC\Controller::scheduleFactories
     *
     * @covers  Molajo\IoC\Standard::__construct
     * @covers  Molajo\IoC\Base::setDependencies
     * @covers  Molajo\IoC\Base::setDependencyUsingReflection
     * @covers  Molajo\IoC\Base::setDependencyUsingReflectionInterface
     * @covers  Molajo\IoC\Base::onBeforeInstantiation
     * @covers  Molajo\IoC\Base::onBeforeInstantiationDependencyValues
     * @covers  Molajo\IoC\Base::onBeforeInstantiationReflectionLoop
     * @covers  Molajo\IoC\Base::onBeforeInstantiationReflection
     * @covers  Molajo\IoC\Base::onBeforeInstantiationVerifyDependency
     * @covers  Molajo\IoC\Base::onBeforeInstantiationVerifyOptions
     * @covers  Molajo\IoC\Instantiate::instantiateClass
     * @covers  Molajo\IoC\Instantiate::processReflectionDependencies
     * @covers  Molajo\IoC\Instantiate::instantiateClassNotStaticTryCatch
     * @covers  Molajo\IoC\Instantiate::instantiateClassNotStatic
     * @covers  Molajo\IoC\Instantiate::instantiateStatic
     * @covers  Molajo\IoC\Instantiate::onAfterInstantiation
     * @covers  Molajo\IoC\Instantiate::getProductValue
     * @covers  Molajo\IoC\Instantiate::getProductValueStatic
     * @covers  Molajo\IoC\Instantiate::getProductValueInstance
     * @covers  Molajo\IoC\Instantiate::getProductValueProperties
     * @covers  Molajo\IoC\Instantiate::getProductValueDoNotSave
     * @covers  Molajo\IoC\Instantiate::removeContainerEntries
     * @covers  Molajo\IoC\Instantiate::setContainerEntries
     * @covers  Molajo\IoC\Instantiate::scheduleFactories
     * @covers  Molajo\IoC\Adapter::__construct
     * @covers  Molajo\IoC\Adapter::setConstructorOptions
     * @covers  Molajo\IoC\Adapter::getNamespace
     * @covers  Molajo\IoC\Adapter::getOptions
     * @covers  Molajo\IoC\Adapter::getStoreContainerEntryIndicator
     * @covers  Molajo\IoC\Adapter::readFile
     * @covers  Molajo\IoC\Adapter::readFileIntoArray
     * @covers  Molajo\IoC\Adapter::sortObject
     * @covers  Molajo\IoC\Adapter::sortObjectLoadIntoArray
     * @covers  Molajo\IoC\Adapter::sortObjectLoadSortedArrayIntoObject
     *
     * @expectedException \CommonApi\Exception\RuntimeException
     * @expectedExceptionMessage IoC Create::getAdapter Class does not exist: Molajo\IoC\DoesNotExist
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

        $create = new Create($options);
        $create->instantiateFactoryMethod();
    }
}
