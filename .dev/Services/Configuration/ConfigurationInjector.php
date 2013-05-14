<?php
/**
 * Configuration Dependency Injector
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Services\Configuration;

use Exception;
use Molajo\IoC\Handler\CustomInjector;
use Molajo\IoC\Api\InjectorInterface;
use Molajo\IoC\Exception\InjectorException;

/**
 * Configuration Service Dependency Injector
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class ConfigurationInjector extends CustomInjector implements InjectorInterface
{
    /**
     * Constructor
     *
     * @since   1.0
     */
    public function __construct()
    {
        $this->service_namespace        = 'Molajo\\Application\\Configuration\\Adapter';
        $this->store_instance_indicator = true;
    }

    /**
     * Instantiate Class
     *
     * @param   bool $create_static
     *
     * @return  object
     * @since   1.0
     * @throws  InjectorException
     */
    public function instantiate($create_static = false)
    {
        $options              = array();
        $options['if_exists'] = true;
        $cache_instance       = $this->container_instance->getService('Cache', $options);

        $registry_instance = $this->container_instance->getService('Registry');

        $options['if_exists'] = true;
        $profiler_instance    = $this->container_instance->getService('Profiler', $options);

        $dataobject_instance = $this->container_instance->getService('ConfigurationData');

        $options                             = array();
        $options['cache_instance']           = $cache_instance;
        $options['registry_instance']        = $registry_instance;
        $options['profiler_instance']        = $profiler_instance;
        $options['container_instance'] = $this->container_instance;
        $options['dataobject_instance']      = $dataobject_instance;

        try {
            $handler_class    = 'Molajo\\Application\\Configuration\\Handler\\Xml';
            $handler_instance = new $handler_class($options);

        } catch (Exception $e) {

            throw new InjectorException
            ('IoC: Injector Instance Failed for ' . $this->service_namespace
                . ' failed.' . $e->getMessage());
        }

        try {
            $service_namespace      = $this->service_namespace;
            $this->service_instance = new $service_namespace($handler_instance);

        } catch (Exception $e) {

            throw new InjectorException
            ('IoC: Injector Instance Failed for ' . $this->service_namespace
                . ' failed.' . $e->getMessage());
        }

        return $this;
    }
}
