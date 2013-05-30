<?php
/**
 * Test Class
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo;

class StandardMock
{
    public $foo = 1;
    public $bar = 2;
    public $baz = 3;

    /**
     * Constructor
     *
     * @since   1.0
     */
    public function __construct($foo = 1, $bar = 2, $baz = 3)
    {
        $this->foo = $foo;
        $this->bar = $bar;
        $this->baz = $baz;
    }

    /**
     * Initialise
     *
     * @return  object
     * @since   1.0
     */
    public function initialise()
    {

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
