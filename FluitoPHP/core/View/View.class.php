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

namespace FluitoPHP\View;

/**
 * View Class.
 *
 * This class defines the view and how to call the views.
 *
 * Variables:
 *      1. $header
 *      2. $footer
 *      3. $view
 *      4. $errorHeader
 *      5. $errorFooter
 *      6. $errorView
 *      7. $title
 *      8. $errorTitle
 *
 * Functions:
 *      1. __construct
 *      2. Run
 *      3. HandleError
 *      4. SetHeader
 *      5. SetFooter
 *      6. SetView
 *      7. SetErrorHeader
 *      8. SetErrorFooter
 *      9. SetErrorView
 *      10. Title
 *      11. ErrorTitle
 *      12. RefreshView
 *      13. LoadTemplate
 *
 * @author Neha Jain
 * @since  0.1
 */
class View extends \FluitoPHP\Base\Base {

    /**
     * Used to store the header template.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    private $header = "header";

    /**
     * Used to store the footer template.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    private $footer = "footer";

    /**
     * Used to store the view template.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    private $view = null;

    /**
     * Used to store the error header template.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    private $errorHeader = "header";

    /**
     * Used to store the error footer template.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    private $errorFooter = "footer";

    /**
     * Used to store the error view template.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    private $errorView = null;

    /**
     * Used to store the html title.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    private $title = "FluitoPHP: Page";

    /**
     * Used to store the html error title.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    private $errorTitle = "FluitoPHP: Error";

    /**
     * Constructor to initialize this class.
     *
     * @author Neha Jain
     * @since  0.1
     */
    function __construct() {


        $this->
                RefreshView();
    }

    /**
     * Used to the load the view.
     *
     * @throws \FluitoPHP\HttpException\HttpException Throws exception if the view template is not found.
     * @author Neha Jain
     * @since  0.1
     */
    public function Run() {


        $this->
                RefreshView();

        if (!file_exists($this->
                        view) &&
                !file_exists(MODULE . DS . $this->
                                Request()->
                                GetModule() . VIEWS . DS . $this->
                        view . '.php')) {

            throw new \FluitoPHP\HttpException\HttpException("Error: View not found");
        }

        extract($this->Extract());

        if (file_exists($this->
                        header)) {

            require($this->
                    header);
        } else if (file_exists(MODULE . DS . $this->
                                Request()->
                                GetModule() . VIEWS . DS . $this->
                        header . '.php')) {

            require(MODULE . DS . $this->
                            Request()->
                            GetModule() . VIEWS . DS . $this->
                    header . '.php');
        }

        if (file_exists($this->
                        view)) {

            require($this->
                    view);
        } else {

            require(MODULE . DS . $this->
                            Request()->
                            GetModule() . VIEWS . DS . $this->
                    view . '.php');
        }

        if (file_exists($this->
                        footer)) {

            require($this->
                    footer);
        } else if (file_exists(MODULE . DS . $this->
                                Request()->
                                GetModule() . VIEWS . DS . $this->
                        footer . '.php')) {

            require(MODULE . DS . $this->
                            Request()->
                            GetModule() . VIEWS . DS . $this->
                    footer . '.php');
        }
    }

    /**
     * Used to the load the view.
     *
     * @throws \FluitoPHP\HttpException\HttpException Throws exception if the view template is not found.
     * @author Neha Jain
     * @since  0.1
     */
    public function HandleError() {

        if (!$this->
                errorView) {

            $this->
                    errorView = $this->
                            Request()->
                            GetErrorHandler() . 'Error';
        }

        if (file_exists($this->
                        errorHeader)) {

            require($this->
                    errorHeader);
        } else if (file_exists(MODULE . DS . $this->
                                Request()->
                                GetErrorModule() . VIEWS . DS . 'error' . DS . $this->
                        errorHeader . '.php')) {

            require(MODULE . DS . $this->
                            Request()->
                            GetErrorModule() . VIEWS . DS . 'error' . DS . $this->
                    errorHeader . '.php');
        } else if (file_exists(LIB . DS . 'ErrorHandler' . DS . 'view' . DS . $this->
                        errorHeader . '.php')) {

            require(LIB . DS . 'ErrorHandler' . DS . 'view' . DS . $this->
                    errorHeader . '.php');
        }

        if (file_exists($this->
                        errorView)) {

            require($this->
                    errorView);
        } else if (file_exists(MODULE . DS . $this->
                                Request()->
                                GetErrorModule() . VIEWS . DS . 'error' . DS . $this->
                        errorView . '.php')) {

            require(MODULE . DS . $this->
                            Request()->
                            GetErrorModule() . VIEWS . DS . 'error' . DS . $this->
                    errorView . '.php');
        } else if (file_exists(EXTENSIONS . DS . 'ErrorHandler' . DS . 'view' . DS . $this->
                        errorView . '.php')) {

            require(EXTENSIONS . DS . 'ErrorHandler' . DS . 'view' . DS . $this->
                    errorView . '.php');
        } else {

            require(LIB . DS . 'ErrorHandler' . DS . 'view' . DS . 'DefaultError.php');
        }

        if (file_exists($this->
                        errorFooter)) {

            require($this->
                    errorFooter);
        } else if (file_exists(MODULE . DS . $this->
                                Request()->
                                GetErrorModule() . VIEWS . DS . 'error' . DS . $this->
                        errorFooter . '.php')) {

            require(MODULE . DS . $this->
                            Request()->
                            GetErrorModule() . VIEWS . DS . 'error' . DS . $this->
                    errorFooter . '.php');
        } else if (file_exists(LIB . DS . 'ErrorHandler' . DS . 'view' . DS . $this->
                        errorFooter . '.php')) {

            require(LIB . DS . 'ErrorHandler' . DS . 'view' . DS . $this->
                    errorFooter . '.php');
        }
    }

