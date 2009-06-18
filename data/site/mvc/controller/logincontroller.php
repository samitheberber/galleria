<?php
/**
 * File for Login controller class.
 *
 * This file contains Login controller class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package mvc
 */

/**
 * Class of Login controller class.
 *
 * This is class for Login Controller.
 * @name LoginController
 * @package mvc
 * @subpackage controller
 */
class LoginController extends Galleria_Controller
{

    /**
     * Index page.
     *
     * Index page action. Sets up Login index page.
     * @access public
     */
    public function indexAction()
    {
        if ($this->_requests->hasPost()) {
            $username = $this->_requests->posts()->{'username'};
            $password = $this->_requests->posts()->{'password'};
            Galleria_Auth::tryLog($username, hash('sha512', $password));
            $this->getView()->failed = true;
        }

        if (!Galleria_Auth::isLogged())
            $this->getView()->show_form = true;
        else
            $this->redirect('index', 'index');
    }

    /**
     * Out page.
     *
     * Out page action. Logs out.
     * @access public
     */
    public function outAction()
    {
        Galleria_Auth::logOut();
        $this->redirect('login', 'index');
    }

    /**
     * Do before.
     *
     * Does before any action.
     */
    public function before()
    {
        if (Galleria_Auth::isLogged() && $this->_action != 'out') {
            $this->setNext('index','index');
            return false;
        } else {
            return true;
        }
    }

}
