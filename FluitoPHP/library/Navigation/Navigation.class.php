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

namespace FluitoPHP\Navigation;

/**
 * Navigation Class.
 *
 * This class is used to generate navigation.
 *
 * Variables:
 *      1. $config
 *      2. $elements
 *      3. $parentNav
 *      4. $appConfig
 *
 * Functions:
 *      1. __construct
 *      2. UpdateConfig
 *      3. AddElement
 *      4. AddSeparator
 *      5. RemoveElement
 *      6. SetActive
 *      7. RemoveActive
 *      8. GetElement
 *      9. GetSubNav
 *      10. IsSubNav
 *      11. ContainsActive
 *      12. GenerateElement
 *      13. Generate
 *      14. Render
 *
 * @author Vipin Jain
 * @since  0.1
 */
class Navigation extends \FluitoPHP\Base\Base {

    /**
     * Used to store the configuration of the navigation.
     *
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    protected $config = array(
        'container' => array(
            'tag' => 'ul',
            'prefix' => '',
            'suffix' => '',
            'innerPrefix' => '',
            'innerSuffix' => '',
            'class' => 'nav navbar-nav',
            'id' => '',
            'attributes' => array(),
        ),
        'subContainer' => array(
            'tag' => 'ul',
            'prefix' => '',
            'suffix' => '',
            'innerPrefix' => '',
            'innerSuffix' => '',
            'class' => 'dropdown-menu',
            'id' => '',
            'attributes' => array(),
        ),
        'separator' => array(
            'tag' => 'li',
            'prefix' => '',
            'suffix' => '',
            'innerPrefix' => '',
            'innerSuffix' => '',
            'class' => 'divider',
            'id' => '',
            'attributes' => array(
                'role' => 'separator'
            ),
        ),
        'element' => array(
            'tag' => 'li',
            'prefix' => '',
            'suffix' => '',
            'innerPrefix' => '',
            'innerSuffix' => '',
            'activeClass' => 'active',
            'subNavClass' => 'dropdown',
            'subNavActiveClass' => '',
            'class' => '',
            'id' => '',
            'attributes' => array(),
            'linkTag' => 'a',
            'linkActiveTag' => 'a',
            'linkPrefix' => '',
            'linkSuffix' => '',
            'linkInnerPrefix' => '',
            'linkInnerSuffix' => '',
            'linkSubNavInnerPrefix' => '',
            'linkSubNavInnerSuffix' => ' <span class="caret"></span>',
            'linkActiveInnerPrefix' => '',
            'linkActiveInnerSuffix' => ' <span class="sr-only">(current)</span>',
            'linkSubNavActiveInnerPrefix' => '',
            'linkSubNavActiveInnerSuffix' => '',
            'linkActiveClass' => '',
            'linkSubNavActiveClass' => '',
            'linkSubNavClass' => 'dropdown-toggle',
            'linkClass' => '',
            'linkId' => '',
            'linkAttributes' => array(),
            'linkSubNavAttributes' => array(
                'data-toggle' => 'dropdown',
                'role' => 'button',
                'aria-haspopup' => 'true',
                'aria-expanded' => 'false',
            )
        ),
        'subElement' => array(
            'tag' => 'li',
            'prefix' => '',
            'suffix' => '',
            'innerPrefix' => '',
            'innerSuffix' => '',
            'activeClass' => 'active',
            'subNavClass' => 'dropdown',
            'subNavActiveClass' => '',
            'class' => '',
            'id' => '',
            'attributes' => array(),
            'linkTag' => 'a',
            'linkActiveTag' => 'a',
            'linkPrefix' => '',
            'linkSuffix' => '',
            'linkInnerPrefix' => '',
            'linkInnerSuffix' => '',
            'linkSubNavInnerPrefix' => '',
            'linkSubNavInnerSuffix' => ' <span class="caret"></span>',
            'linkActiveInnerPrefix' => '',
            'linkActiveInnerSuffix' => ' <span class="sr-only">(current)</span>',
            'linkSubNavActiveInnerPrefix' => '',
            'linkSubNavActiveInnerSuffix' => '',
            'linkActiveClass' => '',
            'linkSubNavActiveClass' => '',
            'linkSubNavClass' => 'dropdown-toggle',
            'linkClass' => '',
            'linkId' => '',
            'linkAttributes' => array(),
            'linkSubNavAttributes' => array(
                'data-toggle' => 'dropdown',
                'role' => 'button',
                'aria-haspopup' => 'true',
                'aria-expanded' => 'false',
            )
        )
    );

    /**
     * Used to store the elements of the navigation.
     *
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    protected $elements = array();

    /**
     * Used to store parent navigation if this instance is a sub-navigation.
     *
     * @var \FluitoPHP\Navigation\Navigation
     * @author Vipin Jain
     * @since  0.1
     */
    protected $parentNav = null;

