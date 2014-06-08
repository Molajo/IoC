<?php
/**
 * Schedule for processing Factory Method Requests and Container Entries
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC;

use CommonApi\IoC\ScheduleInterface;
use Molajo\IoC\Schedule\Request;

/**
 * Schedule for processing Factory Method Requests and Container Entries
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class Schedule extends Request implements ScheduleInterface
{
    /**
     * Schedule Factory Method for Requested Product
     *
     * @param   string $product_name
     * @param   array  $options
     *
     * @return  $this
     * @since   1.0.0
     */
    public function scheduleFactoryMethod($product_name = null, array $options = array())
    {
        if ($this->hasContainerEntry($product_name) === true) {
            return $this->getContainerEntry($product_name);
        }

        if (isset($options['if_exists'])) {
            return false;
        }

        $this->queue_id = 1;
        $work_object    = $this->setProductRequest($product_name, $options);

        $this->setProcessRequestsArray($product_name, $work_object);

        $this->processRequestQueue();

        return $this->product_result;
    }

    /**
     * Schedule Product Factory Method
     *
     * Handles requests for FactoryMethod product, including dependency fulfillment
     *
     * @return  $this
     * @since   1.0.0
     */
    public function processRequestQueue()
    {
        $count = 1;
        while (count($this->process_requests) > 0) {

            $this->processRequests();

            if (count($this->to_be_processed_requests) > 0) {
                $count = $this->processNewRequestQueue($count);
            }
        }

        return $this;
    }

    /**
     * Process each product request to satisfy dependencies and, when all dependencies
     *  have been met, to complete the Factory Method processes including creating the product
     *
     * @return  $this
     * @since   1.0.0
     */
    public function processRequests()
    {
        foreach ($this->process_requests as $id => $work_object) {

            if ($work_object->factory_method->getRemainingDependencyCount() === 0) {
                $this->processFactoryModel($work_object);
            }
        }

        return $this;
    }

    /**
     * Process each product request to satisfy dependencies and, when all dependencies
     *  have been met, to complete the Factory Method processes including creating the product
     *
     * @param   integer $count
     *
     * @return  integer
     * @since   1.0.0
     */
    public function processNewRequestQueue($count)
    {
        foreach ($this->to_be_processed_requests as $product_name => $options) {
            $work_object = $this->setProductRequest($product_name, $options);
            $this->setProcessRequestsArray($product_name, $work_object);
        }

        $count++;
        if ($count > 500) {
            var_dump($this->process_requests);
            throw new \Exception('processRequestQueue endless loop');
        }

        return $count;
    }
}
