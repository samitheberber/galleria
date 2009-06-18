<?php
/**
 * Database Pdo class file
 * 
 * This file contains Database Pdo class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package lib
 */

/**
 * Database Pdo class.
 *
 * Handles database main functionality.
 * @name Galleria_Database_Pdo
 * @package lib
 * @subpackage database
 */
class Galleria_Database_Pdo extends PDO
{

    /**
     * Creates Pdo.
     *
     * This is constructor of Pdo.
     * @access public
     * @param array $configs
     */
    public function __construct($configs)
    {
        $dsn = $configs['pdo_driver'] . ':dbname=' . $configs['dbname'] . ';host=' . $configs['host'] . ';port=' . $configs['port'];
        $username = $configs['username'];
        $password = $configs['password'];
        parent::__construct($dsn, $username, $password);
    }

}
