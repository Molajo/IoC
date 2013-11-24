<?php
/**
 * Standard Dependency Injector
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC\Handler;

use CommonApi\IoC\ServiceHandlerInterface;

/**
 * Standard Dependency Injector
 *
 * Executes for those requests which do not require a custom adapter.
 *
 * $options = array();
 * $option['foo'] = $bar;
 * $this->dependencies['Molajo//Foo//Bar'] = $options;
 *
 * 1. Request must be made for Service Namespace (ex. Molajo//Foo//Bar)
 *      or the $option['service_namespace'] must be provided
 *
 * 2. One (only) of the following can be provided as an $option array entry (default false for all);
 *      $option['static_instance_indicator'] = true;
 *      $option['store_instance_indicator'] = true;
 *      $option['store_properties_indicator'] = true;
 *
 * 3. Other Constructor Dependencies can be provided in the $option array
 *      $option['fieldhandler'] = $fieldhandler;
 *      $option['parameter_name'] = $x;
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class StandardInjector extends AbstractInjector implements ServiceHandlerInterface
{
    /**
     * Constructor
     *
     * @param  array $options
     *
     * @since  1.0
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);
    }
}
