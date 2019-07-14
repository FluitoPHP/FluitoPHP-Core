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

namespace FluitoPHP\Authentication;

/**
 * Authentication Class.
 *
 * This class is framework for authentication in your application.
 *
 * Variables:
 *      1. $instance
 *      2. $config
 *      3. $currentUser
 *      4. $database
 *
 * Functions:
 *      1. __construct
 *      2. GetInstance
 *      3. UpdateConfig
 *      4. GetPrefix
 *      5. GetConn
 *      6. Upgrade
 *      7. PasswordHash
 *      8. PasswordVerify
 *      9. Login
 *      10. SetUser
 *      11. GetUser
 *      12. GetLoggedInUser
 *      13. Logout
 *      14. CreatePasswordReset
 *      15. ValidatePasswordReset
 *      16. FulfillPasswordReset
 *      17. GetUserList
 *
 * @author Neha Jain
 * @since  0.1
 */
class Authentication {

    /**
     * Used for storing Singleton instance.
     *
     * @var \FluitoPHP\Authentication\Authentication
     * @author Neha Jain
     * @since  0.1
     */
    static private $instance = null;

    /**
     * Used to store the configuration.
     *
     * @var array
     * @author Neha Jain
     * @since  0.1
     */
    private $config = array(
        'id' => 'FluitoPHPAuth',
        'expire' => 2592000,
        'dbconn' => null,
        'prefix' => ''
    );

    /**
     * Used to store the current user object.
     *
     * @var \FluitoPHP\Authentication\User
     * @author Neha Jain
     * @since  0.1
     */
    private $currentUser = null;

    /**
     * Used to store the database instance.
     *
     * @var \FluitoPHP\Database\Database
     * @author Neha Jain
     * @since  0.1
     */
    private $database = null;

    /**
     * Used to make this class as a singleton class.
     *
     * @author Neha Jain
     * @since  0.1
     */
    private function __construct() {

        $this->
                database = \FluitoPHP\Database\Database::GetInstance();

        require_once( dirname(__FILE__) . DS . 'User.class.php' );

        $appConfig = \FluitoPHP\FluitoPHP::GetInstance()->
                GetConfig('AUTHENTICATION');

        $appConfig = $appConfig ? $appConfig : [];

        $moduleConfig = \FluitoPHP\FluitoPHP::GetInstance()->
                GetModuleConfig('AUTHENTICATION');

        $moduleConfig = $moduleConfig ? $moduleConfig : [];

        $appConfig = array_replace_recursive($appConfig, $moduleConfig);

        $this->
                UpdateConfig($appConfig);
    }

