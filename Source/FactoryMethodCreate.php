<?php
/**
 * Factory Method Create
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC;

use CommonApi\Exception\RuntimeException;
use CommonApi\IoC\FactoryInterface;

/**
 * Factory Method Create
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class FactoryMethodCreate
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
        $adapter = $this->getFactoryMethodAdapter($this->options);

        $controller = $this->getFactoryMethodController($adapter);

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
    protected function getFactoryMethodAdapter(array $options)
    {
        $class = $options['factory_method_namespace'];

        if (class_exists($class)) {
        } else {
            throw new RuntimeException(
                'IoC FactoryMethodCreate::getFactoryMethodAdapter Class does not exist: ' . $class
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
    protected function getFactoryMethodController(FactoryInterface $factory_method_adapter)
    {
        return new FactoryMethodController($factory_method_adapter);
    }
}
