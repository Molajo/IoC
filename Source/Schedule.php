<?php
/**
 * Schedule for processing Factory Method Requests and Container Entries
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC;


use CommonApi\IoC\ContainerInterface;
use CommonApi\IoC\ScheduleInterface;

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
     * Container    \CommonApi\IoC\ContainerInterface
     *
     * @var     object
     * @since  1.0.0
     */
    protected $container = null;

    /**
     * Class Dependencies derived from Reflection
     *
     * @var     array
     * @since  1.0.0
     */
    protected $class_dependencies = array();

    /**
     * Request Queue
     *
     * @var     array
     * @since  1.0.0
     */
    protected $queue_id = 1;

    /**
     * Process Request Queue
     *
     * @var     array
     * @since  1.0.0
     */
    protected $process_requests = array();

    /**
     * New Requests Queue
     *
     * @var     array
     * @since  1.0.0
     */
    protected $request_process_queue = array();

    /**
     * Dependency of Array
     *
     * @var     array
     * @since  1.0.0
     */
    protected $dependency_of = array();

    /**
     * Standard IoC Factory Method (Used when no custom Factory Method is required)
     *
     * @var     array
     * @since  1.0.0
     */
    protected $standard_adapter_namespace = 'Molajo\\IoC\\StandardFactoryMethod';

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     * @param null               $class_dependencies_filename
     * @param string             $standard_adapter_namespace
     *
     * @since  1.0.0
     */
    public function __construct(
        ContainerInterface $container,
        $class_dependencies_filename = null,
        $standard_adapter_namespace = 'Molajo\\IoC\\StandardFactoryMethod'
    ) {
        $this->container                   = $container;
        $this->standard_adapter_namespaces = $standard_adapter_namespace;
        $this->loadClassDependencies($class_dependencies_filename);
    }

    /**
     * Schedule Factory
     *
     * Handles requests for FactoryMethod product, including dependency fulfillment
     *
     * @param   string $product_name
     * @param   array  $options
     *
     * @return  $this
     * @since  1.0.0
     */
    public function scheduleFactoryMethod($product_name = null, array $options = array())
    {
        if ($this->hasContainerEntry($product_name) === true) {
            return $this->getContainerEntry($product_name);
        }

        if (isset($options['if_exists'])) {
            return false;
        }

        $options['product_name']  = $product_name;
        $options['container_key'] = $this->getContainerEntryKey($product_name);
        $options['ioc_id']        = $this->queue_id++;

        $options = $this->getFactoryMethodNamespace($options);


        var_dump($options);
        die;

        $options['class_dependencies'] = $this->getReflectionDependencies($options['factory_method_namespace']);

        $this->instantiateFactoryMethod($options);

        return $this;
    }

    /**
     * Get the Factory Method Namespace
     *
     * @param   array $options
     *
     * @return  array
     * @since   1.0.0
     */
    protected function getFactoryMethodNamespace($options)
    {
        $namespace = new FactoryMethodNamespace(
            $this->standard_adapter_namespace,
            $options
        );

        return $namespace->get();
    }

    /**
     * Is Factory Method Namespace in options?
     *
     * @param   array $options
     *
     * @return  mixed
     * @since  1.0.0
     */
    protected function getFactoryNamespaceOptions(array $options = array())
    {
        if (isset($options['factory_method_namespace'])) {
            return $options;
        }

        return false;
    }

    /**
     * Is Factory Method Namespace the Product Name?
     *
     * @param   array $options
     *
     * @return  mixed
     * @since  1.0.0
     */
    protected function getFactoryNamespaceProductName(array $options = array())
    {
        if (class_exists($options['product_name'])) {
            $options['factory_method_namespace'] = $options['product_name'];
            return $options;
        }

        return false;
    }

    /**
     * Is Factory Method Namespace the Container Key?
     *
     * @param   array $options
     *
     * @return  mixed
     * @since  1.0.0
     */
    protected function getFactoryNamespaceContainerKey(array $options = array())
    {
        $results = $this->getLastFolder($options['container_key']);
        if ($results === false) {
            $options['factory_method_namespace'] = $this->standard_adapter_namespace;
            return $options;
        }

        if (class_exists($options['product_name'])) {
            return $options['product_name'];
        }
    }

    /**
     * Is Factory Method Namespace the Product Name?
     *
     * @param   array $options
     *
     * @return  array
     * @since  1.0.0
     */
    protected function getFactoryNamespaceDefault(array $options = array())
    {
        $options['factory_method_namespace'] = $this->standard_adapter_namespace;

        return $options;
    }

    /**
     * Retrieve the last folder in string
     *
     * @param   string $value
     *
     * @return  mixed
     * @since  1.0.0
     */
    protected function getLastFolder($value)
    {
        if (strrpos($value, '/')) {
            return substr($value, strrpos($value, '/') + 1, 999);
        }

        return false;
    }

    /**
     * Reflection Dependencies
     *
     * @param   string $product_namespace
     *
     * @return  array
     * @since  1.0.0
     */
    protected function getReflectionDependencies($product_namespace)
    {
        $reflection = array();

        if (isset($this->class_dependencies[$product_namespace])) {
            $reflection = $this->class_dependencies[$product_namespace];
        } else {
            //todo - automate reflection
        }

        return $reflection;
    }

    /**
     * Schedule Product Factory Method
     *
     * Handles requests for FactoryMethod product, including dependency fulfillment
     *
     * @param   string $product_name
     * @param   array  $options
     *
     * @return  mixed
     * @since  1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function processQueue($product_name = null, array $options = array())
    {
        $this->dependency_of         = array();
        $this->request_process_queue = array();
        $schedule_product_name       = $product_name;
        $count                       = 0;
        $continue_processing         = true;
        $schedule_product_result     = null;

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

                    $count++;

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
     * Update Service for Dependency Value
     *
     * @param   string $dependency
     * @param   mixed  $dependency_value
     *
     * @return  $this
     * @since  1.0.0
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
     * See if product already exists within the container
     *
     * @param   string $key
     *
     * @return  mixed
     * @since  1.0.0
     */
    protected function hasContainerEntry($key)
    {
        if ($this->container->has($key) === false) {
            return false;
        }

        return true;
    }

    /**
     * Get the primary key for container
     *
     * @param   string $key
     *
     * @return  string
     * @since  1.0.0
     */
    protected function getContainerEntryKey($key)
    {
        return $this->container->getKey($key, true);
    }

    /**
     * See if product already exists within the container
     *
     * @param   string $key
     *
     * @return  mixed
     * @since  1.0.0
     */
    protected function getContainerEntry($key)
    {
        if ($this->container->has($key) === false) {
            return false;
        }

        return $this->container->get($key);
    }

    /**
     * Load Class Dependencies and Factory Method Aliases
     *
     * @param  string $filename
     *
     * @since  1.0.0
     * @return  $this
     */
    protected function loadClassDependencies($filename = null)
    {
        if (isset($options['set'])) {
            if (isset($options['value'])) {
                $value = $options['value'];
            } else {
                $value = null;
            }

            return $this->container->set($product_name, $value);
        }


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
