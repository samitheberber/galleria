<?php
/**
 * File for Images model class.
 *
 * This file contains Images model class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package mvc
 */

/**
 * Class of Images model class.
 *
 * This is class for Images Model.
 * @name ImagesModel
 * @package mvc
 * @subpackage model
 */
class ImagesModel extends Galleria_Model_Collection
{

    /**
     * Gives image info.
     *
     * This function gets image information from database and returns it.
     * @access public
     * @param integer $pid
     * @return Galleria_Image_Object
     */
    public function getImageInfo($pid)
    {
        $pdo = $this->_getConnection();
        if (Galleria_Auth::isLogged() && Galleria_Auth::getObject()->hasGroups()) {
            if (Galleria_Auth::getObject()->isInAdminGroup()) {
                $sth = $pdo->prepare('SELECT DISTINCT pd.pid, pd.pg, pd.year, pd.desc, pd.cid, pd.cname, pd.shown FROM galleria.picturedata AS pd WHERE pd.pid = ?');
                $sth->execute(array($pid));
            } else {
                $sth = $pdo->prepare('SELECT DISTINCT pd.pid, pd.pg, pd.year, pd.desc, pd.cid, pd.cname FROM galleria.picturedata AS pd WHERE pd.pid = ? AND pd.shown = TRUE AND (pd.gid IS NULL OR pd.gid IN (' . $this->_prepareIn(Galleria_Auth::getObject()->getSQLGroups()) . '))');
                $sth->execute(array_merge(array($pid), Galleria_Auth::getObject()->getSQLGroups()));
            }
        } else {
            $sth = $pdo->prepare('SELECT DISTINCT pd.pid, pd.pg, pd.year, pd.desc, pd.cid, pd.cname FROM galleria.picturedata AS pd WHERE pd.pid = ? AND pd.shown = TRUE AND pd.gid IS NULL');
            $sth->execute(array($pid));
        }
        $obj = $sth->fetchObject();
        return ($obj) ? new Galleria_Image_Object($obj) : null;
    }

    /**
     * Gives image tags.
     *
     * This function gets image related tags from database and retruns them.
     * @access public
     * @param integer $pid
     * @return array
     */
    public function getImageTags($pid)
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare('SELECT top.tid, top.name FROM galleria.tagsofpictures AS top WHERE top.pid = ?');
        $sth->execute(array($pid));
        return $this->_fetchObjects($sth);
    }

    /**
     * Is file found?
     *
     * This function checks if file is set in database.
     * @access public
     * @param integer $pid
     * @return boolean
     */
    public function fileFound($pid)
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare('SELECT p.id FROM galleria.pictures AS p WHERE p.id = ?');
        $sth->execute(array($pid));
        return ($this->_fetchObjects($sth));
    }

    /**
     * Check permission.
     *
     * This function checks if user has permission to see selected picture.
     * @access public
     * @param integer $pid
     * @return boolean
     */
    public function checkPermission($pid)
    {
        $pdo = $this->_getConnection();
        if (Galleria_Auth::isLogged() && Galleria_Auth::getObject()->hasGroups()) {
            if (Galleria_Auth::getObject()->isInAdminGroup()) {
                $sth = $pdo->prepare('SELECT DISTINCT pd.pid FROM galleria.picturedata AS pd WHERE pd.pid = ?');
                $sth->execute(array($pid));
            } else {
                $sth = $pdo->prepare('SELECT DISTINCT pd.pid FROM galleria.picturedata AS pd WHERE pd.pid = ? AND pd.shown = TRUE AND (pd.gid IS NULL OR pd.gid IN (' . $this->_prepareIn(Galleria_Auth::getObject()->getSQLGroups()) . '))');
                $sth->execute(array_merge(array($pid), Galleria_Auth::getObject()->getSQLGroups()));
            }
        } else {
            $sth = $pdo->prepare('SELECT DISTINCT pd.pid FROM galleria.picturedata AS pd WHERE pd.pid = ? AND pd.shown = TRUE AND pd.gid IS NULL');
            $sth->execute(array($pid));
        }
        $obj = $sth->fetchObject();
        return ($obj);
    }

}
