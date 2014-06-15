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
class NamespaceTest extends PHPUnit_Framework_TestCase
{
    /**
     * Pass in Full namespace
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
     * @return  void
     * @since   1.0.0
     */
    public function testPassInNamespaceFull()
    {
        $options                    = array();
        $options['product_name']    = 'Dispatcher';
        $options['container_key']   = 'Molajo\\Factories\\Dispatcher';
        $options['ioc_id']          = 1;
        $options['factory_method_namespace']
            = $options['container_key'] . '\\' . 'DispatcherFactoryMethod';

        $standard_adapter_namespace = 'Molajo\\IoC\\FactoryMethod\\Standard';

        $class = 'Molajo\\IoC\\Product\\SetNamespace';
        $factory_method_namespace = new $class($standard_adapter_namespace);

        $this->assertEquals($factory_method_namespace->get($options), $options);
    }

    /**
     * Pass in namespace
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
     * @return  void
     * @since   1.0.0
     */
    public function testPassInNamespacePlus()
    {
        $options                    = array();
        $options['product_name']    = 'Dispatcher';
        $options['container_key']   = 'Molajo\\Factories\\Dispatcher';
        $options['ioc_id']          = 1;
        $options['factory_method_namespace'] = $options['container_key'];

        $standard_adapter_namespace = 'Molajo\\IoC\\FactoryMethod\\Standard';

        $class = 'Molajo\\IoC\\Product\\SetNamespace';
        $factory_method_namespace = new $class($standard_adapter_namespace);

        $options['factory_method_namespace'] .= '\\DispatcherFactoryMethod';

        $this->assertEquals($factory_method_namespace->get($options), $options);
    }

    /**
     * Test product name as namespace + folder + 'FactoryMethod'
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
     * @return  void
     * @since   1.0.0
     */
    public function testProductNamePlus()
    {
        $options                    = array();
        $options['product_name']    = 'Molajo\\Factories\\Dispatcher';
        $options['ioc_id']          = 1;
        $standard_adapter_namespace = 'Molajo\\IoC\\FactoryMethod\\Standard';

        $class = 'Molajo\\IoC\\Product\\SetNamespace';
        $factory_method_namespace = new $class($standard_adapter_namespace);

        $options['factory_method_namespace']
            = $options['product_name'] . '\\' . 'DispatcherFactoryMethod';

        $this->assertEquals($factory_method_namespace->get($options), $options);
    }

    /**
     * Test container_key as namespace  + folder + 'FactoryMethod'
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
     * @return  void
     * @since   1.0.0
     */
    public function testContainerKeyNamespace()
    {
        $options                    = array();
        $options['product_name']    = 'Dispatcher';
        $options['container_key']   = 'Molajo\\Factories\\Dispatcher';
        $options['ioc_id']          = 1;
        $standard_adapter_namespace = 'Molajo\\IoC\\FactoryMethod\\Standard';

        $class = 'Molajo\\IoC\\Product\\SetNamespace';
        $factory_method_namespace = new $class($standard_adapter_namespace);

        $options['factory_method_namespace']
            = $options['container_key'] . '\\' . $options['product_name'] . 'FactoryMethod';

        $this->assertEquals($factory_method_namespace->get($options), $options);
    }

    /**
     * No namespace
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
     * @return  void
     * @since   1.0.0
     */
    public function testNoNamespace()
    {
        $options                    = array();
        $options['product_name']    = 'Whoknows';
        $options['container_key']   = 'Whoknows';
        $options['ioc_id']          = 1;
        $options['factory_method_namespace'] = null;
        $options['product_namespace']    = 'Molajo\\Who\\Whoknows';

        $standard_adapter_namespace = 'Molajo\\IoC\\FactoryMethod\\Standard';

        $class = 'Molajo\\IoC\\Product\\SetNamespace';
        $factory_method_namespace = new $class($standard_adapter_namespace);

        $options['factory_method_namespace'] = $standard_adapter_namespace;

        $this->assertEquals($factory_method_namespace->get($options), $options);
    }

    /**
     * No default namespace
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
     * @return  void
     * @since   1.0.0
     */
    public function testNoDefaultNamespace()
    {
        $options                    = array();
        $options['product_name']    = 'Whoknows';
        $options['container_key']   = 'Whoknows';
        $options['ioc_id']          = 1;
        $options['factory_method_namespace'] = null;
        $options['product_namespace']    = 'Molajo\\Who\\Whoknows';

        $standard_adapter_namespace = null;

        $class = 'Molajo\\IoC\\Product\\SetNamespace';
        $factory_method_namespace = new $class($standard_adapter_namespace);

        $options['factory_method_namespace'] = 'Molajo\IoC\FactoryMethod\Standard';

        $this->assertEquals($factory_method_namespace->get($options), $options);
    }
}
