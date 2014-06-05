<?php
/**
 * Class Dependencies
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC;

/**
 * Get the dependencies for a class using Dependencies processes
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class ClassDependencies
{
    /**
     * Standard IoC Factory Method (Used when no custom Factory Method is required)
     *
     * @var    string
     * @since  1.0.0
     */
    protected $standard_adapter_namespace;

    /**
     * Options
     *
     * @var    array
     * @since  1.0.0
     */
    protected $options = array();

    /**
     * Methods
     *
     * @var    array
     * @since  1.0.0
     */
    protected $option_entry
        = array(
            'factory_method_namespace',
            'product_name',
            'container_key'
        );

    /**
     * Constructor
     *
     * @param  string $standard_adapter_namespace
     * @param  array  $options
     *
     * @since  1.0.0
     */
    public function __construct(
        $standard_adapter_namespace,
        array $options = array()
    ) {
        $this->standard_adapter_namespaces = $standard_adapter_namespace;
        $this->options                     = $options;
    }

    /**
     * Determine the Factory Method Namespace for Product Requested
     *
     * @return  array
     * @since   1.0.0
     */
    public function get()
    {
        $results = false;

        foreach ($this->option_entry as $option_entry) {
            $results = $this->getFactoryNamespaceFolderFile($option_entry);
            if ($results === true) {
                break;
            }
        }

        if ($results === false) {
            $this->options['factory_method_namespace'] = $this->standard_adapter_namespace;
        }

        return $this->options;
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
