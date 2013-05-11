<?php
/**
 * Inversion of Control Container
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC;

use Exception;
use Molajo\IoC\Adapter;
use Molajo\IoC\Api\ContainerInterface;
use Molajo\IoC\Exception\ContainerException;

/**
 * Application Service Dependency Injector
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Container implements ContainerInterface
{
    /**
     * Service Connections
     *
     * @var     array
     * @since   1.0
     */
    protected $connections = array();

    /**
     * Connecting - used to prevent simultaneous instantiations of the same object
     *
     * @var     array
     * @since   1.0
     */
    protected $lock_same_connection = array();

    /**
     *  Retrieves Service Instance and sends it back to the caller.
     *
     * Usage:
     * $results = $iocc->getService('Cache', $options);
     *
     * @param    string $service_name
     * @param    array  $options
     *
     * @results  null|object
     * @since    1.0
     * @throws   ContainerException
     */
    public function getService($service_name, $options = array())
    {
        /** 1. Return service instance, if it already exists */
        if (isset($this->connections[$service_name])) {
            return $this->connections[$service_name];
        }

        /** 2. Only return an instance if it already exists (don't create new instance) */
        if (isset($options['if_exists'])) {
            if ($options['if_exists'] === true) {
                return null;
            }
        }

        /** 3. Identify service with simultaneous object constructions */
        if (isset($this->lock_same_connection[$service_name])) {
            throw new ContainerException
            ('Inversion of Control Container getService: second, simultaneous permanent service load (loop): '
                . $service_name);
        }

        /** 4. Get a new instance of the DI Adapter */
        try {
            $adapter = new Adapter();

        } catch (Exception $e) {
            throw new ContainerException
            ('Inversion of Control Container:Instantiate IoC Container Exception: ', $e->getMessage());
        }

        /** 5. Instantiate Injector and inject with $this and options */
        $adapter->instantiateInjector($service_name);

        $adapter->setInjectorProperty('container_instance', $this);
        $adapter->setInjectorProperty('options', $options);

        /** 6. Lock any additional instantiating of service until this one is complete */
        if ($adapter->getInjectorProperty('store_instance_indicator') === true
            || $adapter->getInjectorProperty('store_properties_indicator') === true
        ) {
            $this->lock_same_connection[$service_name] = true;
        }

        /** 7. Before Service Instantiate */
        $adapter->onBeforeServiceInstantiate();

        /** 8. Instantiate, store and unlock */
        $adapter->instantiate($adapter->getInjectorProperty('static_instance_indicator'));

        if ($adapter->getInjectorProperty('store_instance_indicator') === true
            || $adapter->getInjectorProperty('store_properties_indicator') === true
        ) {
            $this->connections[$service_name] = $adapter->getServiceInstance();

            if (isset($this->lock_same_connection[$service_name])) {
                unset($this->lock_same_connection[$service_name]);
                $this->connections[$service_name] = $adapter->getServiceInstance();
            }
        }

        /** 9. After Service Instantiate */
        $adapter->onAfterServiceInstantiate();
        $adapter->initialise();
        $adapter->onAfterServiceInitialise();

        /** 10. Return results */
        return $adapter->getServiceInstance();
    }

    /**
     * Replace the existing service instance with the passed in object
     *
     * @param    string  $service_name
     * @param    object  $instance
     *
     * @results  $this
     * @since    1.0
     * @throws   ContainerException
     */
    public function replaceService($service_name, $instance = null)
    {
        if (isset($this->connections[$service_name])) {
        } else {
            throw new ContainerException
            ('Inversion of Control Container replaceService: : ' . $service_name . ' does not exist.');
        }

        return $this->connections[$service_name];
    }

    /**
     * Clone the existing service instance and return the cloned instance
     *
     * @param    string $service_name
     *
     * @results  null|object
     * @since    1.0
     * @throws   ContainerException
     */
    public function cloneService($service_name)
    {
        if (isset($this->connections[$service_name])) {
        } else {
            throw new ContainerException
            ('Inversion of Control Container cloneService: : ' . $service_name . ' does not exist.');
        }

        return clone $this->connections[$service_name];
    }

    /**
     * Remove the existing service instance
     *
     * @param    string $service_name
     *
     * @results  $this
     * @since    1.0
     */
    public function removeService($service_name)
    {
        if (isset($this->connections[$service_name])) {
            unset($this->connections[$service_name]);
        }

        return $this;
    }
}
