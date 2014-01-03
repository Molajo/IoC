<?php
/**
 * Service Provider Adapter
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC;

use CommonApi\IoC\ServiceProviderAdapterInterface;
use CommonApi\IoC\ServiceProviderInterface;

/**
 * Service Provider Adapter
 *
 *  Driven by the Service Provider Controller to interact with the injected Service Provider
 *  in order to resolve dependencies by constructing dependent classes and
 *  construct the primary class, itself, once all dependencies are available
 *  The constructed class will be stored within the IoC Container if so specified
 *  in the Service Provider options.
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class ServiceProviderAdapter implements ServiceProviderAdapterInterface
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
     * Dependencies that have been satisfied
     *
     * @var     array
     * @since   1.0
     */
    protected $dependency_values = array();

    /**
     * Service Provider Instance
     *
     * @var     object  CommonApi\IoC\ServiceProviderInterface
     * @since   1.0
     */
    protected $service_provider;

    /**
     * Service Instance
     *
     * @var     object
     * @since   1.0
     */
    protected $service_instance;

    /**
     * Container Key
     *
     * Value is the Service Provider Namespace unless it is Standard Service Provider
     * in which case it is the Namespace for the Service, itself
     *
     * @var     string
     * @since   1.0
     */
    protected $container_key;

    /**
     * The Constructor is invoked by Controller->setServiceWorkObject for each Service
     *
     * @param  ServiceProviderInterface $service_provider
     *
     * @since  1.0
     */
    public function __construct(
        ServiceProviderInterface $service_provider
    ) {
        $this->service_provider = $service_provider;
    }

    /**
     * Service Provider Controller requests Service Namespace from Service Provider
     *
     * @return  string
     * @since   1.0
     */
    public function getServiceNamespace()
    {
        return $this->service_provider->getServiceNamespace();
    }

    /**
     * Service Provider Controller requests Service Options from Service Provider
     *
     * @return  array
     * @since   1.0
     */
    public function getServiceOptions()
    {
        return $this->service_provider->getServiceOptions();
    }

    /**
     * Service Provider Controller retrieves "store instance indicator" from Service Provider
     *
     * @return  string
     * @since   1.0
     */
    public function getStoreInstanceIndicator()
    {
        return $this->service_provider->getStoreInstanceIndicator();
    }

    /**
     * Service Provider Controller provides reflection values which the Service Provider
     *  can use to set Dependencies. Alternatively, Dependencies can be specifically defined
     *  by the Service Provider. In either case, Dependencies are returned to the IoC Service
     *  Provider Controller.
     *
     * @param   array $reflection
     *
     * @return  array
     * @since   1.0
     */
    public function setDependencies(array $reflection = null)
    {
        $this->dependencies = $this->service_provider->setDependencies($reflection);

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
     * Service Provider Controller removes Dependency (Either itself or for if_exists)
     *
     * Note: no communication with the Service Provider in this method
     *
     * @param   string $dependency
     *
     * @return  $this
     * @since   1.0
     */
    public function removeDependency($dependency)
    {
        if (isset($this->dependency_values[$dependency])) {
            unset($this->dependency_values[$dependency]);
        }
    }

    /**
     * Service Provider Controller provides an Instance for Dependency, not sent to the
     *  Service Provider until all Dependencies in place. At that time, the Service Provider Controller
     *  uses onBeforeInstantiation to send satisfied Dependencies to the Service Provider
     *
     * Note: no communication with the Service Provider in this method
     *
     * @param   string $dependency
     * @param   object $dependency_value
     *
     * @return  $this
     * @since   1.0
     */
    public function setDependencyInstance($dependency, $dependency_value)
    {
        $this->dependency_values[$dependency] = $dependency_value;

        return $this;
    }

    /**
     * Service Provider Controller requests count of Dependencies not yet satisfied
     *
     * Note: no communication with the Service Provider in this method
     *
     * @return  int
     * @since   1.0
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
     * Service Provider Controller shares Dependency Instances with Service Provider for final processing before Class creation
     *
     * @return  $this
     * @since   1.0
     */
    public function onBeforeInstantiation()
    {
        $this->service_provider->onBeforeInstantiation($this->dependency_values);

        return $this;
    }

    /**
     * Service Provider Controller triggers the Service Provider to create the Class for the Service
     *
     * @return  $this
     * @since   1.0
     */
    public function instantiateService()
    {
        $this->service_provider->instantiateService();

        return $this;
    }

    /**
     * Service Provider Controller triggers the Service Provider to execute logic that follows class instantiation,
     *  Location for Setter Dependencies or any other actions that must follow Class Creation
     *
     * @return  $this
     * @since   1.0
     */
    public function onAfterInstantiation()
    {
        $this->service_provider->onAfterInstantiation();

        return $this;
    }

    /**
     * Service Provider Controller requests Service Instance for just created Class from Service Provider
     *
     * @return  object
     * @since   1.0
     */
    public function getServiceInstance()
    {
        $this->service_instance = $this->service_provider->getServiceInstance();

        return $this->service_instance;
    }

    /**
     * Following Class creation, Service Provider requests the Service Provider Controller set Services in the Container
     *
     * @return  string
     * @since   1.0
     */
    public function setService()
    {
        return $this->service_provider->setService();
    }

    /**
     * Following Class creation, Service Provider requests the Service Provider Controller remove Services from the Container
     *
     * @return  string
     * @since   1.0
     */
    public function removeService()
    {
        return $this->service_provider->removeService();
    }

    /**
     * Following Class creation, Service Provider requests the Service Provider Controller instantiate Services
     *
     * @return  $this
     * @since   1.0
     */
    public function scheduleServices()
    {
        return $this->service_provider->scheduleServices();
    }
}
