<?php
/**
 * File for Tag model class.
 *
 * This file contains Tag model class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package mvc
 */

/**
 * Class of Tag model class.
 *
 * This is class for Tag Model.
 * @name TagModel
 * @package mvc
 * @subpackage model
 */
class TagModel extends Galleria_Model_Collection
{

    /**
     * Gives name of tag.
     *
     * This function gets name of specific tag from database.
     * @access public
     * @param integer $tagid
     * @return string
     */
    public function getTagName($tagid)
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare("SELECT t.name AS name FROM galleria.tags AS t WHERE t.id = ?");
        $sth->execute(array($tagid));
        return $sth->fetchObject()->name;
    }

    /**
     * Gives pictures.
     *
     * This function gets pictures from database according specified tag id.
     * @access public
     * @param integer $tagid
     * @return array
     */
    public function getPics($tagid)
    {
        $pdo = $this->_getConnection();
        if (Galleria_Auth::isLogged() && Galleria_Auth::getObject()->hasGroups()) {
            if (Galleria_Auth::getObject()->isInAdminGroup()) {
                $sth = $pdo->prepare('SELECT DISTINCT p.id AS pid, p.description AS desc, p.shown FROM galleria.pictures AS p JOIN galleria.picturetags AS pt ON pt.pictureid = p.id AND pt.tagid = ?');
                $sth->execute(array($tagid));
            } else {
                $sth = $pdo->prepare('SELECT DISTINCT pd.pid, pd.desc FROM galleria.picturedata AS pd JOIN galleria.picturetags AS pt ON pt.pictureid = pd.pid AND pt.tagid = ? WHERE (pd.gid IS NULL OR pd.gid IN (' . $this->_prepareIn(Galleria_Auth::getObject()->getSQLGroups()) . ')) AND pd.shown = TRUE');
                $sth->execute(array_merge(array($tagid), Galleria_Auth::getObject()->getSQLGroups()));
            }
        } else {
            $sth = $pdo->prepare('SELECT DISTINCT pd.pid, pd.desc FROM galleria.picturedata AS pd JOIN galleria.picturetags AS pt ON pt.pictureid = pd.pid AND pt.tagid = ? WHERE pd.gid IS NULL AND pd.shown = TRUE');
            $sth->execute(array($tagid));
        }
        return $this->_fetchObjects($sth);
    }

}
