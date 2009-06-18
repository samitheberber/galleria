<?php
/**
 * Auth Object file.
 * 
 * This file contains Galleria_Auth_Object class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package lib
 */

/**
 * Authentication object class.
 *
 * This class is used with authentication information saving.
 * @name Galleria_Auth_Object
 * @package lib
 * @subpackage auth
 */
class Galleria_Auth_Object
{

    /**#@+
     * @access private
     */
    /**
     * User id
     * @var integer
     */
    protected $_id;
    /**
     * User account name
     * @var string
     */
    protected $_username;
    /**
     * Usergroups
     * @var array
     */
    protected $_usergroups;
    /**
     * User name
     * @var string
     */
    protected $_name;
    /**#@-*/

    /**
     * Creates object.
     *
     * This function is the constructor of Galleria_Auth_Object.
     * @access public
     * @param integer $id
     * @param string $username
     * @param array $usergroups
     * @param string $name
     */
    public function __construct($id, $username, $usergroups, $name)
    {
        $this->_id = $id;
        $this->_username = $username;
        $this->_usergroups = $usergroups;
        $this->_name = $name;
    }

    /**
     * Is user logged in?
     *
     * Returns information about if user is logged in.
     * @access public
     * @return boolean
     */
    public function logged()
    {
        return !empty($this->_username);
    }

    /**
     * Is user in admin group?
     *
     * Returns information about if user is administrator.
     * @access public
     * @return boolean
     */
    public function isInAdminGroup()
    {
        $state = false;
        foreach ($this->_usergroups as $group) {
            if ($group[1]) {
                $state = true;
            }
        }
        return $state;
    }

    /**
     * Gives user id.
     *
     * Returns id of user.
     * @access public
     * @return integer
     */
    public function getUid()
    {
        return $this->_id;
    }

    /**
     * Gives groups of user.
     *
     * Returns all groups where user is in.
     * @access public
     * @return array
     */
    public function getUsergroups()
    {
        $groups = array();
        foreach ($this->_usergroups as $group) {
            $groups[] = $group[0];
        }
        return $groups;
    }

    /**
     * Gives groups of user.
     *
     * Return all groups where user is in, but in sql-related string.
     * @access public
     * @return string
     */
    public function getSQLGroups()
    {
        return array_keys($this->_usergroups);
    }

    /**
     * Has groups?
     *
     * Return boolean for if user has groups.
     * @access public
     * @return boolean
     */
    public function hasGroups()
    {
        return !empty($this->_usergroups);
    }

    /**
     * Updates object.
     *
     * Updates this object with new values, that could be changed.
     * @access public
     * @param string $username
     * @param array $groups
     * @param string $name
     */
    public function update($username, $groups, $name)
    {
        $this->_username = $username;
        $this->_usergroups = $groups;
        $this->_name = $name;
    }

    /**
     * Give name.
     *
     * This function returns name of current user.
     * @access public
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Give username.
     *
     * This function returns username of current user.
     * @access public
     * @return string
     */
    public function getUsername()
    {
        return $this->_username;
    }

}
