<?php
/**
 * Sample Dependency Injector Class
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Services;



use Exception;
use Molajo\IoC\Exception\InjectorException;

/**
 * Sample Dependency Injector Class
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class SampleInjector extends AbstractInjector implements InjectorInterface
{
    /**
     * on Before Startup Event
     *
     * Follows instantiation of the service class and before the method identified as the "start" method
     *
     * @return  object
     * @since   1.0
     */
    public function onBeforeServiceInstantiate()
    {

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

    }

    /**
     * On After Startup Instantiate
     *
     * Follows the completion of the instantiate service method
     *
     * @return  object
     * @since   1.0
     * @throws  InjectorException
     */
    public function onAfterServiceInstantiate()
    {

    }

    /**
     * Initialise Service Class, if the method exists
     *
     * @return  object
     * @since   1.0
     * @throws  InjectorException
     */
    public function initialise()
    {

    }

    /**
     * Get Service Instance
     *
     * @return  object
     * @since   1.0
     * @throws  InjectorException
     */
    public function getServiceInstance()
    {

    }
}
