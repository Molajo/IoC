<?php
/**
 * Factory Method Namespace
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC;

/**
 * Get the Factory Method Namespace for Product
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class FactoryMethodNamespace
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
        $this->standard_adapter_namespace = $standard_adapter_namespace;
        $this->options                    = $options;
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
     * Build a possible Namespace path and test it for class
     *
     *
     * @return  boolean
     * @since   1.0.0
     */
    protected function getFactoryNamespaceFolderFile($option_entry)
    {
        if (isset($this->options[$option_entry])) {
        } else {
            return false;
        }

        $test    = array();
        $test[0] = $this->options[$option_entry];
        $test[1] = $this->getFolderFile($test[0]);

        foreach ($test as $value) {
            if ($this->checkClassExists($value) === true) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get Folder File Namespace
     *
     * @param   string $path
     *
     * @return  string
     * @since   1.0.0
     */
    protected function getFolderFile($path)
    {
        $folder    = $this->getLastFolder($path);
        $separator = '\\';
        return $path . $separator . $folder . 'FactoryMethod';
    }

    /**
     * Check class exists
     *
     * @param   string $value
     *
     * @return  boolean
     * @since   1.0.0
     */
    protected function checkClassExists($value)
    {
        if (class_exists($value)) {
            $this->options['factory_method_namespace'] = $value;
            return true;
        }

        return false;
    }

    /**
     * Retrieve the last folder in string
     *
     * @param   string $value
     *
     * @return  string|false
     * @since   1.0.0
     */
    protected function getLastFolder($value)
    {
        if (strrpos($value, '\\')) {
            return substr($value, strrpos($value, '\\') + 1, 999);
        }

        return false;
    }
}
