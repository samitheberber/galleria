<?php
/**
 * Category element class file
 * 
 * This file contains Category element class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package lib
 */

/**
 * Category element class.
 *
 * Handles all category functionality.
 * @name Galleria_Element_Category
 * @package lib
 * @subpackage element
 */
class Galleria_Element_Category
{

    /**#@+
     * @access private
     */
    /**
     * @var integer
     */
    protected $_id;
    /**
     * @var integer
     */
    protected $_parent;
    /**
     * @var string
     */
    protected $_name;
    /**
     * @var array
     */
    protected $_children;
    /**
     * @var boolean
     */
    protected $_pics;
    /**#@-*/

    /**
     * Creates category element.
     *
     * This is contructor of category element.
     * @access public
     * @param integer $id
     * @param string $name
     * @param integer $parent
     * @param boolean $pics
     */
    public function __construct($id, $name, $parent = null, $pics = true)
    {
        $this->_id = $id;
        $this->_parent = ($parent) ? $parent : 0;
        $this->_name = $name;
        $this->_children = array();
        $this->_pics = $pics;
    }

    /**
     * Add child.
     *
     * This function adds child to this category.
     * @access public
     * @param Galleria_Element_Category $child
     */
    public function addChild(Galleria_Element_Category $child)
    {
        $this->_children[] = $child;
    }

    /**
     * Add children.
     *
     * This function adds children to this category.
     * @access public
     * @param array $children
     */
    public function addChildren($children)
    {
        $this->_children = array_merge($this->_children, $children);
    }

    /**
     * To string.
     *
     * This function converts category element to string.
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Gives name.
     *
     * This function gives name of category element.
     * @access public
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Gives id.
     *
     * This function gives id of category element.
     * @access public
     * @return integer
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Gives parent.
     *
     * This function gives parent of category element.
     * @access public
     * @return integer
     */
    public function getParent()
    {
        return $this->_parent;
    }

    /**
     * Has pictures?
     *
     * This function tells if category element has pictures.
     * @access public
     * @return boolean
     */
    public function hasPics()
    {
        return $this->_pics;
    }

    /**
     * Gives children.
     *
     * This function gives children of category element.
     * @access public
     * @return array
     */
    public function getChildren()
    {
        return $this->_children;
    }

    /**
     * Has children?
     *
     * This function tells if category element has children.
     * @access public
     * @return boolean
     */
    public function hasChildren()
    {
        return !empty($this->_children);
    }

    /**
     * Count.
     *
     * This function returns number of category elements following.
     * @access public
     * @return integer
     */
    public function getCount()
    {
        $count = 1;
        if ($this->hasChildren()) {
            foreach ($this->getChildren() as $child) {
                $count = $count + $child->getCount();
            }
        }
        return $count;
    }

    /**
     * Creates category element.
     *
     * This function creates category element tree from list of array.
     * @access public
     * @static
     * @param array $cats
     * @return array
     */
    public static function create($cats)
    {
        $orderedCats = array();
        foreach ($cats as $cat) {
            $orderedCats[] = new Galleria_Element_Category($cat->id, $cat->name, $cat->parent, (isset($cat->haspics) ? $cat->haspics : true));
        }
        return self::_appendChildren(0, $orderedCats);
    }

    /**
     * Appends children.
     *
     * This function appends children for specific category element.
     * @access private
     * @static
     * @param integer $parent
     * @param array $children
     * @return array
     */
    private static function _appendChildren($parent, $children)
    {
        $selected = array();
        $notselected = array();
        foreach ($children as $child) {
            if ($child->getParent() == $parent)
                $selected[] = $child;
            else
                $notselected[] = $child;
        }
        foreach ($selected as $schild) {
            $schild->addChildren(self::_appendChildren($schild->getId(), $notselected));
        }
        return $selected;
    }

    /**
     * How many?
     *
     * This function tells how many items are in hierarchy.
     * @access public
     * @static
     * @param array|Galleria_Element_Category $element
     * @return integer
     */
    public static function howMany($element)
    {
        if (is_array($element)) {
            $count = 0;
            foreach ($element as $cat) {
                $count = $count + $cat->getCount();
            }
        } else
            $count = $element->getCount();
        return $count;
    }

    /**
     * JSON format.
     *
     * This function creates specific format for this elements type.
     * @access public
     * @static
     * @param array|Galleria_Element_Category $element
     * @return array
     */
    public static function jsonFormat($element)
    {
        if (is_array($element)) {
            $result = array();
            foreach ($element as $cat) {
                $result[] = self::jsonFormat($cat);
            }
        } else {
            $specs = array(
                'id' => $element->getId(),
                'info' => array($element->getName())
            );
            if ($element->hasChildren())
                $specs['children'] = self::jsonFormat($element->getChildren());
            $result = (object) $specs;
        }
        return $result;
    }

}
