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

namespace FluitoPHP\Session;

/**
 * Session Class.
 *
 * This is a helper class of session.
 *
 * Variables:
 *      1. $instance
 *      2. $flash
 *      3. $persistFlash
 *      4. $dbconns
 *      5. $initConfig
 *      6. $sessionConfigID
 *      7. $endSessionSuccess
 *
 * Functions:
 *      1. __construct
 *      2. GetInstance
 *      3. Open
 *      4. Close
 *      5. Read
 *      6. Write
 *      7. Destroy
 *      8. GCCall
 *      9. Encrypt
 *      10. Decrypt
 *      11. UpdateFlash
 *      12. EndSession
 *      13. DBCreate
 *      14. DBDestroy
 *      15. Get
 *      16. Set
 *      17. GetFlash
 *      18. SetFlash
 *      19. PersistFlash
 *
 * @author Neha Jain
 * @since  0.1
 */
class Session {

    /**
     * Used for storing Singleton instance.
     *
     * @var \FluitoPHP\Session\Session
     * @author Neha Jain
     * @since  0.1
     */
    static private $instance = null;

    /**
     * Used for storing Flash data.
     *
     * @var array
     * @author Neha Jain
     * @since  0.1
     */
    private $flash = [];

    /**
     * Used to store the items to persist in flash array.
     *
     * @var array
     * @author Neha Jain
     * @since  0.1
     */
    private $persistFlash = [];

    /**
     * Used for storing Database data.
     *
     * @var array
     * @author Neha Jain
     * @since  0.1
     */
    private $dbconns = [];

    /**
     * Used to store the initial configuration of session.
     *
     * @var array
     * @author Neha Jain
     * @since  0.1
     */
    private $initConfig = array(
        'name' => 'FluitoPHP_Session',
        'save_path' => '',
        'database' => '',
        'dbConnection' => '',
        'gc_probability' => 1,
        'gc_divisor' => 100,
        'gc_maxlifetime' => 1440,
        'referer_check' => '',
        'cookie_lifetime' => 0,
        'cookie_path' => '/',
        'cookie_domain' => '',
        'cookie_secure' => '',
        'cookie_httponly' => 'true',
        'use_cookies' => 1,
        'use_only_cookies' => 1,
        'encryptKey' => 'FluitoPHP_Session',
        'encryptCallback' => '',
        'decryptCallback' => '',
        'dataArray' => 'FluitoPHP_Data',
        'flashArray' => 'FluitoPHP_Flash',
        'dbArray' => 'FluitoPHP_Database',
        'dbconn' => ''
    );

    /**
     * Store session successful closure.
     *
     * @var bool
     * @author Neha Jain
     * @since  0.1
     */
    private $endSessionSuccess = false;

