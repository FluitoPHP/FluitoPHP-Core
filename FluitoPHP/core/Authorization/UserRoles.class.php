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

namespace FluitoPHP\Authorization;

/**
 * UserRoles Class.
 *
 * This class is used to fetch role details of the user.
 *
 * Variables:
 *      1. $user
 *      2. $roles
 *      3. $permissions
 *      4. $authentication
 *      5. $authorization
 *      6. $database
 *
 * Functions:
 *      1. __construct
 *      2. GetUser
 *      3. GetUserRoles
 *      4. SetUserRoles
 *      5. GetUserPermissions
 *      6. ValidatePermission
 *
 * @author Neha Jain
 * @since  0.1
 */
class UserRoles {

    /**
     * Used for storing Authentication User object.
     *
     * @var \FluitoPHP\Authentication\User
     * @author Neha Jain
     * @since  0.1
     */
    private $user = null;

    /**
     * Used for storing user roles.
     *
     * @var array
     * @author Neha Jain
     * @since  0.1
     */
    private $roles = null;

    /**
     * Used for storing user permissions.
     *
     * @var array
     * @author Neha Jain
     * @since  0.1
     */
    private $permissions = null;

    /**
     * Used to store the authentication instance.
     *
     * @var \FluitoPHP\Authentication\Authentication
     * @author Neha Jain
     * @since  0.1
     */
    private $authentication = null;

    /**
     * Used to store the authorization instance.
     *
     * @var \FluitoPHP\Authorization\Authorization
     * @author Neha Jain
     * @since  0.1
     */
    private $authorization = null;

    /**
     * Used to store the database instance.
     *
     * @var \FluitoPHP\Database\Database
     * @author Neha Jain
     * @since  0.1
     */
    private $database = null;

    /**
     * Used to initialize the user roles object.
     *
     * @param \FluitoPHP\Authentication\User $user Provide the user object.
     * @author Neha Jain
     * @since  0.1
     */
    function __construct($user) {

        $this->
                authentication = \FluitoPHP\Authentication\Authentication::GetInstance();

        $this->
                authorization = \FluitoPHP\Authorization\Authorization::GetInstance();

        $this->
                database = \FluitoPHP\Database\Database::GetInstance();

        if (!$user ||
                !$user->
                        GetUserID()) {

            throw new Exception('Please provide valid user object.');
        }

        $this->
                user = $user;
    }

