<?php
/**
 * Cache Service Provider
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Service\CacheMock;

use Exception;
use Molajo\IoC\AbstractServiceProvider;
use CommonApi\IoC\ServiceProviderInterface;
use CommonApi\Exception\RuntimeException;

/**
 * Cache Service Provider
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class CacheMockServiceProvider extends AbstractServiceProvider implements ServiceProviderInterface
{
    /**
     * Constructor
     *
     * @since   1.0
     */
    public function __construct(array $options = array())
    {
        $options['service_name']             = basename(__DIR__);
        $options['store_instance_indicator'] = true;
        $options['service_namespace']        = 'Molajo\\CacheMock';

        parent::__construct($options);
    }

    /**
     * Instantiate a new handler and inject it into the Adapter for the ServiceProviderInterface
     * Retrieve a list of Interface dependencies and return the data ot the controller.
     *
     * @return  array
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException;
     */
    public function setDependencies(array $reflection = null)
    {
        parent::setDependencies($reflection);

        $this->dependencies['ConfigurationMock']  = array();

        return $this->dependencies;
    }

    /**
     * Instantiate Class
     *
     * @param bool $create_static
     *
     * @return  object
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException;
     */
    public function instantiateService()
    {
        $options = array();
        if (isset($this->options['foo'])) {
            $options['foo'] = $this->options['foo'];
        }
        if (isset($this->options['bar'])) {
            $options['bar'] = $this->options['bar'];
        }
        if (isset($this->options['baz'])) {
            $options['baz'] = $this->options['baz'];
        }
        $options['configuration'] = $this->dependencies['ConfigurationMock'];

        try {
            $class                  = $this->service_namespace;
            $this->service_instance = new $class($options);

        } catch (Exception $e) {

            throw new RuntimeException
            ('IoC Service Provider Instance Failed for ' . $this->service_namespace
            . ' failed.' . $e->getMessage());
        }

        return $this;
    }
}
