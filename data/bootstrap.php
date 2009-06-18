<?php
/**
 * Bootstrap, file that really launch everything.
 * 
 * This file really do everything, including configuring and page viewing.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package data
 */

/**
 * Assign the webroot.
 */
$webroot = '';
foreach(explode('/', $_SERVER['PHP_SELF'], -1) as $part) {
    $webroot .= $part . '/';
}

/**#@+
 * Constants
 *
 * @access public
 * @var string
 */
/**
 * The application root.
 * @name PATH_DATA
 */
define('PATH_DATA', dirname(__FILE__));
/**
 * The library root
 * @name PATH_DATA_LIB
 */
define('PATH_DATA_LIB', PATH_DATA . '/lib');
/**
 * The configuration root.
 * @name PATH_DATA_CONFIG
 */
define('PATH_DATA_CONFIG', PATH_DATA . '/config');
/**
 * The site related root.
 * @name PATH_DATA_SITE
 */
define('PATH_DATA_SITE', PATH_DATA . '/site');
/**
 * The session directory.
 * @name PATH_DATA_SITE_SESSIONS
 */
define('PATH_DATA_SITE_SESSIONS', PATH_DATA_SITE . '/sessions');
/**
 * The image root.
 * @name PATH_DATA_SITE_IMAGES
 */
define('PATH_DATA_SITE_IMAGES', PATH_DATA_SITE . '/images');
/**
 * The model-view-controller root.
 * @name PATH_DATA_SITE_MVC
 */
define('PATH_DATA_SITE_MVC', PATH_DATA_SITE . '/mvc');
/**
 * The model directory.
 * @name PATH_DATA_SITE_MVC_MODEL
 */
define('PATH_DATA_SITE_MVC_MODEL', PATH_DATA_SITE_MVC . '/model');
/**
 * The view directory.
 * @name PATH_DATA_SITE_MVC_VIEW
 */
define('PATH_DATA_SITE_MVC_VIEW', PATH_DATA_SITE_MVC . '/view');
/**
 * The view layout directory.
 * @name PATH_DATA_SITE_MVC_VIEW_LAYOUT
 */
define('PATH_DATA_SITE_MVC_VIEW_LAYOUT', PATH_DATA_SITE_MVC_VIEW . '/layout');
/**
 * The view scripts directory.
 * @name PATH_DATA_SITE_MVC_VIEW_SCRIPTS
 */
define('PATH_DATA_SITE_MVC_VIEW_SCRIPTS', PATH_DATA_SITE_MVC_VIEW . '/scripts');
/**
 * The controller directory.
 * @name PATH_DATA_SITE_MVC_CONTROLLER
 */
define('PATH_DATA_SITE_MVC_CONTROLLER', PATH_DATA_SITE_MVC . '/controller');
/**
 * The webdirectory root.
 * @name WWWROOT
 */
define('WWWROOT', $webroot);
/**#@-*/

/**
 * Autoload class function.
 */
require_once PATH_DATA_LIB . '/auto/load.php';
Galleria_Auto_Load::start();

/**
 * Load configuration files.
 */
Galleria_Auto_Configure::configFiles(array('site', 'database'));
Galleria_Database::init( Galleria_Configuration::get()->{'database'} );

if (Galleria_Database::isOnline()) {

    Galleria_Viewelement::init(WWWROOT);
    Galleria_Auth::init();

    /**
     * Do the rendering.
     */
    Galleria_Site::render();

} else {

    /**
     * Do the offline rendering.
     */
    Galleria_Site::offline();

}
