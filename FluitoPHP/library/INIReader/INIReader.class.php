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

namespace FluitoPHP\INIReader;

/**
 * INIReader Class.
 *
 * This class reads the INI file and converts it to an array.
 *
 * Variables:
 *      1. $inheritance
 *      2. $parseSections
 *      3. $INIArray
 *
 * Functions:
 *      1. __construct
 *      2. GetInheritance
 *      3. GetParseSections
 *      4. Fetch
 *
 * @author Neha Jain
 * @since  0.1
 */
class INIReader extends \FluitoPHP\File\File {

    /**
     * Used for storing inheritance setting.
     *
     * @var bool
     * @author Neha Jain
     * @since  0.1
     */
    private $inheritance = true;

    /**
     * Used for storing parseSections setting.
     *
     * @var bool
     * @author Neha Jain
     * @since  0.1
     */
    private $parseSections = true;

    /**
     * Used for storing INI generated array.
     *
     * @var array
     * @author Neha Jain
     * @since  0.1
     */
    private $INIArray = null;

    /**
     * Used to initialize.
     *
     * @param array $args Provide parameters to the object.
     *                    'filePath' => File Path of the INI file.
     *                    'parseSections' => Provide true if sections are needed to be parsed.
     *                    'inheritance' => Provide true for inheritance of other file on the same path.
     *                                     Requires 'extends=<file to be inherited>' inside the INI.
     * @author Neha Jain
     * @since  0.1
     */
    public function __construct($args = []) {

        if (!isset($args['filePath']) || !is_string($args['filePath'])) {

            throw new \Exception("Error: Input INI file path is not provided.", 0);
        }

        parent::__construct(['filePath' => $args['filePath'], 'mode' => 'r']);

        $this->
                inheritance = isset($args['inheritance']) ? (bool) $args['inheritance'] : true;
        $this->
                parseSections = isset($args['parseSections']) ? (bool) $args['parseSections'] : true;
    }

    /**
     * Used to get inheritance setting.
     *
     * @return bool Returns the inheritance setting value.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetInheritance() {

        return $this->
                inheritance;
    }

    /**
     * Used to get parseSections setting.
     *
     * @return bool Returns the parse sections setting.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetParseSections() {

        return $this->
                parseSections;
    }

    /**
     * Used to read the ini file data.
     *
     * @return array returns the INI data in multi-dimensional array format.
     * @author Neha Jain
     * @since  0.1
     */
    public function Fetch() {

        if (!$this->
                INIArray) {

            $this->
                    INIArray = parse_ini_file($this->
                            GetPath(), $this->
                    parseSections);

            if ($this->
                    parseSections &&
                    $this->
                    inheritance) {

                foreach ($this->
                INIArray as $key => & $value) {

                    $exploded = explode(':', $key);

                    if (count($exploded) > 1) {

                        $final = $value;

                        for ($i = count($exploded) - 1; $i > 0; --$i) {

                            if (isset($this->
                                            INIArray[$exploded[$i]])) {

                                $final = array_replace_recursive($this->
                                        INIArray[$exploded[$i]], $final);
                            }
                        }

                        $this->
                                INIArray[$exploded[0]] = $final;

                        unset($this->
                                INIArray[$key]);
                    }
                }
            }

            $this->
                    INIArray = \FluitoPHP\Arrays\Arrays::DotsToMulti($this->
                            INIArray);
        }

        return $this->
                INIArray;
    }

}