    /**
     * Used to fetch the Instance object globally.
     *
     * @return \FluitoPHP\Authentication\Authentication Returns this instance object.
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
     * Used to update the configuration of the authentication.
     *
     * @param array $config Provide the configuration options.
     * @author Neha Jain
     * @since  0.1
     */
    public function UpdateConfig($config = []) {

        $config = array_intersect_key($config, $this->
                config);

        $this->
                config = array_replace_recursive($this->
                config, $config);

        if (!is_int($this->
                        config['expire'])) {

            $this->
                    config['expire'] = intval($this->
                    config['expire']);
        }

        $this->
                Upgrade();

        \FluitoPHP\Events\Events::GetInstance()->
                Run('FluitoPHP.Authentication.GC', $this->
                        database->
                        Conn($this->
                                GetConn())->
                        Helper()->
                        Select($this->
                                GetPrefix() . 'usersalt', '*', array(
                            array(
                                'column' => "&DateAdd(last_access, {$this->
                                config['expire']}, S)",
                                'operator' => '<',
                                'rightcolumn' => '&CurrDTTM'
                            )
                        ))->
                        GetResults(), $this->
                        config);

        $this->
                database->
                Conn($this->
                        GetConn())->
                Helper()->
                Delete($this->
                        GetPrefix() . 'usersalt', array(
                    array(
                        'column' => "&DateAdd(last_access, {$this->
                        config['expire']}, S)",
                        'operator' => '<',
                        'rightcolumn' => '&CurrDTTM'
                    )
                ))->
                Query();
    }

    /**
     * Used to get table prefix of authentication.
     *
     * @author Neha Jain
     * @since  0.1
     */
    public function GetPrefix() {

        return $this->
                config['prefix'];
    }

    /**
     * Used to get database connection of authentication.
     *
     * @author Neha Jain
     * @since  0.1
     */
    public function GetConn() {

        return $this->
                config['dbconn'];
    }

    /**
     * Used to create/upgrade the base tables for authentication.
     *
     * @author Neha Jain
     * @since  0.1
     */
    public function Upgrade() {

        if (!$this->
                        database->
                        Conn($this->
                                GetConn())->
                        Helper()->
                        CheckTable($this->
                                GetPrefix() . 'userdefn')->
                        GetVar()) {

            $this->
                    database->
                    Conn($this->
                            GetConn())->
                    Helper()->
                    CreateTable($this->
                            GetPrefix() . 'userdefn', array(
                        'user_id' => array(
                            'type' => 'VARCHAR',
                            'length' => 32,
                            'isnull' => false,
                            'primary' => true
                        ),
                        'user_login' => array(
                            'type' => 'VARCHAR',
                            'length' => 128,
                            'isnull' => false
                        ),
                        'created_on' => array(
                            'type' => 'DATETIME',
                            'isnull' => false
                        ),
                        'created_by' => array(
                            'type' => 'VARCHAR',
                            'length' => 32,
                            'isnull' => false
                        )
                    ))->
                    Query();

            $this->
                    database->
                    Conn($this->
                            GetConn())->
                    Helper()->
                    CreateTable($this->
                            GetPrefix() . 'userdata', array(
                        'user_id' => array(
                            'type' => 'VARCHAR',
                            'length' => 32,
                            'isnull' => false,
                            'primary' => true
                        ),
                        'eff_dttm' => array(
                            'type' => 'DATETIME',
                            'isnull' => false,
                            'primary' => true
                        ),
                        'email' => array(
                            'type' => 'VARCHAR',
                            'length' => 256,
                            'isnull' => false
                        ),
                        'first_name' => array(
                            'type' => 'VARCHAR',
                            'length' => 256,
                            'isnull' => false
                        ),
                        'last_name' => array(
                            'type' => 'VARCHAR',
                            'length' => 256,
                            'isnull' => false
                        ),
                        'middle_name' => array(
                            'type' => 'VARCHAR',
                            'length' => 256,
                            'isnull' => true
                        ),
                        'dob' => array(
                            'type' => 'DATE',
                            'isnull' => true
                        ),
                        'doa' => array(
                            'type' => 'DATE',
                            'isnull' => true
                        ),
                        'active' => array(
                            'type' => 'VARCHAR',
                            'length' => 1,
                            'isnull' => false
                        ),
                        'updated_on' => array(
                            'type' => 'DATETIME',
                            'isnull' => false
                        ),
                        'updated_by' => array(
                            'type' => 'VARCHAR',
                            'length' => 32,
                            'isnull' => false
                        )
                            ), array(
                        'fk_userdefn_userdata' => array(
                            'indextype' => 'fk',
                            'columns' => array(
                                'user_id'
                            ),
                            'referencetable' => $this->
                            GetPrefix() . 'userdefn',
                            'referencecolumns' => array(
                                'user_id'
                            )
                        )
                    ))->
                    Query();

            $this->
                    database->
                    Conn($this->
                            GetConn())->
                    Helper()->
                    CreateTable($this->
                            GetPrefix() . 'userpassword', array(
                        'user_id' => array(
                            'type' => 'VARCHAR',
                            'length' => 32,
                            'isnull' => false,
                            'primary' => true
                        ),
                        'eff_dttm' => array(
                            'type' => 'DATETIME',
                            'isnull' => false,
                            'primary' => true
                        ),
                        'password' => array(
                            'type' => 'VARCHAR',
                            'length' => 256,
                            'isnull' => false
                        ),
                        'updated_on' => array(
                            'type' => 'DATETIME',
                            'isnull' => false
                        ),
                        'updated_by' => array(
                            'type' => 'VARCHAR',
                            'length' => 32,
                            'isnull' => false
                        )
                            ), array(
                        'fk_userdefn_userpassword' => array(
                            'indextype' => 'fk',
                            'columns' => array(
                                'user_id'
                            ),
                            'referencetable' => $this->
                            GetPrefix() . 'userdefn',
                            'referencecolumns' => array(
                                'user_id'
                            )
                        )
                    ))->
                    Query();

            $this->
                    database->
                    Conn($this->
                            GetConn())->
                    Helper()->
                    CreateTable($this->
                            GetPrefix() . 'usersalt', array(
                        'salt_id' => array(
                            'type' => 'VARCHAR',
                            'length' => 32,
                            'isnull' => false,
                            'primary' => true
                        ),
                        'user_id' => array(
                            'type' => 'VARCHAR',
                            'length' => 32,
                            'isnull' => false
                        ),
                        'salt' => array(
                            'type' => 'VARCHAR',
                            'length' => 256,
                            'isnull' => false
                        ),
                        'device_name' => array(
                            'type' => 'VARCHAR',
                            'length' => 500,
                            'isnull' => false
                        ),
                        'ip_address' => array(
                            'type' => 'VARCHAR',
                            'length' => 15,
                            'isnull' => false
                        ),
                        'logged_on' => array(
                            'type' => 'DATETIME',
                            'isnull' => false
                        ),
                        'last_access' => array(
                            'type' => 'DATETIME',
                            'isnull' => false
                        )
                            ), array(
                        'fk_userdefn_usersalt' => array(
                            'indextype' => 'fk',
                            'columns' => array(
                                'user_id'
                            ),
                            'referencetable' => $this->
                            GetPrefix() . 'userdefn',
                            'referencecolumns' => array(
                                'user_id'
                            )
                        )
                    ))->
                    Query();

            $this->
                    database->
                    Conn($this->
                            GetConn())->
                    Helper()->
                    CreateTable($this->
                            GetPrefix() . 'userresetsalt', array(
                        'salt_id' => array(
                            'type' => 'VARCHAR',
                            'length' => 32,
                            'isnull' => false,
                            'primary' => true
                        ),
                        'user_id' => array(
                            'type' => 'VARCHAR',
                            'length' => 32,
                            'isnull' => false
                        ),
                        'salt' => array(
                            'type' => 'VARCHAR',
                            'length' => 256,
                            'isnull' => false
                        )
                            ), array(
                        'fk_userdefn_userresetsalt' => array(
                            'indextype' => 'fk',
                            'columns' => array(
                                'user_id'
                            ),
                            'referencetable' => $this->
                            GetPrefix() . 'userdefn',
                            'referencecolumns' => array(
                                'user_id'
                            )
                        )
                    ))->
                    Query();

            $user = new \FluitoPHP\Authentication\User('');

            $user->
                    SetUserData(array(
                        'user_login' => 'administrator',
                        'email' => 'administrator@test.local',
                        'first_name' => 'System',
                        'last_name' => 'Administrator'
                            ), true);

            $user->
                    UpdatePassword('administrator@password');

            \FluitoPHP\Events\Events::GetInstance()->
                    Run('FluitoPHP.Authentication.FirstRun', $this->
                            config);
        }

        \FluitoPHP\Events\Events::GetInstance()->
                Run('FluitoPHP.Authentication.Upgrade', $this->
                        config);
    }

    /**
     * Used to hash the password for safe storage.
     * Note: This method truncates the input password to 72 characters length.
     *
     * @param string $password Provide the password to be hashed.
     * @param bool $isSalt Provide true if this is a hashing of salt.
     * @return string Returns the hashed password.
     * @author Neha Jain
     * @since  0.1
     */
    public function PasswordHash($password, $isSalt = false) {

        if (strlen($password) > 72) {

            $password = substr($password, 0, 72);
        }

        $hashedPassword = \FluitoPHP\Filters\Filters::GetInstance()->
                Run('FluitoPHP.Authentication.HashPassword', $password, $isSalt);

        if ($hashedPassword === $password) {

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        }

        return $hashedPassword;
    }

    /**
     * Used to hash the password for safe storage.
     * Note: This method truncates the input password to 72 characters length.
     *
     * @param string $password Provide the password to be verified.
     * @param string $hashedPassword Provide the password hash for verification.
     * @param bool $isSalt Provide true if this is a verification of salt.
     * @return string Returns true if the password matches the hash else false instead.
     * @author Neha Jain
     * @since  0.1
     */
    public function PasswordVerify($password, $hashedPassword, $isSalt = false) {

        if (strlen($password) > 72) {

            $password = substr($password, 0, 72);
        }

        $result = \FluitoPHP\Filters\Filters::GetInstance()->
                Run('FluitoPHP.Authentication.VerifyPassword', 'no_action', $password, $hashedPassword, $isSalt);

        if ($result === 'no_action') {

            $result = password_verify($password, $hashedPassword);
        }

        return $result;
    }

    /**
     * Used to login the user with the password.
     *
     * @param string $user_login Provide the user login or user id.
     * @param string $password Provide the user password.
     * @param bool $remember Provide true if needs to be remembered.
     * @return mixed Returns true on success else returns the error message.
     * @author Neha Jain
     * @since  0.1
     */
    public function Login($user_login, $password, $remember = false) {

        $user = new \FluitoPHP\Authentication\User($user_login);

        usleep(500000); //sleep for .5 sec to prevent bruteforce attacks.

        if (!$user ||
                !$user->
                        GetUserID()) {

            return new \FluitoPHP\Error\Error('Please enter user login or id.', 'no_login');
        }

        if (!is_string($password) ||
                !strlen($password)) {

            return new \FluitoPHP\Error\Error('Please enter user password.', 'no_password');
        }

        if (!$user->
                        ValidatePassword($password)) {

            return new \FluitoPHP\Error\Error('The username or password is incorrect.', 'invalid_login');
        }

        $filterReturn = \FluitoPHP\Filters\Filters::GetInstance()->
                Run('FluitoPHP.Authentication.Login', true, $user, $user_login);

        if (\FluitoPHP\Error\Error::IsError($filterReturn)) {

            return $filterReturn;
        }

        return $this->
                        SetUser($user_login, $remember);
    }

    /**
     * Used to generate the login salt and store it in the database and cookie/session.
     * Note: This method do not require a password.
     *
     * @param string $user_login Provide the user login or user id.
     * @param bool $remember Provide true if needs to be remembered.
     * @return mixed Returns true on success else returns the error message.
     * @author Neha Jain
     * @since  0.1
     */
    public function SetUser($user_login = null, $remember = false) {

        if (!is_string($user_login) ||
                !strlen($user_login)) {

            return new \FluitoPHP\Error\Error('Please enter user login or id.', 'no_login');
        }

        $user = new \FluitoPHP\Authentication\User($user_login);

        if (!$user ||
                !$user->
                        GetUserID()) {

            return new \FluitoPHP\Error\Error('The username or password is incorrect.', 'invalid_login');
        }

        if ($this->
                currentUser &&
                        $this->
                        currentUser->
                        GetUserID() === $user->
                        GetUserID()) {

            return true;
        }

        $filterReturn = \FluitoPHP\Filters\Filters::GetInstance()->
                Run('FluitoPHP.Authentication.SetUser', true, $user);

        if (\FluitoPHP\Error\Error::IsError($filterReturn)) {

            return $filterReturn;
        }

        $salt_id = uniqid(time() . '-');
        $salt = uniqid(time() . '-', true);

        $this->
                database->
                Conn($this->
                        GetConn())->
                Helper()->
                Insert($this->
                        GetPrefix() . 'usersalt', array(
                    'salt_id' => $salt_id,
                    'user_id' => $user->
                    GetUserID(),
                    'salt' => $this->
                    PasswordHash($salt, true),
                    'device_name' => strlen(\FluitoPHP\Request\Request::GetInstance()->
                            Server('HTTP_USER_AGENT')) > 500 ? substr(\FluitoPHP\Request\Request::GetInstance()->
                                    Server('HTTP_USER_AGENT'), 0, 500) : \FluitoPHP\Request\Request::GetInstance()->
                            Server('HTTP_USER_AGENT'),
                    'ip_address' => \FluitoPHP\Request\Request::GetInstance()->
                    Server('REMOTE_ADDR'),
                    'logged_on' => array('function' => '&CurrDTTM'),
                    'last_access' => array('function' => '&CurrDTTM')
                        )
                )->
                Query();

        $cookieArray = array(
            'salt_id' => $salt_id,
            'salt' => $salt,
        );

        $cookieValue = json_encode($cookieArray);

        $this->
                Logout();

        if ($remember === true) {

            \FluitoPHP\Response\Response::GetInstance()->
                    SetCookie($this->
                            config['id'], $cookieValue, $this->
                            config['expire'], '/', '', false, true);
        } else {

            \FluitoPHP\Response\Response::GetInstance()->
                    SetCookie($this->
                            config['id'], $cookieValue, 0, '/', '', false, true);
        }

        return true;
    }

    /**
     * Used to get logged in user else system user.
     *
     * @param bool $noReset Provide true if you want to just check the login and not update the access time. Default: false
     * @return \FluitoPHP\Authentication\User Returns user if logged in else returns system user.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetUser($noReset = false) {

        if ($this->
                currentUser &&
                        $this->
                        currentUser->
                        GetUserID()) {

            return $this->
                    currentUser;
        }

        $user = null;

        $cookie = json_decode(\FluitoPHP\Request\Request::GetInstance()->
                        Cookie($this->
                                config['id']), true);

        if (isset($cookie['salt_id'])) {

            $saltRow = $this->
                    database->
                    Conn($this->
                            GetConn())->
                    Helper()->
                    Select($this->
                            GetPrefix() . 'usersalt', array(
                        'user_id',
                        'salt'
                            ), array(
                        array(
                            'column' => 'salt_id',
                            'operator' => '=',
                            'rightvalue' => $cookie['salt_id']
                        )
                    ))->
                    GetRow();

            if ($saltRow) {

                if ($this->
                                PasswordVerify($cookie['salt'], $saltRow['salt'])) {

                    $user = new \FluitoPHP\Authentication\User($saltRow['user_id']);

                    if ($user &&
                                    $user->
                                    GetUserID()) {

                        $this->
                                currentUser = $user;

                        if (!$noReset) {

                            $this->
                                    database->
                                    Conn($this->
                                            GetConn())->
                                    Helper()->
                                    Update($this->
                                            GetPrefix() . 'usersalt', array(
                                        'last_access' => array('function' => '&CurrDTTM')
                                            ),
                                            array(
                                                array(
                                                    'column' => 'salt_id',
                                                    'operator' => '=',
                                                    'rightvalue' => $cookie['salt_id']
                                                )
                                            )
                                    )->
                                    Query();
                        }
                    }
                }
            }
        }

        if (!$this->
                currentUser ||
                !$this->
                        currentUser->
                        GetUserID()) {

            $this->
                    currentUser = new \FluitoPHP\Authentication\User('system');
        }

        return $this->
                currentUser;
    }

    /**
     * Used to get logged in user.
     *
     * @param bool $noReset Provide true if you want to just check the login and not update the access time. Default: false
     * @return \FluitoPHP\Authentication\User Returns user if logged in else returns false.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetLoggedInUser($noReset = false) {

        if ($this->
                        GetUser($noReset)->
                        GetUserID() === 'system') {

            return false;
        }

        return $this->
                currentUser;
    }

    /**
     * Used to logout a user.
     *
     * @author Neha Jain
     * @since  0.1
     */
    public function Logout() {

        $cookie = json_decode(\FluitoPHP\Request\Request::GetInstance()->
                        Cookie($this->
                                config['id']), true);

        if (isset($cookie['salt_id'])) {

            $this->
                    database->
                    Conn($this->
                            GetConn())->
                    Helper()->
                    Delete($this->
                            GetPrefix() . 'usersalt', array(
                        array(
                            'column' => 'salt_id',
                            'operator' => '=',
                            'rightvalue' => $cookie['salt_id']
                        )
                    ))->
                    Query();

            \FluitoPHP\Response\Response::GetInstance()->
                    SetCookie($this->
                            config['id'], '', 0, '', '', false, true);
        }

        $this->
                currentUser = null;
    }

    /**
     * Used to disable an existing user.
     *
     * @param string $user_login Provide the user id/login.
     * @return mixed Returns true if disabled else \FluitoPHP\Error\Error will be returned.
     * @author Neha Jain
     * @since  0.1
     */
    public function DisableUser($user_login) {

        if (!is_string($user_login) ||
                !strlen($user_login)) {

            return new \FluitoPHP\Error\Error('Please enter user id/login.', 'disable_no_login');
        }

        $user = new \FluitoPHP\Authentication\User($user_login);

        if (!$user ||
                !$user->
                        GetUserID()) {

            return new \FluitoPHP\Error\Error('User do not exists', 'disable_not_exist');
        }

        $updateArray = $this->
                database->
                Conn($this->
                        GetConn())->
                Helper()->
                Select($this->
                        GetPrefix() . 'userdata A', '*', array(
                    array(
                        'column' => 'A.user_id',
                        'operator' => '=',
                        'rightvalue' => $user_id
                    ),
                    array(
                        'column' => 'A.eff_dttm',
                        'operator' => '=',
                        'rightsubquery' => $this->
                        database->
                        Conn($this->
                                GetConn())->
                        Helper()->
                        Select($this->
                                GetPrefix() . 'userdata A_ED', '&MAX(A_ED.eff_dttm)', array(
                            array(
                                'column' => 'A_ED.user_id',
                                'operator' => '=',
                                'rightcolumn' => 'A.user_id'
                            ),
                            array(
                                'column' => 'A_ED.eff_dttm',
                                'operator' => '<=',
                                'rightcolumn' => '&CurrDTTM'
                            )
                        ))
                    )
                        )
                )->
                GetRow();

        $currentUser = $this->
                GetUser();

        if (!$currentUser ||
                !$currentUser->
                        GetUserID()) {

            $currentUser = new \FluitoPHP\Authentication\User('system');
        }

        $updateArray['eff_dttm'] = $this->
                database->
                Conn($this->
                        GetConn())->
                Helper()->
                Select($this->
                        GetPrefix() . 'userdefn', '&CurrDTTM', array(
                    array(
                        'column' => 'user_id',
                        'operator' => '=',
                        'rightvalue' => $user_id
                    )
                        )
                )->
                GetVar();
        $updateArray['updated_on'] = array('function' => '&CurrDTTM');
        $updateArray['updated_by'] = $currentUser->
                GetUserID();
        $updateArray['active'] = 'n';

        $this->
                database->
                Conn($this->
                        GetConn())->
                Helper()->
                Update($this->
                        GetPrefix() . 'userdata', $updateArray, array(
                    array(
                        'column' => 'user_id',
                        'operator' => '=',
                        'rightvalue' => $user_id
                    )
                        )
                )->
                Query();

        if ($this->
                        database->
                        Conn($this->
                                GetConn())->
                        GetErrorCode()) {

            return new \FluitoPHP\Error\Error($this->
                            database->
                            Conn($this->
                                    GetConn())->
                            GetError(), 'disable_db_error');
        }

        $filterReturn = \FluitoPHP\Filters\Filters::GetInstance()->
                Run('FluitoPHP.Authentication.DisableUser', true, $user_id, $user_login);

        if (\FluitoPHP\Error\Error::IsError($filterReturn)) {

            $this->
                    database->
                    Conn($this->
                            GetConn())->
                    Helper()->
                    Delete($this->
                            GetPrefix() . 'userdata', array(
                        array(
                            'column' => 'user_id',
                            'operator' => '=',
                            'rightvalue' => $user_id
                        ),
                        array(
                            'column' => 'eff_dttm',
                            'operator' => '=',
                            'rightsubquery' => $updateArray['eff_dttm']
                        )
                            )
                    )->
                    Query();

            return $filterReturn;
        }

        return $user_id;
    }

    /**
     * Used to generate a reset password request of a user.
     *
     * @param string $user_login Provide the user id/login.
     * @return mixed Returns reset password salt_id and salt in an associative array if generated else \FluitoPHP\Error\Error is returned.
     * @author Neha Jain
     * @since  0.1
     */
    public function CreatePasswordReset($user_login) {

        $user = false;

        if (is_string($user_login) &&
                strlen($user_login)) {

            $user = new \FluitoPHP\Authentication\User($user_login);
        }

        if (!$user ||
                !$user->
                        GetUserID()) {

            return new \FluitoPHP\Error\Error('No user id/login has been provided.', 'create_password_reset_no_user');
        }

        $saltReturn = array(
            'salt_id' => uniqid(time() . '-'),
            'salt' => uniqid(time() . '-', true)
        );

        $saltReturn = \FluitoPHP\Filters\Filters::GetInstance()->
                Run('FluitoPHP.Authentication.CreatePasswordReset', $saltReturn, $user, $user_login);

        if (\FluitoPHP\Error\Error::IsError($saltReturn)) {

            return $saltReturn;
        }

        $saltReturn = \FluitoPHP\Filters\Filters::GetInstance()->
                Run('FluitoPHP.Authentication.Policy.CreatePasswordReset', $saltReturn, $user, $user_login);

        if (\FluitoPHP\Error\Error::IsError($saltReturn)) {

            return $saltReturn;
        }

        $this->
                database->
                Conn($this->
                        GetConn())->
                Helper()->
                Insert($this->
                        GetPrefix() . 'userpassword', array(
                    'salt_id' => $saltReturn['salt_id'],
                    'user_id' => $user->
                    GetUserID(),
                    'salt' => $this->
                    PasswordHash($saltReturn['salt'], true)
                        )
                )->
                Query();

        if ($this->
                        database->
                        Conn($this->
                                GetConn())->
                        GetErrorCode()) {

            return new \FluitoPHP\Error\Error($this->
                            database->
                            Conn($this->
                                    GetConn())->
                            GetError(), 'create_password_reset_db_error');
        }

        return $saltReturn;
    }

    /**
     * Used to validate the reset password request of a user.
     *
     * @param string $salt_id Provide the salt id.
     * @param string $salt Provide the salt to compare with salt hash.
     * @return mixed Returns user id if validated else \FluitoPHP\Error\Error is returned.
     * @author Neha Jain
     * @since  0.1
     */
    public function ValidatePasswordReset($salt_id, $salt) {

        if (!is_string($salt_id) ||
                !strlen($salt_id)) {

            return new \FluitoPHP\Error\Error('No salt id has been provided.', 'validate_password_reset_no_salt_id');
        }

        if (!is_string($salt) ||
                !strlen($salt)) {

            return new \FluitoPHP\Error\Error('No salt has been provided.', 'validate_password_reset_no_salt');
        }

        $saltReturn = array(
            'salt_id' => $salt_id,
            'salt' => $salt
        );

        $saltReturn = \FluitoPHP\Filters\Filters::GetInstance()->
                Run('FluitoPHP.Authentication.ValidatePasswordReset', $saltReturn);

        if (\FluitoPHP\Error\Error::IsError($saltReturn)) {

            return $saltReturn;
        }

        $saltReturn = \FluitoPHP\Filters\Filters::GetInstance()->
                Run('FluitoPHP.Authentication.Policy.ValidatePasswordReset', $saltReturn);

        if (\FluitoPHP\Error\Error::IsError($saltReturn)) {

            return $saltReturn;
        }

        $saltRow = $this->
                database->
                Conn($this->
                        GetConn())->
                Helper()->
                Select($this->
                        GetPrefix() . 'userresetsalt', array(
                    'user_id',
                    'salt'
                        ), array(
                    array(
                        'column' => 'salt_id',
                        'operator' => '=',
                        'rightvalue' => $saltReturn['salt_id']
                    )
                ))->
                GetRow();

        if (!isset($saltRow['salt']) ||
                !$this->
                        PasswordVerify($saltReturn['salt'], $saltRow['salt'], true)) {

            return new \FluitoPHP\Error\Error('Invalid salt has been provided.', 'validate_password_reset_invalid_salt');
        }

        return $saltReturn['user_id'];
    }

    /**
     * Used to fulfill the reset password request of a user.
     *
     * @param string $salt_id Provide the salt id.
     * @param string $salt Provide the salt to compare with salt hash.
     * @param string $password Provide the password to set.
     * @return mixed Returns true if fulfilled else \FluitoPHP\Error\Error is returned.
     * @author Neha Jain
     * @since  0.1
     */
    public function FulfillPasswordReset($salt_id, $salt, $password) {

        $user_id = $this->
                ValidatePasswordReset($salt_id, $salt);

        if (\FluitoPHP\Error\Error::IsError($user_id)) {

            return $user_id;
        }

        $filterReturn = \FluitoPHP\Filters\Filters::GetInstance()->
                Run('FluitoPHP.Authentication.FulfillPasswordReset', true, $user_id, $password, $salt_id, $salt);

        if (\FluitoPHP\Error\Error::IsError($filterReturn)) {

            return $filterReturn;
        }

        $user = new \FluitoPHP\Authentication\User($user_id);

        $updatePasswordReturn = $user->
                UpdatePassword($password);

        if (\FluitoPHP\Error\Error::IsError($updatePasswordReturn)) {

            return $updatePasswordReturn;
        }

        $this->
                database->
                Conn($this->
                        GetConn())->
                Helper()->
                Delete($this->
                        GetPrefix() . 'userresetsalt', array(
                    array(
                        'column' => 'salt_id',
                        'operator' => '=',
                        'rightvalue' => $salt_id
                    )
                ))->
                Query();

        return true;
    }

    /**
     * Used to list the users in the system.
     *
     * @param bool $activeOnly Provide true if only active users are required to be fetched.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetUserList($activeOnly = false) {

        $where = array(
            array(
                'column' => 'B.eff_dttm',
                'operator' => '=',
                'rightsubquery' => $this->
                        database->
                        Conn($this->
                                GetConn())->
                        Helper()->
                        Select($this->
                                GetPrefix() . 'userdata B_ED', '&MAX(B_ED.eff_dttm)', array(
                            array(
                                'column' => 'B_ED.user_id',
                                'operator' => '=',
                                'rightcolumn' => 'B.user_id'
                            ),
                            array(
                                'column' => 'B_ED.eff_dttm',
                                'operator' => '<=',
                                'rightcolumn' => '&CurrDTTM'
                            )
                        ))
            )
        );

        if ($activeOnly) {

            $where[] = array(
                'column' => 'B.active',
                'operator' => '=',
                'rightvalue' => 'y'
            );
        }

        $userList = $this->
                database->
                Conn($this->
                        GetConn())->
                Helper()->
                Select(array(
                    array(
                        'table' => $this->
                        GetPrefix() . 'userdefn',
                        'alias' => 'A'
                    ),
                    array(
                        'table' => $this->
                        GetPrefix() . 'userdata',
                        'alias' => 'B',
                        'jointype' => 'ij',
                        'joinconditions' => array(
                            array(
                                'column' => 'A.user_id',
                                'operator' => '=',
                                'rightcolumn' => 'B.user_id'
                            )
                        )
                    )
                        ), 'B.*, A.user_login', $where
                )->
                GetResults();
    }

}
