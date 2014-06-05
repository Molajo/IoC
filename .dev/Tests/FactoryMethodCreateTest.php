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
}
