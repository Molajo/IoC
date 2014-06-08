<?php
/**
 * Factory Method Controller
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC;

use CommonApi\IoC\FactoryInterface;
use CommonApi\IoC\FactoryBatchInterface;

/**
 * Factory Method Controller
 *
 * Driven by the Factory Method Controller to interact with Factory Method Adapter
 *  to resolve dependencies by constructing dependent classes and construct
 *  the primary class, itself, once all dependencies are available
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class FactoryMethodController implements FactoryInterface, FactoryBatchInterface
{
    /**
     * Dependencies
     *
     * @var    array
     * @since  1.0.0
     */
    protected $dependencies = null;

    /**
     * Reflection Dependencies
     *
     * @var    object
     * @since  1.0.0
     */
    protected $reflection = null;

    /**
     * Dependencies that have been satisfied
     *
     * @var    array
     * @since  1.0.0
     */
    protected $dependency_values = array();

    /**
     * Factory Method Adapter Instance
     *
     * @var    object  CommonApi\IoC\FactoryInterface
     * @since  1.0.0
     */
    protected $factory_adapter;

    /**
     * Product Result
     *
     * @var    object
     * @since  1.0.0
     */
    protected $product;

    /**
     * Container Key
     *
     * Value is the Factory Method Namespace unless it is Standard Factory Method
     * in which case it is the Namespace for the Class, itself
     *
     * @var    string
     * @since  1.0.0
     */
    protected $container_key;

    /**
     * The Constructor is invoked by Controller->setProductWorkObject for each request
     *
     * @param  FactoryInterface $factory_adapter
     *
     * @since  1.0.0
     */
    public function __construct(
        FactoryInterface $factory_adapter
    ) {
        $this->factory_adapter = $factory_adapter;
    }

    /**
     * Factory Method Controller requests Product Namespace from Factory Method
     *
     * @return  string
     * @since   1.0.0
     */
    public function getNamespace()
    {
        return $this->factory_adapter->getNamespace();
    }

    /**
     * Factory Method Controller requests Service Options from Factory Method
     *
     * @return  array
     * @since   1.0.0
     */
    public function getOptions()
    {
        return $this->factory_adapter->getOptions();
    }

    /**
     * Factory Method Controller retrieves "store instance indicator" from Factory Method
     *
     * @return  string
     * @since   1.0.0
     */
    public function getStoreContainerEntryIndicator()
    {
        return $this->factory_adapter->getStoreContainerEntryIndicator();
    }

    /**
     * Factory Method Controller provides reflection values which the Factory Method
     *  can use to set Dependencies. Alternatively, Dependencies can be specifically defined
     *  by the Factory Method. In either case, Dependencies are returned to the IoC Service
     *  Provider Controller.
     *
     * @param   array $reflection
     *
     * @return  array
     * @since   1.0.0
     */
    public function setDependencies(array $reflection = array())
    {
        $this->dependencies = $this->factory_adapter->setDependencies($reflection);

        if (is_array($this->dependencies) && count($this->dependencies) > 0) {
        } else {
            $this->dependencies = array();
        }

        foreach ($this->dependencies as $key => $value) {
            $this->dependency_values[$key] = null;
        }

        return $this->dependencies;
    }

    /**
     * Factory Method Controller removes Dependency (Either itself or for if_exists)
     *
     * Note: no communication with the Factory Method in this method
     *
     * @param   string $dependency
     *
     * @return  $this
     * @since   1.0.0
     */
    public function removeDependency($dependency)
    {
        if (isset($this->dependency_values[$dependency])) {
            unset($this->dependency_values[$dependency]);
        }
    }

    /**
     * Factory Method Controller provides an Instance for Dependency, not sent to the
     *  Factory Method until all Dependencies in place. At that time, the Factory Method Controller
     *  uses onBeforeInstantiation to send satisfied Dependencies to the Factory Method
     *
     * Note: no communication with the Factory Method in this method
     *
     * @param   string $dependency
     * @param   object $dependency_value
     *
     * @return  $this
     * @since   1.0.0
     */
    public function setDependencyValue($dependency, $dependency_value)
    {
        $this->dependency_values[$dependency] = $dependency_value;

        return $this;
    }

    /**
     * Factory Method Controller requests count of Dependencies not yet satisfied
     *
     * Note: no communication with the Factory Method in this method
     *
     * @return  int
     * @since   1.0.0
     */
    public function getRemainingDependencyCount()
    {
        $count = 0;

        foreach ($this->dependency_values as $key => $instance) {

            if ($key && $instance === null) {
                $count ++;
            }
        }

        return $count;
    }

    /**
     * Factory Method Controller shares Dependency Instances with Factory Method for final processing
     * before Class creation
     *
     * @return  $this
     * @since   1.0.0
     */
    public function onBeforeInstantiation(array $values = array())
    {
        $this->factory_adapter->onBeforeInstantiation($this->dependency_values);

        return $this;
    }

    /**
     * Factory Method Controller triggers the Factory Method to create the Class for the Service
     *
     * @return  $this
     * @since   1.0.0
     */
    public function instantiateClass()
    {
        $this->factory_adapter->instantiateClass();

        return $this;
    }

    /**
     * Factory Method Controller triggers the Factory Method to execute logic that follows class instantiation,
     *  Location for Setter Dependencies or any other actions that must follow Class Creation
     *
     * @return  $this
     * @since   1.0.0
     */
    public function onAfterInstantiation()
    {
        $this->factory_adapter->onAfterInstantiation();

        return $this;
    }

    /**
     * Factory Method Controller requests Product Result for just created Class from Factory Method
     *
     * @return  object
     * @since   1.0.0
     */
    public function getProductValue()
    {
        $this->product = $this->factory_adapter->getProductValue();

        return $this->product;
    }

    /**
     * Following Class creation, Factory Method requests the Factory Method Controller
     *  remove Products from the Container
     *
     * @return  string
     * @since   1.0.0
     */
    public function removeContainerEntries()
    {
        return $this->factory_adapter->removeContainerEntries();
    }

    /**
     * Following Class creation, Factory Method requests the Factory Method Controller instantiate Services
     *
     * @return  $this
     * @since   1.0.0
     */
    public function setContainerEntries()
    {
        return $this->factory_adapter->setContainerEntries();
    }

    /**
     * Following Class creation, Factory Method requests the Factory Method Controller instantiate Services
     *
     * @return  $this
     * @since   1.0.0
     */
    public function scheduleFactories()
    {
        return $this->factory_adapter->scheduleFactories();
    }
}
