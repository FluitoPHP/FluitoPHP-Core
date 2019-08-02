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

namespace FluitoPHP\Pagination;

/**
 * Pagination Class.
 *
 * This class is used to generate pagination.
 *
 * Variables:
 *      1. $config
 *      2. $url
 *      3. $totalPages
 *      4. $currentPage
 *      5. $type
 *      6. $linksToShow
 *      7. $appConfig
 *      8. $sepId
 *
 * Functions:
 *      1. __construct
 *      2. UpdateConfig
 *      3. Setup
 *      4. GenerateElement
 *      5. GenerateSeparator
 *      6. Generate
 *      7. Render
 *
 * @author Vipin Jain
 * @since  0.1
 */
class Pagination extends \FluitoPHP\Base\Base {

    /**
     * Used to store the configuration of the pagination.
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
            'class' => 'pagination',
            'id' => '',
            'attributes' => array(),
        ),
        'pager' => array(
            'tag' => 'li',
            'prefix' => '',
            'suffix' => '',
            'innerPrefix' => '',
            'innerSuffix' => '',
            'activeClass' => 'active',
            'disabledClass' => 'disabled',
            'class' => '',
            'id' => '',
            'attributes' => array(),
            'linkTag' => 'a',
            'linkActiveTag' => 'span',
            'linkDisabledTag' => 'span',
            'linkPrefix' => '',
            'linkSuffix' => '',
            'linkInnerPrefix' => '',
            'linkInnerSuffix' => '',
            'linkActiveInnerPrefix' => '',
            'linkActiveInnerSuffix' => '',
            'linkDisabledInnerPrefix' => '',
            'linkDisabledInnerSuffix' => '',
            'linkActiveClass' => '',
            'linkDisabledClass' => '',
            'linkClass' => '',
            'linkId' => '',
            'linkAttributes' => array(),
            'previousText' => '<span aria-hidden="true">&larr;</span> Previous',
            'nextText' => 'Next <span aria-hidden="true">&rarr;</span>',
            'previousClass' => 'previous',
            'nextClass' => 'next',
            'previousAttributes' => array(),
            'nextAttributes' => array()
        ),
        'separator' => array(
            'tag' => '',
            'prefix' => '',
            'suffix' => '',
            'innerPrefix' => '',
            'innerSuffix' => '',
            'class' => '',
            'id' => '',
            'attributes' => array(),
        ),
        'element' => array(
            'tag' => 'li',
            'prefix' => '',
            'suffix' => '',
            'innerPrefix' => '',
            'innerSuffix' => '',
            'activeClass' => 'active',
            'disabledClass' => 'disabled',
            'class' => '',
            'id' => '',
            'attributes' => array(),
            'linkTag' => 'a',
            'linkActiveTag' => 'span',
            'linkDisabledTag' => 'span',
            'linkPrefix' => '',
            'linkSuffix' => '',
            'linkInnerPrefix' => '',
            'linkInnerSuffix' => '',
            'linkActiveInnerPrefix' => '',
            'linkActiveInnerSuffix' => '',
            'linkDisabledInnerPrefix' => '',
            'linkDisabledInnerSuffix' => '',
            'linkActiveClass' => '',
            'linkDisabledClass' => '',
            'linkClass' => '',
            'linkId' => '',
            'linkAttributes' => array(),
            'previousText' => '<span aria-hidden="true">&laquo;</span>',
            'nextText' => '<span aria-hidden="true">&raquo;</span>',
            'previousClass' => '',
            'nextClass' => '',
            'previousAttributes' => array(
                'aria-label' => 'Previous'
            ),
            'nextAttributes' => array(
                'aria-label' => 'Next'
            )
        )
    );

    /**
     * Used to store the url of the pagination. Use $1 to replace with the page number.
     *
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    protected $url = null;

    /**
     * Used to store the total pages of the pagination.
     *
     * @var int
     * @author Vipin Jain
     * @since  0.1
     */
    protected $totalPages = 1;

    /**
     * Used to store the current page of the pagination.
     *
     * @var int
     * @author Vipin Jain
     * @since  0.1
     */
    protected $currentPage = 1;

    /**
     * Used to store the type, either pagination (0) or pager (1).
     *
     * @var int
     * @author Vipin Jain
     * @since  0.1
     */
    protected $type = 0;

