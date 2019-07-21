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

namespace FluitoPHP\Error;

/**
 * Error Class.
 * 
 * This class is used to show the errors associated.
 * 
 * Variables:
 *      1. $errors
 * 
 * Functions:
 *      1. __construct
 *      2. __toString
 *      3. AddError
 *      4. IsError
 *      5. GetErrors
 *      6. GetMessages
 *      7. GetNames
 * 
 * @author Vipin Jain
 * @since  0.1
 */
class Error {

    /**
     * Used for storing error names/messages.
     * 
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    protected $errors = [];

    /**
     * Used to initialize the class.
     * 
     * @param mixed $message Provide message in string or array of strings format.
     * @param mixed $key Provide error key in string or array of strings format.
     * @author Vipin Jain
     * @since  0.1
     */
    function __construct($message = null, $key = null) {

        $this->
                AddError($message, $key);
    }

    /**
     * Used to get string representation.
     * 
     * @return string Returns the string representation.
     * @author Vipin Jain
     * @since  0.1
     */
    function __toString() {

        $return = '';
        $returnArr = [];
        $count = 1;

        if (count($this->
                        errors) > 1) {

            foreach ($this->
            errors as $key => $value) {

                if (is_string($key)) {

                    $returnArr[] = "{$count}). {$value} ({$key}).";
                } else {

                    $returnArr[] = "{$count}). {$value}.";
                }

                $count++;
            }

            $return .= implode("\r\n", $returnArr);
        } else {

            $arrayKeys = array_keys($this->
                    errors);

            if (is_string($arrayKeys[0])) {

                $return .= "Error: {$this->
                        errors[$arrayKeys[0]]} ({$arrayKeys[0]}).";
            } else {

                $return .= "Error: {$this->
                        errors[$arrayKeys[0]]}.";
            }
        }

        return $return;
    }

    /**
     * Used to add error(s).
     * 
     * @param mixed $message Provide message in string or array of strings format.
     * @param mixed $key Provide error key in string or array of strings format.
     * @return \FluitoPHP\Error\Error Returns self reference for chained calls.
     * @author Vipin Jain
     * @since  0.1
     */
    public function AddError($message = null, $key = null) {

        if (is_string($message) &&
                strlen($message)) {

            $key = preg_replace('/[^a-z0-9_\-]/i', '', $key);

            if (!is_string($key) ||
                    !strlen($key)) {

                $this->
                        errors[] = $message;
            } else {

                $this->
                        errors[$key] = $message;
            }
        } else if (is_array($message) &&
                count($message)) {

            if (!is_array($key)) {

                $key = [];
            }

            foreach ($message as $k => $value) {

                if (is_string($value) &&
                        strlen($value)) {

                    $key[$k] = preg_replace('/[^a-z0-9_\-]/i', '', $key[$k]);

                    if (isset($key[$k]) &&
                            is_string($key[$k]) &&
                            strlen($key[$k])) {

                        $this->
                                errors[$key[$k]] = $value;
                    } else {

                        $this->
                                errors[] = $value;
                    }
                }
            }
        }
    }

    /**
     * Used to check if this is an error object.
     * 
     * @param mixed $object Provide the return value to check if the object is error object or not.
     * @return bool Returns true if the object is an error, and false instead.
     * @author Vipin Jain
     * @since  0.1
     */
    static public function IsError($object) {

        if ($object instanceof \FluitoPHP\Error\Error) {

            return true;
        }

        return false;
    }

    /**
     * Used to get errors in an associative array.
     * 
     * @param mixed $keys Provide keys in array of string or a single key in string format.
     * @return array Returns the associative array containing errors.
     * @author Vipin Jain
     * @since  0.1
     */
    public function GetErrors($keys = []) {

        if (!is_array($keys)) {

            $keys = [$keys];
        }

        $return = [];

        if (!count($keys)) {

            $return = $this->
                    errors;
        } else {

            foreach ($keys as $value) {

                $return[$value] = $this->
                        errors[$value];
            }
        }

        return $return;
    }

    /**
     * Used to get error messages in an array.
     * 
     * @param mixed $keys Provide keys in array of string or a single key in string format.
     * @return array Returns the array containing error messages.
     * @author Vipin Jain
     * @since  0.1
     */
    public function GetMessages($keys = []) {

        if (!is_array($keys)) {

            $keys = [$keys];
        }

        $return = [];

        if (!count($keys)) {

            $return = array_values($this->
                    errors);
        } else {

            foreach ($keys as $value) {

                $return[] = $this->
                        errors[$value];
            }
        }

        return $return;
    }

    /**
     * Used to get error keys in an array.
     * 
     * @return array Returns the array containing error keys.
     * @author Vipin Jain
     * @since  0.1
     */
    public function GetKeys() {

        return array_keys($this->
                errors);
    }

}
