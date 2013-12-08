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
        $this->service_namespace        = 'Molajo\\CacheMock';
        $this->store_instance_indicator = true;

        parent::__construct($options);
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
        $getService    = $this->getService;
        $configuration = $getService('ConfigurationMock');

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
        $options['configuration'] = $configuration;

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
