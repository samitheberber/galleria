<?php
/**
 * File for Pictures view element class.
 * 
 * This file contains Pictures view element class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package lib
 */

/**
 * Class for Pictures view element.
 *
 * This class handles pictures view element functionality.
 * @name Galleria_View_Pictures
 * @package lib
 * @subpackage view
 */
class Galleria_View_Pictures extends Galleria_Viewelement
{

    /**
     * Picture info.
     *
     * This function return picture information.
     * @access public
     * @static
     * @param array $pics
     * @return string
     */
    public static function info($pics)
    {
        $text .= '<div id="pics">';
        foreach ($pics as $pic) {
            $text .= '<a href="' . self::$_http_url . 'images/?id=' . $pic->pid . '"><img src="' . self::$_http_url . 'images/file/?size=s&amp;id=' . $pic->pid . '" alt="' . ((strlen($pic->desc) > 20) ? htmlspecialchars(substr($pic->desc, 0, 17)) . '...' : $pic->desc) . '" ' . ((isset($pic->shown) && !$pic->shown) ? ' class="hidden"' : '') .'/></a>';
        }
        return $text . '</div>';
    }

    /**
     * Format image.
     *
     * This function formats image.
     * @access public
     * @static
     * @param Galleria_Image_Object $imgObj
     * @return string
     */
    public static function format(Galleria_Image_Object $imgObj)
    {
        return '<img src="' . self::$_http_url . 'images/file/?id=' . $imgObj->getId() . '" alt="' . $imgObj->getAlt() . '" ' . (($imgObj->isShown()) ? '' : ' class="hidden"') .'/>';
    }

}
