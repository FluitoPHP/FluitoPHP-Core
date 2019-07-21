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

namespace FluitoPHP\ErrorHandler;

/**
 * DefaultErrorHandler Class.
 *
 * This class is used if no class is defined for error handling in the application.
 *
 * Variables:
 *
 * Functions:
 *      1. indexHandle
 *      2. httpHandle
 *      3. http404Handle
 *
 * @author Vipin Jain
 * @since  0.1
 */
class DefaultErrorHandler extends \FluitoPHP\ErrorHandler\ErrorHandler {

    /**
     * Used to handle all errors.
     *
     * @param \Exception $e Provide the exception that needs to be handled.
     * @author Vipin Jain
     * @since  0.1
     */
    public function indexHandle($e) {

        $this->
                Response()->
                SetHTTPCode(500, $e->
                        getMessage());

        $this->
                Set('excpMsg', $e->
                        getMessage());

        $this->
                Set('excpTrc', $e->
                        getTrace());
    }

    /**
     * Used to handle HTTP errors.
     *
     * @param \Exception $e Provide the exception that needs to be handled.
     * @author Vipin Jain
     * @since  0.1
     */
    public function httpHandle($e) {

        $this->
                Response()->
                SetHTTPCode($e->
                        GetHttpCode(), $e->
                        getMessage());

        $this->
                Set('excpMsg', $e->
                        getMessage());

        $this->
                Set('excpTrc', $e->
                        getTrace());
    }

    /**
     * Used to handle HTTP 404 errors.
     *
     * @param \Exception $e Provide the exception that needs to be handled.
     * @author Vipin Jain
     * @since  0.1
     */
    public function http404Handle($e) {

        $this->
                Response()->
                SetHTTPCode($e->
                        GetHttpCode(), $e->
                        getMessage());

        $this->
                Set('excpMsg', $e->
                        getMessage());

        $this->
                Set('excpTrc', $e->
                        getTrace());
    }

}
