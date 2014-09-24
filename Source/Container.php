<?php
/**
 * Container - storage of product values
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC;

use CommonApi\Exception\InvalidArgumentException;
use CommonApi\IoC\ContainerInterface;
use Molajo\IoC\Container\Utilities;

/**
 * Container - storage of product values
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class Container extends Utilities implements ContainerInterface
{
    /**
     * Determines if the entry identified by the $key exists within the Container
     *
     * @param   string  $key
     *
     * @return  bool
     * @since   1.0.0
     */
    public function has($key)
    {
        if ($this->getKey($key, true) === false) {
            return false;
        }

        return true;
    }

    /**
     * Get Contents from Container Entry associated with the key or return false
     *
     * @param   string $key
     *
     * @return  mixed
     * @since   1.0.0
     */
    public function get($key)
    {
        $key = $this->getKey($key, true);

        return $this->container_registry[$key];
    }

    /**
     * Set the Container Entry with the associated value
     *
     * @param   string $key
     * @param   mixed  $value
     *
     * @return  $this
     * @since   1.0.0
     */
    public function set($key, $value)
    {
        $this->container_registry[$key] = $value;

        return $this;
    }

    /**
     * Remove the existing container instance
     *
     * @param   string $key
     *
     * @return  $this
     * @since   1.0.0
     */
    public function remove($key)
    {
        $key = $this->getKey($key, true);

        unset($this->container_registry[$key]);

        return $this;
    }

    /**
     * Get the preferred key:
     *
     *  1. Existing container entry key
     *  2. Factory method namespace key
     *  3. Use the value provided
     *
     * @param   string  $key
     * @param   boolean $must_exist
     *
     * @return  mixed
     * @since   1.0.0
     */
    public function getKey($key, $must_exist = false)
    {
        $testing_methods = array(
            'testContainerKey',
            'testAlias',
            'createNewKey'
        );

        $array_input = array('dummy');

        return $this->testLoop($key, $must_exist, $testing_methods, $array_input);
    }
}
