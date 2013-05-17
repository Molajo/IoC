<?php
/**
 * Abstract Dependency Injector Class
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC\Handler;

use Exception;
use Molajo\IoC\Api\InjectorInterface;
use Molajo\IoC\Exception\InjectorException;

/**
 * Abstract Dependency Injector Class
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class AbstractInjector implements InjectorInterface
{
    /**
     * getService Closure
     *
     * @var     string
     * @since   1.0
     */
    public $getService;

    /**
     * setService Closure
     *
     * @var     string
     * @since   1.0
     */
    public $setService;

    /**
     * cloneService Closure
     *
     * @var     string
     * @since   1.0
     */
    public $cloneService;

    /**
     * removeService Closure
     *
     * @var     string
     * @since   1.0
     */
    public $removeService;

    /**
     * Service
     *
     * @var     string
     * @since   1.0
     */
    public $service = null;

    /**
     * Service Namespace
     *
     * @var     string
     * @since   1.0
     */
    public $service_namespace = null;

    /**
     * Static Instance Indicator
     *
     * @var     boolean
     * @since   1.0
     * @static
     */
    public $static_instance_indicator = false;

    /**
     * Store Instance Indicator
     *
     * @var     boolean
     * @since   1.0
     */
    public $store_instance_indicator = false;

    /**
     * Store Properties
     *
     * @var     boolean
     * @since   1.0
     */
    public $store_properties_indicator = false;

    /**
     * Service Instance
     *
     * @var     object
     * @since   1.0
     */
    public $service_instance = null;

    /**
     * Options
     *
     * @var     array
     * @since   1.0
     */
    public $options = array();

    /**
     * Static Service Instance
     *
     * @static
     * @var    object  Services
     * @since  1.0
     */
    public static $static_service_instance = null;

    /**
     * List of Property Array
     *
     * @var    array
     * @since  1.0
     */
    protected $property_array = array(
        'getService',
        'setService',
        'cloneService',
        'removeService',
        'service',
        'service_namespace',
        'static_instance_indicator',
        'store_instance_indicator',
        'store_properties_indicator',
        'service_instance',
        'options',
        'static_service_instance',
        'property_array'
    );

    /**
     * Constructor
     *
     * @param   $options
     *
     * @since   1.0
     */
    public function __construct($options)
    {
        if (is_array($options)) {
        } else {
            $options = array();
        }

        if (count($options) > 0) {
            foreach ($this->property_array as $property) {
                if (isset($options[$property])) {
                    $this->$property = $options[$property];
                    unset($options[$property]);
                }
            }
        }

        $this->options = $options;
    }

    /**
     * Get the current value (or default) of the specified property
     *
     * @param   string $key
     * @param   null   $default
     *
     * @return  mixed
     * @since   1.0
     * @throws  InjectorException
     */
    public function get($key, $default = null)
    {
        if (in_array($key, $this->property_array)) {
            return $this->$key;
        }

        throw new InjectorException
        ('Injector: ' . $this->service_namespace .
            ' attempting to get value for unknown key: ' . $key);
    }

    /**
     * Set the value of a property
     *
     * @param   string $key
     * @param   string $value
     *
     * @return  $this
     * @since   1.0
     * @throws  InjectorException
     */
    public function set($key, $value = null)
    {
        if (in_array($key, $this->property_array)) {
            $this->$key = $value;

            return $this;
        }

        throw new InjectorException
        ('Injector: ' . $this->service_namespace .
            ' attempting to set value for unknown key: ' . $key);
    }

    /**
     * Should instance be stored for reuse?
     *
     * @return  bool
     * @since   1.0
     * @throws  InjectorException
     */
    public function storeInstance()
    {
        if ($this->static_instance_indicator === true) {
            $this->store_instance_indicator = true;

        } else {
            if ($this->store_instance_indicator === true) {
            } else {
                $this->store_instance_indicator = false;
            }
        }

        return $this->store_instance_indicator;
    }

    /**
     * Follows instantiation of the service class and before the method identified as the "start" method
     *
     * @return  object
     * @since   1.0
     */
    public function onBeforeServiceInstantiate()
    {
        return $this;
    }

    /**
     * Instantiate Class
     *
     * @param   bool $create_static
     *
     * @return  $this
     * @since   1.0
     * @throws  InjectorException
     */
    public function instantiate($create_static = false)
    {
        if ($this->service_namespace === null) {
            return $this;
        }

        try {
            if ($create_static === true) {
                self::$static_service_instance = self::instantiate_static($this->service_namespace);
            } else {
                $this->service_instance = new $this->service_namespace();
            }

        } catch (Exception $e) {

            throw new InjectorException
            ('IoC: Injector Instance Failed for ' . $this->service_namespace
                . ' failed.' . $e->getMessage());
        }

        return $this;
    }

    /**
     * Instantiate Service class Statically
     *
     * @param   string $service_namespace
     *
     * @static
     *
     * @return  null|object
     * @since   1.0
     * @throws  InjectorException
     */
    public static function instantiate_static($service_namespace)
    {
        try {
            self::$static_service_instance = new $service_namespace();

        } catch (Exception $e) {
            throw new InjectorException
            ('IoC: Injector Instance Failed for ' . $service_namespace
                . ' failed.' . $e->getMessage());
        }
    }

    /**
     * Follows the completion of the instantiate service method
     *
     * @return  object
     * @since   1.0
     * @throws  InjectorException
     */
    public function onAfterServiceInstantiate()
    {
        return $this;
    }

    /**
     * Initialise Service Class, if the method exists
     *
     * @return  object
     * @since   1.0
     * @throws  InjectorException
     */
    public function initialise()
    {
        if ($this->service_namespace === null) {
            return $this;
        }

        if ($this->static_instance_indicator === true) {
            return $this;
        }

        if (method_exists($this->service_instance, 'initialise')) {
        } else {
            return $this;
        }

        $this->service_instance->initialise();

        return $this;
    }

    /**
     * Follows the completion of Initialise
     *
     * @return  object
     * @since   1.0
     * @throws  InjectorException
     */
    public function onAfterServiceInitialise()
    {
        return $this;
    }

    /**
     * Get Service Instance
     *
     * @return  object
     * @since   1.0
     * @throws  InjectorException
     */
    public function getServiceInstance()
    {
        if ($this->static_instance_indicator === true) {
            return self::$static_service_instance;
        }

        return $this->service_instance;
    }
}
