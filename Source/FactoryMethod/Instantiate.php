<?php
/**
 * Factory Method Instantiate
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC\FactoryMethod;

use CommonApi\Exception\RuntimeException;
use Exception;
use ReflectionClass;

/**
 * Factory Method Instantiate
 *
 * Base - Instantiate - Adapter
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
abstract class Instantiate extends Adapter
{
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

        return $this->instantiateClassNotStaticTryCatch($dependencies);
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
     * @return  Base
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function instantiateClassNotStaticTryCatch(array $dependencies = array())
    {
        try {
            return $this->instantiateClassNotStatic($dependencies);

        } catch (Exception $e) {

            throw new RuntimeException(
                'IoC instantiateClass with dependencies Failed: '
                . $this->product_namespace . ' ' . $e->getMessage()
            );
        }
    }

    /**
     * Instantiate Class with dependencies
     *
     * @param   array $dependencies
     *
     * @return  Base
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function instantiateClassNotStatic(array $dependencies = array())
    {
        if (method_exists($this->product_namespace, '__construct') && count($dependencies) > 0) {
            $reflection           = new ReflectionClass($this->product_namespace);

            $this->product_result = $reflection->newInstanceArgs($dependencies);

        } else {
            $class                = $this->product_namespace;
            $this->product_result = new $class();
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
}
