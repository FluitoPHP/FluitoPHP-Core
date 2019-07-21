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

namespace FluitoPHP\Database\Mysql;

/**
 * MysqlQueryHelper class.
 *
 * This class is used to generate queries for a mysql database instance.
 *
 * Variables:
 *
 * Functions:
 *      1. __construct
 *      2. WhereClause
 *      3. GenCustom
 *      4. RetSelect
 *      5. RetCheckTable
 *      6. RetCreateTable
 *      7. RetAlterTable
 *      8. RetTruncateTable
 *      9. RetDropTable
 *      10. RetCreateView
 *      11. RetDropView
 *      12. RetInsert
 *      13. RetUpdate
 *      14. RetDelete
 *      15. Meta
 *
 * @author Vipin Jain
 * @since  0.1
 */
class MysqlQueryHelper extends \FluitoPHP\Database\DBQueryHelper {

    /**
     * Used to initiate query.
     *
     * @param \FluitoPHP\Database\Mysql\MysqlConnectionHelper $connection Provide the connection object.
     * @author Vipin Jain
     * @since  0.1
     */
    public function __construct($connection) {

        parent::__construct($connection);
    }

    /**
     * Used to generate where/on/having clause.
     *
     * @param array $conditions Provide the conditions to be implemented.
     * @param int $type Provide the type of condition to be implemented. 0 = WHERE, 1 = ON and 2 = HAVING
     * @return string Returns the generated where/on/having clause.
     * @author Vipin Jain
     * @since  0.1
     */
    protected function WhereClause($conditions = array(), $type = 0) {

        $conditionsStr = "";

        if (count($conditions)) {

            $counter = 0;

            foreach ($conditions as $condkey => $condvalue) {

                $notType = false;

                $condvalue['operatortype'] = isset($condvalue['operatortype']) ? $condvalue['operatortype'] : 'AND';

                $condvalue['operatortype'] = strtoupper($condvalue['operatortype']);

                if (!in_array($condvalue['operatortype'], array('AND', 'OR'))) {

                    $condvalue['operatortype'] = "AND";
                }

                if (isset($condvalue['notoperator']) &&
                        $condvalue['notoperator']) {

                    $notType = true;
                }

                if ($counter == 0) {

                    switch ($type) {
                        case 1: {
                                $condvalue['operatortype'] = "ON";
                                break;
                            }
                        case 2: {
                                $condvalue['operatortype'] = "HAVING";
                                break;
                            }
                        default: {
                                $condvalue['operatortype'] = "WHERE";
                            }
                    }
                }

                $conditionsStr .= " " . $condvalue['operatortype'];

                if ($notType) {

                    $conditionsStr .= " NOT";
                }

                if (isset($condvalue['startbrackets'])) {

                    $condvalue['startbrackets'] = preg_replace("/[^(\(|\))]/", "", $condvalue['startbrackets']);

                    $conditionsStr .= " " . $condvalue['startbrackets'];
                }

                $noCondition = true;

                if (!(isset($condvalue['column']) &&
                        $condvalue['column']) &&
                        isset($condvalue['subquery']) &&
                        $condvalue['subquery'] instanceof \FluitoPHP\Database\DBQueryHelper &&
                                $condvalue['subquery']->
                                IsSelect()) {

                    $condvalue['column'] = '(' . rtrim($condvalue['subquery']->
                                            Generate(), ';') . ')';

                    $noCondition = false;
                } else if (isset($condvalue['column'])) {

                    $condvalue['column'] = "{$condvalue['column']}";

                    $noCondition = false;
                }

                if (!(isset($condvalue['column']) &&
                        $condvalue['column']) &&
                        isset($condvalue['value']) &&
                        $condvalue['value']) {

                    $condvalue['value'] = $this->
                            connection->
                            GetConn()->
                            real_escape_string($condvalue['value']);
                    $condvalue['column'] = "'{$condvalue['value']}'";

                    $noCondition = false;
                }

                if (!(isset($condvalue['rightcolumn']) &&
                        $condvalue['rightcolumn']) &&
                        isset($condvalue['rightsubquery']) &&
                        $condvalue['rightsubquery'] instanceof \FluitoPHP\Database\DBQueryHelper &&
                                $condvalue['rightsubquery']->
                                IsSelect()) {

                    $condvalue['rightcolumn'] = '(' . rtrim($condvalue['rightsubquery']->
                                            Generate(), ';') . ')';

                    $noCondition = false;
                } else if (isset($condvalue['rightcolumn'])) {

                    $condvalue['rightcolumn'] = "{$condvalue['rightcolumn']}";

                    $noCondition = false;
                }

                if (!(isset($condvalue['rightcolumn']) &&
                        $condvalue['rightcolumn']) &&
                        isset($condvalue['rightvalue']) &&
                        $condvalue['rightvalue']) {

                    $condvalue['rightvalue'] = $this->
                            connection->
                            GetConn()->
                            real_escape_string($condvalue['rightvalue']);
                    $condvalue['rightcolumn'] = "'{$condvalue['rightvalue']}'";

                    $noCondition = false;
                }

                if (!$noCondition) {

                    if (!isset($condvalue['operator']) ||
                            !$condvalue['operator']) {

                        $condvalue['operator'] = "=";
                    }

                    $conditionsStr .= " {$condvalue['column']} {$condvalue['operator']} {$condvalue['rightcolumn']}";
                }

                if (isset($condvalue['endbrackets'])) {

                    $condvalue['endbrackets'] = preg_replace("/[^(\(|\))]/", "", $condvalue['endbrackets']);

                    $conditionsStr .= " " . $condvalue['endbrackets'];
                }

                $counter++;
            }
        }

        return $conditionsStr;
    }

