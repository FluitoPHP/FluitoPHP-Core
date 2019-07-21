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

namespace FluitoPHP\Events;

/**
 * Events Class.
 *
 * This class is framework for running the events in your application.
 *
 * Variables:
 *      1. $instance
 *      2. $eventsMap
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
 * @author Vipin Jain
 * @since  0.1
 */
class Events {

    /**
     * Used for storing Singleton instance.
     *
     * @var \FluitoPHP\Events\Events
     * @author Vipin Jain
     * @since  0.1
     */
    static private $instance = null;

    /**
     * Used to store the events and run them suitably.
     *
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    private $eventsMap = [];

    /**
     * Used to store the arguments of events.
     *
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    private $argsMap = [];

    /**
     * Used to store the priorities of events.
     *
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    private $priorityMap = [];

    /**
     * Used to make this class as a singleton class.
     *
     * @author Vipin Jain
     * @since  0.1
     */
    private function __construct() {

    }

    /**
     * Used to fetch the Instance object globally.
     *
     * @return \FluitoPHP\Events\Events Returns this instance object.
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
     * Used to add a new action.
     *
     * @param string $actionId Action ID when ran this function is invoked
     * @param callable $function Function/Method to call
     * @param int $arguments Number of arguments provided irrespective of the number of arguments in the call, default is 0.
     * @param int $priority Priority of the function, default is 10.
     * @return bool Returns true if successfully registers the function.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Add($actionId, $function, $arguments = 0, $priority = 10) {

        if (!$actionId ||
                !is_string($actionId) ||
                !is_callable($function)) {

            return false;
        }

        if (!is_int($arguments) ||
                $arguments < 0) {

            $arguments = 0;
        }

        if (!is_int($priority)) {

            $priority = 10;
        }

        if (!isset($this->
                        eventsMap[$actionId]) ||
                !is_array($this->
                        eventsMap[$actionId])) {

            $this->
                    eventsMap[$actionId] = [];
            $this->
                    argsMap[$actionId] = [];
            $this->
                    priorityMap[$actionId] = [];
        }

        array_push($this->
                eventsMap[$actionId], $function);

        array_push($this->
                argsMap[$actionId], $arguments);

        array_push($this->
                priorityMap[$actionId], $priority);

        return true;
    }

    /**
     * Used to remove an action.
     *
     * @param string $actionId Action ID from which the function is required to be removed.
     * @param callable $function Function definition which is required to be removed from the action.
     * @return bool Returns true when successfully removed the function.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Remove($actionId, $function) {

        if (!$actionId ||
                !is_string($actionId) ||
                !is_callable($function)) {

            return false;
        }

        $key = array_search($function, $this->
                eventsMap[$actionId]);

        if ($key === false) {

            return false;
        }

        array_splice($this->
                eventsMap[$actionId], $key, 1);

        array_splice($this->
                argsMap[$actionId], $key, 1);

        array_splice($this->
                priorityMap[$actionId], $key, 1);

        return true;
    }

    /**
     * Used to run an action.
     *
     * @param string $name Provide the name of the action that needs to be run.
     * @param mixed $parameters Provide the additional parameters.
     * @return void This function do not return any value, instead return is called when there is nothing to do.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Run() {

        $args = func_get_args();

        if (!count($args) ||
                !is_string($args[0]) ||
                !isset($this->
                        eventsMap[$args[0]])) {

            return;
        }

        $actionId = array_shift($args);

        $priorityArr = $this->
                priorityMap[$actionId];

        while (count($priorityArr)) {

            $min = min($priorityArr);

            $key = array_search($min, $priorityArr);

            unset($priorityArr[$key]);

            $tempArgs = $args;

            if (count($tempArgs) > $this->
                    argsMap[$actionId][$key]) {

                array_splice($tempArgs, $this->
                        argsMap[$actionId][$key]);
            }

            call_user_func_array($this->
                    eventsMap[$actionId][$key], $tempArgs);
        }
    }

}
