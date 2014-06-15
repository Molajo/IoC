<?php
/**
 * Standard Factory Method
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC\FactoryMethod;

use CommonApi\IoC\FactoryInterface;
use CommonApi\IoC\FactoryBatchInterface;

/**
 * Standard Factory Method
 *
 * Executes for those requests which do not require a custom Factory Method.
 *
 * $options                                    = array();
 * $option['foo']                              = $bar;
 * $this->dependencies['Molajo//Foo//Bar']     = $options;
 *
 * 1. Request must be made for Product Namespace
 *      $option['product_name']                = 'Name';
 *      $option['product_namespace']           = 'Molajo\Product\Namespace';
 *
 * 2. One (only) of the following can be provided as an $option array entry (default false for all);
 *      $option['static_instance_indicator']   = true;
 *      $option['store_instance_indicator']    = true;
 *      $option['store_properties_indicator']  = true;
 *
 * 3. Other Constructor Dependencies can be provided in the $option array using the parameter name
 *      $option['parameter_name']              = $value;
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class Standard extends Base implements FactoryInterface, FactoryBatchInterface
{

}