    /**
     * Used to generate custom queries for a specific type of helper by implementing custom methods.
     *
     * @param string $type Provide the type of query to be implemented.
     * @return string Returns the generated custom query.
     * @throws \Exception Exception is thrown if the structure of arguments is not correct.
     * @author Vipin Jain
     * @since  0.1
     */
    protected function GenCustom($type) {

        return "";
    }

    /**
     * Used to generate the select query.
     *
     * @return string Returns the generated select query.
     * @throws \Exception Exception is thrown if the structure of arguments is not correct.
     * @author Vipin Jain
     * @since  0.1
     */
    protected function RetSelect() {

        $return = "SELECT";

        if ($this->
                distinct) {

            $return .= " DISTINCT";
        }

        $columnsArr = [];

        foreach ($this->
        columns as $key => $value) {

            if (is_string($value)) {

                $columnsArr[] = $value;
            } else if (isset($value['subquery']) &&
                    $value['subquery'] instanceof \FluitoPHP\Database\DBQueryHelper &&
                            $value['subquery']->
                            IsSelect()) {

                $columnsArr[] = "(" . $value['subquery']->
                                Generate() . ")" . (isset($value['label']) &&
                        is_string($value['label']) ? " AS \"{$value['label']}\"" : "");
            } else if (isset($value['value'])) {

                $columnsArr[] = "'{$value['value']}'" . (isset($value['label']) &&
                        is_string($value['label']) ? " AS \"{$value['label']}\"" : "");
            } else {

                $columnsArr[] = "{$value['column']}" . (isset($value['label']) &&
                        is_string($value['label']) ? " AS \"{$value['label']}\"" : "");
            }
        }

        if (count($columnsArr)) {

            $return .= " " . implode($columnsArr, ", ");
        } else {

            $return .= " *";
        }


        $tablesStr = "";

        foreach ($this->
        tables as $key => $value) {

            if (is_string($value)) {

                $tablesStr .= ", $value";
            } else {

                if (!(isset($value['table']) &&
                        $value['table'] != "") &&
                        isset($value['subquery']) &&
                        $value['subquery'] instanceof \FluitoPHP\Database\DBQueryHelper &&
                                $value['subquery']->
                                IsSelect()) {

                    $value['table'] = "(" . rtrim($value['subquery']->
                                            Generate(), ';') . ")";

                    if (!isset($value['alias'])) {

                        throw new \Exception("Error: Alias required in case of subquery to be used in from clause.");
                    }
                } else {

                    $value['table'] = "{$value['table']}";
                }

                if (isset($value['alias'])) {

                    $value['alias'] = "`{$value['alias']}`";
                }

                if (!isset($value['table']) ||
                        $value['table'] == "") {

                    throw new \Exception("Error: Tables not properly provided in QueryHelper.");
                }

                if (!isset($value['jointype'])) {

                    $tablesStr .= ", " . $value['table'];

                    if (isset($value['alias'])) {

                        $tablesStr .= " AS " . $value['alias'];
                    }
                } else {

                    switch ($value['jointype']) {
                        case 'j':

                            $tablesStr .= " JOIN " . $value['table'];
                            break;

                        case 'ij':

                            $tablesStr .= " INNER JOIN " . $value['table'];
                            break;

                        case 'cj':

                            $tablesStr .= " CROSS JOIN " . $value['table'];
                            break;

                        case 'sj':

                            $tablesStr .= " STRAIGHT_JOIN " . $value['table'];
                            break;

                        case 'lj':

                            $tablesStr .= " LEFT JOIN " . $value['table'];
                            break;

                        case 'rj':

                            $tablesStr .= " RIGHT JOIN " . $value['table'];
                            break;

                        case 'nj':

                            $tablesStr .= " NATURAL JOIN " . $value['table'];
                            break;

                        case 'nlj':

                            $tablesStr .= " NATURAL LEFT JOIN " . $value['table'];
                            break;

                        case 'nrj':

                            $tablesStr .= " NATURAL RIGHT JOIN " . $value['table'];
                            break;

                        case 'loj':

                            $tablesStr .= " LEFT OUTER JOIN " . $value['table'];
                            break;

                        case 'roj':

                            $tablesStr .= " RIGHT OUTER JOIN " . $value['table'];
                            break;

                        case 'nloj':

                            $tablesStr .= " NATURAL LEFT OUTER JOIN " . $value['table'];
                            break;

                        case 'nroj':

                            $tablesStr .= " NATURAL RIGHT OUTER JOIN " . $value['table'];
                            break;

                        default:
                            break;
                    }

                    if (isset($value['alias'])) {

                        $tablesStr .= " AS " . $value['alias'];
                    }

                    if (isset($value['joinconditions']) &&
                            count($value['joinconditions'])) {

                        $tablesStr .= $this->
                                WhereClause($value['joinconditions'], 1);
                    }
                }
            }
        }

        $return .= " FROM " . ltrim($tablesStr, ", ");


        if (count($this->
                        where)) {

            $return .= $this->
                    WhereClause($this->
                    where);
        }


        $groupsArr = [];

        foreach ($this->
        group as $key => $value) {

            if (is_string($value)) {

                $groupsArr[] = "{$value}";
            } else if (isset($value['subquery']) &&
                    $value['subquery'] instanceof \FluitoPHP\Database\DBQueryHelper &&
                            $value['subquery']->
                            IsSelect()) {

                $groupsArr[] = "(" . rtrim($value['subquery']->
                                        Generate(), ';') . ")";
            } else if (isset($value['value'])) {

                $value['value'] = $this->
                        connection->
                        GetConn()->
                        real_escape_string($value['value']);
                $groupsArr[] = "'{$value['value']}'";
            } else {

                $groupsArr[] = "{$value['column']}";
            }
        }

        if (count($groupsArr)) {

            $return .= " GROUP BY " . implode($groupsArr, ", ");
        }


        if (count($this->
                        having)) {

            $return .= $this->
                    WhereClause($this->
                    having, 2);
        }


        $ordersArr = [];

        foreach ($this->
        order as $key => $value) {

            if (is_string($value)) {

                $ordersArr[] = $value;
            } else if (isset($value['subquery']) &&
                    $value['subquery'] instanceof \FluitoPHP\Database\DBQueryHelper &&
                            $value['subquery']->
                            IsSelect()) {

                $ordersArr[] = "(" . rtrim($value['subquery']->
                                        Generate(), ';') . ")" . (isset($value['type']) &&
                        $value['type'] == 'd' ? " DESC" : "");
            } else if (isset($value['value'])) {

                $ordersArr[] = "'{$value['value']}'" . (isset($value['type']) &&
                        $value['type'] == 'd' ? " DESC" : "");
            } else {

                $ordersArr[] = "{$value['column']}" . (isset($value['type']) &&
                        $value['type'] == 'd' ? " DESC" : "");
            }
        }

        if (count($ordersArr)) {

            $return .= " ORDER BY " . implode($ordersArr, ", ");
        }

        if ($this->
                perpage > 0) {

            if ($this->
                    page < 1) {

                $this->
                        page = 1;
            }

            $startcount = $this->
                    perpage * ($this->
                    page - 1);

            $return .= " LIMIT {$startcount}, {$this->
                    perpage}";
        }

