<?php
/**
 * Test Class
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Services;

class Cache
{
    public $foo = 1;
    public $bar = 2;
    public $baz = 3;

    /**
     * Constructor
     *
     * @since   1.0
     */
    public function __construct($options)
    {
        if (isset($options['foo'])) {
            $this->foo = $options['foo'];
        }
        if (isset($options['bar'])) {
            $this->bar = $options['bar'];
        }
        if (isset($options['baz'])) {
            $this->baz = $options['baz'];
        }
    }

    /**
     * Initialise
     *
     * @return  object
     * @since   1.0
     */
    public function initialise()
    {
        $this->baz = 5;
    }

    /**
     * Get
     *
     * @param   string $key
     * @param   null   $default
     *
     * @return  object
     * @since   1.0
     */
    public function get($key, $default = null)
    {
        if (isset($this->$key)) {
        } else {
            $this->$key = $default;
        }

        return $this->$key;
    }

    /**
     * Set
     *
     * @param   string $key
     * @param   null   $default
     *
     * @return  object
     * @since   1.0
     */
    public function set($key, $value = null)
    {
        $this->$key = $value;

        return $this->$key;
    }
}