<?php
/**
 * Auth file.
 * 
 * This file contains Galleria_Auth class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package lib
 */

/**
 * Authentication class.
 *
 * This class is used with authentication functionality.
 * @name Galleria_Auth
 * @package lib
 * @subpackage auth
 */
class Galleria_Auth
{

    /**
     * The logging function.
     *
     * This is the function, which should be used on logging in.
     * @access public
     * @static
     * @param string $username
     * @param string $password
     */
    public static function tryLog($username, $password)
    {
        $model = new Galleria_Auth_Model();
        $authObj = $model->logIn($username, $password);
        if ($authObj)
            Galleria_Session::set('auth', serialize($authObj));
        else
            self::logOut();
    }

    /**
     * Checks if already logged in.
     *
     * This function checks if user is already logged in and returns the
     * information.
     * @access public
     * @static
     * @return boolean
     */
    public static function isLogged()
    {
        $authObj = unserialize(Galleria_Session::get('auth'));
        return ($authObj) ? $authObj->logged() : false;
    }

    /**
     * Gives auth object.
     *
     * This function returns auth object.
     * @access public
     * @static
     * @return Galleria_Auth_Object
     */
    public static function getObject()
    {
        return unserialize(Galleria_Session::get('auth'));
    }

    /**
     * Logs out.
     *
     * This function logs out by deleting auth object from session.
     * @access public
     * @static
     */
    public static function logOut()
    {
        Galleria_Session::del('auth');
    }

    /**
     * Initialize auth object.
     *
     * This function gets new information for auth object and updates it.
     * @access public
     * @static
     */
    public static function init()
    {
        if (self::isLogged()) {
            $model = new Galleria_Auth_Model();
            $authObj = $model->getData(self::getObject());
            if ($authObj)
                Galleria_Session::set('auth', serialize($authObj));
            else
                self::logOut();
        }
    }

}