        return $return . ";";
    }

    /**
     * Used to generate the check table query.
     *
     * @return string Returns the generated query to check if the table is present in the database.
     * @throws \Exception Exception is thrown if the structure of arguments is not correct.
     * @author Vipin Jain
     * @since  0.1
     */
    protected function RetCheckTable() {

        if (!isset($this->
                        tables[0])) {

            throw new \Exception("Error: Table not properly provided in QueryHelper.");
        }

        return "SHOW TABLES LIKE '{$this->
                tables[0]}';";
    }

    /**
     * Used to generate the create table query.
     *
     * @return string Returns the generated query to create the table.
     * @throws \Exception Exception is thrown if the structure of arguments is not correct.
     * @author Vipin Jain
     * @since  0.1
     */
    protected function RetCreateTable() {

        if (!isset($this->
                        tables[0])) {

            throw new \Exception("Error: Table not properly provided in QueryHelper.");
        }

        $return = "CREATE";

        if ($this->
                temporary == 'T' ||
                $this->
                temporary == 'G') {

            $return .= " TEMPORARY";
        }

        $return .= " TABLE";

        if (isset($this->
                        addArgs['notexists']) &&
                $this->
                addArgs['notexists'] === true) {

            $return .= " IF NOT EXISTS";
        }

        $columnsDefn = [];
        $primaryKeys = [];
        $indexes = [];
        $primaryKeysDefn = "";
        $indexesDefn = "";

        $indexesArr = $this->
                indexes;

        foreach ($this->
        columns as $column => $parms) {

            $parms['type'] = strtoupper($parms['type']);

            if (!isset($parms['type']) ||
                    !is_string($parms['type']) ||
                    !$parms['type']) {

                $parms['type'] = 'VARCHAR';

                $parms['length'] = !isset($parms['length']) ||
                        !is_int($parms['length']) ||
                        $parms['length'] < 1 ? 1 : $parms['length'];
            }

            $type = $parms['type'];

            if (isset($parms['length']) &&
                    is_int($parms['length']) &&
                    $parms['length'] > 0) {

                $type .= "({$parms['length']})";
            }

            $columnsDefn[$column] = "`{$column}` {$type}";

            if (isset($parms['isnull']) &&
                    $parms['isnull'] === false) {

                $columnsDefn[$column] .= " NOT null";
            }

            if (isset($parms['autoincrement']) &&
                    $parms['autoincrement'] === true) {

                $columnsDefn[$column] .= " AUTO_INCREMENT";
            }

            if (isset($parms['default']) &&
                    $parms['default']) {

                if (isset($parms['default']['function']) &&
                        $parms['default']['function']) {

                    $columnsDefn[$column] .= " DEFAULT {$parms['default']['function']}";
                } else if (is_string($parms['default'])) {

                    $columnsDefn[$column] .= " DEFAULT '{$parms['default']}'";
                }
            }

            if (isset($parms['primary']) &&
                    $parms['primary'] === true) {

                $primaryKeys[] = $column;
            }

            if (isset($parms['unique']) &&
                    $parms['unique'] === true) {

                $columnsDefn[$column] .= " UNIQUE";
            }

            if (isset($parms['index']) &&
                    $parms['index'] === true) {

                $columnsDefn[$column] .= " INDEX";
            }

            if (isset($parms['referencetable']) &&
                    isset($parms['referencecolumn']) &&
                    is_string($parms['referencetable']) &&
                    is_string($parms['referencecolumn'])) {

                $columnsDefn[$column] .= " REFERENCES `{$parms['referencetable']}` (`{$parms['referencecolumn']}`)";
            }

            if (isset($parms['check']) &&
                    is_string($parms['check'])) {

                $indexesArr[] = array(
                    'indextype' => 'CH',
                    'check' => $parms['check']
                );
            }
        }

        if (count($primaryKeys) == 1) {

            $columnsDefn[$primaryKeys[0]] .= " PRIMARY KEY";
        } else if (count($primaryKeys) > 1) {

            $primaryKeysDefn = ", PRIMARY KEY (`" . implode($primaryKeys, "`, `") . "`)";
        }

        foreach ($indexesArr as $index => $parms) {

            $indexDefn = "";
            $indexName = "";

            if (is_string($index)) {

                $indexName = " `{$index}`";
            }

            if (!isset($parms['indextype'])) {

                $parms['indextype'] = 'I';
            }

            $parms['indextype'] = strtoupper($parms['indextype']);

            switch ($parms['indextype']) {
                default:
                case 'I':
                    $indexDefn .= "INDEX{$indexName} (`" . implode($parms['columns'], "`, `") . "`)";
                    break;
                case 'K':
                    $indexDefn .= "KEY{$indexName} (`" . implode($parms['columns'], "`, `") . "`)";
                    break;
                case 'U':
                    $indexDefn .= "UNIQUE{$indexName} (`" . implode($parms['columns'], "`, `") . "`)";
                    break;
                case 'UK':
                    $indexDefn .= "UNIQUE KEY{$indexName} (`" . implode($parms['columns'], "`, `") . "`)";
                    break;
                case 'UI':
                    $indexDefn .= "UNIQUE INDEX{$indexName} (`" . implode($parms['columns'], "`, `") . "`)";
                    break;
                case 'FK':
                    if (is_string($index)) {

                        $indexDefn .= "CONSTRAINT{$indexName} FOREIGN KEY (`" . implode($parms['columns'], "`, `") . "`) REFERENCES `{$parms['referencetable']}` (`" . implode($parms['referencecolumns'], "`, `") . "`)";
                    } else {

                        $indexDefn .= "FOREIGN KEY (`" . implode($parms['columns'], "`, `") . "`) REFERENCES `{$parms['referencetable']}` (`" . implode($parms['referencecolumns'], "`, `") . "`)";
                    }
                    break;
                case 'FT':
                    $indexDefn .= "FULLTEXT{$indexName} (`" . implode($parms['columns'], "`, `") . "`)";
                    break;
                case 'FTK':
                    $indexDefn .= "FULLTEXT KEY{$indexName} (`" . implode($parms['columns'], "`, `") . "`)";
                    break;
                case 'FTI':
                    $indexDefn .= "FULLTEXT INDEX{$indexName} (`" . implode($parms['columns'], "`, `") . "`)";
                    break;
                case 'S':
                    $indexDefn .= "SPATIAL{$indexName} (`" . implode($parms['columns'], "`, `") . "`)";
                    break;
                case 'SK':
                    $indexDefn .= "SPATIAL KEY{$indexName} (`" . implode($parms['columns'], "`, `") . "`)";
                    break;
                case 'SI':
                    $indexDefn .= "SPATIAL INDEX{$indexName} (`" . implode($parms['columns'], "`, `") . "`)";
                    break;
                case 'CH':
                    $indexDefn .= "CHECK{$indexName} {$parms['check']}";
                    break;
            }

            $indexes[] = $indexDefn;
        }

        if (count($indexes)) {
            $indexesDefn = ", " . implode($indexes, ", ");
        }

        $return .= " `{$this->
                tables[0]}` (" . implode($columnsDefn, ", ") . "{$primaryKeysDefn}{$indexesDefn})";

        return $return . ";";
    }

    /**
     * Used to generate the alter table query.
     *
     * @return string Returns the generated query to alter the table.
     * @throws \Exception Exception is thrown if the structure of arguments is not correct.
     * @author Vipin Jain
     * @since  0.1
     */
    protected function RetAlterTable() {

        if (!isset($this->
                        tables[0])) {

            throw new \Exception("Error: Table not properly provided in QueryHelper.");
        }

        $return = "ALTER TABLE `{$this->
                tables[0]}` ";

        if (isset($this->
                        addArgs['rename']) &&
                is_string($this->
                        addArgs['rename']) &&
                count($this->
                        addArgs['rename'])) {

            $return .= " RENAME `{$this->
                    addArgs['rename']}`";
        } else if (count($this->
                        columns) || count($this->
                        indexes)) {

            $alterDefns = [];

            if (count($this->
                            columns)) {

                foreach ($this->
                columns as $column => $parms) {

                    $alterDefn = "";

                    if (isset($parms['request'])) {

                        $parms['request'] = strtoupper($parms['request']);
                    }

                    if (!isset($parms['request']) ||
                            !in_array($parms['request'], ['A', 'U', 'D'])) {

                        $parms['request'] = 'A';
                    }

                    switch ($parms['request']) {
                        default:
                        case 'A':

                            if (!isset($parms['type']) ||
                                    !is_string($parms['type']) ||
                                    !$parms['type']) {

                                $parms['type'] = 'VARCHAR';

                                $parms['length'] = !isset($parms['length']) ||
                                        !is_int($parms['length']) ||
                                        $parms['length'] < 1 ? 1 : $parms['length'];
                            }

                            $type = $parms['type'];

                            if (isset($parms['length']) &&
                                    is_int($parms['length']) &&
                                    $parms['length'] > 0) {

                                $type .= "({$parms['length']})";
                            }

                            $return .= " ADD COLUMN `{$column}` {$type}";

                            if (isset($parms['isnull']) &&
                                    $parms['isnull'] === false) {

                                $return .= " NOT null";
                            }

                            if (isset($parms['autoincrement']) &&
                                    $parms['autoincrement'] === true) {

                                $return .= " AUTO_INCREMENT";
                            }

                            if (isset($parms['default']) &&
                                    $parms['default']) {

                                if (isset($parms['default']['function']) &&
                                        $parms['default']['function']) {

                                    $return .= " DEFAULT {$parms['default']['function']}";
                                } else if (is_string($parms['default'])) {

                                    $return .= " DEFAULT '{$parms['default']}'";
                                }
                            }

                            if (isset($parms['place'])) {

                                $parms['place'] = strtoupper($parms['place']);

                                if ($parms['place'] == 'F') {

                                    $return .= " FIRST";
                                } else if ($parms['place'] == 'A' &&
                                        isset($parms['after']) &&
                                        is_string($parms['after']) &&
                                        $parms['after']) {

                                    $return .= " AFTER {$parms['after']}";
                                }
                            }
                            break;
                        case 'U':
                            if (isset($parms['rename']) &&
                                    is_string($parms['rename']) &&
                                    $parms['rename']) {

                                if (!isset($parms['type']) ||
                                        !is_string($parms['type']) ||
                                        !$parms['type']) {

                                    $parms['type'] = 'VARCHAR';

                                    $parms['length'] = !isset($parms['length']) ||
                                            !is_int($parms['length']) ||
                                            $parms['length'] < 1 ? 1 : $parms['length'];
                                }

                                $type = $parms['type'];

                                if (isset($parms['length']) &&
                                        is_int($parms['length']) &&
                                        $parms['length'] > 0) {

                                    $type .= "({$parms['length']})";
                                }

                                $alterDefn = "CHANGE COLUMN `{$column}` `{$parms['rename']}` {$type}";

                                if (isset($parms['isnull']) &&
                                        $parms['isnull'] === false) {

                                    $alterDefn .= " NOT null";
                                }

                                if (isset($parms['autoincrement']) &&
                                        $parms['autoincrement'] === true) {

                                    $alterDefn .= " AUTO_INCREMENT";
                                }

                                if (isset($parms['default']) &&
                                        $parms['default']) {

                                    if (isset($parms['default']['function']) &&
                                            $parms['default']['function']) {

                                        $alterDefn .= " DEFAULT {$parms['default']['function']}";
                                    } else if (is_string($parms['default'])) {

                                        $alterDefn .= " DEFAULT '{$parms['default']}'";
                                    }
                                }

                                if (isset($parms['place'])) {

                                    $parms['place'] = strtoupper($parms['place']);

                                    if ($parms['place'] == 'F') {

                                        $alterDefn .= " FIRST";
                                    } else if ($parms['place'] == 'A' &&
                                            isset($parms['after']) &&
                                            is_string($parms['after']) &&
                                            $parms['after']) {

                                        $alterDefn .= " AFTER `{$parms['after']}`";
                                    }
                                }
                            } else if (isset($parms['type']) &&
                                    is_string($parms['type']) &&
                                    $parms['type']) {

                                $parms['type'] = strtoupper($parms['type']);

                                if ($parms['type'] == 'VARCHAR') {

                                    $parms['length'] = !isset($parms['length']) ||
                                            !is_int($parms['length']) ||
                                            $parms['length'] < 1 ? 1 : $parms['length'];
                                }

                                $type = $parms['type'];

                                if (isset($parms['length']) &&
                                        is_int($parms['length']) &&
                                        $parms['length'] > 0) {

                                    $type .= "({$parms['length']})";
                                }

                                $alterDefn = "MODIFY COLUMN `{$column}` {$type}";

                                if (isset($parms['isnull']) &&
                                        $parms['isnull'] === false) {

                                    $alterDefn .= " NOT null";
                                }

                                if (isset($parms['autoincrement']) &&
                                        $parms['autoincrement'] === true) {

                                    $alterDefn .= " AUTO_INCREMENT";
                                }

                                if (isset($parms['default']) &&
                                        $parms['default']) {

                                    if (isset($parms['default']['function']) &&
                                            $parms['default']['function']) {

                                        $alterDefn .= " DEFAULT {$parms['default']['function']}";
                                    } else if (is_string($parms['default'])) {

                                        $alterDefn .= " DEFAULT '{$parms['default']}'";
                                    }
                                }

                                if (isset($parms['place'])) {

                                    $parms['place'] = strtoupper($parms['place']);

                                    if ($parms['place'] == 'F') {

                                        $alterDefn .= " FIRST";
                                    } else if ($parms['place'] == 'A' &&
                                            isset($parms['after']) &&
                                            is_string($parms['after']) &&
                                            $parms['after']) {

                                        $alterDefn .= " AFTER `{$parms['after']}`";
                                    }
                                }
                            } else if (isset($parms['dropdefault']) &&
                                    $parms['dropdefault'] === true) {

                                $alterDefn = "ALTER COLUMN `{$column}` DROP DEFAULT";
                            } else if (isset($parms['default'])) {

                                if (isset($parms['default']['function']) &&
                                        $parms['default']['function']) {

                                    $alterDefn = "ALTER COLUMN `{$column}` SET DEFAULT {$parms['default']['function']}";
                                } else if (is_string($parms['default'])) {

                                    $alterDefn = "ALTER COLUMN `{$column}` SET DEFAULT '{$parms['default']}'";
                                }
                            }
                            break;
                        case 'D':
                            $alterDefn = "DROP COLUMN `{$column}`";
                            break;
                    }
                }

                $alterDefns[] = $alterDefn;
            }
            if (count($this->
                            indexes)) {

                foreach ($indexesArr as $index => $parms) {

                    $alterDefn = "";
                    $indexName = "";

                    if (is_string($index)) {

                        $indexName = " `{$index}`";
                    }

                    if (!isset($parms['indextype'])) {

                        $parms['indextype'] = 'I';
                    }

                    $parms['indextype'] = strtoupper($parms['indextype']);

                    if (isset($parms['request'])) {

                        $parms['request'] = strtoupper($parms['request']);
                    }

                    if (!isset($parms['request']) ||
                            !in_array($parms['request'], ['A', 'D'])) {

                        $parms['request'] = 'A';
                    }

                    switch ($parms['request']) {
                        default:
                        case 'A':
                            switch ($parms['indextype']) {
                                default:
                                case 'I':
                                    $alterDefn .= "ADD INDEX{$indexName} (`" . implode($parms['columns'], "`, `") . "`)";
                                    break;
                                case 'K':
                                    $alterDefn .= "ADD KEY{$indexName} (`" . implode($parms['columns'], "`, `") . "`)";
                                    break;
                                case 'P':
                                    $alterDefn .= "ADD PRIMARY KEY (`" . implode($parms['columns'], "`, `") . "`)";
                                    break;
                                case 'U':
                                    $alterDefn .= "ADD UNIQUE{$indexName} (`" . implode($parms['columns'], "`, `") . "`)";
                                    break;
                                case 'UK':
                                    $alterDefn .= "ADD UNIQUE KEY{$indexName} (`" . implode($parms['columns'], "`, `") . "`)";
                                    break;
                                case 'UI':
                                    $alterDefn .= "ADD UNIQUE INDEX{$indexName} (`" . implode($parms['columns'], "`, `") . "`)";
                                    break;
                                case 'FK':
                                    $alterDefn .= "FOREIGN KEY{$indexName} (`" . implode($parms['columns'], "`, `") . "`) REFERENCES `{$parms['referencetable']}` (`" . implode($parms['referencecolumns'], "`, `") . "`)";
                                    break;
                                case 'FT':
                                    $alterDefn .= "ADD FULLTEXT{$indexName} (`" . implode($parms['columns'], "`, `") . "`)";
                                    break;
                                case 'FTK':
                                    $alterDefn .= "ADD FULLTEXT KEY{$indexName} (`" . implode($parms['columns'], "`, `") . "`)";
                                    break;
                                case 'FTI':
                                    $alterDefn .= "ADD FULLTEXT INDEX{$indexName} (`" . implode($parms['columns'], "`, `") . "`)";
                                    break;
                                case 'S':
                                    $alterDefn .= "ADD SPATIAL{$indexName} (`" . implode($parms['columns'], "`, `") . "`)";
                                    break;
                                case 'SK':
                                    $alterDefn .= "ADD SPATIAL KEY{$indexName} (`" . implode($parms['columns'], "`, `") . "`)";
                                    break;
                                case 'SI':
                                    $alterDefn .= "ADD SPATIAL INDEX{$indexName} (`" . implode($parms['columns'], "`, `") . "`)";
                                    break;
                                case 'CH':
                                    $alterDefn .= "ADD CHECK{$indexName} {$parms['check']}";
                                    break;
                            }
                            break;
                        case 'D':
                            if (!$indexName &&
                                    $parms['indextype'] != 'P') {

                                throw new \Exception("Error: Index name not provided in QueryHelper.");
                            }

                            switch ($parms['indextype']) {
                                default:
                                case 'I':
                                    $alterDefn .= "DROP INDEX{$indexName}";
                                    break;
                                case 'K':
                                    $alterDefn .= "DROP KEY{$indexName}";
                                    break;
                                case 'P':
                                    $alterDefn .= "DROP PRIMARY KEY";
                                    break;
                                case 'U':
                                    $alterDefn .= "DROP UNIQUE{$indexName}";
                                    break;
                                case 'UK':
                                    $alterDefn .= "DROP UNIQUE KEY{$indexName}";
                                    break;
                                case 'UI':
                                    $alterDefn .= "DROP UNIQUE INDEX{$indexName}";
                                    break;
                                case 'FK':
                                    $alterDefn .= "DROP FOREIGN KEY{$indexName}";
                                    break;
                                case 'FT':
                                    $alterDefn .= "DROP FULLTEXT{$indexName}";
                                    break;
                                case 'FTK':
                                    $alterDefn .= "DROP FULLTEXT KEY{$indexName}";
                                    break;
                                case 'FTI':
                                    $alterDefn .= "DROP FULLTEXT INDEX{$indexName}";
                                    break;
                                case 'S':
                                    $alterDefn .= "DROP SPATIAL{$indexName}";
                                    break;
                                case 'SK':
                                    $alterDefn .= "DROP SPATIAL KEY{$indexName}";
                                    break;
                                case 'SI':
                                    $alterDefn .= "DROP SPATIAL INDEX{$indexName}";
                                    break;
                                case 'CH':
                                    $alterDefn .= "DROP CHECK{$indexName}";
                                    break;
                            }
                            break;
                    }

                    $alterDefns[] = $alterDefn;
                }
            }
        } else {

            throw new \Exception("Error: Parameters not properly provided in QueryHelper.");
        }

        $return .= implode(", ", $alterDefns);

        return $return . ";";
    }

    /**
     * Used to generate the truncate table query.
     *
     * @return string Returns the generated query to truncate table.
     * @throws \Exception Exception is thrown if the structure of arguments is not correct.
     * @author Vipin Jain
     * @since  0.1
     */
    protected function RetTruncateTable() {

        if (!isset($this->
                        tables[0])) {

            throw new \Exception("Error: Table not properly provided in QueryHelper.");
        }

        return "TRUNCATE TABLE `{$this->
                tables[0]}`;";
    }

    /**
     * Used to generate the drop table query.
     *
     * @return string Returns the generated query to drop a table.
     * @throws \Exception Exception is thrown if the structure of arguments is not correct.
     * @author Vipin Jain
     * @since  0.1
     */
    protected function RetDropTable() {

        if (!isset($this->
                        tables[0])) {

            throw new \Exception("Error: Table not properly provided in QueryHelper.");
        }

        $isTemp = isset($this->
                        addArgs['istemp']) && $this->
                addArgs['istemp'] ? " TEMPORARY" : "";

        $exists = isset($this->
                        addArgs['exists']) && $this->
                addArgs['exists'] ? " IF EXISTS" : "";

        return "DROP{$isTemp} TABLE{$exists} `{$this->
                tables[0]}`;";
    }

    /**
     * Used to generate the create/replace view query.
     *
     * @return string Returns the generated query to create/replace view.
     * @throws \Exception Exception is thrown if the structure of arguments is not correct.
     * @author Vipin Jain
     * @since  0.1
     */
    protected function RetCreateView() {

        if (!isset($this->
                        tables[0])) {

            throw new \Exception("Error: View not properly provided in QueryHelper.");
        }

        $return = "CREATE OR REPLACE VIEW `{$this->
                tables[0]}`";

        if (count($this->
                        columns)) {

            $return .= " (`" . implode("`, `", $this->
                            columns) . "`)";
        }

        if (!$this->
                viewSelect ||
                !$this->
                        viewSelect->
                        IsSelect()) {

            throw new \Exception("Error: Helper not properly provided in QueryHelper.");
        }

        $return .= " AS " . $this->
                        viewSelect->
                        Generate();

        return $return . ";";
    }

    /**
     * Used to generate the drop view query.
     *
     * @return string Returns the generated query to drop a view.
     * @throws \Exception Exception is thrown if the structure of arguments is not correct.
     * @author Vipin Jain
     * @since  0.1
     */
    protected function RetDropView() {

        if (!isset($this->
                        tables[0])) {

            throw new \Exception("Error: View not properly provided in QueryHelper.");
        }

        $exists = isset($this->
                        addArgs['exists']) && $this->
                addArgs['exists'] ? " IF EXISTS" : "";

        return "DROP TABLE{$exists} `{$this->
                tables[0]}`;";
    }

    /**
     * Used to generate the insert query.
     *
     * @return string Returns the generated query to insert rows in a table.
     * @throws \Exception Exception is thrown if the structure of arguments is not correct.
     * @author Vipin Jain
     * @since  0.1
     */
    protected function RetInsert() {

        if (!isset($this->
                        tables[0])) {

            throw new \Exception("Error: Table not properly provided in QueryHelper.");
        }

        if (!(is_array($this->
                        values) &&
                count($this->
                        values)) &&
                !($this->
                values instanceof \FluitoPHP\Database\DBQueryHelper &&
                        $this->
                        values->
                        IsSelect())) {

            throw new \Exception("Error: Values not properly provided in QueryHelper.");
        }

        if ($this->
                values instanceof \FluitoPHP\Database\DBQueryHelper &&
                        $this->
                        values->
                        IsSelect()) {

            $this->
                    values->
                    GetRow();

            $columnsInfo = $this->
                    values->
                    GetColInfo();

            $columns = [];

            foreach ($columnsInfo as $value) {

                $columns[] = $value->
                        name;
            }

            $return = "INSERT INTO `{$this->
                    tables[0]}` (`" . implode("`, `", $columns) . "`) " . $this->
                            values->
                            Generate();
        } else {

            $multi = false;
            $columns = array_keys($this->
                    values);

            $valuesArr = [];

            if (!is_string($columns[0])) {

                if (!is_array($this->
                                values[$columns[0]])) {

                    throw new \Exception("Error: Values not properly provided in QueryHelper.");
                }

                $columns = array_keys($this->
                        values[$columns[0]]);

                $multi = true;
            }


            $return = "INSERT INTO `{$this->
                    tables[0]}` (`" . implode("`, `", $columns) . "`) VALUES ";


            if ($multi) {

                foreach ($this->
                values as $row) {

                    $values = array_values($row);

                    foreach ($values as $key => $value) {

                        if (is_string($value)) {

                            $value = $this->
                                    connection->
                                    GetConn()->
                                    real_escape_string($value);
                            $values[$key] = "'{$value}'";
                        } else if (isset($value['function'])) {

                            $values[$key] = $value['function'];
                        }
                    }

                    $valuesArr[] = "(" . implode(", ", $values) . ")";
                }
            } else {

                $values = array_values($this->
                        values);

                foreach ($values as $key => $value) {

                    if (is_string($value)) {

                        $value = $this->
                                connection->
                                GetConn()->
                                real_escape_string($value);
                        $values[$key] = "'{$value}'";
                    } else if (isset($value['function'])) {

                        $values[$key] = $value['function'];
                    }
                }

                $valuesArr[] = "(" . implode(", ", $values) . ")";
            }

            $return .= implode(", ", $valuesArr) . ";";
        }

        return $return;
    }

    /**
     * Used to generate the update query.
     *
     * @return string Returns the generated query to update rows in a table.
     * @throws \Exception Exception is thrown if the structure of arguments is not correct.
     * @author Vipin Jain
     * @since  0.1
     */
    protected function RetUpdate() {

        if (!isset($this->
                        tables[0])) {

            throw new \Exception("Error: Table not properly provided in QueryHelper.");
        }

        if (!is_array($this->
                        values) ||
                !count($this->
                        values)) {

            throw new \Exception("Error: Values not properly provided in QueryHelper.");
        }

        $return = "UPDATE `{$this->
                tables[0]}` SET ";

        $updatesArr = [];

        foreach ($this->
        values as $column => $value) {

            if (is_string($value)) {

                $value = $this->
                        connection->
                        GetConn()->
                        real_escape_string($value);
                $updatesArr[] = "`{$column}`='{$value}'";
            } else if (isset($value['function'])) {

                $updatesArr[] = "`{$column}`=" . $value['function'];
            }
        }

        $return .= implode(", ", $updatesArr);

        if (count($this->
                        where)) {

            $return .= $this->
                    WhereClause($this->
                    where);
        }

        return $return . ";";
    }

    /**
     * Used to generate the delete query.
     *
     * @return string Returns the generated query to delete rows from a table.
     * @throws \Exception Exception is thrown if the structure of arguments is not correct.
     * @author Vipin Jain
     * @since  0.1
     */
    protected function RetDelete() {

        if (!isset($this->
                        tables[0])) {

            throw new \Exception("Error: Table not properly provided in QueryHelper.");
        }

        $return = "DELETE FROM `{$this->
                tables[0]}`";

        if (count($this->
                        where)) {

            $return .= $this->
                    WhereClause($this->
                    where);
        }

        return $return . ";";
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
    public function Meta($metaFunction, $args) {

        $return = '';

        if (is_string($metaFunction)) {

            switch ($metaFunction) {

                case "&CurrDTTM":

                    $return = "SYSDATE()";

                    break;

                case "&CurrDT":

                    $return = "CURDATE()";

                    break;

                case "&CurrTM":

                    $return = "CURTIME()";

                    break;

                case '&MIN':

                    if (!isset($args[0])) {

                        break;
                    }

                    $return = "MIN({$args[0]})";

                    break;

                case '&MAX':

                    if (!isset($args[0])) {

                        break;
                    }

                    $return = "MAX({$args[0]})";

                    break;

                case '&COUNT':

                    if (!isset($args[0])) {

                        break;
                    }

                    $return = "COUNT({$args[0]})";

                    break;

                case '&AVG':

                    if (!isset($args[0])) {

                        break;
                    }

                    $return = "AVG({$args[0]})";

                    break;

                case '&SUM':

                    if (!isset($args[0])) {

                        break;
                    }

                    $return = "SUM({$args[0]})";

                    break;

                case '&DateSub':

                    if (!isset($args[0])) {

                        break;
                    }

                    $interval = isset($args[1]) ? $args[1] : 1;

                    $intervalType = 'SECOND';

                    if (isset($args[2])) {

                        switch ($args[2]) {
                            case 'Y': $intervalType = 'YEAR';
                                break;
                            case 'Q': $intervalType = 'QUARTER';
                                break;
                            case 'M': $intervalType = 'MONTH';
                                break;
                            case 'W': $intervalType = 'WEEK';
                                break;
                            case 'D': $intervalType = 'DAY';
                                break;
                            case 'H': $intervalType = 'HOUR';
                                break;
                            case 'I': $intervalType = 'MINUTE';
                                break;
                            case 'S': $intervalType = 'SECOND';
                                break;
                            case 'MS': $intervalType = 'MICROSECOND';
                                break;
                        }
                    }

                    if (isset($args[0])) {

                        $return = "DATE_SUB({$args[0]}, INTERVAL {$interval} {$intervalType})";
                    }

                    break;

                case '&DateAdd':

                    if (!isset($args[0])) {

                        break;
                    }

                    $interval = isset($args[1]) ? $args[1] : 1;

                    $intervalType = 'SECOND';

                    if (isset($args[2])) {

                        switch ($args[2]) {
                            case 'Y': $intervalType = 'YEAR';
                                break;
                            case 'Q': $intervalType = 'QUARTER';
                                break;
                            case 'M': $intervalType = 'MONTH';
                                break;
                            case 'W': $intervalType = 'WEEK';
                                break;
                            case 'D': $intervalType = 'DAY';
                                break;
                            case 'H': $intervalType = 'HOUR';
                                break;
                            case 'I': $intervalType = 'MINUTE';
                                break;
                            case 'S': $intervalType = 'SECOND';
                                break;
                            case 'MS': $intervalType = 'MICROSECOND';
                                break;
                        }
                    }

                    if (isset($args[0])) {

                        $return = "DATE_ADD({$args[0]}, INTERVAL {$interval} {$intervalType})";
                    }

                    break;
            }
        }

        return $return;
    }

}
