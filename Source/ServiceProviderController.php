<?php
/**
 * Inversion of Control Service Provider Controller
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
use Molajo\IoC\Api\ServiceProviderControllerInterface;

/**
 * Inversion of Control Service Provider Controller
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class ServiceProviderController implements ServiceProviderControllerInterface
{
    /**
     * Container Instance
     *
     * @var     object  CommonApi\IoC\ContainerInstance
     * @since   1.0
     */
    protected $container = null;

    /**
     * Process Services
     *
     * @var     array
     * @since   1.0
     */
    protected $process_services = array();

    /**
     * New Services (Temporarily holds dependencies and schedule next before adding to process_services)
     *
     * @var     array
     * @since   1.0
     */
    protected $service_process_queue = array();

    /**
     * Services Queue
     *
     * @var     array
     * @since   1.0
     */
    protected $queue_id = 1;

    /**
     * Services Folders
     *
     * @var     array
     * @since   1.0
     */
    protected $service_provider_folders = array();

    /**
     * IoC Service Provider Namespaces - lookup table
     *
     * @var     array
     * @since   1.0
     */
    protected $service_provider_namespaces = array();

    /**
     * Class Dependencies derived from Reflection
     *
     * @var     array
     * @since   1.0
     */
    protected $class_dependencies = array();

    /**
     * Class Dependencies constructed and available
     *
     * @var     array
     * @since   1.0
     */
    protected $available_class_dependencies = false;

    /**
     * Standard IoC Service Provider (Used when no custom Service Provider is required)
     *
     * @var     array
     * @since   1.0
     */
    protected $standard_service_provider_namespace = 'Molajo\\IoC\\StandardServiceProvider';

    /**
     * Service Aliases
     *
     * @var     array
     * @since   1.0
     */
    protected $service_provider_aliases = array();

    /**
     * Constructor
     *
     * @param  ContainerInterface $container
     * @param  array              $service_provider_folders
     * @param  string             $class_dependencies
     *
     * @since  1.0
     */
    public function __construct(
        ContainerInterface $container,
        array $service_provider_folders = array(),
        $class_dependencies = null
    ) {
        $this->loadClassDependencies($class_dependencies);

        $this->service_provider_folders = $service_provider_folders;

        if ($this->available_class_dependencies === true) {
            if (is_array($service_provider_folders) && count($service_provider_folders) > 0) {
                $this->mapServiceProviderNamespaces($service_provider_folders);
            }
        } else {
            $this->class_dependencies = $class_dependencies;
        }

        $this->container = $container;
    }

    /**
     * Set service alias
     *
     * @param   string $service_name
     * @param   string $service_namespace
     *
     * @return  $this
     * @since   1.0
     */
    public function setServiceAlias($service_name, $service_namespace)
    {
        $this->service_provider_aliases[$service_name] = $service_namespace;

        return $this;
    }

    /**
     * Process a Set of Service Requests
     *
     * @param   array $batch_services (array [$service_name] => $options)
     *
     * @return  array (array ['service_name'] => $service_instance)
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function getServices(array $batch_services = array())
    {
        foreach ($batch_services as $service_name => $options) {

            try {
                $this->process_services = array();

                $this->getService($service_name, $options);

            } catch (Exception $e) {
                throw new RuntimeException
                ('IoC start: Exception: ' . $e->getMessage());
            }
        }

        return $batch_services;
    }

    /**
     * Get a Service (Class Instance) first recursively resolving its dependencies
     *
     * @param   string $service_name
     * @param   array  $options
     *
     * @return  object
     * @since   1.0
     */
    public function getService($service_name, array $options = array())
    {
        /** Needed if resource map built before resources loaded */
        if ($this->available_class_dependencies === true) {
        } else {
            $this->loadClassDependencies($this->class_dependencies);
            $this->mapServiceProviderNamespaces($this->service_provider_folders);
        }

        $instance = $this->setServiceWorkObject($service_name, $options);

        if ($instance === true) {
            // do not have instance and not a "if_exists" request
        } else {
            return $instance;
        }

        $hold_service_name       = $service_name;
        $return_service_instance = null;

        $count = 0;

        $s                   = null;
        $id                  = 0;
        $continue_processing = true;

        while ($continue_processing === true) {

            /** 1. Get the next Service in order of ID in the Processing Queue */
            foreach ($this->process_services as $id => $object) {

                $s = $this->process_services[$id];

                /** 2. Process each Dependency, one at a time, to see if it has been satisfied */
                if (count($s->dependencies) > 0) {
                    foreach ($s->dependencies as $dependency => $dependency_options) {
                        $s = $this->processDependency($s, $dependency, $dependency_options);
                    }
                }

                if ((int)$s->adapter->getRemainingDependencyCount() === 0) {

                    $service_instance = $this->completeService($s);

                    if ($s->name == $hold_service_name) {
                        $return_service_instance = $service_instance;
                    }
                }

                $count ++;
                if ($count > 200) {

                    echo count($this->process_services) . ' remains';
                    foreach ($this->process_services as $service) {
                        echo $service->name . '<br />';
                        echo '<pre>';
                        var_dump($service);
                    }

                    echo $count . ' is greater than 20000 in IoC';
                    die;
                }
            }

            /** Service Process Queue accumulates new Dependencies and Newly Scheduled Services              */
            /** setServiceWorkObject creates a new process_services entry for those which need to be created */
            /** If new process_services are found, the loop continues                                        */

            if (count($this->service_process_queue) > 0) {

                foreach ($this->service_process_queue as $service_name => $options) {
                    $this->setServiceWorkObject($service_name, $options);
                    unset($this->service_process_queue[$service_name]);
                }

                $this->service_process_queue = array();
            }

            if (count($this->process_services) === 0) {
                $continue_processing = false;
            }
        }

        return $return_service_instance;
    }

    /**
     * Store Instance in the Inversion of Control Container
     *
     * @param   string $container_key (Handler Namespace unless it doesn't exist in which case Service Namespace)
     * @param   object $instance
     * @param   string $service_name
     *
     * @return  $this
     * @since   1.0
     */
    public function setService($container_key, $instance, $service_name = null)
    {
        if ($service_name === null) {
        } else {
            $this->setServiceAlias($service_name, $container_key);
        }

        $this->container->setService($container_key, $instance, $service_name);

        return $this;
    }

    /**
     * Clone Instance in the Inversion of Control Container
     *
     * @param   string $container_key (Handler Namespace unless it doesn't exist in which case Service Namespace)
     *
     * @return  null|object
     * @since   1.0
     */
    public function cloneService($container_key)
    {
        $instance = $this->container->cloneService($container_key);

        return clone $instance;
    }

    /**
     * Remove Instance in the Inversion of Control Container
     *
     * @param   string $container_key (Handler Namespace unless it doesn't exist in which case Service Namespace)
     *
     * @return  $this
     * @since   1.0
     */
    public function removeService($container_key)
    {
        $this->container->removeService($container_key);

        return $this;
    }

    /**
     * Get Service Instance stored in the Inversion of Control Container
     *
     * @param   string      $container_key (Handler Namespace unless it doesn't exist in which case Service Namespace)
     * @param   null|string $service_name
     *
     * @return  null|object
     * @since   1.0
     */
    protected function getContainerInstance($container_key, $service_name = null)
    {
        if ($container_key == $this->standard_service_provider_namespace) {
            if (isset($this->service_provider_aliases[$service_name])) {
                $container_key = $this->service_provider_aliases[$service_name];
            }
        }

        $saved_instance = $this->container->getService($container_key);

        return $saved_instance;
    }

    /**
     * Set the Service Work Object used within this class
     *
     * @param   string $service_name
     * @param   array  $options
     *
     * @return  bool|object
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setServiceWorkObject($service_name, array $options = array())
    {
        /** 1. Create Working Object */
        $s = new stdClass();

        $id      = $this->queue_id ++;
        $s->id   = $id;
        $s->name = $service_name;

        /** 2. Use Container Instance is available */
        $container_key = $s->name;
        if (isset($options['service_namespace'])) {
            $container_key = $options['service_namespace'];
        }

        $saved_instance = $this->getContainerInstance($container_key, $s->name);
        if ($saved_instance === false) {
        } else {
            return $saved_instance; // use container instance
        }

        /** 3. Get the Handler Namespace  */
        if ((string)$service_name == '') {
            $s->service_namespace          = '';
            $s->service_provider_namespace = '';
            $s->container_key              = '';
        } else {

            $s->service_provider_namespace = $this->getServiceProviderNamespace($service_name);

            $container_key = $s->service_provider_namespace;

            if ($container_key == $this->standard_service_provider_namespace) {
                if (isset($this->service_provider_aliases[$s->name])) {
                    $container_key = $this->service_provider_aliases[$s->name];
                }
            }

            $saved_instance = $this->getContainerInstance($container_key, $s->name);

            if ($saved_instance === false) {
            } else {
                return $saved_instance; // use container instance
            }
        }

        $s->options           = $options;
        $s->options['ioc_id'] = $s->id;
        $s->service_provider  = null;
        $s->adapter           = null;

        if (isset($options['service_namespace'])) {
            $s->container_key = $options['service_namespace'];
        } else {
            $s->container_key = $s->service_provider_namespace;
        }

        /** 4. See if a Container Instance is Available */
        if ((string)$service_name == '') {
        } else {
            $saved_instance = $this->getContainerInstance($s->container_key, $s->name);

            if ($saved_instance === false) {
            } else {
                return $saved_instance; // use container instance
            }
        }

        /** 5. Service instance does not exist, but since if_exists is set, forget about it */
        if (isset($s->options['if_exists'])) {
            return false; // do not create instance
        }

        /** 6. Create Handler Instance, inject into Service Item Constructor for Dependency Resolution */
        try {

            $s->service_provider = $this->getServiceProvider
                (
                    $s->name,
                    $s->service_provider_namespace,
                    $s->options
                );

            $s->adapter = $this->getServiceProviderAdapter($s->service_provider);

        } catch (Exception $e) {
            throw new RuntimeException
            ('IoC ServiceItem: Exception: ' . $e->getMessage());
        }

        /** 7. Retrieve Service Item metadata and set dependencies */
        $s->name              = $s->adapter->getServiceName();
        $s->service_namespace = $s->adapter->getServiceNamespace();
        $s->options           = $s->adapter->getServiceOptions();

        if ($s->service_provider_namespace == $this->standard_service_provider_namespace) {
            $s->container_key = $s->service_namespace;
        } else {
            $s->container_key = $s->service_provider_namespace;
        }

        $reflection = null;
        if (isset($this->class_dependencies[$s->service_namespace])) {
            $reflection = $this->class_dependencies[$s->service_namespace];
        }

        $s->dependencies = $s->adapter->setDependencies($reflection);

        if (count($s->dependencies) > 0) {
            foreach ($s->dependencies as $dependency => $dependency_options) {
                $s = $this->processDependency($s, $dependency, $dependency_options);
            }
        }

        $this->process_services[$s->id] = $s;

        return true; // continue working on it
    }

    /**
     * Process a Dependency for the Service Object to see if it can be satisfied
     *
     * @param   object $s Service Object that has the dependency
     * @param   string $dependency
     * @param   array  $dependency_options
     *
     * @return  object
     * @since   1.0
     */
    protected function processDependency($s, $dependency, $dependency_options)
    {
        if (is_array($dependency_options)) {
        } else {
            $dependency_options = array();
        }

        /** 1. Dependency is self */
        if ($dependency == $s->name) {
            $s->adapter->removeDependency($dependency);
            unset($s->dependencies[$dependency]);
            return $s;
        }

        /** 2. Dependency Instance in Container */
        $dependency_instance = $this->getContainerInstance($dependency, $dependency);

        if ($dependency_instance === false) {

            $service_provider_namespace = $this->getServiceProviderNamespace($dependency);

            if ($service_provider_namespace == $this->standard_service_provider_namespace) {
                if (isset($dependency_options['service_namespace'])) {
                    $service_provider_namespace = $dependency_options['service_namespace'];
                }
            }

            $dependency_instance = $this->getContainerInstance($service_provider_namespace, $dependency);
        }

        if ($dependency_instance === false) {
        } else {
            $s->adapter->setDependencyInstance($dependency, $dependency_instance);
            unset($s->dependencies[$dependency]);
            return $s;
        }

        /** 3. Dependency "if_exists" (and it does not exist) */
        if (isset($dependency_options['if_exists'])) {
            $s->adapter->removeDependency($dependency);
            unset($s->dependencies[$dependency]);
            return $s;
        }

        /** 4. Add Dependency to list of services to process */
        $this->service_process_queue[$dependency] = $dependency_options;

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
        /** 0. See if a Container Instance is available for the Service Name (was already in queue) */
        $container_key = $s->service_provider_namespace;

        if ($s->service_provider_namespace == $this->standard_service_provider_namespace) {
            if (isset($s->options['service_namespace'])) {
                if (isset($this->service_provider_aliases[$s->name])) {
                    $container_key = $s->options['service_namespace'];
                }
            }
        }

        $saved_instance = $this->getContainerInstance($container_key, $s->name);
        if ($saved_instance === false) {
            if (isset($s->options['service_namespace'])) {
                $saved_instance = $this->getContainerInstance($s->options['service_namespace'], $s->name);
            }
        }

        if ($saved_instance === false) {
        } else {
            unset($this->process_services[$s->id]);
            return $saved_instance;
        }

        /** 1. Share Dependency Instances with Service Provider for final processing before creating class */
        $s->adapter->onBeforeInstantiation();

        /** 2. Trigger the Service Provider to create the class */
        $s->adapter->instantiateService();

        /** 3. Trigger the Service Provider to execute logic that follows class instantiation */
        $s->adapter->onAfterInstantiation();

        /** 4. Get instance for the just instantiated class */
        $service_instance = $s->adapter->getServiceInstance();

        $s->service_instance = $service_instance;

        /** 5. Store instance in Container (if so requested by the Service Provider) */
        if ($s->adapter->getStoreInstanceIndicator() === true) {
            $this->setService($s->container_key, $s->service_instance, $s->name);
        }

        /** 6. See if the Service Provider has other services that should be also saved in the container */
        $set = $s->adapter->setService();

        if (is_array($set) && count($set) > 0) {
            foreach ($set as $container_key => $instance) {
                $this->setService($container_key, $instance);
            }
        }

        /** 7. See if the Service Provider has services that should now be removed from the container */
        $remove = $s->adapter->removeService();

        if (is_array($remove) && count($remove) > 0) {
            foreach ($remove as $service_name) {
                $this->removeService($service_name);
            }
        }

        /** 8. Schedule additional Services as instructed by the Service Provider */
        $next = $s->adapter->scheduleServices();

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

        /** 9. Remove Service from "To Be Processed" array */
        unset($this->process_services[$s->id]);

        /** 10. Return Instance */
        return $service_instance;
    }

    /**
     * Instantiate DI Adapter, injecting it with the Handler instance
     *
     * @param   ServiceProviderInterface $service_provider
     *
     * @return  object  CommonApi\IoC\ServiceProviderInterface
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function getServiceProviderAdapter(ServiceProviderInterface $service_provider)
    {
        try {
            $ServiceProviderAdapter = new ServiceProviderAdapter($service_provider);
        } catch (Exception $e) {
            throw new RuntimeException
            ('Ioc getServiceProviderAdapter Instantiate ServiceItem Exception: ' . $e->getMessage());
        }

        return $ServiceProviderAdapter;
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
    protected function getServiceProvider(
        $service,
        $service_provider_namespace,
        $options
    ) {
        if (is_array($options) && count($options) > 0) {
        } else {
            $options = array();
        }

        $options['service_name'] = $service;

        if ($service_provider_namespace == $this->standard_service_provider_namespace) {
            if (isset($options['service_namespace'])) {
            } else {
                if (isset($this->service_provider_aliases[$service])) {
                    $options['service_namespace'] = $this->service_provider_aliases[$service];
                }
            }
        }

        try {
            $class            = $service_provider_namespace;
            $service_provider = new $class($options);

        } catch (Exception $e) {

            throw new RuntimeException
            ('IoC getServiceProvider Instantiation Exception: '
            . $service_provider_namespace . ' ' . $e->getMessage());
        }

        return $service_provider;
    }

    /**
     * Get Service Provider Namespace
     *
     * @param   string $service_name
     *
     * @return  string
     * @since   1.0
     */
    protected function getServiceProviderNamespace($service_name)
    {
        if (isset($this->service_provider_namespaces[$service_name])) {
            return $this->service_provider_namespaces[$service_name];
        }

        return $this->standard_service_provider_namespace;
    }

    /**
     * Map IoCC Dependency Injection Handler Namespaces
     *
     * @param   array $service_provider_folders
     *
     * @since   1.0
     * @return  $this
     */
    protected function mapServiceProviderNamespaces(
        array $service_provider_folders = array(),
        $folder_namespace = 'Molajo\\Service'
    )
    {
        if (is_array($service_provider_folders) && count($service_provider_folders) > 0) {
        } else {
            return $this;
        }

        $this->service_provider_folders = $service_provider_folders;

        $services = array();

        foreach ($service_provider_folders as $folder) {

            $temp = $this->getServiceProviderFolders($folder, $folder_namespace);

            if (is_array($temp) && count($temp) > 0) {
                foreach ($temp as $service_name => $service_provider_namespace_namespace) {
                    $services[$service_name]
                                                                   = $service_provider_namespace_namespace . '\\' . $service_name . 'ServiceProvider';
                    $this->service_provider_aliases[$service_name] = $service_provider_namespace_namespace;
                }
            }
        }

        ksort($services);

        $this->service_provider_namespaces = $services;

        return $this;
    }

    /**
     * Get IoC Handler Folders
     *
     * @param   string $service_provider_folder
     * @param   string $service_provider_namespace
     *
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     * @return  array
     */
    protected function getServiceProviderFolders($service_provider_folder, $service_provider_namespace)
    {
        if (is_dir($service_provider_folder)) {
        } else {
            throw new RuntimeException
            ('Container: getServiceProviderFolders Failed. Folder does not exist ' . $service_provider_folder);
        }

        $temp_folders = array();

        $temp = array_diff(scandir($service_provider_folder), array('.', '..'));

        foreach ($temp as $item) {
            if (is_dir($service_provider_folder . '/' . $item)) {
                $temp_folders[$item] = $service_provider_namespace . '\\' . $item;
            }
        }

        return $temp_folders;
    }

    /**
     * Load Class Dependencies
     *
     * @param  string $filename
     *
     * @since   1.0
     * @return  array
     */
    protected function loadClassDependencies($filename = null)
    {
        if (file_exists($filename)) {
            $this->available_class_dependencies = true;
        } else {
            return array();
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

            if (isset($class->name) && isset($class->fqns)) {
                if (strrpos($class->name, 'ServiceProvider')) {
                } else {
                    $this->service_provider_aliases[$class->name] = $class->fqns;
                }
            }
        }

        return $this;
    }
}
