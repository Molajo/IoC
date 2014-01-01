<?php
/**
 * Inversion of Control Controller
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC;

use stdClass;
use Exception;
use CommonApi\Exception\RuntimeException;
use CommonApi\IoC\ContainerInterface;
use CommonApi\IoC\ServiceProviderInterface;

/**
 * Inversion of Control Controller
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
     * Service Aliases => Namespaces
     *
     * @var     array
     * @since   1.0
     */
    protected $service_provider_aliases = array();

    /**
     * Service Namespaces => Aliases
     *
     * @var     array
     * @since   1.0
     */
    protected $service_provider_namespace = array();

    /**
     * Class Dependencies derived from Reflection
     *
     * @var     array
     * @since   1.0
     */
    protected $class_dependencies = array();

    /**
     * Process Services
     *
     * @var     array
     * @since   1.0
     */
    protected $process_services = array();

    /**
     * Services Queue
     *
     * @var     array
     * @since   1.0
     */
    protected $queue_id = 1;

    /**
     * New Services Queue
     *
     * @var     array
     * @since   1.0
     */
    protected $service_process_queue = array();

    /**
     * Dependency of Array
     *
     * @var     array
     * @since   1.0
     */
    protected $dependency_of = array();

    /**
     * Standard IoC Service Provider (Used when no custom Service Provider is required)
     *
     * @var     array
     * @since   1.0
     */
    protected $standard_service_provider_namespace = 'Molajo\\IoC\\StandardServiceProvider';

    /**
     * Constructor
     *
     * @param  ContainerInterface $container
     * @param  null|string        $class_dependencies_filename
     *
     * @since  1.0
     */
    public function __construct(
        array $service_provider_aliases = array(),
        $class_dependencies_filename = null,
        $standard_service_provider_namespace = 'Molajo\\IoC\\StandardServiceProvider'
    ) {
        $this->service_provider_aliases   = $service_provider_aliases;
        $this->service_provider_namespace = array();

        if (count($this->service_provider_aliases) > 0) {
            foreach ($this->service_provider_aliases as $key => $value) {
                $this->service_provider_namespace[$value] = $key;
            }
        }

        $this->loadClassDependencies($class_dependencies_filename);
    }

    /**
     * Process a Set of Service Requests
     *
     * @param   array $batch_services (array [$service_name] => $options)
     *
     * @return  $this
     * @since   1.0
     */
    public function scheduleServices(array $batch_services = array())
    {
        foreach ($batch_services as $service_name => $options) {

            $this->process_services = array();

            if (is_array($options)) {
            } else {
                $options = array();
            }

            $this->scheduleService($service_name, $options);
        }

        return $this;
    }

    /**
     * Get a Service, recursively resolving dependencies
     *
     * @param   string $service_name
     * @param   array  $options
     *
     * @return  mixed
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function scheduleService($service_name = null, array $options = array())
    {
        if (isset($this->container_registry[strtolower($service_name)])) {
            return $this->container_registry[strtolower($service_name)];
        }

        $this->dependency_of         = array();
        $this->service_process_queue = array();

        $this->setWorkObject($service_name, $options);

        $schedule_service_name     = $service_name;
        $count                     = 0;
        $continue_processing       = true;
        $schedule_service_instance = null;

        while ($continue_processing === true) {

            /** 1. Get the next Service in order of ID in the Processing Queue */
            foreach ($this->process_services as $id => $object) {

                $s = $object;
//echo 'Service: ' . $s->name . '<br />';
                /** 2. Can the Service be finished? */
                $finish = false;

                if ($s->service_instance == '') {
                } else {
                    $finish = true;
                }

                if ($finish === false) {
                    if ($s->adapter->getRemainingDependencyCount() == 0) {
                        $finish = true;
                    }
                }

                if ($finish === true) {
                    $s->service_instance = $this->completeService($s);
                    unset($this->process_services[$id]);
                }

                /** 3. Use Instance */
                if ($s->service_instance == '') {

                    $count ++;

                    if ($count > 400) {
                        echo 'The following services ' . count($this->process_services) . ' remain: <br />';

                        foreach ($this->process_services as $service) {
                            echo 'Service: ' . $service->name . '<br />';

                            if (count($service->dependencies) > 0) {
                                foreach ($service->dependencies as $name => $options) {
                                    echo 'Dependencies: ' . $name . '<br />';
                                }
                            }
                        }

                        echo $count . ' is greater than 20000 in IoC';
                        die;
                    }

                } else {

                    if ($s->requested_name == $schedule_service_name) {
                        if ($s->service_instance == '') {
                        } else {
                            $schedule_service_instance = $s->service_instance;
                        }
                    }
                }
            }

            /** Service Process Queue accumulates new Dependencies and Newly Scheduled Services              */
            /** setWorkObject creates a new process_services entry for those which need to be created        */
            /** If new process_services are found, the loop continues                                        */

            if (count($this->service_process_queue) > 0) {
                foreach ($this->service_process_queue as $service_name => $options) {
                    $this->setWorkObject($service_name, $options);
                    unset($this->service_process_queue[$service_name]);
                }
            }

            if (count($this->process_services) === 0) {
                $continue_processing = false;
            }
        }

        return $schedule_service_instance;
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
        $key = strtolower($key);

        if (isset($this->container_registry[$key])) {
            return true;
        }

        return false;
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
        $key = strtolower($key);

        if (isset($this->container_registry[$key])) {
            return $this->container_registry[$key];
        }

        throw new RuntimeException
        ('IoCC Entry for Key: ' . $key . ' does not exist');
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
        $newkey = strtolower($key);

        $this->container_registry[$newkey] = $value;

        if (isset($this->service_provider_namespace[$key])) {
        } else {
            $this->service_provider_namespace[$key] = $key;
        }

        if (isset($this->service_provider_aliases[$key])) {
        } else {
            $this->service_provider_aliases[$key] = $key;
        }

        return $this;
    }

    /**
     * Remove the existing service instance
     *
     * @param   string $key
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\InvalidArgumentException
     */
    public function remove($key)
    {
        $key = strtolower($key);

        if (isset($this->container_registry[$key])) {
            unset($this->container_registry[$key]);

            return $this;
        }

        throw new RuntimeException
        ('Requested Removal of IoCC Entry for Key: ' . $key . ' does not exist');
    }

    /**
     * Clone the existing service instance and return the cloned instance
     *
     * @param   string $key
     *
     * @return  object
     * @since   1.0
     * @throws  \CommonApi\Exception\InvalidArgumentException
     */
    public function cloneInstance($key)
    {
        $key = strtolower($key);

        if (isset($this->container_registry[$key])) {
        } else {
            throw new RuntimeException
            ('Requested Clone of IoCC Entry for Key: ' . $key . ' does not exist');
        }

        $value = $this->container_registry[$key];

        if (is_object($value)) {
        } else {
            throw new RuntimeException
            ('Requested Clone of IoCC Entry for Key: ' . $key . ' for value that is not an object.');
        }

        return clone $value;
    }

    /**
     * Set the Service Work Object used within this class
     *
     * @param   string $service_name
     * @param   array  $options
     *
     * @return  object
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setWorkObject($service_name, array $options = array())
    {
        /** 1. Set key values */
        $s = $this->setContainerKey($service_name, $options);

        /** 2. Instance not found, but ignore due to 'if_exists' */
        if ($s->service_instance == '') {

            if (isset($options['if_exists'])) {
                $s->service_instance            = false;
                $this->process_services[$s->id] = $s;

                return $this;
            }
        }

        /** 3. Instance found in container */
        if ($s->service_instance == '') {
        } else {
            $this->process_services[$s->id] = $s;

            return $this;
        }

        /** 4. Create Service Provider Instance to use for DI and Class Creation */
        $this->instantiateServiceProvider($s);

        return $this;
    }

    /**
     * Get Service Provider Namespace
     *
     * @param   string $service_name
     *
     * @return  $this
     * @since   1.0
     */
    protected function instantiateServiceProvider($s)
    {
        /** 1. Create Service Provider Instance */
        try {

            $service_provider = $this->getServiceProvider
                (
                    $s->name,
                    $s->service_provider_namespace,
                    $s->options
                );

        } catch (Exception $e) {
            throw new RuntimeException
            ('IoC instantiateServiceProvider: Exception: ' . $e->getMessage());
        }

        /** 2. Create Service Adapter Instance */
        try {

            $adapter = $this->getServiceProviderAdapter($service_provider);

        } catch (Exception $e) {
            throw new RuntimeException
            ('IoC instantiateServiceProvider: Exception: ' . $e->getMessage());
        }

        /** 3. Retrieve Service Item metadata and set dependencies */
        $s->service_namespace = $adapter->getServiceNamespace();
        $s->options           = $adapter->getServiceOptions();

        /** 4. Set Dependencies */
        $reflection = null;

        if (isset($this->class_dependencies[$s->service_namespace])) {
            $reflection = $this->class_dependencies[$s->service_namespace];
        } else {
            //todo - automate reflection
            $reflection = array();
        }

        $s->dependencies = $adapter->setDependencies($reflection);

        /** 5. Process Dependencies */
        if (count($s->dependencies) > 0) {

            foreach ($s->dependencies as $dependency => $dependency_options) {

                $response = $this->has($dependency);

                if ($response === true) {
                    $dependency_value = $this->get($dependency);
                    $adapter->setDependencyInstance($dependency, $dependency_value);
                } else {
                    $this->service_process_queue[$dependency] = $dependency_options;
                    if (isset($this->dependency_of[$dependency])) {
                        $temp = $this->dependency_of[$dependency];
                    } else {
                        $temp = array();
                    }
                    $temp[] = $s->id;
                    $this->dependency_of[$dependency] = $temp;
                }
            }
        }

        /** 5. Clean up */
        $s          = $this->sortObject($s);
        $s->adapter = $adapter;

        $this->process_services[$s->id] = $s;

        return $this;
    }

    /**
     * Get Service Provider Namespace
     *
     * @param   string $service_name
     *
     * @return  object
     * @since   1.0
     */
    protected function setContainerKey($service_name, array $options = array())
    {
        /** 1. Initialise Service Object */
        $s                             = new stdClass();
        $id                            = $this->queue_id ++;
        $s->id                         = $id;
        $s->options                    = $options;
        $s->options['ioc_id']          = $s->id;
        $s->requested_name             = $service_name;
        $s->name                       = '';
        $s->container_key              = '';
        $s->service_provider_namespace = '';
        $s->service_instance           = '';

        /** 2. Key sent in is a valid container entry key (or alias for container key) */
        $response = $this->has($service_name);
        if ($response === true) {
            $s->name          = $this->service_provider_aliases[$service_name];
            $s->container_key = $service_name;

            $s->service_instance = $this->get($this->service_provider_aliases[$service_name]);

            return $s;
        }

        if (isset($this->service_provider_aliases[$service_name])) {

            $response = $this->has($this->service_provider_aliases[$service_name]);

            if ($response === true) {

                $s->name          = $service_name;
                $s->container_key = $this->service_provider_aliases[$service_name];

                $s->service_instance = $this->get($this->service_provider_aliases[$service_name]);

                return $s;
            }
        }

        /** 3. Options array includes namespace */
        if (isset($options['service_provider_namespace'])) {

            $this->service_provider_namespace[$options['service_provider_namespace']] = $service_name;
            $this->service_provider_alias[$service_name]                              = $options['service_provider_namespace'];

            $s->name                       = $service_name;
            $s->service_provider_namespace = $options['service_provider_namespace']
                . '\\' . $service_name . 'ServiceProvider';
            $s->container_key              = $options['service_provider_namespace'];

            return $s;
        }

        /** 4. Alias for Standard Service Provider sent in */
        if (isset($this->service_provider_aliases[$service_name])) {

            $s->name                       = $service_name;
            $s->service_provider_namespace = $this->service_provider_aliases[$service_name]
                . '\\' . $service_name . 'ServiceProvider';
            $s->container_key              = $this->service_provider_aliases[$service_name];

            return $s;
        }

        /** 5. Service Provider Namespace sent in */
        if (isset($this->service_provider_namespace[$service_name])) {

            $s->name                       = $this->service_provider_namespace[$service_name];
            $s->service_provider_namespace = $service_name . '\\' . $s->name . 'ServiceProvider';
            $s->container_key              = $service_name;

            return $s;
        }

        /** 6. Use Standard Service Provider */
        $ns
                                               = $this->standard_service_provider_namespace
            . '\\' . $service_name . 'ServiceProvider';
        $s->name                               = $service_name;
        $s->service_provider_namespace         = $this->standard_service_provider_namespace;
        $s->container_key                      = $ns;
        $this->service_provider_namespace[$ns] = $ns;
        $this->service_provider_alias[$ns]     = $ns;

        /** 6a. Exists */
        $response = $this->has($ns);

        if ($response === true) {
            $s->service_instance = $this->get($ns);

            return $s;
        }

        return $s;
    }

    /**
     * Instantiate Class now that dependencies have been satisfied and finish processing
     *
     * @param   string $s service object
     *
     * @return  object
     * @since   1.0
     */
    protected function completeService($s)
    {
        /** 0. Have instance */
        if ($s->service_instance == '') {
        } else {
            $this->satisfyDependency($s->name, $s->service_instance);

            return $s->service_instance;
        }

        /** 1. Share Dependency Instances with Service Provider for final processing before creating class */
        $s->adapter->onBeforeInstantiation();

        /** 2. Trigger the Service Provider to create the class */
        $s->adapter->instantiateService();

        /** 3. Trigger the Service Provider to execute logic that follows class instantiation */
        $s->adapter->onAfterInstantiation();

        /** 4. Get instance for the just instantiated class */
        $service_instance    = $s->adapter->getServiceInstance();
        $s->service_instance = $service_instance;

        /** 5. Store instance in Container (if so requested by the Service Provider) */
        if ($s->adapter->getStoreInstanceIndicator() === true) {
            $this->set($s->container_key, $s->service_instance);
        }

        /** 6. See if the Service Provider has other services that should be also saved in the container */
        $set = $s->adapter->setService();

        if (is_array($set) && count($set) > 0) {
            foreach ($set as $container_key => $value) {
                $this->set($container_key, $value);
            }
        }

        /** 7. See if the Service Provider has services that should now be removed from the container */
        $remove = $s->adapter->removeService();

        if (is_array($remove) && count($remove) > 0) {
            foreach ($remove as $service_name) {
                if ($this->has($service_name) === true) {
                    $this->remove($service_name);
                }
            }
        }

        /** 8. Schedule additional Services as instructed by the Service Provider */
        $next = $s->adapter->scheduleServices();

        // Avoid adding twice
        if (is_array($next) && count($next) > 0) {
            foreach ($next as $service_name => $options) {
                foreach ($this->service_process_queue as $key => $value) {
                    if ($service_name == $key) {
                        unset($next[$service_name]);
                        break;
                    }
                }
            }
        }

        if (is_array($next) && count($next) > 0) {
            foreach ($next as $service_name => $options) {
                $this->service_process_queue[$service_name] = $options;
            }
        }

        /** 9. Return Instance */
        $this->satisfyDependency($s->name, $service_instance);

        return $service_instance;
    }

    /**
     * Update Service for Dependency Value
     *
     * @param   string $dependency
     * @param   mixed  $dependency_value
     *
     * @return  $this
     * @since   1.0
     */
    protected function satisfyDependency($dependency, $dependency_value)
    {
        if (isset($this->dependency_of[$dependency])) {
        } else {
            return $this;
        }

        $temp = $this->dependency_of[$dependency];

        foreach ($temp as $id) {
            if (isset($this->process_services[$id])) {
                $s = $this->process_services[$id];
                $s->adapter->setDependencyInstance($dependency, $dependency_value);
                $this->process_services[$id] = $s;
            }
        }

        return $this;
    }

    /**
     * Instantiate DI Adapter, injecting it with the Handler instance
     *
     * @param   ServiceProviderInterface $service_provider
     *
     * @return  object  \CommonApi\IoC\ServiceProviderInterface
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function getServiceProviderAdapter(ServiceProviderInterface $service_provider)
    {
        try {
            $adapter = new ServiceProviderAdapter($service_provider);

        } catch (Exception $e) {
            throw new RuntimeException
            ('Ioc getServiceProviderAdapter Instantiate ServiceItem Exception: ' . $e->getMessage());
        }

        return $adapter;
    }

    /**
     * Instantiate Service Provider to inject into the Adapter Constructor
     *
     * @param   string $service
     * @param   string $service_provider_namespace
     * @param   string $options
     *
     * @return  object
     * @throws  \CommonApi\Exception\RuntimeException
     * @since   1.0
     */
    protected function getServiceProvider($service, $service_provider_namespace, $options)
    {
        if (is_array($options) && count($options) > 0) {
        } else {
            $options = array();
        }

        $options['service_name'] = $service;

        if ($service_provider_namespace == $this->standard_service_provider_namespace) {
            if (isset($options['service_provider_namespace'])) {
            } else {
                if (isset($this->service_provider_aliases[$service])) {
                    $options['service_provider_namespace'] = $this->service_provider_aliases[$service];
                }
            }
        }

        try {
            $class = $service_provider_namespace;

            $service_provider = new $class($options);

        } catch (Exception $e) {

            throw new RuntimeException
            ('IoC getServiceProvider Instantiation Exception: '
            . $service_provider_namespace . ' ' . $e->getMessage());
        }

        return $service_provider;
    }

    /**
     * Load Class Dependencies and Service Provider Aliases
     *
     * @param  string $filename
     *
     * @since   1.0
     * @return  $this
     */
    protected function loadClassDependencies($filename = null)
    {
        if (file_exists($filename)) {
        } else {
            return $this;
        }

        $x = file_get_contents($filename);

        $input = json_decode($x);

        if (count($input) > 0) {
        } else {
            return array();
        }

        foreach ($input as $class) {
            if (isset($class->constructor_parameters)) {
                $this->class_dependencies[$class->fqns] = $class->constructor_parameters;
            }
        }

        return $this;
    }

    /**
     * Sort Object
     *
     * @param   object $input_object
     *
     * @return  object
     * @since   1.0
     */
    private function sortObject($input_object)
    {
        /** Step 1. Load Array with Fields */
        $hold_array = array();

        foreach (\get_object_vars($input_object) as $key => $value) {
            $hold_array[$key] = $value;
        }

        /** Step 2. Sort Array by Key */
        ksort($hold_array);

        /** Step 3. Create New Object */
        $new_object = new stdClass();

        foreach ($hold_array as $key => $value) {
            $new_object->$key = $value;
        }

        /** Step 4. Return Object */
        return $new_object;
    }
}
