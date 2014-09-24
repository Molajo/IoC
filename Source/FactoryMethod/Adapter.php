<?php
/**
 * Factory Method Adapter
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC\FactoryMethod;

use stdClass;

/**
 * Factory Method Adapter
 *
 * Base - Instantiate - Adapter
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
abstract class Adapter
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
     * Base Path of Site
     *
     * @var    string
     * @since  1.0.0
     */
    protected $base_path = null;

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
            'base_path',
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
        if (count($options) > 0) {
            $this->setConstructorOptions($options);
        }
    }

    /**
     * Set Constructor Options
     *
     * @param   $options
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setConstructorOptions(array $options = array())
    {
        foreach ($this->factory_method_adapter_property_array as $property) {
            if (isset($options[$property])) {
                $this->$property = $options[$property];
                unset($options[$property]);
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
