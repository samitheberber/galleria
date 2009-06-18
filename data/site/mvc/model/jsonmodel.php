<?php
/**
 * File for Json model class.
 *
 * This file contains Json model class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package mvc
 */

/**
 * Class of Json model class.
 *
 * This is class for Json Model.
 * @name JsonModel
 * @package mvc
 * @subpackage model
 */
class JsonModel extends Galleria_Model_Collection
{

    /* User related */

    /**
     * Delete user.
     *
     * This function deletes user.
     * @access public
     * @param integer $uid
     * @return boolean
     */
    public function deleteUser($uid)
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare('DELETE FROM galleria.users WHERE id = ?');
        return $sth->execute(array($uid));
    }

    /**
     * Change user activity.
     *
     * This function grants and removes active status from user.
     * @access public
     * @param integer $uid
     * @param boolean $act
     * @return stdClass
     */
    public function userActivity($uid, $act)
    {
        $pdo = $this->_getConnection();
        if ($act)
            $sth = $pdo->prepare('UPDATE galleria.users SET active = TRUE WHERE id = ? RETURNING id');
        else
            $sth = $pdo->prepare('UPDATE galleria.users SET active = FALSE WHERE id = ? RETURNING id');
        return ($sth->execute(array($uid))) ? $sth->fetchObject() : null;
    }

    /**
     * Add user in group.
     *
     * This function adds user in group.
     * @access public
     * @param integer $uid
     * @param integer $gid
     * @return boolean
     */
    public function addUserInGroup($uid, $gid)
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare('INSERT INTO galleria.usergroups (userid, groupid) VALUES (?, ?)');
        return $sth->execute(array($uid, $gid));
    }

    /**
     * Delete user from group.
     *
     * This function deletes user from group.
     * @access public
     * @param integer $uid
     * @param integer $gid
     * @return boolean
     */
    public function delUserInGroup($uid, $gid)
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare('DELETE FROM galleria.usergroups WHERE userid = ? AND groupid = ?');
        return $sth->execute(array($uid, $gid));
    }

    /**
     * Give groups form user.
     *
     * This function returns groups where user is in.
     * @access public
     * @param integer $uid
     * @param boolean $involves
     * @return array
     */
    public function getGroupsFromUser($uid, $involves = true)
    {
        $pdo = $this->_getConnection();
        if ($involves) {
            $sth = $pdo->prepare('SELECT g.* FROM galleria.groups AS g WHERE g.id IN (SELECT ug.groupid FROM galleria.usergroups AS ug WHERE ug.userid = ?)');
        } else {
            $sth = $pdo->prepare('SELECT g.* FROM galleria.groups AS g WHERE g.id NOT IN (SELECT ug.groupid FROM galleria.usergroups AS ug WHERE ug.userid = ?)');
        }
        return ($sth->execute(array($uid))) ? $this->_fetchObjects($sth) : null;
    }

    /**
     * Save category groups.
     *
     * This function clears and adds new groups for category.
     * @access public
     * @param array $groups
     * @param integer $catid
     * @return boolean
     */
    public function saveCatGroups($groups, $catid)
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare('DELETE FROM galleria.categorygroups WHERE categoryid = ?');
        $status = $sth->execute(array($catid));
        foreach ($groups as $group) {
            $data = $this->_groupsToSqlString($catid, $group);
            $pdo = $this->_getConnection();
            $sth = $pdo->prepare($data->sql);
            if ($sth->execute($data->params))
                $status = true;
        }
        return $status;
    }

    /**
     * Toggle admin status.
     *
     * This function grants and removes admin status from group.
     * @access public
     * @param integer $gid
     * @param boolean $admin
     * @return stdClass
     */
    public function groupAdmin($gid, $admin)
    {
        $pdo = $this->_getConnection();
        if ($admin)
            $sth = $pdo->prepare('UPDATE galleria.groups SET admin = TRUE WHERE id = ? RETURNING id');
        else
            $sth = $pdo->prepare('UPDATE galleria.groups SET admin = FALSE WHERE id = ? RETURNING id');
        return ($sth->execute(array($gid))) ? $sth->fetchObject() : null;
    }

    /**
     * Delete group.
     *
     * This function deletes group.
     * @access public
     * @param integer $gid
     * @return boolean
     */
    public function deleteGroup($gid)
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare('DELETE FROM galleria.groups WHERE id = ?');
        return $sth->execute(array($gid));
    }

    /* Categories related */

    /**
     * Saves categories.
     *
     * This function saves the categories hierarchy.
     * @access public
     * @param array $cats
     * @return boolean
     */
    public function saveCats($cats)
    {
        $status = true;
        foreach ($cats as $cat) {
            $data = $this->_catsToSqlString($cat);
            $pdo = $this->_getConnection();
            $sth = $pdo->prepare($data->sql);
            if (!$sth->execute($data->params))
                $status = false;
        }
        return $status;
    }

    /**
     * Add category.
     *
     * This function creates category.
     * @access public
     * @param string $name
     * @return boolean
     */
    public function addCat($name)
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare('INSERT INTO galleria.categories (name) VALUES (?)');
        return $sth->execute(array($name));
    }

    /**
     * Delete category.
     *
     * This function deletes category.
     * @access public
     * @param integer $id
     * @return boolean
     */
    public function deleteCat($id)
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare('DELETE FROM galleria.categories WHERE id = ?');
        return $sth->execute(array($id));
    }

    /**
     * Gives category related groups.
     *
     * This function group data, which is related to specific category id.
     * @access public
     * @param integer $cid
     * @return array
     */
    public function getGroupsFromCat($cid)
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare('SELECT goc.gid, goc.name, goc.adm FROM galleria.groupsofcategory AS goc WHERE goc.cid = ?');
        $sth->execute(array($cid));
        return $this->_fetchObjects($sth);
    }

    /**
     * Category SQL string.
     *
     * This function creates SQL string for category update.
     * @access private
     * @param stdClass $cat
     * @return boolean
     */
    private function _catsToSqlString($cat)
    {
        return (object) array(
            'sql' => 'UPDATE galleria.categories SET parent = ?, weight = ? WHERE id = ?',
            'params' => array($cat->parent, $cat->weight, $cat->id)
        );
    }

    /**
     * Format category groups.
     *
     * This function format category id and groups id to categorygroups query.
     * @access private
     * @param integer $catid
     * @param integer $groupid
     * return stdClass
     */
    private function _groupsToSqlString($catid, $groupid)
    {
        return (object) array(
            'sql' => 'INSERT INTO galleria.categorygroups (categoryid, groupid) VALUES (?, ?)',
            'params' => array($catid, $groupid)
        );
    }

    /* Pictures related */

    /**
     * Change description.
     *
     * This function changes description of picture.
     * @access public
     * @param integer $pid
     * @param string $text
     * @return boolean
     */
    public function changeDesc($pid, $text)
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare('UPDATE galleria.pictures SET description = ? WHERE id = ?');
        return ($sth->execute(array($text, $pid))) ? $this->_addChangelog(Galleria_Auth::getObject()->getUid(), $pid, 'Changed description to "' . $text . '".') : false;
    }

    /**
     * Change photographer.
     *
     * This function changes photographer of picture.
     * @access public
     * @param integer $pid
     * @param string $text
     * @return boolean
     */
    public function changePg($pid, $text)
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare('UPDATE galleria.pictures SET photographer = ? WHERE id = ?');
        return ($sth->execute(array($text, $pid))) ? $this->_addChangelog(Galleria_Auth::getObject()->getUid(), $pid, 'Changed photographer to "' . $text . '".') : false;
    }

    /**
     * Change year.
     *
     * This function changes year of picture.
     * @access public
     * @param integer $pid
     * @param string $text
     * @return boolean
     */
    public function changeYear($pid, $text)
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare('UPDATE galleria.pictures SET year = ? WHERE id = ?');
        return ($sth->execute(array($text, $pid))) ? $this->_addChangelog(Galleria_Auth::getObject()->getUid(), $pid, 'Changed year to "' . $text . '".') : false;
    }

    /**
     * Change category.
     *
     * This function changes category for picture.
     * @access public
     * @param integer $pid
     * @param integer $cid
     * @return boolean
     */
    public function changeCat($pid, $cid)
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare('UPDATE galleria.pictures SET categoryid = ? WHERE id = ?');
        return ($sth->execute(array($cid, $pid))) ? $this->_addChangelog(Galleria_Auth::getObject()->getUid(), $pid, 'Changed category id to ' . $cid . '.') : false;
    }

    /**
     * Add tag.
     *
     * This function adds specific named tag to picture and creates tag if it
     * doesn't already exists.
     * @access public
     * @param integer $pid
     * @param string $name
     * @return integer|null
     */
    public function addTag($pid, $name)
    {
        if (!($tid = $this->_getTagId($name)))
            $tid = $this->_addNewTag($name);
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare('INSERT INTO galleria.picturetags (pictureid, tagid) VALUES (?, ?) RETURNING tagid AS tid');
        return ($sth->execute(array($pid, $tid))) ? ((($obj = $sth->fetchObject()) && $this->_addChangelog(Galleria_Auth::getObject()->getUid(), $pid, 'Added new tag "' . $name . '".')) ? $obj->tid : null) : null;
    }

    /**
     * Creates new tag.
     *
     * This function creates tag for specified name of tag.
     * @access private
     * @param string $name
     * @return integer|null
     */
    private function _addNewTag($name)
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare('INSERT INTO galleria.tags (name) VALUES (?) RETURNING id');
        return ($sth->execute(array($name))) ? (($obj = $sth->fetchObject()) ? $obj->id : null) : null;
    }

    /**
     * Gives tag id.
     *
     * This function return tag id, which belogns to specific name of tag.
     * @access private
     * @param string $name
     * @return integer|null
     */
    private function _getTagId($name)
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare('SELECT t.id FROM galleria.tags AS t WHERE t.name = ?');
        return ($sth->execute(array($name))) ? (($obj = $sth->fetchObject()) ? $obj->id : null) : null;
    }

    /**
     * Toggle picture shown.
     *
     * This function changes picture visibility between shown and hidden.
     * @access public
     * @param integer $pid
     * @param boolean $show
     * @return boolean
     */
    public function pictureVisibility($pid, $show)
    {
        $pdo = $this->_getConnection();
        if ($show) {
            $sth = $pdo->prepare('UPDATE galleria.pictures SET shown = TRUE WHERE id = ?');
        } else {
            $sth = $pdo->prepare('UPDATE galleria.pictures SET shown = FALSE WHERE id = ?');
        }
        return ($sth->execute(array($pid))) ? $this->_addChangelog(Galleria_Auth::getObject()->getUid(), $pid, 'Changed visibility to ' . (($show) ? 'shown' : 'hidden') . '.') : false;
    }

    /**
     * Delete picture tag.
     *
     * This function deletes tag from picture and if there are no relation
     * assigned to tag, it will be removed.
     * @access public
     * @param integer $pid
     * @param integer $tid
     * @return boolean
     */
    public function deletePicTag($pid, $tid)
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare('DELETE FROM galleria.picturetags WHERE pictureid = ? AND tagid = ?');
        if ($sth->execute(array($pid, $tid))) {
            $pdo = $this->_getConnection();
            $sth = $pdo->prepare('SELECT td.name FROM galleria.tagdata AS td WHERE td.tid = ?');
            if ($sth->execute(array($tid)) && ($obj = $sth->fetchObject()) && $this->_deleteEmptyTags()) {
                return $this->_addChangelog(Galleria_Auth::getObject()->getUid(), $pid, 'Removed tag ' . $obj->name . '.');
            } else
                return false;
        } else
            return false;
    }

    /**
     * Delete empty tags.
     *
     * This function deletes empty tags from database.
     * @access private
     * @return boolean
     */
    private function _deleteEmptyTags()
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare('DELETE FROM galleria.tags WHERE id in (SELECT tid FROM galleria.tagdata WHERE weight = 0)');
        return $sth->execute();
    }

    /**
     * Delete picture.
     *
     * This function deleted picture.
     * @access public
     * @param integer $pid
     * @return boolean
     */
    public function deletePic($pid)
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare('DELETE FROM galleria.pictures WHERE id = ?');
        return ($sth->execute(array($pid)) && $this->_deleteEmptyTags());
    }

    /**
     * Gives changelog.
     *
     * This function returns changelog for specified picture.
     * @access public
     * @param integer $pid
     * @return array
     */
    public function getChangelog($pid)
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare('SELECT cl.user, cl.action, cl.timestamp FROM galleria.changelog AS cl WHERE pid = ? ORDER BY cl.timestamp');
        $sth->execute(array($pid));
        return $this->_fetchObjects($sth);
    }

    /**
     * Gives changelog.
     *
     * This function returns changelog for specified picture.
     * @access public
     * @param integer $pid
     * @return array
     */
    public function getPgs()
    {
        $pdo = $this->_getConnection();
        $sth = $pdo->prepare('SELECT DISTINCT p.photographer AS name FROM galleria.pictures AS p');
        $sth->execute(array());
        return $this->_fetchObjects($sth);
    }

}
