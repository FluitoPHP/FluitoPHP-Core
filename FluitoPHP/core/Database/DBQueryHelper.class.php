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
 * DBQueryHelper Class.
 *
 * This class is used as a base model for the database query generation.
 *
 * Variables:
 *      1. $connection
 *      2. $type
 *      3. $customType
 *      4. $query
 *      5. $tables
 *      6. $distinct
 *      7. $columns
 *      8. $where
 *      9. $group
 *      10. $having
 *      11. $order
 *      12. $perpage
 *      13. $page
 *      14. $values
 *      15. $indexes
 *      16. $viewSelect
 *      17. $temporary
 *      18. $reqAutoIncrement
 *      19. $addArgs
 *
 * Functions:
 *      1. __construct
 *      2. Generate
 *      3. Select
 *      4. CheckTable
 *      5. CreateTable
 *      6. AlterTable
 *      7. TruncateTable
 *      8. DropTable
 *      9. CreateView
 *      10. DropView
 *      11. Insert
 *      12. Update
 *      13. Delete
 *      14. Custom
 *      15. Query
 *      16. GetResults
 *      17. GetRow
 *      18. GetColumn
 *      19. GetVar
 *      20. GetColInfo
 *      21. IsSelect
 *      22. Meta
 *      23. GenCustom
 *      24. RetSelect
 *      25. RetCheckTable
 *      26. RetCreateTable
 *      27. RetAutoIncrement
 *      28. RetAlterTable
 *      29. RetTruncateTable
 *      30. RetDropTable
 *      31. RetCreateView
 *      32. RetDropView
 *      33. RetInsert
 *      34. RetUpdate
 *      35. RetDelete
 *      36. ResolveMeta
 *
 * @author Vipin Jain
 * @since  0.1
 */
abstract class DBQueryHelper {

    /**
     * Used to store the type of query to be generated.
     *
     * @var \FluitoPHP\Database\DBConnectionHelper
     * @author Vipin Jain
     * @since  0.1
     */
    protected $connection = null;

    /**
     * Used to store the type of query to be generated.
     *
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    private $type = null;

    /**
     * Used to store the custom type of query to be generated.
     *
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    private $customType = null;

    /**
     * Used to store the generated query.
     *
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    private $query = '';

    /**
     * Used to store required tables and their joining criteria.
     *
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    protected $tables = [];

    /**
     * Used to store distinct criteria.
     *
     * @var bool
     * @author Vipin Jain
     * @since  0.1
     */
    protected $distinct = false;

    /**
     * Used to store the columns to be fetched.
     *
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    protected $columns = [];

    /**
     * Used to store the where clauses.
     *
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    protected $where = [];

    /**
     * Used to store the group by columns.
     *
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    protected $group = [];

    /**
     * Used to store the having clauses.
     *
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    protected $having = [];

    /**
     * Used to store the order columns.
     *
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    protected $order = [];

    /**
     * Used to store the per page count.
     *
     * @var int
     * @author Vipin Jain
     * @since  0.1
     */
    protected $perpage = -1;

    /**
     * Used to store the page number.
     *
     * @var int
     * @author Vipin Jain
     * @since  0.1
     */
    protected $page = 1;

    /**
     * Used to store the rows of values to be inserted in the table. Format array or \FluitoPHP\Database\DBQueryHelper.
     *
     * @var mixed
     * @author Vipin Jain
     * @since  0.1
     */
    protected $values = [];

    /**
     * Used to store the indexes/keys/foreign keys that needs to be created.
     *
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    protected $indexes = [];

    /**
     * Used to store the select clause of the create view statement.
     *
     * @var \FluitoPHP\Database\DBQueryHelper
     * @author Vipin Jain
     * @since  0.1
     */
    protected $viewSelect = null;

    /**
     * Used to store if the new table needs to be created is a normal table or temporary table.
     *
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    protected $temporary = 'N';

    /**
     * Used to store if the new table needs to create autoincrement column separately.
     *
     * @var bool
     * @author Vipin Jain
     * @since  0.1
     */
    protected $reqAutoIncrement = false;

    /**
     * Used to store additional database specific arguments.
     *
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    protected $addArgs = [];

    /**
     * Used to initiate query.
     *
     * @param \FluitoPHP\Database\DBConnectionHelper $connection Provide the connection object.
     * @author Vipin Jain
     * @since  0.1
     */
    public function __construct($connection) {

        $this->
                connection = $connection;
    }

