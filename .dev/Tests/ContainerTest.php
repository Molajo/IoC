<?php
/**
 * Test Class
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Tests;

use Molajo\Services\Cache;
use Molajo\Services\Configuration;
use Molajo\IoC\Container;

/**
 * Initialise
 *
 * @return  object
 * @since   1.0
 */
class ContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var object
     */
    protected $object;

    /**
     * Setup
     *
     * @return  $this
     * @since   1.0
     */
    protected function setUp()
    {
        $service_library = 'Molajo\\Services';

        $this->object = new Container($service_library);
    }

    /**
     * @covers Molajo\Service\Type\Application::close
     * @todo   Implement testClose().
     */
    public function testGetService()
    {
        $service = 'Cache';
        $options = array();
        $results = $this->object->getService($service, $options);

        var_dump($results);

        $this->assertEquals(true, is_object($results));

        return $this;
    }

    /**
     * Tear Down
     *
     * @return  $this
     * @since   1.0
     */
    protected function tearDown()
    {
    }
}
