<?php
/**
 * Standard Dependency Injector
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC\Handler;

use Exception;
use ReflectionClass;
use ReflectionParameter;
use Molajo\IoC\Exception\InjectorException;
use Molajo\IoC\Handler\AbstractInjector;
use Molajo\IoC\Api\InjectorInterface;

/**
 * Standard Dependency Injector
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class StandardInjector extends AbstractInjector implements InjectorInterface
{
    /**
     * Constructor
     *
     * @param   $options
     *
     * @since   1.0
     */
    public function __construct($options)
    {
        $this->service_namespace        = $options['service'];
        $this->store_instance_indicator = true;

        parent::__construct($options);
    }

    /**
     * Instantiate Class
     *
     * @param   bool  $create_static
     *
     * @return  object
     * @since   1.0
     * @throws  InjectorException
     */
    public function instantiate($create_static = false)
    {
        $options = array();
        $reflect = new ReflectionClass($this->service_namespace);
        $parameters = $reflect->getMethod('__construct')->getParameters();

        if (count($parameters) === 0) {
        } else {
            foreach ($parameters as $parameter) {
                if (isset($this->options[$parameter->name])) {
                    $options[$parameter->name] = $this->options[$parameter->name];
                }
            }
        }

        try {
            $this->service_instance = $reflect->newInstanceArgs($options);

        } catch (Exception $e) {

            throw new InjectorException
            ('IoC: Injector Instance Failed for ' . $this->service_namespace
                . ' failed.' . $e->getMessage());
        }

        return $this;
    }
}
