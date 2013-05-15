<?php
/**
 * Cache Dependency Injector
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Services\Cache;

use Exception;
use Molajo\IoC\Handler\CustomInjector;
use Molajo\IoC\Api\InjectorInterface;
use Molajo\IoC\Exception\InjectorException;

/**
 * Cache Dependency Injector
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class CacheInjector extends CustomInjector implements InjectorInterface
{
    /**
     * Constructor
     *
     * @since   1.0
     */
    public function __construct()
    {
        $this->service_namespace        = 'Molajo\\Services\\Cache';
        $this->store_instance_indicator = true;
    }

    /**
     * Instantiate Class
     *
     * @param bool $create_static
     *
     * @return  object
     * @since   1.0
     * @throws  InjectorException
     */
    public function instantiate($create_static = false)
    {
        $application = $this->frontcontroller_instance->getService('Configuration');

        $options                  = array();
        if (isset($options['foo'])) {
            $options['foo'] = $this->options['foo'];
        }
        if (isset($options['bar'])) {
            $options['bar'] = $this->options['bar'];
        }
        if (isset($options['baz'])) {
            $options['baz'] = $this->options['baz'];
        }

        try {
            $class = $this->service_namespace;
            $this->service_instance = $class($options);

        } catch (Exception $e) {

            throw new InjectorException
            ('IoC: Injector Instance Failed for ' . $this->service_namespace
                . ' failed.' . $e->getMessage());
        }

        return $this;
    }
}
