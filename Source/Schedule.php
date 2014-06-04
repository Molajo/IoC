<?php
/**
 * Schedule for processing Factory Method Requests and Container Entries
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC;

use Exception;
use CommonApi\Exception\RuntimeException;
use CommonApi\IoC\ContainerInterface;
use CommonApi\IoC\FactoryInterface;
use CommonApi\IoC\ScheduleInterface;
use stdClass;

/**
 * Schedule for processing Factory Method Requests and Container Entries
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class Schedule implements ScheduleInterface
{
    /**
     * Container Registry
     *
     * @var     array
     * @since   1.0
     */
    protected $container = null;

    /**
     * Factory Method Aliases => Namespaces
     *
     * @var     array
     * @since   1.0
     */
    protected $adapter_aliases = array();

    /**
     * Factory Method Namespaces => Aliases
     *
     * @var     array
     * @since   1.0
     */
    protected $adapter_namespaces = array();

    /**
     * Class Dependencies derived from Reflection
     *
     * @var     array
     * @since   1.0
     */
    protected $class_dependencies = array();

    /**
     * Process Request Queue
     *
     * @var     array
     * @since   1.0
     */
    protected $process_requests = array();

    /**
     * Request Queue
     *
     * @var     array
     * @since   1.0
     */
    protected $queue_id = 1;

    /**
     * New Requests Queue
     *
     * @var     array
     * @since   1.0
     */
    protected $request_process_queue = array();

    /**
     * Dependency of Array
     *
     * @var     array
     * @since   1.0
     */
    protected $dependency_of = array();

    /**
     * Standard IoC Factory Method (Used when no custom Factory Method is required)
     *
     * @var     array
     * @since   1.0
     */
    protected $standard_adapter_namespaces = 'Molajo\\IoC\\StandardFactoryMethod';

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     * @param array              $adapter_aliases
     * @param null               $class_dependencies_filename
     * @param string             $standard_adapter_namespaces
     *
     * @since  1.0
     */
    public function __construct(
        ContainerInterface $container,
        array $adapter_aliases = array(),
        $class_dependencies_filename = null,
        $standard_adapter_namespaces = 'Molajo\\IoC\\StandardFactoryMethod'
    ) {
        $this->container          = $container;
        $this->adapter_aliases    = $adapter_aliases;
        $this->adapter_namespaces = array();

        if (count($this->adapter_aliases) > 0) {
            foreach ($this->adapter_aliases as $key => $value) {
                $this->adapter_namespaces[$value] = $key;
            }
        }

        $this->loadClassDependencies($class_dependencies_filename);
    }

    /**
     * Schedule Factory recursively resolving dependencies
     *
     * @param   string $product_name
     * @param   array  $options
     *
     * @return  mixed
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function scheduleFactoryMethod($product_name = null, array $options = array())
    {
        if (isset($options['set'])) {
            if (isset($options['value'])) {
                $value = $options['value'];
            } else {
                $value = null;
            }

            return $this->container->set($product_name, $value);
        }

        $product = $this->container->has(strtolower($product_name));

        if ($product === true) {
            return $this->container->get(strtolower($product_name));
        }

        if (isset($options['if_exists'])) {
            return false;
        }

        $this->dependency_of         = array();
        $this->request_process_queue = array();

        $this->setWorkObject($product_name, $options);

        $schedule_product_name   = $product_name;
        $count                   = 0;
        $continue_processing     = true;
        $schedule_product_result = null;

        while ($continue_processing === true) {

            /** 1. Get the next Request in order of ID in the Processing Queue */
            foreach ($this->process_requests as $id => $object) {

                $work_object = $object;
//echo 'Request: ' . $work_object->name . '<br />';
                /** 2. Can the Request be finished? */
                $finish = false;

                if ($work_object->product_result === false) {
                    $finish = true;
                } elseif ($work_object->product_result == '') {
                } else {
                    $finish = true;
                }

                if ($finish === false) {
                    if ($work_object->adapter->getRemainingDependencyCount() == 0) {
                        $finish = true;
                    }
                }

                if ($finish === true) {
                    $work_object->product_result = $this->completeRequest($work_object);
                    unset($this->process_requests[$id]);
                }

                /** 3. Use Instance */
                if ($work_object->product_result == '') {

                    $count ++;

                    if ($count > 400) {

                        foreach ($this->process_requests as $request) {
                            echo 'Request: ' . $request->name . '<br />';

                            if (count($request->dependencies) > 0) {
                                foreach ($request->dependencies as $name => $options) {
                                    echo 'Dependencies: ' . $name . '<br />';
                                }
                            }
                        }

                        echo $count . ' is greater than 20000 in IoC';
                        die;
                    }

                } else {

                    if ($work_object->requested_name == $schedule_product_name) {
                        if ($work_object->product_result == '') {
                        } else {
                            $schedule_product_result = $work_object->product_result;
                        }
                    }
                }
            }

            /** Request Process Queue accumulates new Dependencies and Newly Scheduled Requests              */
            /** setWorkObject creates a new process_requests entry for those which need to be created        */
            /** If new process_requests are found, the loop continues                                        */

            if (count($this->request_process_queue) > 0) {
                foreach ($this->request_process_queue as $product_name => $options) {
                    $this->setWorkObject($product_name, $options);
                    unset($this->request_process_queue[$product_name]);
                }
            }

            if (count($this->process_requests) === 0) {
                $continue_processing = false;
            }
        }

        return $schedule_product_result;
    }

    /**
     * Set the Request Work Object used within this class
     *
     * @param   string $product_name
     * @param   array  $options
     *
     * @return  Schedule
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setWorkObject($product_name, array $options = array())
    {
        /** 1. Set key values */
        $work_object = $this->setContainerKey($product_name, $options);

        /** 2. Instance not found, but ignore due to 'if_exists' */
        if ($work_object->product_result == '') {

            if (isset($options['if_exists'])) {
                $work_object->product_result              = false;
                $this->process_requests[$work_object->id] = $work_object;

                return $this;
            }
        }

        /** 3. Instance found in container */
        if ($work_object->product_result == '') {
        } else {
            $this->process_requests[$work_object->id] = $work_object;

            return $this;
        }

        /** 4. Create Factory Method Adapter Instance to use for DI and Class Creation */
        $this->instantiateFactoryMethod($work_object);

        return $this;
    }

    /**
     * Get Factory Method Namespace
     *
     * @param   stdClass $work_object
     *
     * @return  $this
     * @since   1.0
     * @throws \CommonApi\Exception\RuntimeException
     */
    protected function instantiateFactoryMethod($work_object)
    {
        /** 1. Create Factory Method Adapter Instance */
        try {

            $factory_method_adapter = $this->getFactoryMethodAdapter
                (
                    $work_object->name,
                    $work_object->adapter_namespaces,
                    $work_object->options
                );

        } catch (Exception $e) {
            throw new RuntimeException
            ('IoC instantiateFactoryMethod: Exception: ' . $e->getMessage());
        }

        /** 2. Create Factory Method Adapter Instance */
        try {
            $adapter = $this->getFactoryMethod($factory_method_adapter);

        } catch (Exception $e) {
            throw new RuntimeException
            ('IoC instantiateFactoryMethod: Exception: ' . $e->getMessage());
        }

        /** 3. Retrieve request metadata and set dependencies */
        $work_object->product_namespace = $adapter->getNamespace();
        $work_object->options           = $adapter->getOptions();

        /** 4. Set Dependencies */
        $reflection = null;

        if (isset($this->class_dependencies[$work_object->product_namespace])) {
            $reflection = $this->class_dependencies[$work_object->product_namespace];
        } else {
            //todo - automate reflection
            $reflection = array();
        }

        $work_object->dependencies = $adapter->setDependencies($reflection);

        /** 5. Process Dependencies */
        if (count($work_object->dependencies) > 0) {

            foreach ($work_object->dependencies as $dependency => $dependency_options) {

                $response = $this->container->has($dependency);

                if ($response === true) {
                    $dependency_value = $this->container->get($dependency);
                    $adapter->setDependencyValue($dependency, $dependency_value);
                } else {
                    $this->request_process_queue[$dependency] = $dependency_options;
                    if (isset($this->dependency_of[$dependency])) {
                        $temp = $this->dependency_of[$dependency];
                    } else {
                        $temp = array();
                    }
                    $temp[]                           = $work_object->id;
                    $this->dependency_of[$dependency] = $temp;
                }
            }
        }

        /** 5. Clean up */
        $work_object->adapter = $adapter;

        $this->process_requests[$work_object->id] = $work_object;

        return $this;
    }

    /**
     * Get Factory Method Namespace
     *
     * @param   string $product_name
     * @param   array  $options
     *
     * @return  stdClass
     * @since   1.0
     */
    protected function setContainerKey($product_name, array $options = array())
    {
        /** 1. Initialise Request Object */
        $work_object                     = new stdClass();
        $id                              = $this->queue_id ++;
        $work_object->id                 = $id;
        $work_object->options            = $options;
        $work_object->options['ioc_id']  = $work_object->id;
        $work_object->requested_name     = $product_name;
        $work_object->name               = '';
        $work_object->container_key      = '';
        $work_object->adapter_namespaces = '';
        $work_object->product_result     = '';

        /** 2. Key sent in is a valid container entry key (or alias for container key) */
        $response = $this->container->has($product_name);
        if ($response === true) {
            $work_object->name           = $this->adapter_aliases[$product_name];
            $work_object->container_key  = $product_name;
            $work_object->product_result = $this->container->get($this->adapter_aliases[$product_name]);

            return $work_object;
        }

        if (isset($this->adapter_aliases[$product_name])) {

            $response = $this->container->has($this->adapter_aliases[$product_name]);

            if ($response === true) {

                $work_object->name           = $product_name;
                $work_object->container_key  = $this->adapter_aliases[$product_name];
                $work_object->product_result = $this->container->get($this->adapter_aliases[$product_name]);

                return $work_object;
            }
        }

        /** 3. Options array includes namespace */
        if (isset($options['adapter_namespaces'])) {

            $this->adapter_namespaces[$options['adapter_namespaces']] = $product_name;
            $this->adapter_aliases[$product_name]                     = $options['adapter_namespaces'];

            $work_object->name               = $product_name;
            $work_object->adapter_namespaces = $options['adapter_namespaces']
                . '\\' . $product_name . 'FactoryMethod';
            $work_object->container_key      = $options['adapter_namespaces'];

            return $work_object;
        }

        /** 4. Alias for Standard Factory Method sent in */
        if (isset($this->adapter_aliases[$product_name])) {

            $work_object->name               = $product_name;
            $work_object->adapter_namespaces = $this->adapter_aliases[$product_name]
                . '\\' . $product_name . 'FactoryMethod';
            $work_object->container_key      = $this->adapter_aliases[$product_name];

            return $work_object;
        }

        /** 5. Factory Method Namespace sent in */
        if (isset($this->adapter_namespaces[$product_name])) {

            $work_object->name               = $this->adapter_namespaces[$product_name];
            $work_object->adapter_namespaces = $product_name . '\\' . $work_object->name . 'FactoryMethod';
            $work_object->container_key      = $product_name;

            return $work_object;
        }

        /** 6. Use Standard Factory Method */
        $ns = $this->standard_adapter_namespaces . '\\' . $product_name . 'FactoryMethod';

        $work_object->name               = $product_name;
        $work_object->adapter_namespaces = $this->standard_adapter_namespaces;
        $work_object->container_key      = $ns;
        $this->adapter_namespaces[$ns]   = $ns;
        $this->adapter_aliases[$ns]      = $ns;

        /** 6a. Exists */
        $response = $this->container->has($ns);

        if ($response === true) {
            $work_object->product_result = $this->container->get($ns);

            return $work_object;
        }

        return $work_object;
    }

    /**
     * Instantiate Class now that dependencies have been satisfied and finish processing
     *
     * @param   string $work_object
     *
     * @return  object
     * @since   1.0
     */
    protected function completeRequest($work_object)
    {
        /** 0. Have instance */
        if ($work_object->product_result === false) {
            $this->satisfyDependency($work_object->name, $work_object->product_result);

            return $work_object->product_result;
        }

        if ($work_object->product_result == '') {
        } else {
            $this->satisfyDependency($work_object->name, $work_object->product_result);

            return $work_object->product_result;
        }

        /** 1. Share Dependency Instances with Factory Method for final processing before creating class */
        $work_object->adapter->onBeforeInstantiation();

        /** 2. Trigger the Factory Method to create the class */
        $work_object->adapter->instantiateClass();

        /** 3. Trigger the Factory Method to execute logic that follows class instantiation */
        $work_object->adapter->onAfterInstantiation();

        /** 4. Get instance for the just instantiated class */
        $product_result              = $work_object->adapter->getProductValue();
        $work_object->product_result = $product_result;

        /** 5. Store instance in Container (if so requested by the Factory Method) */
        if ($work_object->adapter->getStoreContainerEntryIndicator() === true) {
            $this->container->set($work_object->container_key, $work_object->product_result);
        }

        /** 6. Factory Method requests container removals */
        $remove = $work_object->adapter->removeContainerEntries();

        if (is_array($remove) && count($remove) > 0) {
            foreach ($remove as $product_name) {
                if ($this->container->has($product_name) === true) {
                    $this->container->remove($product_name);
                }
            }
        }

        /** 7. Factory Method requests container values be set */
        $set = $work_object->adapter->setContainerEntries();

        if (is_array($set) && count($set) > 0) {
            foreach ($set as $product_name => $value) {
                $this->container->set($product_name, $value);
            }
        }

        /** 9. Factory Method schedules factory processing */
        $next = $work_object->adapter->scheduleFactories();

        // Avoid adding twice
        if (is_array($next) && count($next) > 0) {
            foreach ($next as $product_name => $options) {
                foreach ($this->request_process_queue as $key => $value) {
                    if ($product_name == $key) {
                        unset($next[$product_name]);
                        break;
                    }
                }
            }
        }

        if (is_array($next) && count($next) > 0) {
            foreach ($next as $product_name => $options) {
                $this->request_process_queue[$product_name] = $options;
            }
        }

        /** 8. Schedule additional Services as instructed by the Factory Method */
        // Avoid adding twice
        if (is_array($next) && count($next) > 0) {
            foreach ($next as $product_name => $options) {
                foreach ($this->request_process_queue as $key => $value) {
                    if ($product_name == $key) {
                        unset($next[$product_name]);
                        break;
                    }
                }
            }
        }

        if (is_array($next) && count($next) > 0) {
            foreach ($next as $product_name => $options) {
                $this->request_process_queue[$product_name] = $options;
            }
        }

        /** 10. Return Instance */
        $this->satisfyDependency($work_object->name, $product_result);

        return $product_result;
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
            if (isset($this->process_requests[$id])) {
                $work_object = $this->process_requests[$id];
                $work_object->adapter->setDependencyValue($dependency, $dependency_value);
                $this->process_requests[$id] = $work_object;
            }
        }

        return $this;
    }

    /**
     * Instantiate DI Adapter, injecting it with the Handler instance
     *
     * @param   FactoryInterface $factory_method_adapter
     *
     * @return  FactoryMethodController  CommonApi\IoC\FactoryInterface     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function getFactoryMethod(FactoryInterface $factory_method_adapter)
    {
        try {
            $adapter = new FactoryMethodController($factory_method_adapter);

        } catch (Exception $e) {
            throw new RuntimeException
            ('Ioc getFactoryMethod Instantiate ServiceItem Exception: ' . $e->getMessage());
        }

        return $adapter;
    }

    /**
     * Instantiate Factory Method to inject into the Adapter Constructor
     *
     * @param   string $request
     * @param   string $adapter_namespaces
     * @param   string $options
     *
     * @return  FactoryInterface     * @throws  \CommonApi\Exception\RuntimeException
     * @since   1.0
     */
    protected function getFactoryMethodAdapter($request, $adapter_namespaces, $options)
    {
        if (is_array($options) && count($options) > 0) {
        } else {
            $options = array();
        }

        $options['product_name'] = $request;

        if ($adapter_namespaces == $this->standard_adapter_namespaces) {
            if (isset($options['adapter_namespaces'])) {
            } else {
                if (isset($this->adapter_aliases[$request])) {
                    $options['adapter_namespaces'] = $this->adapter_aliases[$request];
                }
            }
        }

        try {
            $class = $adapter_namespaces;

            $factory_method_adapter = new $class($options);

        } catch (Exception $e) {

            throw new RuntimeException
            ('IoC getFactoryMethod Instantiation Exception: '
            . $adapter_namespaces . ' ' . $e->getMessage());
        }

        return $factory_method_adapter;
    }

    /**
     * Load Class Dependencies and Factory Method Aliases
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
}
