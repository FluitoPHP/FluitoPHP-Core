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
 * User Class.
 *
 * This class is used to fetch details about the user.
 *
 * Variables:
 *      1. $userID
 *      2. $userLogin
 *      3. $userData
 *      4. $authentication
 *      5. $database
 *
 * Functions:
 *      1. __construct
 *      2. GetUserID
 *      3. GetUserLogin
 *      4. GetPasswordHash
 *      5. ValidatePassword
 *      6. ChangePassword
 *      7. UpdatePassword
 *      8. GetUserData
 *      9. SetUserData
 *      10. EnableDisable
 *      11. Enable
 *      12. Disable
 *
 * @author Neha Jain
 * @since  0.1
 */
class User {

    /**
     * Used for storing User ID.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    private $userID = null;

    /**
     * Used to store the user login.
     *
     * @var string
     * @author Neha Jain
     * @since  0.1
     */
    private $userLogin = null;

    /**
     * Used to store the user data.
     *
     * @var array
     * @author Neha Jain
     * @since  0.1
     */
    private $userData = [];

    /**
     * Used to store the authentication instance.
     *
     * @var \FluitoPHP\Authentication\Authentication
     * @author Neha Jain
     * @since  0.1
     */
    private $authentication = null;

    /**
     * Used to store the database instance.
     *
     * @var \FluitoPHP\Database\Database
     * @author Neha Jain
     * @since  0.1
     */
    private $database = null;

