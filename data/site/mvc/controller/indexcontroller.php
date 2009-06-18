<?php
/**
 * File for Index controller class.
 *
 * This file contains Index controller class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package mvc
 */

/**
 * Class of Index controller class.
 *
 * This is class for Index Controller.
 * @name IndexController
 * @package mvc
 * @subpackage controller
 */
class IndexController extends Galleria_Controller
{

    /**
     * Index page.
     *
     * Index page action. Sets up Index index page.
     * @access public
     */
    public function indexAction()
    {
        $this->getView()->pics = $this->_getModel()->getLatestPictures();
        $this->getView()->tags = $this->_getModel()->getTags();
        $this->getView()->stats = $this->_getModel()->getStats();
    }

}
