<?php
/**
 * Standard Dependency Injector
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC\Handler\AbstractInjector;

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
        // process constructor options
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
