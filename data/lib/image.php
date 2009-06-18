<?php
/**
 * Image class file
 * 
 * This file contains Image class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package lib
 */

/**
 * Image class.
 *
 * Handles all image functionality.
 * @name Galleria_Image
 * @package lib
 * @subpackage image
 */
class Galleria_Image
{

    /**
     * Get image data.
     *
     * This function includes the image data from file and returns it.
     * @access public
     * @static
     * @param integer $id
     * @param string $size
     * @return string|boolean
     * @uses PATH_DATA_SITE_IMAGES
     */
    public static function getData($id, $size = '')
    {
        return file_get_contents(PATH_DATA_SITE_IMAGES . '/' . (($size) ? $size : 'f') . '/' . md5($id) . '.jpg'); //FIXME
    }

    /**
     * Get image filesize.
     *
     * This function gets image filesize and returns it.
     * @access public
     * @static
     * @param integer $id
     * @param string $size
     * @return integer
     * @uses PATH_DATA_SITE_IMAGES
     */
    public static function getFilesize($id, $size = '')
    {
        return filesize(PATH_DATA_SITE_IMAGES . '/' . (($size) ? $size : 'f') . '/' . md5($id) . '.jpg'); //FIXME
    }

    /**
     * Get 404 image data.
     *
     * This function includes the 404 image data from file and returns it.
     * @access public
     * @static
     * @param string $size
     * @return string|boolean
     * @uses PATH_DATA_SITE_IMAGES
     */
    public static function fileNotFound($size = '')
    {
        return file_get_contents(PATH_DATA_SITE_IMAGES . '/error404' . (($size) ? '_' . $size : '') .'.png'); //FIXME
    }

    /**
     * Get 403 image data.
     *
     * This function includes the 403 image data from file and returns it.
     * @access public
     * @static
     * @param string $size
     * @return string|boolean
     * @uses PATH_DATA_SITE_IMAGES
     */
    public static function permissionDenied($size = '')
    {
        return file_get_contents(PATH_DATA_SITE_IMAGES . '/error403' . (($size) ? '_' . $size : '') .'.png'); //FIXME
    }

    /**
     * Does file found?
     *
     * This function checks if file exists.
     * @access public
     * @static
     * @param integer $id
     * @return boolean
     * @uses PATH_DATA_SITE_IMAGES
     */
    public static function fileFound($id)
    {
        return (file_exists(PATH_DATA_SITE_IMAGES . '/f/' . md5($id) . '.jpg') && file_exists(PATH_DATA_SITE_IMAGES . '/s/' . md5($id) . '.jpg'));
    }

    /**
     * Runs transformation.
     *
     * This function runs image transrofmation.
     * @uses PATH_DATA_SITE_IMAGES
     */
    public static function transform()
    {
        echo '<pre>';
        passthru(PATH_DATA_SITE_IMAGES . '/resize.sh ' . PATH_DATA_SITE_IMAGES);
        echo '</pre>';
    }

    /**
     * Delete files.
     *
     * This function delete files for specific picture id.
     * @access public
     * @param integer $pid
     * @uses PATH_DATA_SITE_IMAGES
     */
    public static function deleteFiles($pid)
    {
        unlink(PATH_DATA_SITE_IMAGES . '/f/' . md5($pid) . '.jpg');
        unlink(PATH_DATA_SITE_IMAGES . '/s/' . md5($pid) . '.jpg');
    }

}
