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
     * Test Empty configuration
     *
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
     * @return void
     * @since   1.0.0
     */
    public function setup()
    {
        $class_dependencies       = __DIR__ . '/Files/ClassDependencies.json';
        $this->class_dependencies = new ClassDependencies($class_dependencies);
    }

    /**
     * Test Empty configuration
     *
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
     * @return  void
     * @since  1.0.0
     */
    public function testDispatcher()
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
}