    /**
     * Used to generate the query.
     *
     * @param bool $autoIncrement Provide if the query requires autoincrement query
     * @param string $column Provide the columns for which auto increment needs to be created.
     * @return string Returns the generated query. In case of error returns empty query.
     * @author Vipin Jain
     * @since  0.1
     */
    final public function Generate($autoIncrement = false, $column = '') {

        if ($autoIncrement && $this->
                reqAutoIncrement) {

            $this->
                    RetAutoIncrement($column);
        }

        if (!$this->
                query) {

            switch ($this->
            type) {

                case 'SELECT':

                    $this->
                            query = $this->
                            RetSelect();

                    break;

                case 'CHECKTABLE':

                    $this->
                            query = $this->
                            RetCheckTable();

                    break;

                case 'CREATETABLE':

                    $this->
                            query = $this->
                            RetCreateTable();

                    break;

                case 'ALTERTABLE':

                    $this->
                            query = $this->
                            RetAlterTable();

                    break;

                case 'TRUNCATETABLE':

                    $this->
                            query = $this->
                            RetTruncateTable();

                    break;

                case 'DROPTABLE':

                    $this->
                            query = $this->
                            RetDropTable();

                    break;

                case 'CREATEVIEW':

                    $this->
                            query = $this->
                            RetCreateView();

                    break;

                case 'DROPVIEW':

                    $this->
                            query = $this->
                            RetDropView();

                    break;

                case 'INSERT':

                    $this->
                            query = $this->
                            RetInsert();

                    break;

                case 'UPDATE':

                    $this->
                            query = $this->
                            RetUpdate();

                    break;

                case 'DELETE':

                    $this->
                            query = $this->
                            RetDelete();

                    break;

                default:

                    $this->
                            query = $this->
                            GenCustom($this->
                            customType);

                    break;
            }
        }

        $this->
                query = $this->
                ResolveMeta($this->
                query);

        return $this->
                query;
    }

    /**
     * Used to define select query. Use below structure to pass values.
     *
     * table id (array)
     * |
     * |--subquery (\FluitoPHP\Database\DBQueryHelper)/table (string)
     * |
     * |--alias (string)
     * |
     * |--jointype (string) (j, ij, cj, sj, lj, rj, nj, nlj, nrj, loj, roj, nloj, nroj)
     * |
     * |--joinconditions (array of array)
     * |  |
     * |  |--condition id (array)
     * |  |  |
     * |  |  |--startbrackets (string)
     * |  |  |
     * |  |  |--operatortype (string) (AND, OR)
     * |  |  |
     * |  |  |--notoperator (bool)
     * |  |  |
     * |  |  |--column or expression (string)/subquery (\FluitoPHP\Database\DBQueryHelper)/value (string)
     * |  |  |
     * |  |  |--operator (string)
     * |  |  |
     * |  |  |--rightcolumn or rightexpression (string)/rightsubquery (\FluitoPHP\Database\DBQueryHelper)/rightvalue (string)
     * |  |  |
     * |  |  |--endbrackets (string)
     *
     * column id (array)
     * |
     * |--column or expression (string)/subquery (\FluitoPHP\Database\DBQueryHelper)/value
     * |
     * |--label (string)
     *
     * where id (array)
     * |
     * |--startbrackets (string)
     * |
     * |--operatortype (string) (AND, OR)
     * |
     * |--notoperator (bool)
     * |
     * |--column or expression (string)/subquery (\FluitoPHP\Database\DBQueryHelper)/value (string)
     * |
     * |--rightcolumn or rightexpression (string)/rightsubquery (\FluitoPHP\Database\DBQueryHelper)/rightvalue (string)
     * |
     * |--operator (string)
     * |
     * |--endbrackets (string)
     *
     * group id (array)
     * |
     * |--column or expression (string)/subquery (\FluitoPHP\Database\DBQueryHelper)/value (string)
     *
     * having id (array)
     * |
     * |--startbrackets (string)
     * |
     * |--operatortype (string) (AND, OR)
     * |
     * |--notoperator (bool)
     * |
     * |--column or expression (string)/subquery (\FluitoPHP\Database\DBQueryHelper)/value (string)
     * |
     * |--rightcolumn or rightexpression (string)/rightsubquery (\FluitoPHP\Database\DBQueryHelper)/rightvalue (string)
     * |
     * |--operator (string)
     * |
     * |--endbrackets (string)
     *
     * order id (array)
     * |
     * |--column or expression (string)/subquery (\FluitoPHP\Database\DBQueryHelper)/value (string)
     * |
     * |--type (string) (a, d)
     *
     *
     * @param mixed $tables Provide the list of tables. Format string or array.
     * @param array $columns Provide the list of columns
     * @param array $where Provide the list of where clause.
     * @param array $group Provide the list of grouping columns.
     * @param array $having Provide the list of having clause.
     * @param array $order Provide the list of order by columns.
     * @param bool $distinct Provide the distinct clause.
     * @param int $perpage Provide the number of rows per page.
     * @param int $page Provide the page number.
     * @param array $addArgs Provide additional database specific arguments.
     * @return \FluitoPHP\Database\DBQueryHelper Self reference is returned for chained calls.
     * @author Vipin Jain
     * @since  0.1
     */
    final public function Select($tables, $columns = [], $where = [], $group = [], $having = [], $order = [], $distinct = false, $perpage = 0, $page = 1, $addArgs = []) {

        if (!$this->
                type &&
                $tables) {

            if (!is_array($tables)) {

                $tables = array($tables);
            }

            if (!$columns) {

                $columns = '*';
            }

            if (!is_array($columns)) {

                $columns = array($columns);
            }

            $this->
                    type = 'SELECT';

            $this->
                    tables = $tables;

            $this->
                    columns = $columns;

            $this->
                    where = $where;

            $this->
                    group = $group;

            $this->
                    having = $having;

            $this->
                    order = $order;

            $this->
                    distinct = $distinct;

            $this->
                    perpage = $perpage;

            $this->
                    page = $page;

            $this->
                    addArgs = $addArgs;
        }

        return $this;
    }

