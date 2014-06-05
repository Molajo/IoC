<?php
/**
 * Inversion of Control Container
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC;

use CommonApi\Exception\InvalidArgumentException;
use CommonApi\IoC\ContainerInterface;

/**
 * Inversion of Control Container
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class Container implements ContainerInterface
{
    /**
     * Container Registry
     *
     * @var     array
     * @since  1.0.0
     */
    protected $container_registry = array();

    /**
     * Factory Method Aliases => Namespaces
     *
     * @var     array
     * @since  1.0.0
     */
    protected $factory_method_aliases = array();

    /**
     * Factory Method Namespaces => Aliases
     *
     * @var     array
     * @since  1.0.0
     */
    protected $factory_method_namespaces = array();

    /**
     * Constructor
     *
     * @param  array $factory_method_aliases
     *
     * @since  1.0.0
     */
    public function __construct(
        array $factory_method_aliases = array()
    ) {
        $this->factory_method_aliases = $factory_method_aliases;
        $this->setFactoryMethodNamespaces();
    }

    /**
     * Determines if the entry identified by the $key exists within the Container
     *
     * @param   string $key
     *
     * @return  bool
     * @since  1.0.0
     */
    public function has($key)
    {
        if ($this->getKey($key, false) === false) {
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
     * @since  1.0.0
     * @throws  \CommonApi\Exception\InvalidArgumentException
     */
    public function get($key)
    {
        $results = $this->getKey($key, true);

        if ($results === false) {
            throw new InvalidArgumentException('Get IoCC Entry for Key: ' . $key . ' does not exist');
        }

        return $this->container_registry[$results];
    }

    /**
     * Set the Container Entry with the associated value
     *
     * @param   string $key
     * @param   mixed  $value
     *
     * @return  $this
     * @since  1.0.0
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
     * @since  1.0.0
     * @throws  \CommonApi\Exception\InvalidArgumentException
     */
    public function remove($key)
    {
        $results = $this->getKey($key);

        if ($results === false) {
            throw new InvalidArgumentException('Remove IoCC Entry for Key: ' . $key . ' does not exist');
        }

        unset($this->container_registry[$results]);

        return $this;
    }

    /**
     * Determine if an alias value exists for this key
     *
     * @param   string  $key
     * @param   boolean $exception
     *
     * @return  boolean
     * @since  1.0.0
     */
    public function getKey($key)
    {
        if ($this->testContainerKey($key) === true) {
            return $key;
        }

        return $this->testAlias($key);
    }

    /**
     * Set factory method namespace array entries associated with alias keys
     *
     * @param   string  $key
     *
     * @return  boolean
     * @since  1.0.0
     */
    protected function testAlias($key)
    {
        $arrays = array('factory_method_aliases', 'factory_method_namespaces');

        foreach ($arrays as $array) {

            $test = $this->testAliasKey($key, $this->$array);

            if ($test === false) {
            } else {
                $key = $test;
                return $key;
            }
        }

        return false;
    }

    /**
     * Set factory method namespace array entries associated with alias keys
     *
     * @param   string  $key
     * @param   array   $array
     *
     * @return  boolean
     * @since  1.0.0
     */
    protected function testAliasKey($key, array $array = array())
    {
        if (count($array) === 0) {
            return false;
        }

        if (isset($array[$key])) {
            if ($this->testContainerKey($array[$key]) === true) {
                return $array[$key];
            }
        }

        return false;
    }

    /**
     * Determine if a container entry exists for this key
     *
     * @param   string  $key
     *
     * @return  boolean
     * @since  1.0.0
     */
    protected function testContainerKey($key)
    {
        if (isset($this->container_registry[$key])) {
            return true;
        }

        return false;
    }

    /**
     * Set factory method namespace array entries associated with alias keys
     *
     * @return  $this
     * @since  1.0.0
     */
    protected function setFactoryMethodNamespaces()
    {
        $this->factory_method_namespaces = array();

        if (count($this->factory_method_aliases) > 0) {
            foreach ($this->factory_method_aliases as $key => $value) {
                $this->factory_method_namespaces[$value] = $key;
            }
        }

        return $this;
    }
}
