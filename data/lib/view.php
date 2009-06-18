<?php
/**
 * File for View class.
 * 
 * This file contains View class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package lib
 */

/**
 * Class for View.
 *
 * This class handles view functionality.
 * @name Galleria_View
 * @package lib
 * @subpackage view
 */
class Galleria_View
{

    /**#@+
     * @access private
     */
    /**
     * @var boolean $_render
     */
    protected $_render;
    /**
     * @var boolean $_layout
     */
    protected $_layout;
    /**
     * @var array $_variables
     */
    protected $_variables;
    /**#@-*/

    /**
     * Creates view.
     *
     * This is contructor of view.
     * @access public
     */
    public function __construct()
    {
        $this->_render = true;
        $this->_layout = true;
        $this->_variables = array();
    }

    /**
     * Set rendered.
     *
     * This function sets if view is rendered.
     * @access public
     * @param boolean $value
     */
    public function setRender($value)
    {
        $this->_render = $value;
        return;
    }

    /**
     * Is rendered?
     *
     * This function tells if view is rendered.
     * @access public
     * @return boolean
     */
    public function render()
    {
        return $this->_render;
    }

    /**
     * Set layout rendered.
     *
     * This function sets if layout is rendered.
     * @access public
     * @param boolean $value
     */
    public function setRenderLayout($value)
    {
        $this->_layout = $value;
        return;
    }

    /**
     * Is layout rendered?
     *
     * This function tells if view is rendered.
     * @access public
     * @return boolean
     */
    public function renderLayout()
    {
        return $this->_layout;
    }

    /**
     * Get variable.
     *
     * This is implementation for PHP's magic method __get to get view
     * variables.
     * @access public
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return isset($this->_variables[$name]) ? $this->_variables[$name] : null;
    }

    /**
     * Set variable.
     *
     * This is implementation for PHP's magic method __set to set view
     * variables.
     * @access public
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->_variables[$name] = $value;
    }

}
