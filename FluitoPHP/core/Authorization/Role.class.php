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

namespace FluitoPHP\Authorization;

/**
 * Role Class.
 *
 * This class is used to fetch role details.
 *
 * Variables:
 *      1. $role
 *      2. $roledata
 *      4. $authentication
 *      5. $authorization
 *      6. $database
 *
 * Functions:
 *      1. __construct
 *      2. GetRole
 *      3. GetRoleData
 *      4. SetRoleData
 *      5. EnableDisable
 *      6. Enable
 *      7. Disable
 *
 * @author Vipin Jain
 * @since  0.1
 */
class Role {

    /**
     * Used for storing role name.
     *
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    private $role = null;

    /**
     * Used for storing role data.
     *
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    private $roledata = null;

    /**
     * Used to store the authentication instance.
     *
     * @var \FluitoPHP\Authentication\Authentication
     * @author Vipin Jain
     * @since  0.1
     */
    private $authentication = null;

    /**
     * Used to store the authorization instance.
     *
     * @var \FluitoPHP\Authorization\Authorization
     * @author Vipin Jain
     * @since  0.1
     */
    private $authorization = null;

    /**
     * Used to store the database instance.
     *
     * @var \FluitoPHP\Database\Database
     * @author Vipin Jain
     * @since  0.1
     */
    private $database = null;

    /**
     * Used to initialize the role object.
     *
     * @param string $role Provide the role name.
     * @author Vipin Jain
     * @since  0.1
     */
    function __construct($role) {

        $this->
                authentication = \FluitoPHP\Authentication\Authentication::GetInstance();

        $this->
                authorization = \FluitoPHP\Authorization\Authorization::GetInstance();

        $this->
                database = \FluitoPHP\Database\Database::GetInstance();

        if (!is_string($role) ||
                !strlen($role)) {

            return;
        }

        $this->
                role = $this->
                database->
                Conn($this->
                        authentication->
                        GetConn())->
                Helper()->
                Select($this->
                        authentication->
                        GetPrefix() . 'roles A', 'A.role', array(
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
                GetVar();

        $this->
                role = $this->
                role ? $this->
                role : null;
    }

    /**
     * Used to get the role name.
     *
     * @return mixed Returns the role name else false.
     * @author Vipin Jain
     * @since  0.1
     */
    public function GetRole() {

        if ($this->
                role === null) {

            return false;
        }

        return $this->
                role;
    }

    /**
     * Used to get the Role data.
     *
     * @return mixed Returns the role data else false.
     * @author Vipin Jain
     * @since  0.1
     */
    public function GetRoleData() {

        if ($this->
                role === null) {

            return false;
        }

        if ($this->
                roledata === null) {

            $this->
                    roledata = $this->
                    database->
                    Conn($this->
                            authentication->
                            GetConn())->
                    Helper()->
                    Select($this->
                            authentication->
                            GetPrefix() . 'roles A', 'A.*', array(
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
                            'rightvalue' => $this->
                            role
                        )
                            )
                    )->
                    GetRow();

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
                            'rightvalue' => $this->
                            role
                        )
                            )
                    )->
                    GetColumn();

            $this->
                    roledata['permissions'] = array();

