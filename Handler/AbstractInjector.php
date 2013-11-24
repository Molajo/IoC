<?php
/**
 * Abstract Dependency Injector Handler
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC\Handler;

use Exception;
use ReflectionClass;
use CommonApi\IoC\ServiceHandlerInterface;
use CommonApi\Exception\RuntimeException;

/**
 * Abstract Dependency Injector Handler
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class AbstractInjector implements ServiceHandlerInterface
{
    /**
     * IoC ID from Controller
     *
     * @var     string
     * @since   1.0
     */
    protected $ioc_id = null;

    /**
     * Service Name
     *
     * @var     string
     * @since   1.0
     */
    protected $service_name = null;

    /**
     * Service Namespace
     *
     * @var     string
     * @since   1.0
     */
    protected $service_namespace = null;

    /**
     * Static Instance Indicator
     *
     * @var     boolean
     * @since   1.0
     * @static
     */
    protected $static_instance_indicator = false;

    /**
     * Store Instance Indicator
     *
     * @var     boolean
     * @since   1.0
     */
    protected $store_instance_indicator = false;

    /**
     * Store Properties
     *
     * @var     boolean
     * @since   1.0
     */
    protected $store_properties_indicator = false;

    /**
     * Options
     *
     * @var     array
     * @since   1.0
     */
    protected $options = array();

    /**
     * Reflection Parameters
     *
     * @var     object
     * @since   1.0
     */
    protected $reflection = null;

    /**
     * Dependencies
     *
     * @var     array
     * @since   1.0
     */
    protected $dependencies = array();

    /**
     * Service Instance
     *
     * @var     object
     * @since   1.0
     */
    protected $service_instance = null;

    /**
     * Static Service Instance
     *
     * @static
     * @var    object  Services
     * @since  1.0
     */
    protected static $static_service_instance = null;

    /**
     * Services to request the Controller set using the Container setServices method
     *
     * @var     array
     * @since   1.0
     */
    protected $set_container_instance = array();

    /**
     * Services to request the Controller remove using the Container setServices method
     *
     * @var     array
     * @since   1.0
     */
    protected $remove_container_instance = array();

    /**
     * Services to request the Controller schedule for class creation
     *
     * @var     array
     * @since   1.0
     */
    protected $schedule_service = array();

    /**
     * List of Property Array
     *
     * @var    array
     * @since  1.0
     */
    protected $injector_property_array = array(
        'ioc_id',
        'service_name',
        'service_namespace',
        'static_instance_indicator',
        'store_instance_indicator',
        'store_properties_indicator',
        'reflection',
        'dependencies',
        'service_instance',
        'static_service_instance',
        'set_container_instance',
        'remove_container_instance',
        'schedule_service'
    );

    /**
     * Constructor
     *
     * @param   $options
     *
     * @since   1.0
     */
    public function __construct(array $options = array())
    {
        if (is_array($options)) {
        } else {
            $options = array();
        }

        if (count($options) > 0) {
            foreach ($this->injector_property_array as $property) {
                if (isset($options[$property])) {
                    $this->$property = $options[$property];
                    unset($options[$property]);
                }
            }
        }

        $this->options = $options;
    }

    /**
     * IoC Controller requests Service Name from DI Handler
     *
     * @return  string
     * @since   1.0
     */
    public function getServiceName()
    {
        return $this->service_name;
    }

    /**
     * IoC Controller requests Service Namespace from DI Handler
     *
     * @return  string
     * @since   1.0
     */
    public function getServiceNamespace()
    {
        return $this->service_namespace;
    }

    /**
     * IoC Controller requests Service Options from DI Handler
     *
     * @return  array
     * @since   1.0
     */
    public function getServiceOptions()
    {
        return $this->options;
    }

    /**
     * IoC Controller retrieves "store instance indicator" from DI Handler
     *
     * @return  string
     * @since   1.0
     */
    public function getStoreInstanceIndicator()
    {
        return $this->store_instance_indicator;
    }

    /**
     * IoC Controller provides reflection values which the DI Handler can use to set Dependencies
     *  Or, Dependencies can be specifically defined by the DI Handler.
     *  In either case, Dependencies are returned to the IoC Controller.
     *
     * @param   object $reflection
     *
     * @return  array
     * @since   1.0
     */
    public function setDependencies(array $reflection = null)
    {
        if ($reflection === null) {
            $this->reflection = array();
        } else {
            $this->reflection = $reflection;
        }

        if (count($this->dependencies) > 0) {
            return $this->dependencies;
        }

        if (count($this->reflection) === 0) {
            return $this->dependencies;
        }

        foreach ($this->reflection as $dependency) {

            /** Other datatype parameters */
            if ($dependency->instance_of === null) {
            } else {

                /** Interface */
                $dependency_name = ucfirst(strtolower($dependency->name));

                if ($dependency_name === $this->service_name) {
                    unset($this->reflection[$dependency_name]);
                } else {

                    /** Only one interface */
                    if (count($dependency->implemented_by) === 1) {
                        $options                                  = array();
                        $options['service_namespace']             = $dependency->implemented_by[0];
                        $options['service_name']                  = $dependency_name;
                        $this->schedule_service[$dependency_name] = $options;
                        $this->dependencies[$dependency_name]     = $options;
                    } else {

                        /** Multiple interfaces */
                        $this->dependencies[$dependency_name] = array();
                    }
                }
            }
        }

        return $this->dependencies;
    }

    /**
     * IoC Controller shares Dependency Instances with DI Handler for final processing before Class creation
     *
     * @param   array $dependency_instances
     *
     * @return  array
     * @since   1.0
     */
    public function processFulfilledDependencies(array $dependency_instances = null)
    {
        /** Were Instantiated Classes Passed In? */
        if ($dependency_instances === null || count($dependency_instances) == 0) {
            return $this->dependencies;
        }

        /** Store Instantiated Class within Dependencies Array */
        foreach ($dependency_instances as $key1 => $value1) {
            foreach ($this->dependencies as $key2 => $value2) {
                if (strtolower($key1) == strtolower($key2)) {
                    $this->dependencies[$key2] = $value1;
                    unset($dependency_instances[$key1]);
                }
            }
        }

        foreach ($dependency_instances as $key => $value) {
            $this->dependencies[$key] = $value;
            unset($dependency_instances[$key]);
        }

        /** Make certain each Reflection entry matches a Dependencies or Options array */
        if (count($this->reflection) > 0) {

            $reflection = $this->reflection;

            foreach ($reflection as $dependency) {

                $found = false;

                /** Dependencies */
                foreach ($this->dependencies as $key => $value) {

                    if (strtolower($dependency->name) == strtolower($key)) {
                        $found = true;
                        break;
                    }
                }

                /** Options */
                if ($found === false) {
                    foreach ($this->options as $key => $value) {
                        if (strtolower($dependency->name) == strtolower($key)) {
                            $this->dependencies[$dependency->name] = $value;
                            $found                                 = true;
                            break;
                        }
                    }
                }

                if ($found === false) {
                    $this->dependencies[$dependency->name] = $dependency->default_value;
                }
            }
        }

        return $this->dependencies;
    }

    /**
     * IoC Controller triggers the DI Handler to create the Class for the Service
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function instantiateService()
    {
        if ($this->service_namespace === null) {
            return $this;
        }

        if ($this->static_instance_indicator === true) {
            self::instantiateStatic($this->service_namespace);
            return $this;
        }

        $dependencies = array();

        if (count($this->reflection) > 0) {

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
        }

        if (method_exists($this->service_namespace, '__construct')
            && count($dependencies) > 0
        ) {

            try {
                $reflection             = new ReflectionClass($this->service_namespace);
                $this->service_instance = $reflection->newInstanceArgs($dependencies);

                return $this;
            } catch (Exception $e) {

                throw new RuntimeException
                ('IoC instantiateService Reflection Failed: ' . $this->service_namespace . ' ' . $e->getMessage());
            }
        }

        try {
            $class = $this->service_namespace;

            $this->service_instance = new $class();
        } catch (Exception $e) {

            throw new RuntimeException
            ('IoC instantiateService Failed: ' . $this->service_namespace . '  ' . $e->getMessage());
        }

        return $this;
    }

    /**
     * Instantiate Service Class Statically
     *
     * @param   string $service_namespace
     *
     * @static
     *
     * @return  void
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public static function instantiateStatic($service_namespace)
    {
        try {
            self::$static_service_instance = new $service_namespace();

        } catch (Exception $e) {
            throw new RuntimeException
            ('IoC instantiateService instantiateStatic  ' . $service_namespace . ' ' . $e->getMessage());
        }

        return;
    }

    /**
     * IoC Controller triggers the DI Handler to execute logic that follows class instantiation,
     *  Location for Setter Dependencies or any other actions that must follow Class Creation
     *
     * @return  $this
     * @since   1.0
     */
    public function performAfterInstantiationLogic()
    {
        return $this;
    }

    /**
     * IoC Controller requests Service Instance for just created Class from DI Handler
     *
     * @return  object
     * @since   1.0
     */
    public function getServiceInstance()
    {
        if ($this->static_instance_indicator === true) {
            $this->store_instance_indicator   = true;
            $this->store_properties_indicator = false;

            return self::$static_service_instance;
        } else {

            if ($this->store_instance_indicator === true) {
                $this->store_properties_indicator = false;
                $this->static_instance_indicator  = false;

                return $this->service_instance;
            } elseif ($this->store_properties_indicator === true) {
                $this->store_instance_indicator  = true;
                $this->static_instance_indicator = false;

                return $this->service_instance->get('*', array());
            }
        }

        $this->store_instance_indicator   = false;
        $this->store_properties_indicator = false;
        $this->static_instance_indicator  = false;

        return $this->service_instance;
    }

    /**
     * Following Class creation, DI Handler requests the IoC Controller set Services in the Container
     *
     * @return  array
     * @since   1.0
     */
    public function setService()
    {
        return $this->set_container_instance;
    }

    /**
     * Following Class creation, DI Handler requests the IoC Controller remove Services from the Container
     *
     * @return  array
     * @since   1.0
     */
    public function removeService()
    {
        return $this->remove_container_instance;
    }

    /**
     * Following Class creation, DI Handler requests the IoC Controller instantiate Services
     *
     * @return  array
     * @since   1.0
     */
    public function scheduleNextService()
    {
        return $this->schedule_service;
    }

    /**
     * Read File
     *
     * @param  string $file_name
     *
     * @return array
     * @since  1.0
     */
    protected function readFile($file_name)
    {
        $temp_array = array();

        if (file_exists($file_name)) {
        } else {
            return array();
        }

        $input = file_get_contents($file_name);

        $temp = json_decode($input);

        if (count($temp) > 0) {
            $temp_array = array();
            foreach ($temp as $key => $value) {
                $temp_array[$key] = $value;
            }
        }

        return $temp_array;
    }
}
