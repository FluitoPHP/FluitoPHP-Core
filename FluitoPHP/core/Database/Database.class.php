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

namespace FluitoPHP\Database;

/**
 * Database Class.
 *
 * Used to initialize all the database connections required by your application.
 *
 * Variables:
 *      1. $instance
 *      2. $initStarted
 *      3. $initStartedModule
 *      4. $connections
 *      5. $defaultConn
 *
 * Functions:
 *      1. __construct
 *      2. GetInstance
 *      3. Setup
 *      4. SetupModule
 *      5. CreateConns
 *      6. DestroyConns
 *      7. Shutdown
 *      8. Conn
 *
 * @author Vipin Jain
 * @since  0.1
 */
class Database {

    /**
     * Used for storing Singleton instance.
     *
     * @var \FluitoPHP\Database\Database
     * @author Vipin Jain
     * @since  0.1
     */
    static private $instance = null;

    /**
     * Used for storing InitStarted flag.
     *
     * @var bool
     * @author Vipin Jain
     * @since  0.1
     */
    static private $initStarted = false;

    /**
     * Used for storing InitStarted flag for module.
     *
     * @var bool
     * @author Vipin Jain
     * @since  0.1
     */
    static private $initStartedModule = false;

    /**
     * Used to store the connections in an associated array.
     *
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    private $connections = [];

    /**
     * Used to store default connection key.
     *
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    private $defaultConn = 'default';

    /**
     * Private constructor to use this class as a singleton class.
     *
     * @return void Return is called in the constructor if the initialization is already started, to avoid looping and displaying 500 error.
     * @author Vipin Jain
     * @since  0.1
     */
    private function __construct() {

    }

