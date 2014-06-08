<?php
/**
 * Factory Method Controller Test
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\IoC;

use PHPUnit_Framework_TestCase;

/**
 * Factory Method Controller Test
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
class ControllerTest extends PHPUnit_Framework_TestCase
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
     * @since   1.0.0
     */
    public function testNormalNamespace()
    {
        $options                      = array();
        $options['product_name']      = 'EventDispatcher';
        $options['product_namespace'] = 'Molajo\\Event\\EventDispatcher';

        $class          = 'Molajo\\IoC\\FactoryMethod\\Standard';
        $factory_method = new $class($options);
        $class          = 'Molajo\\IoC\\Controller';
        $controller     = new $class($factory_method);

        $this->assertEquals($controller->getNamespace($options), $options['product_namespace']);
        $this->assertEquals($controller->getOptions(), array());
        $this->assertEquals($controller->getStoreContainerEntryIndicator(), false);
        $this->assertEquals($controller->setDependencies(array()), array());
        $this->assertEquals($controller->getRemainingDependencyCount(), 0);
        $controller->onBeforeInstantiation();
        $controller->instantiateClass();
        $controller->onAfterInstantiation();

//file_put_contents(__DIR__ . '/ControllerED.txt', serialize($controller->getProductValue()));

        $this->assertEquals(file_get_contents(__DIR__ . '/ControllerED.txt'),
            serialize($controller->getProductValue()));
    }
}
