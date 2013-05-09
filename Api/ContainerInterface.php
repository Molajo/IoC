<?php
/**
 * Inversion of Control Container
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\IoC\Api;

use Molajo\IoC\Exception\ContainerException;

/**
 * Inversion of Control Container
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface ContainerInterface
{
    /**
     * Handle requests for Services either by instantiating an instance of the Service
     *  and injecting its dependencies, or by returning a shared instance already available,
     *  or by not returning an instance that is not yet available.
     *
     * @param    string $service_name
     * @param    array  $options
     *
     * @results  null|object
     * @since    1.0
     * @throws   ContainerException
     */
    public function getService($service_name, $options = array());
}
