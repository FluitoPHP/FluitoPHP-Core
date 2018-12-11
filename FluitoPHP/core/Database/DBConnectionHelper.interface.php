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

namespace FluitoPHP\Database;

/**
 * DBConnectionHelper Interface.
 *
 * This interface is used as a base model for the database connection and querying.
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
interface DBConnectionHelper {

    /**
     * Constructor to connect to database.
     *
     * @param string $id Provide the identifier for the connection through which it can be accessed.
     * @param object $details Provide the connection details in the object.
     * @author Neha Jain
     * @since  0.1
     */
    public function __construct($id, $details);

    /**
     * Used to check if the database is connected.
     *
     * @return bool Returns true if connected and false if not connected.
     * @author Neha Jain
     * @since  0.1
     */
    public function Connected();

    /**
     * Used to disconnect from database.
     *
     * @return bool Returns true if successfully disconnected and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function Disconnect();

    /**
     * Used to get the connection resource object.
     *
     * @return resource Returns connection object.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetConn();

    /**
     * Used to change auto commit to database.
     *
     * @param bool $mode Provide true for auto commit and false for manual commit.
     * @return bool Returns true if the modification runs successfully.
     * @author Neha Jain
     * @since  0.1
     */
    public function AutoCommit($mode = true);

    /**
     * Used to manually commit to database.
     *
     * @return bool Returns true on success and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function Commit();

    /**
     * Used to rollback transactions done on database. This method is useful when the auto commit is turned off.
     *
     * @return bool Returns true on success and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function Rollback();

    /**
     * Used to query on database.
     *
     * @param string $query The query in string format needed to be run on this connection.
     * @return bool Returns true or insert id if the query runs successfully.
     * @author Neha Jain
     * @since  0.1
     */
    public function Query($query);

    /**
     * Used to get number of affected/fetched rows by last query from this connection.
     *
     * @return int Returns the number of rows affected or returned by the last query and returns 0 if this is not a DML query. Returns -1 if the query ran to error.
     * @author Neha Jain
     * @since  0.1
     */
    public function AffectedRows();

    /**
     * Used to get the info of the last query ran on this connection.
     *
     * @return string Returns the message for the latest query which ran on this connection.
     * @author Neha Jain
     * @since  0.1
     */
    public function LastInfo();

    /**
     * Used to fetch result of the last query ran on this connection.
     *
     * @param bool $objectType Provide true if you require row in object format, false will return result rows in associative array.
     * @return array Returns resultant rows in array of associative array or object, depending on the parameter.
     * @author Neha Jain
     * @since  0.1
     */
    public function LastResult($objectType = false);

    /**
     * Used to fetch the results in an array which is retrieved by the provided query from this connection.
     *
     * @param string $query The query in string format needed to be run on this connection.
     * @param bool $objectType Provide true if you require row in object format, false will return result rows in associative array.
     * @return array Returns resultant rows in array of associative array or object, depending on the 2nd parameter.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetResults($query, $objectType = false);

    /**
     * Used to fetch the row in an array/object which is retrieved by the provided query from this connection.
     *
     * @param string $query The query in string format needed to be run on this connection.
     * @param bool $objectType Provide true if you require row in object format, false will return result rows in associative array.
     * @return mixed Returns resultant row in associative array or object, depending on the 2nd parameter.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetRow($query, $objectType = false);

    /**
     * Used to fetch the column in an array which is retrieved by the provided query from this connection.
     *
     * @param string $query The query in string format needed to be run on this connection.
     * @param bool $objectType Provide true if you require row in object format, false will return result rows in associative array.
     * @return array Returns resultant column in array.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetColumn($query, $objectType = false);

    /**
     * Used to fetch the variable which is retrieved by the provided query from this connection.
     *
     * @param string $query The query in string format needed to be run on this connection.
     * @return string The first variable of first row will be fetched and returned in string format.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetVar($query);

    /**
     * Used to fetch the columns info from last ran query from this connection.
     *
     * @return array Returns the columns info.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetColInfo();

    /**
     * Used to get the error code from last query.
     *
     * @return int Returns the error code from last query ran on this connection.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetErrorCode();

    /**
     * Used to get the SQL error code from last query.
     *
     * @return string Returns the SQL error code from last query ran on this connection.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetSQLError();

    /**
     * Used to get the error string from last query.
     *
     * @return string Returns the error string from last query ran on this connection.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetError();

    /**
     * Used to get the warnings from last query ran on this connection.
     *
     * @return string Returns the database warnings from last query.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetWarnings();

    /**
     * Used to get current connection status of database server.
     *
     * @return string Returns the current connection status of database server.
     * @author Neha Jain
     * @since  0.1
     */
    public function ClientStats();

    /**
     * Used to get current system status of current database server.
     *
     * @return string Returns the current system status of database server.
     * @author Neha Jain
     * @since  0.1
     */
    public function Stats();

    /**
     * Used to get or set character set of current application to make database results to be provided in the same character set.
     *
     * @param string $charset Character set of the client application.
     * @return mixed Returns character set if the parameter provided is null, else it tries to set the connection character set and returns true on success and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function CharSet($charset = null);

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
    public function ChangeUser($username, $password, $database = null);

    /**
     * Used to change the selected database.
     *
     * @param string $database Provide database name to select.
     * @return bool Returns true on success and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function SelectDB($database);

    /**
     * Used to start a new transaction.
     *
     * @return bool Returns true on success and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function BeginTransaction();

    /**
     * Used to create a new query helper object.
     *
     * @return \FluitoPHP\Database\DBQueryHelper This function will return a new query helper.
     * @author Neha Jain
     * @since  0.1
     */
    public function Helper();
}
