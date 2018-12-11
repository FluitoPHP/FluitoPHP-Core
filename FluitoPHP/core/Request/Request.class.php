<?php

/**
 * FluitoPHP(tm): Lightweight MVC (http://www.fluitophp.org)
 * Copyright (c) 2017, FluitoSoft (http://www.fluitosoft.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2017, FluitoSoft (http://www.fluitosoft.com)
 * @link          http://www.fluitophp.org FluitoPHP(tm): Lightweight MVC
 * @since         0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace FluitoPHP\Request;

/**
 * Define Request Class.
 *
 * Variables:
 *      1. $instance
 *      2. $module
 *      3. $controller
 *      4. $action
 *      5. $errorModule
 *      6. $errorHandler
 *      7. $subPath
 *      8. $parsedURL
 *      9. $SERVER
 *      10. $ENV
 *      11. $GET
 *      12. $POST
 *      13. $COOKIE
 *      14. $FILES
 *      15. $defaultModule
 *      16. $defaultController
 *      17. $defaultAction
 *      18. $URLModule
 *      19. $URLController
 *      20. $URLAction
 *      21. $URLModuleTaken
 *      22. $preRan
 *      23. $moduleFixed
 *      24. $modules
 *      25. $originalParsedURL
 *      26. $rawPostData
 *      27. $requestHeaders
 *
 * Functions:
 *      1. __construct
 *      2. GetInstance
 *      3. Setup
 *      4. GetModule
 *      5. GetController
 *      6. GetAction
 *      7. GetErrorModule
 *      8. GetErrorHandler
 *      9. GetURLSubPath
 *      10. GetURLVars
 *      11. GetSubPath
 *      12. Run
 *      13. HandleError
 *      14. Server
 *      15. Env
 *      16. Get
 *      17. Post
 *      18. Cookie
 *      19. Files
 *      20. IsPost
 *      21. URL
 *      22. PreRun
 *      23. SetModule
 *      24. SetController
 *      25. SetAction
 *      26. FixModule
 *      27. GetModules
 *      28. ShiftModule
 *      29. ShiftController
 *      30. RefreshMaps
 *      31. GetRawPostData
 *      32. GetRequestHeader
 *      33. IsModuleFixed
 *
 * @author Neha Jain
 * @since  0.1
 */
class Request {

    /**
     * Used for storing Singleton instance.
     *
     * @var \FluitoPHP\Request\Request
     * @author Neha Jain
     * @since  0.1
     */
    static private $instance = null;

    /**
     * Used for storing module name.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    private $module = 'index';

    /**
     * Used for storing controller name.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    private $controller = 'index';

    /**
     * Used for storing action name.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    private $action = 'index';

    /**
     * Used for storing error module.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    private $errorModule = null;

    /**
     * Used for storing error handler.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    private $errorHandler = null;

    /**
     * Used for storing URL sub path.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    private $subPath = '';

    /**
     * Used for storing parsed URL.
     *
     * @var array
     * @author Neha Jain
     * @since  0.1
     */
    private $parsedURL = [];

    /**
     * Used to store the SERVER variables.
     *
     * @var array
     * @author Neha Jain
     * @since  0.1
     */
    private $SERVER = null;

    /**
     * Used to store the ENV variables.
     *
     * @var array
     * @author Neha Jain
     * @since  0.1
     */
    private $ENV = null;

    /**
     * Used to store the GET variables.
     *
     * @var array
     * @author Neha Jain
     * @since  0.1
     */
    private $GET = null;

    /**
     * Used to store the POST variables.
     *
     * @var array
     * @author Neha Jain
     * @since  0.1
     */
    private $POST = null;

    /**
     * Used to store the COOKIE variables.
     *
     * @var array
     * @author Neha Jain
     * @since  0.1
     */
    private $COOKIE = null;

    /**
     * Used to store the FILES variables.
     *
     * @var array
     * @author Neha Jain
     * @since  0.1
     */
    private $FILES = null;

    /**
     * Used to store the default module from the configuration files.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    private $defaultModule;

    /**
     * Used to store the default controller from the configuration files.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    private $defaultController;

    /**
     * Used to store the default action from the configuration files.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    private $defaultAction;

    /**
     * Used to store module from the URL.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    private $URLModule;

    /**
     * Used to store controller from the URL.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    private $URLController = '';

    /**
     * Used to store action from the URL.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    private $URLAction = '';

    /**
     * Used to store if the module is taken from URL.
     *
     * @var bool
     * @author Neha Jain
     * @since  0.1
     */
    private $URLModuleTaken = false;

    /**
     * Used to store true if the PreRun Step is executed.
     *
     * @var bool
     * @author Neha Jain
     * @since  0.1
     */
    private $preRan = false;

