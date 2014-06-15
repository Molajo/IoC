<?php
/**
 * Create Product
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC\Schedule;

use Molajo\IoC\Schedule;
use stdClass;

/**
 * Create
 *
 * Request - Dependency - Create - Base
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class Create extends Base
{
    /**
     * Instantiate Class now that dependencies have been satisfied and finish processing
     *
     * @param   stdClass $work_object
     *
     * @return  Schedule
     * @since   1.0.0
     */
    protected function processFactoryModel($work_object)
    {
        $methods = array(
            'processFactoryModelProductCreate',
            'processFactoryModelRemoveContainerEntries',
            'processFactoryModelSetContainerEntries',
            'processFactoryModelScheduleRequests',
            'processFactoryModelSetDependencyOfInstances'
        );

        foreach ($methods as $method) {
            $work_object = $this->$method($work_object);
        }

        $this->unsetProcessRequestsArray($work_object->options['product_name']);

        $this->product_result = $work_object->product_result;

        return $this;
    }

    /**
     * Set an entry in the process_requests array and a cross reference in $request_names_to_id
     *
     * @param   string $product_name
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function unsetProcessRequestsArray($product_name)
    {
        $queue_id = $this->request_names_to_id[$product_name];

        unset($this->process_requests[$queue_id]);
        unset($this->request_names_to_id[$product_name]);

        return $this;
    }

    /**
     * Instantiate Class
     *
     * @param   string $work_object
     *
     * @return  string
     * @since   1.0.0
     */
    protected function processFactoryModelProductCreate($work_object)
    {
        $work_object->factory_method->onBeforeInstantiation();
        $work_object->factory_method->instantiateClass();
        $work_object->factory_method->onAfterInstantiation();
        $product_result              = $work_object->factory_method->getProductValue();
        $work_object->product_result = $product_result;

        if ($work_object->factory_method->getStoreContainerEntryIndicator() === true) {
            $this->container->set($work_object->options['container_key'], $work_object->product_result);
        }

        return $work_object;
    }

    /**
     * Remove Container Entries, as requested by Factory Model
     *
     * @param   string $work_object
     *
     * @return  string
     * @since   1.0.0
     */
    protected function processFactoryModelRemoveContainerEntries($work_object)
    {
        if (count($work_object->factory_method->removeContainerEntries()) > 0) {
            $this->processFactoryModelArray($work_object->factory_method->removeContainerEntries(), 'remove');
        }

        return $work_object;
    }

    /**
     * Set Container Entries, as requested by Factory Model
     *
     * @param   string $work_object
     *
     * @return  string
     * @since   1.0.0
     */
    protected function processFactoryModelSetContainerEntries($work_object)
    {
        if (count($work_object->factory_method->setContainerEntries()) > 0) {
            $this->processFactoryModelArray($work_object->factory_method->setContainerEntries(), 'set');
        }

        return $work_object;
    }

    /**
     * Set Container Entries, as requested by Factory Model
     *
     * @param   array  $array
     * @param   string $method
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function processFactoryModelArray($array, $method)
    {
        foreach ($array as $product_name => $value) {

            if ($method === 'set') {
                $this->container->set($product_name, $value);
            } else {
                if ($this->container->has($product_name) === true) {
                    $this->container->remove($product_name);
                }
            }
        }

        return $this;
    }

    /**
     * Schedule additional Product requests, as specified by Factory Model
     *
     * @param   string $work_object
     *
     * @return  string
     * @since   1.0.0
     */
    protected function processFactoryModelScheduleRequests($work_object)
    {
        $schedule = $work_object->factory_method->scheduleFactories();

        if (is_array($schedule) && count($schedule) > 0) {
            foreach ($schedule as $product_name => $options) {
                $this->to_be_processed_requests[$product_name] = $options;
            }
        }

        return $work_object;
    }

    /**
     * Update Service for Dependency Value
     *
     * @param   stdClass $work_object
     *
     * @return  stdClass
     * @since   1.0.0
     */
    protected function processFactoryModelSetDependencyOfInstances($work_object)
    {
        if (count($work_object->dependency_of) === 0) {
            return $work_object;
        }

        foreach ($work_object->dependency_of as $dependency_key) {
            $queue_id          = $this->request_names_to_id[$dependency_key];
            $dependency_object = $this->process_requests[$queue_id];

            $dependency_object->factory_method->setDependencyValue(
                $work_object->options['product_name'],
                $work_object->product_result
            );
        }

        return $work_object;
    }
}
