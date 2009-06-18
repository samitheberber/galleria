<?php
/**
 * File for Search controller class.
 *
 * This file contains Search controller class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package mvc
 */

/**
 * Class of Search controller class.
 *
 * This is class for Search Controller.
 * @name SearchController
 * @package mvc
 * @subpackage controller
 */
class SearchController extends Galleria_Controller
{

    /**
     * Index page.
     *
     * Index page action. Sets up Search index page.
     * @access public
     */
    public function indexAction()
    {
        if ($this->_requests->hasGet()) {
            if ($this->_requests->gets()->getData == 'true') {
            } else {
                $text = $this->_requests->gets()->search_text;
                $pg = $this->_requests->gets()->search_pg;
                $year = (int) $this->_requests->gets()->search_year;
            }
            $this->getView()->stext = $text;
            $this->getView()->spg = $pg;
            $this->getView()->syear = $year;
            $this->getView()->data = $this->_getModel()->getSearchPictures($text, $pg, $year);
        }
    }

}
