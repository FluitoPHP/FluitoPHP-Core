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
/**
 * Used for storing the Environment Type.
 *
 * @type string
 * @author Vipin Jain
 * @since  0.1
 */
if (!defined('ENV')) {

    define(
            'ENV', getenv('ENV') ? getenv('ENV') : 'PRODUCTION'
    );
}

/**
 * Used for storing the directory separator.
 *
 * @type string
 * @author Vipin Jain
 * @since  0.1
 */
if (!defined('DS')) {

    define(
            'DS', DIRECTORY_SEPARATOR
    );
}

/**
 * Used for storing the application absolute path.
 *
 * @type string
 * @author Vipin Jain
 * @since  0.1
 */
if (!defined('ABSOLUTE')) {

    define(
            'ABSOLUTE', realpath('.')
    );
}

/**
 * Specify Framework and application Parent directory. Used for storing the application directory path, includes both framework files and application
 *
 * @type string
 * @author Vipin Jain
 * @since  0.1
 */
if (!defined('FRAMEWORK')) {

    define(
            'FRAMEWORK', realpath('..' . DS . 'FluitoPHP')
    );
}

/**
 * Used for storing extensions path.
 *
 * @type string
 * @author Vipin Jain
 * @since  0.1
 */
if (!defined('EXTENSIONS')) {

    define(
            'EXTENSIONS', realpath('..' . DS . 'extensions')
    );
}

/**
 * Used for storing application path.
 *
 * @type string
 * @author Vipin Jain
 * @since  0.1
 */
if (!defined('APP')) {

    define(
            'APP', realpath('..' . DS . 'application')
    );
}

/**
 * Import basic Framework Class.
 */
require(FRAMEWORK . DS . 'FluitoPHP.class.php');

/**
 * Discover the environment and create the framework instance and start setup.
 */
/**
 * At last run the application or return the object to other calling application.
 */
if (defined('FP_FW_GET_INSTANCE') &&
        constant('FP_FW_GET_INSTANCE')) {

    return FluitoPHP\FluitoPHP::GetInstance(ENV);
} else {

    FluitoPHP\FluitoPHP::GetInstance(ENV)->
            Run();
}