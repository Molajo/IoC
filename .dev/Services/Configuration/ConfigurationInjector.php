<?php
/**
 * Configuration Dependency Injector
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Services\Configuration;

use Exception;
use Molajo\IoC\Handler\CustomInjector;
use Molajo\IoC\Api\InjectorInterface;
use Molajo\IoC\Exception\InjectorException;

/**
 * Configuration Service Dependency Injector
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class ConfigurationInjector extends CustomInjector implements InjectorInterface
{
    /**
     * Constructor
     *
     * @since   1.0
     */
    public function __construct($options)
    {
        $this->service_namespace        = 'Molajo\\Configuration';
        $this->store_instance_indicator = true;

        parent::__construct($options);
    }

}
