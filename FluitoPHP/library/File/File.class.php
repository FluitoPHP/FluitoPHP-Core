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

namespace FluitoPHP\File;

/**
 * File Class.
 *
 * Used for file handling.
 *
 * Variables:
 *      1. $filePath
 *      2. $mode
 *      3. $useIncludePath
 *      4. $fileObject
 *
 * Functions:
 *      1. __construct
 *      2. __destruct
 *      3. SetPath
 *      4. GetPath
 *      5. GetMode
 *      6. GetIncludePathSetting
 *      7. GetFileObject
 *      8. Seek
 *      9. Read
 *      10. Write
 *      11. Delete
 *      12. Size
 *
 * @author Vipin Jain
 * @since  0.1
 */
class File {

    /**
     * Used for storing file path.
     *
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    private $filePath = null;

    /**
     * Used for storing connection mode.
     *
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    private $mode = 'w';

    /**
     * Used for storing use include path setting.
     *
     * @var bool
     * @author Vipin Jain
     * @since  0.1
     */
    private $useIncludePath = false;

    /**
     * Used for storing file object.
     *
     * @var resource
     * @author Vipin Jain
     * @since  0.1
     */
    private $fileObject = null;

    /**
     * Used to initialize the file.
     *
     * @param array $args Provide parameters to the object.
     *                    'filePath' => Provide path of the file.
     *                    'mode' => Mode through which file is accessed.
     *                              Please visit: http://php.net/manual/en/function.fopen.php for possible modes.
     *                    'useIncludePath' => If we need to use the include path of the php.ini setting.
     * @throws \Exception Throws exception if the file is not found.
     * @author Vipin Jain
     * @since  0.1
     */
    public function __construct($args = []) {

        if (!isset($args['filePath']) || !is_string($args['filePath'])) {

            throw new \Exception("Error: File path is not provided.", 0);
        }

        $args['useIncludePath'] = isset($args['useIncludePath']) ? (bool) $args['useIncludePath'] : false;

        $args['mode'] = isset($args['mode']) ? $args['mode'] : 'a+';

        $this->
                SetPath($args['filePath'], $args['mode'], $args['useIncludePath']);
    }

    /**
     * Used to close the file resource.
     *
     * @author Vipin Jain
     * @since  0.1
     */
    public function __destruct() {

        if ($this->
                fileObject) {

            fclose($this->
                    fileObject);
        }
    }

    /**
     * Used to init or change the filepath.
     *
     * @param string $filePath Provide path of the file.
     * @param string $mode Mode through which file is accessed. Please visit: http://php.net/manual/en/function.fopen.php for possible modes.
     * @param string $useIncludePath If we need to use the include path of the php.ini setting.
     * @return $this Self reference is returned for chained calls.
     * @throws \Exception Throws exception if the file is not found.
     * @author Vipin Jain
     * @since  0.1
     */
    public function SetPath($filePath, $mode = 'a+', $useIncludePath = false) {

        if ($this->
                fileObject) {

            fclose($this->
                    fileObject);
        }

        $useIncludePath = (bool) $useIncludePath;

        $fileObject = fopen($filePath, $mode, $useIncludePath);

        if (!$fileObject) {

            throw new \Exception("Error: The path ({$filePath}) and mode ({$mode}) combination provided does not fetch a file.", 0);
        }

        $fileMeta = stream_get_meta_data($fileObject);

        $this->
                filePath = $fileMeta['uri'];
        $this->
                mode = $mode;
        $this->
                useIncludePath = $useIncludePath;
        $this->
                fileObject = $fileObject;

        return $this;
    }

    /**
     * Used to get the filepath.
     *
     * @return string
     * @author Vipin Jain
     * @since  0.1
     */
    public function GetPath() {

        return $this->
                filePath;
    }

    /**
     * Used to get the file connection mode.
     *
     * @return string
     * @author Vipin Jain
     * @since  0.1
     */
    public function GetMode() {

        return $this->
                mode;
    }

    /**
     * Used to get the setting to search in include_path.
     *
     * @return bool
     * @author Vipin Jain
     * @since  0.1
     */
    public function GetIncludePathSetting() {

        return $this->
                useIncludePath;
    }

    /**
     * used to get the file resource object.
     *
     * @return resource
     * @author Vipin Jain
     * @since  0.1
     */
    public function GetFileObject() {

        return $this->
                fileObject;
    }

    /**
     * Used to seek the file resource object pointer.
     *
     * @param int $offset The offset. To move to a position before the end-of-file, you need to pass a negative value in offset and set whence to SEEK_END.
     * @param int $whence Please visit: http://php.net/manual/en/function.fseek.php for possible values.
     * @return $this Self reference is returned for chained calls.
     * @throws \Exception Throws exception if the file is not initialized or is unable to seek the file.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Seek($offset, $whence = SEEK_SET) {

        if (!$this->
                fileObject) {

            throw new \Exception('Invalid call. Object not initialized yet.', 0);
        }


        if (fseek($this->
                        fileObject) === -1) {

            throw new \Exception('Unable to seek the file.', 0);
        }

        return $this;
    }

    /**
     * Used to read the file data.
     *
     * @param int $length Length of the characters to be read.
     * @return string Data being read is returned.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Read($length = null) {

        if (!is_int($length) ||
                $length <= 0) {

            $length = filesize($this->
                    filePath);
        }

        return fread($this->
                fileObject, $length);
    }

    /**
     * Used to write the file data.
     *
     * @param string $data Provide data string that needs to be written in the file.
     * @return $this Self reference is returned for chained calls.
     * @throws \Exception
     * @author Vipin Jain
     * @since  0.1
     */
    public function Write($data) {

        if (fwrite($this->
                        fileObject, $data) === false) {

            throw new \Exception('The file is not writable.', 0);
        }

        return $this;
    }

    /**
     * Used to delete the file from disk.
     *
     * @throws \Exception Throws exception if the file is not initialized or is unable to delete.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Delete() {

        if (!$this->
                fileObject) {

            throw new \Exception('Invalid call. Object not initialized yet.', 0);
        }

        fclose($this->
                fileObject);

        if (!unlink($this->
                        filePath)) {

            throw new \Exception("Unable to delete the file ({$this->
            filePath}).", 0);
        }
    }

    /**
     * Used to get the file size.
     *
     * @return int Returns the file size in bytes.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Size() {

        return filesize($this->
                filePath);
    }

}