    /**
     * Used to get application configuration of the class.
     *
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    static protected $appConfig = null;

    /**
     * Constructor to initialize this class.
     *
     * @param array $config Provide the configuration options.
     * @param \FluitoPHP\Navigation\Navigation $parentNav Provide parent navigation if this is a sub-navigation.
     * @author Vipin Jain
     * @since  0.1
     */
    function __construct($config = [], $parentNav = null) {

        if (self::$appConfig === null) {

            self::$appConfig = \FluitoPHP\FluitoPHP::GetInstance()->
                    GetConfig('NAVIGATION');

            self::$appConfig = self::$appConfig ? self::$appConfig : [];

            $moduleConfig = \FluitoPHP\FluitoPHP::GetInstance()->
                    GetModuleConfig('NAVIGATION');

            $moduleConfig = $moduleConfig ? $moduleConfig : [];

            self::$appConfig = array_replace_recursive(self::$appConfig, $moduleConfig);
        }

        if (isset(self::$appConfig['container'])) {

            self::$appConfig['container'] = array_intersect_key(self::$appConfig['container'], $this->
                    config['container']);

            $this->
                    config['container'] = array_replace_recursive($this->
                    config['container'], self::$appConfig['container']);
        }

        if (isset(self::$appConfig['subContainer'])) {

            self::$appConfig['subContainer'] = array_intersect_key(self::$appConfig['subContainer'], $this->
                    config['subContainer']);

            $this->
                    config['subContainer'] = array_replace_recursive($this->
                    config['subContainer'], self::$appConfig['subContainer']);
        }

        if (isset(self::$appConfig['separator'])) {

            self::$appConfig['separator'] = array_intersect_key(self::$appConfig['separator'], $this->
                    config['separator']);

            $this->
                    config['separator'] = array_replace_recursive($this->
                    config['separator'], self::$appConfig['separator']);
        }

        if (isset(self::$appConfig['element'])) {

            self::$appConfig['element'] = array_intersect_key(self::$appConfig['element'], $this->
                    config['element']);

            $this->
                    config['element'] = array_replace_recursive($this->
                    config['element'], self::$appConfig['element']);
        }

        $this->
                UpdateConfig($config, $parentNav);
    }

    /**
     * Used to update the configuration of the navigation.
     *
     * @param array $config Provide the configuration options.
     * @param \FluitoPHP\Navigation\Navigation $parentNav Provide parent navigation if this is a sub-navigation.
     * @author Vipin Jain
     * @since  0.1
     */
    public function UpdateConfig($config = [], $parentNav = null) {

        if (isset($config['container'])) {

            $config['container'] = array_intersect_key($config['container'], $this->
                    config['container']);

            $this->
                    config['container'] = array_replace_recursive($this->
                    config['container'], $config['container']);
        }

        if (isset($config['subContainer'])) {

            $config['subContainer'] = array_intersect_key($config['subContainer'], $this->
                    config['subContainer']);

            $this->
                    config['subContainer'] = array_replace_recursive($this->
                    config['subContainer'], $config['subContainer']);
        }

        if (isset($config['separator'])) {

            $config['separator'] = array_intersect_key($config['separator'], $this->
                    config['separator']);

            $this->
                    config['separator'] = array_replace_recursive($this->
                    config['separator'], $config['separator']);
        }

        if (isset($config['element'])) {

            $config['element'] = array_intersect_key($config['element'], $this->
                    config['element']);

            $this->
                    config['element'] = array_replace_recursive($this->
                    config['element'], $config['element']);
        }

        if ($parentNav instanceof \FluitoPHP\Navigation\Navigation) {

            $this->
                    parentNav = true;
        }
    }

