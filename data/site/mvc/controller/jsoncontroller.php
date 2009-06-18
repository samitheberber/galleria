<?php
/**
 * File for Json controller class.
 *
 * This file contains Json controller class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package mvc
 */

/**
 * Class of Json controller class.
 *
 * This is class for Json Controller.
 * @name JsonController
 * @package mvc
 * @subpackage controller
 */
class JsonController extends Galleria_Controller
{

    /**
     * Index page.
     *
     * Index page action. Sets up Json index page.
     * @access public
     */
    public function indexAction()
    {
        $this->getView()->json = json_encode(array(
            array(
                'page' => 'get',
                'data' => array('search', 'admin')
            ),
            array(
                'page' => 'post',
                'data' => array('newcat', 'deletecat', 'admin-cats')
            )
        ));
    }

    /**
     * Get page.
     *
     * Get page action. Handles all get requests.
     * @access public
     */
    public function getAction()
    {
        $json = json_encode(array());
        if ($this->_requests->hasGet()) {
            if ($this->_requests->gets()->data == 'search') {
                $text = ($this->_requests->gets()->text) ? str_replace('*', '%', str_replace('%', '', $this->_requests->gets()->text)) : null;
                $pg = ($this->_requests->gets()->pg) ? str_replace('*', '%', str_replace('%', '', $this->_requests->gets()->pg)) : null;
                $year = ($this->_requests->gets()->year) ? str_replace('*', '%', str_replace('%', '', $this->_requests->gets()->year)) : null;
                $json = array(
                    'terms' => array(
                        'text' => $text,
                        'pg' => $pg,
                        'year' => $year
                    ),
                    'data' => $this->_getModel()->getSearchPictures($text, $pg, $year)
                );
            } elseif ($this->_requests->gets()->data == 'tags') {
                if ($this->_requests->gets()->field == 'name') {
                    $tmp_arr = array();
                    foreach($this->_getModel()->getTags() as $tag)
                        $tmp_arr[] = $tag->name;
                    $json = $tmp_arr;
                } else {
                    $json = $this->_getModel()->getTags();
                }
            } elseif ($this->_requests->gets()->data == 'photographers') {
                $json = array();
                foreach($this->_getModel()->getPgs() as $pg)
                    $json[] = $pg->name;
            } elseif ($this->_requests->gets()->data == 'admin' && Galleria_Auth::isLogged() && Galleria_Auth::getObject()->isInAdminGroup()) {
                if ($this->_requests->gets()->subdata == 'cats') {
                    $cats = Galleria_Element_Category::create($this->_getModel()->getCats());
                    $json = (object) array(
                        'requestFirstIndex' => 0,
                        'firstIndex' => 0,
                        'count' => Galleria_Element_Category::howMany($cats),
                        'totalCount' => Galleria_Element_Category::howMany($cats),
                        'columns' => array('Name'),
                        'items' => Galleria_Element_Category::jsonFormat($cats)
                    );
                } elseif ($this->_requests->gets()->subdata == 'catgroups') {
                    $json = array(
                        'state' => 'success',
                        'groups' => $this->_getModel()->getGroupsFromCat($this->_requests->gets()->catid),
                        'allgroups' => $this->_getModel()->getGroups()
                    );
                } elseif ($this->_requests->gets()->subdata == 'usergroups') {
                    $json = array(
                        'state' => 'success',
                        'groupsin' => $this->_getModel()->getGroupsFromUser($this->_requests->gets()->userid),
                        'groupsout' => $this->_getModel()->getGroupsFromUser($this->_requests->gets()->userid, false)
                    );
                } elseif ($this->_requests->gets()->subdata == 'piccats') {
                    $json = $this->_getModel()->getCats();
                } elseif ($this->_requests->gets()->subdata == 'changelog' && ($pid = $this->_requests->gets()->pid)) {
                    $json = $this->_getModel()->getChangelog($pid);
                }
            }
        }
        $this->getView()->json = json_encode($json);
    }

