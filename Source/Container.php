<?php
/**
 * Inversion of Control Container
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC;

use CommonApi\Exception\InvalidArgumentException;
use CommonApi\IoC\ContainerInterface;

/**
 * Inversion of Control Container
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @since      1.0
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
     * Service names to container key, can be stored under either
     *
     * @var     array
     * @since   1.0
     */
    protected $service_name_container_key = array();

    /**
     * Get Instance from Container or return false
     *
     * @param   string $container_key
     *
     * @return  bool|null|object
     * @since   1.0
     */
    public function getService($container_key)
    {
        $container_key = strtolower($container_key);

        if (isset($this->container_registry[$container_key])) {
            return $this->container_registry[$container_key];
        }

        if (isset($this->service_name_container_key[$container_key])) {
            $container_key = $this->service_name_container_key[$container_key];
        } else {
            return false;
        }

        if (isset($this->container_registry[$container_key])) {
            return $this->container_registry[$container_key];
        }

        return false;
    }

    /**
     * Set the existing service instance with the passed in object
     *
     * @param   string      $container_key
     * @param   object      $instance
     * @param   null|string $alias
     *
     * @return  $this
     * @since   1.0
     */
    public function setService($container_key, $instance = null, $alias = null)
    {
        $container_key = strtolower($container_key);

        if ($alias === null) {
        } else {
            $alias = strtolower($alias);
        }

        if (isset($this->container_registry[$container_key])) {
        } elseif (isset($this->service_name_container_key[$container_key])) {

            if (isset($this->container_registry[$this->service_name_container_key[$container_key]])) {
                $container_key = $this->service_name_container_key[$container_key];
            }
        } elseif (isset($this->service_name_container_key[$alias])) {
            if (isset($this->container_registry[$this->service_name_container_key[$alias]])) {
                $container_key = $this->service_name_container_key[$alias];
            }
        }

        $this->container_registry[$container_key] = $instance;

        if ($alias === null) {
        } elseif ($container_key === $alias) {
        } else {
            $service_name_container_key[$alias] = $container_key;
        }

        return $this;
    }

    /**
     * Clone the existing service instance and return the cloned instance
     *
     * @param   string $container_key
     *
     * @return  null|object
     * @since   1.0
     * @throws  \CommonApi\Exception\InvalidArgumentException
     */
    public function cloneService($container_key)
    {
        $container_key = strtolower($container_key);

        if (isset($this->container_registry[$container_key])) {
            return clone $this->container_registry[$container_key];
        }

        if (isset($this->service_name_container_key[$container_key])) {
            $container_key = $this->service_name_container_key[$container_key];
        } else {
            return false;
        }

        if (isset($this->container_registry[$container_key])) {
            return clone $this->container_registry[$container_key];
        }

        throw new InvalidArgumentException
        ('Inversion of Control Container cloneService: : '
        . $container_key . ' does not exist.');
    }

    /**
     * Remove the existing service instance
     *
     * @param   string $container_key
     *
     * @return  $this
     * @since   1.0
     */
    public function removeService($container_key)
    {
        $container_key = strtolower($container_key);

        if (isset($this->container_registry[$container_key])) {

            unset($this->container_registry[$container_key]);

            if (isset($this->service_name_container_key[$container_key])) {
                unset($this->service_name_container_key[$container_key]);
            }

            return $this;
        }

        if (isset($this->service_name_container_key[$container_key])) {
            $container_key = $this->service_name_container_key[$container_key];
            unset($this->service_name_container_key[$container_key]);
        } else {
            return $this;
        }

        unset($this->container_registry[$container_key]);

        return $this;
    }
}