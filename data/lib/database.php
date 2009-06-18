<?php
/**
 * Database class file
 * 
 * This file contains Database class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package lib
 */

/**
 * Database class.
 *
 * Handles database functionality.
 * @name Galleria_Database
 * @package lib
 * @subpackage database
 */
class Galleria_Database
{

    /**
     * @access private
     * @static
     * @var Galleria_Database_Pdo
     */
    private static $_pdo = null;

    /**
     * @access private
     * @static
     * @var boolean
     */
    private static $_connected = false;

    /**
     * Initialize database functionality.
     *
     * This function initializes database functionality with configurations.
     * @access public
     * @static
     * @param array $configs
     */
    public static function init($configs)
    {
        try {
            self::$_pdo = new Galleria_Database_Pdo($configs);
            self::$_connected = true;
        } catch(Exception $e) {
            self::$_connected = false;
        }
    }

    /**
     * Gives PDO.
     *
     * This function gives correct PDO.
     * @access public
     * @static
     * @return Galleria_Database_Pdo
     */
    public static function getPDO()
    {
        return self::$_pdo;
    }

    /**
     * Is online?
     *
     * This function tells if database is online.
     * @access public
     * @static
     * @return boolean
     */
    public static function isOnline()
    {
        return self::$_connected;
    }

}