    /**
     * Used to check if the module is fixed so that it cannot be updated.
     *
     * @var bool
     * @author Neha Jain
     * @since  0.1
     */
    private $moduleFixed = false;

    /**
     * Used to store all modules.
     *
     * @var array
     * @author Neha Jain
     * @since  0.1
     */
    private $modules = null;

    /**
     * Used for storing original parsed URL.
     *
     * @var array
     * @author Neha Jain
     * @since  0.1
     */
    private $originalParsedURL = [];

    /**
     * Used for storing raw post data.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    private $rawPostData = null;

    /**
     * Used for storing request headers.
     *
     * @var array
     * @author Neha Jain
     * @since  0.1
     */
    private $requestHeaders = null;

    /**
     * Private constructor to use this class as a singleton class.
     *
     * @author Neha Jain
     * @since  0.1
     */
    private function __construct() {

        $this->
                SERVER = isset($_SERVER) ? $_SERVER : array();
        $this->
                ENV = isset($_ENV) ? $_ENV : array();
        $this->
                GET = isset($_GET) ? $_GET : array();
        $this->
                POST = isset($_POST) ? $_POST : array();
        $this->
                COOKIE = isset($_COOKIE) ? $_COOKIE : array();
        $this->
                FILES = isset($_FILES) ? $_FILES : array();

        $_SERVER = [];
        $_ENV = [];
        $_GET = [];
        $_POST = [];
        $_COOKIE = [];
        $_FILES = [];
        $_REQUEST = [];

        $this->
                modules = scandir(MODULE);

        array_shift($this->
                modules);
        array_shift($this->
                modules);
    }

