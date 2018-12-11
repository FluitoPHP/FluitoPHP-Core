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

namespace FluitoPHP\Database\Mysql;

/**
 * MysqlConnectionHelper Class.
 *
 * This class is used to connect and query a mysql database instance.
 *
 * Variables:
 *      1. $id
 *      2. $connection
 *      3. $host
 *      4. $port
 *      5. $socket
 *      6. $username
 *      7. $password
 *      8. $database
 *      9. $autocommit
 *      10. $lastQuery
 *      11. $lastResult
 *      12. $lastColInfo
 *
 * Functions:
 *      1. __construct
 *      2. Connected
 *      3. Disconnect
 *      4. GetConn
 *      5. AutoCommit
 *      6. Commit
 *      7. Rollback
 *      8. Query
 *      9. AffectedRows
 *      10. LastInfo
 *      11. LastResult
 *      12. GetResults
 *      13. GetRow
 *      14. GetColumn
 *      15. GetVar
 *      16. GetColInfo
 *      17. GetErrorCode
 *      18. GetSQLError
 *      19. GetError
 *      20. GetWarnings
 *      21. ClientStats
 *      22. Stats
 *      23. CharSet
 *      24. ChangeUser
 *      25. SelectDB
 *      26. BeginTransaction
 *      27. Helper
 *
 * @author Neha Jain
 * @since  0.1
 */
class MysqlConnectionHelper implements \FluitoPHP\Database\DBConnectionHelper {

    /**
     * Used to store identifier of this connection.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    protected $id;

    /**
     * Used to store connection class.
     *
     * @var \mysqli
     * @author Neha Jain
     * @since  0.1
     */
    protected $connection = null;

    /**
     * Used to store the host of the connection.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    protected $host = null;

    /**
     * Used to store the port of the connection.
     *
     * @var int
     * @author Neha Jain
     * @since  0.1
     */
    protected $port = null;

    /**
     * Used to store the socket of the connection.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    protected $socket = null;

    /**
     * Used to store the username of the connection.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    protected $username = null;

    /**
     * Used to store the password of the connection.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    protected $password = null;

    /**
     * Used to store the database of the connection.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    protected $database = null;

    /**
     * Used to store the autocommit of the connection.
     *
     * @var bool
     * @author Neha Jain
     * @since  0.1
     */
    protected $autocommit = true;

    /**
     * Used to store the last query performed on the connection.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    protected $lastQuery = null;

    /**
     * Used to store the last result returned from the connection. Format array or string.
     *
     * @var mixed
     * @author Neha Jain
     * @since  0.1
     */
    protected $lastResult = null;

    /**
     * Used to store the last column info.
     *
     * @var array
     * @author Neha Jain
     * @since  0.1
     */
    protected $lastColInfo = [];

    /**
     * Constructor to connect to database.
     *
     * @param string $id Provide the identifier for the connection through which it can be accessed.
     * @param object $details Provide the connection details in the object.
     * @author Neha Jain
     * @since  0.1
     */
    public function __construct($id, $details) {

        $this->
                id = $id;

        if (!isset($details->
                        host) ||
                !isset($details->
                        username) ||
                !isset($details->
                        password) ||
                !isset($details->
                        database)) {

            throw new \Exception('Error: Database details faulty.');
        }

        $this->
                host = $details->
                host;

        if (isset($details->
                        port)) {

            $this->
                    port = intval($details->
                    port);
        } else {

            $this->
                    port = intval(ini_get('mysqli.default_port'));
        }

        if (isset($details->
                        socket)) {

            $this->
                    socket = $details->
                    socket;
        } else {

            $this->
                    socket = ini_get('mysqli.default_socket');
        }

        $this->
                username = $details->
                username;

        $this->
                password = $details->
                password;

        $this->
                database = $details->
                database;

        $this->
                autocommit = $details->
                autocommit;

        $this->
                connection = new \mysqli($this->
                host, $this->
                username, $this->
                password, $this->
                database, $this->
                port, $this->
                socket);

        if ($this->
                connection->
                connect_errno) {

            throw new \Exception('Error: Could not connect: ' . $this->
            connection->
            connect_error . ' Error code: ' . $this->
            connection->
            connect_errno);
        }

        $this->
                AutoCommit($this->
                        autocommit);
    }

    /**
     * Used to check if the database is connected.
     *
     * @return bool Returns true if connected and false if not connected.
     * @author Neha Jain
     * @since  0.1
     */
    public function Connected() {

        return $this->
                        connection->
                        ping();
    }

