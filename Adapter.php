<?php
/**
 * Injector Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\IoC;

use Exception;
use Molajo\IoC\Exception\InjectorException;
use Molajo\IoC\Api\AdapterInterface;

/**
 * Injector Adapter
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Adapter implements AdapterInterface
{
    /**
     * Injector Folder Namespace
     *
     * @var     array
     * @since   1.0
     */
    protected $injector_folder_namespace = 'Molajo\\Application\\Services\\';

    /**
     * Injector Instance
     *
     * @var     array
     * @since   1.0
     */
    protected $injector;

    /**
     * Injector Type
     *
     * @var     string
     * @since   1.0
     */
    protected $injector_type;

    /**
     * Service Name
     *
     * @var     string
     * @since   1.0
     */
    protected $service_name;

    /**
     * List of Properties
     *
     * @var    object
     * @since  1.0
     */
    protected $property_array = array(
        'connections',
        'injector',
        'injector_folder_namespace',
        'injector_type',
        'service_name'
    );

    /**
     * Set the value of a specified key
     *
     * @param   string $key
     * @param   mixed  $value
     *
     * @return  mixed
     * @since   1.0
     * @throws  InjectorException
     */
    public function set($key, $value = null)
    {
        $key = strtolower($key);

        if (in_array($key, $this->property_array)) {
        } else {
            throw new InjectorException
            ('IoC Injector Adapter Set: unknown key: ' . $key);
        }

        $this->$key = $value;

        return $this->$key;
    }

    /**
     * Get the current value (or default) of the specified key
     *
     * @param   string $key
     * @param   mixed  $default
     *
     * @return  mixed
     * @since   1.0
     * @throws  InjectorException
     */
    public function get($key, $default = null)
    {
        $key = strtolower($key);

        if (in_array($key, $this->property_array)) {
        } else {
            throw new InjectorException
            ('IoC Injector Adapter Get: unknown key: ' . $key);
        }

        if ($this->$key === null) {
            $this->$key = $default;
        }

        return $this->$key;
    }

    /**
     * Instantiates Service Class
     *
     * @param   string $service_name
     *
     * @return  object
     * @since   1.0
     * @throws  InjectorException
     */
    public function instantiateInjector($service_name)
    {
        $this->service_name = $service_name;

        try {

            $class_name = $this->injector_folder_namespace
                . $this->service_name . '\\'
                . $this->service_name . 'Injector';

            $this->injector = new $class_name();

        } catch (Exception $e) {

            throw new InjectorException
            ('Injector Adapter: Injector Instance Failed for ' . $service_name
                . ' failed.' . $e->getMessage());
        }

        return $this->injector;
    }

    /**
     * Get the current value for the specified key for the Injector
     *
     * @param   string     $key
     * @param   null|mixed $default
     *
     * @return  mixed
     * @since   1.0
     */
    public function getInjectorProperty($key, $default = null)
    {
        return $this->injector->get($key, $default);
    }

    /**
     * Get the current value for the specified key for the Injector
     *
     * @param   string     $key
     * @param   null|mixed $value
     *
     * @return  $this
     * @since   1.0
     */
    public function setInjectorProperty($key, $value = null)
    {
        $this->injector->set($key, $value);

        return $this;
    }

    /**
     * Execute the Injector onBeforeServiceInstantiate
     *
     * @return  $this
     * @since   1.0
     */
    public function onBeforeServiceInstantiate()
    {
        $this->injector->onBeforeServiceInstantiate();

        return $this;
    }

    /**
     * Instantiate Injector
     *
     * @param   bool $create_static
     *
     * @return  $this
     * @since   1.0
     */
    public function instantiate($create_static = false)
    {
        $this->injector->instantiate($create_static);

        return $this;
    }

    /**
     * Execute the Injector onBeforeServiceInstantiate
     *
     * @return  $this
     * @since   1.0
     */
    public function onAfterServiceInstantiate()
    {
        $this->injector->onAfterServiceInstantiate();

        return $this;
    }

    /**
     * Execute the Injector initialise
     *
     * @return  $this
     * @since   1.0
     */
    public function initialise()
    {
        $this->injector->initialise();

        return $this;
    }

    /**
     * Execute the Injector onBeforeServiceInstantiate
     *
     * @return  $this
     * @since   1.0
     */
    public function onAfterServiceInitialise()
    {
        $this->injector->onAfterServiceInitialise();

        return $this;
    }

    /**
     * Retrieve the Service Instance, store the instance or properties, if requested
     *
     * @return  $this
     * @since   1.0
     */
    public function getServiceInstance()
    {
        return $this->injector->getServiceInstance();
    }
}
