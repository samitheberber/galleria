<?php
/**
 * File for Stats view element class.
 * 
 * This file contains stats view element class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package lib
 */

/**
 * Class for Stats view element.
 *
 * This class handles stats view element functionality.
 * @name Galleria_View_Stats
 * @package lib
 * @subpackage view
 */
class Galleria_View_Stats extends Galleria_Viewelement
{

    /**
     * Stats info.
     *
     * This function returns statistic informations.
     * @access public
     * @static
     * @param stdClass $statObj
     * @return string
     */
    public static function info(stdClass $statObj)
    {
        return 'You can see ' . self::_format($statObj->yours) . ' of all ' . self::_format($statObj->all) . '.';
    }

    /**
     * Format stats.
     *
     * This function formats stats.
     * @access private
     * @static
     * @param integet $count
     * @return string
     */
    protected static function _format($count)
    {
        return ($count <= 1) ? $count .' picture' : $count . ' pictures';
    }

}