    /**
     * Used to fetch the singleton instance object.
     *
     * @return \FluitoPHP\Request\Request
     * @author Neha Jain
     * @since  0.1
     */
    static public function GetInstance() {

        if (self::$instance === null) {

            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Used to get the module name.
     *
     * @throws \FluitoPHP\HttpException\HttpException Throws this exception if the path can not be found.
     * @throws \Exception Throws this exception if the controller class in not properly defined.
     * @author Neha Jain
     * @since  0.1
     */
    public function Setup() {

        $scriptBaseDir = strtr(strtolower($this->
                                Server('SCRIPT_FILENAME')), array(
            '\\' => '/'
        ));

        if (strpos($scriptBaseDir, 'index.php') === strlen($scriptBaseDir) - strlen('index.php')) {

            $scriptBaseDir = substr($scriptBaseDir, 0, strpos($scriptBaseDir, 'index.php'));
        }

        $documentRoot = strtr(strtolower($this->
                                Server('DOCUMENT_ROOT')), array(
            '\\' => '/'
        ));

        $removalPath = '';

        if (strlen($scriptBaseDir) > strlen($documentRoot)) {

            $removalPath = substr($scriptBaseDir, strlen($documentRoot));
        }

        $this->
                subPath = $removalPath;

        $filtered_uri = $this->
                Server('REQUEST_URI');

        $filtered_uri = explode('?', $filtered_uri);

        $filtered_uri = $filtered_uri[0];

        $requestConfig = \FluitoPHP\FluitoPHP::GetInstance()->
                GetConfig('REQUEST');

        $requestConfig = $requestConfig ? $requestConfig : [];

        $URLSuffix = isset($requestConfig['URLSuffix']) ? strtolower($requestConfig['URLSuffix']) : null;

        if (isset($requestConfig['default'])) {

            $this->
                    defaultModule = isset($requestConfig['default']['module']) ? strtolower($requestConfig['default']['module']) : null;

            $this->
                    defaultController = isset($requestConfig['default']['controller']) ? strtolower($requestConfig['default']['controller']) : null;

            $this->
                    defaultAction = isset($requestConfig['default']['action']) ? strtolower($requestConfig['default']['action']) : null;
        }

        $filtered_uri = trim($filtered_uri, '/');

        if ($removalPath &&
                strpos(strtolower($filtered_uri), $removalPath) === 0) {

            $filtered_uri = substr($filtered_uri, strlen($removalPath));
        } else if ($removalPath &&
                strpos(strtolower($filtered_uri), rtrim($removalPath, '/')) === 0) {

            $filtered_uri = substr($filtered_uri, strlen(rtrim($removalPath, '/')));
        }

        while (strpos($filtered_uri, '//') !== false) {

            $filtered_uri = strtr($filtered_uri, array(
                '//' => '/'
            ));
        }

        $urlMaps = \FluitoPHP\FluitoPHP::GetInstance()->
                GetConfig('URLMAPS');

        $urlMaps = $urlMaps ? \FluitoPHP\Arrays\Arrays::MultiToDots($urlMaps) : [];

        foreach ($urlMaps as $key => $value) {

            if (strpos(strtolower($filtered_uri), strtolower($key)) === 0) {

                $filtered_uri = strtolower($value) . substr($filtered_uri, strlen($key));
                break;
            }
        }

        $uri_levels = explode('/', $filtered_uri);

        $this->
                originalParsedURL = $uri_levels;

        if (isset($uri_levels[0]) &&
                !strlen($uri_levels[0])) {

            array_shift($uri_levels);
        }

        if (isset($requestConfig['prefix']) &&
                is_array($requestConfig['prefix']) &&
                count($requestConfig['prefix'])) {

            foreach ($requestConfig['prefix'] as $value) {

                if (isset($uri_levels[0]) &&
                        strlen($uri_levels[0]) &&
                        is_string($value) &&
                        strlen($value)) {

                    $this->
                            parsedURL[$value] = $uri_levels[0];
                    array_shift($uri_levels);
                }
            }
        }

        if (isset($uri_levels[0])) {

            $module_test = strtolower($uri_levels[0]);

            if (!isset($uri_levels[1]) &&
                    $URLSuffix &&
                    strpos($module_test, $URLSuffix) === strlen($module_test) - strlen($URLSuffix)) {

                $module_test = substr($module_test, 0, strpos($module_test, $URLSuffix));
            }

            if (strlen($module_test) &&
                    file_exists(MODULE . DS . $module_test)) {

                $this->
                        module = $module_test;

                $this->
                        URLModule = array_shift($uri_levels);

                $this->
                        URLModuleTaken = true;
            }
        }

        $moduleMapsArray = \FluitoPHP\FluitoPHP::GetInstance()->
                GetConfig('MODULEMAPS');

        $moduleMapsArray = $moduleMapsArray ? $moduleMapsArray : [];

        if (isset($moduleMapsArray[$this->
                        module])) {

            $this->
                    module = $moduleMapsArray[$this->
                    module];
        }

        if (!$this->
                module) {

            $this->
                    module = $this->
                    defaultModule;
        }

        $requestModuleConfig = \FluitoPHP\FluitoPHP::GetInstance()->
                GetModuleConfig('REQUEST', false);

        $requestModuleConfig = $requestModuleConfig ? $requestModuleConfig : [];

        if (isset($requestModuleConfig['default'])) {

            $this->
                    defaultController = isset($requestModuleConfig['default']['controller']) ? strtolower($requestModuleConfig['default']['controller']) : $this->
                    defaultController;

            $this->
                    defaultAction = isset($requestModuleConfig['default']['action']) ? strtolower($requestModuleConfig['default']['action']) : $this->
                    defaultAction;
        }

        $URLSuffix = isset($requestModuleConfig['URLSuffix']) ? strtolower($requestModuleConfig['URLSuffix']) : $URLSuffix;

        if (isset($uri_levels[0]) &&
                strlen($uri_levels[0])) {

            $this->
                    controller = strtolower($uri_levels[0]);

            if (!isset($uri_levels[1]) &&
                    $URLSuffix &&
                    strpos($this->
                            controller, $URLSuffix) === strlen($this->
                            controller) - strlen($URLSuffix)) {

                $this->
                        controller = substr($this->
                        controller, 0, strpos($this->
                                controller, $URLSuffix));
            }

            $this->
                    URLController = $this->
                    controller;

            array_shift($uri_levels);
        }

        $controllerMapsArray = \FluitoPHP\FluitoPHP::GetInstance()->
                GetConfig('CONTROLLERMAPS');

        $controllerMapsArray = $controllerMapsArray ? $controllerMapsArray : [];

        $moduleControllerMapsArray = \FluitoPHP\FluitoPHP::GetInstance()->
                GetModuleConfig('CONTROLLERMAPS', false);

        $moduleControllerMapsArray = $moduleControllerMapsArray ? $moduleControllerMapsArray : [];

        $controllerMapsArray = array_replace_recursive($controllerMapsArray, $moduleControllerMapsArray);

        if (isset($controllerMapsArray[$this->
                        module][$this->
                        controller
                ])) {

            $this->
                    controller = $controllerMapsArray[$this->
                    module][$this->
                    controller
            ];
        }

        if (isset($uri_levels[0]) &&
                strlen($uri_levels[0])) {

            $this->
                    action = strtolower($uri_levels[0]);

            if (!isset($uri_levels[1]) &&
                    $URLSuffix &&
                    strpos($this->
                            action, $URLSuffix) === strlen($this->
                            action) - strlen($URLSuffix)) {

                $this->
                        action = substr($this->
                        action, 0, strpos($this->
                                action, $URLSuffix));
            }

            $this->
                    URLAction = $this->
                    action;

            array_shift($uri_levels);
        }

        $actionMapsArray = \FluitoPHP\FluitoPHP::GetInstance()->
                GetConfig('ACTIONMAPS');

        $actionMapsArray = $actionMapsArray ? $actionMapsArray : [];

        $moduleActionMapsArray = \FluitoPHP\FluitoPHP::GetInstance()->
                GetModuleConfig('ACTIONMAPS', false);

        $moduleActionMapsArray = $moduleActionMapsArray ? $moduleActionMapsArray : [];

        $actionMapsArray = array_replace_recursive($actionMapsArray, $moduleActionMapsArray);

        if (isset($actionMapsArray[$this->
                        module][$this->
                        controller
                        ][$this->
                        action
                ])) {

            $this->
                    action = $actionMapsArray[$this->
                    module][$this->
                    controller
                    ][$this->
                    action
            ];
        }

        if (isset($uri_levels[0]) &&
                !strlen($uri_levels[0])) {

            array_shift($uri_levels);
        }

        $this->
                parsedURL = array_replace($uri_levels, $this->
                parsedURL);
    }

    /**
     * Used to get the module name.
     *
     * @return string
     * @author Neha Jain
     * @since  0.1
     */
    public function GetModule() {

        return $this->
                module;
    }

    /**
     * Used to get the controller name.
     *
     * @return string
     * @author Neha Jain
     * @since  0.1
     */
    public function GetController() {

        return $this->
                controller;
    }

    /**
     * Used to get the action name.
     *
     * @return string
     * @author Neha Jain
     * @since  0.1
     */
    public function GetAction() {

        return $this->
                action;
    }

    /**
     * Used to get the error module name.
     *
     * @return string
     * @author Neha Jain
     * @since  0.1
     */
    public function GetErrorModule() {

        return $this->
                errorModule;
    }

    /**
     * Used to get the error handler name.
     *
     * @return string
     * @author Neha Jain
     * @since  0.1
     */
    public function GetErrorHandler() {

        return $this->
                errorHandler;
    }

    /**
     * Used to get the subPath.
     *
     * @return string
     * @author Neha Jain
     * @since  0.1
     */
    public function GetURLSubPath() {

        return $this->
                subPath;
    }

    /**
     * Used to get the parsedURL array.
     *
     * @return array
     * @author Neha Jain
     * @since  0.1
     */
    public function GetURLVars() {

        return $this->
                parsedURL;
    }

    /**
     * Used to get the subpath of the application.
     *
     * @return string
     * @author Neha Jain
     * @since  0.1
     */
    public function GetSubPath() {

        return $this->
                subPath;
    }

    /**
     * Used to run the request.
     *
     * @author Neha Jain
     * @since  0.1
     */
    public function Run() {

        $this->
                PreRun();

        $controllerClass = '\\' . $this->
                controller . 'Controller';

        $controllerObject = new $controllerClass;

        $controllerObject->
                Run($this->
                        action);
    }

    /**
     * Used to handle the errors/exceptions.
     *
     * @param \Exception $exception This is the exception object which needs to be handled.
     * @author Neha Jain
     * @since  0.1
     */
    public function HandleError($exception) {

        $moduleErrorHandlers = \FluitoPHP\FluitoPHP::GetInstance()->
                GetModuleConfig('ERRORS', false);

        $moduleErrorHandlers = $moduleErrorHandlers ? $moduleErrorHandlers : [];

        $this->
                errorModule = null;
        $this->
                errorHandler = null;

        if (isset($moduleErrorHandlers['handler'])) {

            $this->
                    errorModule = $this->
                    module;
            $this->
                    errorHandler = $moduleErrorHandlers['handler'];
        }


        if (!$this->
                errorModule) {

            $errorHandlers = \FluitoPHP\FluitoPHP::GetInstance()->
                    GetConfig('ERRORS');

            $errorHandlers = $errorHandlers ? $errorHandlers : [];

            if (isset($errorHandlers['handler'])) {

                if (isset($errorHandlers['module'])) {

                    $this->
                            errorModule = $errorHandlers['module'];
                } else {

                    $this->
                            errorModule = $this->
                            module;
                }

                $this->
                        errorHandler = $errorHandlers['handler'];
            }
        }

        if ($this->
                errorModule &&
                file_exists(MODULE . DS . $this->
                        errorModule . CONTROLLERS . DS . $this->
                        errorHandler . 'ErrorHandler.class.php')) {

            require_once( MODULE . DS . $this->
                    errorModule . CONTROLLERS . DS . $this->
                    errorHandler . 'ErrorHandler.class.php' );

            $handlerClass = '\\' . $this->
                    errorHandler . 'ErrorHandler';

            $parentClasses = class_parents($handlerClass);

            if (isset($parentClasses['FluitoPHP\ErrorHandler\ErrorHandler']) ||
                    isset($parentClasses['\FluitoPHP\ErrorHandler\ErrorHandler'])) {

                $handlerObject = new $handlerClass;

                $errorHandle = 'index';

                if (class_exists('HttpException') &&
                        $exception instanceof \FluitoPHP\HttpException\HttpException) {

                    if (is_callable(array($handlerObject,
                                'http' . $exception->
                                        GetHttpCode() . 'Handle'))) {

                        $errorHandle = 'http' . $exception->
                                        GetHttpCode();
                    } elseif (is_callable(array($handlerObject,
                                'http' . 'Handle'))) {

                        $errorHandle = 'http';
                    }
                }

                call_user_func_array(array(
                    $handlerObject,
                    $errorHandle . 'Handle'
                        ), array($exception));

                goto endHandler;
            }
        }

        $this->
                errorHandler = 'Default';

        if (file_exists(EXTENSIONS . DS . 'ErrorHandler' . DS . $this->
                        errorHandler . 'ErrorHandler.class.php')) {

            require_once( EXTENSIONS . DS . 'ErrorHandler' . DS . $this->
                    errorHandler . 'ErrorHandler.class.php' );
        } else {

            require_once( LIB . DS . 'ErrorHandler' . DS . $this->
                    errorHandler . 'ErrorHandler.class.php' );
        }

        $handlerClass = "\\FluitoPHP\\ErrorHandler\\{$this->
                errorHandler}ErrorHandler";

        if (!class_exists($handlerClass)) {

            $handlerClass = "\\FluitoPHP\\ErrorHandler\\Custom\\{$this->
                    errorHandler}ErrorHandler";
        }

        $parentClasses = class_parents($handlerClass);

        if (isset($parentClasses['FluitoPHP\ErrorHandler\ErrorHandler']) ||
                isset($parentClasses['\FluitoPHP\ErrorHandler\ErrorHandler'])) {

            $handlerObject = new $handlerClass;

            $errorHandle = 'index';

            if (class_exists('HttpException') &&
                    $exception instanceof \FluitoPHP\HttpException\HttpException) {

                if (is_callable(array($handlerObject,
                            'http' . $exception->
                                    GetHttpCode() . 'Handle'))) {

                    $errorHandle = 'http' . $exception->
                                    GetHttpCode();
                } elseif (is_callable(array($handlerObject,
                            'http' . 'Handle'))) {

                    $errorHandle = 'http';
                }
            }

            call_user_func_array(array(
                $handlerObject,
                $errorHandle . 'Handle'
                    ), array($exception));
        }

        endHandler:
    }