    /**
     * Used to generate the query to check if the table exists in the database.
     *
     * @param string $table Provide the table name needs to be checked.
     * @param array $addArgs Provide additional database specific arguments.
     * @return \FluitoPHP\Database\DBQueryHelper Self reference is returned for chained calls.
     * @author Vipin Jain
     * @since  0.1
     */
    final public function CheckTable($table, $addArgs = []) {

        if (!$this->
                type &&
                $table &&
                strpos($table, '%') === false) {

            $this->
                    type = 'CHECKTABLE';

            $this->
                    tables = array($table);

            $this->
                    addArgs = $addArgs;
        }

        return $this;
    }

    /**
     * Used to generate the query to create a table in the database. Use below structure to pass parameters.
     *
     * column id (array key is the column name)
     * |
     * |--type (string) (field type, use actual name as per database as most of the types are common in all databases)
     * |
     * |--length (int)
     * |
     * |--isnull (bool)
     * |
     * |--autoincrement (bool)
     * |
     * |--default (string/array)
     * |  |
     * |  |--function (string)
     * |
     * |--primary (bool)
     * |
     * |--unique (bool)
     * |
     * |--index (bool)
     * |
     * |--check (string)
     * |
     * |--referencetable  (string)
     * |
     * |--referencecolumn (string)
     *
     * index id (array key is the index name)
     * |
     * |--indextype (string) (K, I, U, UK, UI, FK, FT, FTK, FTI, S, SK, SI, CH)
     * |
     * |--columns (array of strings)
     * |
     * |--check (string)
     * |
     * |--referencetable (string)
     * |
     * |--referencecolumns (array of strings)
     * |  |
     * |  |--column (string)
     *
     * @param string $table Provide the table name needs to be created.
     * @param array $columns Provide the columns needs to be created.
     * @param array $indexes Provide indexes that needs to be created including foreign keys.
     * @param string $temporary Provide this variable as N for normal table, T for (local) temporary table and G for global temporary table.
     * @param array $addArgs Provide additional database specific arguments.
     * @return \FluitoPHP\Database\DBQueryHelper Self reference is returned for chained calls.
     * @author Vipin Jain
     * @since  0.1
     */
    final public function CreateTable($table, $columns, $indexes = [], $temporary = 'N', $addArgs = []) {

        if (!$this->
                type &&
                $table) {

            $temporary = strtoupper($temporary);

            if (!in_array($temporary, ['N', 'T', 'G'])) {

                $temporary = 'N';
            }

            $this->
                    type = 'CREATETABLE';

            $this->
                    tables = array($table);

            $this->
                    columns = $columns;

            $this->
                    indexes = $indexes;

            $this->
                    temporary = $temporary;

            $this->
                    addArgs = $addArgs;
        }

        return $this;
    }

