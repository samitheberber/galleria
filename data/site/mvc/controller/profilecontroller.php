<?php
/**
 * File for Profile controller class.
 *
 * This file contains Profile controller class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package mvc
 */

/**
 * Class of Profile controller class.
 *
 * This is class for Profile Controller.
 * @name ProfileController
 * @package mvc
 * @subpackage controller
 */
class ProfileController extends Galleria_Controller
{

    /**
     * Profile page.
     *
     * This gives profile page.
     * @access public
     */
    public function indexAction()
    {
        if ($this->_requests->hasGet() && $this->_requests->gets()->action == 'changepw') {
            if ($this->_requests->hasPost() && ($pw = $this->_requests->posts()->password) && ($pwrt = $this->_requests->posts()->passwordrt) && ($pw == $pwrt)) {
                $this->getView()->pwc = $this->_getModel()->changePw(hash('sha512', $pw), Galleria_Auth::getObject()->getUid());
            } else
                $this->getView()->pwc = false;
        }
        $this->getView()->userData = Galleria_Auth::getObject();
        $this->getView()->userPics = $this->_getModel()->getUserPics(Galleria_Auth::getObject()->getUid());
    }

    /**
     * Do this before.
     *
     * This function checks if user is logged in to show his&hre profile.
     * @access public
     * @return boolean
     */
    public function before()
    {
        if (Galleria_Auth::isLogged())
            return true;
        $this->setNext('login','index');
        return false;
    }

}
