<?php
/**
 * Image object class file
 * 
 * This file contains Image object class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package lib
 */

/**
 * Image object class.
 *
 * Contains image information.
 * @name Galleria_Image_Object
 * @package lib
 * @subpackage image
 */
class Galleria_Image_Object
{

    /**#@+
     * @access private
     */
    /**
     * @var integer $_id
     */
    protected $_id;
    /**
     * @var string $_pg
     */
    protected $_pg;
    /**
     * @var string $_year
     */
    protected $_year;
    /**
     * @var string $_desc
     */
    protected $_desc;
    /**
     * @var boolean $_shown
     */
    protected $_shown;
    /**
     * @var Galleria_Element_Category $_cat
     */
    protected $_cat;
    /**#@-*/

    /**
     * Creates image object.
     *
     * This is constructor of image object.
     * @access public
     * @param stdClass $obj
     */
    public function __construct(stdClass $obj)
    {
        $this->_id = $obj->pid;
        $this->_pg = $obj->pg;
        $this->_year = $obj->year;
        $this->_desc = $obj->desc;
        $this->_shown = isset($obj->shown) ? (boolean) $obj->shown : true;
        $this->_cat = new Galleria_Element_Category($obj->cid, $obj->cname);
    }

    /**
     * Gives id.
     *
     * This function returns id of image.
     * @access public
     * @return integer
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Gives alt-text.
     *
     * This function returns alternative text of image.
     * @access public
     * @return string
     */
    public function getAlt()
    {
        return ((strlen($this->_desc) > 20) ? htmlspecialchars(substr($this->_desc, 0, 17) . '...') : $this->_desc);
    }

    /**
     * Gives year.
     *
     * This function returns year of image.
     * @access public
     * @return string
     */
    public function getYear()
    {
        return $this->_year;
    }

    /**
     * Gives photographer.
     *
     * This function returns photographer of image.
     * @access public
     * @return integer
     */
    public function getPg()
    {
        return $this->_pg;
    }

    /**
     * Gives category.
     *
     * This function returns category of image.
     * @access public
     * @return integer
     */
    public function getCat()
    {
        return $this->_cat;
    }

    /**
     * Gives description.
     *
     * This function returns description of image.
     * @access public
     * @return integer
     */
    public function getDesc()
    {
        return $this->_desc;
    }

    /**
     * Is shown?
     *
     * This function tells if picture is shown.
     * @access public
     * @return boolean
     */
    public function isShown()
    {
        return $this->_shown;
    }

}
