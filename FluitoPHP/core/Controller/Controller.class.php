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

namespace FluitoPHP\Controller;

/**
 * Abstract Controller Class.
 * 
 * This class defines the basic structure and provides access to the basic functions to controller classes.
 * 
 * Variables:
 * 
 * Functions:
 *      1. __construct
 *      2. Run
 *      3. indexAction
 * 
 * @author Neha Jain
 * @since  0.1
 */
abstract class Controller extends \FluitoPHP\Base\Base {

    function __construct() {
        
    }

    /**
     * Used to run the controller action and render views.
     * 
     * @param string $action Provide the action to be invoked.
     * @author Neha Jain
     * @since  0.1
     */
    public function Run($action) {

        call_user_func(array(
            $this,
            $action . 'Action'
        ));
    }

    /**
     * Abstract index action to maintain request handling.
     * 
     * @author Neha Jain
     * @since  0.1
     */
    abstract public function indexAction();
}