    /**
     * Private constructor to use this class as a singleton class.
     *
     * @author Neha Jain
     * @since  0.1
     */
    private function __construct() {

        if (session_id()) {

            session_write_close();
        }

        $config = \FluitoPHP\FluitoPHP::GetInstance()->
                GetConfig('SESSION');

        $config = $config ? $config : [];

        $moduleConfig = \FluitoPHP\FluitoPHP::GetInstance()->
                GetModuleConfig('SESSION');

        $moduleConfig = $moduleConfig ? $moduleConfig : [];

        $config = array_replace_recursive($config, $moduleConfig);

        $config = array_intersect_key($config, $this->
                initConfig);

        $this->
                initConfig = array_replace($this->
                initConfig, $config);

        $keys = array_keys($config);

        if (in_array('save_path_eval', $keys)) {

            $this->
                    initConfig['save_path'] = eval($this->
                    initConfig['save_path_eval']);

            $keys[] = 'save_path';
        }

        if (in_array('encryptCallback', $keys) &&
                ( is_callable($this->
                        initConfig['encryptCallback']) ||
                is_callable(json_decode($this->
                                initConfig['encryptCallback'])) )) {

            if (!is_callable($this->
                            initConfig['encryptCallback'])) {

                $this->
                        initConfig['encryptCallback'] = json_decode($this->
                        initConfig['encryptCallback']);
            }
        } else {

            $this->
                    initConfig['encryptCallback'] = array($this,
                'Encrypt');
        }

        if (in_array('decryptCallback', $keys) &&
                ( is_callable($this->
                        initConfig['decryptCallback']) ||
                is_callable(json_decode($this->
                                initConfig['decryptCallback'])) )) {

            if (!is_callable($this->
                            initConfig['decryptCallback'])) {

                $this->
                        initConfig['decryptCallback'] = json_decode($this->
                        initConfig['decryptCallback']);
            }
        } else {

            $this->
                    initConfig['decryptCallback'] = array($this,
                'Decrypt');
        }

        if ($this->
                initConfig['database'] == '' &&
                !in_array('save_path', $keys)) {

            $this->
                    initConfig['save_path'] = realpath(FRAMEWORK . DS . '..' . DS . 'temp' . DS . 'session');
        }

        if (!session_set_save_handler(array($this,
                    'Open'), array($this,
                    'Close'), array($this,
                    'Read'), array($this,
                    'Write'), array($this,
                    'Destroy'), array($this,
                    'GCCall'))) {

            trigger_error('Error: Unable to register the session handler', E_USER_ERROR);
        }

        if (ini_set('session.name', $this->
                        initConfig['name']) === false) {

            trigger_error('Error: Unable to register session name', E_USER_ERROR);
        }

        if (!$this->
                initConfig['database'] &&
                ini_set('session.save_path', $this->
                        initConfig['save_path']) === false) {

            trigger_error('Error: Unable to register session save path', E_USER_ERROR);
        }

        if (ini_set('session.gc_probability', $this->
                        initConfig['gc_probability']) === false) {

            trigger_error('Error: Unable to register session parameters', E_USER_ERROR);
        }

        if (ini_set('session.gc_divisor', $this->
                        initConfig['gc_divisor']) === false) {

            trigger_error('Error: Unable to register session parameters', E_USER_ERROR);
        }

        if (ini_set('session.gc_maxlifetime', $this->
                        initConfig['gc_maxlifetime']) === false) {

            trigger_error('Error: Unable to register session parameters', E_USER_ERROR);
        }

        if (ini_set('session.referer_check', $this->
                        initConfig['referer_check']) === false) {

            trigger_error('Error: Unable to register session parameters', E_USER_ERROR);
        }

        if (ini_set('session.cookie_lifetime', $this->
                        initConfig['cookie_lifetime']) === false) {

            trigger_error('Error: Unable to register session cookie parameters', E_USER_ERROR);
        }

        if (ini_set('session.cookie_path', $this->
                        initConfig['cookie_path']) === false) {

            trigger_error('Error: Unable to register session cookie parameters', E_USER_ERROR);
        }

        if (ini_set('session.cookie_domain', $this->
                        initConfig['cookie_domain']) === false) {

            trigger_error('Error: Unable to register session cookie parameters', E_USER_ERROR);
        }

        if (ini_set('session.cookie_secure', $this->
                        initConfig['cookie_secure']) === false) {

            trigger_error('Error: Unable to register session cookie parameters', E_USER_ERROR);
        }

        if (ini_set('session.cookie_httponly', $this->
                        initConfig['cookie_httponly']) === false) {

            trigger_error('Error: Unable to register session cookie parameters', E_USER_ERROR);
        }

        if (ini_set('session.use_cookies', $this->
                        initConfig['use_cookies']) === false) {

            trigger_error('Error: Unable to register session cookie parameters', E_USER_ERROR);
        }

        if (ini_set('session.use_only_cookies', $this->
                        initConfig['use_only_cookies']) === false) {

            trigger_error('Error: Unable to register session cookie parameters', E_USER_ERROR);
        }

        // Fix for session ID being regenerated in some PHP versions when
        // the INI setting session.name has been updated.

        $sessID = \FluitoPHP\FluitoPHP::GetInstance()->
                Request()->
                Cookie($this->
                initConfig['name']);

        session_id($sessID);

        if (!session_start()) {

            trigger_error('Error: Unable to start session', E_USER_ERROR);
        }

        $this->
                flash = isset($_SESSION[$this->
                        initConfig['flashArray']]) ? $_SESSION[$this->
                initConfig['flashArray']] : array();
        $this->
                dbconns = isset($_SESSION[$this->
                        initConfig['dbArray']]) ? $_SESSION[$this->
                initConfig['dbArray']] : array();
        $_SESSION = isset($_SESSION[$this->
                        initConfig['dataArray']]) ? $_SESSION[$this->
                initConfig['dataArray']] : array();

        foreach ($this->
        dbconns as $id => & $data) {

            FluitoPHP\Database\Database::GetInstance()->
                    CreateConns($id, $data);
        }

        \FluitoPHP\Events\Events::GetInstance()->
                Add('FluitoPHP.SystemShutdown', array($this,
                    'EndSession'), 0, 1);
    }

