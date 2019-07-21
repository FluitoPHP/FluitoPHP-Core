<?php

/**
 * FluitoPHP(tm): Lightweight MVC (http://www.fluitophp.org)
 * Copyright (c) 2019, Vipin Jain (http://www.codesnsolutions.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2019, Vipin Jain (http://www.codesnsolutions.com)
 * @link          http://www.fluitophp.org FluitoPHP(tm): Lightweight MVC
 * @since         0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace FluitoPHP;

/**
 * Check and enable Debugger. Used for storing the debug flag.
 *
 * @type string
 * @author Vipin Jain
 * @since  0.1
 */
if (getenv('DEBUG') &&
        strtolower(getenv('DEBUG')) === 'on') {

    if (!defined('DEBUG')) {
        define(
                'DEBUG', true
        );
    }
} else {

    if (!defined('DEBUG')) {

        define(
                'DEBUG', false
        );
    }
}

/**
 * Used for storing the web path which is restricted to be used by the web apps so that no conflict occurs.
 *
 * @type string
 * @author Vipin Jain
 * @since  0.1
 */
if (!defined('RESTRICTED')) {

    define(
            'RESTRICTED', 'FluitoPHP'
    );
}

/**
 * Used for storing the web path which is restricted to be used by the web apps so that no conflict occurs.
 *
 * @type string
 * @author Vipin Jain
 * @since  0.1
 */
if (!defined('RESOURCES')) {

    define(
            'RESOURCES', 'resources'
    );
}

/**
 * Used for storing core library path.
 *
 * @type string
 * @author Vipin Jain
 * @since  0.1
 */
if (!defined('CORE')) {

    define(
            'CORE', FRAMEWORK . DS . 'core'
    );
}

/**
 * Used for storing library path.
 *
 * @type string
 * @author Vipin Jain
 * @since  0.1
 */
if (!defined('LIB')) {

    define(
            'LIB', FRAMEWORK . DS . 'library'
    );
}

/**
 * Used for storing extensions path.
 *
 * @type string
 * @author Vipin Jain
 * @since  0.1
 */
if (!defined('EXTENSIONS')) {

    define(
            'EXTENSIONS', realpath(FRAMEWORK . DS . '..' . DS . 'extensions')
    );
}

/**
 * Used for storing application path.
 *
 * @type string
 * @author Vipin Jain
 * @since  0.1
 */
if (!defined('APP')) {

    define(
            'APP', realpath(FRAMEWORK . DS . '..' . DS . 'application')
    );
}

/**
 * Used for storing configuration path.
 *
 * @type string
 * @author Vipin Jain
 * @since  0.1
 */
if (!defined('CONFIG')) {

    define(
            'CONFIG', APP . DS . 'configuration'
    );
}

/**
 * Used for storing bootloaders path.
 *
 * @type string
 * @author Vipin Jain
 * @since  0.1
 */
if (!defined('BOOT')) {

    define(
            'BOOT', APP . DS . 'boot'
    );
}

/**
 * Used for storing modules path.
 *
 * @type string
 * @author Vipin Jain
 * @since  0.1
 */
if (!defined('MODULE')) {

    define(
            'MODULE', APP . DS . 'modules'
    );
}

/**
 * Used for storing module configuration path.
 *
 * @type string
 * @author Vipin Jain
 * @since  0.1
 */
if (!defined('MODCONFIG')) {

    define(
            'MODCONFIG', DS . 'configuration'
    );
}

/**
 * Used for storing module bootloaders path.
 *
 * @type string
 * @author Vipin Jain
 * @since  0.1
 */
if (!defined('MODBOOT')) {

    define(
            'MODBOOT', DS . 'boot'
    );
}

/**
 * Used for storing models path.
 *
 * @type string
 * @author Vipin Jain
 * @since  0.1
 */
if (!defined('MODELS')) {

    define(
            'MODELS', DS . 'models'
    );
}

/**
 * Used for storing controllers path.
 *
 * @type string
 * @author Vipin Jain
 * @since  0.1
 */
