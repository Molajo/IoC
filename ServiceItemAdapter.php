<?php
/**
 * Service Item Dependency Injection Adapter
 *  Driven by the IoC Controller to interact with the DI Handler to resolve dependencies and create classes
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC;

use CommonApi\IoC\ServiceItemInterface;
use CommonApi\IoC\ServiceProviderInterface;

/**
 * Service Item Dependency Injection Adapter
 *  Driven by the IoC Controller to interact with the DI Handler to resolve dependencies and create classes
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class ServiceItemAdapter implements ServiceItemInterface
{
    /**
     * Dependencies
     *
     * @var     array
     * @since   1.0
     */
    protected $dependencies = null;

    /**
     * Reflection Dependencies
     *
     * @var     object
     * @since   1.0
     */
    protected $reflection = null;

    /**
     * Dependencies set with instance
     *
     * @var     array
     * @since   1.0
     */
    protected $dependency_instances = array();

    /**
     * Handler
     *
     * @var     object  CommonApi\IoC\ServiceProviderInterface
     * @since   1.0
     */
    protected $handler;

    /**
     * Service Instance
     *
     * @var     object
     * @since   1.0
     */
    protected $service_instance;

    /**
     * Container Key (Handler Namespace unless it is Standard Handler, if so Service Namespace)
     *
     * @var     string
     * @since   1.0
     */
    protected $container_key;

    /**
     * The Constructor is invoked by Controller->setServiceWorkObject for each Service
     *
     * @param  ServiceProviderInterface $handler
     *
     * @since  1.0
     */
    public function __construct(
        ServiceProviderInterface $handler
    ) {
        $this->handler = $handler;
    }

    /**
     * IoC Controller requests Service Name from DI Handler
     *
     * @return  string
     * @since   1.0
     */
    public function getServiceName()
    {
        return $this->handler->getServiceName();
    }

    /**
     * IoC Controller requests Service Namespace from DI Handler
     *
     * @return  string
     * @since   1.0
     */
    public function getServiceNamespace()
    {
        return $this->handler->getServiceNamespace();
    }

    /**
     * IoC Controller requests Service Options from DI Handler
     *
     * @return  array
     * @since   1.0
     */
    public function getServiceOptions()
    {
        return $this->handler->getServiceOptions();
    }

    /**
     * IoC Controller retrieves "store instance indicator" from DI Handler
     *
     * @return  string
     * @since   1.0
     */
    public function getStoreInstanceIndicator()
    {
        return $this->handler->getStoreInstanceIndicator();
    }

    /**
     * IoC Controller provides reflection values which the DI Handler can use to set Dependencies
     *  Or, Dependencies can be specifically defined by the DI Handler.
     *  In either case, Dependencies are returned to the IoC Controller.
     *
     * @param   array $reflection
     *
     * @return  array
     * @since   1.0
     */
    public function setDependencies(array $reflection = null)
    {
        $this->dependencies = $this->handler->setDependencies($reflection);

        if (is_array($this->dependencies) && count($this->dependencies) > 0) {
        } else {
            $this->dependencies = array();
        }

        foreach ($this->dependencies as $key => $value) {
            $this->dependency_instances[$key] = null;
        }

        return $this->dependencies;
    }

    /**
     * IoC Controller removes Dependency (Either itself or for if_exists)
     *
     * Note: no communication with the DI Handler in this method
     *
     * @param   string $dependency
     *
     * @return  $this
     * @since   1.0
     */
    public function removeDependency($dependency)
    {
        if (isset($this->dependency_instances[$dependency])) {
            unset($this->dependency_instances[$dependency]);
        }
    }

    /**
     * IoC Controller provides an Instance for Dependency, not sent to the
     *  DI Handler until all Dependencies in place. At that time, the IoC Controller
     *  uses processFulfilledDependencies to send satisfied Dependencies to the DI Handler
     *
     * Note: no communication with the DI Handler in this method
     *
     * @param   string $dependency
     * @param   object $dependency_instance
     *
     * @return  $this
     * @since   1.0
     */
    public function setDependencyInstance($dependency, $dependency_instance)
    {
        $this->dependency_instances[$dependency] = $dependency_instance;

        return $this;
    }

    /**
     * IoC Controller requests count of Dependencies not yet satisfied
     *
     * Note: no communication with the DI Handler in this method
     *
     * @return  int
     * @since   1.0
     */
    public function getRemainingDependencyCount()
    {
        $count = 0;

        foreach ($this->dependency_instances as $key => $instance) {

            if ($key && $instance === null) {
                $count ++;
            }
        }

        return $count;
    }

    /**
     * IoC Controller shares Dependency Instances with DI Handler for final processing before Class creation
     *
     * @return  $this
     * @since   1.0
     */
    public function processFulfilledDependencies()
    {
        $this->handler->processFulfilledDependencies($this->dependency_instances);

        return $this;
    }

    /**
     * IoC Controller triggers the DI Handler to create the Class for the Service
     *
     * @return  $this
     * @since   1.0
     */
    public function instantiateService()
    {
        $this->handler->instantiateService();

        return $this;
    }

    /**
     * IoC Controller triggers the DI Handler to execute logic that follows class instantiation,
     *  Location for Setter Dependencies or any other actions that must follow Class Creation
     *
     * @return  $this
     * @since   1.0
     */
    public function performAfterInstantiationLogic()
    {
        $this->handler->performAfterInstantiationLogic();

        return $this;
    }

    /**
     * IoC Controller requests Service Instance for just created Class from DI Handler
     *
     * @return  object
     * @since   1.0
     */
    public function getServiceInstance()
    {
        $this->service_instance = $this->handler->getServiceInstance();

        return $this->service_instance;
    }

    /**
     * Following Class creation, DI Handler requests the IoC Controller set Services in the Container
     *
     * @return  string
     * @since   1.0
     */
    public function setService()
    {
        return $this->handler->setService();
    }

    /**
     * Following Class creation, DI Handler requests the IoC Controller remove Services from the Container
     *
     * @return  string
     * @since   1.0
     */
    public function removeService()
    {
        return $this->handler->removeService();
    }

    /**
     * Following Class creation, DI Handler requests the IoC Controller instantiate Services
     *
     * @return  $this
     * @since   1.0
     */
    public function scheduleNextService()
    {
        return $this->handler->scheduleNextService();
    }
}
