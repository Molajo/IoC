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
    protected $service_library;

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
     * @param  string  $service_library
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
     * @param    string $service
     * @param    array  $options
     *
     * @results  null|object
     * @since    1.0
     * @throws   ContainerException
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

        /** 3. Stop attempt for simultaneous object construction */
        if (isset($this->lock_same_connection[$service])) {
            throw new ContainerException
            ('Inversion of Control Container getService: second, '
                . ' simultaneous permanent service load (loop): '
                . $service);
        }

        /** 4. Instantiate Adapter Constructor */
        $adapter = $this->getAdapter($service);

        $adapter->instantiateInjector($service);

        $adapter->onBeforeServiceInstantiate();

        $adapter->instantiate($adapter->getInjectorProperty('static_instance_indicator'));

        if ($adapter->getInjectorProperty('store_instance_indicator') === true
            || $adapter->getInjectorProperty('store_properties_indicator') === true
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

        return $adapter->getServiceInstance();
    }

    /**
     * Instantiates DI Handler, passing it into the Adapter Constructor
     *
     * @param   string  $service
     *
     * @return  object
     * @since   1.0
     * @throws  ContainerException
     */
    protected function getAdapter($service)
    {
        try {
            $adapter = new Adapter();

        } catch (Exception $e) {
            throw new ContainerException
            ('Inversion of Control Container: Instantiate IoC Container Exception: ', $e->getMessage());
        }

        $class_name = $this->services_library
            . $this->service . '\\'
            . $this->service . 'Injector';



        try {

            $this->injector = new $class_name();

        } catch (Exception $e) {

            throw new InjectorException
            ('Injector Adapter: Injector Instance Failed for ' . $service
                . ' failed.' . $e->getMessage());
        }


        $adapter->setInjectorProperty('container_instance', $this);
        $adapter->setInjectorProperty('options', $options);

        /** 6. Lock any additional instantiating of service until this one is complete */
        if ($adapter->getInjectorProperty('store_instance_indicator') === true
            || $adapter->getInjectorProperty('store_properties_indicator') === true
        ) {
            $this->lock_same_connection[$service] = true;
        }

        return $this;
    }


    /**
     * Replace the existing service instance with the passed in object
     *
     * @param    string  $service
     * @param    object  $instance
     *
     * @results  $this
     * @since    1.0
     * @throws   ContainerException
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
     * @param    string $service
     *
     * @results  null|object
     * @since    1.0
     * @throws   ContainerException
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
     * @param    string $service
     *
     * @results  $this
     * @since    1.0
     */
    public function removeService($service)
    {
        if (isset($this->registry[$service])) {
            unset($this->registry[$service]);
        }

        return $this;
    }
}
