<?php
/**
 * File for Tag controller class.
 *
 * This file contains Tag controller class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package mvc
 */

/**
 * Class of Tag controller class.
 *
 * This is class for Tag Controller.
 * @name TagController
 * @package mvc
 * @subpackage controller
 */
class TagController extends Galleria_Controller
{

    /**
     * Index page.
     *
     * Index page action. Sets up Tag index page.
     * @access public
     */
    public function indexAction()
    {
        if ($this->_requests->hasGet()) {
            $tagid = $this->_requests->gets()->id;
            $this->getView()->tagname = $this->_getModel()->getTagName($tagid);
            $this->getView()->pics = $this->_getModel()->getPics($tagid);
        }
    }

}
