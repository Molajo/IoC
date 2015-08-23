<?php
/**
 * Schedule for processing Factory Method Requests and Container Entries
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC;

use CommonApi\IoC\ScheduleInterface;
use Exception;
use Molajo\IoC\Schedule\Request;
use stdClass;

/**
 * Schedule for processing Factory Method Requests and Container Entries
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class Schedule extends Request implements ScheduleInterface
{
    /**
     * Counter
     *
     * @var    integer
     * @since  1.0
     */
    protected $count = 0;

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
        $this->checkRequest($product_name);

        if (isset($options['set']) && $options['set'] === true) {
            $this->container->set($product_name, $options['value']);

            return $this->getContainerEntry($product_name);
        }

        if ($this->hasContainerEntry($product_name) === true) {
            return $this->getContainerEntry($product_name);
        }

        if (isset($options['if_exists'])) {
            return false;
        }

        $this->queue_id++;

        $work_object = $this->setProductRequest($product_name, $options);

        $this->setProcessRequestsArray($product_name, $work_object);

        $this->processRequestQueue();

        return $this->product_result;
    }

    /**
     * Process the Request Queue Iteratively until all process_request entries are complete
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function processRequestQueue()
    {
        while (count($this->process_requests) > 0) {

            $this->processRequests();

            if (count($this->to_be_queued_requests) > 0) {
                $this->processNewRequestQueue();
            }

            $this->checkMaximumIterations();
        }

        return $this;
    }

    /**
     * Process each product request:
     *  1) Verify if dependencies have been set
     *  2) If so, remove from process_requests array and instantiate class/complete processing
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function processRequests()
    {
        foreach ($this->process_requests as $id => $work_object) {

            $count = $work_object->factory_method->getRemainingDependencyCount();

            if ($count === 0) {
                $this->unsetProcessRequestsArray($id);
                $this->processFactoryModel($work_object);
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
    protected function processNewRequestQueue()
    {
        foreach ($this->to_be_queued_requests as $product_name => $options) {

            $work_object = $this->setProductRequest($product_name, $options);

            $this->setProcessRequestsArray($product_name, $work_object);
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

        if (isset($this->to_be_queued_requests[$product_name])) {
            unset($this->to_be_queued_requests[$product_name]);
        }

        return $this;
    }

    /**
     * Remove process_requests entry and $request_names_to_id cross reference
     *
     * @param   integer $queue_id
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function unsetProcessRequestsArray($queue_id)
    {
        $product_name = $this->process_requests[$queue_id]->options['product_name'];

        unset($this->process_requests[$queue_id]);
        unset($this->request_names_to_id[$product_name]);

        return $this;
    }

    /**
     * Verifies the maximum count has not been exceeded to guard against schedule looping
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function checkMaximumIterations()
    {
        $this->count++;

        if ($this->count > 50000) {
            echo 'In IoCC Schedule -- Count > 50000';
            $this->displayQueue();
            throw new Exception('IOC Schedule checkMaximumIterations Endless Loop');
        }

        return $this;
    }

    /**
     * Guards against looping (Request for itself as a dependency)
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function checkRequest($product_name)
    {
        foreach ($this->process_requests as $requests) {
            if ($requests->options['product_name'] === $product_name) {
                $this->displayQueue();
                throw new Exception('IOC Schedule checkRequest Scheduled Product Name Twice: ' . $product_name);
            }
        }

        return $this;
    }

    /**
     * Display items in Queue
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function displayQueue()
    {
        echo 'Count of Requests In Queue Awaiting Dependencies: ' . count($this->process_requests) . '<br>';

        foreach ($this->process_requests as $request) {
            echo 'Request Key: ' . $request->options['product_name'] . '<br>';
            $queue_id    = $request->options['ioc_id'];
            $work_object = $this->process_requests[$queue_id];
            foreach ($work_object->dependencies as $dependency_key => $dependency_value) {
                echo '...Dependency Key: ' . $dependency_key . '<br>';
            }
        }

        echo 'Count of Requests To Be Queued: ' . count($this->to_be_queued_requests) . '<br>';

        foreach ($this->to_be_queued_requests as $request) {
            echo 'To Be Queued Request Key: ' . $request->options['product_name'] . '<br>';
        }

        return $this;
    }
}