    /**
     * Used to fetch the singleton instance object.
     *
     * @return \FluitoPHP\Session\Session
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
     * Used to open the session.
     *
     * @param string $path Give the path of the session directory.
     * @param string $name Give session name.
     * @return bool returns if the opening of session is successful.
     * @author Neha Jain
     * @since  0.1
     */
    public function Open($path, $name) {

        if ($this->
                initConfig['database']) {

            if (!\FluitoPHP\Database\Database::GetInstance()->
                            Conn($this->
                                    initConfig['dbconn'])->
                            Helper()->
                            CheckTable($this->
                                    initConfig['database'])->
                            GetVar() &&
                    !\FluitoPHP\Database\Database::GetInstance()->
                            Conn($this->
                                    initConfig['dbconn'])->
                            Helper()->
                            CreateTable($this->
                                    initConfig['database'], array(
                                'id' => array(
                                    'type' => 'VARCHAR',
                                    'length' => 100,
                                    'isnull' => false,
                                    'primary' => true
                                ),
                                'data' => array(
                                    'type' => 'LONGTEXT',
                                    'isnull' => false
                                ),
                                'created' => array(
                                    'type' => 'DATETIME',
                                    'isnull' => false
                                ),
                                'updated' => array(
                                    'type' => 'DATETIME',
                                    'isnull' => false
                                ),
                                    )
                            )->
                            Query()
            ) {

                return false;
            }
        } else {
            if (!is_dir($path)) {

                if (!mkdir($path)) {

                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Used to close the open the session.
     *
     * @return bool Return true to successfully close the session.
     * @author Neha Jain
     * @since  0.1
     */
    public function Close() {

        return $this->
                endSessionSuccess;
    }

    /**
     * Used to read the session data.
     *
     * @param string $id Provide the Session ID here.
     * @return array Returns the array of data of that session.
     * @author Neha Jain
     * @since  0.1
     */
    public function Read($id) {

        $data = false;

        if ($this->
                initConfig['database']) {

            $data = (string) \FluitoPHP\Database\Database::GetInstance()->
                            Conn($this->
                                    initConfig['dbconn'])->
                            Helper()->
                            Select($this->
                                    initConfig['database'], 'data', array(
                                array(
                                    'column' => 'id',
                                    'operator' => '=',
                                    'rightvalue' => $id
                                )
                                    )
                            )->
                            GetVar();
        } else {

            $data = '';

            if (file_exists("{$this->
                            initConfig['save_path']}" . DS . "{$this->
                            initConfig['name']}_{$id}.sess")) {

                $data = (string) file_get_contents("{$this->
                                initConfig['save_path']}" . DS . "{$this->
                                initConfig['name']}_{$id}.sess");
            }
        }

        $data = call_user_func_array($this->
                initConfig['decryptCallback'], array($data,
            $this->
            initConfig['encryptKey']));

        return $data;
    }

    /**
     * Used to write the session data.
     *
     * @param string $id Provide the Session ID here.
     * @param array $data Provide the modified session data.
     * @return bool Returns true on success and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function Write($id, $data) {

        $data = call_user_func_array($this->
                initConfig['encryptCallback'], array(
            $data,
            $this->
            initConfig['encryptKey']
                )
        );

        if ($this->
                initConfig['database']) {

            if (\FluitoPHP\Database\Database::GetInstance()->
                            Conn($this->
                                    initConfig['dbconn'])->
                            Helper()->
                            Select($this->
                                    initConfig['database'], 'data', array(
                                array(
                                    'column' => 'id',
                                    'operator' => '=',
                                    'rightvalue' => $id
                                )
                                    )
                            )->
                            GetVar()
            ) {

                return \FluitoPHP\Database\Database::GetInstance()->
                                Conn($this->
                                        initConfig['dbconn'])->
                                Helper()->
                                Update($this->
                                        initConfig['database'], array(
                                    'data' => $data,
                                    'updated' => array(
                                        'function' => '&CurrDTTM'
                                    )
                                        ), array(
                                    array(
                                        'column' => 'id',
                                        'operator' => '=',
                                        'rightvalue' => $id
                                    )
                                        )
                                )->
                                Query();
            } else {

                return \FluitoPHP\Database\Database::GetInstance()->
                                Conn($this->
                                        initConfig['dbconn'])->
                                Helper()->
                                Insert($this->
                                        initConfig['database'], array(
                                    'id' => $id,
                                    'data' => $data,
                                    'created' => array('function' => '&CurrDTTM'),
                                    'updated' => array('function' => '&CurrDTTM')
                                        )
                                )->
                                Query();
            }
        } else {

            return file_put_contents("{$this->
                            initConfig['save_path']}" . DS . "{$this->
                            initConfig['name']}_{$id}.sess", $data) === false ? false : true;
        }
    }

    /**
     * Used to destroy the sessions as per required.
     *
     * @param sstring $id Provide the Session ID here to destroy.
     * @return bool Returns true on success and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function Destroy($id) {

        if ($this->
                initConfig['database']) {

            if (\FluitoPHP\Database\Database::GetInstance()->
                            Conn($this->
                                    initConfig['dbconn'])->
                            Helper()->
                            Delete($this->
                                    initConfig['database'], array(
                                array(
                                    'column' => 'id',
                                    'operator' => '=',
                                    'rightvalue' => $id
                                )
                                    )
                            )->
                            Query()
            ) {

                return false;
            }
        } else {

            if (file_exists("{$this->
                            initConfig['save_path']}" . DS . "{$this->
                            initConfig['name']}_{$id}.sess")) {

                if (!unlink("{$this->
                                initConfig['save_path']}" . DS . "{$this->
                                initConfig['name']}_{$id}.sess")) {

                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Used to garbage collect the sessions.
     *
     * @param int $maxlifetime Provide the maximum lifetime of a session.
     * @author Neha Jain
     * @since  0.1
     */
    public function GCCall($maxlifetime) {

        $destroyed = [];

        if ($this->
                initConfig['database']) {

            $destroyed = \FluitoPHP\Database\Database::GetInstance()->
                    Conn($this->
                            initConfig['dbconn'])->
                    Helper()->
                    Select($this->
                            initConfig['database'], '*', array(
                        array(
                            'column' => 'updated',
                            'operator' => '<',
                            'rightvalue' => "&DateSub(&CurrDTTM, {$maxlifetime}, S)"
                        )
                            )
                    )->
                    GetResults();

            for ($i = 0; $i < count($destroyed); $i++) {

                $destroyed[$i]['data'] = call_user_func_array($this->
                        initConfig['decryptCallback'], array($destroyed[$i]['data'],
                    $this->
                    initConfig['encryptKey']));
            }

            \FluitoPHP\Database\Database::GetInstance()->
                    Conn($this->
                            initConfig['dbconn'])->
                    Helper()->
                    Delete($this->
                            initConfig['database'], array(
                        array(
                            'column' => 'updated',
                            'operator' => '<',
                            'rightvalue' => "&DateSub(&CurrDTTM, {$maxlifetime}, S)"
                        )
                            )
                    )->
                    Query();
        } else {

            foreach (glob("{$this->
                    initConfig['save_path']}" . DS . "{$this->
                    initConfig['name']}_*.sess") as & $file) {

                if (filemtime($file) + $maxlifetime < time() && file_exists($file)) {

                    $destroyed[] = array('id' => substr(preg_replace('/' . preg_quote("{$this->
                                                initConfig['save_path']}" . DS . "{$this->
                                                initConfig['name']}_", '/') . '/', '', $file), 0, -5),
                        'data' => call_user_func_array($this->
                                initConfig['decryptCallback'], array(file_get_contents($file),
                            $this->
                            initConfig['encryptKey'])),
                        'created' => date('Y-m-d H:i:s', filectime($file)),
                        'updated' => date('Y-m-d H:i:s', filemtime($file))
                    );
                }
            }
        }

        $destroyed = \FluitoPHP\Filters\Filters::GetInstance()->
                Run('FluitoPHP.Session.GC', $destroyed);
    }

    /**
     * Used to encrypt the session data before storing.
     *
     * @param string $data Provide the decrypted session data.
     * @param string $key Provide the encryption key.
     * @return string Returns the encrypted session data.
     * @author Neha Jain
     * @since  0.1
     */
    private function Encrypt($data, $key) {

        return base64_encode($data);
    }

    /**
     * Used to decrypt the session data before storing.
     *
     * @param string $data Provide the encrypted session data.
     * @param string $key Provide the encryption key.
     * @return string Returns the decrypted session data.
     * @author Neha Jain
     * @since  0.1
     */
    private function Decrypt($data, $key) {

        return base64_decode($data);
    }

    /**
     * Used to remove the session flash which needs to be removed in this session.
     *
     * @author Neha Jain
     * @since  0.1
     */
    private function UpdateFlash() {

        if (is_array($this->
                        flash)) {

            $keys = array_keys($this->
                    flash);

            foreach ($keys as & $key) {

                if (!in_array($key, $this->
                                persistFlash)) {

                    unset($this->
                            flash[$key]);
                }
            }
        }
    }

    /**
     * Used to call session closing routines.
     *
     * @author Neha Jain
     * @since  0.1
     */
    public function EndSession() {

        $this->
                UpdateFlash();

        $temp = $_SESSION;
        $_SESSION = [];
        $_SESSION[$this->
                initConfig['dataArray']] = $temp;
        $_SESSION[$this->
                initConfig['flashArray']] = $this->
                flash;
        $_SESSION[$this->
                initConfig['dbArray']] = $this->
                dbconns;

        $this->
                endSessionSuccess = true;

        session_write_close();
    }

    /**
     * Used to create data for session database.
     *
     * @param string $id Provide the database identifier.
     * @param mixed $data Provide the connection data in array or object format.
     * @return bool Returns true on success and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function DBCreate($id, $data) {

        if (is_array($data)) {

            $data['autocommit'] = !(isset($data['autocommit']) &&
                    ucfirst($data['autocommit']) != '1');

            $data = (object) $data;
        }

        if (( is_string($id) ||
                is_int($id) ) &&
                        \FluitoPHP\Database\Database::GetInstance()->
                        CreateConns($id, $data)) {

            $this->
                    dbconns[$id] = $data;
            return true;
        }

        return false;
    }

    /**
     * Used to destroy data for session database.
     *
     * @param string $id Provide the database identifier.
     * @return bool Returns true on success and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function DBDestroy($id) {

        if (( is_string($id) ||
                is_int($id) ) &&
                isset($this->
                        dbconns[$id]) &&
                        \FluitoPHP\Database\Database::GetInstance()->
                        DestroyConns($id)) {

            $return = $this->
                    dbconns[$id];
            unset($this->
                    dbconns[$id]);
            return $return;
        }

        return false;
    }

    /**
     * Used to get session data for variable.
     *
     * @param string $id Provide the variable name for which data to be fetched.
     * @return mixed Returns false if variable not found else returns the data.
     * @author Neha Jain
     * @since  0.1
     */
    public function Get($id = null) {

        if (( is_string($id) ||
                is_int($id) ) &&
                isset($_SESSION[$id])) {

            return $_SESSION[$id];
        }

        return false;
    }

    /**
     * Used to set session data for variable.
     *
     * @param string $id Provide the variable name for which data to be stored.
     * @param mixed $data Provide the variable data.
     * @return bool Returns true on success and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function Set($id, $data = null) {

        if (is_string($id) ||
                is_int($id)) {

            $_SESSION[$id] = $data;
            return true;
        }

        return false;
    }

    /**
     * Used to get session flash data for variable.
     *
     * @param string $id Provide the variable name for which data to be fetched.
     * @return mixed Returns false if variable not found else returns the data.
     * @author Neha Jain
     * @since  0.1
     */
    public function GetFlash($id = null) {

        if (( is_string($id) ||
                is_int($id) ) &&
                isset($this->
                        flash[$id])) {

            return $this->
                    flash[$id];
        }

        return false;
    }

    /**
     * Used to set session flash data for variable.
     *
     * @param string $id Provide the variable name for which data to be stored.
     * @param mixed $data Provide the variable data.
     * @return bool Returns true on success and false on failure.
     * @author Neha Jain
     * @since  0.1
     */
    public function SetFlash($id, $data = null) {

        if (is_string($id) ||
                is_int($id)) {

            $this->
                    flash[$id] = $data;
            $this->
                    persistFlash[] = $id;
            return true;
        }

        return false;
    }

    /**
     * Used to persist session flash data for variable.
     *
     * @param string $id Provide the variable name for which data to be persisted.
     * @return bool Returns true on success and false if the variable is not found in the session flash data.
     * @author Neha Jain
     * @since  0.1
     */
    public function PersistFlash($id) {

        if (( is_string($id) ||
                is_int($id) ) &&
                isset($this->
                        flash[$id])) {

            $this->
                    persistflash[] = $id;
            return true;
        }

        return false;
    }

}
