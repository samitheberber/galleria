<?php
/**
 * Configuration class file.
 * 
 * This file contains Configuration class file.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package lib
 */

/**
 * Sets and gets configurations.
 *
 * Class handles all configuration settings and gettings.
 * @name Galleria_Configuration
 * @package lib
 * @subpackage configuration
 */
class Galleria_Configuration
{

    /**
     * Sets configurations.
     *
     * Function sets configuration in object and searialize it in session. State
     * of success is returned.
     * @access public
     * @static
     * @param array $configs
     * @return boolean
     */
    public static function set($configs)
    {
        return (boolean) Galleria_Session::set('configuration', serialize(new Galleria_Configuration_Object($configs)));
    }

    /**
     * Gets configurations.
     *
     * Function gets configuration object from session, unserialize it and gives
     * the configuration object.
     * @access public
     * @static
     * @return Galleria_Configuration_Object
     */
    public static function get()
    {
        return unserialize(Galleria_Session::get('configuration'));
    }

}
