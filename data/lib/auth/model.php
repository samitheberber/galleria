<?php
/**
 * Auth Model file.
 * 
 * This file contains Galleria_Auth_Model class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package lib
 */

/**
 * Authentication model class.
 *
 * This class is used with authentication database functionality.
 * @name Galleria_Auth_Model
 * @package lib
 * @subpackage auth
 */
class Galleria_Auth_Model extends Galleria_Model
{

    /**
     * Gets user information and reformalize it.
     *
     * Uses username and password to get user information from database and
     * saves that info in authentication object.
     * @access public
     * @param string $uname
     * @param string $pword
     * @return Galleria_Auth_Object
     */
    public function logIn($uname, $pword)
    {
        $userdataAr = $this->_getUserData($uname, $pword);
        if (empty($userdataAr))
            return null;
        $userdataO = $userdataAr[0];
        $id = $userdataO->id;
        $username = $userdataO->username;
        $name = $userdataO->name;
        $groups = $this->_getGroups($id);
        return new Galleria_Auth_Object($id, $username, $groups, $name);
    }

    /**
     * Gets user information from database.
     *
     * This function does the database query and returns database query result.
     * @access private
     * @param string $username
     * @param string $password
     * @return stdClass
     */
    protected function _getUserData($username, $password)
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare("SELECT u.id AS id, u.username AS username, u.name AS name FROM galleria.users AS u WHERE u.username = ? AND u.password = ? AND u.active = TRUE");
        $sth->execute(array($username, $password));
        return $this->_fetchObjects($sth);
    }

    /**
     * Gets groups of user.
     *
     * This function returns groups of user from database.
     * @access private
     * @param integer $userid
     * @return array
     */
    protected function _getGroups($userid)
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare("SELECT gou.gid, gou.name, gou.adm FROM galleria.groupsofuser AS gou WHERE gou.uid = ?");
        $sth->execute(array($userid));
        $groups = array();
        $gObjs = $this->_fetchObjects($sth);
        foreach ($gObjs as $gObj) {
            $groups[$gObj->gid] = array($gObj->name, $gObj->adm);
        }
        return $groups;
    }

    /**
     * Updates object.
     *
     * This function updates existing auth object with new information from
     * database.
     * @access public
     * @param Galleria_Auth_Object $object
     * @return Galleria_Auth_Object
     */
    public function getData($object)
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare("SELECT u.username AS username, u.name AS name FROM galleria.users AS u WHERE u.id = ? AND u.active = TRUE");
        $sth->execute(array($object->getUid()));
        $resObj = $sth->fetchObject();
        if (empty($resObj))
            return null;
        $object->update($resObj->username, $this->_getGroups($object->getUid()), $resObj->name);
        return $object;
    }

}