    /**
     * Used to get SERVER variables.
     *
     * @param string $key Provide the key value to be retrieved.
     * @return mixed Returns null if the value is not found else the value is returned.
     * @author Neha Jain
     * @since  0.1
     */
    public function Server($key) {

        if (((is_string($key) &&
                strlen($key)) ||
                is_int($key)) &&
                isset($this->
                        SERVER[$key])) {

            switch ($key) {
                case 'HTTPS':

                    return strtolower($this->
                                    SERVER[$key]) !== 'off' ? true : false;

                    break;
                default:

                    return $this->
                            SERVER[$key];

                    break;
            }
        }

        return null;
    }

    /**
     * Used to get ENV variables.
     *
     * @param string $key Provide the key value to be retrieved.
     * @return mixed Returns null if the value is not found else the value is returned.
     * @author Neha Jain
     * @since  0.1
     */
    public function Env($key) {

        if (((is_string($key) &&
                strlen($key)) ||
                is_int($key)) &&
                isset($this->
                        ENV[$key])) {

            return $this->
                    ENV[$key];
        }

        return null;
    }

    /**
     * Used to get GET variables.
     *
     * @param string $key Provide the key value to be retrieved.
     * @return mixed Returns null if the value is not found else the value is returned.
     * @author Neha Jain
     * @since  0.1
     */
    public function Get($key) {

        if (((is_string($key) &&
                strlen($key)) ||
                is_int($key)) &&
                isset($this->
                        GET[$key])) {

            return $this->
                    GET[$key];
        }

        return null;
    }

