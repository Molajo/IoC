<?php
/**
 * Container - storage of product values
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC\Container;

use CommonApi\Exception\InvalidArgumentException;

/**
 * Utilities for Container
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
abstract class Utilities
{
    /**
     * Container Registry
     *
     * @var     array
     * @since   1.0.0
     */
    protected $container_registry = array();

    /**
     * Factory Method Aliases => Namespaces
     *
     * @var     array
     * @since   1.0.0
     */
    protected $factory_method_aliases = array();

    /**
     * Factory Method Namespaces => Aliases
     *
     * @var     array
     * @since   1.0.0
     */
    protected $factory_method_namespaces = array();

    /**
     * Factory Method Aliases => Namespaces
     *
     * @var     boolean
     * @since   1.0.0
     */
    protected $on = false;

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
        $this->setNamespaces($factory_method_aliases);
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
        if (isset($array[$key])) {
            return $this->testAliasKeyExists($key, $array[$key], $must_exist);
        }

        return false;
    }

    /**
     * Set factory method namespace array entries associated with alias keys
     *
     * @param   string  $key
     * @param   string  $alias_key
     * @param   boolean $must_exist
     *
     * @return  false|string
     * @since   1.0.0
     */
    protected function testAliasKeyExists($key, $alias_key, $must_exist)
    {
        if ($must_exist === false) {
            return $alias_key;
        }

        if ($this->testContainerKeyMustExist($alias_key) === true
            || $this->testContainerKeyMustExist($key) === true
        ) {
            return $alias_key;
        }

        return false;
    }

    /**
     * Determine if a container entry exists for this key
     *
     * @param   string $key
     *
     * @return  boolean
     * @since   1.0.0
     */
    protected function testContainerKeyMustExist($key)
    {
        if (isset($this->container_registry[$key])) {
            return true;
        }

        return false;
    }

    /**
     * Determine if a container entry exists for this key
     *
     * @param   string $key
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
     * @param   string   $key
     * @param   boolean  $must_exist
     * @param   string[] $testing_methods
     * @param   string[] $array_input
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
    protected function setNamespaces(array $factory_method_aliases = array())
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
