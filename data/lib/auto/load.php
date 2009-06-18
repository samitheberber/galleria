<?php
/**
 * Auto load class file.
 * 
 * This file contains Galleria_Auto_Load class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package lib
 */

/**
 * Auto load class.
 *
 * This class handles automatic all class calls.
 * @name Galleria_Auto_Load
 * @package lib
 * @subpackage auto
 */
class Galleria_Auto_Load
{

    /**
     * Starts class auto loading.
     *
     * This function starts automatic loading for all classes, which are called.
     * @access public
     * @static
     * @uses PATH_DATA_LIB
     */
    public static function start()
    {
        /* Load __autoload magic function */
        function __autoload($class_name)
        {
            $path = PATH_DATA_LIB . '/' . strtolower(str_replace('_', '/', str_replace('Galleria_', '', $class_name))) . '.php';
            if(file_exists($path)) {
                require_once $path;
            }
            return;
        }
    }

}