    /**
     * Used to get POST variables.
     *
     * @param string $key Provide the key value to be retrieved.
     * @return mixed Returns null if the value is not found else the value is returned.
     * @author Neha Jain
     * @since  0.1
     */
    public function Post($key) {

        if (((is_string($key) &&
                strlen($key)) ||
                is_int($key)) &&
                isset($this->
                        POST[$key])) {

            return $this->
                    POST[$key];
        }

        return null;
    }

    /**
     * Used to get COOKIE variables.
     *
     * @param string $key Provide the key value to be retrieved.
     * @return mixed Returns null if the value is not found else the value is returned.
     * @author Neha Jain
     * @since  0.1
     */
    public function Cookie($key) {

        if (((is_string($key) &&
                strlen($key)) ||
                is_int($key)) &&
                isset($this->
                        COOKIE[$key])) {

            return $this->
                    COOKIE[$key];
        }

        return null;
    }

    /**
     * Used to get FILES variables.
     *
     * @param string $key Provide the key value to be retrieved.
     * @return mixed Returns null if the value is not found else the value is returned.
     * @author Neha Jain
     * @since  0.1
     */
    public function Files($key) {

        if (((is_string($key) &&
                strlen($key)) ||
                is_int($key)) &&
                isset($this->
                        FILES[$key])) {

            return $this->
                    FILES[$key];
        }

        return null;
    }

