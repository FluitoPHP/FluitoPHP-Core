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

namespace FluitoPHP\ErrorHandler;

/**
 * Abstract ErrorHandler Class.
 * 
 * This class defines the basic structure and provides access to the basic functions to error handler classes.
 * 
 * Variables:
 *      
 * 
 * Functions:
 *      1. indexAction
 * 
 * @author Neha Jain
 * @since  0.1
 */
abstract class ErrorHandler extends \FluitoPHP\Base\Base {

    /**
     * Abstract index handle to maintain error handling.
     * 
     * @param \Exception $exception Provide the exception that needs to be handled.
     * @author Neha Jain
     * @since  0.1
     */
    abstract public function indexHandle($exception);
}