if (!defined('CONTROLLERS')) {

    define(
            'CONTROLLERS', DS . 'controllers'
    );
}

/**
 * Used for storing views path.
 *
 * @type string
 * @author Vipin Jain
 * @since  0.1
 */
if (!defined('VIEWS')) {

    define(
            'VIEWS', DS . 'views'
    );
}

/**
 * FluitoPHP Class.
 *
 * This class is the base class needs to run the application.
 *
 * Minimum requirements:
 *      1. Constant 'ENV' pointing to the current instance type e.g. PRODUCTION, DEVELOPMENT etc.
 *      2. Constant 'DS' pointing to constant 'DIRECTORY_SEPARATOR'.
 *      3. Constant 'ABSOLUTE' pointing to the path of the script referenced by the request.
 *      4. Constant 'FRAMEWORK' pointing to the path of this class file.
 *
 * Extra configuration:
 *      1. Constant 'DEBUG' for debugging purposes type bool.
 *
 * Variables:
 *      1. $instance
 *      2. $instanceType
 *      3. $configType
 *      4. $globalConfig
 *      5. $moduleConfig
 *      6. $prevErrorHandler
 *      7. $output
 *      8. $noticeWarnings
 *      9. $loadedExtensions
 *      10. $startTime
 *      11. $data
 *
 * Functions:
 *      1. __construct
 *      2. GetInstance
 *      3. Autoloader
 *      4. ExceptionHandler
 *      5. ErrorHandler
 *      6. Shutdown
 *      7. Setup
 *      8. Run
 *      9. GetConfig
 *      10. GetModuleConfig
 *      11. Boot
 *      12. ErrorList
 *      13. Load
 *      14. Request
 *      15. Response
 *      16. DB
 *      17. Session
 *      18. Authentication
 *      19. Authorization
 *      20. Model
 *      21. Set
 *      22. Get
 *      23. Events
 *      24. Filters
 *      25. Extract
 *
 * @author Vipin Jain
 * @since  0.1
 */
class FluitoPHP {

    /**
     * Singleton Instance variable.
     *
     * @var \FluitoPHP\FluitoPHP
     * @author Vipin Jain
     * @since  0.1
     */
    static private $instance = null;

    /**
     * Instance type variable.
     *
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    private $instanceType = null;

    /**
     * Config type variable.
     *
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    private $configType = null;

    /**
     * Used for storing global application configuration.
     *
     * @var \FluitoPHP\Config\Config
     * @author Vipin Jain
     * @since  0.1
     */
    private $globalConfig = null;

    /**
     * Used for storing module configuration.
     *
     * @var \FluitoPHP\Config\Config
     * @author Vipin Jain
     * @since  0.1
     */
    private $moduleConfig = null;

    /**
     * Used for storing previous error handler.
     *
     * @var callable
     * @author Vipin Jain
     * @since  0.1
     */
    private $prevErrorHandler = null;

    /**
     * Used for storing the output.
     *
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    private $output = '';

    /**
     * Used for storing list of non-fatal errors/notices/warnings generated.
     *
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    private $noticeWarnings = [];

    /**
     * Used for storing loaded extensions.
     *
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    private $loadedExtensions = [];

    /**
     * Used to store start time of the request.
     *
     * @var float
     * @author Vipin Jain
     * @since  0.1
     */
    private $startTime = null;