    /**
     * Used to generate the query to alter a table in database.
     *
     * column id (array key is the column name)
     * |
     * |--request (string) (A, U, D)
     * |
     * |--place (string) (F, A)
     * |
     * |--after (string)
     * |
     * |--rename (string)
     * |
     * |--type (string) (field type, use actual name as per database as most of the types are common in all databases)
     * |
     * |--length (int)
     * |
     * |--isnull (bool)
     * |
     * |--autoincrement (bool)
     * |
     * |--default (string/array)
     * |  |
     * |  |--function (string)
     * |
     * |--dropdefault (bool)
     *
     * index id (array key is the index name)
     * |
     * |--request (string) (A, D)
     * |
     * |--rename (string)
     * |
     * |--indextype (string) (K, I, P, U, UK, UI, FK, FT, FTK, FTI, S, SK, SI, CH)
     * |
     * |--columns (array of strings)
     * |
     * |--check (string)
     * |
     * |--referencetable (string)
     * |
     * |--referencecolumns (array of strings)
     * |  |
     * |  |--column (string)
     *
     * $addArgs
     * |
     * |--rename (string)
     *
     * @param string $table Provide the table name needs to be altered.
     * @param array $columns Provide the columns needs to be created/altered/deleted.
     * @param array $indexes Provide indexes that needs to be created including foreign keys.
     * @param array $addArgs Provide additional database specific arguments.
     * @return \FluitoPHP\Database\DBQueryHelper Self reference is returned for chained calls.
     * @author Vipin Jain
     * @since  0.1
     */
    final public function AlterTable($table, $columns, $indexes = [], $addArgs = []) {

        if (!$this->
                type &&
                $table) {

            $this->
                    type = 'ALTERTABLE';

            $this->
                    tables = array($table);

            $this->
                    columns = $columns;

            $this->
                    indexes = $indexes;

            $this->
                    addArgs = $addArgs;
        }

        return $this;
    }

    /**
     * Used to generate the query to truncate a table in database.
     *
     * @param string $table Provide the table that needs to be truncated.
     * @param array $addArgs Provide additional database specific arguments.
     * @return \FluitoPHP\Database\DBQueryHelper Self reference is returned for chained calls.
     * @author Vipin Jain
     * @since  0.1
     */
    final public function TruncateTable($table, $addArgs = []) {

        if (!$this->
                type &&
                $table) {

            $this->
                    type = 'TRUNCATETABLE';

            $this->
                    tables = array($table);

            $this->
                    addArgs = $addArgs;
        }

        return $this;
    }

    /**
     * Used to generate the query to drop a table from database.
     *
     * @param string $table Provide the table that needs to be dropped.
     * @param array $addArgs Provide additional database specific arguments.
     * @return \FluitoPHP\Database\DBQueryHelper Self reference is returned for chained calls.
     * @author Vipin Jain
     * @since  0.1
     */
    final public function DropTable($table, $addArgs = []) {

        if (!$this->
                type &&
                $table) {

            $this->
                    type = 'DROPTABLE';

            $this->
                    tables = array($table);

            $this->
                    addArgs = $addArgs;
        }

        return $this;
    }

    /**
     * Used to generate the query to create a table in the database. Use below structure to pass parameters.
     *
     * @param string $table Provide the table name needs to be created.
     * @param array $columns Provide the column list if required.
     * @param string $viewSelect Provide the select query helper for this create view clause.
     * @param array $addArgs Provide additional database specific arguments.
     * @return \FluitoPHP\Database\DBQueryHelper Self reference is returned for chained calls.
     * @author Vipin Jain
     * @since  0.1
     */
    final public function CreateView($table, $columns = [], $viewSelect = null, $addArgs = []) {

        if (!$this->
                type &&
                $table) {

            $this->
                    type = 'CREATEVIEW';

            $this->
                    tables = array($table);

            $this->
                    columns = $columns;

            $this->
                    viewSelect = $viewSelect;

            $this->
                    addArgs = $addArgs;
        }

        return $this;
    }

