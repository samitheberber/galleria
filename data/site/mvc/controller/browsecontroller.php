<?php
/**
 * File for Browse controller class.
 *
 * This file contains Browse controller class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package mvc
 */

/**
 * Class of Browse controller class.
 *
 * This is class for Browse Controller.
 * @name BrowseController
 * @package mvc
 * @subpackage controller
 */
class BrowseController extends Galleria_Controller
{

    /**
     * Index page.
     *
     * Index page action. Sets up Browse index page.
     * @access public
     */
    public function indexAction()
    {
        if ($this->_requests->hasGet()){
            $id = $this->_requests->gets()->id;
            $this->getView()->name = $this->_getModel()->getCatName($id);
            $this->getView()->pics = $this->_getModel()->getPictures($id);
        } else {
            $this->getView()->categories = Galleria_Element_Category::create($this->_getModel()->getCategories());
        }
    }

}
