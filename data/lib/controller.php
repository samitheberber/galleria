<?php
/**
 * Controller class file
 * 
 * This file contains Controller class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package lib
 */

/**
 * Controller class.
 *
 * This class is part of Model-View-Controller set. It controls sets and gets.
 * @name Galleria_Controller
 * @abstract
 * @package lib
 * @subpackage controller
 */
abstract class Galleria_Controller
{

    /**#@+
     * @access private
     */
    /**
     * @var string
     */
    protected $_action;
    /**
     * @var Galleria_Model
     */
    protected $_model;
    /**
     * @var Galleria_View
     */
    protected $_view;
    /**
     * @var Galleria_Requests
     */
    protected $_requests;
    /**
     * @var string
     */
    protected $_ncont;
    /**
     * @var string
     */
    protected $_nact;
    /**
     * @var boolean
     */
    protected $_redirect;
    /**#@-*/

    /**
     * Creates controller.
     *
     * This is contructor of Controller.
     * @access public
     * @param string $action
     * @param Galleria_Model $model
     * @param Galleria_Requests $requests
     */
    public function __construct($action, $model, $requests)
    {
        $this->_action = $action;
        $this->_model = $model;
        $this->_requests = $requests;
        $this->_view = new Galleria_View();
        $this->_ncont = null;
        $this->_nact = null;
        $this->_redirect = false;
    }

    /**
     * Gives model.
     *
     * This function gives controller related model.
     * @access private
     * @return Galleria_Model
     * @throws Exception
     */
    protected function _getModel()
    {
        if ($this->_model) {
            return $this->_model;
        } else {
            throw new Exception('Model not setted.');
        }
    }

    /**
     * Do before.
     *
     * These is done before actual action. Returned boolean indicates continuing
     * of controller.
     * @access public
     * @return boolean
     */
    public function before()
    {
        return true;
    }

    /**
     * Do after.
     *
     * These is done after actual action. Returned boolean indicates continuing
     * of controller.
     * @access public
     * @return boolean
     */
    public function after()
    {
        return true;
    }

    /**
     * Gives view.
     *
     * This function returns the view of controller.
     * @access public
     * @return Galleria_View
     */
    public function getView()
    {
        return $this->_view;
    }

    /**
     * Sets next.
     *
     * This function sets next controller and action, which will be runned.
     * @access public
     * @param string $cont
     * @param string $act
     */
    public function setNext($cont, $act)
    {
        $this->_ncont = $cont;
        $this->_nact = $act;
    }

    /**
     * Gives next controller.
     *
     * Gives next controller, which will be runned.
     * @access public
     * @return string
     */
    public function getPage()
    {
        return $this->_ncont;
    }

    /**
     * Gives next controller action.
     *
     * Gives next controller action, which will be runned.
     * @access public
     * @return string
     */
    public function getSubpage()
    {
        return $this->_nact;
    }

    /**
     * Sets redirection.
     *
     * This function not just tells next controller and action. It also tells to
     * stop further rendering and jump to totally new page.
     * @access public
     */
    public function redirect($cont, $act)
    {
        $this->setNext($cont, $act);
        $this->_redirect = true;
    }

    /**
     * Will redirect?
     *
     * Tells if redirection is set.
     * @access public
     * @return boolean
     */
    public function isRedirected()
    {
        return $this->_redirect;
    }

}