    /**
     * Used to initialize the user object.
     *
     * @param string $user_login Provide the user id/login.
     * @param bool $activeOnly Provide false if the non active user is required to be fetched.
     * @author Neha Jain
     * @since  0.1
     */
    function __construct($user_login, $activeOnly = true) {

        $this->
                authentication = \FluitoPHP\Authentication\Authentication::GetInstance();

        $this->
                database = \FluitoPHP\Database\Database::GetInstance();

        if (!is_string($user_login) ||
                !strlen($user_login)) {

            return;
        }

        if ($user_login === 'system') {

            $this->
                    userID = 'system';

            $this->
                    userLogin = 'system';

            return;
        }

        if ($activeOnly) {

            $userRow = $this->
                    database->
                    Conn($this->
                            authentication->
                            GetConn())->
                    Helper()->
                    Select(array(
                        array(
                            'table' => $this->
                            authentication->
                            GetPrefix() . 'userdefn',
                            'alias' => 'A'
                        ),
                        array(
                            'table' => $this->
                            authentication->
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
                            ), 'A.user_id, A.user_login', array(
                        array(
                            'column' => 'A.user_id',
                            'operator' => '=',
                            'rightvalue' => $user_login,
                            'startbrackets' => '('
                        ),
                        array(
                            'operatortype' => 'OR',
                            'column' => 'A.user_login',
                            'operator' => '=',
                            'rightvalue' => $user_login,
                            'endbrackets' => ')'
                        ),
                        array(
                            'column' => 'B.eff_dttm',
                            'operator' => '=',
                            'rightsubquery' => $this->
                            database->
                            Conn($this->
                                    authentication->
                                    GetConn())->
                            Helper()->
                            Select($this->
                                    authentication->
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
                        ),
                        array(
                            'column' => 'B.active',
                            'operator' => '=',
                            'rightvalue' => 'y'
                        )
                    ))->
                    GetRow();
        } else {

            $userRow = $this->
                    database->
                    Conn($this->
                            authentication->
                            GetConn())->
                    Helper()->
                    Select($this->
                            authentication->
                            GetPrefix() . 'userdefn', 'user_id, user_login', array(
                        array(
                            'column' => 'user_id',
                            'operator' => '=',
                            'rightvalue' => $user_login
                        ),
                        array(
                            'operatortype' => 'OR',
                            'column' => 'user_login',
                            'operator' => '=',
                            'rightvalue' => $user_login
                        )
                    ))->
                    GetRow();
        }

        if (!$userRow) {

            return;
        }

        $this->
                userID = $userRow['user_id'];

        $this->
                userLogin = $userRow['user_login'];
    }

    /**
     * Used to get the User ID.
     *
     * @return mixed Returns the User ID else false.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetUserID() {

        if ($this->
                userID === null) {

            return false;
        }

        return $this->
                userID;
    }

    /**
     * Used to get the User Login.
     *
     * @return mixed Returns the User Login else false.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetUserLogin() {

        if ($this->
                userID === null) {

            return false;
        }

        return $this->
                userLogin;
    }

    /**
     * Used to fetch the password hash of the user.
     *
     * @return string Returns the password hash or false if unable to fetch.
     * @author Neha Jain
     * @since  0.1
     */
    protected function GetPasswordHash() {

        if ($this->
                userID === null ||
                $this->
                userID === 'system') {

            return false;
        }

        $passwordHash = $this->
                database->
                Conn($this->
                        authentication->
                        GetConn())->
                Helper()->
                Select($this->
                        authentication->
                        GetPrefix() . 'userpassword A', 'password', array(
                    array(
                        'column' => 'A.user_id',
                        'operator' => '=',
                        'rightvalue' => $this->
                        userID
                    ),
                    array(
                        'column' => 'A.eff_dttm',
                        'operator' => '=',
                        'rightsubquery' => $this->
                        database->
                        Conn($this->
                                authentication->
                                GetConn())->
                        Helper()->
                        Select($this->
                                authentication->
                                GetPrefix() . 'userpassword A_ED', '&MAX(A_ED.eff_dttm)', array(
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
                ))->
                GetVar();

        if (!$passwordHash) {

            return false;
        }

        return $passwordHash;
    }

    /**
     * Used to validate the password of the user.
     *
     * @param string $password Provide the password for the user.
     * @return bool Returns true if success else false.
     * @author Neha Jain
     * @since  0.1
     */
    public function ValidatePassword($password) {

        if ($this->
                userID === null ||
                $this->
                userID === 'system') {

            return false;
        }

        $passwordHash = $this->
                GetPasswordHash();

        if ($passwordHash &&
                        $this->
                        authentication->
                        PasswordVerify($password, $passwordHash)) {

            return true;
        }

        return false;
    }

    /**
     * Used to change the password of the logged in user.
     *
     * @param string $newPassword Provide the new password that needs to be set.
     * @param string $oldPassword Provide the old password for verification.
     * @return mixed Returns password string if updated else \FluitoPHP\Error\Error is returned.
     * @author Neha Jain
     * @since  0.1
     */
    public function ChangePassword($newPassword, $oldPassword) {

        if (!$this->
                userID ||
                $this->
                userID === 'system' ||
                $this->
                userID !== $this->
                        authentication->
                        GetUser()->
                        GetUserID()) {

            return new \FluitoPHP\Error\Error('Please login to use this functionality.', 'change_password_not_logged_in');
        }

        if (!is_string($oldPassword) ||
                !strlen($oldPassword)) {

            return new \FluitoPHP\Error\Error('Please enter current password.', 'change_password_empty_password');
        }

        $passwordHash = $this->
                GetPasswordHash();

        if (!$passwordHash ||
                !$this->
                        authentication->
                        PasswordVerify($oldPassword, $passwordHash)) {

            return new \FluitoPHP\Error\Error('The current password is incorrect.', 'change_password_wrong_password');
        }

        $newPassword = \FluitoPHP\Filters\Filters::GetInstance()->
                Run('FluitoPHP.Authentication.User.ChangePassword', $newPassword, $oldPassword, $this->
                GetUser());

        if (\FluitoPHP\Error\Error::IsError($newPassword)) {

            return $newPassword;
        }

        return $this->
                        UpdatePassword($newPassword);
    }

    /**
     * Used to update the password of this user.
     *
     * @param string $password Provide the new password that needs to be set.
     * @param bool $generate Provide true if no password is null and a new password needs to be generated.
     * @return mixed Returns password string if updated else \FluitoPHP\Error\Error is returned.
     * @author Neha Jain
     * @since  0.1
     */
    public function UpdatePassword($password, $generate = false) {

        if (!$this->
                userID ||
                $this->
                userID === 'system') {

            return new \FluitoPHP\Error\Error('No user id/login has been provided.', 'update_password_no_user');
        }

        if (!is_string($password) ||
                !strlen($password)) {

            if ($generate) {

                $password = uniqid(time(), true);
            } else {

                return new \FluitoPHP\Error\Error('Please enter password.', 'update_password_password_empty');
            }
        } else if (strlen($password) > 72) {

            return new \FluitoPHP\Error\Error('Password can not be longer than 72 characters.', 'update_password_password_max');
        } else if (strlen($password) < 8) {

            return new \FluitoPHP\Error\Error('Password can not be shorter than 8 characters.', 'update_password_password_min');
        }

        $currentUser = $this->
                authentication->
                GetUser();

        $password = \FluitoPHP\Filters\Filters::GetInstance()->
                Run('FluitoPHP.Authentication.User.UpdatePassword', $password, $this);

        if (\FluitoPHP\Error\Error::IsError($password)) {

            return $password;
        }

        $password = \FluitoPHP\Filters\Filters::GetInstance()->
                Run('FluitoPHP.Authentication.User.Policy.Password', $password, $this);

        if (\FluitoPHP\Error\Error::IsError($password)) {

            return $password;
        }

        $this->
                database->
                Conn($this->
                        authentication->
                        GetConn())->
                Helper()->
                Insert($this->
                        authentication->
                        GetPrefix() . 'userpassword', array(
                    'user_id' => $this->
                    userID,
                    'eff_dttm' => array('function' => '&CurrDTTM'),
                    'password' => $this->
                    authentication->
                    PasswordHash($password),
                    'updated_on' => array('function' => '&CurrDTTM'),
                    'updated_by' => $currentUser->
                    GetUserID()
                        )
                )->
                Query();

        if ($this->
                        database->
                        Conn($this->
                                authentication->
                                GetConn())->
                        GetErrorCode()) {

            return new \FluitoPHP\Error\Error($this->
                            database->
                            Conn($this->
                                    authentication->
                                    GetConn())->
                            GetError(), 'update_password_db_error');
        }

        return $password;
    }

    /**
     * Used to get the user data.
     *
     * @return mixed Returns associative array of the user data else false.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetUserData() {

        if (!$this->
                userID ||
                $this->
                userID === 'system') {

            return false;
        }

        if (!$this->
                userData ||
                \FluitoPHP\Error\Error::IsError($this->
                        userData)) {

            $this->
                    userData = $this->
                    database->
                    Conn($this->
                            authentication->
                            GetConn())->
                    Helper()->
                    Select(array(
                        array(
                            'table' => $this->
                            authentication->
                            GetPrefix() . 'userdefn',
                            'alias' => 'A'
                        ),
                        array(
                            'table' => $this->
                            authentication->
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
                            ), 'A.user_login, B.*', array(
                        array(
                            'column' => 'A.user_id',
                            'operator' => '=',
                            'rightvalue' => $this->
                            userID
                        ),
                        array(
                            'column' => 'B.eff_dttm',
                            'operator' => '=',
                            'rightsubquery' => $this->
                            database->
                            Conn($this->
                                    authentication->
                                    GetConn())->
                            Helper()->
                            Select($this->
                                    authentication->
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
                    ))->
                    GetRow();

            $this->
                    userData = \FluitoPHP\Filters\Filters::GetInstance()->
                    Run('FluitoPHP.Authentication.User.GetUserData', $this->
                    userData, $this);
        }

        return $this->
                userData;
    }

    /**
     * Used to update/create a user.
     *
     * @param array $data Provide the details for the user. 'user_login' key is mandatory in case of create.
     * @param bool $use_login Provide true if user_login should be used to create the new user as user id.
     * @return mixed Returns true if created/updated else \FluitoPHP\Error\Error will be returned.
     * @author Neha Jain
     * @since  0.1
     */
    public function SetUserData($data, $use_login = false) {

        if ($this->
                userID === 'system') {

            return new \FluitoPHP\Error\Error('No user id/login has been provided.', 'setuserdata_no_user');
        }

        $user_id = '';

        if (!$this->
                userID) {

            if (!is_string($data['user_login']) ||
                    !strlen($data['user_login'])) {

                return new \FluitoPHP\Error\Error('Please enter user login for user creation.', 'setuserdata_no_login');
            }

            $duplicate = $this->
                    database->
                    Conn($this->
                            authentication->
                            GetConn())->
                    Helper()->
                    Select($this->
                            authentication->
                            GetPrefix() . 'userdefn', 'user_login', array(
                        array(
                            'column' => 'user_login',
                            'operator' => '=',
                            'rightvalue' => $data['user_login']
                        )
                    ))->
                    GetVar();

            if ($duplicate) {

                return new \FluitoPHP\Error\Error('Please use another login as this is already used.', 'setuserdata_duplicate_login');
            }

            $user_login = $data['user_login'];

            $user_id = $user_login;

            if (!$use_login) {

                $user_id = uniqid(time() . '-');
            } else {

                $duplicateid = $this->
                        database->
                        Conn($this->
                                authentication->
                                GetConn())->
                        Helper()->
                        Select($this->
                                authentication->
                                GetPrefix() . 'userdefn', 'user_id', array(
                            array(
                                'column' => 'user_id',
                                'operator' => '=',
                                'rightvalue' => $user_id
                            )
                        ))->
                        GetVar();

                if ($duplicateid) {

                    return new \FluitoPHP\Error\Error('Please use another id as this is already used.', 'setuserdata_duplicate_id');
                }
            }

            $currentUser = $this->
                    authentication->
                    GetUser();

            $this->
                    database->
                    Conn($this->
                            authentication->
                            GetConn())->
                    Helper()->
                    Insert($this->
                            authentication->
                            GetPrefix() . 'userdefn', array(
                        'user_id' => $user_id,
                        'user_login' => $user_login,
                        'created_on' => array('function' => '&CurrDTTM'),
                        'created_by' => $currentUser->
                        GetUserID()
                            )
                    )->
                    Query();

            if ($this->
                            database->
                            Conn($this->
                                    authentication->
                                    GetConn())->
                            GetErrorCode()) {

                return new \FluitoPHP\Error\Error($this->
                                database->
                                Conn($this->
                                        authentication->
                                        GetConn())->
                                GetError(), 'setuserdata_db_error');
            }
        } else {

            $user_id = $this->
                    userID;

            $data = array_replace($this->
                            GetUserData(), $data);
        }

        $error = false;

        if (!isset($data['email']) ||
                !is_string($data['email']) ||
                !strlen($data['email'])) {

            $error = new \FluitoPHP\Error\Error('Please provide user email.', 'setuserdata_no_email');
            goto SETUSERDATA_ERROR;
        }

        $data['email'] = filter_var($data['email'], FILTER_SANITIZE_EMAIL);

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {

            $error = new \FluitoPHP\Error\Error('Please provide valid user email.', 'setuserdata_invalid_email');
            goto SETUSERDATA_ERROR;
        }

        $duplicateemail = $this->
                database->
                Conn($this->
                        authentication->
                        GetConn())->
                Helper()->
                Select($this->
                        authentication->
                        GetPrefix() . 'userdata', 'email', array(
                    array(
                        'column' => 'user_id',
                        'operator' => '<>',
                        'rightvalue' => $user_id
                    ),
                    array(
                        'column' => 'email',
                        'operator' => '=',
                        'rightvalue' => $data['email']
                    )
                ))->
                GetVar();

        if ($duplicateemail) {

            $error = new \FluitoPHP\Error\Error('Please use another email as this is already used.', 'setuserdata_duplicate_email');
            goto SETUSERDATA_ERROR;
        }

        if (!isset($data['first_name']) ||
                !is_string($data['first_name']) ||
                !strlen($data['first_name'])) {

            $error = new \FluitoPHP\Error\Error('Please provide user first name.', 'setuserdata_no_fname');
            goto SETUSERDATA_ERROR;
        }

        if (!isset($data['last_name']) ||
                !is_string($data['last_name']) ||
                !strlen($data['last_name'])) {

            $error = new \FluitoPHP\Error\Error('Please provide user last name.', 'setuserdata_no_lname');
            goto SETUSERDATA_ERROR;
        }

        if (!isset($data['middle_name']) ||
                !is_string($data['middle_name']) ||
                !strlen($data['middle_name'])) {

            $data['middle_name'] = array('function' => 'NULL');
        }

        if (!isset($data['dob']) ||
                !is_string($data['dob']) ||
                !strlen($data['dob'])) {

            $data['dob'] = array('function' => 'NULL');
        }

        if (!isset($data['doa']) ||
                !is_string($data['doa']) ||
                !strlen($data['doa'])) {

            $data['doa'] = array('function' => 'NULL');
        }

        if (!isset($data['active']) ||
                !is_string($data['active']) ||
                !strlen($data['active'])) {

            $data['active'] = 'y';
        }

        $eff_dttm = $this->
                database->
                Conn($this->
                        authentication->
                        GetConn())->
                Helper()->
                Select($this->
                        authentication->
                        GetPrefix() . 'userdefn', '&CurrDTTM', array(
                    array(
                        'column' => 'user_id',
                        'operator' => '=',
                        'rightvalue' => $user_id
                    )
                        )
                )->
                GetVar();

        $filterReturn = \FluitoPHP\Filters\Filters::GetInstance()->
                Run('FluitoPHP.Authentication.User.SetUserData.Validate', true, $user_id, $data, $this);

        if (\FluitoPHP\Error\Error::IsError($filterReturn)) {

            $error = $filterReturn;
            goto SETUSERDATA_ERROR;
        }

        $this->
                database->
                Conn($this->
                        authentication->
                        GetConn())->
                Helper()->
                Insert($this->
                        authentication->
                        GetPrefix() . 'userdata', array(
                    'user_id' => $user_id,
                    'eff_dttm' => $eff_dttm,
                    'email' => $data['email'],
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'middle_name' => $data['middle_name'],
                    'dob' => $data['dob'],
                    'doa' => $data['doa'],
                    'active' => $data['active'],
                    'updated_on' => array('function' => '&CurrDTTM'),
                    'updated_by' => $currentUser->
                    GetUserID()
                        )
                )->
                Query();

        if ($this->
                        database->
                        Conn($this->
                                authentication->
                                GetConn())->
                        GetErrorCode()) {

            $error = new \FluitoPHP\Error\Error($this->
                            database->
                            Conn($this->
                                    authentication->
                                    GetConn())->
                            GetError(), 'create_db_error');
            goto SETUSERDATA_ERROR;
        }

        $filterReturn = \FluitoPHP\Filters\Filters::GetInstance()->
                Run('FluitoPHP.Authentication.User.SetUserData', true, $user_id, $data, $this);

        if (\FluitoPHP\Error\Error::IsError($filterReturn)) {

            $this->
                    database->
                    Conn($this->
                            authentication->
                            GetConn())->
                    Helper()->
                    Delete($this->
                            authentication->
                            GetPrefix() . 'userdata', array(
                        array(
                            'column' => 'user_id',
                            'operator' => '=',
                            'rightvalue' => $user_id
                        ),
                        array(
                            'column' => 'eff_dttm',
                            'operator' => '=',
                            'rightvalue' => $eff_dttm
                        )
                            )
                    )->
                    Query();

            $error = $filterReturn;
            goto SETUSERDATA_ERROR;
        }

        if ($user_id !== $this->
                userID) {

            $this->
                    userID = $user_id;

            $this->
                    userLogin = $user_login;
        }

        $this->
                userData = null;

        return true;

        SETUSERDATA_ERROR:

        if ($user_id !== $this->
                userID) {

            $this->
                    database->
                    Conn($this->
                            authentication->
                            GetConn())->
                    Helper()->
                    Delete($this->
                            authentication->
                            GetPrefix() . 'userdefn', array(
                        array(
                            'column' => 'user_id',
                            'operator' => '=',
                            'rightvalue' => $user_id
                        )
                            )
                    )->
                    Query();
        }

        return $error;
    }

    /**
     * Used to enable/disable this user.
     *
     * @param bool $enabled Provide true to enable false to disable the user.
     * @return mixed Returns true if enabled else \FluitoPHP\Error\Error will be returned.
     * @author Neha Jain
     * @since  0.1
     */
    private function EnableDisable($enabled = true) {

        if (!$this->
                userID ||
                $this->
                userID === 'system') {

            return new \FluitoPHP\Error\Error('Please enter user id/login.', 'enabledisable_no_login');
        }

        if (!$enabled &&
                $this->
                userID === 'administrator') {

            return new \FluitoPHP\Error\Error('Cannot disable administrator user account.', 'enabledisable_admin_login');
        }

        $userData = $this->
                GetUserData();

        if ($enabled) {

            if ($userData['active'] === 'y') {

                return true;
            }
        } else {

            if ($userData['active'] === 'n') {

                return true;
            }
        }

        $updateArray = $userData;

        $currentUser = $this->
                authentication->
                GetUser();

        $eff_dttm = $this->
                database->
                Conn($this->
                        authentication->
                        GetConn())->
                Helper()->
                Select($this->
                        authentication->
                        GetPrefix() . 'userdefn', '&CurrDTTM', array(
                    array(
                        'column' => 'user_id',
                        'operator' => '=',
                        'rightvalue' => $this->
                        userID
                    )
                        )
                )->
                GetVar();

        $updateArray['eff_dttm'] = $eff_dttm;
        $updateArray['updated_on'] = array('function' => '&CurrDTTM');
        $updateArray['updated_by'] = $currentUser->
                GetUserID();
        $updateArray['active'] = $enabled ? 'y' : 'n';

        $this->
                database->
                Conn($this->
                        authentication->
                        GetConn())->
                Helper()->
                Insert($this->
                        authentication->
                        GetPrefix() . 'userdata', $updateArray)->
                Query();

        if ($this->
                        database->
                        Conn($this->
                                authentication->
                                GetConn())->
                        GetErrorCode()) {

            return new \FluitoPHP\Error\Error($this->
                            database->
                            Conn($this->
                                    authentication->
                                    GetConn())->
                            GetError(), 'enable_db_error');
        }

        $filterReturn = \FluitoPHP\Filters\Filters::GetInstance()->
                Run('FluitoPHP.Authentication.User.EnableDisable', true, $this->
                userID, $updateArray, $enabled);

        if (\FluitoPHP\Error\Error::IsError($filterReturn)) {

            $this->
                    database->
                    Conn($this->
                            authentication->
                            GetConn())->
                    Helper()->
                    Delete($this->
                            authentication->
                            GetPrefix() . 'userdata', array(
                        array(
                            'column' => 'user_id',
                            'operator' => '=',
                            'rightvalue' => $this->
                            userID
                        ),
                        array(
                            'column' => 'eff_dttm',
                            'operator' => '=',
                            'rightsubquery' => $eff_dttm
                        )
                            )
                    )->
                    Query();

            return $filterReturn;
        }

        $this->
                userData = null;

        return true;
    }

    /**
     * Used to enable this user.
     *
     * @return mixed Returns true if enabled else \FluitoPHP\Error\Error will be returned.
     * @author Neha Jain
     * @since  0.1
     */
    public function Enable() {

        return $this->
                        EnableDisable(true);
    }

    /**
     * Used to disable this user.
     *
     * @return mixed Returns true if disable else \FluitoPHP\Error\Error will be returned.
     * @author Neha Jain
     * @since  0.1
     */
    public function Disable() {

        return $this->
                        EnableDisable(false);
    }

}