    /**
     * Used to add an element to the navigation.
     *
     * @param string $name Provide element name/label.
     * @param string $url Provide the URL for the element.
     * @param string $title Provide the title for the element.
     * @param array $params Provide additional parameters.
     * @param int $position Provide the position number (valid positive integer including zero). Providing any other parameter will make it append to the last.
     * @return mixed Returns actual element position or returns false on failure.
     * @author Vipin Jain
     * @since  0.1
     */
    public function AddElement($name, $url, $title = null, $params = [], $position = null, $active = false) {

        if (!is_string($name) ||
                !strlen($name) ||
                !is_string($url)) {

            return false;
        }

        $name = htmlentities($name);
        $url = htmlentities($url);

        if (!is_string($title) ||
                !strlen($title)) {

            $title = $name;
        }

        if (!is_array($params)) {

            $params = [];
        }

        if (!$this->
                        IsSubNav()) {

            $params = array_intersect_key($params, $this->
                    config['element']);

            $params = array_replace_recursive($this->
                    config['element'], $params);
        } else {

            $params = array_intersect_key($params, $this->
                    config['subElement']);

            $params = array_replace_recursive($this->
                    config['subElement'], $params);
        }

        if ($active) {

            $this->
                    RemoveActive();
        }

        $element = array(
            'type' => 'element',
            'name' => $name,
            'url' => $url,
            'title' => $title,
            'active' => $active,
            'parameters' => $params,
            'subNav' => null
        );

        if (is_int($position) &&
                count($this->
                        elements) > $position) {

            array_splice($this->
                    elements, $position, 0, [$element]);
        } else {

            $this->
                    elements[] = $element;

            $position = count($this->
                            elements) - 1;
        }

        return $position;
    }

    /**
     * Used to add a separator element to the navigation.
     *
     * @param array $params Provide additional parameters.
     * @param int $position Provide the position number (valid positive integer including zero). Providing any other parameter will make it append to the last.
     * @return \FluitoPHP\Navigation\Navigation Returns self reference for chained calls.
     * @author Vipin Jain
     * @since  0.1
     */
    public function AddSeparator($params = [], $position = null) {

        if (!is_array($params)) {

            $params = [];
        }

        $params = array_intersect_key($params, $this->
                config['separator']);

        $params = array_replace_recursive($this->
                config['separator'], $params);

        $element = array(
            'type' => 'separator',
            'parameters' => $params
        );

        if (is_int($position) &&
                count($this->
                        elements) > $position) {

            array_splice($this->
                    elements, $position, 0, [$element]);
        } else {

            $this->
                    elements[] = $element;
        }

        return $this;
    }

    /**
     * Used to remove an element from the navigation.
     *
     * @param int $position Provide the position of the element to be reomved.
     * @return mixed Returns false if the element do not exists or returns the removed element.
     * @author Vipin Jain
     * @since  0.1
     */
    public function RemoveElement($position) {

        if (!is_int($position) ||
                count($this->
                        elements) > $position) {

            return false;
        }

        $return = array_splice($this->
                elements, $position, 1);

        return $return;
    }

    /**
     * Used to set an element of the navigation active.
     *
     * @param int $position Provide the position of the element.
     * @return bool Returns true if the element exists and no sub-navigation is set as active else false.
     * @author Vipin Jain
     * @since  0.1
     */
    public function SetActive($position) {

        if (!is_int($position) ||
                count($this->
                        elements) <= $position) {

            return false;
        }

        $this->
                RemoveActive();

        $this->
                elements[$position]['active'] = true;

        return true;
    }

    /**
     * Used to set the active element of the navigation as non active.
     *
     * @author Vipin Jain
     * @since  0.1
     */
    public function RemoveActive() {

        if ($this->
                        IsSubNav()) {

            $this->
                    parentNav->
                    RemoveActive();
        }

        if ($this->
                        ContainsActive() !== false) {

            foreach ($this->
            elements as $key => $element) {

                if ($element['active']) {

                    $this->
                            elements[$key]['active'] = false;
                } else if ($element['subNav'] &&
                                $element['subNav']->
                                ContainsActive() !== false) {

                    $element['subNav']->
                            RemoveActive();
                }
            }
        }
    }