    /**
     * Used for storing data for current request.
     *
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    private $data = [];

    /**
     * Private constructor to use this class as a singleton class.
     *
     * @author Vipin Jain
     * @since  0.1
     */
    private function __construct() {

        if (DEBUG) {

            error_reporting(E_ALL);

            if (isset($_SERVER["REQUEST_TIME_FLOAT"])) {

                $this->
                        startTime = $_SERVER["REQUEST_TIME_FLOAT"];
            } else {

                $time = microtime(true);

                if (is_string($time)) {

                    $timeParts = explode(" ", $time);

                    $time = (float) $timeParts[1] + (float) $timeParts[0];
                }

                $this->
                        startTime = $time;
            }
        }

        /**
         * Attach Autoloader.
         */
        spl_autoload_register(
                array(
                    $this,
                    'Autoloader'
                )
        );

        /**
         * Attach Exception Handler.
         */
        set_exception_handler(
                array(
                    $this,
                    'ExceptionHandler'
                )
        );

        /**
         * Attach Error Handler.
         */
        $prevErrorHandler = set_error_handler(
                array(
                    $this,
                    'ErrorHandler'
                )
        );

        if ($prevErrorHandler &&
                is_callable($prevErrorHandler)) {

            $this->
                    prevErrorHandler = $prevErrorHandler;
        }

        /**
         * Attach Shutdown hook.
         */
        register_shutdown_function(
                array(
                    $this,
                    'Shutdown'
                )
        );
    }

    /**
     * Used to fetch the singleton instance object.
     *
     * @param string $instanceType Provide this to call setup in same function.
     * @param string $configType Provide this as 'INI' (Only option available).
     * @return \FluitoPHP\FluitoPHP Returns this instance object.
     * @author Vipin Jain
     * @since  0.1
     */
    static public function GetInstance($instanceType = null, $configType = 'INI') {

        if (self::$instance === null) {

            self::$instance = new self();
        }

        if ($instanceType) {

            self::$instance->
                    Setup($instanceType, $configType);
        }

        return self::$instance;
    }

    /**
     * Used to fetch the class file with the class name and include it with the run.
     * This function is used by FluitoPHP system.
     *
     * @param string $className This is the class name this function required to fetch.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Autoloader($className) {

        $className = substr($className, 0, 10) === "FluitoPHP\\" ? substr($className, 10) : $className;
        $className = substr($className, 0, 11) === "\\FluitoPHP\\" ? substr($className, 11) : $className;

        $extension = substr($className, 0, 10) === "extension\\";

        $className = $extension ? substr($className, 10) : $className;

        $className = str_replace('\\', DS, $className);

        if ($extension) {

            if (file_exists(EXTENSIONS . DS . $className . '.class.php')) {

                require_once( EXTENSIONS . DS . $className . '.class.php' );
            }
        } else {

            if (file_exists(CORE . DS . $className . '.class.php')) {

                require_once( CORE . DS . $className . '.class.php' );
            } else if (file_exists(EXTENSIONS . DS . $className . '.class.php')) {

                require_once( EXTENSIONS . DS . $className . '.class.php' );
            } else if (file_exists(LIB . DS . $className . '.class.php')) {

                require_once( LIB . DS . $className . '.class.php' );
            }
        }
    }

    /**
     * Used to handle errors.
     *
     * @param int $severity Contains the level of the error raised, as an integer.
     * @param string $errstr Contains the error message, as a string.
     * @param string $errfile Contains the filename that the error was raised in, as a string.
     * @param int $errline Contains the line number the error was raised at, as an integer.
     * @param array $errcontext This points to the active symbol table at the point the error occurred.
     * @author Vipin Jain
     * @since  0.1
     */
    public function ErrorHandler($severity, $errstr, $errfile, $errline, $errcontext) {

        if (!( error_reporting() & $severity )) {

        }

        if ($this->
                prevErrorHandler) {

            call_user_func($this->
                    prevErrorHandler, $severity, $errstr, $errfile, $errline, $errcontext);
        }

        $this->
                ExceptionHandler(new \ErrorException($errstr, 0, $severity, $errfile, $errline));
    }

    /**
     * Used to handle exceptions.
     *
     * @param \Exception $exception This method will handle all the exceptions which are occurred in the system.
     * @author Vipin Jain
     * @since  0.1
     */
    public function ExceptionHandler($exception) {

        if ($exception instanceof \ErrorException &&
                in_array($exception->
                                getSeverity(), [2, 8, 32, 128, 512, 1024, 8192, 16384])) {

            $this->
                    noticeWarnings[] = $exception;
        } else {

            ob_get_clean();
            ob_start();

            $this->
                    Request()->
                    HandleError($exception);

            $beforeOp = ob_get_clean();

            ob_start();

            $this->
                    Response()->
                    HandleError();

            $this->
                    output = ob_get_clean() . $beforeOp;
        }
    }

