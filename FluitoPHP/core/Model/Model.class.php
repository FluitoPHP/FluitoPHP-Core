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

namespace FluitoPHP\Model;

/**
 * Model Class.
 *
 * This class defines the model. It can be extended for creating different functionalities.
 *
 * Variables:
 *      1. $model
 *      2. $connectionid
 *
 * Functions:
 *      1. __construct
 *      2. GetName
 *      3. GetConnectionID
 *      4. GetConnection
 *      5. AffectedRows
 *      6. SelectAll
 *      7. Select
 *      8. Insert
 *      9. Update
 *      10. Delete
 *
 * @author Neha Jain
 * @since  0.1
 */
class Model extends \FluitoPHP\Base\Base {

    /**
     * Used to store the model/table name.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    protected $model = "";

    /**
     * Used to store the connection identifier of the database.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    protected $connectionid = null;

    /**
     * Constructor to initialize this class.
     *
     * @param string $model Provide the model name.
     * @param string $connectionid Provide the connection id to use.
     * @author Neha Jain
     * @since  0.1
     */
    function __construct($model, $connectionid = null) {

        $this->
                model = $model;
        $this->
                connectionid = $connectionid;
    }

    /**
     * Used to get the name of the model.
     *
     * @return string Returns the name.
     * @author Neha Jain
     * @since  0.1
     */
    final public function GetName() {

        return $this->
                model;
    }

    /**
     * Used to get the connection id of the model.
     *
     * @return string Returns the connection id.
     * @author Neha Jain
     * @since  0.1
     */
    final public function GetConnectionID() {

        return $this->
                connectionid;
    }

    /**
     * Used to get the database connection of the model.
     *
     * @return \FluitoPHP\Database\DBConnectionHelper The helper class is returned having this \FluitoPHP\Database\DBConnectionHelper interface implemented.
     * @author Neha Jain
     * @since  0.1
     */
    final public function GetConnection() {

        return $this->
                        DB()->
                        Conn($this->
                                connectionid);
    }

    /**
     * Used to get the number of affected rows by the last ran query.
     *
     * @return int Returns the number of affected rows by last ran query.
     * @author Neha Jain
     * @since  0.1
     */
    final public function AffectedRows() {

        return $this->
                        DB()->
                        Conn($this->
                                connectionid)->
                        AffectedRows();
    }

    /**
     * Used to get all data from the table.
     *
     * @return array Returns the data from the table.
     * @author Neha Jain
     * @since  0.1
     */
    public function SelectAll() {

        return $this->
                        DB()->
                        Conn($this->
                                connectionid)->
                        Helper()->
                        Select($this->
                                model)->
                        GetResults();
    }

    /**
     * Used to get selected data from the table.
     *
     * @return array Returns the selected data from the table.
     * @author Neha Jain
     * @since  0.1
     */
    public function Select($columns = [], $where = [], $group = [], $having = [], $order = [], $distinct = false, $perpage = 0, $page = 1, $addArgs = []) {

        return $this->
                        DB()->
                        Conn($this->
                                connectionid)->
                        Helper()->
                        Select($this->
                                model, $columns, $where, $group, $having, $order, $distinct, $perpage, $page, $addArgs)->
                        GetResults();
    }

    /**
     * Used to insert the values in the table.
     *
     * @param mixed $values Provide the values in an associative array. Format array or \FluitoPHP\Database\DBQueryHelper.
     * @param array $addArgs Provide additional database specific arguments.
     * @return mixed Returns the insert id of auto increment column else true and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function Insert($values, $addArgs = []) {

        return $this->
                        DB()->
                        Conn($this->
                                connectionid)->
                        Helper()->
                        Insert($this->
                                model, $values, $addArgs)->
                        Query();
    }

    /**
     * Used to update values in table.
     *
     * @param array $values Provide the values in an associative array.
     * @param array $where Provide the where clause.
     * @param array $addArgs Provide additional database specific arguments.
     * @return mixed Returns the insert id of auto increment column else true and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function Update($values, $where = [], $addArgs = []) {

        return $this->
                        DB()->
                        Conn($this->
                                connectionid)->
                        Helper()->
                        Update($this->
                                model, $values, $where, $addArgs)->
                        Query();
    }

    /**
     * Used to delete rows from the table.
     *
     * @param array $where Provide the where clause.
     * @param array $addArgs Provide additional database specific arguments.
     * @return bool Returns true on success and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function Delete($where = [], $addArgs = []) {

        return $this->
                        DB()->
                        Conn($this->
                                connectionid)->
                        Helper()->
                        Delete($this->
                                model, $where, $addArgs)->
                        Query();
    }

}
