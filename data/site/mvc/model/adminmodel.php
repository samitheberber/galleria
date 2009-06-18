<?php
/**
 * File for Admin model class.
 *
 * This file contains Admin model class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package mvc
 */

/**
 * Class of Admin model class.
 *
 * This is class for Admin Model.
 * @name AdminModel
 * @package mvc
 * @subpackage model
 */
class AdminModel extends Galleria_Model_Collection
{

    /**
     * Give groups.
     *
     * This function returns groups.
     * @access public
     * @return array
     */
    public function getGroups()
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare("SELECT g.id, g.name, g.admin FROM galleria.groups AS g");
        $sth->execute();
        return $this->_fetchObjects($sth);
    }

    /**
     * Give users.
     *
     * This function returns users.
     * @access public
     * @return array
     */
    public function getUsers()
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare("SELECT ud.uid, ud.username, ud.name, ud.active, ud.gid FROM galleria.userdata AS ud");
        $sth->execute();
        return $this->_fetchObjects($sth);
    }

    /**
     * Add new user.
     *
     * This function adds new user.
     * @access public
     * @param string $name
     * @param string $user
     * @param string $pass
     * @return stdClass
     */
    public function addNewUser($name, $user, $pass)
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare("INSERT INTO galleria.users (name, username, password) VALUES (?, ?, ?) RETURNING name");
        $sth->execute(array($name, $user, $pass));
        return $sth->fetchObject();
    }

    /**
     * Add new group.
     *
     * This function adds new group.
     * @access public
     * @param string $name
     * @param boolean $admin
     * @return stdClass
     */
    public function addNewGroup($name, $admin)
    {
        $pdo = $this->_getConnection();
        if ($admin)
            $sth = $pdo->prepare("INSERT INTO galleria.groups (name, admin) VALUES (?, TRUE) RETURNING name");
        else
            $sth = $pdo->prepare("INSERT INTO galleria.groups (name) VALUES (?) RETURNING name");
        $sth->execute(array($name));
        return $sth->fetchObject();
    }

    /**
     * Add new picture.
     *
     * This function add new picture.
     * @access public
     * @param string $pg
     * @param string $year
     * @param string $desc
     * @return boolean
     */
    public function addPicture($pg, $year, $desc)
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare("INSERT INTO galleria.pictures (photographer, year, description) VALUES (?, ?, ?) RETURNING id");
        return ($sth->execute(array($pg, $year, $desc))) ? (($pid = $sth->fetchObject()->id) ? (($this->_addChangelog(Galleria_Auth::getObject()->getUid(), $pid, 'Picture created.')) ? $pid : $pid) : null) : null;
    }

    /**
     * Give pictures.
     *
     * This function returns pictures, which are not in a group nor shown.
     * @access public
     * @return array
     */
    public function getPics()
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare("SELECT DISTINCT pd.pid, pd.desc, pd.shown FROM galleria.picturedata AS pd WHERE pd.cid IS NULL OR pd.shown = FALSE");
        $sth->execute();
        return $this->_fetchObjects($sth);
    }

}
