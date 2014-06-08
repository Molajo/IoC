<?php
/**
 * Factory Method Base
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC;

use CommonApi\IoC\FactoryInterface;
use CommonApi\IoC\FactoryBatchInterface;

/**
 * Factory Method Base
 *
 * FactoryMethodAdapter - FactoryMethodInstantiate - FactoryMethodBase
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
abstract class FactoryMethodBase extends FactoryMethodInstantiate implements FactoryInterface, FactoryBatchInterface
{
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
     * @return  FactoryMethodBase
     * @since   1.0.0
     */
    protected function setDependencyUsingReflection($dependency)
    {
        if ($dependency->instance_of === null) {
            return $this;
        }

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
     * @param   string $dependency_name
     * @param   object $dependency
     *
     * @return  FactoryMethodBase
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
        if ($dependency_values === null || count($dependency_values) === 0) {
            return $this->dependencies;
        }

        $this->onBeforeInstantiationDependencyValues($dependency_values);

        $this->onBeforeInstantiationReflectionLoop();

        return $this->dependencies;
    }

    /**
     * Before class instantiation, set gathered dependency values for dependencies
     *
     * @param   array $dependency_values
     *
     * @return  array
     * @since   1.0.0
     */
    protected function onBeforeInstantiationDependencyValues(array $dependency_values)
    {
        foreach ($dependency_values as $key => $value) {
            $this->dependencies[strtolower($key)] = $value;
            unset($dependency_values[$key]);
        }

        return $dependency_values;
    }

    /**
     * Before class instantiation, process reflection array
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function onBeforeInstantiationReflectionLoop()
    {
        if (count($this->reflection) > 0) {
            $reflection = $this->reflection;
            foreach ($reflection as $dependency) {
                $this->onBeforeInstantiationReflection($dependency);
            }
        }

        return $this;
    }

    /**
     * Before class instantiation, process dependency
     *
     * @param   object $dependency
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function onBeforeInstantiationReflection($dependency)
    {
        $found = $this->onBeforeInstantiationVerifyDependency($dependency);

        if ($found === false) {
            $found = $this->onBeforeInstantiationVerifyOptions($dependency);
        }

        if ($found === false) {
            $this->dependencies[$dependency->name] = $dependency->default_value;
        }

        return $this;
    }

    /**
     * For specified dependency, determine if a dependency value exists
     *
     * @param   object $dependency
     *
     * @return  boolean
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
     * @return  boolean
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
}