    /**
     * Used to store the number of links to show at a time.
     *
     * @var int
     * @author Vipin Jain
     * @since  0.1
     */
    protected $linksToShow = 5;

    /**
     * Used to get application configuration of the class.
     *
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    static protected $appConfig = null;

    /**
     * Used to increment at separator generation for use in html id attribute.
     *
     * @var int
     * @author Vipin Jain
     * @since  0.1
     */
    protected $sepId = 0;

    /**
     * Constructor to initialize this class.
     *
     * @param array $config Provide the arguments in associative array. Provide parameters same as UpdateConfig method.
     * @author Vipin Jain
     * @since  0.1
     */
    function __construct($config = []) {

        if (self::$appConfig === null) {

            self::$appConfig = \FluitoPHP\FluitoPHP::GetInstance()->
                    GetConfig('PAGINATION');

            self::$appConfig = self::$appConfig ? self::$appConfig : [];

            $moduleConfig = \FluitoPHP\FluitoPHP::GetInstance()->
                    GetModuleConfig('PAGINATION');

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

        if (isset(self::$appConfig['pager'])) {

            self::$appConfig['pager'] = array_intersect_key(self::$appConfig['pager'], $this->
                    config['pager']);

            $this->
                    config['pager'] = array_replace_recursive($this->
                    config['pager'], self::$appConfig['pager']);
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

        $config = isset($config) &&
                is_array($config) ? $config : [];

        if (isset($config['container'])) {

            $config['container'] = array_intersect_key($config['container'], $this->
                    config['container']);

            $this->
                    config['container'] = array_replace_recursive($this->
                    config['container'], $config['container']);
        }

        if (isset($config['pager'])) {

            $config['pager'] = array_intersect_key($config['pager'], $this->
                    config['pager']);

            $this->
                    config['pager'] = array_replace_recursive($this->
                    config['pager'], $config['pager']);
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

        if (isset($config['url']) &&
                is_string($config['url']) &&
                strlen($config['url']) &&
                strpos($config['url'], '{$PAGE}') !== false) {

            $this->
                    url = $config['url'];
        }

        if (isset($config['totalPages']) &&
                intval($config['totalPages'])) {

            $this->
                    totalPages = intval($config['totalPages']);
        }

        if (isset($config['currentPage']) &&
                intval($config['currentPage'])) {

            $this->
                    currentPage = intval($config['currentPage']);
        } else if (!$this->
                currentPage) {

            $this->
                    currentPage = 1;
        }

        if (isset($config['type']) &&
                in_array($config['type'], [0, 1])) {

            $this->
                    type = $config['type'];
        } else if (!in_array($this->
                        type, [0, 1])) {

            $this->
                    type = 0;
        }

        if (isset($config['linksToShow']) &&
                intval($config['linksToShow'])) {

            $this->
                    linksToShow = intval($config['linksToShow']);
        } else if (!$this->
                linksToShow) {

            $this->
                    linksToShow = 5;
        }
    }

    /**
     * Used to update the configuration of the pagination.
     *
     * @param string $url Provide the url of the links. It must include '{$PAGE}' for page number replacement.
     * @param int $totalPages Provide the total number of pages.
     * @param int $currentPage Provide the current page number.
     * @param int $type Provide 0 to render pagination and 1 for pager.
     * @param int $linksToShow Provide the number of links to show in case of pagination.
     * @param array $config Provide the configuration options.
     * @author Vipin Jain
     * @since  0.1
     */
    public function UpdateConfig($url, $totalPages, $currentPage = null, $type = null, $linksToShow = null, $config = []) {

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

        if (is_string($url) &&
                strlen($url) &&
                strpos($url, '{$PAGE}') !== false) {

            $this->
                    url = $url;
        }

        if (intval($totalPages)) {

            $this->
                    totalPages = intval($totalPages);
        }

        if (intval($currentPage)) {

            $this->
                    currentPage = intval($currentPage);
        } else if (!$this->
                currentPage) {

            $this->
                    currentPage = 1;
        }

        if (in_array($type, [0, 1])) {

            $this->
                    type = $type;
        } else if (!in_array($this->
                        type, [0, 1])) {

            $this->
                    type = 0;
        }

        if (intval($linksToShow)) {

            $this->
                    linksToShow = intval($linksToShow);
        } else if (!$this->
                linksToShow) {

            $this->
                    linksToShow = 5;
        }
    }

    /**
     * Used to setup the pagination/pager.
     *
     * @param string $url Provide pagination url.
     * @param int $totalPages Provide the total pages of the pagination.
     * @param int $currentPage Provide the current page number.
     * @param int $type Provide true if the element is currently active.
     * @param int $linksToShow Provide additional parameters.
     * @return \FluitoPHP\Pagination\Pagination Returns self reference for chained calls.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Setup($url, $totalPages = 1, $currentPage = 1, $type = 0, $linksToShow = 5) {

        if (!is_string($url) ||
                !strlen($url)) {

            return false;
        }

        $url = htmlentities($url);

        return $this;
    }

    /**
     * Used to generate the html of the element.
     *
     * @param int $pageNumber Provide the page number for which the html to be generated. 0 means previous button and > $totalPages means next button.
     * @param bool $disableActive Provide true if needs to be disabled.
     * @return string Returns the generated html of the element.
     * @author Vipin Jain
     * @since  0.1
     */
    protected function GenerateElement($pageNumber, $disableActive = false) {

        $return = [];

        $cont = $this->
                config['element'];

        if ($this->
                type === 1) {

            $cont = $this->
                    config['pager'];
        }

        $setPageNumber = $pageNumber;
        $previous = false;
        $next = false;
        $linkText = $pageNumber;

        if ($setPageNumber === 0) {

            $previous = true;
            $setPageNumber = $disableActive ? 0 : $this->
                    currentPage - 1;
            $linkText = $cont['previousText'];
        } else if ($setPageNumber > $this->
                totalPages) {

            $next = true;
            $setPageNumber = $disableActive ? 0 : $this->
                    currentPage + 1;
            $linkText = $cont['nextText'];
        }

        $linkURL = str_replace('{$PAGE}', $setPageNumber, $this->
                url);

        if (is_string($cont['prefix']) &&
                strlen($cont['prefix'])) {

            $return[] = $cont['prefix'];
        }

        if (is_string($cont['tag']) &&
                strlen($cont['tag'])) {

            $cont['tag'] = htmlentities($cont['tag']);

            $index = count($return);
            $return[] = array('tag' => $cont['tag']);
            $return[$index]['children'] = [];

            $classStr = htmlentities((is_string($cont['class']) &&
                    strlen($cont['class']) ? $cont['class'] : '') .
                    ($disableActive ? ($previous ||
                    $next ? " {$cont['disabledClass']}" : " {$cont['activeClass']}") : "") .
                    ($previous ? " {$cont['previousClass']}" : "") .
                    ($next ? " {$cont['nextClass']}" : ""));

            $classStr = str_replace('.', "", $classStr);
            $classStr = str_replace('#', "", $classStr);
            $classStr = str_replace('"', "", $classStr);

            if (strlen($classStr)) {

                $return[$index]['class'] = trim($classStr);
            }

            if (is_string($cont['id']) &&
                    strlen($cont['id'])) {

                $idStr = htmlentities($cont['id']);
                $idStr = str_replace('.', "", $idStr);
                $idStr = str_replace('#', "", $idStr);
                $idStr = str_replace(' ', "", $idStr);
                $idStr = str_replace('"', "", $idStr);

                $return[$index]['id'] = $idStr . '-' . $pageNumber;
            }

            if (!is_array($cont['attributes'])) {

                $cont['attributes'] = [];
            }

            if ($previous) {

                $cont['attributes'] = array_replace($cont['attributes'], $cont['previousAttributes']);
            } else if ($next) {

                $cont['attributes'] = array_replace($cont['attributes'], $cont['nextAttributes']);
            }

            if (count($cont['attributes'])) {

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

        if (is_string($cont['linkPrefix']) &&
                strlen($cont['linkPrefix'])) {

            if (isset($return[$index]['tag'])) {

                $return[$index]['children'][] = $cont['linkPrefix'];
            } else {

                $return[] = $cont['linkPrefix'];
            }
        }

        if ($disableActive) {

            if ($previous || $next) {

                $linkTag = $cont['linkDisabledTag'];
            } else {

                $linkTag = $cont['linkActiveTag'];
            }
        } else {

            $linkTag = $cont['linkTag'];
        }

        $linkChild = [];

        if (is_string($linkTag) &&
                strlen($linkTag)) {

            $linkTag = htmlentities($linkTag);

            $linkChild['tag'] = $linkTag;

            $classStr = htmlentities((is_string($cont['linkClass']) &&
                    strlen($cont['linkClass']) ? $cont['linkClass'] : '') .
                    ($disableActive ? (!$previous &&
                    !$next ? " {$cont['linkActiveClass']}" : " {$cont['linkDisabledClass']}") : ""));

            $classStr = str_replace('.', "", $classStr);
            $classStr = str_replace('#', "", $classStr);
            $classStr = str_replace('"', "", $classStr);

            if (strlen($classStr)) {

                $linkChild['class'] = trim($classStr);
            }

            if (is_string($cont['linkId']) &&
                    strlen($cont['linkId'])) {

                $idStr = htmlentities($cont['linkId']);
                $idStr = str_replace('.', "", $idStr);
                $idStr = str_replace('#', "", $idStr);
                $idStr = str_replace(' ', "", $idStr);
                $idStr = str_replace('"', "", $idStr);

                $linkChild['id'] = $idStr . '-' . $pageNumber;
            }

            if (!is_array($cont['linkAttributes'])) {

                $cont['linkAttributes'] = [];
            }

            if (strtolower($linkTag) === 'a') {

                $cont['linkAttributes'] = array_replace($cont['linkAttributes'], ['href' => $linkURL]);
            }

            if (count($cont['linkAttributes'])) {

                $linkChild['attributes'] = [];

                foreach ($cont['linkAttributes'] as $attr => $value) {

                    $attr = str_replace('"', "", $attr);
                    $attr = str_replace('\'', "", $attr);
                    $attr = str_replace('=', "", $attr);

                    $value = str_replace('"', "'", $value);

                    $attr = htmlentities($attr);

                    $linkChild['attributes'][$attr] = htmlentities($value);
                }
            }
        }

        if (is_string($cont['linkInnerPrefix']) &&
                strlen($cont['linkInnerPrefix'])) {

            if (isset($linkChild['tag'])) {

                $linkChild['children'][] = $cont['linkInnerPrefix'];
            } else if (isset($return[$index]['tag'])) {

                $return[$index]['children'][] = $cont['linkInnerPrefix'];
            } else {

                $return[] = $cont['linkInnerPrefix'];
            }
        }

        if ($disableActive &&
                !$previous &&
                !$next &&
                is_string($cont['linkActiveInnerPrefix']) &&
                strlen($cont['linkActiveInnerPrefix'])) {

            if (isset($linkChild['tag'])) {

                $linkChild['children'][] = $cont['linkActiveInnerPrefix'];
            } else if (isset($return[$index]['tag'])) {

                $return[$index]['children'][] = $cont['linkActiveInnerPrefix'];
            } else {

                $return[] = $cont['linkActiveInnerPrefix'];
            }
        }

        if ($disableActive &&
                is_string($cont['linkDisabledInnerPrefix']) &&
                strlen($cont['linkDisabledInnerPrefix'])) {

            if (isset($linkChild['tag'])) {

                $linkChild['children'][] = $cont['linkDisabledInnerPrefix'];
            } else if (isset($return[$index]['tag'])) {

                $return[$index]['children'][] = $cont['linkDisabledInnerPrefix'];
            } else {

                $return[] = $cont['linkDisabledInnerPrefix'];
            }
        }

        if (isset($linkChild['tag'])) {

            $linkChild['children'][] = $linkText;
        } else if (isset($return[$index]['tag'])) {

            $return[$index]['children'][] = $linkText;
        } else {

            $return[] = $linkText;
        }

        if ($disableActive &&
                is_string($cont['linkDisabledInnerSuffix']) &&
                strlen($cont['linkDisabledInnerSuffix'])) {

            if (isset($linkChild['tag'])) {

                $linkChild['children'][] = $cont['linkDisabledInnerSuffix'];
            } else if (isset($return[$index]['tag'])) {

                $return[$index]['children'][] = $cont['linkDisabledInnerSuffix'];
            } else {

                $return[] = $cont['linkDisabledInnerSuffix'];
            }
        }

        if ($disableActive &&
                !$previous &&
                !$next &&
                is_string($cont['linkActiveInnerSuffix']) &&
                strlen($cont['linkActiveInnerSuffix'])) {

            if (isset($linkChild['tag'])) {

                $linkChild['children'][] = $cont['linkActiveInnerSuffix'];
            } else if (isset($return[$index]['tag'])) {

                $return[$index]['children'][] = $cont['linkActiveInnerSuffix'];
            } else {

                $return[] = $cont['linkActiveInnerSuffix'];
            }
        }

        if (is_string($cont['linkInnerSuffix']) &&
                strlen($cont['linkInnerSuffix'])) {

            if (isset($linkChild['tag'])) {

                $linkChild['children'][] = $cont['linkInnerSuffix'];
            } else if (isset($return[$index]['tag'])) {

                $return[$index]['children'][] = $cont['linkInnerSuffix'];
            } else {

                $return[] = $cont['linkInnerSuffix'];
            }
        }

        if (isset($return[$index]['tag'])) {

            $return[$index]['children'][] = $linkChild;
        } else {

            $return[] = $linkChild;
        }

        if (is_string($cont['linkSuffix']) &&
                strlen($cont['linkSuffix'])) {

            if (isset($return[$index]['tag'])) {

                $return[$index]['children'][] = $cont['linkSuffix'];
            } else {

                $return[] = $cont['linkSuffix'];
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

        return $return;
    }

    /**
     * Used to generate the html of the separator.
     *
     * @return string Returns the generated html of the separator.
     * @author Vipin Jain
     * @since  0.1
     */
    protected function GenerateSeparator() {

        $return = [];

        $cont = $this->
                config['separator'];

        if (is_string($cont['prefix']) &&
                strlen($cont['prefix'])) {

            $return[] = $cont['prefix'];
        }

        if (is_string($cont['tag']) &&
                strlen($cont['tag'])) {

            $cont['tag'] = htmlentities($cont['tag']);

            $index = count($return);
            $return[] = array('tag' => $cont['tag']);

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

                $return[$index]['id'] = $idStr . '-' . $this->
                        sepId;
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

        $this->
                sepId ++;

        return $return;
    }

    /**
     * Used to generate the html of the pagination/pager.
     *
     * @param string $event Provide the name of the filter to be applied on the elements array.
     * @return string Returns the generated html of the pagination/pager.
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

        if (!$this->
                url ||
                !$this->
                totalPages) {

            return $returnStr;
        }

        $cont = $this->
                config['container'];

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

        $disableActive = false;

        if ($this->
                currentPage === 1) {

            $disableActive = true;
        }

        if (isset($return[$index]['tag'])) {

            $return[$index]['children'] = array_merge($return[$index]['children'], $this->
                            GenerateElement(0, $disableActive));
        } else {

            $return = array_merge($return, $this->
                            GenerateElement(0, $disableActive));
        }

        if ($this->
                type === 0) {

            $start = 0;
            $end = $this->
                    totalPages;

            if ($this->
                    totalPages > $this->
                    linksToShow) {

                $start = $this->
                        currentPage - floor($this->
                                linksToShow / 2);

                if ($start < 0) {

                    $start = 0;
                }

                $end = $start + $this->
                        linksToShow;
            }

            for ($x = $start; $x < $end; $x++) {

                if ($x > $start) {

                    if (isset($return[$index]['tag'])) {

                        $return[$index]['children'][] = $this->
                                GenerateSeparator();
                    } else {

                        $return[] = $this->
                                GenerateSeparator();
                    }
                }

                $disableActive = false;

                if ($x + 1 == $this->
                        currentPage) {

                    $disableActive = true;
                }

                if (isset($return[$index]['tag'])) {

                    $return[$index]['children'] = array_merge($return[$index]['children'], $this->
                                    GenerateElement($x + 1, $disableActive));
                } else {

                    $return = array_merge($return, $this->
                                    GenerateElement($x + 1, $disableActive));
                }
            }
        }

        $disableActive = false;

        if ($this->
                currentPage === $this->
                totalPages) {

            $disableActive = true;
        }

        if (isset($return[$index]['tag'])) {

            $return[$index]['children'] = array_merge($return[$index]['children'], $this->
                            GenerateElement($this->
                                    totalPages + 1, $disableActive));
        } else {

            $return = array_merge($return, $this->
                            GenerateElement($this->
                                    totalPages + 1, $disableActive));
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

        $returnStr .= \FluitoPHP\HTMLGenerator\HTMLGenerator::GenerateFromArray($return);

        return $returnStr;
    }

    /**
     * Used to render the pagination/pager.
     *
     * @param string $event Provide the name of the filter to be applied on the elements array.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Render($event = null) {

        echo $this->
                Generate($event);
    }

}
