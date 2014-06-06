<?php
/**
 * Dispatcher
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\Event;

use CommonApi\Event\DispatcherInterface;
use CommonApi\Event\EventDispatcherInterface;
use CommonApi\Event\EventInterface;

/**
 * Dispatcher
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class Dispatcher implements DispatcherInterface
{
    /**
     * Event Dispatcher
     *
     * @var    object  CommonApi\Event\EventDispatcherInterface
     * @since  1.0
     */
    protected $event_dispatcher = null;

    /**
     * Registered Listeners by Callback
     *
     * @var    array
     * @since  1.0
     */
    protected $callback_events = array();

    /**
     * Class Constructor
     *
     * @param  EventDispatcherInterface $event_dispatcher
     *
     * @since  1.0
     */
    public function __construct(
        EventDispatcherInterface $event_dispatcher
    ) {
        $this->event_dispatcher = $event_dispatcher;
    }

    /**
     * Requester Schedules Event with Dispatcher
     *
     * @param   string         $event_name
     * @param   EventInterface $event
     *
     * @return  $this
     * @since   1.0
     */
    public function scheduleEvent($event_name, EventInterface $event)
    {
        $listeners = array();

        if (isset($this->callback_events[$event_name])) {
            $listeners = $this->sortEventListenersByPriority($event_name);
        }

        return $this->event_dispatcher->triggerListeners($event, $listeners);
    }

    /**
     * Sort Listeners by Priority
     *
     * @param   string $event_name
     *
     * @return  $this
     * @since   1.0
     */
    public function sortEventListenersByPriority($event_name)
    {
        $priorities = $this->callback_events[$event_name];
        krsort($priorities);

        $listeners = array();
        foreach ($priorities as $priority => $callbacks) {
            foreach ($callbacks as $callback) {
                $listeners[] = $callback;
            }
        }

        return $listeners;
    }

    /**
     * Listener registers for an Event with the Dispatcher
     *
     * @param   string   $event_name
     * @param   callable $callback
     * @param   int      $priority  1 is highest
     *
     * @return  $this
     * @since   1.0
     */
    public function registerForEvent($event_name, $callback, $priority = 50)
    {
        if (isset($this->callback_events[$event_name])) {
            $priorities = $this->callback_events[$event_name];
        } else {
            $priorities = array();
        }

        if (isset($priorities[$priority])) {
            $callback_array = $priorities[$priority];
        } else {
            $callback_array = array();
        }

        $callback_array[]                   = $callback;
        $priorities[]                       = $callback_array;
        $this->callback_events[$event_name] = $priorities;

        return $this;
    }
}
