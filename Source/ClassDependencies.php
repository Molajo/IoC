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
     * Class Dependencies by QCN
     *
     * @var    array
     * @since  1.0.0
     */
    protected $class_dependencies;

    /**
     * Options
     *
     * @var    array
     * @since  1.0.0
     */
    protected $options = array();

    /**
     * Constructor
     *
     *
     * @since  1.0.0
     * @param string $class_dependencies_filename
     */
    public function __construct($class_dependencies_filename = null)
    {
        $this->loadClassDependencies($class_dependencies_filename);
    }

    /**
     * Set Factory Method Reflection Values and retrieve Dependencies
     *
     * @param   object
     *
     * @return  object
     * @since   1.0.0
     */
    public function get($work_object)
    {
        $work_object->product_namespace = $work_object->factory_method->getNamespace();
        $work_object->reflection        = $this->getReflectionDependencies($work_object->product_namespace);
        $work_object->dependencies      = $work_object->factory_method->setDependencies($work_object->reflection);

        return $work_object;
    }

    /**
     * Reflection Dependencies for Namespace
     *
     * @param   string $namespace
     *
     * @return  array
     * @since   1.0.0
     */
    protected function getReflectionDependencies($namespace)
    {
        $reflection = array();

        if (isset($this->class_dependencies[$namespace])) {
            $reflection = $this->class_dependencies[$namespace];
        } else {
            //todo - automate reflection
        }

        return $reflection;
    }

    /**
     * Load Class Dependencies derived using Reflection into Class Property
     *
     * @param   string $filename
     *
     * @since   1.0.0
     * @return  $this
     */
    protected function loadClassDependencies($filename = null)
    {
        $input = $this->readFile($filename);

        if ($input === false) {
        } else {
            $this->processClassDependencyData($input);
        }

        return $this;
    }

    /**
     * Read json encoded file
     *
     * @param  string $filename
     *
     * @since   1.0.0
     * @return  array
     */
    protected function readFile($filename = null)
    {
        if (file_exists($filename)) {
        } else {
            return false;
        }

        $x = file_get_contents($filename);

        return json_decode($x);
    }

    /**
     * Read json encoded file
     *
     * @param   object
     *
     * @since   1.0.0
     * @return  $this
     */
    protected function processClassDependencyData($input)
    {
        foreach ($input as $class) {
            if (isset($class->constructor_parameters)) {
                $this->class_dependencies[$class->fqns] = $class->constructor_parameters;
            }
        }

        return $this;
    }
}
