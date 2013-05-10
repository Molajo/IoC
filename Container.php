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
     * Connecting - used to prevent loops
     *
     * @var     array
     * @since   1.0
     */
    protected $lock_same_connection = array();

    /**
     * Handle requests for Services either by instantiating an instance of the Service
     *  and injecting its dependencies, or by returning a shared instance already available,
     *  or by not returning an instance that is not yet available.
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

        /** 2. If the service requested does not already exist, forget about it */
        if (isset($options['if_exists'])) {
            if ($options['if_exists'] === true) {
                return null;
            }
        }

        /** 3. Return service instance, if it already exists */
        if (isset($this->lock_same_connection[$service_name])) {
            throw new ContainerException
            ('Inversion of Control Container getService: second, simultaneous permanent service load (loop): '
                . $service_name);
        }

        /** 4. New instance of Inversion of Control Container */
        try {
            $adapter = new Adapter();

        } catch (Exception $e) {
            throw new ContainerException
            ('Inversion of Control Container:Instantiate IoC Container Exception: ', $e->getMessage());
        }

        /** 5. Instantiate Injector and inject with Frontcontroller and options */
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
}
