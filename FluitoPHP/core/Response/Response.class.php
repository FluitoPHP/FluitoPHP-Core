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

namespace FluitoPHP\Response;

/**
 * Response Class.
 *
 * Helper class for response of the web application.
 *
 * Variables:
 *      1. $instance
 *      2. $view
 *      3. $httpCodes
 *
 * Functions:
 *      1. __construct
 *      2. GetInstance
 *      3. Setup
 *      4. Run
 *      5. HandleError
 *      6. SetHeader
 *      7. SetLocation
 *      8. SetRefresh
 *      9. SetHTTPCode
 *      10. View
 *      11. SetCookie
 *      12. SetContentType
 *
 * @author Neha Jain
 * @since  0.1
 */
class Response {

    /**
     * Used for storing Singleton instance.
     *
     * @var \FluitoPHP\Response\Response
     * @author Neha Jain
     * @since  0.1
     */
    static private $instance = null;

    /**
     * Used for storing View instance.
     *
     * @var \FluitoPHP\View\View
     * @author Neha Jain
     * @since  0.1
     */
    private $view = null;

    /**
     * Used for storing HTTP Status codes and their text message.
     *
     * @var array
     * @author Neha Jain
     * @since  0.1
     */
    private $httpCodes = array(
        "100" => "Continue",
        "101" => "Switching Protocols",
        "102" => "Processing",
        "200" => "OK",
        "201" => "Created",
        "202" => "Accepted",
        "203" => "Non-Authoritative Information",
        "204" => "No Content",
        "205" => "Reset Content",
        "206" => "Partial Content",
        "207" => "Multi-Status",
        "208" => "Already Reported",
        "226" => "IM Used",
        "300" => "Multiple Choices",
        "301" => "Moved Permanently",
        "302" => "Found",
        "303" => "See Other",
        "304" => "Not Modified",
        "305" => "Use Proxy",
        "306" => "Switch Proxy",
        "307" => "Temporary Redirect",
        "308" => "Permanent Redirect",
        "400" => "Bad Request",
        "401" => "Unauthorized",
        "402" => "Payment Required",
        "403" => "Forbidden",
        "404" => "Not Found",
        "405" => "Method Not Allowed",
        "406" => "Not Acceptable",
        "407" => "Proxy Authentication Required",
        "408" => "Request Timeout",
        "409" => "Conflict",
        "410" => "Gone",
        "411" => "Length Required",
        "412" => "Precondition Failed",
        "413" => "Payload Too Large",
        "414" => "URI Too Long",
        "415" => "Unsupported Media Type",
        "416" => "Range Not Satisfiable",
        "417" => "Expectation Failed",
        "418" => "I'm a teapot",
        "421" => "Misdirected Request",
        "422" => "Unprocessable Entity",
        "423" => "Locked",
        "424" => "Failed Dependency",
        "426" => "Upgrade Required",
        "428" => "Precondition Required",
        "429" => "Too Many Requests",
        "431" => "Request Header Fields Too Large",
        "451" => "Unavailable For Legal Reasons",
        "500" => "Internal Server Error",
        "501" => "Not Implemented",
        "502" => "Bad Gateway",
        "503" => "Service Unavailable",
        "504" => "Gateway Timeout",
        "505" => "HTTP Version Not Supported",
        "506" => "Variant Also Negotiates",
        "507" => "Insufficient Storage",
        "508" => "Loop Detected",
        "510" => "Not Extended",
        "511" => "Network Authentication Required"
    );

    /**
     * Private constructor to use this class as a singleton class.
     *
     * @author Neha Jain
     * @since  0.1
     */
    private function __construct() {

    }

