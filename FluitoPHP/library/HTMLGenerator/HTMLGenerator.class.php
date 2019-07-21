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

namespace FluitoPHP\HTMLGenerator;

/**
 * HTMLGenerator Class.
 *
 * This class is generating HTML from PHP objects and arrays.
 *
 * Variables:
 *
 *
 * Functions:
 *      1. __construct
 *      2. GenerateFromArray
 *
 * @author Vipin Jain
 * @since  0.1
 */
class HTMLGenerator {

    /**
     * Used to initialize this class.
     *
     * @author Vipin Jain
     * @since  0.1
     */
    function __construct() {

    }

    /**
     * Used to generate the HTML from array with below structure.
     *
     * element
     * |--tag
     * |
     * |--class
     * |
     * |--id
     * |
     * |--attributes (key value pairs)
     * |
     * |--children (contains array of elements/text)
     *
     * @param array $elements Provide the reference array of elements.
     * @return string Returns the generated HTML.
     * @author Vipin Jain
     * @since  0.1
     */
    static public function GenerateFromArray($elements) {

        $return = '';

        foreach ($elements as $element) {

            if (!is_array($element)) {

                $return .= $element;
            } else if (isset($element['tag']) &&
                    is_string($element['tag']) &&
                    $element['tag']) {

                $element['tag'] = strtolower($element['tag']);

                $return .= '<' . $element['tag'];

                if (isset($element['id']) &&
                        is_string($element['id']) &&
                        $element['id']) {

                    $return .= ' id="' . $element['id'] . '"';
                }

                if (isset($element['class']) &&
                        is_string($element['class']) &&
                        $element['class']) {

                    $return .= ' class="' . $element['class'] . '"';
                }

                if (isset($element['attributes']) &&
                        is_array($element['attributes']) &&
                        count($element['attributes'])) {

                    foreach ($element['attributes'] as $attr => $value) {

                        $return .= ' ' . $attr . '="' . $value . '"';
                    }
                }

                if ((in_array($element['tag'], array(
                            'img',
                            'input',
                            'link',
                            'br'
                        )) &&
                        !in_array($element['tag'], array(
                            'script',
                            'textarea'
                        ))) ||
                        !isset($element['children']) ||
                        !is_array($element['children']) ||
                        !count($element['children'])) {

                    $return .= '/>';
                } else {

                    $return .= '>';

                    if (isset($element['text']) &&
                            is_string($element['text']) &&
                            strlen($element['text']) &&
                            !in_array($element['tag'], array(
                                'script'
                            ))) {

                        $return .= $element['text'];
                    }

                    if (isset($element['children']) &&
                            is_array($element['children']) &&
                            count($element['children']) &&
                            !in_array($element['tag'], array(
                                'textarea',
                                'iframe'
                            ))) {

                        $return .= self::GenerateFromArray($element['children']);
                    }

                    $return .= '</' . $element['tag'] . '>';
                }
            }
        }

        return $return;
    }

}
