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
 * Authorization Class.
 *
 * This class is framework for authorization in your application.
 *
 * Variables:
 *      1. $instance
 *      2. $config
 *      3. $authentication
 *      4. $database
 *
 * Functions:
 *      1. __construct
 *      2. GetInstance
 *      3. UpdateConfig
 *      4. Upgrade
 *      5. GetUser
 *      6. GetRole
 *
 * @author Neha Jain
 * @since  0.1
 */
class Authorization {

    /**
     * Used for storing Singleton instance.
     *
     * @var \FluitoPHP\Authorization\Authorization
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
    private $config = array();

    /**
     * Used for storing authentication object.
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
     * Used to make this class as a singleton class.
     *
     * @author Neha Jain
     * @since  0.1
     */
    private function __construct() {

        $this->
                authentication = \FluitoPHP\Authentication\Authentication::GetInstance();

        $this->
                database = \FluitoPHP\Database\Database::GetInstance();

        require_once( dirname(__FILE__) . DS . 'UserRoles.class.php' );
        require_once( dirname(__FILE__) . DS . 'Role.class.php' );

        $appConfig = \FluitoPHP\FluitoPHP::GetInstance()->
                GetConfig('AUTHORIZATION');

        $appConfig = $appConfig ? $appConfig : [];

        $moduleConfig = \FluitoPHP\FluitoPHP::GetInstance()->
                GetModuleConfig('AUTHORIZATION');

        $moduleConfig = $moduleConfig ? $moduleConfig : [];

        $appConfig = array_replace_recursive($appConfig, $moduleConfig);

        $this->
                UpdateConfig($appConfig);
    }

    /**
     * Used to fetch the Instance object globally.
     *
     * @return \FluitoPHP\Authorization\Authorization Returns this instance object.
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
     * Used to update the configuration of the authorization.
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

        $this->
                Upgrade();
    }

    /**
     * Used to create the base tables for authentication.
     *
     * @author Neha Jain
     * @since  0.1
     */
    public function Upgrade() {

        if (!$this->
                        database->
                        Conn($this->
                                authentication->
                                GetConn())->
                        Helper()->
                        CheckTable($this->
                                authentication->
                                GetPrefix() . 'userroles')->
                        GetVar()) {

            $this->
                    database->
                    Conn($this->
                            authentication->
                            GetConn())->
                    Helper()->
                    CreateTable($this->
                            authentication->
                            GetPrefix() . 'roles', array(
                        'role' => array(
                            'type' => 'VARCHAR',
                            'length' => 128,
                            'isnull' => false,
                            'primary' => true
                        ),
                        'eff_dttm' => array(
                            'type' => 'DATETIME',
                            'isnull' => false,
                            'primary' => true
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
                    ))->
                    Query();

            $this->
                    database->
                    Conn($this->
                            authentication->
                            GetConn())->
                    Helper()->
                    CreateTable($this->
                            authentication->
                            GetPrefix() . 'roleperm', array(
                        'role' => array(
                            'type' => 'VARCHAR',
                            'length' => 128,
                            'isnull' => false
                        ),
                        'eff_dttm' => array(
                            'type' => 'DATETIME',
                            'isnull' => false
                        ),
                        'permission' => array(
                            'type' => 'VARCHAR',
                            'length' => 256,
                            'isnull' => false
                        )
                            ), array(
                        'fk_roles_roleperm' => array(
                            'indextype' => 'fk',
                            'columns' => array(
                                'role',
                                'eff_dttm'
                            ),
                            'referencetable' => $this->
                            authentication->
                            GetPrefix() . 'roles',
                            'referencecolumns' => array(
                                'role',
                                'eff_dttm'
                            )
                        )
                    ))->
                    Query();

            $this->
                    database->
                    Conn($this->
                            authentication->
                            GetConn())->
                    Helper()->
                    CreateTable($this->
                            authentication->
                            GetPrefix() . 'userroles', array(
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
                        'role' => array(
                            'type' => 'VARCHAR',
                            'length' => 128,
                            'isnull' => false,
                            'primary' => true
                        ),
                        'removed' => array(
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
                        'fk_userdefn_userroles' => array(
                            'indextype' => 'fk',
                            'columns' => array(
                                'user_id'
                            ),
                            'referencetable' => $this->
                            authentication->
                            GetPrefix() . 'userdefn',
                            'referencecolumns' => array(
                                'user_id'
                            )
                        ),
                        'fk_roles_userroles' => array(
                            'indextype' => 'fk',
                            'columns' => array(
                                'role'
                            ),
                            'referencetable' => $this->
                            authentication->
                            GetPrefix() . 'roles',
                            'referencecolumns' => array(
                                'role'
                            )
                        )
                    ))->
                    Query();

            $adminRole = new \FluitoPHP\Authorization\Role('administrator');

            if (!$adminRole->
                            GetRole()) {

                $adminRole->
                        SetRoleData(array(
                            'role' => 'administrator',
                            'permissions' => array(
                                'administrator'
                            )
                                ), true);
            }

            $userRole = new \FluitoPHP\Authorization\UserRoles(new \FluitoPHP\Authentication\User('administrator'));

            if (!in_array('administrator', $userRole->
                                    GetUserRoles())) {

                $userRole->
                        SetUserRoles(array(
                            'administrator'
                ));
            }

            \FluitoPHP\Events\Events::GetInstance()->
                    Run('FluitoPHP.Authorization.FirstRun', $this->
                            config, $this->
                            authentication);
        }

        \FluitoPHP\Events\Events::GetInstance()->
                Run('FluitoPHP.Authorization.Upgrade', $this->
                        config, $this->
                        authentication);
    }

    /**
     * Used to fetch the user with role details.
     *
     * @param string $user_login Provide user login/id to get the role details. Non string will lead to logged in user.
     * @return \FluitoPHP\Authorization\UserRoles Returns the user roles object.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetUser($user_login = null) {

        $user = null;

        if (!is_string($user_login) ||
                !strlen($user_login)) {

            $user = $this->
                    authentication->
                    GetLoggedInUser();

            if (!$user) {

                throw new Exception('Please login to access the application.');
            }
        } else {

            $user = new \FluitoPHP\Authentication\User($user_login);
        }

        if (!$user ||
                !$user->
                        GetUserID()) {

            throw new Exception('Please provide valid user_login.');
        }

        return new \FluitoPHP\Authorization\UserRoles($user);
    }

}