    /**
     * Used to fetch the Instance object globally.
     *
     * @return \FluitoPHP\Database\Database Returns this instance object.
     * @author Vipin Jain
     * @since  0.1
     */
    static public function GetInstance() {

        if (self::$instance === null) {

            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Used to setup the databases.
     *
     * @author Vipin Jain
     * @since  0.1
     */
    public function Setup() {

        if (self::$initStarted) {

            return;
        }

        self::$initStarted = true;

        require_once( dirname(__FILE__) . DS . 'DBConnectionHelper.interface.php' );
        require_once( dirname(__FILE__) . DS . 'DBQueryHelper.class.php' );

        $connectionsData = \FluitoPHP\FluitoPHP::GetInstance()->
                GetConfig('DATABASE');

        $connectionsData = $connectionsData ? $connectionsData : [];

        if (isset($connectionsData['default']) &&
                $connectionsData['default'] != '' &&
                isset($connectionsData['connections'][$connectionsData['default']])) {

            $default = $connectionsData['default'];
        }

        foreach ($connectionsData['connections'] as $id => & $details) {

            $details['autocommit'] = !(isset($details['autocommit']) &&
                    $details['autocommit'] != '1');

            $this->
                    CreateConns($id, (object) $details);
        }

        if (isset($default) &&
                isset($this->
                        connections[$default])) {

            $this->
                    defaultConn = $default;
        } elseif (!isset($this->
                        connections[$this->
                        defaultConn])) {
            $keys = array_keys($this->
                    connections);

            $this->
                    defaultConn = $keys[0];
        }

        \FluitoPHP\Events\Events::GetInstance()->
                Add('FluitoPHP.SystemShutdown', array($this,
                    'Shutdown'));
    }

    /**
     * Used to setup the databases.
     *
     * @author Vipin Jain
     * @since  0.1
     */
    public function SetupModule() {

        if (self::$initStartedModule) {

            return;
        }

        self::$initStartedModule = true;

        $connectionsData = \FluitoPHP\FluitoPHP::GetInstance()->
                GetConfig('DATABASE');

        $connectionsData = $connectionsData ? $connectionsData : ['connections' => []];

        $moduleConnectionsData = \FluitoPHP\FluitoPHP::GetInstance()->
                GetModuleConfig('DATABASE');

        $moduleConnectionsData = $moduleConnectionsData ? $moduleConnectionsData : ['connections' => []];


        foreach ($moduleConnectionsData['connections'] as $id => & $details) {

            if (in_array($id, $this->
                            connections)) {

                $this->
                        DestroyConns($id);
            }

            $details['autocommit'] = !(isset($details['autocommit']) &&
                    $details['autocommit'] != '1');

            $this->
                    CreateConns($id, (object) $details);
        }

        $connectionsData = array_replace_recursive($connectionsData, $moduleConnectionsData);

        if (isset($connectionsData['default']) &&
                $connectionsData['default'] != '' &&
                isset($connectionsData['connections'][$connectionsData['default']])) {

            $default = $connectionsData['default'];
        }

        if (isset($default) &&
                isset($this->
                        connections[$default])) {

            $this->
                    defaultConn = $default;
        } elseif (!isset($this->
                        connections[$this->
                        defaultConn])) {
            $keys = array_keys($this->
                    connections);

            $this->
                    defaultConn = $keys[0];
        }
    }

    /**
     * Used to create a connection in runtime.
     *
     * @param string $id Provide the identifier for the connection through which it can be accessed.
     * @param object $data Provide the connection details in the object.
     * @return bool Returns true on success and false on error.
     * @throws \Exception Throws exception if the helper class is faulty.
     * @author Vipin Jain
     * @since  0.1
     */
    public function CreateConns($id, $data) {

        if (isset($this->
                        connections[$id])) {

            return false;
        }

        $helperType = ucfirst($data->
                type);

        $connectionHelperType = $helperType . 'ConnectionHelper';
        $connectionHelperTypeClass = "\\FluitoPHP\\Database\\{$helperType}\\{$connectionHelperType}";
        $connectionHelperTypeCustomClass = "\\FluitoPHP\\extension\\Database\\{$helperType}\\{$connectionHelperType}";

        if (!class_exists($connectionHelperTypeClass) || !class_exists($connectionHelperTypeCustomClass)) {

            if (file_exists(EXTENSIONS . DS . 'Database' . DS . $helperType . DS . $connectionHelperType . '.class.php')) {

                require_once( EXTENSIONS . DS . 'Database' . DS . $helperType . DS . $connectionHelperType . '.class.php' );
            } elseif (file_exists(LIB . DS . 'Database' . DS . $helperType . DS . $connectionHelperType . '.class.php')) {

                require_once( LIB . DS . 'Database' . DS . $helperType . DS . $connectionHelperType . '.class.php' );
            }
        }

        if (!class_exists($connectionHelperTypeClass)) {

            if (!class_exists($connectionHelperTypeCustomClass)) {

                return false;
            }
        }

        $queryHelperType = $helperType . 'QueryHelper';
        $queryHelperTypeClass = "\\FluitoPHP\\Database\\{$helperType}\\{$queryHelperType}";
        $queryHelperTypeCustomClass = "\\FluitoPHP\\extension\\Database\\{$helperType}\\{$queryHelperType}";

        if (!class_exists($queryHelperTypeClass) || !class_exists($queryHelperTypeCustomClass)) {

            if (file_exists(EXTENSIONS . DS . 'Database' . DS . $helperType . DS . $queryHelperType . '.class.php')) {

                require_once( EXTENSIONS . DS . 'Database' . DS . $helperType . DS . $queryHelperType . '.class.php' );
            } elseif (file_exists(LIB . DS . 'Database' . DS . $helperType . DS . $queryHelperType . '.class.php')) {

                require_once( LIB . DS . 'Database' . DS . $helperType . DS . $queryHelperType . '.class.php' );
            }
        }

        if (!class_exists($queryHelperTypeClass)) {

            if (!class_exists($queryHelperTypeCustomClass)) {

                return false;
            }
        }

        if (class_exists($connectionHelperTypeCustomClass)) {

            $connection = new $connectionHelperTypeCustomClass($id, $data);
        } else {

            $connection = new $connectionHelperTypeClass($id, $data);
        }

        if (!$connection instanceof \FluitoPHP\Database\DBConnectionHelper) {

            throw new \Exception('Error: Connection helper is faulty.');
        }


        if (class_exists($queryHelperTypeCustomClass)) {

            $parents = class_parents($queryHelperTypeCustomClass);
        } else {

            $parents = class_parents($queryHelperTypeClass);
        }

        if (!(isset($parents['FluitoPHP\Database\DBQueryHelper']) ||
                isset($parents['\FluitoPHP\Database\DBQueryHelper']))) {

            throw new \Exception('Error: Query helper is faulty.', 0);
        }

        if (!$connection->
                        Connected()) {

            return false;
        }

        $this->
                connections[$id] = $connection;

        return true;
    }

    /**
     * Used to destroy defined connections in runtime.
     *
     * @param string $id Provide the identifier which was used at the time of connection.
     * @return bool Returns true on success and false on error.
     * @author Vipin Jain
     * @since  0.1
     */
    public function DestroyConns($id = null) {

        if (isset($this->
                        connections[$id])) {

            return false;
        }

        if (!$this->
                        connections[$id]->
                        Disconnect()) {

            return false;
        }

        unset($this->
                connections[$id]);

        return true;
    }

    /**
     * Used to destroy all DB connections at the end of runtime. This method is invoked at the Shutdown Action.
     *
     * @throws \Exception Throws exception if any connection fails to disconnect.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Shutdown() {

        foreach ($this->
        connections as $id => & $connObj) {

            if (!$connObj->
                            Disconnect()) {

                throw new \Exception("Error: Unable to terminate connection. Connection Identifier: " . $id);
            }
        }
    }

    /**
     * Used to fetch any of the open connections.
     *
     * @param string $id Provide the connection identifier to fetch the connection and query on the same.
     * @return \FluitoPHP\Database\DBConnectionHelper The helper class is returned having this \FluitoPHP\Database\DBConnectionHelper interface implemented.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Conn($id = null) {

        if (!self::$initStarted) {

            $this->
                    Setup();
        }

        if (!self::$initStartedModule && \FluitoPHP\FluitoPHP::GetInstance()->
                        Request()->
                        IsModuleFixed()) {

            $this->
                    SetupModule();
        }

        if (!$id ||
                !isset($this->
                        connections[$id])) {

            $id = $this->
                    defaultConn;
        }

        return $this->
                connections[$id];
    }

}
