<?php
/**
 * Configuration Dependency Injector
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\ServiceMocks\ConfigurationMock;

use Molajo\IoC\Handler\CustomInjector;
use Molajo\IoC\Api\InjectorInterface;

/**
 * Configuration Service Dependency Injector
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class ConfigurationMockInjector extends CustomInjector implements InjectorInterface
{
    /**
     * Constructor
     *
     * @since   1.0
     */
    public function __construct($options)
    {
        $this->service_namespace        = 'Molajo\\ConfigurationMock';
        $this->store_instance_indicator = true;

        parent::__construct($options);
    }
}