    /**
     * Used to get the element(s) of the navigation.
     *
     * @param int $position Provide the position of the element.
     * @return array Returns the element if the position is found or returns all the elements if the position is null/false or equivalent.
     * @author Vipin Jain
     * @since  0.1
     */
    public function GetElement($position = null) {

        if (!$position) {

            return $this->
                    elements;
        }

        if (is_int($position) &&
                count($this->
                        elements) > $position) {

            return $this->
                    elements[$position];
        }

        return false;
    }

    /**
     * Used to get the sub-navigation object of the element.
     *
     * @param int $position Provide the position number of the element for which sub navigation has to be edited.
     * @return \FluitoPHP\Navigation\Navigation Returns the sub-navigation object.
     * @author Vipin Jain
     * @since  0.1
     */
    public function GetSubNav($position) {

        if (!(is_int($position) &&
                count($this->
                        elements) > $position)) {

            return false;
        }

        if ($this->
                elements[$position]['type'] === 'separator') {

            return false;
        }

        if ($this->
                elements[$position]['subNav'] === null) {

            $this->
                    elements[$position]['subNav'] = new Navigation($this->
                    config, $this);
        }

        return $this->
                elements[$position]['subNav'];
    }

    /**
     * Used to check if the element is a sub navigation.
     *
     * @return bool Returns true if this is a sub-navigation.
     * @author Vipin Jain
     * @since  0.1
     */
    public function IsSubNav() {

        return $this->
                parentNav !== null;
    }

    /**
     * Used to check if there is an active element in the navigation.
     *
     * @return mixed Returns key of active element if there is an active element in the navigation.
     * @author Vipin Jain
     * @since  0.1
     */
    public function ContainsActive() {

        foreach ($this->
        elements as $key => $element) {

            if ($element['active']) {

                return $key;
            } else if ($element['subNav'] &&
                            $element['subNav']->
                            ContainsActive() !== false) {

                return $key;
            }
        }

        return false;
    }

