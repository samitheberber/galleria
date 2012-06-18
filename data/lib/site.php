<?php
/**
 * File for Site class.
 * 
 * This file contains Site class.
 * @author Sami Saada <saada@cs.helsinki.fi>
 * @version 0.1
 * @package lib
 */

/**
 * Class for Site.
 *
 * This class handles site functionality.
 * @name Galleria_Site
 * @package lib
 * @subpackage site
 */
class Galleria_Site
{

    /**#@+
     * @access private
     * @static
     */
    /**
     * @var string $_page
     */
    protected static $_page = 'index';
    /**
     * @var string $_subpage
     */
    protected static $_subpage = 'index';
    /**
     * @var Galleria_Requests $_requests
     */
    protected static $_requests = null;
    /**
     * @var Galleria_Model $_model
     */
    protected static $_model = null;
    /**
     * @var string $_viewpath
     */
    protected static $_viewpath = null;
    /**
     * @var Galleria_Controller $_controller
     */
    protected static $_controller = null;
    /**
     * @var Galleria_Controller $_controller
     */
    protected static $_layout = 'default.phtml';
    /**#@-*/

    /**
     * Render view.
     *
     * This function does the rendering operations.
     * @access public
     * @static
     */
    public static function render()
    {
        self::_parseParams();
        self::_quickRender();
        self::_redir();
        self::_activateView();
        return;
    }

    /**
     * Render offline view.
     *
     * This function does the offline rendering operations.
     * @access public
     * @static
     */
    public static function offline()
    {
        self::$_layout = 'error.phtml';
        self::$_page = 'Error';
        self::$_subpage = 'Dboffline';
        self::_loadView();
        self::_loadController();
        self::_activateView();
        return;
    }

    /**
     * Quick render.
     *
     * This function does the main parts for rendering, which are also used for
     * redirections.
     * @access private
     * @static
     */
    protected static function _quickRender()
    {
        self::_loadView();
        self::_loadModel();
        self::_loadController();
    }

    /**
     * Parses parameters.
     *
     * This function parses all request to page hierarchy and request object.
     * @access private
     * @static
     * @uses WWWROOT, SERVER, GET, POST
     */
    protected static function _parseParams()
    {
        $stringParams = preg_replace('/^' . str_replace('/', '\/', WWWROOT) . '/', '', $_SERVER['REQUEST_URI']);
        $arrayParams = explode('/', $stringParams, 3);
        $pageCell = isset($arrayParams[0]) ? $arrayParams[0] : null;
        $subpageCell = isset($arrayParams[1]) ? $arrayParams[1] : null;
        self::$_page = ctype_alpha($pageCell) ? $pageCell : 'Index';
        self::$_subpage = (ctype_alpha($subpageCell) && self::$_page !== 'Index') ? $subpageCell : 'Index';
        self::$_requests = new Galleria_Requests($_GET, array_merge($_POST, $_FILES));
        return;
    }

    /**
     * Load model.
     *
     * This function loads model.
     * @access private
     * @static
     * @uses PATH_DATA_SITE_MVC_MODEL
     */
    protected static function _loadModel()
    {
        $modelName = self::$_page . 'Model';
        self::$_model = (@include PATH_DATA_SITE_MVC_MODEL . '/' . strtolower($modelName) . '.php') ? new $modelName() : null;
        return;
    }

    /**
     * Load view.
     *
     * This function loads view.
     * @access private
     * @static
     * @uses PATH_DATA_SITE_MVC_VIEW_SCRIPTS
     */
    protected static function _loadView()
    {
        $viewName = self::$_page . '/' . self::$_subpage;
        $filePath = PATH_DATA_SITE_MVC_VIEW_SCRIPTS . '/' . strtolower($viewName) . '.phtml';
        self::$_viewpath = file_exists($filePath) ? $filePath : null;
        return;
    }

    /**
     * Load controller.
     *
     * This function loads controller.
     * @access private
     * @static
     * @uses PATH_DATA_SITE_MVC_CONTROLLER
     */
    protected static function _loadController()
    {
        $controllerName = self::$_page . 'Controller';
        $actionName = self::$_subpage . 'Action';

        if (!@include PATH_DATA_SITE_MVC_CONTROLLER . '/' . strtolower($controllerName) . '.php') {
            self::$_page = 'Error';
            self::$_subpage = 'Pagenotfound';
            return self::_quickRender();
        }

        $activeController = new $controllerName(self::$_subpage, self::$_model, self::$_requests);
        if ($activeController->before()) {
            $activeController->$actionName();
            if (!$activeController->after())
                throw new Exception('Controller failed.');
        } else {
            self::$_page = $activeController->getPage();
            self::$_subpage = $activeController->getSubpage();
            return self::_quickRender();
        }
        self::$_controller = $activeController;

        return;
    }

    /**
     * Activate view.
     *
     * This function activates view.
     * @access private
     * @static
     * @uses WWWROOT, PATH_DATA_SITE_MVC_VIEW_LAYOUT
     */
    protected static function _activateView()
    {
        $html_url = preg_replace('/\/index.php/', '', WWWROOT);
        if (self::$_controller->getView()->render()) {
            $view = self::$_controller->getView();
            ob_start();
            if (!@include self::$_viewpath)
                throw new Exception('View script doesn\'t exist.');
            $content = ob_get_contents();
            ob_clean();
        } else
            echo "do not render";
        if (self::$_controller->getView()->renderLayout()) {
            $page_content = $content;
            ob_start();
            if (!@include PATH_DATA_SITE_MVC_VIEW_LAYOUT . '/' . self::$_layout)
                throw new Exception('Layout script doesn\'t exist.');
            $renderedView = ob_get_contents();
            ob_clean();
        } else {
            $renderedView = $content;
        }
        echo $renderedView;
    }

    /**
     * Redirects.
     *
     * This function redirects to other location.
     * @access private
     * @static
     * @uses WWWROOT
     */
    protected static function _redir()
    {
        $cont = self::$_controller;
        if ($cont->isRedirected()) {
            $page = WWWROOT;
            $page = ($cont->getPage() == 'index' && $cont->getSubpage() == 'index') ? $page : $page . $cont->getPage();
            $page = ($cont->getSubpage() == 'index') ? $page : $page . $cont->getSubpage();
            header('Location: ' . $page);
        }
    }

}
