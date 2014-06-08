<?php
/**
 * Map Factories Test
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\IoC;

use stdClass;
use PHPUnit_Framework_TestCase;

/**
 * Map Factories Test
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
class MapFactoriesTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test Empty configuration
     *
     * @covers  Molajo\IoC\MapFactories::__construct
     * @covers  Molajo\IoC\MapFactories::createMap
     * @covers  Molajo\IoC\MapFactories::map
     * @covers  Molajo\IoC\MapFactories::mapFolder
     * @covers  Molajo\IoC\MapFactories::getFolders
     * @covers  Molajo\IoC\MapFactories::getFolderFiles
     *
     * @return void
     * @since  1.0.0
     */
    public function testEmpty()
    {
        $folders[] = __DIR__ . '/Files/Factories';
        $folders[] = __DIR__ . '/Files/Factories2';

        $adapter_namespace_prefix = 'Molajo\Factories';

        $adapter_alias_filename = __DIR__ . '/Files/MapFactoriesTest.json';

        $container = new MapFactories(
            $folders,
            $adapter_namespace_prefix,
            $adapter_alias_filename
        );

        $container->createMap();

        $this->assertEquals(
            file_get_contents(__DIR__ . '/Files/MapFactoriesTestCompare.json'),
            file_get_contents(__DIR__ . '/Files/MapFactoriesTest.json')
        );
    }
}
