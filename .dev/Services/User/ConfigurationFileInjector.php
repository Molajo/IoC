<?php
/**
 * Configuration File Dependency Injector
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Services\ConfigurationFile;

use Exception;
use Molajo\IoC\Handler\CustomInjector;
use Molajo\IoC\Api\InjectorInterface;
use Molajo\IoC\Exception\InjectorException;



/**
 * Configuration File Dependency Injector
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class ConfigurationFileInjector extends CustomInjector implements InjectorInterface
{
    /**
     * Instantiate Class
     *
     * @param   bool $create_static
     *
     * @return  object
     * @since   1.0
     * @throws  InjectorException
     */
    public function instantiate($create_static = false)
    {
        $configuration = $this->frontcontroller_instance->getService('Configuration');

        $model_name = null;
        if (isset($this->options['model_name'])) {
            $model_name = $this->options['model_name'];
        }
        $model_name = ucfirst(strtolower($model_name));

        $model_type = null;
        if (isset($this->options['model_type'])) {
            $model_type = $this->options['model_type'];
        }
        $model_type = ucfirst(strtolower($model_type));

        try {
            $this->service_instance = $configuration->getFile($model_type, $model_name);

        } catch (Exception $e) {

            throw new InjectorException
            ('IoC: Injector Instance Failed for ' . $this->service_namespace
                . ' failed.' . $e->getMessage());
        }

        return $this;
    }
}
