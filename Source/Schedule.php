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
     * Standard IoC Factory Method (Used when no custom Factory Method is required)
     *
     * @var     array
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
     * @param  object             $class_dependencies
     * @param  string             $standard_adapter_namespace
     *
     * @since  1.0.0
     */
    public function __construct(
        ContainerInterface $container,
        $class_dependencies,
        $standard_adapter_namespace = 'Molajo\\IoC\\StandardFactoryMethod'
    ) {
        $this->container                  = $container;
        $this->class_dependencies         = $class_dependencies;
        $this->standard_adapter_namespace = $standard_adapter_namespace;
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

        $work_object = $this->setProductRequest($product_name, $options);

        $this->process_requests[$work_object->options['ioc_id']] = $work_object;

        return $this->product_result;
    }

    /**
     * Schedule Product Factory Method
     *
     * Handles requests for FactoryMethod product, including dependency fulfillment
     *
     * @return  Schedule
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function processRequestQueue()
    {
        while (count($this->process_requests) > 0) {

            $this->processRequests();

            if (count($this->to_be_processed_requests) > 0) {
                $this->processNewRequestQueue();
            }
        }

        return $this;
    }

    /**
     * Process each product request to satisfy dependencies and, when all dependencies
     *  have been met, to complete the Factory Method processes including creating the product
     *
     * @return  Schedule
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function processRequests()
    {
        foreach ($this->process_requests as $id => $work_object) {
            $work_object = $this->satisfyDependencies($work_object);

            if ($work_object->adapter->getRemainingDependencyCount() === 0) {
                $work_object = $this->completeRequest($work_object);
            }
        }

        return $this;
    }

    /**
     * Process each product request to satisfy dependencies and, when all dependencies
     *  have been met, to complete the Factory Method processes including creating the product
     *
     * @return  Schedule
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function processNewRequestQueue()
    {
        foreach ($this->to_be_processed_requests as $product_name => $options) {
            $work_object                                             = $this->setProductRequest(
                $product_name,
                $options
            );
            $this->process_requests[$work_object->options['ioc_id']] = $work_object;
            unset($this->to_be_processed_requests[$product_name]);
        }

        $this->to_be_processed_requests = array();

        return $this;
    }

    /**
     * Process Product Request
     *
     * Initializes request and adds into the processing queue
     *
     * @param   string $product_name
     * @param   array  $options
     *
     * @return  $this
     * @since   1.0.0
     */
    public function setProductRequest($product_name = null, array $options = array())
    {
        $options['product_name']  = $product_name;
        $options['container_key'] = $this->getContainerEntryKey($product_name);
        $options['ioc_id']        = $this->queue_id++;

        $options = $this->getFactoryMethodNamespace($options);

        $work_object                 = new stdClass();
        $work_object->options        = $options;
        $work_object->factory_method = $this->createFactoryMethod($options);
        $work_object->dependencies   = new stdClass();
        $work_object->dependency_of  = new stdClass();
        $work_object->product_result = new stdClass();

        $work_object = $this->setClassDependencies($work_object);
        var_dump($work_object);
        die;
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
        $namespace = new FactoryMethodNamespace(
            $this->standard_adapter_namespace,
            $options
        );

        return $namespace->get();
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
     * Get Dependencies for Product Requested
     *
     * @param   stdClass $work_object
     *
     * @return  stdClass $work_object
     * @since   1.0.0
     */
    protected function satisfyDependencies($work_object)
    {
        //foreach ($work_object->dependencies as $product_name => )
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
     * Instantiate Class now that dependencies have been satisfied and finish processing
     *
     * @param   string $work_object
     *
     * @return  object
     * @since  1.0.0
     */
    protected function completeRequest($work_object)
    {

        unset($this->process_requests[$id]);
        if ($work_object->requested_name == $schedule_product_name) {
            if ($work_object->product_result == '') {
            } else {
                $schedule_product_result = $work_object->product_result;
            }
        }

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
     * See if product already exists within the container
     *
     * @param   string $key
     *
     * @return  boolean
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
}