    /**
     * This method ends the application run.
     *
     * @author Vipin Jain
     * @since  0.1
     */
    public function Shutdown() {

        ob_start();

        $this->
                Events()->
                Run('FluitoPHP.Shutdown');

        $this->
                Events()->
                Run('FluitoPHP.SystemShutdown');

        if (DEBUG) {

            $noticeWarningsHTML = "";

            $noticeWarnings = $this->
                    NoticeWarningsList();

            if (count($noticeWarnings)) {

                $noticeWarningsText = "Notice-Warnings: Please debug for notice and warnings.";
                foreach ($noticeWarnings as $key => $value) {

                    $key = $key + 1;

                    $htmlValue = str_replace("\n", "<br/>", $value);
                    $noticeWarningsHTML .= "<li class=\"list-group-item\" style=\"overflow: auto;\"><div>{$key}.) {$htmlValue}</div></li>";
                }

                $noticeWarningsHTML = '<div class="alert alert-warning alert-dismissible fade show" role="alert">' .
                        'Notice and Warnings:<ul class="list-group" style="max-height: 250px;">' .
                        $noticeWarningsHTML . '</ul><button type="button" class="close"' .
                        ' data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;' .
                        '</span></button></div>';

                $this->
                        Response()->
                        SetHeader($noticeWarningsText);
            }

            $time = microtime(true);

            if (is_string($time)) {

                $timeParts = explode(" ", $time);

                $time = (float) $timeParts[1] + (float) $timeParts[0];
            }

            $totalRuntimeText = 'Total-Runtime: ' . ($time - $this->
                    startTime) . ' seconds';

            $totalRuntimeHTML = '<div class="alert alert-info alert-dismissible fade show float-left" role="alert" id="FluitoPHP-debug">' .
                    $totalRuntimeText . '<br/><small>Automatically closes in 4 seconds.</small><button type="button" class="close"' .
                    ' data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;' .
                    '</span></button></div>';

            $this->
                    Response()->
                    SetHeader($totalRuntimeText);

            $position = strrpos(strtolower($this->
                            output), '</body>');

            if ($position !== false) {

                $this->
                        output = substr($this->
                                output, 0, $position) .
                        '<div class="FluitoPHP-debug-parent position-fixed" style="bottom: 0.5em; left: 0.5em; right: 0.5em">' .
                        '<div class="FluitoPHP-debug-mid"><div class="FluitoPHP-debug-child">' . $noticeWarningsHTML .
                        $totalRuntimeHTML . '</div></div></div><script>' .
                        'window.setTimeout(function($){$(\'#FluitoPHP-debug\').alert(\'close\');}, 4000, jQuery);' .
                        '</script>' . substr($this->
                                output, $position);
            }
        }

        ob_get_clean();

        echo $this->
        output;
    }

