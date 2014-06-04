<?php
/**
 * Inversion of Control Container
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC;

use CommonApi\Exception\RuntimeException;
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
     * @since   1.0
     */
    protected $container_registry = array();

    /**
     * Factory Method Aliases => Namespaces
     *
     * @var     array
     * @since   1.0
     */
    protected $adapter_aliases = array();

    /**
     * Factory Method Namespaces => Aliases
     *
     * @var     array
     * @since   1.0
     */
    protected $adapter_namespaces = array();

    /**
     * Constructor
     *
     * @param  array $adapter_aliases
     *
     * @since  1.0
     */
    public function __construct(
        array $adapter_aliases = array()
    ) {
        $this->adapter_aliases = $adapter_aliases;
        $this->setAdapterNamespaces();
    }

    /**
     * Determines if the entry identified by the $key exists within the Container
     *
     * @param   string $key
     *
     * @return  bool
     * @since   1.0
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
     * @since   1.0
     * @throws  \CommonApi\Exception\InvalidArgumentException
     */
    public function get($key)
    {
        $key = $this->getKey($key, true);

        return $this->container_registry[strtolower($key)];
    }

    /**
     * Set the Container Entry with the associated value
     *
     * @param   string $key
     * @param   mixed  $value
     *
     * @return  $this
     * @since   1.0
     */
    public function set($key, $value)
    {
        $newkey = $this->getKey($key, false);

        if ($newkey === false) {
            $newkey = strtolower($key);
        }

        $this->container_registry[$newkey] = $value;

        if (isset($this->adapter_namespaces[$key])) {
        } else {
            $this->adapter_namespaces[$key] = $key;
        }

        if (isset($this->adapter_aliases[$key])) {
        } else {
            $this->adapter_aliases[$key] = $key;
        }

        return $this;
    }

    /**
     * Remove the existing container instance
     *
     * @param   string $key
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\InvalidArgumentException
     */
    public function remove($key)
    {
        $key = $this->getKey($key, true);

        unset($this->container_registry[$key]);

        return $this;
    }

    /**
     * Determine if an alias value exists for this key
     *
     * @param   string $key
     *
     * @return  bool
     * @since   1.0
     */
    protected function getKey($key, $exception = false)
    {
        if (isset($this->container_registry[strtolower($key)])) {
            return strtolower($key);
        }

        if (isset($this->adapter_namespaces[$key])) {
            if (isset($this->container_registry[strtolower($this->adapter_namespaces[$key])])) {
                return strtolower($this->adapter_namespaces[$key]);
            }
        }

        if ($exception === true) {
            throw new RuntimeException('Requested IoCC Entry for Key: ' . $key . ' does not exist');
        }

        return false;
    }

    /**
     * Set adapter namespace array entries associated with alias keys
     *
     * @return  $this
     * @since   1.0
     */
    protected function setAdapterNamespaces()
    {
        $this->adapter_namespaces = array();

        if (count($this->adapter_aliases) > 0) {
            foreach ($this->adapter_aliases as $key => $value) {
                $this->adapter_namespaces[$value] = $key;
            }
        }

        return $this;
    }
}
