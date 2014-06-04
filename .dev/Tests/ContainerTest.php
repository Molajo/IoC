<?php
/**
 * Container Test
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\IoC;

use CommonApi\Exception\RuntimeException;
use CommonApi\IoC\ContainerInterface;
use stdClass;
use PHPUnit_Framework_TestCase;

/**
 * Container Test
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
class ContainerTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test Empty configuration
     *
     * @covers  Molajo\IoC\Container::__construct
     * @covers  Molajo\IoC\Container::has
     * @covers  Molajo\IoC\Container::get
     * @covers  Molajo\IoC\Container::set
     * @covers  Molajo\IoC\Container::remove
     *
     * @return void
     * @since   1.0
     */
    public function testEmpty()
    {
        /**
        $class   = new \ReflectionClass('Molajo\IoC\Container');
        $methods = $class->getMethods();
        foreach ($methods as $method) {
        echo '     * @covers  ' . $method->class . '::' . $method->name . PHP_EOL;
        }
        die;
*/
        $container = new Container();

        $a_stuff = new stdClass();
        $a_stuff->here = 'more';

        $this->assertEquals($container->has('a'), false);
        $this->assertEquals($container->set('a', $a_stuff), $container);
        $this->assertEquals($container->has('a'), true);
        $this->assertEquals($container->get('a'), $a_stuff);
        $this->assertEquals($container->remove('a'), $container);
        $this->assertEquals($container->has('a'), false);
    }
    /**
     * Test Empty configuration
     *
     * @covers  Molajo\IoC\Container::__construct
     * @covers  Molajo\IoC\Container::has
     * @covers  Molajo\IoC\Container::get
     * @covers  Molajo\IoC\Container::set
     * @covers  Molajo\IoC\Container::remove
     * @expectedException \CommonApi\Exception\RuntimeException
     * @expectedExceptionMessage IoCC Entry for Key: a does not exist
     *
     * @return  void
     * @since   1.0
     */
    public function testGetNotExisting()
    {
        $container = new Container();

        $container->get('a');
    }

    /**
     * Test loaded configuration
     *
     * @covers  Molajo\IoC\Container::__construct
     * @covers  Molajo\IoC\Container::has
     * @covers  Molajo\IoC\Container::get
     * @covers  Molajo\IoC\Container::set
     * @covers  Molajo\IoC\Container::remove
     *
     * @return void
     * @since   1.0
     */
    public function testLoadConfiguration()
    {
        $aliases = readJsonFile(__DIR__ . '\FactoryMethodAliases.json');
        $container = new Container($aliases);

        $a_stuff = new stdClass();
        $a_stuff->here = 'more';

        $this->assertEquals($container->has('Molajo\\Factories\\Dispatcher'), false);
        $this->assertEquals($container->set('Molajo\\Factories\\Dispatcher', $a_stuff), $container);
        $this->assertEquals($container->has('Molajo\\Factories\\Dispatcher'), true);
        $this->assertEquals($container->get('Molajo\\Factories\\Dispatcher'), $a_stuff);
        $this->assertEquals($container->remove('Molajo\\Factories\\Dispatcher'), $container);
        $this->assertEquals($container->has('Molajo\\Factories\\Dispatcher'), false);
    }

    /**
     * Test loaded configuration for get entry not existing
     *
     * @covers  Molajo\IoC\Container::__construct
     * @covers  Molajo\IoC\Container::has
     * @covers  Molajo\IoC\Container::get
     * @covers  Molajo\IoC\Container::set
     * @covers  Molajo\IoC\Container::remove
     * @expectedException \CommonApi\Exception\RuntimeException
     * @expectedExceptionMessage IoCC Entry for Key: Molajo\Factories\Dispatcher does not exist
     *
     * @return  void
     * @since   1.0
     */
    public function testLoadConfigurationNotExisting()
    {
        $aliases = readJsonFile(__DIR__ . '\FactoryMethodAliases.json');
        $container = new Container($aliases);

        $container->get('Molajo\\Factories\\Dispatcher');
    }

    /**
     * Test loaded configuration
     *
     * @covers  Molajo\IoC\Container::__construct
     * @covers  Molajo\IoC\Container::has
     * @covers  Molajo\IoC\Container::get
     * @covers  Molajo\IoC\Container::set
     * @covers  Molajo\IoC\Container::remove
     *
     * @return void
     * @since   1.0
     */
    public function testAliases()
    {
        $aliases = readJsonFile(__DIR__ . '/Files/FactoryMethodAliases.json');

        $container = new Container($aliases);

        $a_stuff = new stdClass();
        $a_stuff->here = 'more';

        //$this->assertEquals($container->has('Dispatcher'), false);
        $this->assertEquals($container->set('Dispatcher', $a_stuff), $container);
        $this->assertEquals($container->has('Molajo\\Factories\\Dispatcher'), true);
        $this->assertEquals($container->get('Molajo\\Factories\\Dispatcher'), $a_stuff);
        $this->assertEquals($container->remove('Molajo\\Factories\\Dispatcher'), $container);
        $this->assertEquals($container->has('Molajo\\Factories\\Dispatcher'), false);
    }
}
function readJsonFile($file_name)
{
    $temp_array = array();

    if (file_exists($file_name)) {
    } else {
        return array();
    }

    $input = file_get_contents($file_name);

    $temp = json_decode($input);

    if (count($temp) > 0) {
        $temp_array = array();
        foreach ($temp as $key => $value) {
            $temp_array[$key] = $value;
        }
    }

    return $temp_array;
}
