<?php
/**
 * Service Provider Controller Interface
 *
 * @package    IoC
 * @copyright  2013 Common Api. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\IoC\Api;

/**
 * Service Provider Controller Interface
 *
 * @package    IoC
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Common Api. All rights reserved.
 * @since      0.1
 */
interface ServiceProviderControllerInterface
{
    /**
     * Set service alias
     *
     * @param   string $service_name
     * @param   string $service_namespace
     *
     * @return  $this
     * @since   0.1
     */
    public function setServiceAlias($service_name, $service_namespace);

    /**
     * Process a Set of Service Requests
     *
     * @param   array $batch_services (array [$service_name] => $options)
     *
     * @return  array (array ['service_name'] => $service_instance)
     * @since   0.1
     */
    public function getServices(array $batch_services = array());

    /**
     * Create a Class Instance (Service) and its dependencies (and those services and their dependencies, etc.)
     *
     * @param   string $service_name
     * @param   array  $options
     *
     * @return  object
     * @since   0.1
     */
    public function getService($service_name, array $options = array());

    /**
     * Store Instance in the Inversion of Control Container
     *
     * @param   string $container_key (Handler Namespace unless it doesn't exist in which case Service Namespace)
     * @param   object $instance
     * @param   string $service_name
     *
     * @return  $this
     * @since   0.1
     */
    public function setService($container_key, $instance, $service_name = null);

    /**
     * Clone Instance in the Inversion of Control Container
     *
     * @param   string $container_key
     *
     * @return  null|object
     * @since   0.1
     */
    public function cloneService($container_key);

    /**
     * Remove Instance in the Inversion of Control Container
     *
     * @param   string $container_key
     *
     * @return  $this
     * @since   0.1
     */
    public function removeService($container_key);
}
