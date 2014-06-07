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
     * Container
     *
     * @var     object  CommonApi\IoC\ContainerInterface
     * @since   1.0.0
     */
    protected $container = null;

    /**
     * Class Dependencies
     *
     * @var     object
     * @since   1.0.0
     */
    protected $class_dependencies = null;

    /**
     * Factory Method Namespace
     *
     * @var     object
     * @since   1.0.0
     */
    protected $factory_method_namespace = null;

    /**
     * Request Queue
     *
     * @var     integer
     * @since   1.0.0
     */
    protected $queue_id = 1;

    /**
     * Process Request Queue
     *
     * @var     array
     * @since   1.0.0
     */
    protected $process_requests = array();

    /**
     * New Requests Queue
     *
     * @var     array
     * @since   1.0.0
     */
    protected $to_be_processed_requests = array();

    /**
     * Process Request Queue
     *
     * @var     array
     * @since   1.0.0
     */
    protected $request_names_to_id = array();

    /**
     * Standard IoC Factory Method Namespace (Used when no custom Factory Method is required)
     *
     * @var     string
     * @since   1.0.0
     */
    protected $standard_adapter_namespace = 'Molajo\\IoC\\StandardFactoryMethod';

    /**
     * Product Result
     *
     * @var     object
     * @since   1.0.0
     */
    protected $product_result;

    /**
     * Constructor
     *
     * @param  ContainerInterface $container
     * @param  string             $class_dependencies_file
     * @param  string             $standard_adapter_namespace
     *
     * @since  1.0.0
     */
    public function __construct(
        array $factory_method_aliases = array(),
        $class_dependencies_file = '',
        $standard_adapter_namespace = 'Molajo\\IoC\\StandardFactoryMethod'
    ) {
        $this->createContainer($factory_method_aliases);
        $this->createClassDependencies($class_dependencies_file);
        $this->createFactoryMethodNamespace($standard_adapter_namespace);
    }

    /**
     * Schedule Factory Method for Requested Product
     *
     * @param   string $product_name
     * @param   array  $options
     *
     * @return  $this
     * @since   1.0.0
     */
    public function scheduleFactoryMethod($product_name = null, array $options = array())
    {
        if ($this->hasContainerEntry($product_name) === true) {
            return $this->getContainerEntry($product_name);
        }

        if (isset($options['if_exists'])) {
            return false;
        }

        $this->queue_id = 1;
        $work_object    = $this->setProductRequest($product_name, $options);

        $this->setProcessRequestsArray($product_name, $work_object);

        $this->processRequestQueue();

        return $this->product_result;
    }

    /**
     * Schedule Product Factory Method
     *
     * Handles requests for FactoryMethod product, including dependency fulfillment
     *
     * @return  $this
     * @since   1.0.0
     */
    public function processRequestQueue()
    {
        $count = 1;
        while (count($this->process_requests) > 0) {

            $this->processRequests();

            if (count($this->to_be_processed_requests) > 0) {
                $count = $this->processNewRequestQueue($count);
            }
        }

        return $this;
    }

    /**
     * Process each product request to satisfy dependencies and, when all dependencies
     *  have been met, to complete the Factory Method processes including creating the product
     *
     * @return  $this
     * @since   1.0.0
     */
    public function processRequests()
    {
        foreach ($this->process_requests as $id => $work_object) {

            if ($work_object->factory_method->getRemainingDependencyCount() === 0) {
                $this->processFactoryModel($work_object);
            }
        }

        return $this;
    }

    /**
     * Process each product request to satisfy dependencies and, when all dependencies
     *  have been met, to complete the Factory Method processes including creating the product
     *
     * @param   integer $count
     *
     * @return  integer
     * @since   1.0.0
     */
    public function processNewRequestQueue($count)
    {
        foreach ($this->to_be_processed_requests as $product_name => $options) {
            $work_object = $this->setProductRequest($product_name, $options);
            $this->setProcessRequestsArray($product_name, $work_object);
        }

        $count++;
        if ($count > 500) {
            var_dump($this->process_requests);
            throw new \Exception('processRequestQueue endless loop');
        }

        return $count;
    }

    /**
     * Process Product Request
     *
     * Initializes request and adds into the processing queue
     *
     * @param   string $product_name
     * @param   array  $options
     *
     * @return  stdClass
     * @since   1.0.0
     */
    public function setProductRequest($product_name = null, array $options = array())
    {
        $options['product_name']  = $product_name;
        $options['container_key'] = $this->getContainerEntryKey($product_name);
        $options['ioc_id']        = $this->queue_id++;

        $options = $this->getFactoryMethodNamespace($options);

        return $this->setProductRequestWorkObject($product_name, $options);
    }

    /**
     * Process Product Request
     *
     * Initializes request and adds into the processing queue
     *
     * @param   string $product_name
     * @param   array  $options
     *
     * @return  stdClass
     * @since   1.0.0
     */
    public function setProductRequestWorkObject($product_name = null, array $options = array())
    {
        $work_object          = new stdClass();
        $work_object->options = $options;

        $work_object->factory_method = $this->createFactoryMethod($options);

        $work_object->dependency_of = array();
        if (isset($options['dependency_of'])) {
            $work_object->dependency_of[] = $options['dependency_of'];
        }

        $work_object->product_result = new stdClass();

        $work_object = $this->setClassDependencies($work_object);

        $work_object = $this->satisfyDependencies($work_object);

        return $work_object;
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
        return $this->factory_method_namespace->get($options);
    }

    /**
     * Instantiate Factory Method Create Class which will create the Product Factory Method
     *
     * @param   array $options
     *
     * @return  FactoryMethodController
     * @since   1.0.0
     */
    protected function createFactoryMethod(array $options)
    {
        $create = new FactoryMethodCreate($options);

        return $create->instantiateFactoryMethod();
    }

    /**
     * Get Dependencies for Product Requested
     *
     * @param   stdClass $work_object
     *
     * @return  stdClass $work_object
     * @since   1.0.0
     */
    protected function setClassDependencies($work_object)
    {
        return $this->class_dependencies->get($work_object);
    }

    /**
     * Process each outstanding dependency for product request
     *
     * @param   stdClass $work_object
     *
     * @return  stdClass $work_object
     * @since   1.0.0
     */
    protected function satisfyDependencies($work_object)
    {
        $satisfied = array();

        foreach ($work_object->dependencies as $key => $dependency_array) {

            $results = $this->satisfyDependency(
                $dependency_array['product_name'],
                $key,
                $dependency_array['product_namespace'],
                $work_object
            );

            if ($results === true) {
                $satisfied[] = $key;
            }
        }

        $this->satisfyDependenciesUnset($work_object, $satisfied);

        return $work_object;
    }

    /**
     * Unset dependencies
     *
     * @param   stdClass $work_object
     * @param   array    $satisfied
     *
     * @return  stdClass $work_object
     * @since   1.0.0
     */
    protected function satisfyDependenciesUnset($work_object, array $satisfied = array())
    {
        if (count($satisfied) > 0) {
            foreach ($satisfied as $key) {
                unset($work_object->dependencies[$key]);
            }
        }

        return $work_object;
    }

    /**
     * Update Service for Dependency Value
     *
     * @param   string   $dependency_name
     * @param   string   $dependency_key
     * @param   stdClass $work_object
     *
     * @return  boolean
     * @since   1.0.0
     */
    protected function satisfyDependency($dependency_name, $dependency_key, $dependency_namespace, $work_object)
    {
        if ($this->hasContainerEntry($dependency_name) === false) {
        } else {
            $dependency_value = $this->getContainerEntry($dependency_name);
            $work_object->factory_method->setDependencyValue($dependency_key, $dependency_value);

            return true;
        }

        $this->addDependencyToQueue($dependency_name, $dependency_namespace, $work_object->options['product_name']);

        return false;
    }

    /**
     * Add Dependency to Queue
     *
     * @param   string $dependency_name
     * @param   string $product_name
     * @param   string $dependency_namespace
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function addDependencyToQueue($dependency_name, $dependency_namespace, $product_name)
    {
        if (isset($this->request_names_to_id[$dependency_name])) {
        } else {
            $this->to_be_processed_requests[$dependency_name]
                = array(
                'dependency_of'     => $product_name,
                'product_namespace' => $dependency_namespace
            );
        }

        return $this;
    }

    /**
     * Set an entry in the process_requests array and a cross reference in $request_names_to_id
     *
     * @param   string   $product_name
     * @param   stdClass $work_object
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setProcessRequestsArray($product_name, $work_object)
    {
        $queue_id                                 = $work_object->options['ioc_id'];
        $this->process_requests[$queue_id]        = $work_object;
        $this->request_names_to_id[$product_name] = $queue_id;

        if (isset($this->to_be_processed_requests[$product_name])) {
            unset($this->to_be_processed_requests[$product_name]);
        }

        return $this;
    }

    /**
     * Instantiate Class now that dependencies have been satisfied and finish processing
     *
     * @param   stdClass $work_object
     *
     * @return  Schedule
     * @since   1.0.0
     */
    protected function processFactoryModel($work_object)
    {
        $methods = array(
            'processFactoryModelProductCreate',
            'processFactoryModelRemoveContainerEntries',
            'processFactoryModelSetContainerEntries',
            'processFactoryModelScheduleRequests',
            'processFactoryModelSetDependencyOfInstances'
        );

        foreach ($methods as $method) {
            $work_object = $this->$method($work_object);
        }

        $this->unsetProcessRequestsArray($work_object->options['product_name']);

        $this->product_result = $work_object->product_result;

        return $this;
    }

    /**
     * Set an entry in the process_requests array and a cross reference in $request_names_to_id
     *
     * @param   string $product_name
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function unsetProcessRequestsArray($product_name)
    {
        $queue_id = $this->request_names_to_id[$product_name];

        unset($this->process_requests[$queue_id]);
        unset($this->request_names_to_id[$product_name]);

        return $this;
    }

    /**
     * Instantiate Class
     *
     * @param   string $work_object
     *
     * @return  string
     * @since   1.0.0
     */
    protected function processFactoryModelProductCreate($work_object)
    {
        $work_object->factory_method->onBeforeInstantiation();
        $work_object->factory_method->instantiateClass();
        $work_object->factory_method->onAfterInstantiation();
        $product_result              = $work_object->factory_method->getProductValue();
        $work_object->product_result = $product_result;

        if ($work_object->factory_method->getStoreContainerEntryIndicator() === true) {
            $this->container->set($work_object->options['container_key'], $work_object->product_result);
        }

        return $work_object;
    }

    /**
     * Remove Container Entries, as requested by Factory Model
     *
     * @param   string $work_object
     *
     * @return  string
     * @since   1.0.0
     */
    protected function processFactoryModelRemoveContainerEntries($work_object)
    {
        if (count($work_object->factory_method->removeContainerEntries()) > 0) {
            $this->processFactoryModelArray($work_object->factory_method->removeContainerEntries(), 'remove');
        }

        return $work_object;
    }

    /**
     * Set Container Entries, as requested by Factory Model
     *
     * @param   string $work_object
     *
     * @return  string
     * @since   1.0.0
     */
    protected function processFactoryModelSetContainerEntries($work_object)
    {
        if (count($work_object->factory_method->setContainerEntries()) > 0) {
            $this->processFactoryModelArray($work_object->factory_method->setContainerEntries(), 'set');
        }

        return $work_object;
    }

    /**
     * Set Container Entries, as requested by Factory Model
     *
     * @param   array  $array
     * @param   string $method
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function processFactoryModelArray($array, $method)
    {
        foreach ($array as $product_name => $value) {

            if ($method === 'set') {
                $this->container->set($product_name, $value);
            } else {
                if ($this->container->has($product_name) === true) {
                    $this->container->remove($product_name);
                }
            }
        }

        return $this;
    }

    /**
     * Schedule additional Product requests, as specified by Factory Model
     *
     * @param   string $work_object
     *
     * @return  string
     * @since   1.0.0
     */
    protected function processFactoryModelScheduleRequests($work_object)
    {
        $schedule = $work_object->factory_method->scheduleFactories();

        if (is_array($schedule) && count($schedule) > 0) {
            foreach ($schedule as $product_name => $options) {
                $this->to_be_processed_requests[$product_name] = $options;
            }
        }

        return $work_object;
    }

    /**
     * Update Service for Dependency Value
     *
     * @param   stdClass $work_object
     *
     * @return  stdClass
     * @since   1.0.0
     */
    protected function processFactoryModelSetDependencyOfInstances($work_object)
    {
        if (count($work_object->dependency_of) === 0) {
            return $work_object;
        }

        foreach ($work_object->dependency_of as $dependency_key) {
            $queue_id          = $this->request_names_to_id[$dependency_key];
            $dependency_object = $this->process_requests[$queue_id];
            $dependency_object->factory_method->setDependencyValue(
                $work_object->options['product_name'],
                $work_object->product_result
            );
        }

        return $work_object;
    }

    /**
     * See if product already exists within the container
     *
     * @param   string $key
     *
     * @return  boolean
     * @since   1.0.0
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
     * @since   1.0.0
     */
    protected function getContainerEntryKey($key)
    {
        $new_key = $this->container->getKey($key, true);
        if ($new_key === false) {
            $new_key = $key;
        }
        return $new_key;
    }

    /**
     * See if product already exists within the container
     *
     * @param   string $key
     *
     * @return  mixed
     * @since   1.0.0
     */
    protected function getContainerEntry($key)
    {
        if ($this->container->has($key) === false) {
            return false;
        }

        return $this->container->get($key);
    }

    /**
     * Create Container
     *
     * @param   array $factory_method_aliases
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function createContainer(array $factory_method_aliases = array())
    {
        $this->container = new Container($factory_method_aliases);

        return $this;
    }

    /**
     * Create Factory Method Namespace Object
     *
     * @param   string $standard_adapter_namespace
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function createFactoryMethodNamespace($standard_adapter_namespace)
    {
        if (trim($standard_adapter_namespace) === '') {
            $this->standard_adapter_namespace = 'Molajo\\IoC\\StandardFactoryMethod';
        } else {
            $this->standard_adapter_namespace = $standard_adapter_namespace;
        }

        $this->factory_method_namespace = new FactoryMethodNamespace($standard_adapter_namespace);

        return $this;
    }

    /**
     * Create Class Dependencies Object
     *
     * @param   string $class_dependencies_file
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function createClassDependencies($class_dependencies_file)
    {
        $this->class_dependencies = new ClassDependencies($class_dependencies_file);

        return $this;
    }
}
