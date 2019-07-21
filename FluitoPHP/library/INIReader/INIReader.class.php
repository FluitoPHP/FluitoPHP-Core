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

namespace FluitoPHP\INIReader;

/**
 * INIReader Class.
 *
 * This class reads the INI file and converts it to an array.
 *
 * Variables:
 *      1. $inheritance
 *      2. $parseSections
 *      3. $decodeEnvars
 *      4. $INIArray
 *
 * Functions:
 *      1. __construct
 *      2. GetInheritance
 *      3. GetParseSections
 *      4. Fetch
 *
 * @author Vipin Jain
 * @since  0.1
 */
class INIReader extends \FluitoPHP\File\File {

    /**
     * Used for storing inheritance setting.
     *
     * @var bool
     * @author Vipin Jain
     * @since  0.1
     */
    private $inheritance = true;

    /**
     * Used for storing parseSections setting.
     *
     * @var bool
     * @author Vipin Jain
     * @since  0.1
     */
    private $parseSections = true;

    /**
     * Used for storing decodeEnvars setting.
     *
     * @var bool
     * @author Vipin Jain
     * @since  0.1
     */
    private $decodeEnvars = true;

    /**
     * Used for storing INI generated array.
     *
     * @var array
     * @author Vipin Jain
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
     *                    'decodeEnvars' => Provide true to update the %ENVAR% to be updated with the
     *                                      environment variables from OS/Web Server OR set variable
     *                                      set explicitly.
     * @author Vipin Jain
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
        $this->
                decodeEnvars = isset($args['decodeEnvars']) ? (bool) $args['decodeEnvars'] : true;
    }

    /**
     * Used to get inheritance setting.
     *
     * @return bool Returns the inheritance setting value.
     * @author Vipin Jain
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
     * @author Vipin Jain
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
     * @author Vipin Jain
     * @since  0.1
     */
    public function Fetch() {

        if ($this->
                INIArray === NULL) {

            $INIString = $this->
                    Read();

            if ($this->
                    decodeEnvars) {

                $offset = 0;

                while (($fi = strpos($INIString, '%', $offset)) !== false) {

                    if (substr($INIString, $fi + 1, 1) === '%') {

                        $offset = $fi + 2;
                        continue;
                    }

                    $fie = strpos($INIString, '%', $fi + 1);

                    if ($fie !== false) {

                        $envarName = substr($INIString, $fi + 1, $fie - $fi - 1);
                        $envarValue = getenv($envarName);

                        if ($envarValue !== false) {

                            $INIString = substr($INIString, 0, $fi) . $envarValue . substr($INIString, $fie + 1);
                        }
                    }
                }

                $INIString = str_replace('%%', '%', $INIString);
            }

            $this->
                    INIArray = parse_ini_string($INIString, $this->
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