    /**
     * Used to initialize the application setup.
     *
     * @param string $instanceType Provide the instance type, e.g. PRODUCTION, DEVELOPMENT etc
     * @param string $configType Provide the configuration type, e.g. INI etc
     * @return $this Self reference is returned for chained calls.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Setup($instanceType, $configType = 'INI') {

        require_once( CORE . DS . 'Base' . DS . 'Base.class.php' );
        require_once( CORE . DS . 'Request' . DS . 'Request.class.php' );
        require_once( CORE . DS . 'Response' . DS . 'Response.class.php' );
        require_once( CORE . DS . 'View' . DS . 'View.class.php' );
        require_once( CORE . DS . 'Controller' . DS . 'Controller.class.php' );
        require_once( CORE . DS . 'Config' . DS . 'Config.class.php' );
        require_once( CORE . DS . 'ErrorHandler' . DS . 'ErrorHandler.class.php' );
        require_once( CORE . DS . 'HttpException' . DS . 'HttpException.class.php' );

        $this->
                instanceType = $instanceType;

        $this->
                configType = $configType;

        $this->
                globalConfig = new Config\Config(['instanceType' => $this->
            instanceType, 'module' => null, 'configType' => $this->
            configType]);

        $this->
                Request();

        $this->
                Response();

        $this->
                Request()->
                Setup();

        $this->
                Response()->
                Setup();

        return $this;
    }

    /**
     * Used to run the application and render the response.
     *
     * @author Vipin Jain
     * @since  0.1
     */
    public function Run() {

        ob_start();

        $this->
                Boot();

        $this->
                Request()->
                Run();

        $beforeOp = ob_get_clean();

        ob_start();

        $this->
                Response()->
                Run();

        $this->
                output = ob_get_clean() . $beforeOp;
    }

    /**
     * Used to fetch the configuration variable of the application.
     *
     * @param string $variable Provide the variable name to be fetched.
     * @return mixed Returns the value of the variable, either array or string.
     * @throws \Exception Exception is thrown if the configuration file is not read.
     * @author Vipin Jain
     * @since  0.1
     */
    public function GetConfig($variable = null) {

        return $this->
                        globalConfig->
                        Get($variable);
    }

    /**
     * Used to fetch the configuration variable of the module.
     *
     * @param string $variable Provide the variable name to be fetched.
     * @param bool $freezeModule Provide true to fix the module. Module once fixed it cannot be updated.
     * @return mixed Returns the value of the variable, either array or string.
     * @throws \Exception Exception is thrown if the configuration file is not read.
     * @author Vipin Jain
     * @since  0.1
     */
    public function GetModuleConfig($variable = null, $freezeModule = true) {

        if (!$this->
                moduleConfig ||
                        $this->
                        moduleConfig->
                        GetModule() !== $this->
                        Request()->
                        GetModule()) {

            $this->
                    moduleConfig = new Config\Config(['instanceType' => $this->
                instanceType, 'module' => $this->
                        Request()->
                        GetModule(), 'configType' => $this->
                configType]);
        }

        if ($freezeModule === true) {

            $this->
                    Request()->
                    FixModule();
        }

        return $this->
                        moduleConfig->
                        Get($variable);
    }

    /**
     * Used to run bootloaders.
     *
     * @author Vipin Jain
     * @since  0.1
     */
    public function Boot() {

        require_once( CORE . DS . 'Boot' . DS . 'Boot.class.php' );

        $this->
                bootloaderFiles = scandir(BOOT);
        array_shift($this->
                bootloaderFiles);
        array_shift($this->
                bootloaderFiles);

        foreach ($this->
        bootloaderFiles as $file) {

            $classname = str_replace('Boot.class.php', '', $file) . 'Boot';

            if (!class_exists($classname)) {

                require_once(realpath(BOOT . DS . $file));

                $class_parents = class_parents($classname);

                if (isset($class_parents['FluitoPHP\Boot\Boot']) ||
                        isset($class_parents['\FluitoPHP\Boot\Boot'])) {
                    $this->
                            bootloaders[$classname] = new $classname();

                    $this->
                            bootloaders[$classname]->
                            Run();
                }
            }
        }

        $this->
                Request()->
                FixModule();

        $this->
                moduleBootloaderPath = MODULE . DS . $this->
                        Request()->
                        GetModule() . MODBOOT;

        $this->
                bootloaderModuleFiles = scandir($this->
                moduleBootloaderPath);

        array_shift($this->
                bootloaderModuleFiles);
        array_shift($this->
                bootloaderModuleFiles);

        foreach ($this->
        bootloaderModuleFiles as $file) {

            $classname = str_replace('Boot.class.php', '', $file) . 'Boot';

            if (!class_exists($classname)) {

                require_once(realpath($this->
                                moduleBootloaderPath . DS . $file));

                $class_parents = class_parents($classname);

                if (isset($class_parents['FluitoPHP\Boot\Boot']) ||
                        isset($class_parents['\FluitoPHP\Boot\Boot'])) {
                    $this->
                            bootloaders[$classname] = new $classname();

                    $this->
                            bootloaders[$classname]->
                            Run();
                }
            }
        }
    }

