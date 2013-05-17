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
     * getService Closure
     *
     * @var     string
     * @since   1.0
     */
    protected $getService;

    /**
     * setService Closure
     *
     * @var     string
     * @since   1.0
     */
    protected $setService;

    /**
     * cloneService Closure
     *
     * @var     string
     * @since   1.0
     */
    protected $cloneService;

    /**
     * removeService Closure
     *
     * @var     string
     * @since   1.0
     */
    protected $removeService;

    /**
     * Constructor
     *
     * @param  string      $getService
     * @param  string      $setService
     * @param  string      $cloneService
     * @param  string      $removeService
     * @param  null|string $service_library
     *
     * @since  1.0
     */
    public function __construct(
        $getService,
        $setService,
        $cloneService,
        $removeService,
        $service_library = 'Molajo\\Services'
    ) {
        $this->getService      = $getService;
        $this->setService      = $setService;
        $this->cloneService    = $cloneService;
        $this->removeService   = $removeService;
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
        $options['getService']    = $this->getService;
        $options['setService']    = $this->setService;
        $options['cloneService']  = $this->cloneService;
        $options['removeService'] = $this->removeService;

        $adapter = $this->getAdapter($service, $options);

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
    protected function getAdapter($service, $options = array())
    {
        $ns = $this->setNamespace($service);

        $options['service']           = $service;

        try {
            $handler = new $ns($options);

        } catch (Exception $e) {

            throw new ContainerException
            ('Inversion of Control Container: Instantiate IoC Handler: ' . $ns . ' ' . $e->getMessage());
        }

        try {
            $adapter = new Adapter($handler);

        } catch (Exception $e) {
            throw new ContainerException
            ('Inversion of Control Container: Instantiate IoC Adapter Exception: ' . $e->getMessage());
        }

        if ($adapter->get('store_instance_indicator') === true
            || $adapter->get('store_properties_indicator') === true
        ) {
            $this->lock_same_connection[$service] = true;
        }

        return $adapter;
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
        $x = strrpos($service, '\\');

        if ((bool)$x === true) {
            $ns = str_replace('\\', '/', substr($service, 0, $x)) . '/';

        } else {
            $ns = $this->service_library . '\\' . $service . '\\' . $service . 'Injector';

            if (class_exists($ns)) {
            } else {
                $ns = 'Molajo\\IoC\\Handler\\StandardInjector';
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