    /**
     * Used to fetch the singleton instance object.
     *
     * @return \FluitoPHP\Response\Response
     * @author Neha Jain
     * @since  0.1
     */
    static public function GetInstance() {

        if (self::$instance === null) {

            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Used for Setting up Response object.
     *
     * @author Neha Jain
     * @since  0.1
     */
    public function Setup() {

        $this->
                view = new \FluitoPHP\View\View();
    }

    /**
     * Used to run the response.
     *
     * @author Neha Jain
     * @since  0.1
     */
    public function Run() {

        if (!$this->
                view) {

            $this->
                    Setup();
        }

        $this->
                view->
                Run();
    }

    /**
     * Used to run the response error handler.
     *
     * @author Neha Jain
     * @since  0.1
     */
    public function HandleError() {

        if (!$this->
                view) {

            $this->
                    Setup();
        }

        $this->
                view->
                HandleError();
    }

    /**
     * Used to set response headers.
     *
     * @param string $header Provide the header string.
     * @param bool $replace Provide false if you want to add the same header type instead of replacing.
     * @param int $http_response_code Provide the http response code.
     * @return bool Returns true on success and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function SetHeader($header, $replace = true, $http_response_code = null) {

        if (!$header) {

            return false;
        }

        if ($http_response_code !== null &&
                !is_int($http_response_code)) {

            $http_response_code = intval($http_response_code);
        }

        if ($http_response_code > 0) {

            header($header, $replace, $http_response_code);
        } else {

            header($header, $replace);
        }

        return true;
    }

    /**
     * Used to set response location headers.
     *
     * @param string $url Provide the url string.
     * @param bool $type Provide true if the type of redirect is 301 permanent.
     * @author Neha Jain
     * @since  0.1
     */
    public function SetLocation($url, $type = false) {

        if (!$type) {

            $this->
                    SetHeader("Location: {$url}");
        } else {

            $this->
                    SetHeader("Location: {$url}", true, 301);
        }

        exit;
    }

    /**
     * Used to set response refresh headers.
     *
     * @param string $url Provide the url string.
     * @param int $time Provide the time in seconds after which to refresh.
     * @return bool Returns true on success and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function SetRefresh($url, $time = 0) {

        $time = intval(abs($time));

        return $this->
                        SetHeader("refresh:{$time};url={$url}");
    }

    /**
     * Used to set HTTP status code.
     *
     * @param string $code Provide the HTTP code.
     * @param string $message Provide custom message for the code.
     * @return bool Returns true on success and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function SetHTTPCode($code, $message = "") {

        if (!$message &&
                isset($this->
                        httpCodes[$message])) {

            $message = $this->
                    httpCodes[$message];
        }

        if ($message) {

            $message = " {$message}";
        }

        return $this->
                        SetHeader("HTTP/1.0 {$code}{$message}", true, $code);
    }

    /**
     * Used to get current View object.
     *
     * @return \FluitoPHP\View\View Returns current View object.
     * @author Neha Jain
     * @since  0.1
     */
    public function View() {

        return $this->
                view;
    }

    /**
     * Used to set browser cookie.
     *
     * @param string $name Provide the cookie name.
     * @param string $value Provide the cookie value.
     * @param int $expire Provide the expiration time in seconds. Zero will make the cookie expire at the end of the session when the browser closes.
     * @param string $path Provide the path for the cookie where it can be accessed.
     * @param string $domain Provide the domain for which cookie has to be created.
     * @param bool $secure Provide if the cookie is only to be setup on secure connection.
     * @param bool $httponly Provide if the generated cookie can be access only by http channels and not by client side scripts.
     * @return bool Returns true if there is no output sent and the function successfully ran. though it does not guarantee that the client accepted the cookie.
     * @author Neha Jain
     * @since  0.1
     */
    public function SetCookie($name, $value = "", $expire = 0, $path = "", $domain = "", $secure = false, $httponly = false) {

        if ($expire) {

            $expire = time() + $expire;
        }

        return setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    /**
     * Used to set response content type headers.
     *
     * @param string $url Provide the content type string.
     * @author Neha Jain
     * @since  0.1
     */
    public function SetContentType($contentType) {

        return $this->
                        SetHeader("Content-Type: {$contentType}");
    }

}