    /**
     * Used to generate the query to drop a view from database.
     *
     * @param string $table Provide the view that needs to be dropped.
     * @param array $addArgs Provide additional database specific arguments.
     * @return \FluitoPHP\Database\DBQueryHelper Self reference is returned for chained calls.
     * @author Vipin Jain
     * @since  0.1
     */
    final public function DropView($table, $addArgs = []) {

        if (!$this->
                type &&
                $table) {

            $this->
                    type = 'DROPVIEW';

            $this->
                    tables = array($table);

            $this->
                    addArgs = $addArgs;
        }

        return $this;
    }

    /**
     * Used to generate the query to insert data in table of database.
     *
     * column id (array key is the column name) => value (string)
     * |
     * |--function (string)
     *
     * @param string $table Provide the table name in which data needs to be inserted.
     * @param mixed $values Provide the values in an associative array. Format array or \FluitoPHP\Database\DBQueryHelper.
     * @param array $addArgs Provide additional database specific arguments.
     * @return \FluitoPHP\Database\DBQueryHelper Self reference is returned for chained calls.
     * @author Vipin Jain
     * @since  0.1
     */
    final public function Insert($table, $values, $addArgs = []) {

        if (!$this->
                type &&
                $table) {

            $this->
                    type = 'INSERT';

            $this->
                    tables = array($table);

            $this->
                    values = $values;

            $this->
                    addArgs = $addArgs;
        }

        return $this;
    }

    /**
     * Used to generate the query to update data in table of database.
     *
     * @param string $table Provide the table name in which data needs to be inserted.
     * @param array $values Provide the values in an associative array.
     * @param array $where Provide the where clause.
     * @param array $addArgs Provide additional database specific arguments.
     * @return \FluitoPHP\Database\DBQueryHelper Self reference is returned for chained calls.
     * @author Vipin Jain
     * @since  0.1
     */
    final public function Update($table, $values, $where = [], $addArgs = []) {

        if (!$this->
                type &&
                $table) {

            $this->
                    type = 'UPDATE';

            $this->
                    tables = array($table);

            $this->
                    values = $values;

            $this->
                    where = $where;

            $this->
                    addArgs = $addArgs;
        }

        return $this;
    }

    /**
     * Used to generate the query to delete data from table of database.
     *
     * @param string $table Provide the table name from which data needs to be deleted.
     * @param array $where Provide the where clause.
     * @param array $addArgs Provide additional database specific arguments.
     * @return \FluitoPHP\Database\DBQueryHelper Self reference is returned for chained calls.
     * @author Vipin Jain
     * @since  0.1
     */
    final public function Delete($table, $where = [], $addArgs = []) {

        if (!$this->
                type &&
                $table) {

            $this->
                    type = 'DELETE';

            $this->
                    tables = array($table);

            $this->
                    where = $where;

            $this->
                    addArgs = $addArgs;
        }

        return $this;
    }

    /**
     * Used to generate custom queries.
     *
     * @param string $type Provide the type of custom query to be generated.
     * @return \FluitoPHP\Database\DBQueryHelper Self reference is returned for chained calls.
     * @author Vipin Jain
     * @since  0.1
     */
    final public function Custom($type) {

        if (!$this->
                type &&
                $table) {

            $this->
                    type = 'CUSTOM';

            $this->
                    customType = $type;

            $this->
                    addArgs = func_get_args();
        }

        return $this;
    }

    /**
     * Used to query on database.
     *
     * @return bool Returns true or insert id if the query runs successfully.
     * @author Vipin Jain
     * @since  0.1
     */
    final public function Query() {

        $result = $this->
                connection->
                Query($this->
                Generate());

        if ($this->
                        connection->
                        GetConn()->
                insert_id) {

            $result = $this->
                            connection->
                            GetConn()->
                    insert_id;
        }

        return $result;
    }

    /**
     * Used to fetch the results in an array which is retrieved by the provided query from this connection.
     *
     * @param bool $objectType Provide true if you require row in object format, false will return result rows in associative array.
     * @return array Returns resultant rows in array of associative array or object, depending on the 2nd parameter.
     * @author Vipin Jain
     * @since  0.1
     */
    final public function GetResults($objectType = false) {

        return $this->
                        connection->
                        GetResults($this->
                                Generate(), $objectType);
    }

