<?php
/**
 * Configuration object class file.
 * 
 * This file contains Configuration object class file.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package lib
 */

/**
 * The configurations object.
 *
 * Class contains all configurations that are setted.
 * @name Galleria_Configuration_Object
 * @package lib
 * @subpackage configuration
 */
class Galleria_Configuration_Object
{

    /**
     * @access private
     * @var array
     */
    protected $_options;

    /**
     * Creates object.
     *
     * This is contructor on Configuration Object.
     * @access public
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->_options = $options;
    }

    /**
     * Magic get.
     *
     * This function implements PHP's magic function __get().
     * @access public
     * @param string $value
     * @return mixed
     */
    public function __get($value)
    {
        return isset($this->_options[$value]) ? $this->_options[$value] : null;
    }

}
