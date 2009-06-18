<?php
/**
 * File for Sessions class.
 * 
 * This file contains Session class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package lib
 */

/**
 * Initialize session here
 */
session_name('galleria');
session_cache_expire(180);
session_save_path(PATH_DATA_SITE_SESSIONS);
session_start();

/**
 * Class for Sessions.
 *
 * This class handles session functionality.
 * @name Galleria_Session
 * @package lib
 * @subpackage session
 */
class Galleria_Session
{

    /**
     * Sets session variable.
     *
     * This function inserts value for named variable.
     * @access public
     * @static
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public static function set($name, $value)
    {
        return $_SESSION[$name] = $value;
    }

    /**
     * Gets session variable.
     *
     * This function gives value of session variable.
     * @access public
     * @static
     * @param string $name
     * @return mixed
     */
    public static function get($name)
    {
        return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
    }

    /**
     * Deletes session variable.
     *
     * This function deletes variable from session.
     * @access public
     * @static
     * @param string $name
     * @return boolean
     */
    public static function del($name)
    {
        return session_unset($name);
    }

}
