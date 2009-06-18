<?php
/**
 * File for Galleria Requests class.
 *
 * File contains Galleria Requests.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package lib
 */

/**
 * Class for Galleria Requests.
 *
 * Class handles all requests.
 * @name Galleria_Requests
 * @abstract
 * @package lib
 * @subpackage requests
 */
class Galleria_Requests
{

    /**#@+
     * @access private
     */
    /**
     * @var Galleria_Requests_Gets
     */
    protected $_gets;
    /**
     * @var Galleria_Requests_Posts
     */
    protected $_posts;
    /**#@-*/

    /**
     * Creates requests.
     *
     * This is constructor for requests.
     * @access public
     * @param array $gets
     * @param array $posts
     */
    public function __construct($gets, $posts)
    {
        $this->_gets = ($gets) ? new Galleria_Requests_Gets($gets) : null;
        $this->_posts = ($posts) ? new Galleria_Requests_Posts($posts) : null;
    }

    /**
     * Gives gets.
     *
     * This function returns gets.
     * @access public
     * @return Galleria_Requests_Gets
     */
    public function gets()
    {
        return $this->_gets;
    }

    /**
     * Gives posts.
     *
     * This function returns posts.
     * @access public
     * @return Galleria_Requests_Posts
     */
    public function posts()
    {
        return $this->_posts;
    }

    /**
     * Has get?
     *
     * This function tells if gets are setted.
     * @access public
     * @return boolean
     */
    public function hasGet()
    {
        return !empty($this->_gets);
    }

    /**
     * Has posts?
     *
     * This function tells if posts are setted.
     * @access public
     * @return boolean
     */
    public function hasPost()
    {
        return !empty($this->_posts);
    }

}