            if (is_array($permissions)) {

                foreach ($permissions as $permission) {

                    $this->
                            roledata['permissions'][] = $permission['permission'];
                }
            }
        }

        return $this->
                roledata;
    }

    /**
     * Used to update/create a role.
     *
     * @param array $data Provide the details for the role. 'role' key is mandatory in case of create.
     * @param bool $adminEdit Provide true if you want to edit administrator role. Caution this should be used only by internal functions.
     * @return mixed Returns true if created/updated else \FluitoPHP\Error\Error will be returned.
     * @author Vipin Jain
     * @since  0.1
     */
    public function SetRoleData($data, $adminEdit = false) {

        if ($this->
                role === 'administrator' &&
                !$adminEdit) {

            return new \FluitoPHP\Error\Error('Cannot update administrator role.', 'setroledata_admin_role');
        }

        $role = '';

        if (!$this->
                role) {

            if (!is_string($data['role']) ||
                    !strlen($data['role'])) {

                return new \FluitoPHP\Error\Error('Please enter role name for role creation.', 'setroledata_no_role');
            }

            $duplicate = $this->
                    database->
                    Conn($this->
                            authentication->
                            GetConn())->
                    Helper()->
                    Select($this->
                            authentication->
                            GetPrefix() . 'roles', 'role', array(
                        array(
                            'column' => 'role',
                            'operator' => '=',
                            'rightvalue' => $data['role']
                        )
                    ))->
                    GetVar();

            if ($duplicate) {

                return new \FluitoPHP\Error\Error('Please use another role name as this is already used.', 'setroledata_duplicate_role');
            }

            $role = $data['role'];

            $currentUser = $this->
                    authentication->
                    GetUser();
        } else {

            $role = $this->
                    role;

            $data = array_replace($this->
                            GetRoleData(), $data);
        }

        $eff_dttm = $this->
                database->
                Conn($this->
                        authentication->
                        GetConn())->
                Helper()->
                Select($this->
                        authentication->
                        GetPrefix() . 'roles A', '&CurrDTTM', array(
                    array(
                        'column' => 'A.role',
                        'operator' => '=',
                        'rightvalue' => 'administrator'
                    )
                        )
                )->
                GetVar();

        if (!$eff_dttm) {
            $eff_dttm = array('function' => '&CurrDTTM');
        }

        $filterReturn = \FluitoPHP\Filters\Filters::GetInstance()->
                Run('FluitoPHP.Authentication.Role.SetRoleData.Validate', true, $role, $data, $eff_dttm, $this);

        if (\FluitoPHP\Error\Error::IsError($filterReturn)) {

            return $filterReturn;
        }

        if (!isset($data['active']) ||
                !is_string($data['active']) ||
                !in_array($data['active'], array('y', 'n'))) {

            $data['active'] = 'y';
        }

        $this->
                database->
                Conn($this->
                        authentication->
                        GetConn())->
                Helper()->
                Insert($this->
                        authentication->
                        GetPrefix() . 'roles', array(
                    'role' => $role,
                    'eff_dttm' => $eff_dttm,
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

            return new \FluitoPHP\Error\Error($this->
                            database->
                            Conn($this->
                                    authentication->
                                    GetConn())->
                            GetError(), 'setroledata_db_error');
        }

        $permissions = array();

        foreach ($data['permissions'] as $permission) {

            $permissions[] = array(
                'role' => $role,
                'eff_dttm' => $eff_dttm,
                'permission' => $permission
            );
        }

        $this->
                database->
                Conn($this->
                        authentication->
                        GetConn())->
                Helper()->
                Insert($this->
                        authentication->
                        GetPrefix() . 'roleperm', $permissions)->
                Query();

        if ($this->
                        database->
                        Conn($this->
                                authentication->
                                GetConn())->
                        GetErrorCode()) {

            $error = $this->
                    database->
                    Conn($this->
                            authentication->
                            GetConn())->
                    GetError();

            $this->
                    database->
                    Conn($this->
                            authentication->
                            GetConn())->
                    Helper()->
                    Delete($this->
                            authentication->
                            GetPrefix() . 'roles', array(
                        array(
                            'column' => 'role',
                            'operator' => '=',
                            'rightvalue' => $role
                        ),
                        array(
                            'column' => 'eff_dttm',
                            'operator' => '=',
                            'rightvalue' => $eff_dttm
                        )
                            )
                    )->
                    Query();

            return new \FluitoPHP\Error\Error($error, 'setroledata_db_error');
        }

        $filterReturn = \FluitoPHP\Filters\Filters::GetInstance()->
                Run('FluitoPHP.Authentication.Role.SetRoleData', true, $role, $data, $eff_dttm, $this);

        if (\FluitoPHP\Error\Error::IsError($filterReturn)) {

            $this->
                    database->
                    Conn($this->
                            authentication->
                            GetConn())->
                    Helper()->
                    Delete($this->
                            authentication->
                            GetPrefix() . 'roleperm', array(
                        array(
                            'column' => 'role',
                            'operator' => '=',
                            'rightvalue' => $role
                        ),
                        array(
                            'column' => 'eff_dttm',
                            'operator' => '=',
                            'rightvalue' => $eff_dttm
                        )
                            )
                    )->
                    Query();

            $this->
                    database->
                    Conn($this->
                            authentication->
                            GetConn())->
                    Helper()->
                    Delete($this->
                            authentication->
                            GetPrefix() . 'roles', array(
                        array(
                            'column' => 'role',
                            'operator' => '=',
                            'rightvalue' => $role
                        ),
                        array(
                            'column' => 'eff_dttm',
                            'operator' => '=',
                            'rightvalue' => $eff_dttm
                        )
                            )
                    )->
                    Query();

            return $filterReturn;
        }

        if ($role !== $this->
                role) {

            $this->
                    role = $role;
        }

        $this->
                roleData = null;

        return true;
    }

    /**
     * Used to enable/disable this role.
     *
     * @param bool $enabled Provide true to enable false to disable the role.
     * @return mixed Returns true if enabled else \FluitoPHP\Error\Error will be returned.
     * @author Vipin Jain
     * @since  0.1
     */
    private function EnableDisable($enabled = true) {

        if (!$this->
                role) {

            return new \FluitoPHP\Error\Error('Please enter role name.', 'enabledisable_no_role');
        }

        if (!$enabled &&
                $this->
                role === 'administrator') {

            return new \FluitoPHP\Error\Error('Cannot disable administrator role.', 'enabledisable_admin_role');
        }

        $roleData = $this->
                GetRoleData();

        if ($enabled) {

            if ($roleData['active'] === 'y') {

                return true;
            }
        } else {

            if ($roleData['active'] === 'n') {

                return true;
            }
        }

        $updateArray = $roleData;

        $currentUser = $this->
                authentication->
                GetUser();

        $old_eff_dttm = $updateArray['eff_dttm'];

        $eff_dttm = $this->
                database->
                Conn($this->
                        authentication->
                        GetConn())->
                Helper()->
                Select($this->
                        authentication->
                        GetPrefix() . 'role', '&CurrDTTM', array(
                    array(
                        'column' => 'role',
                        'operator' => '=',
                        'rightvalue' => $this->
                        role
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
                        GetPrefix() . 'roles', $updateArray)->
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

        $this->
                database->
                Conn($this->
                        authentication->
                        GetConn())->
                Helper()->
                Insert($this->
                        authentication->
                        GetPrefix() . 'roles', $this->
                        database->
                        Conn($this->
                                authentication->
                                GetConn())->
                        Helper()->
                        Select($this->
                                authentication->
                                GetPrefix() . 'roleperm', "role, '{$eff_dttm}', permission", array(
                            array(
                                'column' => 'role',
                                'operator' => '=',
                                'rightvalue' => $this->
                                role
                            ), array(
                                'column' => 'eff_dttm',
                                'operator' => '=',
                                'rightsubquery' => $old_eff_dttm
                            )
                                )
                        )
                )->
                Query();

        if ($this->
                        database->
                        Conn($this->
                                authentication->
                                GetConn())->
                        GetErrorCode()) {

            $error = $this->
                    database->
                    Conn($this->
                            authentication->
                            GetConn())->
                    GetError();

            $this->
                    database->
                    Conn($this->
                            authentication->
                            GetConn())->
                    Helper()->
                    Delete($this->
                            authentication->
                            GetPrefix() . 'roles', array(
                        array(
                            'column' => 'role',
                            'operator' => '=',
                            'rightvalue' => $this->
                            role
                        ),
                        array(
                            'column' => 'eff_dttm',
                            'operator' => '=',
                            'rightsubquery' => $eff_dttm
                        )
                            )
                    )->
                    Query();

            return new \FluitoPHP\Error\Error($error, 'enable_db_error');
        }

        $filterReturn = \FluitoPHP\Filters\Filters::GetInstance()->
                Run('FluitoPHP.Authorization.Role.EnableDisable', true, $this->
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
                            GetPrefix() . 'roleperm', array(
                        array(
                            'column' => 'role',
                            'operator' => '=',
                            'rightvalue' => $this->
                            role
                        ),
                        array(
                            'column' => 'eff_dttm',
                            'operator' => '=',
                            'rightsubquery' => $eff_dttm
                        )
                            )
                    )->
                    Query();

            $this->
                    database->
                    Conn($this->
                            authentication->
                            GetConn())->
                    Helper()->
                    Delete($this->
                            authentication->
                            GetPrefix() . 'roles', array(
                        array(
                            'column' => 'role',
                            'operator' => '=',
                            'rightvalue' => $this->
                            role
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
     * Used to enable this role.
     *
     * @return mixed Returns true if enabled else \FluitoPHP\Error\Error will be returned.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Enable() {

        return $this->
                        EnableDisable(true);
    }

    /**
     * Used to disable this role.
     *
     * @return mixed Returns true if disable else \FluitoPHP\Error\Error will be returned.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Disable() {

        return $this->
                        EnableDisable(false);
    }

}
