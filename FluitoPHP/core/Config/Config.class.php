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

namespace FluitoPHP\Config;

/**
 * Config Class.
 *
 * This class is used to fetch the configuration from configuration files.
 *
 * Variables:
 *      1. $defaultInstanceType
 *      2. $instanceType
 *      3. $module
 *      4. $defaultConfigType
 *      5. $configType
 *      6. $configTypes
 *      7. $configExtensions
 *      8. $config
 *      9. $configObject
 *
 * Functions:
 *      1. __construct
 *      2. Fetch
 *      3. Get
 *      4. GetModule
 *
 * @author Vipin Jain
 * @since  0.1
 */
class Config {

    /**
     * Used for storing default instance type.
     *
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    private $defaultInstanceType = 'PRODUCTION';

    /**
     * Used for storing instance type.
     *
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    private $instanceType = null;

    /**
     * Used for storing current requested module.
     *
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    private $module = null;

    /**
     * Used for storing default configuration type.
     *
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    private $defaultConfigType = 'INI';

    /**
     * Used for storing configuration type.
     *
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    private $configType = null;

    /**
     * Used for storing available configuration types.
     *
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    private $configTypes = array('INI');

    /**
     * Used for storing available configuration type extensions.
     *
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    private $configExtensions = array(
        'INI' => 'ini'
    );

    /**
     * Used for storing currently read configuration.
     *
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    private $config = null;

    /**
     * Used for storing currently read configuration object.
     *
     * @var \FluitoPHP\INIReader\INIReader
     * @author Vipin Jain
     * @since  0.1
     */
    private $configObject = null;

    /**
     * Used to initialize the Config object.
     *
     * @param array $args Provide parameters to the object.
     *                    'instanceType' => Provide the instance type. e.g.: PRODUCTION, DEVELOPMENT
     *                    'module' => Provide current module name for which configuration needs to be fetched.
     *                    'configType' => Provide the configuration type from the list of available configuration types.
     * @author Vipin Jain
     * @since  0.1
     */
    public function __construct($args = []) {

        $args['instanceType'] = isset($args['instanceType']) ? $args['instanceType'] : 'PRODUCTION';

        $args['module'] = isset($args['module']) ? $args['module'] : null;

        $args['configType'] = isset($args['configType']) ? $args['configType'] : 'INI';

        $this->
                Fetch($args['instanceType'], $args['module'], $args['configType']);
    }

