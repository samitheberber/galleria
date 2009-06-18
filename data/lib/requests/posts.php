<?php
/**
 * File for Galleria Requests Posts class.
 *
 * File contains Galleria Requests Posts.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package lib
 */

/**
 * Class for Galleria Requests Posts.
 *
 * Class handles all post requests.
 * @name Galleria_Requests_Posts
 * @abstract
 * @package lib
 * @subpackage requests
 */
class Galleria_Requests_Posts
{

    /**
     * @access private
     * @var array $_values
     */
    protected $_values;

    /**
     * Creates Posts.
     *
     * This is constructor of Posts.
     * @access public
     * @param array $values
     */
    public function __construct($values)
    {
        $this->_values = array();
        foreach ($values as $name => $value) {
            $this->{$name} = $value;
        }
    }

    /**
     * Gets variable.
     *
     * This is PHP's magic method implementation for __get.
     * @access public
     * @param string $value
     * @return string
     */
    public function __get($value)
    {
        return isset($this->_values[$value]) ? $this->_values[$value] : null;
    }

    /**
     * Sets variable.
     *
     * This is PHP's magic method implementation for __set.
     * @access public
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value)
    {
        $this->_values[$name] = $value;
    }
}
