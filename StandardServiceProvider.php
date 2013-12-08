<?php
/**
 * Standard Service Provider
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC;

use CommonApi\IoC\ServiceProviderInterface;

/**
 * Standard Service Provider
 *
 * Executes for those requests which do not require a custom Service Provider.
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
class StandardServiceProvider extends AbstractServiceProvider implements ServiceProviderInterface
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
