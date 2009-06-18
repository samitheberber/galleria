<?php
/**
 * File for Galleria Model class.
 *
 * File contains Galleria Model.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package lib
 */

/**
 * Class for Galleria Model.
 *
 * Class contains all model information.
 * @name Galleria_Model
 * @abstract
 * @package lib
 * @subpackage model
 */
abstract class Galleria_Model
{

    /**
     * Gives database connetion.
     *
     * This function gives database connection, the PDO object.
     * @access private
     * @return Galleria_Database_Pdo
     */
    protected function _getConnection()
    {
        return Galleria_Database::getPDO();
    }

    /**
     * Fetch objects.
     *
     * This function fetches objects and returns object array.
     * @access private
     * @param PDOStatement $state
     * @return array
     */
    protected function _fetchObjects($state)
    {
        $list = array();
        while ($obj = $state->fetchObject()) {
            $list[] = $obj;
        }
        return $list;
    }

    /**
     * Prepare in sentence.
     *
     * This function adds correct value of ?-marks in line for IN statement.
     * @access private
     * @param array $values
     * @return string
     */
    protected function _prepareIn($values)
    {
        $str = '';
        for ($i=0; $i<count($values)-1; $i++)
            $str .= '?, ';
        $str .= '?';
        return $str;
    }

}
