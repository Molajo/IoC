<?php
/**
 * Event Dispatcher
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\Event;

use CommonApi\Event\EventInterface;
use CommonApi\Event\EventDispatcherInterface;

/**
 * Event Dispatcher
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class EventDispatcher implements EventDispatcherInterface
{
    /**
     * Event Dispatcher
     *
     * @var    string
     * @since  1.0
     */
    protected $dummy_property = 'value';

    /**
     * Event Dispatcher triggers Listeners
     *
     * @param   EventInterface $event
     * @param   array          $listeners - array of callable functions
     *
     * @return  array
     * @since   1.0
     */
    public function triggerListeners(EventInterface $event, array $listeners = array())
    {
        $return_items = $event->get('return_items');
        $data         = $event->get('data');

        if (count($listeners) > 0) {
            foreach ($listeners as $listener) {
                $data = $listener($event->get('event_name'), $data);
            }
        }

        $new = array();
        foreach ($return_items as $key) {
            $new[$key] = $data[$key];
        }

        return $new;
    }
}
