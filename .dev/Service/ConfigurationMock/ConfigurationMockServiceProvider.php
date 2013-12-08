<?php
/**
 * Configuration Service Provider
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Service\ConfigurationMock;

use Molajo\IoC\AbstractServiceProvider;
use CommonApi\IoC\ServiceProviderInterface;

/**
 * Configuration Service Provider
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class ConfigurationMockServiceProvider extends AbstractServiceProvider implements ServiceProviderInterface
{
    /**
     * Constructor
     *
     * @since   1.0
     */
    public function __construct(array $options = array())
    {
        $this->service_namespace        = 'Molajo\\ConfigurationMock';
        $this->store_instance_indicator = true;

        parent::__construct($options);
    }
}
