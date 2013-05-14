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
 * Inversion of Control Container
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Container implements ContainerInterface
{
    /**
     * Service Registry
     *
     * @var     array
     * @since   1.0
     */
    protected $registry = array();

    /**
     * Prevents simultaneous instantiations of the same object
     *
     * @var     array
     * @since   1.0
     */
    protected $lock_same_connection = array();

    /**
     * Namespace for Service Library
     *
     * @var     object
     * @since   1.0
     */
    protected $service_library = 'Molajo\\Services';

    /**
     * Injector Adapter
     *
     * @var     object
     * @since   1.0
     */
    protected $adapter;

    /**
     * Constructor
     *
     * @param  string $service_library
     *
     * @since  1.0
     */
    public function __construct($service_library = 'Molajo\\Services')
    {
        $this->service_library = $service_library;
    }

    /**
     * Get Service
     *
     * @param   string $service
     * @param   array  $options
     *
     * @return  null|object
     * @since   1.0
     * @throws  ContainerException
     */
    public function getService($service, $options = array())
    {
        /** 1. Return service instance, if it already exists */
        if (isset($this->registry[$service])) {
            return $this->registry[$service];
        }

        /** 2. If the service instance does not exist, forget about it */
        if (isset($options['if_exists'])) {
            if ($options['if_exists'] === true) {
                return null;
            }
        }

        /** 3. Stop attempt at simultaneous object construction */
        if (isset($this->lock_same_connection[$service])) {
            throw new ContainerException
            ('Inversion of Control Container getService: second, '
                . ' simultaneous permanent service load (loop): '
                . $service);
        }

        /** 4. Adapter interacts with DI Handler */
        $adapter = $this->getAdapter($service, $options);

        $adapter->instantiateInjector($service);
        $adapter->onBeforeServiceInstantiate();
        $adapter->instantiate($adapter->get('static_instance_indicator'));

        if ($adapter->get('store_instance_indicator') === true
            || $adapter->get('store_properties_indicator') === true
        ) {
            $this->registry[$service] = $adapter->getServiceInstance();

            if (isset($this->lock_same_connection[$service])) {
                unset($this->lock_same_connection[$service]);
                $this->registry[$service] = $adapter->getServiceInstance();
            }
        }

        $adapter->onAfterServiceInstantiate();
        $adapter->initialise();
        $adapter->onAfterServiceInitialise();

        $results = $adapter->getServiceInstance();

        if ($adapter->get('store_instance_indicator') === true
            || $adapter->get('store_properties_indicator') === true
        ) {

            $this->registry[$service] = $results;
        }

        return $results;
    }

    /**
     * Instantiate DI Handler, inject it into the Adapter Constructor
     *
     * @param   string $service
     *
     * @return  object
     * @since   1.0
     * @throws  ContainerException
     */
    protected function getAdapter($service, $options)
    {
        $ns = $this->setNamespace($service);

        $handler_class_ns = $ns[0];
        $handler_class_name = $ns[1];

        try {
            $handler = new $handler_class_ns();

        } catch (Exception $e) {

            throw new ContainerException
            ('Inversion of Control Container: Instantiate IoC Handler: ' . $ns[0] . ' ' . $e->getMessage());
        }

        try {
            $adapter = new Adapter($handler, $options);

        } catch (Exception $e) {
            throw new ContainerException
            ('Inversion of Control Container: Instantiate IoC Adapter Exception: ' . $e->getMessage());
        }

        $adapter->set('container_instance', $this);
        $adapter->set('options', $options);

        if ($adapter->get('store_instance_indicator') === true
            || $adapter->get('store_properties_indicator') === true
        ) {
            $this->lock_same_connection[$service] = true;
        }

        return $this;
    }

    /**
     * Build Service DI Handler Namespace
     *
     * @param   string $service
     *
     * @return  array
     * @since   1.0
     * @throws  ContainerException
     */
    protected function setNamespace($service)
    {
        $ns = strrpos($service, '\\');

        if ((bool)$ns === true) {
            $ns[0] = str_replace('\\', '/', substr($service, 0, $ns)) . '/';
            $ns[1] = substr($service, $ns + 1);
        } else {
            $ns[0] = $this->service_library . $service;
            $ns[1] = $service . 'Injector';

            if (class_exists($ns[0])) {
            } else {
                $ns[0] = 'Molajo\\Ioc\\Handler\\StandardInjector';
                $ns[1] = 'StandardInjector';
            }
        }

        return $ns;
    }

    /**
     * Replace the existing service instance with the passed in object
     *
     * @param   string $service
     * @param   object $instance
     *
     * @return  $this
     * @since   1.0
     * @throws  ContainerException
     */
    public function setService($service, $instance = null)
    {
        if (isset($this->registry[$service])) {
        } else {
            throw new ContainerException
            ('Inversion of Control Container replaceService: : ' . $service . ' does not exist.');
        }

        return $this->registry[$service];
    }

    /**
     * Clone the existing service instance and return the cloned instance
     *
     * @param   string $service
     *
     * @return  null|object
     * @since   1.0
     * @throws  ContainerException
     */
    public function cloneService($service)
    {
        if (isset($this->registry[$service])) {
        } else {
            throw new ContainerException
            ('Inversion of Control Container cloneService: : ' . $service . ' does not exist.');
        }

        return clone $this->registry[$service];
    }

    /**
     * Remove the existing service instance
     *
     * @param   string $service
     *
     * @return  $this
     * @since   1.0
     */
    public function removeService($service)
    {
        if (isset($this->registry[$service])) {
            unset($this->registry[$service]);
        }

        return $this;
    }
}
