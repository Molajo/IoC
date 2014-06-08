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

            $results = $this->satisfyDependency(
                $dependency_array['product_name'],
                $key,
                $dependency_array['product_namespace'],
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
     * @param   stdClass $work_object
     *
     * @return  boolean
     * @since   1.0.0
     */
    protected function satisfyDependency($dependency_name, $dependency_key, $dependency_namespace, $work_object)
    {
        if ($this->hasContainerEntry($dependency_name) === false) {
        } else {
            $dependency_value = $this->getContainerEntry($dependency_name);
            $work_object->factory_method->setDependencyValue($dependency_key, $dependency_value);

            return true;
        }

        $this->addDependencyToQueue($dependency_name, $dependency_namespace, $work_object->options['product_name']);

        return false;
    }

    /**
     * Add Dependency to Queue
     *
     * @param   string $dependency_name
     * @param   string $product_name
     * @param   string $dependency_namespace
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function addDependencyToQueue($dependency_name, $dependency_namespace, $product_name)
    {
        if (isset($this->request_names_to_id[$dependency_name])) {
        } else {
            $this->to_be_processed_requests[$dependency_name]
                = array(
                'dependency_of'     => $product_name,
                'product_namespace' => $dependency_namespace
            );
        }

        return $this;
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
