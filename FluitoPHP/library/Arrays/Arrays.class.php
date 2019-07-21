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

namespace FluitoPHP\Arrays;

/**
 * Arrays Class.
 *
 * This class is framework for array methods in the application.
 *
 * Variables:
 *
 * Functions:
 *      1. __construct
 *      2. DotsToMulti
 *      3. MultiToDots
 *
 * @author Vipin Jain
 * @since  0.1
 */
class Arrays {

    /**
     * Used to initialize the class.
     *
     * @author Vipin Jain
     * @since  0.1
     */
    function __construct() {

    }

    /**
     * Used to format the keys of the array to multi dimensional array.
     *
     * @param array $arr Provide the array that needs to be converted to multi dimensional.
     * @return array Returns the updated array.
     * @author Vipin Jain
     * @since  0.1
     */
    static public function DotsToMulti($arr) {

        if (is_array($arr)) {

            $ikeys = array_keys($arr);
            $icount = count($ikeys);

            for ($i = 0; $i < $icount; $i++) {

                if (is_array($arr[$ikeys[$i]])) {

                    $jkeys = array_keys($arr[$ikeys[$i]]);
                    $jcount = count($jkeys);

                    for ($j = 0; $j < $jcount; $j++) {

                        if (is_array($arr[$ikeys[$i]][$jkeys[$j]])) {

                            $kkeys = array_keys($arr[$ikeys[$i]][$jkeys[$j]]);
                            $kcount = count($kkeys);

                            for ($k = 0; $k < $kcount; $k++) {

                                if (is_array($arr[$ikeys[$i]][$jkeys[$j]][$kkeys[$k]])) {

                                }

                                $kkeyexp = explode('.', $kkeys[$k]);

                                $kkeyexpcount = count($kkeyexp);

                                if ($kkeyexpcount > 1) {

                                    $kvalue = array($kkeyexp[$kkeyexpcount - 1] => $arr[$ikeys[$i]][$jkeys[$j]][$kkeys[$k]]);
                                    unset($arr[$ikeys[$i]][$jkeys[$j]][$kkeys[$k]]);

                                    for ($c = $kkeyexpcount - 2; $c > 0; $c--) {

                                        $kvalue_tmp = array($kkeyexp[$c] => $kvalue);
                                        $kvalue = $kvalue_tmp;
                                    }

                                    $arr[$ikeys[$i]][$jkeys[$j]][$kkeyexp[0]] = array_replace_recursive(isset($arr[$ikeys[$i]][$jkeys[$j]][$kkeyexp[0]]) ? $arr[$ikeys[$i]][$jkeys[$j]][$kkeyexp[0]] : array(), $kvalue);
                                }
                            }
                        }

                        $jkeyexp = explode('.', $jkeys[$j]);

                        $jkeyexpcount = count($jkeyexp);

                        if ($jkeyexpcount > 1) {

                            $jvalue = array($jkeyexp[$jkeyexpcount - 1] => $arr[$ikeys[$i]][$jkeys[$j]]);
                            unset($arr[$ikeys[$i]][$jkeys[$j]]);

                            for ($b = $jkeyexpcount - 2; $b > 0; $b--) {

                                $jvalue_tmp = array($jkeyexp[$b] => $jvalue);
                                $jvalue = $jvalue_tmp;
                            }

                            $arr[$ikeys[$i]][$jkeyexp[0]] = array_replace_recursive(isset($arr[$ikeys[$i]][$jkeyexp[0]]) ? $arr[$ikeys[$i]][$jkeyexp[0]] : array(), $jvalue);
                        }
                    }
                }

                $ikeyexp = explode('.', $ikeys[$i]);

                $ikeyexpcount = count($ikeyexp);

                if ($ikeyexpcount > 1) {

                    $ivalue = array($ikeyexp[$ikeyexpcount - 1] => $arr[$ikeys[$i]]);
                    unset($arr[$ikeys[$i]]);

                    for ($a = $ikeyexpcount - 2; $a > 0; $a--) {

                        $ivalue_tmp = array($ikeyexp[$a] => $ivalue);
                        $ivalue = $ivalue_tmp;
                    }

                    $arr[$ikeyexp[0]] = array_replace_recursive(isset($arr[$ikeyexp[0]]) ? $arr[$ikeyexp[0]] : array(), $ivalue);
                }
            }
        }

        return $arr;
    }

    /**
     * Used to convert multi dimensional array to dots keys.
     *
     * @param array $arr Provide the array that needs to be converted to dots keys.
     * @return array Returns the updated array.
     * @author Vipin Jain
     * @since  0.1
     */
    static public function MultiToDots($arr) {

        $lastRunContains = false;

        if (is_array($arr)) {

            do {
                $lastRunContains = false;

                foreach ($arr as $key => $value) {

                    if (is_array($value)) {

                        $temp_array = [];

                        foreach ($value as $keyin => $valuein) {

                            $keyin = $key . '.' . $keyin;

                            $temp_array[$keyin] = $valuein;
                        }

                        unset($arr[$key]);
                        $arr = array_replace($arr, $temp_array);
                        $lastRunContains = true;
                    }
                }
            } while ($lastRunContains);
        }

        return $arr;
    }

}
