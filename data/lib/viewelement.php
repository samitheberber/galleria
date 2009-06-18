<?php
/**
 * File for View element class.
 * 
 * This file contains View element class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package lib
 */

/**
 * Class for View element.
 *
 * This class initializes view elements.
 * @name Galleria_Viewelement
 * @package lib
 * @subpackage view
 */
class Galleria_Viewelement
{

    /**
     * @access private
     * @var string $_http_url
     */
    protected static $_http_url;

    /**
     * Initialize html root.
     *
     * This function initializes html root of view elements.
     * @access public
     * @static
     * @param string $html
     */
    public static function init($html)
    {
        self::$_http_url = $html;
    }

}
