<?php
/**
 * Injector Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\IoC;

use Molajo\IoC\Exception\InjectorException;
use Molajo\IoC\Api\InjectorInterface;

/**
 * Injector Adapter
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
    protected $service_name;

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
    public function getInjectorProperty($key, $default = null)
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
    public function setInjectorProperty($key, $value = null)
    {
        $this->handler->set($key, $value);

        return $this;
    }

    /**
     * Execute the Injector onBeforeServiceInstantiate
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

    /**
     * Only used if the instance is requested as static
     *
     * @return  bool
     * @since   1.0
     * @throws  InjectorException
     */
    public function getStatic()
    {
        // TODO: Implement getStatic() method.
    }

    /**
     * Should instance be stored for reuse?
     *
     * @return  bool
     * @since   1.0
     * @throws  InjectorException
     */
    public function storeInstance()
    {
        // TODO: Implement storeInstance() method.
    }

    /**
     * Instantiate Service class Statically
     *
     * @param   string $service_namespace
     *
     * @static
     *
     * @return  null|object
     * @since   1.0
     * @throws  InjectorException
     */
    public static function instantiate_static($service_namespace)
    {
        // TODO: Implement instantiate_static() method.
    }
}
