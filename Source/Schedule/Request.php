<?php
/**
 * Request
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC\Schedule;

use Molajo\IoC\FactoryMethod;
use Molajo\IoC\Product\CreateFactoryMethod;
use stdClass;

/**
 * Request
 *
 * Request - Dependency - CreateFactoryMethod - Base
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
abstract class Request extends Dependency
{
    /**
     * Process Product Request
     *
     * Initializes request and adds into the processing queue
     *
     * @param   string $product_name
     * @param   array  $options
     *
     * @return  stdClass
     * @since   1.0.0
     */
    public function setProductRequest($product_name = null, array $options = array())
    {
        $options['product_name']  = $product_name;
        $options['container_key'] = $this->getContainerEntryKey($product_name);
        $options['ioc_id']        = $this->queue_id++;

        $options = $this->getNamespace($options);

        return $this->setProductRequestWorkObject($product_name, $options);
    }

    /**
     * Process Product Request
     *
     * Initializes request and adds into the processing queue
     *
     * @param   string $product_name
     * @param   array  $options
     *
     * @return  stdClass
     * @since   1.0.0
     */
    public function setProductRequestWorkObject($product_name = null, array $options = array())
    {
        $work_object          = new stdClass();
        $work_object->options = $options;

        $work_object->factory_method = $this->createFactoryMethod($options);

        $namespace                                 = $work_object->factory_method->getNamespace();
        $work_object->options['product_namespace'] = $namespace;

        $work_object->dependency_of = array();
        if (isset($options['dependency_of'])) {
            $work_object->dependency_of[] = $options['dependency_of'];
        }

        $work_object->product_result = new stdClass();

        $work_object = $this->setClassDependencies($work_object);

        $work_object = $this->satisfyDependencies($work_object);

        if ($product_name === 'Event') {
            if ($options["event_name"] === 'onBeforeRead') {
                //fine here
            }
        }
        return $work_object;
    }

    /**
     * Get the Factory Method Namespace
     *
     * @param   array $options
     *
     * @return  array
     * @since   1.0.0
     */
    protected function getNamespace($options)
    {
        return $this->factory_method_namespace->get($options);
    }

    /**
     * Instantiate Factory Method CreateFactoryMethod Class which will create the Product Factory Method
     *
     * @param   array $options
     *
     * @return  FactoryMethod
     * @since   1.0.0
     */
    protected function createFactoryMethod(array $options)
    {
        $create = new CreateFactoryMethod($options);

        return $create->instantiateFactoryMethod();
    }

    /**
     * Get Dependencies for Product Requested
     *
     * @param   stdClass $work_object
     *
     * @return  stdClass $work_object
     * @since   1.0.0
     */
    protected function setClassDependencies($work_object)
    {
        return $this->class_dependencies->get($work_object);
    }
}
