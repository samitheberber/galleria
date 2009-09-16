<?php
/**
 * File for Browse model class.
 *
 * This file contains Browse model class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package mvc
 */

/**
 * Class of Browse model class.
 *
 * This is class for Browse Model.
 * @name BrowseModel
 * @package mvc
 * @subpackage model
 */
class BrowseModel extends Galleria_Model_Collection
{

    /**
     * Gets pictures.
     *
     * This function gets pictures data from database.
     * @access public
     * @param integer $cid
     * @return array
     */
    public function getPictures($cid)
    {
        $pdo = $this->_getConnection();
        if (Galleria_Auth::isLogged() && Galleria_Auth::getObject()->hasGroups()) {
            if (Galleria_Auth::getObject()->isInAdminGroup()) {
                $sth = $pdo->prepare('SELECT DISTINCT pd.pid, pd.desc, pd.shown FROM galleria.picturedata AS pd WHERE pd.cid = ?');
                $sth->execute(array($cid));
            } else {
                $sth = $pdo->prepare('SELECT DISTINCT pd.pid, pd.desc FROM galleria.picturedata AS pd WHERE pd.cid = ? AND pd.shown = TRUE AND (pd.gid IS NULL OR pd.gid IN (' . $this->_prepareIn(Galleria_Auth::getObject()->getSQLGroups()) . '))');
                $sth->execute(array_merge(array($cid), Galleria_Auth::getObject()->getSQLGroups()));
            }
        } else {
            $sth = $pdo->prepare('SELECT DISTINCT pd.pid, pd.desc FROM galleria.picturedata AS pd WHERE pd.cid = ? AND pd.shown = TRUE AND pd.gid IS NULL');
            $sth->execute(array($cid));
        }
        return $this->_fetchObjects($sth);
    }

    /**
     * Gets categories.
     *
     * This function get categories data from database.
     * @access public
     * @return array
     */
    public function getCategories()
    {
        $usergroups = (Galleria_Auth::isLogged()) ? Galleria_Auth::getObject()->getUsergroups() : array();
        $pdo = $this->_getConnection();
        if (Galleria_Auth::isLogged() && Galleria_Auth::getObject()->hasGroups()) {
            if (Galleria_Auth::getObject()->isInAdminGroup()) {
                $sth = $pdo->prepare('SELECT DISTINCT cl.cid AS id, cl.cpar AS parent, cl.name, cl.haspics FROM galleria.categorylist AS cl');
                $sth->execute();
            } else {
                $sth = $pdo->prepare('SELECT DISTINCT cl.cid AS id, cl.cpar AS parent, cl.name, cl.haspics FROM galleria.categorylist AS cl WHERE cl.gid IS NULL OR cl.gid IN (' . $this->_prepareIn(Galleria_Auth::getObject()->getSQLGroups()) . ')');
                $sth->execute(Galleria_Auth::getObject()->getSQLGroups());
            }
        } else {
            $sth = $pdo->prepare('SELECT DISTINCT cl.cid AS id, cl.cpar AS parent, cl.name, cl.haspics FROM galleria.categorylist AS cl WHERE cl.gid IS NULL');
            $sth->execute();
        }
        return $this->_fetchObjects($sth);
    }

    /**
     * Gets name of category.
     *
     * This function gets name of specified category from database.
     * @access public
     * @param integer $id
     * @return string
     */
    public function getCatName($id)
    {
        $usergroups = (Galleria_Auth::isLogged()) ? Galleria_Auth::getObject()->getUsergroups() : array();
        $pdo = $this->_getConnection();
        if (Galleria_Auth::isLogged() && Galleria_Auth::getObject()->hasGroups()) {
            if (Galleria_Auth::getObject()->isInAdminGroup()) {
                $sth = $pdo->prepare('SELECT galleria.catpath(?) AS name');
                $state = $sth->execute(array($id));
            } else {
                $sth = $pdo->prepare('SELECT DISTINCT galleria.catpath(cl.cid) AS name FROM galleria.categorylist AS cl WHERE (cl.gid IS NULL OR cl.gid IN (' . $this->_prepareIn(Galleria_Auth::getObject()->getSQLGroups()) . ')) AND cl.cid = ?');
                $state = $sth->execute(array_merge(Galleria_Auth::getObject()->getSQLGroups(), array($id)));
            }
        } else {
            $sth = $pdo->prepare('SELECT DISTINCT galleria.catpath(cl.cid) AS name FROM galleria.categorylist AS cl WHERE (cl.gid IS NULL) AND cl.cid = ?');
            $state = $sth->execute(array($id));
        }
        return ($state) ? (($obj = $sth->fetchObject()) ? $obj->name : null) : null;
    }

}
