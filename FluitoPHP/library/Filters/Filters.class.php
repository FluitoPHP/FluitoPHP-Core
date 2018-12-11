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

namespace FluitoPHP\Filters;

/**
 * Filters Class.
 * 
 * This class is framework for running the filters in your application.
 * 
 * Variables:
 *      1. $instance
 *      2. $filtersMap
 *      3. $argsMap
 *      4. $priorityMap
 * 
 * Functions:
 *      1. __construct
 *      2. GetInstance
 *      3. Add
 *      4. Remove
 *      5. Run
 * 
 * @author Neha Jain
 * @since  0.1
 */
class Filters {

    /**
     * Used for storing Singleton instance.
     * 
     * @var \FluitoPHP\Filters\Filters
     * @author Neha Jain
     * @since  0.1
     */
    static private $instance = null;

    /**
     * Used to store the filters and run them suitably.
     * 
     * @var array
     * @author Neha Jain
     * @since  0.1
     */
    private $filtersMap = [];

    /**
     * Used to store the arguments of filters.
     * 
     * @var array
     * @author Neha Jain
     * @since  0.1
     */
    private $argsMap = [];

    /**
     * Used to store the priorities of filters.
     * 
     * @var array
     * @author Neha Jain
     * @since  0.1
     */
    private $priorityMap = [];

    /**
     * Used to make this class as a singleton class.
     * 
     * @author Neha Jain
     * @since  0.1
     */
    private function __construct() {
        
    }

    /**
     * Used to fetch the Instance object globally.
     * 
     * @return \FluitoPHP\Filters\Filters Returns this instance object.
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
     * Used to add a new filter.
     * 
     * @param string $filterId Filter ID when ran this function is invoked
     * @param callable $function Function/Method to call
     * @param int $arguments Number of arguments provided irrespective of the number of arguments in the call, default is 1.
     * @param int $priority Priority of the function, default is 10.
     * @return bool Returns true if successfully registers the function.
     * @author Neha Jain
     * @since  0.1
     */
    public function Add($filterId, $function, $arguments = 1, $priority = 10) {

        if (!$filterId ||
                !is_string($filterId) ||
                !is_callable($function)) {

            return false;
        }

        if (!is_int($arguments) ||
                $arguments < 1) {

            $arguments = 1;
        }

        if (!is_int($priority)) {

            $priority = 10;
        }

        if (!isset($this->
                        filtersMap[$filterId]) ||
                !is_array($this->
                        filtersMap[$filterId])) {

            $this->
                    filtersMap[$filterId] = [];
            $this->
                    argsMap[$filterId] = [];
            $this->
                    priorityMap[$filterId] = [];
        }

        array_push($this->
                filtersMap[$filterId], $function);

        array_push($this->
                argsMap[$filterId], $arguments);

        array_push($this->
                priorityMap[$filterId], $priority);

        return true;
    }

    /**
     * Used to remove an action.
     * 
     * @param string $filterId Filter ID from which the function is required to be removed.
     * @param callable $function Function definition which is required to be removed from the filter.
     * @return bool Returns true when successfully removed the function.
     * @author Neha Jain
     * @since  0.1
     */
    public function Remove($filterId, $function) {

        if (!$filterId ||
                !is_string($filterId) ||
                !is_callable($filterId)) {

            return false;
        }

        $key = array_search($function, $this->
                filtersMap[$filterId]);

        if ($key === false) {

            return false;
        }

        array_splice($this->
                filtersMap[$filterId], $key, 1);

        array_splice($this->
                argsMap[$filterId], $key, 1);

        array_splice($this->
                priorityMap[$filterId], $key, 1);

        return true;
    }

    /**
     * Used to run an filter.
     * 
     * @param string $name Provide the name of the filter that needs to be run.
     * @param mixed $object Provide the object that needs to be filtered.
     * @param mixed $parameters Provide the additional parameters.
     * @return void This function do not return any value, instead return is called when there is nothing to do.
     * @author Neha Jain
     * @since  0.1
     */
    public function Run() {

        $args = func_get_args();

        if (count($args) < 2 ||
                !is_string($args[0]) ||
                !isset($this->
                        filtersMap[$args[0]])) {

            return $args[1];
        }

        $filterId = array_shift($args);

        $priorityArr = $this->
                priorityMap[$filterId];

        while (count($priorityArr)) {

            $min = min($priorityArr);

            $key = array_search($min, $priorityArr);

            unset($priorityArr[$key]);

            $tempArgs = $args;

            if (count($tempArgs) > $this->
                    argsMap[$filterId][$key]) {

                array_splice($tempArgs, $this->
                        argsMap[$filterId][$key]);
            }

            $args[0] = call_user_func_array($this->
                    filtersMap[$filterId][$key], $tempArgs);
        }

        return $args[1];
    }

}