    /**
     * Used to generate the element and its subnav.
     *
     * @param array $element Provide the element array.
     * @return string Returns the generated element.
     * @author Vipin Jain
     * @since  0.1
     */
    protected function GenerateElement($element) {

        $return = [];

        if ($element['type'] === 'element') {

            $linkActive = $element['active'];

            $containsSubNav = $element['subNav'] !== null;

            $subNavActive = $element['subNav'] &&
                            $element['subNav']->
                            ContainsActive() !== false;

            if (is_string($element['parameters']['prefix']) &&
                    strlen($element['parameters']['prefix'])) {

                $return[] = $element['parameters']['prefix'];
            }

            if (is_string($element['parameters']['tag']) &&
                    strlen($element['parameters']['tag'])) {

                $element['parameters']['tag'] = htmlentities($element['parameters']['tag']);

                $index = count($return);
                $return[] = array('tag' => $element['parameters']['tag']);
                $return[$index]['children'] = [];

                $classStr = htmlentities((is_string($element['parameters']['class']) &&
                        strlen($element['parameters']['class']) ? $element['parameters']['class'] : '') .
                        ($linkActive ?
                        " {$element['parameters']['activeClass']}" : "") .
                        ($containsSubNav ?
                        " {$element['parameters']['subNavClass']}" : "") .
                        ($subNavActive ?
                        " {$element['parameters']['subNavActiveClass']}" : ""));

                $classStr = str_replace('.', "", $classStr);
                $classStr = str_replace('#', "", $classStr);
                $classStr = str_replace('"', "", $classStr);

                if (strlen($classStr)) {

                    $return[$index]['class'] = trim($classStr);
                }

                if (is_string($element['parameters']['id']) &&
                        strlen($element['parameters']['id'])) {

                    $idStr = htmlentities($element['parameters']['id']);
                    $idStr = str_replace('.', "", $idStr);
                    $idStr = str_replace('#', "", $idStr);
                    $idStr = str_replace(' ', "", $idStr);
                    $idStr = str_replace('"', "", $idStr);

                    $return[$index]['id'] = $idStr;
                }

                if (!is_array($element['parameters']['attributes'])) {

                    $element['parameters']['attributes'] = [];
                }

                $element['parameters']['attributes'] = array_replace($element['parameters']['attributes'], ['title' => $element['title']]);

                if (count($element['parameters']['attributes'])) {

                    $return[$index]['attributes'] = [];

                    foreach ($element['parameters']['attributes'] as $attr => $value) {

                        $attr = str_replace('"', "", $attr);
                        $attr = str_replace('\'', "", $attr);
                        $attr = str_replace('=', "", $attr);

                        $value = str_replace('"', "'", $value);

                        $attr = htmlentities($attr);

                        $return[$index]['attributes'][$attr] = htmlentities($value);
                    }
                }
            }

            if (is_string($element['parameters']['innerPrefix']) &&
                    strlen($element['parameters']['innerPrefix'])) {

                if (isset($return[$index]['tag'])) {

                    $return[$index]['children'][] = $element['parameters']['innerPrefix'];
                } else {

                    $return[] = $element['parameters']['innerPrefix'];
                }
            }

            if (is_string($element['parameters']['linkPrefix']) &&
                    strlen($element['parameters']['linkPrefix'])) {

                if (isset($return[$index]['tag'])) {

                    $return[$index]['children'][] = $element['parameters']['linkPrefix'];
                } else {

                    $return[] = $element['parameters']['linkPrefix'];
                }
            }

            if ($linkActive) {

                $linkTag = $element['parameters']['linkActiveTag'];
            } else {

                $linkTag = $element['parameters']['linkTag'];
            }

            $linkChild = [];

            if (is_string($linkTag) &&
                    strlen($linkTag)) {

                $linkTag = htmlentities($linkTag);

                $linkChild['tag'] = $linkTag;

                $classStr = htmlentities((is_string($element['parameters']['linkClass']) &&
                        strlen($element['parameters']['linkClass']) ? $element['parameters']['linkClass'] : '') .
                        ($linkActive ?
                        " {$element['parameters']['linkActiveClass']}" : "") .
                        ($containsSubNav ?
                        " {$element['parameters']['linkSubNavClass']}" : "") .
                        ($subNavActive ?
                        " {$element['parameters']['linkSubNavActiveClass']}" : ""));

                $classStr = str_replace('.', "", $classStr);
                $classStr = str_replace('#', "", $classStr);
                $classStr = str_replace('"', "", $classStr);

                if (strlen($classStr)) {

                    $linkChild['class'] = trim($classStr);
                }

                if (is_string($element['parameters']['linkId']) &&
                        strlen($element['parameters']['linkId'])) {

                    $idStr = htmlentities($element['parameters']['linkId']);
                    $idStr = str_replace('.', "", $idStr);
                    $idStr = str_replace('#', "", $idStr);
                    $idStr = str_replace(' ', "", $idStr);
                    $idStr = str_replace('"', "", $idStr);

                    $linkChild['id'] = $idStr;
                }

                if (!is_array($element['parameters']['linkAttributes'])) {

                    $element['parameters']['linkAttributes'] = [];
                }

                if ($containsSubNav) {

                    if (!is_array($element['parameters']['linkSubNavAttributes'])) {

                        $element['parameters']['linkSubNavAttributes'] = [];
                    }

                    $element['parameters']['linkAttributes'] = array_replace($element['parameters']['linkAttributes'], $element['parameters']['linkSubNavAttributes']);
                }

                if (strtolower($linkTag) === 'a') {

                    $element['parameters']['linkAttributes'] = array_replace($element['parameters']['linkAttributes'], ['href' => $element['url']]);
                }

                if (count($element['parameters']['linkAttributes'])) {

                    $linkChild['attributes'] = [];

                    foreach ($element['parameters']['linkAttributes'] as $attr => $value) {

                        $attr = str_replace('"', "", $attr);
                        $attr = str_replace('\'', "", $attr);
                        $attr = str_replace('=', "", $attr);

                        $value = str_replace('"', "'", $value);

                        $attr = htmlentities($attr);

                        $linkChild['attributes'][$attr] = htmlentities($value);
                    }
                }
            }

            if (is_string($element['parameters']['linkInnerPrefix']) &&
                    strlen($element['parameters']['linkInnerPrefix'])) {

                if (isset($linkChild['tag'])) {

                    $linkChild['children'][] = $element['parameters']['linkInnerPrefix'];
                } else if (isset($return[$index]['tag'])) {

                    $return[$index]['children'][] = $element['parameters']['linkInnerPrefix'];
                } else {

                    $return[] = $element['parameters']['linkInnerPrefix'];
                }
            }

            if ($containsSubNav &&
                    is_string($element['parameters']['linkSubNavInnerPrefix']) &&
                    strlen($element['parameters']['linkSubNavInnerPrefix'])) {

                if (isset($linkChild['tag'])) {

                    $linkChild['children'][] = $element['parameters']['linkSubNavInnerPrefix'];
                } else if (isset($return[$index]['tag'])) {

                    $return[$index]['children'][] = $element['parameters']['linkSubNavInnerPrefix'];
                } else {

                    $return[] = $element['parameters']['linkSubNavInnerPrefix'];
                }
            }

            if ($linkActive) {

                if (is_string($element['parameters']['linkActiveInnerPrefix']) &&
                        strlen($element['parameters']['linkActiveInnerPrefix'])) {

                    if (isset($linkChild['tag'])) {

                        $linkChild['children'][] = $element['parameters']['linkActiveInnerPrefix'];
                    } else if (isset($return[$index]['tag'])) {

                        $return[$index]['children'][] = $element['parameters']['linkActiveInnerPrefix'];
                    } else {

                        $return[] = $element['parameters']['linkActiveInnerPrefix'];
                    }
                }
            } else if ($subNavActive) {

                if (is_string($element['parameters']['linkSubNavActiveInnerPrefix']) &&
                        strlen($element['parameters']['linkSubNavActiveInnerPrefix'])) {

                    if (isset($linkChild['tag'])) {

                        $linkChild['children'][] = $element['parameters']['linkSubNavActiveInnerPrefix'];
                    } else if (isset($return[$index]['tag'])) {

                        $return[$index]['children'][] = $element['parameters']['linkSubNavActiveInnerPrefix'];
                    } else {

                        $return[] = $element['parameters']['linkSubNavActiveInnerPrefix'];
                    }
                }
            }

            $nameStr = htmlentities($element['name']);

            if (isset($linkChild['tag'])) {

                $linkChild['children'][] = $nameStr;
            } else if (isset($return[$index]['tag'])) {

                $return[$index]['children'][] = $nameStr;
            } else {

                $return[] = $nameStr;
            }

            if ($linkActive) {

                if (is_string($element['parameters']['linkActiveInnerSuffix']) &&
                        strlen($element['parameters']['linkActiveInnerSuffix'])) {

                    if (isset($linkChild['tag'])) {

                        $linkChild['children'][] = $element['parameters']['linkActiveInnerSuffix'];
                    } else if (isset($return[$index]['tag'])) {

                        $return[$index]['children'][] = $element['parameters']['linkActiveInnerSuffix'];
                    } else {

                        $return[] = $element['parameters']['linkActiveInnerSuffix'];
                    }
                }
            } else if ($subNavActive) {

                if (is_string($element['parameters']['linkSubNavActiveInnerSuffix']) &&
                        strlen($element['parameters']['linkSubNavActiveInnerSuffix'])) {

                    if (isset($linkChild['tag'])) {

                        $linkChild['children'][] = $element['parameters']['linkSubNavActiveInnerSuffix'];
                    } else if (isset($return[$index]['tag'])) {

                        $return[$index]['children'][] = $element['parameters']['linkSubNavActiveInnerSuffix'];
                    } else {

                        $return[] = $element['parameters']['linkSubNavActiveInnerSuffix'];
                    }
                }
            }

            if ($containsSubNav &&
                    is_string($element['parameters']['linkSubNavInnerSuffix']) &&
                    strlen($element['parameters']['linkSubNavInnerSuffix'])) {

                if (isset($linkChild['tag'])) {

                    $linkChild['children'][] = $element['parameters']['linkSubNavInnerSuffix'];
                } else if (isset($return[$index]['tag'])) {

                    $return[$index]['children'][] = $element['parameters']['linkSubNavInnerSuffix'];
                } else {

                    $return[] = $element['parameters']['linkSubNavInnerSuffix'];
                }
            }

            if (is_string($element['parameters']['linkInnerSuffix']) &&
                    strlen($element['parameters']['linkInnerSuffix'])) {

                if (isset($linkChild['tag'])) {

                    $linkChild['children'][] = $element['parameters']['linkInnerSuffix'];
                } else if (isset($return[$index]['tag'])) {

                    $return[$index]['children'][] = $element['parameters']['linkInnerSuffix'];
                } else {

                    $return[] = $element['parameters']['linkInnerSuffix'];
                }
            }

            if (isset($return[$index]['tag'])) {

                $return[$index]['children'][] = $linkChild;
            } else {

                $return[] = $linkChild;
            }

            if (is_string($element['parameters']['linkSuffix']) &&
                    strlen($element['parameters']['linkSuffix'])) {

                if (isset($return[$index]['tag'])) {

                    $return[$index]['children'][] = $element['parameters']['linkSuffix'];
                } else {

                    $return[] = $element['parameters']['linkSuffix'];
                }
            }

            if ($containsSubNav) {

                $subnav = $element['subNav']->
                        Generate();

                if (isset($return[$index]['tag'])) {

                    $return[$index]['children'] = array_merge($return[$index]['children'], $subnav);
                } else {

                    $return = array_merge($return, $subnav);
                }
            }


            if (is_string($element['parameters']['innerSuffix']) &&
                    strlen($element['parameters']['innerSuffix'])) {

                if (isset($return[$index]['tag'])) {

                    $return[$index]['children'][] = $element['parameters']['innerSuffix'];
                } else {

                    $return[] = $element['parameters']['innerSuffix'];
                }
            }

            if (is_string($element['parameters']['suffix']) &&
                    strlen($element['parameters']['suffix'])) {


                $return[] = $element['parameters']['suffix'];
            }
        } else if ($element['type'] === 'separator') {

            if (is_string($element['parameters']['prefix']) &&
                    strlen($element['parameters']['prefix'])) {

                $return[] = $element['parameters']['prefix'];
            }

            if (is_string($element['parameters']['tag']) &&
                    strlen($element['parameters']['tag'])) {

                $element['parameters']['tag'] = htmlentities($element['parameters']['tag']);

                $index = count($return);
                $return[] = array('tag' => $element['parameters']['tag']);

                if (is_string($element['parameters']['class']) &&
                        strlen($element['parameters']['class'])) {

                    $classStr = htmlentities($element['parameters']['class']);
                    $classStr = str_replace('.', "", $classStr);
                    $classStr = str_replace('#', "", $classStr);
                    $classStr = str_replace('"', "", $classStr);

                    $return[$index]['class'] = $classStr;
                }

                if (is_string($element['parameters']['id']) &&
                        strlen($element['parameters']['id'])) {

                    $idStr = htmlentities($element['parameters']['id']);
                    $idStr = str_replace('.', "", $idStr);
                    $idStr = str_replace('#', "", $idStr);
                    $idStr = str_replace(' ', "", $idStr);
                    $idStr = str_replace('"', "", $idStr);

                    $return[$index]['id'] = $idStr;
                }

                if (is_array($element['parameters']['attributes']) &&
                        count($element['parameters']['attributes'])) {

                    $return[$index]['attributes'] = [];

                    foreach ($element['parameters']['attributes'] as $attr => $value) {

                        $attr = str_replace('"', "", $attr);
                        $attr = str_replace('\'', "", $attr);
                        $attr = str_replace('=', "", $attr);

                        $value = str_replace('"', "'", $value);

                        $attr = htmlentities($attr);

                        $return[$index]['attributes'][$attr] = htmlentities($value);
                    }
                }
            }

            if (is_string($element['parameters']['innerPrefix']) &&
                    strlen($element['parameters']['innerPrefix'])) {

                if (isset($return[$index]['tag'])) {

                    $return[$index]['children'][] = $element['parameters']['innerPrefix'];
                } else {

                    $return[] = $element['parameters']['innerPrefix'];
                }
            }

            if (is_string($element['parameters']['innerSuffix']) &&
                    strlen($element['parameters']['innerSuffix'])) {

                if (isset($return[$index]['tag'])) {

                    $return[$index]['children'][] = $element['parameters']['innerSuffix'];
                } else {

                    $return[] = $element['parameters']['innerSuffix'];
                }
            }

            if (is_string($element['parameters']['suffix']) &&
                    strlen($element['parameters']['suffix'])) {

                $return[] = $element['parameters']['suffix'];
            }
        }

        return $return;
    }

