<?php
/**
 * File for Admin controller class.
 *
 * This file contains admin controller class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package mvc
 */

/**
 * Class of admin controller.
 *
 * This class handles admin related controlling.
 * @name AdminController
 * @package mvc
 * @subpackage controller
 */
class AdminController extends Galleria_Controller
{

    /**
     * Index page.
     *
     * Index page action. Sets up Admin index page.
     * @access public
     */
    public function indexAction()
    {
    }

    /**
     * User control page.
     *
     * This page handles all user and group related functionality.
     * @access public
     */
    public function usercontrolAction()
    {
        if ($this->_requests->gets() && is_string($this->_requests->gets()->newuser) && $posts = $this->_requests->posts()) {
            $newuser = $this->_getModel()->addNewUser($posts->name, $posts->username, hash('sha512', $posts->password));
            if ($newuser) {
                $this->getView()->newuser = $newuser;
            } else {
                $this->getView()->newuserfailed = true;
            }
        }
        if ($this->_requests->gets() && is_string($this->_requests->gets()->newgroup) && $posts = $this->_requests->posts()) {
            $newgroup = $this->_getModel()->addNewGroup($posts->name, (boolean) $posts->admin);
            if ($newgroup) {
                $this->getView()->newgroup = $newgroup;
            } else {
                $this->getView()->newgroupfailed = true;
            }
        }
        $this->getView()->pageTitle = 'User Control';
        $groups = $this->_getModel()->getGroups();
        $this->getView()->users = $this->_modifyUsers($this->_getModel()->getUsers(), $groups);
        $this->getView()->groups = $groups;
    }

    /**
     * Picture control page.
     *
     * This function handles inserted pictures.
     * @access public
     */
    public function picturecontrolAction()
    {
        $this->getView()->pageTitle = 'Pictures Control';
        if ($posts = $this->_requests->posts()) {
            $this->_insertPicture($posts->picturefile);
        }
        $this->getView()->pics = $this->_getModel()->getPics();
    }

    /**
     * Do these before.
     *
     * This function checks if user has right to be in these pages.
     * @access public
     * @return boolean
     */
    public function before()
    {
        if (Galleria_Auth::isLogged()) {
            if (Galleria_Auth::getObject()->isInAdminGroup()) {
                return true;
            }
        }
        $this->setNext('login','index');
        return false;
    }

    /**
     * Modify users.
     *
     * This function formats users.
     * @access private
     * @param array $users
     * @param array $groups
     * @return array
     */
    protected function _modifyUsers($users, $groups)
    {
        $userArr = array();
        foreach ($users as $user) {
            if (isset($userArr[$user->uid])) {
                $userArr[$user->uid]['groups'][] = $this->_modifyGroups($user->gid, $groups);
            } else {
                $userArr[$user->uid] = array(
                    'id' => $user->uid,
                    'username' => $user->username,
                    'name' => $user->name,
                    'aclass' => ($user->active) ? 'active' : 'inactive',
                    'groups' => array($this->_modifyGroups($user->gid, $groups))
                );
            }
        }
        $ready = array();
        foreach ($userArr as $user) {
            if ($user['groups'][0] == null)
                unset($user['groups']);
            $ready[] = (object) $user;
        }
        return $ready;
    }

    /**
     * Modifies groups.
     *
     * This function formats groups.
     * @access private
     * @param integer $gid
     * @param array $groups
     * @return stdClass
     */
    protected function _modifyGroups($gid, $groups)
    {
        $selected = null;
        foreach ($groups as $group) {
            if ($group->id == $gid) {
                $selected = $group;
                break;
            }
            continue;
        }
        return $selected;
    }

    /**
     * Insert picture.
     *
     * This function inserts picture into tmp file.
     * @access private
     * @param array $picdata
     * @uses PATH_DATA_SITE_IMAGES
     */
    protected function _insertPicture($picdata)
    {
        if (is_uploaded_file($picdata['tmp_name'])) {
            if ($picdata['type'] == 'image/jpeg') {
                $picinfo = $this->_imageInfo($picdata['tmp_name']);
                if ($pid = $this->_getModel()->addPicture($picinfo->pg, $picinfo->year, $picdata['name']))
                    move_uploaded_file($picdata['tmp_name'], PATH_DATA_SITE_IMAGES . '/tmp/' . md5($pid) . $picinfo->filetype);
                ob_start();
                @Galleria_Image::transform();
                $this->getView()->info = ob_get_contents();
                ob_clean();
            } else
                $this->getView()->info = 'Unsupported mime-type.';
        } else
            $this->getView()->info = 'No file imported.';
    }

    /**
     * Gives image information.
     *
     * This function gives information about specific image.
     * @access private
     * @param string $file
     * @return stdClass
     */
    protected function _imageInfo($file)
    {
        $exif = exif_read_data($file);
        return (object) array(
            'pg' => (isset($exif['Model']) ? $exif['Model'] : 'Not available'),
            'year' => (isset($exif['DateTime']) ? substr($exif['DateTime'], 0, 4) : date('Y')),
            'filetype' => ((exif_imagetype($file) == IMAGETYPE_JPEG) ? '.jpg' : ((exif_imagetype($file) == IMAGETYPE_PNG) ? '.png' :''))
        );
    }

}
