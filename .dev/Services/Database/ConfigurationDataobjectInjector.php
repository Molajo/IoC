<?php
/**
 * Configuration Dataobject Dependency Injector
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Services\ConfigurationDataobject;

use Exception;
use Molajo\IoC\Handler\CustomInjector;
use Molajo\IoC\Api\InjectorInterface;
use Molajo\IoC\Exception\InjectorException;



/**
 * Configuration Dataobject Dependency Injector
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class ConfigurationDataobjectInjector extends CustomInjector implements InjectorInterface
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
        /**
         *  1. Get Instance of Cache
         */
        $options              = array();
        $options['if_exists'] = true;
        $cache_instance       = $this->frontcontroller_instance->getService('Cache', $options);

        /**
         *  2. Get Instance of Registry
         */
        $registry_instance = $this->frontcontroller_instance->getService('Registry');

        /**
         *  3. Get Dataobject (Check Registry, Cache, then retrieve it)
         */
        $model_name = null;
        if (isset($this->options['model_name'])) {
            $model_name = $this->options['model_name'];
        }
        $model_name = ucfirst(strtolower($model_name));

        $model_type = null;
        if (isset($this->options['model_type'])) {
            $model_type = $this->options['model_type'];
        }
        if ($model_type === null) {
            $model_type = 'dataobject';
        }
        $model_type = ucfirst(strtolower($model_type));

        $parameter_registry = null;
        if (isset($this->options['parameter_registry'])) {
            $parameter_registry = $this->options['parameter_registry'];
        }

        $this->service_instance = false;
        $model_registry_name    = $model_name . $model_type;

        /** 4a. Registry */
        $set_registry = true;

        if (is_object($registry_instance)) {
            $this->service_instance = $registry_instance->get($model_registry_name);
            if ($this->service_instance === false) {
            } else {
                $set_registry = false;
            }
        }

        /** 4b. Cache */
        $set_cache = true;

        if (is_object($cache_instance) && $this->service_instance === false) {
            $cached_output = $cache_instance->get(md5($model_registry_name));

            if ($cached_output->isHit() === false) {
            } else {
                $set_cache              = false;
                $this->service_instance = $cached_output->value;
            }
        }

        /** 4c. Retrieve it */
        if ($this->service_instance === false) {

            $configuration = $this->frontcontroller_instance->getService('Configuration');

            try {
                $this->service_instance = $configuration->getDataobject($model_type, $model_name);
                $set_registry           = false;

            } catch (Exception $e) {

                throw new InjectorException
                ('IoC: Injector Instance Failed for ' . $this->service_namespace
                    . ' failed.' . $e->getMessage());
            }
        }

        /**
         *  5. Throw exception if Dataobject is not found
         */
        if ($this->service_instance === false) {
            throw new InjectorException
            ('IoC Injector: Could not locate Dataobject:  ' . $model_registry_name);
        }

        /**
         *  6. Load the Dataobject into the Registry and Cache it.
         */

        /** 6a. Set Registry */
        //  if (is_object($registry_instance) && $set_registry === true) {
        //      $registry_instance->createRegistry($model_registry_name);
        //      $registry_instance->loadArray($model_registry_name, $this->service_instance);
        //  }

        /** 6b. Set Cache */
        if (is_object($cache_instance) && $set_cache === true) {
            $cache_it = $registry_instance->getArray($model_registry_name);
            $cache_instance->set(md5($model_registry_name), $cache_it);
        }

        return $this;
    }
}
