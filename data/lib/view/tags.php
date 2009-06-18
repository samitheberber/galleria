<?php
/**
 * File for Tags view element class.
 * 
 * This file contains Tags view element class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package lib
 */

/**
 * Class for Tags view element.
 *
 * This class handles tags view element functionality.
 * @name Galleria_View_Tags
 * @package lib
 * @subpackage view
 */
class Galleria_View_Tags extends Galleria_Viewelement
{

    /**
     * Tags info.
     *
     * This function returns information about tags.
     * @access public
     * @static
     * @param array $tags
     * @return string
     */
    public static function info($tags)
    {
        $text = '<div id="tags">';
        foreach ($tags as $tag) {
            $text .= ' <span style="font-size: ' . self::_calcSize($tag->weight, $tag->sum) . '%;"><a href="' . self::$_http_url . 'tag/?id=' . $tag->tid . '">' . $tag->name . '</a></span>';
        }
        return $text . '</div>';
    }

    /**
     * Tags list.
     *
     * This function returns unordered list about tags.
     * @access public
     * @static
     * @param array $tags
     * @return string
     */
    public static function lists($tags, $pid)
    {
        $text = '<ul id="tags-' . $pid . '" class="picturetags">';
        foreach ($tags as $tag) {
            $text .= '<li><a href="' . self::$_http_url . 'tag/?id=' . $tag->tid . '" id="tag-' . $tag->tid . '">' . $tag->name . '</a></li>';
        }
        if (Galleria_Auth::isLogged() && Galleria_Auth::getObject()->isInAdminGroup())
            $text .= '<li class="addtag"><a href="#addpictag">[Add new tag]</a></li>';
        return $text . '</ul>';
    }

    /**
     * Calculate size.
     *
     * This function calculates correct size of tag.
     * @access private
     * @param integer $weight
     * @param integer $sum
     * @return integer
     */
    private static function _calcSize($weight, $sum)
    {
        return (100+ceil(10*$weight/$sum)*10);
    }

}