    /**
     * Used to set the header template.
     *
     * @param string $header Provide the header template name or filepath.
     * @author Neha Jain
     * @since  0.1
     */
    public function SetHeader($header) {

        $this->
                header = $header;
    }

    /**
     * Used to set the footer template.
     *
     * @param string $footer Provide the footer template name or filepath.
     * @author Neha Jain
     * @since  0.1
     */
    public function SetFooter($footer) {

        $this->
                footer = $footer;
    }

    /**
     * Used to set the view template.
     *
     * @param string $view Provide the view template name or filepath.
     * @return boolean Returns true if the template is found and sets the template else false is returned.
     * @author Neha Jain
     * @since  0.1
     */
    public function SetView($view) {

        if (!file_exists($view) &&
                !file_exists(MODULE . DS . $this->
                                Request()->
                                GetModule() . VIEWS . DS . $view . '.php')) {

            return false;
        }

        $this->
                view = $view;

        return true;
    }

    /**
     * Used to set the error header template.
     *
     * @param string $errorHeader Provide the error header template name or filepath.
     * @author Neha Jain
     * @since  0.1
     */
    public function SetErrorHeader($errorHeader) {

        $this->
                errorHeader = $errorHeader;
    }

    /**
     * Used to set the error footer template.
     *
     * @param string $errorFooter Provide the error footer template name or filepath.
     * @author Neha Jain
     * @since  0.1
     */
    public function SetErrorFooter($errorFooter) {

        $this->
                errorFooter = $errorFooter;
    }

    /**
     * Used to set the error view template.
     *
     * @param string $errorView Provide the error view template name or filepath.
     * @return boolean Returns true if the template is found and sets the template else false is returned.
     * @author Neha Jain
     * @since  0.1
     */
    public function SetErrorView($errorView) {

        if (!file_exists($errorView) &&
                !file_exists(MODULE . DS . $this->
                                Request()->
                                GetErrorModule() . VIEWS . DS . 'error' . DS . $errorView . '.php')) {

            return false;
        }

        $this->
                errorView = $errorView;

        return true;
    }

    /**
     * Used to get and set html title.
     *
     * @param string $title Provide the new html title to set.
     * @return string Returns the old html title if the parameter is provided else current html title is returned.
     * @author Neha Jain
     * @since  0.1
     */
    public function Title($title = null) {

        if (is_string($title) &&
                $title) {

            $return = $this->
                    title;

            $this->
                    title = $title;

            return $return;
        }

        return $this->
                title;
    }

    /**
     * Used to get and set html title for error page.
     *
     * @param string $errorTitle Provide the new html error title to set.
     * @return string Returns the old html error title if the parameter is provided else current html error title is returned.
     * @author Neha Jain
     * @since  0.1
     */
    public function ErrorTitle($errorTitle = null) {

        if (is_string($errorTitle) &&
                $errorTitle) {

            $return = $this->
                    errorTitle;

            $this->
                    errorTitle = $errorTitle;

            return $return;
        }

        return $this->
                errorTitle;
    }

    /**
     * Used to refresh the view template.
     *
     * @author Neha Jain
     * @since  0.1
     */
    public function RefreshView() {

        $this->
                view = $this->
                        Request()->
                        GetController() . ucfirst($this->
                                Request()->
                                GetAction());
    }

    /**
     * Used to load Sub Template.
     *
     * @param string $template Provide the name of the template or full path to the template file.
     * @param string $extension Provide the extension of the template. This is not required when the full path is provided or the template is of php extension.
     * @return string Returns true if the template is loaded else returns false.
     * @author Neha Jain
     * @since  0.1
     */
    protected function LoadTemplate($template = '', $extension = 'php') {

        if (file_exists($template)) {

            require($template);
            return true;
        } else if (file_exists(MODULE . DS . $this->
                                Request()->
                                GetModule() . VIEWS . DS . $template . '.' . $extension)) {

            require(MODULE . DS . $this->
                            Request()->
                            GetModule() . VIEWS . DS . $template . '.' . $extension);
            return true;
        }

        return false;
    }

}
