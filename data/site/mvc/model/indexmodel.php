<?php
/**
 * File for Index model class.
 *
 * This file contains Index model class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package mvc
 */

/**
 * Class of Index model class.
 *
 * This is class for Index Model.
 * @name IndexModel
 * @package mvc
 * @subpackage model
 */
class IndexModel extends Galleria_Model_Collection
{

    /**
     * Give latest pictures.
     *
     * This function gets latest pictures from database.
     * @access public
     * @return array
     */
    public function getLatestPictures()
    {
        $pdo = $this->_getConnection();
        if (Galleria_Auth::isLogged() && Galleria_Auth::getObject()->hasGroups()) {
            if (Galleria_Auth::getObject()->isInAdminGroup()) {
                $sth = $pdo->prepare('SELECT DISTINCT pd.pid, pd.desc, pd.shown FROM galleria.picturedata AS pd ORDER BY pd.pid DESC LIMIT 8');
                $sth->execute();
            } else {
                $sth = $pdo->prepare('SELECT DISTINCT pd.pid, pd.desc FROM galleria.picturedata AS pd WHERE (pd.gid IS NULL OR pd.gid IN ('. $this->_prepareIn(Galleria_Auth::getObject()->getSQLGroups()) .')) AND pd.shown = TRUE ORDER BY pd.pid DESC LIMIT 8');
                $sth->execute(Galleria_Auth::getObject()->getSQLGroups());
            }
        } else {
            $sth = $pdo->prepare('SELECT DISTINCT pd.pid, pd.desc FROM galleria.picturedata AS pd WHERE pd.gid IS NULL AND pd.shown = TRUE ORDER BY pd.pid DESC LIMIT 8');
            $sth->execute();
        }
        return $this->_fetchObjects($sth);
    }

    /**
     * Give stats.
     *
     * This function gets some site relevate statistics.
     * @access public
     * @return stdClass
     */
    public function getStats()
    {
        $pdo = $this->_getConnection();
        if (Galleria_Auth::isLogged() && Galleria_Auth::getObject()->hasGroups()) {
            $sth = $pdo->prepare('SELECT (SELECT count(*) FROM galleria.pictures AS p WHERE p.id IN (SELECT DISTINCT pd.pid FROM galleria.picturedata AS pd WHERE pd.gid IS NULL OR pd.gid IN ('. $this->_prepareIn(Galleria_Auth::getObject()->getSQLGroups()) .')) AND p.shown = TRUE) AS yours, (SELECT count(*) FROM galleria.pictures) AS all');
            $sth->execute(Galleria_Auth::getObject()->getSQLGroups());
        } else {
            $sth = $pdo->prepare('SELECT (SELECT count(*) FROM galleria.pictures AS p WHERE p.id IN (SELECT DISTINCT pd.pid FROM galleria.picturedata AS pd WHERE pd.gid IS NULL) AND p.shown = TRUE) AS yours, (SELECT count(*) FROM galleria.pictures) AS all');
            $sth->execute();
        }
        return $sth->fetchObject();
    }

}
