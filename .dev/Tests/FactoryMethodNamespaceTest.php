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
class FactoryMethodNamespaceTest extends PHPUnit_Framework_TestCase
{
    /**
     * Pass in Full namespace
     *
     * @covers  Molajo\IoC\FactoryMethodNamespace::__construct
     * @covers  Molajo\IoC\FactoryMethodNamespace::get
     * @covers  Molajo\IoC\FactoryMethodNamespace::getFactoryNamespaceFolderFile
     * @covers  Molajo\IoC\FactoryMethodNamespace::checkClassExists
     * @covers  Molajo\IoC\FactoryMethodNamespace::getLastFolder
     *
     * @return void
     * @since  1.0.0
     */
    public function testPassInNamespaceFull()
    {
        $options                    = array();
        $options['product_name']    = 'Dispatcher';
        $options['container_key']   = 'Molajo\\Factories\\Dispatcher';
        $options['ioc_id']          = 1;
        $options['factory_method_namespace']
            = $options['container_key'] . '\\' . 'DispatcherDogFactoryMethod';

        $standard_adapter_namespace = 'Molajo\\IoC\\StandardFactoryMethod';

        $factory_method_namespace = new FactoryMethodNamespace($standard_adapter_namespace, $options);

        $this->assertEquals($factory_method_namespace->get(), $options);
    }

    /**
     * Pass in namespace
     *
     * @covers  Molajo\IoC\FactoryMethodNamespace::__construct
     * @covers  Molajo\IoC\FactoryMethodNamespace::get
     * @covers  Molajo\IoC\FactoryMethodNamespace::getFactoryNamespaceFolderFile
     * @covers  Molajo\IoC\FactoryMethodNamespace::checkClassExists
     * @covers  Molajo\IoC\FactoryMethodNamespace::getLastFolder
     *
     * @return void
     * @since  1.0.0
     */
    public function testPassInNamespacePlus()
    {
        $options                    = array();
        $options['product_name']    = 'Dispatcher';
        $options['container_key']   = 'Molajo\\Factories\\Dispatcher';
        $options['ioc_id']          = 1;
        $options['factory_method_namespace'] = $options['container_key'];

        $standard_adapter_namespace = 'Molajo\\IoC\\StandardFactoryMethod';

        $factory_method_namespace = new FactoryMethodNamespace($standard_adapter_namespace, $options);

        $options['factory_method_namespace'] .= '\\DispatcherFactoryMethod';

        $this->assertEquals($factory_method_namespace->get(), $options);
    }

    /**
     * Test product name as namespace + folder + 'FactoryMethod'
     *
     * @covers  Molajo\IoC\FactoryMethodNamespace::__construct
     * @covers  Molajo\IoC\FactoryMethodNamespace::get
     * @covers  Molajo\IoC\FactoryMethodNamespace::getFactoryNamespaceFolderFile
     * @covers  Molajo\IoC\FactoryMethodNamespace::checkClassExists
     * @covers  Molajo\IoC\FactoryMethodNamespace::getLastFolder
     *
     * @return void
     * @since  1.0.0
     */
    public function testProductNamePlus()
    {
        $options                    = array();
        $options['product_name']    = 'Molajo\\Factories\\Dispatcher';
        $options['ioc_id']          = 1;
        $standard_adapter_namespace = 'Molajo\\IoC\\StandardFactoryMethod';

        $factory_method_namespace = new FactoryMethodNamespace($standard_adapter_namespace, $options);

        $options['factory_method_namespace']
            = $options['product_name'] . '\\' . 'DispatcherFactoryMethod';

        $this->assertEquals($factory_method_namespace->get(), $options);
    }

    /**
     * Test container_key as namespace  + folder + 'FactoryMethod'
     *
     * @covers  Molajo\IoC\FactoryMethodNamespace::__construct
     * @covers  Molajo\IoC\FactoryMethodNamespace::get
     * @covers  Molajo\IoC\FactoryMethodNamespace::getFactoryNamespaceFolderFile
     * @covers  Molajo\IoC\FactoryMethodNamespace::checkClassExists
     * @covers  Molajo\IoC\FactoryMethodNamespace::getLastFolder
     *
     * @return void
     * @since  1.0.0
     */
    public function testContainerKeyNamespace()
    {
        $options                    = array();
        $options['product_name']    = 'Dispatcher';
        $options['container_key']   = 'Molajo\\Factories\\Dispatcher';
        $options['ioc_id']          = 1;
        $standard_adapter_namespace = 'Molajo\\IoC\\StandardFactoryMethod';

        $factory_method_namespace = new FactoryMethodNamespace($standard_adapter_namespace, $options);

        $options['factory_method_namespace']
            = $options['container_key'] . '\\' . $options['product_name'] . 'FactoryMethod';

        $this->assertEquals($factory_method_namespace->get(), $options);
    }
}
