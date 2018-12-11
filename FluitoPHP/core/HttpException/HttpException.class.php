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

namespace FluitoPHP\HttpException;

/**
 * HttpException Class.
 * 
 * Used to identify if the actual issue arising the error is HTTP issue.
 * 
 * Variables:
 *      1. $httpCode
 * 
 * Functions:
 *      1. __construct
 *      2. GetHttpCode
 * 
 * @author Neha Jain
 * @since  0.1
 */
class HttpException extends \Exception {

    /**
     * Used for storing HTTP code.
     * 
     * @var int
     * @author Neha Jain
     * @since  0.1
     */
    private $httpCode;

    /**
     * Used to initialize exception.
     * 
     * @param string $message Provide the error message.
     * @param int $httpCode Provide the HTTP error code.
     * @param int $code Provide the error code.
     * @param \Throwable $previous Provide the chained exception.
     * @author Neha Jain
     * @since  0.1
     */
    function __construct($message = '', $httpCode = 404, $code = 0, $previous = null) {


        parent::__construct($message, $code, $previous);

        $this->
                httpCode = $httpCode;
    }

    /**
     * Used to fetch the HTTP code.
     * 
     * @return int
     * @author Neha Jain
     * @since  0.1
     */
    public function GetHttpCode() {

        return $this->
                httpCode;
    }

}
