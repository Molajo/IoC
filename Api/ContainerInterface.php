<?php
/**
 * Container Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\IoC\Api;

use Molajo\IoC\Exception\ContainerException;

/**
 * Container Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface ContainerInterface
{
    /**
     * Set the value of a specified key
     *
     * @param   string $key
     * @param   mixed  $value
     *
     * @return  mixed
     * @since   1.0
     * @throws  ContainerException
     */
    public function set($key, $value = null);

    /**
     * Get the current value (or default) of the specified key
     *
     * @param   string $key
     * @param   mixed  $default
     *
     * @return  mixed
     * @since   1.0
     * @throws  ContainerException
     */
    public function get($key, $default = null);

    /**
     * Instantiates Service Class
     *
     * @param   string $service_name
     *
     * @return  object  $this->injector
     * @since   1.0
     */
    public function instantiateInjector($service_name);

    /**
     * Get the current value for the specified key for the Injector
     *
     * @param   string     $key
     * @param   null|mixed $default
     *
     * @return  mixed
     * @since   1.0
     */
    public function getInjectorProperty($key, $default = null);

    /**
     * Get the current value for the specified key for the Injector
     *
     * @param   string     $key
     * @param   null|mixed $value
     *
     * @return  $this
     * @since   1.0
     */
    public function setInjectorProperty($key, $value = null);

    /**
     * Fire Injector onBeforeServiceInstantiate
     *
     * @return  $this
     * @since   1.0
     */
    public function onBeforeServiceInstantiate();

    /**
     * Instantiate Injector
     *
     * @param   bool $create_static
     *
     * @return  $this
     * @since   1.0
     */
    public function instantiate($create_static);

    /**
     * Fire Injector onBeforeServiceInstantiate
     *
     * @return  $this
     * @since   1.0
     */
    public function onAfterServiceInstantiate();

    /**
     * Fire Injector initialise
     *
     * @return  $this
     * @since   1.0
     */
    public function initialise();

    /**
     * Fire Injector onBeforeServiceInstantiate
     *
     * @return  $this
     * @since   1.0
     */
    public function onAfterServiceInitialise();

    /**
     * Retrieve the Service Instance
     *
     * @return  $this
     * @since   1.0
     */
    public function getServiceInstance();
}