    /**
     * Used to fetch the row in an array/object which is retrieved by the provided query from this connection.
     *
     * @param bool $objectType Provide true if you require row in object format, false will return result rows in associative array.
     * @return mixed Returns resultant row in associative array or object, depending on the 2nd parameter.
     * @author Vipin Jain
     * @since  0.1
     */
    final public function GetRow($objectType = false) {

        return $this->
                        connection->
                        GetRow($this->
                                Generate(), $objectType);
    }

    /**
     * Used to fetch the column in an array which is retrieved by the provided query from this connection.
     *
     * @param bool $objectType Provide true if you require row in object format, false will return result rows in associative array.
     * @return array Returns resultant column in array.
     * @author Vipin Jain
     * @since  0.1
     */
    final public function GetColumn($objectType = false) {

        return $this->
                        connection->
                        GetColumn($this->
                                Generate(), $objectType);
    }

    /**
     * Used to fetch the variable which is retrieved by the provided query from this connection.
     *
     * @return string The first variable of first row will be fetched and returned in string format.
     * @author Vipin Jain
     * @since  0.1
     */
    final public function GetVar() {

        return $this->
                        connection->
                        GetVar($this->
                                Generate());
    }

    /**
     * Used to fetch the columns info from last ran query from this connection.
     *
     * @return array Returns the columns info.
     * @author Vipin Jain
     * @since  0.1
     */
    public function GetColInfo() {

        return $this->
                        connection->
                        GetColInfo();
    }

    /**
     * Used to get if the helper contains select query.
     *
     * @return bool
     * @author Vipin Jain
     * @since  0.1
     */
    final public function IsSelect() {

        return $this->
                type === 'SELECT';
    }

    /**
     * Used to resolve meta SQL provided.
     *
     * @param string $metaFunction Provide meta function to resolve.
     * @param array $args Provide array of arguments for function.
     * @return string Returns the resolved meta SQL to use in database.
     * @author Vipin Jain
     * @since  0.1
     */
    abstract public function Meta($metaFunction, $args);

    /**
     * Used to generate custom queries for a specific type of helper by implementing custom methods.
     *
     * @param string $type Provide the type of query to be implemented.
     * @return string Returns the generated custom query.
     * @throws \Exception Exception is thrown if the structure of arguments is not correct.
     * @author Vipin Jain
     * @since  0.1
     */
    abstract protected function GenCustom($type);

    /**
     * Used to generate the select query.
     *
     * @return string Returns the generated select query.
     * @throws \Exception Exception is thrown if the structure of arguments is not correct.
     * @author Vipin Jain
     * @since  0.1
     */
    abstract protected function RetSelect();

    /**
     * Used to generate the check table query.
     *
     * @return string Returns the generated query to check if the table is present in the database.
     * @throws \Exception Exception is thrown if the structure of arguments is not correct.
     * @author Vipin Jain
     * @since  0.1
     */
    abstract protected function RetCheckTable();

    /**
     * Used to generate the create table query.
     *
     * @return string Returns the generated query to create the table.
     * @throws \Exception Exception is thrown if the structure of arguments is not correct.
     * @author Vipin Jain
     * @since  0.1
     */
    abstract protected function RetCreateTable();

    /**
     * Used to generate the autoincrement query for the corresponding create table.
     *
     * @param string $column Provide the column name for which the autoincrement query needs to be generated.
     * @return string Returns the generated query to create the table.
     * @throws \Exception Exception is thrown if the structure of arguments is not correct.
     * @author Vipin Jain
     * @since  0.1
     */
    protected function RetAutoIncrement($column) {

        if ($this->
                reqAutoIncrement) {

            return "";
        }

        return "";
    }

    /**
     * Used to generate the alter table query.
     *
     * @return string Returns the generated query to alter the table.
     * @throws \Exception Exception is thrown if the structure of arguments is not correct.
     * @author Vipin Jain
     * @since  0.1
     */
    abstract protected function RetAlterTable();

    /**
     * Used to generate the truncate table query.
     *
     * @return string Returns the generated query to truncate table.
     * @throws \Exception Exception is thrown if the structure of arguments is not correct.
     * @author Vipin Jain
     * @since  0.1
     */
    abstract protected function RetTruncateTable();

