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

namespace FluitoPHP\Base;

/**
 * Base Class.
 *
 * This class defines the basic structure and provides access to the basic functions to major classes.
 *
 * Variables:
 *      1. $framework
 *      2. $view
 *
 * Functions:
 *      1. Framework
 *      2. Request
 *      3. Response
 *      4. DB
 *      5. Session
 *      6. Authentication
 *      7. Authorization
 *      8. View
 *      9. Model
 *      10. Set
 *      11. Get
 *      12. URL
 *      13. Events
 *      14. Filters
 *      15. Load
 *      16. Extract
 *
 * @author Vipin Jain
 * @since  0.1
 */
class Base {

    /**
     * Used for storing framework object.
     *
     * @var \FluitoPHP\FluitoPHP
     * @author Vipin Jain
     * @since  0.1
     */
    static private $framework = null;

    /**
     * Used for storing view.
     *
     * @var \FluitoPHP\View\View
     * @author Vipin Jain
     * @since  0.1
     */
    static private $view = null;

    /**
     * Used to get framework object.
     *
     * @return \FluitoPHP\FluitoPHP Used to fetch framework object for quick access.
     * @author Vipin Jain
     * @since  0.1
     */
    final protected function Framework() {

        if (!self::$framework) {

            self::$framework = \FluitoPHP\FluitoPHP::GetInstance();
        }

        return self::$framework;
    }

    /**
     * Used to get request object.
     *
     * @return \FluitoPHP\Request\Request Used to fetch request object for quick access.
     * @author Vipin Jain
     * @since  0.1
     */
    final protected function Request() {

        return $this->
                        Framework()->
                        Request();
    }

    /**
     * Used to get response object.
     *
     * @return \FluitoPHP\Response\Response Used to fetch response object for quick access.
     * @author Vipin Jain
     * @since  0.1
     */
    final protected function Response() {

        return $this->
                        Framework()->
                        Response();
    }

    /**
     * Used to get database object.
     *
     * @return \FluitoPHP\Database\Database Used to fetch database object for quick access.
     * @author Vipin Jain
     * @since  0.1
     */
    final protected function DB() {

        return $this->
                        Framework()->
                        DB();
    }

    /**
     * Used to get session object.
     *
     * @return \FluitoPHP\Session\Session Used to fetch session object for quick access.
     * @author Vipin Jain
     * @since  0.1
     */
    final protected function Session() {

        return $this->
                        Framework()->
                        Session();
    }

    /**
     * Used to get authentication object.
     *
     * @return \FluitoPHP\Authentication\Authentication Used to fetch authentication object for quick access.
     * @author Vipin Jain
     * @since  0.1
     */
    final protected function Authentication() {

        return $this->
                        Framework()->
                        Authentication();
    }

    /**
     * Used to get authorization object.
     *
     * @return \FluitoPHP\Authorization\Authorization Used to fetch authorization object for quick access.
     * @author Vipin Jain
     * @since  0.1
     */
    final protected function Authorization() {

        return $this->
                        Framework()->
                        Authorization();
    }

    /**
     * Used to get current View object.
     *
     * @return \FluitoPHP\View\View Returns current View object.
     * @author Vipin Jain
     * @since  0.1
     */
    final protected function View() {

        if (!self::$view) {

            self::$view = $this->
                    Response()->
                    View();
        }

        return self::$view;
    }

    /**
     * Used to get the model.
     *
     * @param type $modelName Provide the model name or table name.
     * @return \FluitoPHP\Model\Model Returns the model for the table.
     * @throws \Exception Throws the exception if the model name is not correct.
     * @author Vipin Jain
     * @since  0.1
     */
    final protected function Model($modelName) {

        return $this->
                        Framework()->
                        Model($modelName);
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
    final protected function Set($key, $value) {

        return $this->
                        Framework()->
                        Set($key, $value);
    }

    /**
     * Used for fetching data for data transfer in the current request.
     *
     * @param string $key Provide the key value to fetch the data from data array.
     * @return mixed Returns false if the key is not found in the data array else returns the value.
     * @author Vipin Jain
     * @since  0.1
     */
    final protected function Get($key) {

        return $this->
                        Framework()->
                        Get($key);
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
     * @author Vipin Jain
     * @since  0.1
     */
    final protected function URL($pathOrController = '', $action = '', $module = '', $addArgs = [], $query = [], $customPrefix = null) {

        return $this->
                        Request()->
                        URL($pathOrController, $action, $module, $addArgs, $query, $customPrefix);
    }

    /**
     * Used to get events object.
     *
     * @return \FluitoPHP\Events\Events Used to fetch events object for quick access.
     * @author Vipin Jain
     * @since  0.1
     */
    final protected function Events() {

        return $this->
                        Framework()->
                        Events();
    }

    /**
     * Used to get filters object.
     *
     * @return \FluitoPHP\Filters\Filters Used to fetch filters object for quick access.
     * @author Vipin Jain
     * @since  0.1
     */
    final protected function Filters() {

        return $this->
                        Framework()->
                        Filters();
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
    final protected function Load($extension, $args = null, $instance = 0) {

        return $this->
                        Framework()->
                        Load($extension, $args, $instance);
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
                        Framework()->
                        Extract();
    }

}