    /**
     * Used to get the list of non-fatal errors/notices/warnings generated in the runtime.
     *
     * @return array Returns the list of non-fatal errors/notices/warnings generated in the runtime.
     * @author Vipin Jain
     * @since  0.1
     */
    public function NoticeWarningsList() {

        return $this->
                noticeWarnings;
    }

    /**
     * Used to load the extension and get the object.
     *
     * @param string $extension Provide the extension name it must be a qualified class name of the extension.
     * @param mixed $args Provide if any arguments needs to be provided to the class.
     * @param int $instance Provide the instance number of the extension. Normally singleton class have only one instance, providing instance other than zero will convert back to zero.
     * @return mixed Returns the object of the loaded extension.
     * @author Vipin Jain
     * @since  0.1
     */
    final public function Load($extension, $args = null, $instance = 0) {

        if (!is_string($extension) ||
                !strlen($extension)) {

            return false;
        }

        if (file_exists(EXTENSIONS . DS . $extension . DS . $extension . '.class.php')) {

            $class = "\\FluitoPHP\\extension\\{$extension}\\{$extension}";
        } else if (file_exists(LIB . DS . $extension . DS . $extension . '.class.php')) {

            $class = "\\FluitoPHP\\{$extension}\\{$extension}";
        } else {

            return false;
        }

        if (!is_int($instance) ||
                $instance < 0 ||
                is_callable(array($class, 'GetInstance'))) {

            $instance = 0;
        }

        if (!isset($this->
                        loadedExtensions[$extension][$instance])) {

            $object = null;

            if (is_callable(array($class, 'GetInstance'))) {

                if (isset($this->
                                loadedExtensions[$extension][0])) {

                    return $this->
                            loadedExtensions[$extension][0];
                }

                if ($args) {

                    $object = call_user_func(array($class, 'GetInstance'), $args);
                } else {

                    $object = call_user_func(array($class, 'GetInstance'));
                }
            } else {

                if ($args) {

                    $object = new $class($args);
                } else {

                    $object = new $class();
                }
            }

            if ($object === null) {

                return false;
            }

            if (!isset($this->
                            loadedExtensions[$extension])) {

                $this->
                        loadedExtensions[$extension] = array();
            }

            $this->
                    loadedExtensions[$extension][$instance] = $object;
        }

        return $this->
                loadedExtensions[$extension][$instance];
    }

    /**
     * Used to get request object.
     *
     * @return \FluitoPHP\Request\Request Used to fetch request object for quick access.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Request() {

        return \FluitoPHP\Request\Request::GetInstance();
    }

    /**
     * Used to get response object.
     *
     * @return \FluitoPHP\Response\Response Used to fetch response object for quick access.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Response() {

        return \FluitoPHP\Response\Response::GetInstance();
    }

    /**
     * Used to get database object.
     *
     * @return \FluitoPHP\Database\Database Used to fetch database object for quick access.
     * @author Vipin Jain
     * @since  0.1
     */
    public function DB() {

        require_once( CORE . DS . 'Database' . DS . 'Database.class.php' );

        return \FluitoPHP\Database\Database::GetInstance();
    }