    /**
     * Used to generate the drop table query.
     *
     * @return string Returns the generated query to drop a table.
     * @throws \Exception Exception is thrown if the structure of arguments is not correct.
     * @author Vipin Jain
     * @since  0.1
     */
    abstract protected function RetDropTable();

    /**
     * Used to generate the create/replace view query.
     *
     * @return string Returns the generated query to create/replace view.
     * @throws \Exception Exception is thrown if the structure of arguments is not correct.
     * @author Vipin Jain
     * @since  0.1
     */
    abstract protected function RetCreateView();

    /**
     * Used to generate the drop view query.
     *
     * @return string Returns the generated query to drop a view.
     * @throws \Exception Exception is thrown if the structure of arguments is not correct.
     * @author Vipin Jain
     * @since  0.1
     */
    abstract protected function RetDropView();

    /**
     * Used to generate the insert query.
     *
     * @return string Returns the generated query to insert rows in a table.
     * @throws \Exception Exception is thrown if the structure of arguments is not correct.
     * @author Vipin Jain
     * @since  0.1
     */
    abstract protected function RetInsert();

    /**
     * Used to generate the update query.
     *
     * @return string Returns the generated query to update rows in a table.
     * @throws \Exception Exception is thrown if the structure of arguments is not correct.
     * @author Vipin Jain
     * @since  0.1
     */
    abstract protected function RetUpdate();

    /**
     * Used to generate the delete query.
     *
     * @return string Returns the generated query to delete rows from a table.
     * @throws \Exception Exception is thrown if the structure of arguments is not correct.
     * @author Vipin Jain
     * @since  0.1
     */
    abstract protected function RetDelete();

    /**
     * Used to resolve meta SQL in the query.
     *
     * @param string $query Provide SQL query with meta SQL.
     * @return string Returns the resolved meat SQL to use in database.
     * @author Vipin Jain
     * @since  0.1
     */
    public function ResolveMeta($query) {

        if (!is_string($query)) {

            return $query;
        }

        $position = -1;
        $offset = 0;

        while (($position = strpos($query, '&', $offset)) !== false) {

            $offset = strpos($query, '&&', $offset);

            if ($offset === $position) {

                while (substr($query, $offset, 1) === '&') {

                    $offset++;
                }
                continue;
            }

            preg_match('/^&[a-z0-9_]*/i', substr($query, $position), $matches);

            $metaFunction = $matches[0];

            $lastpos = $position + strlen($metaFunction) - 1;

            if (substr($query, $lastpos + 1, 1) == '(') {

                $opCount = 0;
                $clCount = 0;

                while (strlen($query) > $lastpos) {

                    if (substr($query, $lastpos + 1, 1) == '(') {

                        $opCount++;
                    } else if (substr($query, $lastpos + 1, 1) == ')') {

                        $clCount++;
                    }

                    $lastpos++;

                    if ($opCount == $clCount) {

                        break;
                    }
                }
            }

            $prior = substr($query, 0, $position);

            $last = substr($query, $lastpos + 1);

            $meta = substr($query, $position + strlen($metaFunction), $lastpos - ($position + strlen($metaFunction)) + 1);

            $retain = $metaFunction . $meta;

            $meta = substr($meta, 1, strlen($meta) - 2);

            $args = [];

            if ($meta) {

                $mLastpos = 0;
                $mCounter = 0;

                while (strlen($meta) > $mLastpos) {

                    if (substr($meta, $mLastpos, 1) == '(') {

                        $mCounter++;
                    } else if (substr($meta, $mLastpos, 1) == ')') {

                        $mCounter--;
                    } else if (substr($meta, $mLastpos, 1) == ',' &&
                            $mCounter == 0) {

                        $args[] = substr($meta, 0, $mLastpos);

                        $meta = trim(substr($meta, $mLastpos + 1));

                        $mLastpos = -1;
                    }

                    $mLastpos++;
                }

                $args[] = $meta;
            }

            $resolvedMeta = $this->
                    Meta($metaFunction, $args);

            if (!$resolvedMeta) {

                $resolvedMeta = $retain;
            }

            $query = $prior . $resolvedMeta . $last;

            $offset = $position + 1;

            if ($offset >= strlen($query)) {

                $offset = strlen($query);
            }
        }

        $query = str_replace('&&', '&', $query);

        return $query;
    }

}
