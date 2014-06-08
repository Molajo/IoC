<?php
/**
 * Abstract Base
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC\Schedule;

use Molajo\IoC\Container;
use Molajo\IoC\Product\ClassDependencies;
use Molajo\IoC\Product\SetNamespace;

/**
 * Base
 *
 * Request - Dependency - Create - Base
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
abstract class Base
{
    /**
     * Container
     *
     * @var     object  CommonApi\IoC\ContainerInterface
     * @since   1.0.0
     */
    protected $container = null;

    /**
     * Class Dependencies
     *
     * @var     object
     * @since   1.0.0
     */
    protected $class_dependencies = null;

    /**
     * Factory Method Namespace
     *
     * @var     object
     * @since   1.0.0
     */
    protected $factory_method_namespace = null;

    /**
     * Request Queue
     *
     * @var     integer
     * @since   1.0.0
     */
    protected $queue_id = 1;

    /**
     * Process Request Queue
     *
     * @var     array
     * @since   1.0.0
     */
    protected $process_requests = array();

    /**
     * New Requests Queue
     *
     * @var     array
     * @since   1.0.0
     */
    protected $to_be_processed_requests = array();

    /**
     * Process Request Queue
     *
     * @var     array
     * @since   1.0.0
     */
    protected $request_names_to_id = array();

    /**
     * Standard IoC Factory Method Namespace (Used when no custom Factory Method is required)
     *
     * @var     string
     * @since   1.0.0
     */
    protected $standard_adapter_namespace = 'Molajo\\IoC\\FactoryMethod\\Standard';

    /**
     * Product Result
     *
     * @var     object
     * @since   1.0.0
     */
    protected $product_result;

    /**
     * Constructor
     *
     * @param  string $class_dependencies_file
     * @param  string $standard_adapter_namespace
     *
     * @since  1.0.0
     */
    public function __construct(
        array $factory_method_aliases = array(),
        $class_dependencies_file = '',
        $standard_adapter_namespace = 'Molajo\\IoC\\FactoryMethod\\Standard'
    ) {
        $this->createContainer($factory_method_aliases);
        $this->createClassDependencies($class_dependencies_file);
        $this->createNamespace($standard_adapter_namespace);
    }

    /**
     * See if product already exists within the container
     *
     * @param   string $key
     *
     * @return  boolean
     * @since   1.0.0
     */
    protected function hasContainerEntry($key)
    {
        if ($this->container->has($key) === false) {
            return false;
        }

        return true;
    }

    /**
     * Get the primary key for container
     *
     * @param   string $key
     *
     * @return  string
     * @since   1.0.0
     */
    protected function getContainerEntryKey($key)
    {
        $new_key = $this->container->getKey($key, true);
        if ($new_key === false) {
            $new_key = $key;
        }
        return $new_key;
    }

    /**
     * See if product already exists within the container
     *
     * @param   string $key
     *
     * @return  mixed
     * @since   1.0.0
     */
    protected function getContainerEntry($key)
    {
        if ($this->container->has($key) === false) {
            return false;
        }

        return $this->container->get($key);
    }

    /**
     * Create Container
     *
     * @param   array $factory_method_aliases
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function createContainer(array $factory_method_aliases = array())
    {
        $this->container = new Container($factory_method_aliases);

        return $this;
    }

    /**
     * Create Factory Method Namespace Object
     *
     * @param   string $standard_adapter_namespace
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function createNamespace($standard_adapter_namespace)
    {
        if (trim($standard_adapter_namespace) === '') {
            $this->standard_adapter_namespace = 'Molajo\\IoC\\FactoryMethod\\Standard';
        } else {
            $this->standard_adapter_namespace = $standard_adapter_namespace;
        }

        $this->factory_method_namespace = new SetNamespace($standard_adapter_namespace);

        return $this;
    }

    /**
     * Create Class Dependencies Object
     *
     * @param   string $class_dependencies_file
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function createClassDependencies($class_dependencies_file)
    {
        $this->class_dependencies = new ClassDependencies($class_dependencies_file);

        return $this;
    }
}