    /**
     * Used to check if the current request is of POST type.
     *
     * @return bool Returns true if the current request is of POST type.
     * @author Neha Jain
     * @since  0.1
     */
    public function IsPost() {

        return $this->
                        Server('REQUEST_METHOD') === 'POST';
    }

    /**
     * Used to generate the URL to any path or action.
     *
     * @param string $pathOrController Provide custom path or controller name.
     * @param string $action Provide action name if controller is provided.
     * @param string $module Provide module name if applicable.
     * @param array $addArgs Provide addition path to append.
     * @param array $query Provide query in associative array.
     * @param string $customPrefix Provide custom prefix to use instead of the current host.
     * @return string Returns the generated URL.
     * @author Neha Jain
     * @since  0.1
     */
    public function URL($pathOrController = '', $action = '', $module = '', $addArgs = [], $query = [], $customPrefix = null) {

        if (is_string($customPrefix) ||
                strlen($customPrefix)) {

            $return = $customPrefix;
        } else {

            $return = 'http' . ($this->
                            Server('HTTPS') ? 's' : '') . '://' . $this->
                            Server('HTTP_HOST');
        }

        if (!is_array($addArgs)) {

            $addArgs = [];
        }

        if (is_string($pathOrController) &&
                $pathOrController) {

            if (strtolower($pathOrController) === "index" &&
                    strtolower($action) === "index" &&
                    !count($addArgs)) {

                $pathOrController = '';
            }

            if (is_string($action) &&
                    $action) {

                if (strtolower($action) === "index" &&
                        !count($addArgs)) {

                    $action = '';
                }

                if (is_string($module) &&
                        $module) {

                    if (((!$this->
                            defaultModule &&
                            $module !== "index") ||
                            ($this->
                            defaultModule &&
                            $module !== $this->
                            defaultModule)) ||
                            (strlen($pathOrController) &&
                            in_array($pathOrController, $this->
                                    modules))) {

                        $return .= "/{$module}";
                    }
                }

                if ($pathOrController) {

                    $return .= "/{$pathOrController}";
                }

                if ($action) {

                    $return .= "/{$action}";
                }
            } else {

                $return .= '/' . ltrim($pathOrController, '/');
            }
        }

        if (count($addArgs)) {

            $return .= '/' . implode('/', $addArgs);
        }

        $queryTemp = [];

        if (count($query)) {

            foreach ($query as $key => $value) {

                if (is_string($key)) {

                    $queryTemp[] = "{$key}={$value}";
                } else {

                    $queryTemp[] = "{$value}";
                }
            }

            $return .= '?' . implode('&', $queryTemp);
        }

        return $return;
    }

