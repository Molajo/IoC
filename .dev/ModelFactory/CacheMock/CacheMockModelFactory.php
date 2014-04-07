<?php
/**
 * Cache Factory Method
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\Factories\CacheMock;

use Exception;
use Molajo\IoC\FactoryMethodBase;
use CommonApi\IoC\FactoryInterface;
use CommonApi\Exception\RuntimeException;

/**
 * Cache Factory Method
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class CacheMockFactoryMethod extends FactoryMethodBase implements FactoryInterface, FactoryBatchInterface
{
    /**
     * Constructor
     *
     * @since   1.0
     */
    public function __construct(array $options = array())
    {
        $options['product_name']             = basename(__DIR__);
        $options['store_instance_indicator'] = true;
        $options['product_namespace']        = 'Molajo\\CacheMock';

        parent::__construct($options);
    }

    /**
     * Retrieve a list of Interface dependencies and return the data ot the controller.
     *
     * @return  array
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function setDependencies(array $reflection = null)
    {
        parent::setDependencies($reflection);

        $this->dependencies['ConfigurationMock'] = array();

        return $this->dependencies;
    }

    /**
     * Instantiate Class
     *
     * @param bool $create_static
     *
     * @return  object
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function instantiateClass()
    {
        $options = array();
        if (isset($this->options['foo'])) {
            $options['foo'] = $this->options['foo'];
        }
        if (isset($this->options['bar'])) {
            $options['bar'] = $this->options['bar'];
        }
        if (isset($this->options['baz'])) {
            $options['baz'] = $this->options['baz'];
        }
        $options['configuration'] = $this->dependencies['ConfigurationMock'];

        try {
            $class                = $this->product_namespace;
            $this->product_result = new $class($options);

        } catch (Exception $e) {

            throw new RuntimeException
            ('IoC Factory Method Adapter Instance Failed for ' . $this->product_namespace
            . ' failed.' . $e->getMessage());
        }

        return $this;
    }
}