    /**
     * Used to disconnect from database.
     *
     * @return bool Returns true if successfully disconnected and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function Disconnect() {

        if (!$this->
                        Connected()) {

            return true;
        }

        if (!$this->
                        AutoCommit()) {

            $this->
                    Commit();
        }

        return $this->
                        connection->
                        close();
    }

    /**
     * Used to get the connection resource object.
     *
     * @return resource Returns connection object.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetConn() {

        return $this->
                connection;
    }

    /**
     * Used to change auto commit to database.
     *
     * @param bool $mode Provide true for auto commit and false for manual commit.
     * @return bool Returns true if the modification runs successfully.
     * @author Neha Jain
     * @since  0.1
     */
    public function AutoCommit($mode = true) {

        if (!is_bool($mode)) {

            return false;
        }

        return $this->
                        connection->
                        autocommit($mode);
    }

    /**
     * Used to manually commit to database.
     *
     * @return bool Returns true on success and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function Commit() {

        return $this->
                        connection->
                        commit();
    }

    /**
     * Used to rollback transactions done on database. This method is useful when the auto commit is turned off.
     *
     * @return bool Returns true on success and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function Rollback() {

        return $this->
                        connection->
                        rollback();
    }

    /**
     * Used to query on database.
     *
     * @param string $query The query in string format needed to be run on this connection.
     * @return bool Returns true or insert id if the query runs successfully.
     * @author Neha Jain
     * @since  0.1
     */
    public function Query($query) {

        $this->
                lastResult = null;

        $result = $this->
                connection->
                query($query);

        $return = $result ? true : false;

        $this->
                lastResult = null;

        if ($result instanceof \mysqli_result) {

            $this->
                    lastResult = $result->
                    fetch_all(MYSQLI_ASSOC);

            $this->
                    lastColInfo = $result->
                    fetch_fields();

            $result->
                    close();
        } else {

            $this->
                    lastResult = null;

            $this->
                    lastColInfo = [];
        }

        if ($this->
                connection->
                insert_id) {

            $return = $this->
                    connection->
                    insert_id;
        }

        return $return;
    }

    /**
     * Used to get number of affected/fetched rows by last query from this connection.
     *
     * @return int Returns the number of rows affected or returned by the last query and returns 0 if this is not a DML query. Returns -1 if the query ran to error.
     * @author Neha Jain
     * @since  0.1
     */
    public function AffectedRows() {

        return $this->
                connection->
                affected_rows;
    }

    /**
     * Used to get the info of the last query ran on database.
     *
     * @return string Returns the message for the latest query which ran on database.
     * @author Neha Jain
     * @since  0.1
     */
    public function LastInfo() {

        return $this->
                connection->
                info;
    }

    /**
     * Used to fetch result of the last query ran on database.
     *
     * @param bool $objectType Provide true if you require row in object format, false will return result rows in associative array.
     * @return array Returns resultant rows in array of associative array or object, depending on the parameter.
     * @author Neha Jain
     * @since  0.1
     */
    public function LastResult($objectType = false) {

        if (!$this->
                lastResult) {

            return false;
        }

        $result = $this->
                lastResult;

        if ($objectType) {

            foreach ($result as $key => $value) {

                $result[$key] = (object) $value;
            }
        }

        return $result;
    }

