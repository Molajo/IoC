<?php
/**
 * Standard Dependency Injector
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC\Handler;

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
     * @since   1.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Instantiate Class
     *
     * @param  bool  $create_static
     *
     * @return  object
     * @since   1.0
     * @throws  InjectorException
     */
    public function instantiate($create_static = false)
    {
        $deps = array();
        $controller = new \ReflectionClass($controllerName);
        $params = $class->getMethod('__construct')->getParameters();
        foreach($params as $p) {

            $export = ReflectionParameter::export(
                array(
                    $p->getDeclaringClass()->name,
                    $p->getDeclaringFunction()->name
                ),
                $p->name,
                true
            );

            // Example: string(EmailHelper)
            $type = preg_replace('/.*?(\w+)\s+\$'.$p->name.'.*/', '\\1', $export);
            $deps[] = $serviceManager->get($type);

        }
        $method->invokeArgs(new $controllerName(), $deps);
    }

    /**
     * Instantiate Class
     *
     * @param  bool  $create_static
     *
     * @return  object
     * @since   1.0
     * @throws  InjectorException
     */
    public function onAfterServiceInstantiate($create_static = false)
    {
        // process setter option array
    }
}
