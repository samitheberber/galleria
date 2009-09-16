<?php
/**
 * The index of page.
 * 
 * This file catches all page requests and redirect them to bootstrap.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package data
 * @subpackage html
 */

try {
    /**
     * Loads bootstrap file
     */
    require '../bootstrap.php';
} catch (Exception $e) {
    echo $e->getMessage();
}
