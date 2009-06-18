<?php
/**
 * File for Categories view element class.
 * 
 * This file contains Categories view element class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package lib
 */

/**
 * Class for Categories view element.
 *
 * This class handles categories view element functionality.
 * @name Galleria_View_Categories
 * @package lib
 * @subpackage view
 */
class Galleria_View_Categories extends Galleria_Viewelement
{

    /**
     * Category info.
     *
     * This function returns information about category.
     * @access public
     * @static
     * @param Galleria_Element_Category $cat
     * @return string
     */
    public static function info($cat)
    {
        if ($cat->hasPics())
            return '<a id="cat-' . $cat->getId() . '" href="' . self::$_http_url . 'browse/?id=' . $cat->getId() . '">' . $cat->getName() . '</a>';
        return '<span id="cat-' . $cat->getId() . '">' . $cat->getName() . '</span>';
    }

    /**
     * Category list.
     *
     * This function returns unordered list about categories.
     * @access public
     * @static
     * @param Galleria_Element_Category
     * @return string
     */
    public static function ulList($cats)
    {
        $text = '<ul>';
        foreach($cats as $cat) {
            $text .= '<li>' . self::info($cat);
            if ($cat->hasChildren()) {
                $text .= self::ulList($cat->getChildren());
            }
            $text .= '</li>';
        }
        return $text . '</ul>';
    }

    /**
     * Category admin list.
     *
     * This function returns unordered list about categories for administrators.
     * @access public
     * @static
     * @param Galleria_Element_Category
     * @return string
     */
    public static function adminList($cats, $class = null)
    {
        $text = empty($class) ? '<ul>' : '<ul class="' . $class . '">';
        foreach($cats as $cat) {
            $text .= '<li id="list_' . $cat->getId() . '">' . $cat->getName();
            if ($cat->hasChildren()) {
                $text .= self::adminList($cat->getChildren());
            }
            $text .= '</li>';
        }
        return $text . '</ul>';
    }

}
