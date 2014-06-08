<?php
/**
 * Base Factory Method
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC;

use CommonApi\Exception\RuntimeException;
use CommonApi\IoC\FactoryInterface;
use CommonApi\IoC\FactoryBatchInterface;
use Exception;
use ReflectionClass;
use stdClass;

/**
 * Base Factory Method
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
abstract class FactoryMethodBase implements FactoryInterface, FactoryBatchInterface
{
    /**
     * IoC ID from Controller
     *
     * @var    string
     * @since  1.0.0
     */
    protected $ioc_id = null;

    /**
     * Product Name
     *
     * @var    string
     * @since  1.0.0
     */
    protected $product_name = null;

    /**
     * Product Namespace
     *
     * @var    string
     * @since  1.0.0
     */
    protected $product_namespace = null;

    /**
     * Static Instance Indicator
     *
     * @var    boolean
     * @since  1.0.0
     * @static
     */
    protected $static_instance_indicator = false;

    /**
     * Store Instance Indicator
     *
     * @var    boolean
     * @since  1.0.0
     */
    protected $store_instance_indicator = false;

    /**
     * Store Properties
     *
     * @var    boolean
     * @since  1.0.0
     */
    protected $store_properties_indicator = false;

    /**
     * Options
     *
     * @var    array
     * @since  1.0.0
     */
    protected $options = array();

    /**
     * Reflection Parameters
     *
     * @var    array
     * @since  1.0.0
     */
    protected $reflection = array();

    /**
     * Dependencies
     *
     * @var    array
     * @since  1.0.0
     */
    protected $dependencies = array();

    /**
     * Product Result
     *
     * @var    object
     * @since  1.0.0
     */
    protected $product_result = null;

    /**
     * Static Product Result
     *
     * @static
     * @var    object  Services
     * @since  1.0.0
     */
    protected static $static_product_result = null;

    /**
     * Container Entries to remove from IoCC
     *
     * @var    array
     * @since  1.0.0
     */
    protected $remove_container_entries = array();

    /**
     * Services to update within the IoCC
     *
     * @var    array
     * @since  1.0.0
     */
    protected $set_container_entries = array();

    /**
     * Services to schedule for Product Creation
     *
     * @var    array
     * @since  1.0.0
     */
    protected $schedule_factory_methods = array();

    /**
     * List of Property Array
     *
     * @var    array
     * @since  1.0.0
     */
    protected $factory_method_adapter_property_array
        = array(
            'ioc_id',
            'product_name',
            'product_namespace',
            'static_instance_indicator',
            'store_instance_indicator',
            'store_properties_indicator',
            'reflection',
            'dependencies',
            'product_result',
            'static_product_result',
            'remove_container_entries',
            'set_container_entries',
            'schedule_factory_methods'
        );

    /**
     * Constructor
     *
     * @param   $options
     *
     * @since  1.0.0
     */
    public function __construct(array $options = array())
    {
        $this->schedule_factory_methods = array();

        if (is_array($options)) {
        } else {
            $options = array();
        }

        if (count($options) > 0) {
            foreach ($this->factory_method_adapter_property_array as $property) {
                if (isset($options[$property])) {
                    $this->$property = $options[$property];
                    unset($options[$property]);
                }
            }
        }

        $this->options = $options;
    }

    /**
     * Factory Method Controller requests Product Namespace from Factory Method
     *
     * @return  string
     * @since  1.0.0
     */
    public function getNamespace()
    {
        return $this->product_namespace;
    }

    /**
     * Factory Method Controller requests Service Options from Factory Method
     *
     * @return  array
     * @since  1.0.0
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Factory Method Controller retrieves "store instance indicator" from Factory Method
     *
     * @return  boolean
     * @since  1.0.0
     */
    public function getStoreContainerEntryIndicator()
    {
        return $this->store_instance_indicator;
    }

    /**
     * Factory Method can use this method to define Service Dependencies
     *  or use the Service Dependencies automatically defined by Reflection processes
     *
     * @param   array $reflection
     *
     * @return  array
     * @since   1.0.0
     */
    public function setDependencies(array $reflection = array())
    {
        $this->reflection = $reflection;

        if (count($this->reflection) === 0) {
            return $this->dependencies;
        }

        foreach ($this->reflection as $dependency) {
            $this->setDependencyUsingReflection($dependency);
        }

        return $this->dependencies;
    }

    /**
     * Use reflection data to establish dependency
     *
     * @param   object $dependency
     *
     * @return  array
     * @since   1.0.0
     */
    protected function setDependencyUsingReflection($dependency)
    {
        /** Other data type parameters */
        if ($dependency->instance_of === null) {
            return $this;
        }

        /** Interface */
        $dependency_name = ucfirst(strtolower($dependency->name));

        if ($dependency_name === $this->product_name) {
            unset($this->reflection[$dependency_name]);

        } else {
            $this->setDependencyUsingReflectionInterface($dependency_name, $dependency);
        }

        return $this;
    }

    /**
     * Use reflection data to establish dependency for Interfaces
     *
     * @param   object $dependency
     *
     * @return  array
     * @since   1.0.0
     */
    protected function setDependencyUsingReflectionInterface($dependency_name, $dependency)
    {
        if (count($dependency->implemented_by) === 1) {
            $options                                          = array();
            $options['product_namespace']                     = $dependency->implemented_by[0];
            $options['product_name']                          = $dependency_name;
            $this->schedule_factory_methods[$dependency_name] = $options;
            $this->dependencies[$dependency_name]             = $options;

            return $this;
        }

        /** Multiple interfaces */
        $this->dependencies[$dependency_name] = array();

        return $this;
    }

    /**
     * Logic contained within this method is invoked after Dependencies Instances are available
     *  and before the instantiateClass Method is invoked
     *
     * @param   array $dependency_values
     *
     * @return  array
     * @since   1.0.0
     */
    public function onBeforeInstantiation(array $dependency_values = null)
    {
        /** Were Instantiated Classes Passed In? */
        if ($dependency_values === null || count($dependency_values) === 0) {
            return $this->dependencies;
        }

        $this->onBeforeInstantiationDependencyValues($dependency_values);

        /** Make certain each Reflection entry matches a Dependencies or Options array */
        if (count($this->reflection) > 0) {

            $reflection = $this->reflection;

            foreach ($reflection as $dependency) {

                /** Dependencies */
                $found = $this->onBeforeInstantiationVerifyDependency($dependency);

                /** Options */
                if ($found === false) {
                    $found = $this->onBeforeInstantiationVerifyOptions($dependency);
                }

                if ($found === false) {
                    $this->dependencies[$dependency->name] = $dependency->default_value;
                }
            }
        }

        return $this->dependencies;
    }

    /**
     * Before class instantiation, set gathered dependency values for dependencies
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function onBeforeInstantiationDependencyValues(array $dependency_values)
    {
        foreach ($dependency_values as $key1 => $value1) {
            foreach ($this->dependencies as $key2 => $value2) {
                if (strtolower($key1) == strtolower($key2)) {
                    $this->dependencies[$key2] = $value1;
                    unset($dependency_values[$key1]);
                }
            }
        }

        foreach ($dependency_values as $key => $value) {
            $this->dependencies[$key] = $value;
            unset($dependency_values[$key]);
        }

        return $dependency_values;
    }

    /**
     * For specified dependency, determine if a dependency value exists
     *
     * @param   object $dependency
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function onBeforeInstantiationVerifyDependency($dependency)
    {
        $found = false;

        foreach ($this->dependencies as $key => $value) {
            if (strtolower($dependency->name) == strtolower($key)) {
                $found = true;
                break;
            }
        }

        return $found;
    }

    /**
     * For specified dependency, determine if an options array entry value exists
     *
     * @param   object $dependency
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function onBeforeInstantiationVerifyOptions($dependency)
    {
        $found = false;

        foreach ($this->options as $key => $value) {

            if (strtolower($dependency->name) == strtolower($key)) {
                $this->dependencies[$dependency->name] = $value;
                $found                                 = true;
                break;
            }
        }

        return $found;
    }

    /**
     * Service instantiated automatically or within this method by the Factory Method
     *
     * @return  $this
     * @since   1.0.0
     */
    public function instantiateClass()
    {
        if ($this->product_namespace === null) {
            return $this;
        }

        if ($this->static_instance_indicator === true) {
            self::instantiateStatic($this->product_namespace);
            return $this;
        }

        $dependencies = array();
        if (count($this->reflection) > 0) {
            $dependencies = $this->processReflectionDependencies($dependencies);
        }

        if (method_exists($this->product_namespace, '__construct')
            && count($dependencies) > 0
        ) {
            return $this->instantiateClassWithDependencies($dependencies);
        }

        return $this->instantiateClassWithoutDependencies();
    }

    /**
     * Process Reflection Dependencies
     *
     * @param   array $dependencies
     *
     * @return  mixed
     * @since   1.0.0
     */
    protected function processReflectionDependencies(array $dependencies = array())
    {
        foreach ($this->reflection as $dependency) {

            $dependency_name  = $dependency->name;
            $dependency_value = $dependency->default_value;

            foreach ($this->dependencies as $key => $value) {

                if (strtolower($dependency_name) == strtolower($key)) {
                    $dependency_value = $value;
                    break;
                }
            }

            $dependencies[$dependency_name] = $dependency_value;
        }

        return $dependencies;
    }

    /**
     * Instantiate Class with dependencies
     *
     * @param   array $dependencies
     *
     * @return  mixed
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function instantiateClassWithDependencies(array $dependencies = array())
    {
        try {
            $reflection = new ReflectionClass($this->product_namespace);

            $this->product_result = $reflection->newInstanceArgs($dependencies);

        } catch (Exception $e) {

            throw new RuntimeException(
                'IoC instantiateClass with dependencies Failed: '
                . $this->product_namespace . ' ' . $e->getMessage()
            );
        }

        return $this;
    }

    /**
     * Instantiate Class without dependencies
     *
     * @return  mixed
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function instantiateClassWithoutDependencies()
    {
        try {
            $class = $this->product_namespace;

            $this->product_result = new $class();

        } catch (Exception $e) {

            throw new RuntimeException(
                'IoC instantiateClass without dependencies Failed: '
                . $this->product_namespace . '  ' . $e->getMessage()
            );
        }

        return $this;
    }

    /**
     * Instantiate Service Class Statically
     *
     * @param   string $product_namespace
     *
     * @static
     *
     * @return  void
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public static function instantiateStatic($product_namespace)
    {
        try {
            self::$static_product_result = new $product_namespace();

        } catch (Exception $e) {
            throw new RuntimeException(
                'IoC instantiateClass instantiateStatic  ' . $product_namespace . ' ' . $e->getMessage()
            );
        }

        return;
    }

    /**
     * Logic contained within this method is invoked after the class construction
     *  and can be used for setter logic or other post-construction processing
     *
     * @return  $this
     * @since   1.0.0
     */
    public function onAfterInstantiation()
    {
        return $this;
    }

    /**
     * Factory Method Controller requests Product Result for just created Class
     *
     * @return  object
     * @since   1.0.0
     */
    public function getProductValue()
    {
        if ($this->static_instance_indicator === true) {
            return $this->getProductValueStatic();
        }

        if ($this->store_instance_indicator === true) {
            return $this->getProductValueInstance();
        }

        if ($this->store_properties_indicator === true) {
            return $this->getProductValueProperties();
        }

        return $this->getProductValueDoNotSave();
    }

    /**
     * Get Product Value: Static
     *
     * @return  object
     * @since   1.0.0
     */
    protected function getProductValueStatic()
    {
        $this->store_instance_indicator   = true;
        $this->store_properties_indicator = false;

        return self::$static_product_result;
    }

    /**
     * Get Product Value: Instance
     *
     * @return  object
     * @since   1.0.0
     */
    protected function getProductValueInstance()
    {
        $this->store_properties_indicator = false;
        $this->static_instance_indicator  = false;

        return $this->product_result;
    }

    /**
     * Get Product Value: Properties
     *
     * @return  object
     * @since   1.0.0
     */
    protected function getProductValueProperties()
    {

        $this->store_instance_indicator  = true;
        $this->static_instance_indicator = false;

        return $this->product_result->get('*', array());
    }

    /**
     * Get Product Value: Properties
     *
     * @return  object
     * @since  1.0.0
     */
    protected function getProductValueDoNotSave()
    {

        $this->store_instance_indicator   = false;
        $this->store_properties_indicator = false;
        $this->static_instance_indicator  = false;

        return $this->product_result;
    }

    /**
     * Request for array of Products to be removed from the IoC Container
     *
     * @return  array
     * @since  1.0.0
     */
    public function removeContainerEntries()
    {
        return $this->remove_container_entries;
    }

    /**
     * Request for array of Products and Values to be saved to the IoC Container
     *
     * @return  array
     * @since  1.0.0
     */
    public function setContainerEntries()
    {
        return $this->set_container_entries;
    }

    /**
     * Request for array of Factory Methods to be Scheduled
     *
     * @return  array
     * @since  1.0.0
     */
    public function scheduleFactories()
    {
        return $this->schedule_factory_methods;
    }

    /**
     * Read File
     *
     * @param   string $file_name
     *
     * @return  array
     * @since   1.0.0
     */
    protected function readFile($file_name)
    {
        if (file_exists($file_name)) {
        } else {
            return array();
        }

        $input = file_get_contents($file_name);

        return $this->readFileIntoArray($input);
    }

    /**
     * Process JSON string by loading into an array
     *
     * @param   string $input
     *
     * @return  array
     * @since   1.0.0
     */
    protected function readFileIntoArray($input)
    {
        $temp = json_decode($input);

        $temp_array = array();
        if (count($temp) > 0) {
            $temp_array = array();
            foreach ($temp as $key => $value) {
                $temp_array[$key] = $value;
            }
        }

        return $temp_array;
    }

    /**
     * Sort Object
     *
     * @param   object $input_object
     *
     * @return  stdClass
     * @since  1.0.0
     */
    protected function sortObject($input_object)
    {
        $hold_array = $this->sortObjectLoadIntoArray($input_object);

        return $this->sortObjectLoadSortedArrayIntoObject($hold_array);
    }

    /**
     * Sort Object
     *
     * @param   object $input_object
     *
     * @return  array
     * @since   1.0.0
     */
    protected function sortObjectLoadIntoArray($input_object)
    {
        $hold_array = array();
        foreach (\get_object_vars($input_object) as $key => $value) {
            $hold_array[$key] = $value;
        }

        ksort($hold_array);

        return $hold_array;
    }

    /**
     * Sort Object
     *
     * @param   array $hold_array
     *
     * @return  stdClass
     * @since   1.0.0
     */
    protected function sortObjectLoadSortedArrayIntoObject(array $hold_array = array())
    {
        $new_object = new stdClass();

        if (count($hold_array) > 0) {
            foreach ($hold_array as $key => $value) {
                $new_object->$key = $value;
            }
        }

        return $new_object;
    }
}