    /**
     * Used to get session object.
     *
     * @return \FluitoPHP\Session\Session Used to fetch session object for quick access.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Session() {

        require_once( CORE . DS . 'Session' . DS . 'Session.class.php' );

        return \FluitoPHP\Session\Session::GetInstance();
    }

    /**
     * Used to get authentication object.
     *
     * @return \FluitoPHP\Authentication\Authentication Used to fetch authentication object for quick access.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Authentication() {

        require_once( CORE . DS . 'Authentication' . DS . 'Authentication.class.php' );

        return \FluitoPHP\Authentication\Authentication::GetInstance();
    }

    /**
     * Used to get authorization object.
     *
     * @return \FluitoPHP\Authorization\Authorization Used to fetch authorization object for quick access.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Authorization() {

        require_once( CORE . DS . 'Authorization' . DS . 'Authorization.class.php' );

        return \FluitoPHP\Authorization\Authorization::GetInstance();
    }

    /**
     * Used to get the model.
     *
     * @param type $modelName Provide the model name or table name.
     * @param string $connectionid Provide the connection id to use.
     * @return \FluitoPHP\Model\Model Returns the model for the table.
     * @throws \Exception Throws the exception if the model name is not correct.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Model($modelName, $connectionid = null) {

        if (!is_string($modelName) ||
                !$modelName) {

            throw new \Exception("Error: Please provide correct model name.");
        }

        $modelName = strtolower($modelName);

        $modelExactName = $modelName . (is_string($connectionid) &&
                strlen($connectionid) ? "_{$connectionid}" : '');

        if (!isset($this->
                        models[$modelExactName])) {

            require_once( CORE . DS . 'Model' . DS . 'Model.class.php' );

            $modelClass = '';

            if (file_exists(MODULE . DS . $this->
                                    Request()->
                                    GetModule() . MODELS . DS . $modelName . 'Model.class.php')) {

                require_once(MODULE . DS . $this->
                                Request()->
                                GetModule() . MODELS . DS . $modelName . 'Model.class.php');

                $parentClasses = class_parents($modelName . 'Model');

                if (isset($parentClasses['FluitoPHP\Model\Model']) ||
                        isset($parentClasses['\FluitoPHP\Model\Model'])) {

                    $modelClass = $modelName . 'Model';
                }
            }

            if ($modelClass) {

                $this->
                        models[$modelExactName] = new $modelClass;
            } else {

                $this->
                        models[$modelExactName] = new \FluitoPHP\Model\Model($modelName, $connectionid);
            }
        }

        return $this->
                models[$modelExactName];
    }

    /**
     * Used for storing data for data transfer in the current request.
     *
     * @param string $key Provide the key value to store the data in data array.
     * @param mixed $value Provide the data value to be stored.
     * @return boolean Returns true on success and false on failure.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Set($key, $value) {

        if (!is_string($key) ||
                !$key) {

            return false;
        }

        $this->
                data[$key] = $value;

        return true;
    }

    /**
     * Used for fetching data for data transfer in the current request.
     *
     * @param string $key Provide the key value to fetch the data from data array.
     * @return mixed Returns false if the key is not found in the data array else returns the value.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Get($key) {

        if (!is_string($key) ||
                !$key) {

            return false;
        }

        if (isset($this->
                        data[$key])) {

            return $this->
                    data[$key];
        }

        return false;
    }

    /**
     * Used to get events object.
     *
     * @return \FluitoPHP\Events\Events Used to fetch events object for quick access.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Events() {

        if (!isset($this->
                        loadedExtensions['Events'])) {

            $this->
                    loadedExtensions['Events'] = array();
            $this->
                    loadedExtensions['Events'][0] = \FluitoPHP\Events\Events::GetInstance();
        }

        return $this->
                loadedExtensions['Events'][0];
    }

    /**
     * Used to get filters object.
     *
     * @return \FluitoPHP\Filters\Filters Used to fetch filters object for quick access.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Filters() {

        if (!isset($this->
                        loadedExtensions['Filters'])) {

            $this->
                    loadedExtensions['Filters'] = array();
            $this->
                    loadedExtensions['Filters'][0] = \FluitoPHP\Filters\Filters::GetInstance();
        }

        return $this->
                loadedExtensions['Filters'][0];
    }

    /**
     * Used to extract array of data transfer array.
     *
     * @return array Returns the data transfer array.
     * @author Vipin Jain
     * @since  0.1
     */
    final public function Extract() {

        return $this->
                data;
    }

}
