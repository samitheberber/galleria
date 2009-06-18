<?php
/**
 * File for Galleria Requests Gets class.
 *
 * File contains Galleria Requests Gets.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package lib
 */

/**
 * Class for Galleria Requests Gets.
 *
 * Class handles all get requests.
 * @name Galleria_Requests_Gets
 * @abstract
 * @package lib
 * @subpackage requests
 */
class Galleria_Requests_Gets
{

    /**
     * @access private
     * @var array $_values
     */
    protected $_values;

    /**
     * Creates Gets.
     *
     * This is constructor of Gets.
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
