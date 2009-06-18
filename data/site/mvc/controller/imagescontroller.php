<?php
/**
 * File for Images controller class.
 *
 * This file contains Images controller class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package mvc
 */

/**
 * Class of Images controller class.
 *
 * This is class for Images Controller.
 * @name ImagesController
 * @package mvc
 * @subpackage controller
 */
class ImagesController extends Galleria_Controller
{

    /**
     * Index page.
     *
     * Index page action. Sets up Image index page.
     * @access public
     */
    public function indexAction()
    {
        if ($this->_requests->hasGet()) {
            $id = $this->_requests->gets()->id;
            $this->getView()->pid = $id;
            $this->getView()->picData = $this->_getModel()->getImageInfo($id);
            $this->getView()->tags = $this->_getModel()->getImageTags($id);
        }
    }

    /**
     * File page.
     *
     * File page action. Sets up Image file page.
     * @access public
     */
    public function fileAction()
    {
        if ($this->_requests->hasGet()) {

            $id = $this->_requests->gets()->id;
            $size = $this->_requests->gets()->size;

            if ($this->_getModel()->checkPermission($id)) {
                $this->getView()->type = 'jpeg';
                $this->getView()->fileData = Galleria_Image::getData($id, $size);
                $this->getView()->filesize = Galleria_Image::getFilesize($id, $size);
            } elseif (!($this->_getModel()->fileFound($id) && Galleria_Image::fileFound($id))) {
                $this->getView()->type = 'png';
                $this->getView()->fileData = Galleria_Image::fileNotFound($size);
            } else {
                $this->getView()->type = 'png';
                $this->getView()->fileData = Galleria_Image::permissionDenied($size);
            }

        } else {
            $this->getView()->type = 'png';
            $this->getView()->fileData = Galleria_Image::fileNotFound();
        }
        $this->getView()->setRenderLayout(false); // Disable layout rendering
    }

}
