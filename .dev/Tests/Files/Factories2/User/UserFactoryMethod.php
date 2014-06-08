<?php
/**
 * Mock User Factory Method
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\Factories\User;

use CommonApi\IoC\FactoryInterface;
use CommonApi\IoC\FactoryBatchInterface;
use Molajo\IoC\FactoryMethodBase;

/**
 *  Mock User Factory Method
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class UserFactoryMethod extends FactoryMethodBase implements FactoryInterface, FactoryBatchInterface
{
    /**
     * Constructor
     *
     * @param   $options
     *
     * @since  1.0.0
     */
    public function __construct(array $options = array())
    {
        $options['product_namespace'] = 'Molajo\\Event\\User';
        $options['store_instance_indicator'] = true;

        parent::__construct($options);
    }

    /**
     * Factory Method can use this method to define Service Dependencies
     *  or use the Service Dependencies automatically defined by Reflection processes
     *
     * @param   array $reflection
     *
     * @return  array
     * @since  1.0.0
     */
    public function setDependencies(array $reflection = null)
    {
        return parent::setDependencies($reflection);
    }

    /**
     * Logic contained within this method is invoked after Dependencies Instances are available
     *  and before the instantiateClass Method is invoked
     *
     * @param   array $dependency_values
     *
     * @return  array
     * @since  1.0.0
     */
    public function onBeforeInstantiation(array $dependency_values = null)
    {
        return parent::onBeforeInstantiation($dependency_values);
    }

    /**
     * Service instantiated automatically or within this method by the Factory Method
     *
     * @return  $this
     * @since  1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function instantiateClass()
    {
        return parent::instantiateClass();
    }

    /**
     * Logic contained within this method is invoked after the class construction
     *  and can be used for setter logic or other post-construction processing
     *
     * @return  $this
     * @since  1.0.0
     */
    public function onAfterInstantiation()
    {
        return parent::onAfterInstantiation();
    }

    /**
     * Factory Method Controller requests Product Result for just created Class from Factory Method
     *
     * @return  object
     * @since  1.0.0
     */
    public function getProductValue()
    {
        return parent::getProductValue();
    }

    /**
     * Request for array of Products to be removed from the IoC Container
     *
     * @return  array
     * @since  1.0.0
     */
    public function removeContainerEntries()
    {
        return $this->remove_container_entries;
    }

    /**
     * Request for array of Products and Values to be saved to the IoC Container
     *
     * @return  array
     * @since  1.0.0
     */
    public function setContainerEntries()
    {
        return $this->set_container_entries;
    }

    /**
     * Request for array of Factory Methods to be Scheduled
     *
     * @return  array
     * @since  1.0.0
     */
    public function scheduleFactories()
    {
        return $this->schedule_factory_methods;
    }
}