    /**
     * Used to get the User object.
     *
     * @return \FluitoPHP\Authentication\User Returns the User object else false.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetUser() {

        if ($this->
                user === null) {

            return false;
        }

        return $this->
                user;
    }

    /**
     * Used to get the User Roles.
     *
     * @return array Returns the list of User roles.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetUserRoles() {

        if ($this->
                user === null) {

            return array();
        }

        if ($this->
                roles === null) {

            $roles = $this->
                    database->
                    Conn($this->
                            authentication->
                            GetConn())->
                    Helper()->
                    Select(array(
                        array(
                            'table' => $this->
                            authentication->
                            GetPrefix() . 'userroles',
                            'alias' => 'A'
                        ),
                        array(
                            'table' => $this->
                            authentication->
                            GetPrefix() . 'roles',
                            'alias' => 'B',
                            'jointype' => 'ij',
                            'joinconditions' => array(
                                array(
                                    'column' => 'A.role',
                                    'operator' => '=',
                                    'rightcolumn' => 'B.role'
                                )
                            )
                        )
                            ), 'A.role', array(
                        array(
                            'column' => 'A.user_id',
                            'operator' => '=',
                            'rightvalue' => $this->
                            user->
                            GetUserID()
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
                                    GetPrefix() . 'userroles A_ED', '&MAX(A_ED.eff_dttm)', array(
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
                                    GetPrefix() . 'roles B_ED', '&MAX(B_ED.eff_dttm)', array(
                                array(
                                    'column' => 'B_ED.role',
                                    'operator' => '=',
                                    'rightcolumn' => 'B.role'
                                ),
                                array(
                                    'column' => 'B_ED.eff_dttm',
                                    'operator' => '<=',
                                    'rightcolumn' => '&CurrDTTM'
                                )
                            ))
                        ),
                        array(
                            'column' => 'A.removed',
                            'operator' => '=',
                            'rightvalue' => 'n'
                        ),
                        array(
                            'column' => 'B.active',
                            'operator' => '=',
                            'rightvalue' => 'y'
                        )
                    ))->
                    GetColumn();

            $this->
                    roles = array();

            if (is_array($roles)) {

                foreach ($roles as $value) {

                    $this->
                            roles[] = $value['role'];
                }
            }
        }

        return $this->
                roles;
    }

    /**
     * Used to set the User Roles.
     *
     * @param array $roles Provide the list of roles that needs to set for the user.
     * @return bool Returns true on success and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function SetUserRoles($roles) {

        if ($this->
                user === null) {

            return false;
        }

        $old_roles = $this->
                GetUserRoles();

        $addRemoveRoles = array();

        $user_id = $this->
                GetUser()->
                GetUserID();

        $currentUserID = $this->
                authentication->
                GetUser()->
                GetUserID();

        $eff_dttm = $this->
                database->
                Conn($this->
                        authentication->
                        GetConn())->
                Helper()->
                Select($this->
                        authentication->
                        GetPrefix() . 'roles', '&CurrDTTM', array(
                    array(
                        'column' => 'role',
                        'operator' => '=',
                        'rightvalue' => 'administrator'
                    )
                        )
                )->
                GetVar();

        foreach ($old_roles as $role) {

            if (!in_array($role, $roles)) {

                $addRemoveRoles[] = array(
                    'user_id' => $user_id,
                    'eff_dttm' => $eff_dttm,
                    'role' => $role,
                    'removed' => 'y',
                    'updated_on' => array('function' => '&CurrDTTM'),
                    'updated_by' => $currentUserID
                );
            }
        }

        foreach ($roles as $role) {

            $validRole = $this->
                    database->
                    Conn($this->
                            authentication->
                            GetConn())->
                    Helper()->
                    Select($this->
                            authentication->
                            GetPrefix() . 'roles A', 'A.role, A.active', array(
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
                                    GetPrefix() . 'roles A_ED', '&MAX(A_ED.eff_dttm)', array(
                                array(
                                    'column' => 'A_ED.role',
                                    'operator' => '=',
                                    'rightcolumn' => 'A.role'
                                ),
                                array(
                                    'column' => 'A_ED.eff_dttm',
                                    'operator' => '<=',
                                    'rightcolumn' => '&CurrDTTM'
                                )
                            ))
                        ),
                        array(
                            'column' => 'A.role',
                            'operator' => '=',
                            'rightvalue' => $role
                        )
                            )
                    )->
                    GetRow();

            if ($validRole &&
                    $validRole['active'] === 'y') {

                $addRemoveRoles[] = array(
                    'user_id' => $user_id,
                    'eff_dttm' => $eff_dttm,
                    'role' => $role,
                    'removed' => 'n',
                    'updated_on' => array('function' => '&CurrDTTM'),
                    'updated_by' => $currentUserID
                );
            } else if ($validRole['active'] === 'n' &&
                    in_array($role, $old_roles)) {

                $addRemoveRoles[] = array(
                    'user_id' => $user_id,
                    'eff_dttm' => $eff_dttm,
                    'role' => $role,
                    'removed' => 'y',
                    'updated_on' => array('function' => '&CurrDTTM'),
                    'updated_by' => $currentUserID
                );
            }
        }

        $this->
                database->
                Conn($this->
                        authentication->
                        GetConn())->
                Helper()->
                Insert($this->
                        authentication->
                        GetPrefix() . 'userroles', $addRemoveRoles)->
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
                            GetError(), 'setuserroles_db_error');
        }

        $this->
                roles = null;
        $this->
                permissions = null;

        return true;
    }

    /**
     * Used to get the User Permissions.
     *
     * @return array Returns the list of user permissions
     * @author Neha Jain
     * @since  0.1
     */
    public function GetUserPermissions() {

        if ($this->
                user === null) {

            return array();
        }

        if ($this->
                permissions === null) {

            $roles = $this->
                    GetUserRoles();

            if (empty($roles)) {

                return array();
            }

            $rolecolumn = "('" . implode("', '", $roles) . "')";

            $permissions = $this->
                    database->
                    Conn($this->
                            authentication->
                            GetConn())->
                    Helper()->
                    Select(array(
                        array(
                            'table' => $this->
                            authentication->
                            GetPrefix() . 'roles',
                            'alias' => 'A'
                        ),
                        array(
                            'table' => $this->
                            authentication->
                            GetPrefix() . 'roleperm',
                            'alias' => 'B',
                            'jointype' => 'ij',
                            'joinconditions' => array(
                                array(
                                    'column' => 'A.role',
                                    'operator' => '=',
                                    'rightcolumn' => 'B.role'
                                ),
                                array(
                                    'column' => 'A.eff_dttm',
                                    'operator' => '=',
                                    'rightcolumn' => 'B.eff_dttm'
                                )
                            )
                        )
                            ), 'B.permission', array(
                        array(
                            'column' => 'A.role',
                            'operator' => 'IN',
                            'rightcolumn' => $rolecolumn
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
                                    GetPrefix() . 'roles A_ED', '&MAX(A_ED.eff_dttm)', array(
                                array(
                                    'column' => 'A_ED.role',
                                    'operator' => '=',
                                    'rightcolumn' => 'A.role'
                                ),
                                array(
                                    'column' => 'A_ED.eff_dttm',
                                    'operator' => '<=',
                                    'rightcolumn' => '&CurrDTTM'
                                )
                            ))
                        ),
                        array(
                            'column' => 'A.active',
                            'operator' => '=',
                            'rightvalue' => 'y'
                        )
                    ))->
                    GetColumn();

            $this->
                    permissions = array();

            if (is_array($permissions)) {

                foreach ($permissions as $value) {

                    $this->
                            permissions[] = $value['permission'];
                }
            }
        }

        return $this->
                permissions;
    }

    /**
     * Used to validate if the passed permission is in the current user.
     *
     * @param string $permission Provide the permission against which the user needs to be validated.
     * @return bool Returns true if the user is valid for the permission provided.
     * @author Neha Jain
     * @since  0.1
     */
    public function ValidatePermission($permission) {

        if ($this->
                user === null) {

            return false;
        }

        if (in_array('administrator', $this->
                                GetUserRoles()) ||
                (is_string($permission) &&
                strlen($permission) &&
                in_array($permission, $this->
                                GetUserPermissions()))) {

            return true;
        }

        return false;
    }

}
