<?php
/**
 * Dependency Injector Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\IoC;

use Molajo\IoC\Api\InjectorInterface;

/**
 * Dependency Injector Adapter
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Adapter implements InjectorInterface
{
    /**
     * Injector Handler Instance
     *
     * @var     array
     * @since   1.0
     */
    protected $handler;

    /**
     * Service Name
     *
     * @var     string
     * @since   1.0
     */
    protected $service;

    /**
     * Constructor
     *
     * @param  InjectorInterface $handler
     *
     * @since  1.0
     */
    public function __construct(InjectorInterface $handler)
    {
        $this->handler = $handler;
    }

    /**
     * Get the current value for the specified key for the Injector
     *
     * @param   string     $key
     * @param   null|mixed $default
     *
     * @return  mixed
     * @since   1.0
     */
    public function get($key, $default = null)
    {
        return $this->handler->get($key, $default);
    }

    /**
     * Get the current value for the specified key for the Injector
     *
     * @param   string     $key
     * @param   null|mixed $value
     *
     * @return  $this
     * @since   1.0
     */
    public function set($key, $value = null)
    {
        $this->handler->set($key, $value);

        return $this;
    }

    /**
     * Execute the DI onBeforeServiceInstantiate
     *
     * @return  $this
     * @since   1.0
     */
    public function onBeforeServiceInstantiate()
    {
        $this->handler->onBeforeServiceInstantiate();

        return $this;
    }

    /**
     * Instantiate Injector
     *
     * @param   bool $create_static
     *
     * @return  $this
     * @since   1.0
     */
    public function instantiate($create_static = false)
    {
        $this->handler->instantiate($create_static);

        return $this;
    }

    /**
     * Execute the Injector onBeforeServiceInstantiate
     *
     * @return  $this
     * @since   1.0
     */
    public function onAfterServiceInstantiate()
    {
        $this->handler->onAfterServiceInstantiate();

        return $this;
    }

    /**
     * Execute the Injector initialise
     *
     * @return  $this
     * @since   1.0
     */
    public function initialise()
    {
        $this->handler->initialise();

        return $this;
    }

    /**
     * Execute the Injector onBeforeServiceInstantiate
     *
     * @return  $this
     * @since   1.0
     */
    public function onAfterServiceInitialise()
    {
        $this->handler->onAfterServiceInitialise();

        return $this;
    }

    /**
     * Retrieve the Service Instance, store the instance or properties, if requested
     *
     * @return  $this
     * @since   1.0
     */
    public function getServiceInstance()
    {
        return $this->handler->getServiceInstance();
    }
}
