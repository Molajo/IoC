<?php
/**
 * Create
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC\Product;

use CommonApi\Exception\RuntimeException;
use CommonApi\IoC\FactoryInterface;
use Molajo\IoC\FactoryMethod\Controller;

/**
 * Factory Method Create
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class Create
{
    /**
     * Options
     *
     * @var     array
     * @since  1.0.0
     */
    protected $options = array();

    /**
     * Constructor
     *
     * @param   array $options
     *
     * @since  1.0.0
     */
    public function __construct(
        array $options = array()
    ) {
        $this->options = $options;
    }

    /**
     * Get Factory Method Namespace
     *
     *
     * @return  FactoryInterface
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function instantiateFactoryMethod()
    {
        $adapter = $this->getAdapter($this->options);

        $controller = $this->getController($adapter);

        return $controller;
    }

    /**
     * Instantiate Factory Method to inject into the Adapter Constructor
     *
     * @param   array $options
     *
     * @return  FactoryInterface
     * @throws  \CommonApi\Exception\RuntimeException
     * @since   1.0.0
     */
    protected function getAdapter(array $options)
    {
        $class = $options['factory_method_namespace'];

        if (class_exists($class)) {
        } else {
            throw new RuntimeException(
                'IoC Create::getAdapter Class does not exist: ' . $class
            );
        }

        $factory_method_adapter = new $class($options);

        return $factory_method_adapter;
    }

    /**
     * Instantiate DI Adapter, injecting it with the Handler instance
     *
     * @param   FactoryInterface $factory_method_adapter
     *
     * @return  FactoryInterface
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function getController(FactoryInterface $factory_method_adapter)
    {
        return new Controller($factory_method_adapter);
    }
}
