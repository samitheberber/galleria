<?php
/**
 * Auto configure class file.
 * 
 * This file contains Galleria_Auto_Configure class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package lib
 */

/**
 * Auto configuration class.
 *
 * This class handles automatic all configurations.
 * @name Galleria_Auto_Configure
 * @package lib
 * @subpackage auto
 */
class Galleria_Auto_Configure
{

    /**
     * Configure selected files.
     *
     * Function configures selected files from selected path. Returns state of
     * success.
     * @access public
     * @static
     * @param array $elements
     * @return boolean
     */
    public static function configFiles($elements)
    {
        $configuration = array();
        foreach ($elements as $element) {
            $configDataDist = self::_distFileData($element);
            $configDataLocal = self::_userFileData($element);
            $configData = array_merge( (array) $configDataDist, (array) $configDataLocal );
            $configuration[$element] = $configData;
        }
        return Galleria_Configuration::set($configuration);
    }

    /**
     * Does file exist?
     *
     * Function checks if file exists and return it content or if it doesn't
     * exists it returns empty array.
     * @access private
     * @static
     * @param string $name
     * @return array
     */
    private static function _fileExists($name)
    {
        return (file_exists($name)) ? require_once $name : array();
    }

    /**
     * Gets distribution file data.
     *
     * Function combines requested information and returns file exists function.
     * @access private
     * @static
     * @param string $element
     * @uses PATH_DATA_CONFIG
     * @return array
     */
    private static function _distFileData($element)
    {
        return self::_fileExists(PATH_DATA_CONFIG . '/' . $element . '.dist.php');
    }

    /**
     * Gets local file data.
     *
     * Function combines requested information and returns file exists function.
     * @access private
     * @static
     * @param string $element
     * @uses PATH_DATA_CONFIG
     * @return array
     */
    private static function _userFileData($element)
    {
        return self::_fileExists(PATH_DATA_CONFIG . '/' . $element . '.php');
    }

}