    /**
     * Used to generate the html of the navigation.
     *
     * @param string $event Provide the name of the filter to be applied on the elements array.
     * @return mixed Returns the generated html of the navigation or generated array of the subnav.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Generate($event = null) {

        if (is_string($event) &&
                $event) {

            \FluitoPHP\Events\Events::GetInstance()->
                    Run($event, $this);
        }

        $return = [];
        $returnStr = '';

        $cont = $this->
                config['container'];

        if ($this->
                        IsSubNav()) {

            $cont = $this->
                    config['subContainer'];
        }

        if (is_string($cont['prefix']) &&
                strlen($cont['prefix'])) {

            $return[0] = $cont['prefix'];
        }

        if (is_string($cont['tag']) &&
                strlen($cont['tag'])) {

            $cont['tag'] = htmlentities($cont['tag']);

            $index = count($return);
            $return[] = array('tag' => $cont['tag']);
            $return[$index]['children'] = [];

            if (is_string($cont['class']) &&
                    strlen($cont['class'])) {

                $classStr = htmlentities($cont['class']);
                $classStr = str_replace('.', "", $classStr);
                $classStr = str_replace('#', "", $classStr);
                $classStr = str_replace('"', "", $classStr);

                $return[$index]['class'] = $classStr;
            }

            if (is_string($cont['id']) &&
                    strlen($cont['id'])) {

                $idStr = htmlentities($cont['id']);
                $idStr = str_replace('.', "", $idStr);
                $idStr = str_replace('#', "", $idStr);
                $idStr = str_replace(' ', "", $idStr);
                $idStr = str_replace('"', "", $idStr);

                $return[$index]['id'] = $idStr;
            }

            if (is_array($cont['attributes']) &&
                    count($cont['attributes'])) {

                $return[$index]['attributes'] = [];

                foreach ($cont['attributes'] as $attr => $value) {

                    $attr = str_replace('"', "", $attr);
                    $attr = str_replace('\'', "", $attr);
                    $attr = str_replace('=', "", $attr);

                    $value = str_replace('"', "'", $value);

                    $attr = htmlentities($attr);

                    $return[$index]['attributes'][$attr] = htmlentities($value);
                }
            }
        }

        if (is_string($cont['innerPrefix']) &&
                strlen($cont['innerPrefix'])) {

            if (isset($return[$index]['tag'])) {

                $return[$index]['children'][] = $cont['innerPrefix'];
            } else {

                $return[] = $cont['innerPrefix'];
            }
        }

        if (isset($return[$index]['tag'])) {

            foreach ($this->
            elements as $element) {

                $return[$index]['children'] = array_merge($return[$index]['children'], $this->
                                GenerateElement($element));
            }
        } else {

            foreach ($this->
            elements as $element) {

                $return = array_merge($return, $this->
                                GenerateElement($element));
            }
        }

        if (is_string($cont['innerSuffix']) &&
                strlen($cont['innerSuffix'])) {

            if (isset($return[$index]['tag'])) {

                $return[$index]['children'][] = $cont['innerSuffix'];
            } else {

                $return[] = $cont['innerSuffix'];
            }
        }

        if (is_string($cont['suffix']) &&
                strlen($cont['suffix'])) {

            $return[] = $cont['suffix'];
        }

        if (is_string($event) &&
                $event) {

            $return = \FluitoPHP\Filters\Filters::GetInstance()->
                    Run($event . '.HTMLArray', $return);
        }

        if ($this->
                        IsSubNav()) {

            return $return;
        }

        $returnStr .= \FluitoPHP\HTMLGenerator\HTMLGenerator::GenerateFromArray($return);

        return $returnStr;
    }

    /**
     * Used to render the navigation.
     * Sub-navigation will not get rendered.
     *
     * @param string $event Provide the name of the filter to be applied on the elements array.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Render($event = null) {

        if (!$this->
                        IsSubNav()) {

            echo $this->
                    Generate($event);
        }
    }

}
