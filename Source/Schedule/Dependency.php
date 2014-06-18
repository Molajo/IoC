<?php
/**
 * Dependency
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC\Schedule;

use stdClass;

/**
 * Dependency
 *
 * Request - Dependency - Create - Base
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
abstract class Dependency extends Create
{
    /**
     * Process each outstanding dependency for product request
     *
     * @param   stdClass $work_object
     *
     * @return  stdClass $work_object
     * @since   1.0.0
     */
    protected function satisfyDependencies($work_object)
    {
        $satisfied = array();

        foreach ($work_object->dependencies as $key => $dependency_array) {

            if (isset($dependency_array['product_namespace'])) {
                $namespace = $dependency_array['product_namespace'];
            } else {
                $namespace = $key;
            }

            if (isset($dependency_array['if_exists'])) {
                $if_exists = true;
            } else {
                $if_exists = false;
            }

            $results = $this->satisfyDependency(
                $key,
                $key,
                $namespace,
                $if_exists,
                $work_object
            );

            if ($results === true) {
                $satisfied[] = $key;
            }
        }

        $this->satisfyDependenciesUnset($work_object, $satisfied);

        return $work_object;
    }

    /**
     * Unset dependencies
     *
     * @param   stdClass $work_object
     * @param   array    $satisfied
     *
     * @return  stdClass $work_object
     * @since   1.0.0
     */
    protected function satisfyDependenciesUnset($work_object, array $satisfied = array())
    {
        if (count($satisfied) > 0) {
            foreach ($satisfied as $key) {
                unset($work_object->dependencies[$key]);
            }
        }

        return $work_object;
    }

    /**
     * Update Service for Dependency Value
     *
     * @param   string   $dependency_name
     * @param   string   $dependency_key
     * @param   string   $dependency_namespace
     * @param   boolean  $if_exists
     * @param   stdClass $work_object
     *
     * @return  boolean
     * @since   1.0.0
     */
    protected function satisfyDependency(
        $dependency_name,
        $dependency_key,
        $dependency_namespace,
        $if_exists,
        $work_object
    ) {
        $return = true;

        if ($this->satisfyDependencyInContainer($dependency_name, $dependency_key, $work_object) === true) {
            return $return;
        }

        if ($this->satisfyDependencyIfExists($if_exists, $dependency_key, $work_object) === true) {
            return $return;
        }

        $return = false;

        if ($this->addDependencyToArray(
                $dependency_name,
                $dependency_key,
                $work_object->options['product_name'],
                'process_requests'
            ) === true
        ) {
            return $return;
        }

        if ($this->addDependencyToArray(
                $dependency_name,
                $dependency_key,
                $work_object->options['product_name'],
                'to_be_processed_requests'
            ) === true
        ) {
            return $return;
        }

        $this->addDependencyNew($dependency_name, $dependency_namespace, $work_object->options['product_name']);

        return $return;
    }

    /**
     * Dependency is in the Container
     *
     * @param   string   $dependency_name
     * @param   string   $dependency_key
     * @param   stdClass $work_object
     *
     * @return  boolean
     * @since   1.0.0
     */
    protected function satisfyDependencyInContainer(
        $dependency_name,
        $dependency_key,
        $work_object
    ) {
        /** Dependency Exists in Container */
        if ($this->hasContainerEntry($dependency_name) === true) {
            $dependency_value = $this->getContainerEntry($dependency_name);
            $work_object->factory_method->setDependencyValue($dependency_key, $dependency_value);
            return true;
        }

        return false;
    }

    /**
     * Dependency is only requested if it already exists
     *
     * @param   boolean  $if_exists
     * @param   string   $dependency_key
     * @param   stdClass $work_object
     *
     * @return  boolean
     * @since   1.0.0
     */
    protected function satisfyDependencyIfExists(
        $if_exists,
        $dependency_key,
        $work_object
    ) {
        if ($if_exists === true) {
            $work_object->factory_method->removeDependency($dependency_key);
            return true;
        }

        return false;
    }

    /**
     * See if Dependency has already been requested as a product
     *
     * @param   string $dependency_name
     * @param   string $dependency_namespace
     * @param   string $product_name
     * @param   string $array_name
     *
     * @return  boolean
     * @since   1.0.0
     */
    protected function addDependencyToArray($dependency_name, $dependency_namespace, $product_name, $array_name)
    {
        $array = $this->$array_name;

        if (isset($array[$dependency_name])) {
        } else {
            return false;
        }

        $dependency                      = $array[$dependency_name];
        $dependency['dependency_of'][]   = $product_name;
        $dependency['product_namespace'] = $dependency_namespace;

        $array[$dependency_name] = $dependency;

        $this->$array_name = $array;

        return true;
    }

    /**
     * See if Dependency has already been requested as a product
     *
     * @param   string $dependency_name
     * @param   string $dependency_namespace
     * @param   string $product_name
     *
     * @return  boolean
     * @since   1.0.0
     */
    protected function addDependencyNew($dependency_name, $dependency_namespace, $product_name)
    {
        $options                      = array();
        $options['dependency_of']     = array();
        $options['dependency_of'][]   = $product_name;
        $options['product_namespace'] = $dependency_namespace;

        $this->to_be_processed_requests[$dependency_name] = $options;

        return true;
    }

    /**
     * Set an entry in the process_requests array and a cross reference in $request_names_to_id
     *
     * @param   string   $product_name
     * @param   stdClass $work_object
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setProcessRequestsArray($product_name, $work_object)
    {
        $queue_id                                 = $work_object->options['ioc_id'];
        $this->process_requests[$queue_id]        = $work_object;
        $this->request_names_to_id[$product_name] = $queue_id;

        if (isset($this->to_be_processed_requests[$product_name])) {
            unset($this->to_be_processed_requests[$product_name]);
        }

        return $this;
    }
}
