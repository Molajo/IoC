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
    public function testNormalNamespace()
    {
        $options                  = array();
        $options['product_name']  = 'Dispatcher';
        $options['container_key'] = 'Molajo\\Factories\\Dispatcher';
        $options['ioc_id']        = 1;
        $options['factory_method_namespace']
                                  = $options['container_key'] . '\\' . 'DispatcherFactoryMethod';

        $class   = 'Molajo\\IoC\\Product\\Create';
        $create  = new $class($options);
        $results = $create->instantiateFactoryMethod();

//file_put_contents(__DIR__ . '/DispatcherFactoryMethod.txt', serialize($results));

        $this->assertEquals(
            file_get_contents(__DIR__ . '/DispatcherFactoryMethod.txt'),
            serialize($create->instantiateFactoryMethod())
        );
    }

    /**
     * Pass in Standard Factory Method Namespace
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
    public function testStandardNamespace()
    {
        $options                             = array();
        $options['product_name']             = 'Standard';
        $options['container_key']            = 'Molajo\\IoC\\FactoryMethod\\Standard';
        $options['ioc_id']                   = 1;
        $options['factory_method_namespace'] = $options['container_key'];

        $class   = 'Molajo\\IoC\\Product\\Create';
        $create  = new $class($options);
        $results = $create->instantiateFactoryMethod();

//file_put_contents(__DIR__ . '/Standard.txt', serialize($results));

        $this->assertEquals(
            file_get_contents(__DIR__ . '/Standard.txt'),
            serialize($create->instantiateFactoryMethod())
        );
    }

    /**
     * Pass in Standard Factory Method Namespace
     *
     * @covers                   Molajo\IoC\Product\Create::__construct
     * @covers                   Molajo\IoC\Product\Create::instantiateFactoryMethod
     * @covers                   Molajo\IoC\Product\Create::getAdapter
     * @covers                   Molajo\IoC\Product\Create::getController
     *
     * @covers                   Molajo\IoC\FactoryMethod\Controller::__construct
     * @covers                   Molajo\IoC\FactoryMethod\Controller::getNamespace
     * @covers                   Molajo\IoC\FactoryMethod\Controller::getOptions
     * @covers                   Molajo\IoC\FactoryMethod\Controller::getStoreContainerEntryIndicator
     * @covers                   Molajo\IoC\FactoryMethod\Controller::setDependencies
     * @covers                   Molajo\IoC\FactoryMethod\Controller::removeDependency
     * @covers                   Molajo\IoC\FactoryMethod\Controller::setDependencyValue
     * @covers                   Molajo\IoC\FactoryMethod\Controller::getRemainingDependencyCount
     * @covers                   Molajo\IoC\FactoryMethod\Controller::onBeforeInstantiation
     * @covers                   Molajo\IoC\FactoryMethod\Controller::instantiateClass
     * @covers                   Molajo\IoC\FactoryMethod\Controller::onAfterInstantiation
     * @covers                   Molajo\IoC\FactoryMethod\Controller::getProductValue
     * @covers                   Molajo\IoC\FactoryMethod\Controller::removeContainerEntries
     * @covers                   Molajo\IoC\FactoryMethod\Controller::setContainerEntries
     * @covers                   Molajo\IoC\FactoryMethod\Controller::scheduleFactories
     *
     * @covers                   Molajo\IoC\FactoryMethod\Base::setDependencies
     * @covers                   Molajo\IoC\FactoryMethod\Base::setDependencyUsingReflection
     * @covers                   Molajo\IoC\FactoryMethod\Base::setDependencyUsingReflectionInterface
     * @covers                   Molajo\IoC\FactoryMethod\Base::onBeforeInstantiation
     * @covers                   Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationDependencyValues
     * @covers                   Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationReflectionLoop
     * @covers                   Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationReflection
     * @covers                   Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationVerifyDependency
     * @covers                   Molajo\IoC\FactoryMethod\Base::onBeforeInstantiationVerifyOptions
     * @covers                   Molajo\IoC\FactoryMethod\Instantiate::instantiateClass
     * @covers                   Molajo\IoC\FactoryMethod\Instantiate::processReflectionDependencies
     * @covers                   Molajo\IoC\FactoryMethod\Instantiate::instantiateClassNotStaticTryCatch
     * @covers                   Molajo\IoC\FactoryMethod\Instantiate::instantiateClassNotStatic
     * @covers                   Molajo\IoC\FactoryMethod\Instantiate::instantiateStatic
     * @covers                   Molajo\IoC\FactoryMethod\Instantiate::onAfterInstantiation
     * @covers                   Molajo\IoC\FactoryMethod\Instantiate::getProductValue
     * @covers                   Molajo\IoC\FactoryMethod\Instantiate::getProductValueStatic
     * @covers                   Molajo\IoC\FactoryMethod\Instantiate::getProductValueInstance
     * @covers                   Molajo\IoC\FactoryMethod\Instantiate::getProductValueProperties
     * @covers                   Molajo\IoC\FactoryMethod\Instantiate::getProductValueDoNotSave
     * @covers                   Molajo\IoC\FactoryMethod\Instantiate::removeContainerEntries
     * @covers                   Molajo\IoC\FactoryMethod\Instantiate::setContainerEntries
     * @covers                   Molajo\IoC\FactoryMethod\Instantiate::scheduleFactories
     * @covers                   Molajo\IoC\FactoryMethod\Adapter::__construct
     * @covers                   Molajo\IoC\FactoryMethod\Adapter::setConstructorOptions
     * @covers                   Molajo\IoC\FactoryMethod\Adapter::getNamespace
     * @covers                   Molajo\IoC\FactoryMethod\Adapter::getOptions
     * @covers                   Molajo\IoC\FactoryMethod\Adapter::getStoreContainerEntryIndicator
     * @covers                   Molajo\IoC\FactoryMethod\Adapter::readFile
     * @covers                   Molajo\IoC\FactoryMethod\Adapter::readFileIntoArray
     * @covers                   Molajo\IoC\FactoryMethod\Adapter::sortObject
     * @covers                   Molajo\IoC\FactoryMethod\Adapter::sortObjectLoadIntoArray
     * @covers                   Molajo\IoC\FactoryMethod\Adapter::sortObjectLoadSortedArrayIntoObject
     *
     * @expectedException \CommonApi\Exception\RuntimeException
     * @expectedExceptionMessage IoC Create::getAdapter Class does not exist: Molajo\IoC\DoesNotExist
     *
     * @return void
     * @since                    1.0.0
     */
    public function testClassDoesNotExist()
    {
        $options                             = array();
        $options['product_name']             = 'Standard';
        $options['container_key']            = 'Molajo\\IoC\\DoesNotExist';
        $options['ioc_id']                   = 1;
        $options['factory_method_namespace'] = $options['container_key'];

        $class  = 'Molajo\\IoC\\Product\\Create';
        $create = new $class($options);
        $create->instantiateFactoryMethod();
    }
}
