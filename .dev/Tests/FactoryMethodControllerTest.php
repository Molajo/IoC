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
        $options                      = array();
        $options['product_name']      = 'EventDispatcher';
        $options['product_namespace'] = 'Molajo\\Event\\EventDispatcher';

        $class          = 'Molajo\\IoC\\FactoryMethod\\Standard';
        $factory_method = new $class($options);
        $class          = 'Molajo\\IoC\\FactoryMethod\\Controller';
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