    /**
     * Used to Run before the MVC but after the Bootloaders.
     *
     * @author Neha Jain
     * @since  0.1
     */
    public function PreRun() {

        $this->
                preRan = true;

        if (!file_exists(MODULE . DS . $this->
                        module . CONTROLLERS . DS . $this->
                        controller . 'Controller.class.php')) {

            if (!$this->
                    defaultController ||
                    !file_exists(MODULE . DS . $this->
                            module . CONTROLLERS . DS . $this->
                            defaultController . 'Controller.class.php')) {

                throw new \FluitoPHP\HttpException\HttpException('Controller (' . $this->
                controller . ') not found in Module (' . $this->
                module . ').', 404);
            }

            if ($this->
                    controller) {

                array_unshift($this->
                        parsedURL, $this->
                        URLAction);

                $this->
                        URLAction = $this->
                        URLController;

                $this->
                        action = $this->
                        URLController;

                $this->
                        URLController = '';
            }

            $this->
                    controller = $this->
                    defaultController;
        }

        require_once( MODULE . DS . $this->
                module . CONTROLLERS . DS . $this->
                controller . 'Controller.class.php' );


        $parentClasses = class_parents($this->
                controller . 'Controller');

        if (!(isset($parentClasses['FluitoPHP\Controller\Controller']) ||
                isset($parentClasses['\FluitoPHP\Controller\Controller']))) {

            throw new \Exception('Controller class not defined properly.', 0);
        }

        $classMethods = get_class_methods($this->
                controller . 'Controller');

        if (!in_array($this->
                        action . 'Action', $classMethods)) {

            if ($this->
                    action) {

                array_unshift($this->
                        parsedURL, $this->
                        URLAction);

                $this->
                        URLAction = '';
            }

            if (!$this->
                    defaultAction ||
                    !in_array($this->
                            defaultAction . 'Action', $classMethods)) {

                throw new \FluitoPHP\HttpException\HttpException('Action (' . $this->
                action . ') not found in Module (' . $this->
                module . ').Controller (' . $this->
                controller . ').', 404);
            }

            $this->
                    action = $this->
                    defaultAction;
        }
    }

    /**
     * Used to update the module name.
     *
     * @param string $module Provide the module name.
     * @return bool Returns true on success and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function SetModule($module) {

        if ($this->
                preRan ||
                $this->
                moduleFixed) {

            return false;
        }

        if (!is_string($module) ||
                !strlen($module)) {

            return false;
        }

        $position = 0;

        if ($this->
                URLModule) {

            array_splice($this->
                    parsedURL, $position, 0, $this->
                    URLModule);

            $this->
                    URLModule = '';
        }

        $this->
                module = strtolower($module);

        $this->
                RefreshMaps();

        \FluitoPHP\Response\Response::GetInstance()->
                View()->
                RefreshView();

        return true;
    }

    /**
     * Used to update the controller name.
     *
     * @param string $controller Provide the controller name.
     * @return bool Returns true on success and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function SetController($controller) {

        if ($this->
                preRan) {

            return false;
        }

        if (!is_string($controller) ||
                !strlen($controller)) {

            return false;
        }

        $position = 0;

        if ($this->
                URLController) {

            if ($this->
                    URLModuleTaken &&
                    !$this->
                    URLModule) {

                $position++;
            }

            array_splice($this->
                    parsedURL, $position, 0, $this->
                    URLController);

            $this->
                    URLController = '';
        }

        $this->
                controller = strtolower($controller);

        \FluitoPHP\Response\Response::GetInstance()->
                View()->
                RefreshView();

        return true;
    }

    /**
     * Used to update the action name.
     *
     * @param string $action Provide the action name.
     * @return bool Returns true on success and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function SetAction($action) {

        if ($this->
                preRan) {

            return false;
        }

        if (!is_string($action) ||
                !strlen($action)) {

            return false;
        }

        $position = 0;

        if ($this->
                URLAction) {
            if ($this->
                    URLModuleTaken &&
                    !$this->
                    URLModule) {

                $position++;
            }

            if (!$this->
                    URLController) {

                $position++;
            }

            array_splice($this->
                    parsedURL, $position, 0, $this->
                    URLAction);

            $this->
                    URLAction = '';
        }

        $this->
                action = strtolower($action);

        \FluitoPHP\Response\Response::GetInstance()->
                View()->
                RefreshView();

        return true;
    }

    /**
     * Used to fix the module so that it can not be updated by code.
     *
     * @author Neha Jain
     * @since  0.1
     */
    public function FixModule() {

        $this->
                moduleFixed = true;
    }

