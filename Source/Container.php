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
        $this->setFactoryMethodNamespaces($factory_method_aliases);
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
     * @since  1.0.0
     * @throws  \CommonApi\Exception\InvalidArgumentException
     */
    public function get($key)
    {
        $key = $this->action($key, 'get');

        return $this->container_registry[$key];
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
        $key = $this->action($key, 'remove');

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

    /**
     * Perform action
     *
     * @param   string $key
     * @param   string $action
     *
     * @return  string
     * @since   1.0.0
     * @throws  \CommonApi\Exception\InvalidArgumentException
     */
    protected function action($key, $action)
    {
        $results = $this->getKey($key, true);

        if ($results === false) {
            throw new InvalidArgumentException(
                'Get IoCC Entry for Key: ' . $key . ' Action: ' . $action . ' does not exist'
            );
        }

        return $results;
    }

    /**
     * If a container key does not exist, create a new key
     *
     * @param   string  $key
     * @param   boolean $must_exist
     *
     * @return  mixed
     * @since   1.0.0
     */
    protected function createNewKey($key, $must_exist = false)
    {
        if ($must_exist === true) {
            return false;
        }

        $results = $this->getKeyNamespace($key);

        if ($results === false) {
            return $key;
        }

        $key = $results;

        return $key;
    }

    /**
     * Set factory method namespace array entries associated with alias keys
     *
     * @param   string  $key
     * @param   boolean $must_exist
     *
     * @return  mixed
     * @since   1.0.0
     */
    protected function testAlias($key, $must_exist = false)
    {
        $testing_methods = array('testAliasKey');

        $array_input = array('factory_method_aliases', 'factory_method_namespaces');

        return $this->testLoop($key, $must_exist, $testing_methods, $array_input);
    }

    /**
     * Set factory method namespace array entries associated with alias keys
     *
     * @param   string  $key
     * @param   boolean $must_exist
     * @param   array   $array
     *
     * @return  false|string
     * @since   1.0.0
     */
    protected function testAliasKey($key, $must_exist = false, array $array = array())
    {
        if (count($array) === 0) {
            return false;
        }

        if (isset($array[$key])) {

            $results = $this->testContainerKey($array[$key], $must_exist);

            if ($results === false) {
            } else {
                echo $key;
                $key = $results;
                return $key;
            }
        }

        return false;
    }

    /**
     * Determine if a container entry exists for this key
     *
     * @param   string  $key
     *
     * @return  string|false
     * @since   1.0.0
     */
    protected function testContainerKey($key)
    {
        if (isset($this->container_registry[$key])) {
            return $key;
        }

        return false;
    }

    /**
     * Set factory method namespace array entries associated with alias keys
     *
     * @param   string $key
     *
     * @return  mixed
     * @since   1.0.0
     */
    protected function getKeyNamespace($key)
    {
        if (isset($this->factory_method_aliases[$key]) === true) {
            return $this->factory_method_aliases[$key];
        }

        if (isset($this->factory_method_namespaces[$key]) === true) {
            return $key;
        }

        return false;
    }

    /**
     * Generic loop used for testing keys and reducing code duplication
     *
     * @param   string     $key
     * @param   boolean    $must_exist
     * @param   string[]   $testing_methods
     * @param   string[]   $array_input
     *
     * @return  mixed
     * @since   1.0.0
     */
    protected function testLoop($key, $must_exist, array $testing_methods, array $array_input)
    {
        foreach ($array_input as $input) {

            foreach ($testing_methods as $method) {

                $result = $this->testLoopEvaluate($key, $must_exist, $method, $input);

                if ($result === false) {
                } else {
                    $key = $result;

                    return $key;
                }
            }
        }

        return false;
    }

    /**
     * Generic loop used for testing keys and reducing code duplication
     *
     * @param   string  $key
     * @param   boolean $must_exist
     * @param   string  $method
     * @param   string  $input
     *
     * @return  mixed
     * @since   1.0.0
     */
    protected function testLoopEvaluate($key, $must_exist, $method, $input)
    {
        if ($input === 'dummy') {
            $result = $this->$method($key, $must_exist);
            return $result;
        }

        return $this->$method($key, $must_exist, $this->$input);
    }

    /**
     * Set factory method namespace array entries associated with alias keys
     *
     * @param   array $factory_method_aliases
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setFactoryMethodNamespaces(array $factory_method_aliases = array())
    {
        $this->factory_method_aliases    = $factory_method_aliases;
        $this->factory_method_namespaces = array();

        if (count($this->factory_method_aliases) > 0) {
            foreach ($this->factory_method_aliases as $key => $value) {
                $this->factory_method_namespaces[$value] = $key;
            }
        }

        return $this;
    }
}