    /**
     * Used to fetch the results in an array which is retrieved by the provided query from this connection.
     *
     * @param string $query The query in string format needed to be run on this connection.
     * @param bool $objectType Provide true if you require row in object format, false will return result rows in associative array.
     * @return array Returns resultant rows in array of associative array or object, depending on the 2nd parameter.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetResults($query, $objectType = false) {

        if (!$this->
                        Query($query)) {

            return false;
        }

        return $this->
                        LastResult($objectType);
    }

    /**
     * Used to fetch the row in an array/object which is retrieved by the provided query from this connection.
     *
     * @param string $query The query in string format needed to be run on this connection.
     * @param bool $objectType Provide true if you require row in object format, false will return result rows in associative array.
     * @return mixed Returns resultant row in associative array or object, depending on the 2nd parameter.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetRow($query, $objectType = false) {

        if (!$this->
                        Query($query)) {

            return false;
        }


        $this->
                LastResult($objectType);

        if (count($this->
                        lastResult) > 0) {

            $this->
                    lastResult = $this->
                    lastResult[0];
        } else {

            $this->
                    lastResult = false;
        }

        return $this->
                lastResult;
    }

    /**
     * Used to fetch the column in an array which is retrieved by the provided query from this connection.
     *
     * @param string $query The query in string format needed to be run on this connection.
     * @param bool $objectType Provide true if you require row in object format, false will return result rows in associative array.
     * @return array Returns resultant column in array.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetColumn($query, $objectType = false) {

        if (!$this->
                        Query($query)) {

            return false;
        }


        $this->
                LastResult($objectType);

        if (count($this->
                        lastResult) > 0) {

            if ($objectType) {

                $tempArray = (array) $this->
                        lastResult[0];
                reset($tempArray);
                $firstColumn = key($tempArray);

                $tempArray = $this->
                        lastResult;

                $this->
                        lastResult = [];

                foreach ($tempArray as $key => $value) {

                    $this->
                            lastResult[$key] = (object) array($firstColumn => $value[$firstColumn]);
                }
            } else {

                $tempArray = $this->
                        lastResult[0];
                reset($tempArray);
                $firstColumn = key($tempArray);

                $tempArray = $this->
                        lastResult;

                $this->
                        lastResult = [];

                foreach ($tempArray as $key => $value) {

                    $this->
                            lastResult[$key] = array($firstColumn => $value[$firstColumn]);
                }
            }
        } else {
            $this->
                    lastResult = false;
        }

        return $this->
                lastResult;
    }

    /**
     * Used to fetch the variable which is retrieved by the provided query from this connection.
     *
     * @param string $query The query in string format needed to be run on this connection.
     * @return string The first variable of first row will be fetched and returned in string format.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetVar($query) {

        if (!$this->
                        Query($query)) {

            return false;
        }


        $this->
                LastResult();

        if (count($this->
                        lastResult) > 0) {

            $this->
                    lastResult = $this->
                    lastResult[0];

            reset($this->
                    lastResult);
            $firstColumn = key($this->
                    lastResult);

            $this->
                    lastResult = $this->
                    lastResult[$firstColumn];
        } else {

            $this->
                    lastResult = false;
        }

        return $this->
                lastResult;
    }

    /**
     * Used to fetch the columns info from last ran query from this connection.
     *
     * @return array Returns the columns info.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetColInfo() {

        return $this->
                lastColInfo;
    }

    /**
     * Used to get the error code from last query.
     *
     * @return int Returns the error code from last query ran on this connection.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetErrorCode() {

        return $this->
                connection->
                errno;
    }

    /**
     * Used to get the SQL error code from last query.
     *
     * @return string Returns the SQL error code from last query ran on this connection.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetSQLError() {

        return $this->
                connection->
                sqlstate;
    }

    /**
     * Used to get the error string from last query.
     *
     * @return string Returns the error string from last query ran on this connection.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetError() {

        return $this->
                connection->
                error;
    }

    /**
     * Used to get the warnings from last query ran on this connection.
     *
     * @return string Returns the database warnings from last query.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetWarnings() {

        if ($this->
                connection->
                warning_count) {

            $return = [];

            $result = $this->
                    connection
                    ->query("SHOW WARNINGS");

            if ($result instanceof \mysqli_result) {

                $return = $result->
                        fetch_all(MYSQLI_ASSOC);
            }

            $result->
                    free();

            return $return;
        }

        return false;
    }

    /**
     * Used to get current connection status of database server.
     *
     * @return string Returns the current connection status of database server.
     * @author Neha Jain
     * @since  0.1
     */
    public function ClientStats() {

        return $this->
                        connection->
                        get_connection_stats();
    }

    /**
     * Used to get current system status of current database server.
     *
     * @return string Returns the current system status of database server.
     * @author Neha Jain
     * @since  0.1
     */
    public function Stats() {

        return $this->
                        connection->
                        stat();
    }

    /**
     * Used to get or set character set of current application to make database results to be provided in the same character set.
     *
     * @param string $charset Character set of the client application.
     * @return mixed Returns character set if the parameter provided is null, else it tries to set the connection character set and returns true on success and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function CharSet($charset = null) {

        if ($charset) {

            return $this->
                            connection->
                            set_charset($charset);
        } else {

            return $this->
                            connection->
                            character_set_name();
        }
    }

    /**
     * Used to update the current user of the connection. This method is used to change the user or change the database without terminating this connection and creating a new one.
     *
     * @param string $username Provide user name of the database user.
     * @param string $password Provide user password of the database user.
     * @param string $database Provide database to select else current database is used.
     * @return bool Returns true on success and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function ChangeUser($username, $password, $database = null) {

        if ($database) {

            $database = $this->
                    database;
        }

        if (!$this->
                        connection->
                        change_user($username, $password, $database)) {

            return false;
        }

        $this->
                username = $username;
        $this->
                password = $password;
        $this->
                database = $database;

        return true;
    }

    /**
     * Used to change the selected database.
     *
     * @param string $database Provide database name to select.
     * @return bool Returns true on success and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function SelectDB($database) {

        if (!$this->
                        connection->
                        select_db($database)) {

            return false;
        }

        $this->
                database = $database;

        return true;
    }

    /**
     * Used to start a new transaction.
     *
     * @return bool Returns true on success and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function BeginTransaction() {

        $flag = func_get_arg(0);
        $name = func_get_arg(1);

        if ($flag === false) {

            return $this->
                            connection->
                            begin_transaction();
        } else {

            if ($name === false) {

                return $this->
                                connection->
                                begin_transaction($flag);
            } else {

                return $this->
                                connection->
                                begin_transaction($flag, $name);
            }
        }
    }

    /**
     * Used to create a new query helper object.
     *
     * @return \FluitoPHP\Database\MysqlQueryHelper This function will return a new query helper.
     * @author Neha Jain
     * @since  0.1
     */
    public function Helper() {

        return new \FluitoPHP\Database\Mysql\MysqlQueryHelper($this);
    }

}