    /**
     * Used to initialize or change the configuration.
     *
     * @param string $instanceType Provide the instance type. e.g.: PRODUCTION, DEVELOPMENT
     * @param string $module Provide current module name for which configuration needs to be fetched.
     * @param string $configType Provide the configuration type from the list of available configuration types.
     * @return $this Return the object itself for chained calls.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Fetch($instanceType, $module = null, $configType = 'INI') {

        if (!$instanceType ||
                !is_string($instanceType)) {

            $instanceType = $this->
                    defaultInstanceType;
        }

        if (!in_array($configType, $this->
                        configTypes)) {

            $configType = $this->
                    defaultConfigType;
        }

        $module = $module ? strtolower($module) : null;

        if ($module &&
                !file_exists(MODULE . DS . $module)) {

            $module = null;
        }

        $this->
                instanceType = strtoupper($instanceType);
        $this->
                module = $module;
        $this->
                configType = strtoupper($configType);

        switch ($this->
        configType) {

            case 'INI': {

                    if ($this->
                            module) {

                        $filePath = MODULE . DS . $this->
                                module . MODCONFIG . DS . $this->
                                instanceType . '.' . $this->
                                configExtensions[$this->
                                configType];
                    } else {

                        $filePath = CONFIG . DS . $this->
                                instanceType . '.' . $this->
                                configExtensions[$this->
                                configType];
                    }

                    $this->
                            configObject = new \FluitoPHP\INIReader\INIReader(['filePath' => $filePath]);

                    $returnConfig = $this->
                            configObject->
                            Fetch();

                    if (isset($returnConfig['include'])) {

                        if (is_string($returnConfig['include'])) {

                            if ($returnConfig['include']) {

                                $this->
                                        Includer($returnConfig, $returnConfig['include']);
                            }
                        } else if (is_array($returnConfig['include'])) {

                            $includer = array_pop($returnConfig['include']);

                            while ($includer) {

                                $this->
                                        Includer($returnConfig, $includer);

                                $includer = array_pop($returnConfig['include']);
                            }
                        }

                        unset($returnConfig['include']);
                    }

                    while (isset($returnConfig['extends']) &&
                    is_string($returnConfig['extends']) &&
                    $returnConfig['extends']) {

                        if (file_exists($returnConfig['extends'])) {

                            $filePath = $returnConfig['extends'];
                        } else if (file_exists($returnConfig['extends'] . '.' . $this->
                                        configExtensions[$this->
                                        configType])) {

                            $filePath = $returnConfig['extends'] . '.' . $this->
                                    configExtensions[$this->
                                    configType];
                        } else if ($this->
                                module) {

                            $filePath = MODULE . DS . $this->
                                    module . MODCONFIG . DS . $returnConfig['extends'] . '.' . $this->
                                    configExtensions[$this->
                                    configType];
                        } else {

                            $filePath = CONFIG . DS . $returnConfig['extends'] . '.' . $this->
                                    configExtensions[$this->
                                    configType];
                        }

                        unset($returnConfig['extends']);

                        if (file_exists($filePath)) {

                            $extendedObject = new \FluitoPHP\INIReader\INIReader(['filePath' => $filePath]);

                            $extended = $extendedObject->
                                    Fetch();

                            foreach ($extended as $key => & $value) {

                                if (!isset($returnConfig[$key])) {

                                    $returnConfig[$key] = $value;
                                } else {

                                    $value = is_array($value) ? $value : array($value);

                                    $returnConfig[$key] = is_array($returnConfig[$key]) ? $returnConfig[$key] : array($returnConfig[$key]);

                                    $returnConfig[$key] = array_replace_recursive($value, $returnConfig[$key]);
                                }
                            }
                        }

                        if (isset($returnConfig['include'])) {

                            if (is_string($returnConfig['include'])) {

                                if ($returnConfig['include']) {

                                    $this->
                                            Includer($returnConfig, $returnConfig['include']);
                                }
                            } else if (is_array($returnConfig['include'])) {

                                $includer = array_pop($returnConfig['include']);

                                while ($includer) {

                                    $this->
                                            Includer($returnConfig, $includer);

                                    $includer = array_pop($returnConfig['include']);
                                }
                            }

                            unset($returnConfig['include']);
                        }
                    }

                    $this->
                            config = $returnConfig;

                    break;
                }
        }

        return $this;
    }

    /**
     * Used to include additional file in the configuration.
     *
     * @param type $returnConfig The configuration array that needs to be updated with the include.
     * @param type $includer The configuration that needs to included.
     * @author Vipin Jain
     * @since  0.1
     */
    private function Includer(&$returnConfig, $includer) {

        if (file_exists($includer)) {

            $filePath = $includer;
        } else if (file_exists($includer . '.' . $this->
                        configExtensions[$this->
                        configType])) {

            $filePath = $includer . '.' . $this->
                    configExtensions[$this->
                    configType];
        } else if ($this->
                module) {

            $filePath = MODULE . DS . $this->
                    module . MODCONFIG . DS . $includer . '.' . $this->
                    configExtensions[$this->
                    configType];
        } else {

            $filePath = CONFIG . DS . $includer . '.' . $this->
                    configExtensions[$this->
                    configType];
        }

        if (file_exists($filePath)) {

            $extendedObject = new \FluitoPHP\INIReader\INIReader(['filePath' => $filePath]);

            $extended = $extendedObject->
                    Fetch();

            foreach ($extended as $key => & $value) {

                if (!isset($returnConfig[$key]) ||
                        (!is_array($value) &&
                        !is_array($returnConfig[$key]))) {

                    $returnConfig[$key] = $value;
                } else {

                    $value = is_array($value) ? $value : array($value);

                    $returnConfig[$key] = is_array($returnConfig[$key]) ? $returnConfig[$key] : array($returnConfig[$key]);

                    $returnConfig[$key] = array_replace_recursive($returnConfig[$key], $value);
                }
            }
        }
    }

    /**
     * Used to get the configuration variable.
     *
     * @param string $variable Provide the variable name from the configuration file.
     * @return mixed This method returns the value of the variable from the configuration file, either array or string.
     * @throws \Exception Exception is thrown if the configuration file is not read.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Get($variable = null) {

        if ($this->
                config === null) {

            throw new \Exception('Configuration not read.', 0);
        }

        if ($variable &&
                !isset($this->
                        config[$variable])) {

            return null;
        }

        return $variable ? $this->
                config[$variable] : $this->
                config;
    }

    /**
     * Used to get module of the current configuration.
     *
     * @return string Returns module name for the configuration else returns null in case this is global configuration.
     * @author Vipin Jain
     * @since  0.1
     */
    public function GetModule() {

        return $this->
                module;
    }

}
