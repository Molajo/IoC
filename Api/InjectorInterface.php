<?php
/**
 * Injector Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\IoC\Api;

use Molajo\IoC\Exception\InjectorException;

/**
 * Injector Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface InjectorInterface
{
    /**
     * on Before Startup Event
     *
     * Follows instantiation of the service class and before the method identified as the "start" method
     *
     * @return  object
     * @since   1.0
     */
    public function onBeforeServiceInstantiate();

    /**
     * Instantiate Class
     *
     * @param   bool $create_static
     *
     * @return  object
     * @since   1.0
     * @throws  InjectorException
     */
    public function instantiate($create_static = false);

    /**
     * Instantiate Service class Statically
     *
     * @param   string $service_namespace
     *
     * @static
     *
     * @return  null|object
     * @since   1.0
     * @throws  InjectorException
     */
    public static function instantiate_static($service_namespace);

    /**
     * On After Startup Instantiate
     *
     * Follows the completion of the instantiate service method
     *
     * @return  object
     * @since   1.0
     * @throws  InjectorException
     */
    public function onAfterServiceInstantiate();

    /**
     * Initialise Service Class, if the method exists
     *
     * @return  object
     * @since   1.0
     * @throws  InjectorException
     */
    public function initialise();

    /**
     * On After Service Instance Initialise method
     *
     * Follows the completion of Initialise
     *
     * @return  object
     * @since   1.0
     * @throws  InjectorException
     */
    public function onAfterServiceInitialise();

    /**
     * Get Service Instance
     *
     * @return  object
     * @since   1.0
     * @throws  InjectorException
     */
    public function getServiceInstance();
}
