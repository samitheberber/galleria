<?php
/**
 * File for Galleria Model Collection class.
 *
 * File contains Galleria Model Collection.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package lib
 */

/**
 * Class for Galleria Model Collection.
 *
 * Class contains common galleria model functions.
 * @name Galleria_Model_Collection
 * @abstract
 * @package lib
 * @subpackage model
 */
abstract class Galleria_Model_Collection extends Galleria_Model
{

    /**
     * Gives searched pictures.
     *
     * This function gets searched pictures.
     * @access public
     * @param string $text
     * @param string $pg
     * @param string $year
     * @return array
     */
    public function getSearchPictures($text, $pg, $year)
    {
        if (empty($text) && empty($pg) && empty($year))
            return array();

        $pdo = $this->_getConnection();
        if (Galleria_Auth::isLogged() && Galleria_Auth::getObject()->hasGroups()) {
            $sth = $pdo->prepare('SELECT DISTINCT pd.pid, pd.desc FROM galleria.picturedata AS pd WHERE ( pd.gid IS NULL OR pd.gid IN (' . $this->_prepareIn(Galleria_Auth::getObject()->getSQLGroups()) . ') ) AND pd.pg ILIKE ? AND pd.year LIKE ? AND (pd.desc ILIKE ? OR pd.pid IN (SELECT pt.pictureid FROM galleria.picturetags AS pt JOIN galleria.tags AS t ON t.id = pt.tagid WHERE t.name ILIKE ?)) AND pd.shown = TRUE');
            $sth->execute(array_merge(Galleria_Auth::getObject()->getSQLGroups(), array('%' . $pg . '%', '%' . $year . '%', '%' . $text . '%', '%' . $text . '%')));
        } else {
            $sth = $pdo->prepare('SELECT DISTINCT pd.pid, pd.desc FROM galleria.picturedata AS pd WHERE pd.gid IS NULL AND pd.pg ILIKE ? AND pd.year LIKE ? AND (pd.desc ILIKE ? OR pd.pid IN (SELECT pt.pictureid FROM galleria.picturetags AS pt JOIN galleria.tags AS t ON t.id = pt.tagid WHERE t.name ILIKE ?)) AND pd.shown = TRUE');
            $sth->execute(array('%' . $pg . '%', '%' . $year . '%', '%' . $text . '%', '%' . $text . '%'));
        }
        return $this->_fetchObjects($sth);
    }

    /**
     * Gives categories.
     *
     * This function returns categories from database.
     * @access public
     * @return array
     */
    public function getCats()
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare('SELECT c.id, galleria.catpath(c.id) AS name, c.parent FROM galleria.categories AS c');
        $sth->execute();
        return $this->_fetchObjects($sth);
    }

    /**
     * Gives groups.
     *
     * This function returns group data from database.
     * @access public
     * @return array
     */
    public function getGroups()
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare('SELECT g.id, g.name, g.admin FROM galleria.groups AS g');
        $sth->execute();
        return $this->_fetchObjects($sth);
    }

    /**
     * Gives tags.
     *
     * This function gets tags from database.
     * @access public
     * @return array
     */
    public function getTags()
    {
        $pdo = $this->_getConnection();
        if (Galleria_Auth::isLogged() && Galleria_Auth::getObject()->hasGroups()) {
            if (Galleria_Auth::getObject()->isInAdminGroup()) {
                $sth = $pdo->prepare('SELECT DISTINCT * FROM galleria.tagdata');
                $sth->execute();
            } else {
                $sth = $pdo->prepare('SELECT DISTINCT td.* FROM galleria.tagdata AS td JOIN galleria.tagsofpictures AS top ON td.tid = top.tid JOIN galleria.picturedata AS pd ON top.pid = pd.pid WHERE (pd.gid IS NULL OR pd.gid IN ('. $this->_prepareIn(Galleria_Auth::getObject()->getSQLGroups()) .')) AND pd.shown = TRUE');
                $sth->execute(Galleria_Auth::getObject()->getSQLGroups());
            }
        } else {
            $sth = $pdo->prepare('SELECT DISTINCT td.* FROM galleria.tagdata AS td JOIN galleria.tagsofpictures AS top ON td.tid = top.tid JOIN galleria.picturedata AS pd ON top.pid = pd.pid WHERE pd.gid IS NULL AND pd.shown = TRUE');
            $sth->execute();
        }
        return $this->_fetchObjects($sth);
    }

    /**
     * Add to changelog.
     *
     * This function adds data to changelog of picture.
     * @access private
     * @param integer $uid
     * @param integer $pid
     * @param string $action
     * @return boolean
     */
    protected function _addChangelog($uid, $pid, $action)
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare('INSERT INTO galleria.picturemodifies (pictureid, userid, action) VALUES (?, ?, ?)');
        return $sth->execute(array($pid, $uid, $action));
    }

}
