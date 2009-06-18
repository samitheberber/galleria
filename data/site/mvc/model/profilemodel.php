<?php
/**
 * File for Profile model class.
 *
 * This file contains Profile model class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package mvc
 */

/**
 * Class of Profile model class.
 *
 * This is class for Profile Model.
 * @name ProfileModel
 * @package mvc
 * @subpackage model
 */
class ProfileModel extends Galleria_Model_Collection
{

    /**
     * Change password.
     *
     * This function changes password for specific user.
     * @access public
     * @param string $pw
     * @param integer $uid
     * @return boolean
     */
    public function changePw($pw, $uid)
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare('UPDATE galleria.users SET password = ? WHERE id = ?');
        return $sth->execute(array($pw, $uid));
    }

    /**
     * Get pictures of user.
     *
     * This function returns specific user related pictures.
     * @access public
     * @param integer $uid
     * @return array|boolean
     */
    public function getUserPics($uid)
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare('SELECT DISTINCT p.id AS pid, p.description AS desc, p.shown FROM galleria.pictures AS p JOIN galleria.picturemodifies AS pm ON p.id = pm.pictureid AND pm.userid = ?');
        return ($sth->execute(array($uid))) ? (($obj = $this->_fetchObjects($sth)) ? $obj : false): false;
    }

}
