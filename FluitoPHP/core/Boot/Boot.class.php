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

namespace FluitoPHP\Boot;

/**
 * Abstract Boot Class.
 * 
 * This class defines the basic structure and provides access to the basic functions to boot classes.
 * 
 * Variables:
 * 
 * Functions:
 *      1. __construct
 *      2. Run
 * 
 * @author Neha Jain
 * @since  0.1
 */
abstract class Boot extends \FluitoPHP\Base\Base {

    /**
     * Constructor to initialize this class.
     * 
     * @author Neha Jain
     * @since  0.1
     */
    function __construct() {
        
    }

    /**
     * Abstract Run to run the boot class.
     * 
     * @author Neha Jain
     * @since  0.1
     */
    abstract public function Run();
}
