<?php
/**
 * Build
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC;

use Exception;
use CommonApi\Exception\RuntimeException;
use CommonApi\IoC\FactoryInterface;
use stdClass;

/**
 * Build
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class Build
{
    /**
     * Options
     *
     * @var     array
     * @since  1.0.0
     */
    protected $options = array();

    /**
     * Constructor
     *
     * @param   array  $options
     *
     * @since  1.0.0
     */
    public function __construct(
        array $options = array()
    ) {
        $this->options = $options;
    }

    /**
     * Initiate Build Process
     *
     * @return  stdClass
     * @since  1.0.0
     */
    protected function initiate()
    {
        $work_object                     = new stdClass();
        $work_object->options            = $this->options;
        $work_object->factory_method     = null;
        $work_object->product_result     = null;

        return $work_object;
    }

    protected function getReflectionDependencies()
    {
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
    }

    /**
     * Get Factory Method Namespace
     *
     * @param   stdClass $work_object
     *
     * @return  $this
     * @since  1.0.0
     * @throws \CommonApi\Exception\RuntimeException
     */
    protected function instantiateFactoryMethod($work_object)
    {
        /** 1. Create Factory Method Adapter Instance */
        try {

            $factory_method_adapter = $this->getFactoryMethodAdapter
            (
                $work_object->name,
                $work_object->factory_method_namespace,
                $work_object->options
            );

        } catch (Exception $e) {
            throw new RuntimeException
            (
                'IoC instantiateFactoryMethod: Exception: ' . $e->getMessage()
            );
        }

        /** 2. Create Factory Method Adapter Instance */
        try {
            $adapter = $this->getFactoryMethod($factory_method_adapter);

        } catch (Exception $e) {
            throw new RuntimeException
            (
                'IoC instantiateFactoryMethod: Exception: ' . $e->getMessage()
            );
        }


        /** 5. Clean up */
        $work_object->adapter = $adapter;

        $this->process_requests[$work_object->id] = $work_object;

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
     * Instantiate DI Adapter, injecting it with the Handler instance
     *
     * @param   FactoryInterface $factory_method_adapter
     *
     * @return  FactoryMethodController  CommonApi\IoC\FactoryInterface
     * @since  1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function getFactoryMethod(FactoryInterface $factory_method_adapter)
    {
        try {
            $adapter = new FactoryMethodController($factory_method_adapter);

        } catch (Exception $e) {
            throw new RuntimeException(
                'Ioc getFactoryMethod Instantiate ServiceItem Exception: ' . $e->getMessage()
            );
        }

        return $adapter;
    }

    /**
     * Instantiate Factory Method to inject into the Adapter Constructor
     *
     * @param   string  $product_name
     * @param   string  $factory_method_namespace
     * @param   options $options
     *
     * @return  FactoryInterface     * @throws  \CommonApi\Exception\RuntimeException
     * @since  1.0.0
     */
    protected function getFactoryMethodAdapter($product_name, $factory_method_namespace, array $options)
    {
        if ($factory_method_namespace == $this->standard_factory_method_namespace) {
            if (isset($options['factory_method_namespace'])) {
            } else {
                if (isset($this->adapter_aliases[$product_name])) {
                    $options['factory_method_namespace'] = $this->adapter_aliases[$product_name];
                }
            }
        }

        try {
            $class = $factory_method_namespace;

            $factory_method_adapter = new $class($options);

        } catch (Exception $e) {

            throw new RuntimeException
            (
                'IoC getFactoryMethod Instantiation Exception: '
                . $factory_method_namespace . ' ' . $e->getMessage()
            );
        }

        return $factory_method_adapter;
    }
}