    /**
     * Post page.
     *
     * Post page action. Handles all post requests.
     * @access public
     */
    public function postAction()
    {
        if (Galleria_Auth::isLogged() && Galleria_Auth::getObject()->isInAdminGroup() && $posts = $this->_requests->posts()) {
            if ($cat = $posts->newcat) {
                $result = $this->_getModel()->addCat($cat);
            } elseif ($cat = $posts->deletecat) {
                $result = $this->_getModel()->deleteCat($cat);
            } elseif ($gid = $posts->delgroup) {
                $result = $this->_getModel()->deleteGroup($gid);
            } elseif ($uid = $posts->deleteuser) {
                $result = $this->_getModel()->deleteUser($uid);
            } elseif ($pid = $posts->deletepic) {
                if ($result = $this->_getModel()->deletePic($pid))
                    Galleria_Image::deleteFiles($pid);
            } elseif ($gid = $posts->grantadmin) {
                $result = $this->_getModel()->groupAdmin($gid, true);
            } elseif ($gid = $posts->dropadmin) {
                $result = $this->_getModel()->groupAdmin($gid, false);
            } elseif ($uid = $posts->activateuser) {
                $result = $this->_getModel()->userActivity($uid, true);
            } elseif ($uid = $posts->deactivateuser) {
                $result = $this->_getModel()->userActivity($uid, false);
            } elseif ($pid = $posts->showpic) {
                $result = $this->_getModel()->pictureVisibility($pid, true);
            } elseif ($pid = $posts->hidepic) {
                $result = $this->_getModel()->pictureVisibility($pid, false);
            } elseif (($pid = $posts->changedesc) && ($text = $posts->text)) {
                $result = $this->_getModel()->changeDesc($pid, $text);
            } elseif (($pid = $posts->changepg) && ($text = $posts->text)) {
                $result = $this->_getModel()->changePg($pid, $text);
            } elseif (($pid = $posts->changeyear) && ($text = $posts->text)) {
                $result = $this->_getModel()->changeYear($pid, $text);
            } elseif (($pid = $posts->addtag) && ($text = $posts->text)) {
                $result = (count($text) <= 20) ? (($data = $this->_getModel()->addTag($pid, $text)) ? $data : false) : false;
            } elseif (($pid = $posts->changecat) && ($cid = $posts->cat)) {
                $result = $this->_getModel()->changeCat($pid, $cid);
            } elseif (($pid = $posts->deletepictag) && ($tid = $posts->tid)) {
                $result = $this->_getModel()->deletePicTag($pid, $tid);
            } elseif ($posts->data == 'catgroups') {
                $result = $this->_getModel()->saveCatGroups($this->_parseCatGroups($posts->groups), $posts->catid);
            } elseif ($posts->data == 'addusergroup') {
                $result = $this->_getModel()->addUserInGroup($posts->uid, $posts->gid);
            } elseif ($posts->data == 'delusergroup') {
                $result = $this->_getModel()->delUserInGroup($posts->uid, $posts->gid);
            } elseif ($cats = $posts->{'admin-cats'}) {
                $result = $this->_getModel()->saveCats($this->_parseCatsToArray($cats['items']));
            } else {
                $result = true;
            }
        } else {
            $result = false;
        }
        $this->getView()->result = json_encode($result);
    }

    /**
     * Do before.
     *
     * Does these things before any action. Unsets layout rendering.
     * @access public
     */
    public function before()
    {
        $this->getView()->setRenderLayout(false);
        return true;
    }

    /**
     * Parse category groups.
     *
     * This function parses groups for categories.
     * @access private
     * @param string $data
     * @return array
     */
    private function _parseCatGroups($data)
    {
        $groups = array();
        foreach (explode('&', $data) as $group) {
            $groups[] = (int) str_replace('groups=', '', $group);
        }
        return $groups;
    }

    /**
     * Parse Cats 2 Array.
     *
     * This function parses categories to array.
     * @access private
     * @param array $cats
     * @param integer $parent
     * @return array
     */
    private function _parseCatsToArray($cats, $parent = null)
    {
        $result = array();
        foreach ($cats as $weight => $cat) {
            $result[] = (object) array(
                'id' => (int) $cat['id'],
                'parent' => $parent,
                'weight' => $weight
            );
            if (isset($cat['children']))
                $result = array_merge($result, $this->_parseCatsToArray($cat['children'], (int) $cat['id']));
        }
        return $result;
    }

}