    /**
     * Used to get all the module names.
     *
     * @return array Returns all the module names.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetModules() {

        return $this->
                modules;
    }

    /**
     * Used to update the module name and shift the contents to controller and further to action.
     *
     * @param string $module Provide the module name.
     * @return bool Returns true on success and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function ShiftModule($module) {

        if ($this->
                preRan ||
                $this->
                moduleFixed) {

            return false;
        }

        if (!is_string($module) ||
                !strlen($module)) {

            return false;
        }

        if ($this->
                URLModule) {

            if ($this->
                    URLController) {

                if ($this->
                        URLAction) {

                    array_splice($this->
                            parsedURL, 0, 0, $this->
                            URLAction);

                    $this->
                            URLAction = $this->
                            URLController;

                    $this->
                            URLController = $this->
                            URLModule;
                } else {

                    array_splice($this->
                            parsedURL, 0, 0, $this->
                            URLController);

                    $this->
                            URLController = $this->
                            URLModule;
                }
            } else {

                array_splice($this->
                        parsedURL, 0, 0, $this->
                        URLModule);
            }

            $this->
                    URLModule = '';
        }

        $this->
                module = strtolower($module);

        $this->
                RefreshMaps();

        \FluitoPHP\Response\Response::GetInstance()->
                View()->
                RefreshView();

        return true;
    }

    /**
     * Used to update the controller name.
     *
     * @param string $controller Provide the controller name.
     * @return bool Returns true on success and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function ShiftController($controller) {

        if ($this->
                preRan) {

            return false;
        }

        if (!is_string($controller) ||
                !strlen($controller)) {

            return false;
        }

        $position = 0;

        if ($this->
                URLController) {

            if ($this->
                    URLAction) {

                array_splice($this->
                        parsedURL, 0, 0, $this->
                        URLAction);

                $this->
                        URLAction = $this->
                        URLController;
            } else {

                array_splice($this->
                        parsedURL, 0, 0, $this->
                        URLController);
            }

            $this->
                    URLController = '';
        }

        $this->
                controller = strtolower($controller);

        \FluitoPHP\Response\Response::GetInstance()->
                View()->
                RefreshView();

        return true;
    }

    /**
     * Used to update the mapping of controller and views.
     *
     * @author Neha Jain
     * @since  0.1
     */
    private function RefreshMaps() {

        if ($this->
                URLController) {

            $controllerMapsArray = \FluitoPHP\FluitoPHP::GetInstance()->
                    GetConfig('CONTROLLERMAPS');

            $controllerMapsArray = $controllerMapsArray ? $controllerMapsArray : [];

            $moduleControllerMapsArray = \FluitoPHP\FluitoPHP::GetInstance()->
                    GetModuleConfig('CONTROLLERMAPS', false);

            $moduleControllerMapsArray = $moduleControllerMapsArray ? $moduleControllerMapsArray : [];

            $controllerMapsArray = array_replace_recursive($controllerMapsArray, $moduleControllerMapsArray);

            if (isset($controllerMapsArray[$this->
                            module][$this->
                            controller
                    ])) {

                $this->
                        controller = $controllerMapsArray[$this->
                        module][$this->
                        controller
                ];
            }
        }

        if ($this->
                URLAction) {

            $actionMapsArray = \FluitoPHP\FluitoPHP::GetInstance()->
                    GetConfig('ACTIONMAPS');

            $actionMapsArray = $actionMapsArray ? $actionMapsArray : [];

            $moduleActionMapsArray = \FluitoPHP\FluitoPHP::GetInstance()->
                    GetModuleConfig('ACTIONMAPS', false);

            $moduleActionMapsArray = $moduleActionMapsArray ? $moduleActionMapsArray : [];

            $actionMapsArray = array_replace_recursive($actionMapsArray, $moduleActionMapsArray);

            if (isset($actionMapsArray[$this->
                            module][$this->
                            controller
                            ][$this->
                            action
                    ])) {

                $this->
                        action = $actionMapsArray[$this->
                        module][$this->
                        controller
                        ][$this->
                        action
                ];
            }
        }
    }

    /**
     * Used to get the post request raw data.
     *
     * @return string Returns the post request raw data.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetRawPostData() {

        if ($this->
                rawPostData === null) {

            $this->
                    rawPostData = file_get_contents('php://input');
        }

        return $this->
                rawPostData;
    }

    /**
     * Used to get the request header.
     *
     * @param string $key Provide the key to get the single request header.
     * @return mixed Returns the request header if the key is passed else all the headers will be returned.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetRequestHeader($key = null) {

        if ($this->
                requestHeaders === null) {

            if (function_exists('getallheaders')) {

                $this->
                        requestHeaders = getallheaders();
            } else {

                $this->
                        requestHeaders = array();

                foreach ($this->
                SERVER as $k => $value) {

                    if (substr($k, 0, 5) == 'HTTP_') {

                        $k = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($k, 5)))));
                        $this->
                                requestHeaders[$k] = $value;
                    }
                }
            }
        }

        if (!is_string($key) ||
                !strlen($key)) {

            return $this->
                    requestHeaders;
        }

        if (isset($this->
                        requestHeaders[$key])) {

            return $this->
                    requestHeaders[$key];
        }

        return false;
    }

    /**
     * Used to check if the module is fixed or not.
     *
     * @return bool Returns true if the module is fixed and false if the module has not been fixed.
     * @author Neha Jain
     * @since  0.1
     */
    public function IsModuleFixed() {

        return $this->
                moduleFixed;
    }

}
