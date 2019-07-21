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

namespace FluitoPHP\FileManager;

/**
 * Description of FileManager
 *
 * Constants:
 *      1. UPLOAD_NO_POST
 *      2. UPLOAD_INVALID_FILE_ID
 *      3. UPLOAD_FILE_ID_NOT_FOUND
 *      4. UPLOAD_FILE_MIME_NO_MATCH
 *      5. UPLOAD_FILE_EXCEEDS_MAX_SIZE
 *      6. UPLOAD_FILE_BELOW_MIN_SIZE
 *      7. UPLOAD_FILE_MIME_RESTRICTED
 *
 * Variables:
 *      1. $basePath
 *      2. $defaultPath
 *      3. $URLTrimPath
 *      4. $URLPrefix
 *      5. $thumbURLTrimPath
 *      6. $thumbURLPrefix
 *      7. $filesConfig
 *      8. $restrictedMimes
 *
 * Functions:
 *      1. __construct
 *      2. ListPath
 *      3. Upload
 *      4. MoveUploadedFile
 *      5. Delete
 *      6. DeleteDirectory
 *      7. Copy
 *      8. CopyDirectory
 *      9. Move
 *      10. Rename
 *      11. GetBasePath
 *      12. GetPathInfo
 *      13. GetBreadcrumbInfo
 *      14. CreateDirectory
 *
 * @author Vipin Jain
 * @since  0.1
 */
class FileManager {

    /**
     * Used when the Upload method is triggered and the request is not of POST type.
     *
     * @author Vipin Jain
     * @since  0.1
     */
    const UPLOAD_NO_POST = 100;

    /**
     * Used when an invalid identifier is provided to the Upload method.
     *
     * @author Vipin Jain
     * @since  0.1
     */
    const UPLOAD_INVALID_FILE_ID = 101;

    /**
     * Used when the uploaded file with identifier is not found.
     *
     * @author Vipin Jain
     * @since  0.1
     */
    const UPLOAD_FILE_ID_NOT_FOUND = 102;

    /**
     * Used when file mime type do not match in mime types provided to the Upload method.
     *
     * @author Vipin Jain
     * @since  0.1
     */
    const UPLOAD_FILE_MIME_NO_MATCH = 103;

    /**
     * Used when file size is greater than max size provided to the Upload method.
     *
     * @author Vipin Jain
     * @since  0.1
     */
    const UPLOAD_FILE_EXCEEDS_MAX_SIZE = 104;

    /**
     * Used when file size is smaller than min size provided to the Upload method.
     *
     * @author Vipin Jain
     * @since  0.1
     */
    const UPLOAD_FILE_BELOW_MIN_SIZE = 105;

    /**
     * Used when the mime of the file matches restricted mime types.
     *
     * @author Vipin Jain
     * @since  0.1
     */
    const UPLOAD_FILE_MIME_RESTRICTED = 106;

    /**
     * Used to store the base path of the directory.
     *
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    protected $basePath = null;

    /**
     * Used to store the default path and used if the path provided to the constructor is not valid.
     *
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    protected $defaultPath = null;

    /**
     * Used to trim the path of the file for URL Generation.
     *
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    protected $URLTrimPath = '';

    /**
     * Used to append to the path of the file for URL Generation.
     *
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    protected $URLPrefix = '';

    /**
     * Used to trim the path of the file for thumbnail URL Generation.
     *
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    protected $thumbURLTrimPath = '';

    /**
     * Used to append to the path of the file for thumbnail URL Generation.
     *
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    protected $thumbURLPrefix = '';

    /**
     * Used to store the files configuration.
     *
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    static protected $filesConfig = null;

    /**
     * Used to store the restricted mime types.
     *
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    static protected $restrictedMimes = array(
        'application/x-msdownload',
        'application/x-dosexec'
    );

    /**
     * Used to initialize the class.
     *
     * @param array $config Provide parameters for the manager in below format.
     *              'basePath' Provide the base path of the manager or else provide the array containing the same key elements.
     *              'URLTrimPath' Provide the URL trim path.
     *              'URLPrefix' Provide the URL prefix.
     *              'thumbURLTrimPath' Provide the thumbnail URL trim path.
     *              'thumbURLPrefix' Provide the thumbnail URL prefix.
     * @throws \Exception
     * @author Vipin Jain
     * @since  0.1
     */
    public function __construct($config = array()) {

        $defaultConfig = array(
            'basePath' => null,
            'URLTrimPath' => null,
            'URLPrefix' => null,
            'thumbURLTrimPath' => null,
            'thumbURLPrefix' => null
        );

        $config = array_replace($defaultConfig, $config);

        $this->
                defaultPath = constant('ABSOLUTE') . DS . 'files';

        if (strlen($config['basePath']) > 0 &&
                is_dir($config['basePath'])) {

            $this->
                    basePath = realpath($config['basePath']);
        } else if (is_dir($this->
                        defaultPath)) {

            $this->
                    basePath = $this->
                    defaultPath;
        } else {

            throw new \Exception("Error: The FileManager path is not valid.");
        }

        if (!is_string($config['URLTrimPath']) ||
                !is_dir(realpath($config['URLTrimPath']))) {

            if (substr($this->
                            basePath, 0, strlen(constant('ABSOLUTE') . DS)) === constant('ABSOLUTE') . DS) {

                $this->
                        URLTrimPath = constant('ABSOLUTE') . DS;
            } else {

                $this->
                        URLTrimPath = $this->
                        basePath . DS;
            }
        } else {

            $this->
                    URLTrimPath = $config['URLTrimPath'];
        }

        if (!is_string($config['URLPrefix'])) {

            $this->
                    URLPrefix = '';
        } else {

            $this->
                    URLPrefix = $config['URLPrefix'];
        }

        if (!is_string($config['thumbURLTrimPath']) ||
                !is_dir(realpath($config['thumbURLTrimPath']))) {

            $this->
                    thumbURLTrimPath = $this->
                    basePath . DS;
        } else {

            $this->
                    thumbURLTrimPath = $config['thumbURLTrimPath'];
        }

        if (!is_string($config['thumbURLPrefix'])) {

            $this->
                    thumbURLPrefix = '';
        } else {

            $this->
                    thumbURLPrefix = $config['thumbURLPrefix'];
        }

        if (self::$filesConfig === null) {

            self::$filesConfig = \FluitoPHP\FluitoPHP::GetInstance()->
                    GetConfig("FILES");

            self::$filesConfig = self::$filesConfig ? self::$filesConfig : [];

            $moduleConfig = \FluitoPHP\FluitoPHP::GetInstance()->
                    GetModuleConfig('FILES');

            $moduleConfig = $moduleConfig ? $moduleConfig : [];

            self::$filesConfig = array_replace_recursive(self::$filesConfig, $moduleConfig);

            if (isset(self::$filesConfig['restrictedmimes']) &&
                    is_array(self::$filesConfig['restrictedmimes'])) {

                self::$restrictedMimes = array_merge(self::$restrictedMimes, self::$filesConfig['restrictedmimes']);
            }
        }
    }

    /**
     * Used to list contents of a directory.
     *
     * @param string $subPath Provide the relative path from the $basePath.
     * @param array $extensions Provide the list of extensions for filtering.
     * @param bool $listDirectories Provide true if the subdirectories needs to be listed.
     * @param bool $showHidden Provide true if the hidden files and directories (i.e. starting with .) needs to be listed.
     * @return array Returns the list of files and folders in a subpath.
     * @author Vipin Jain
     * @since  0.1
     */
    public function ListPath($subPath = '', $extensions = [], $listDirectories = true, $showHidden = false) {

        if (!is_string($subPath)) {

            $subPath = '';
        }

        if (substr($subPath, 0, 1) === "/" ||
                substr($subPath, 0, 1) === "\\") {

            $subPath = substr($subPath, 1);
        }

        $path = realpath($this->
                basePath . DS . $subPath);

        $returnList = [];

        if (substr($path, 0, strlen($this->
                                basePath)) === $this->
                basePath &&
                is_dir($path)) {

            $pathResource = opendir($path);

            while (false !== ($listing = readdir($pathResource))) {

                if ($listing == "." ||
                        $listing == ".." ||
                        (!$showHidden &&
                        substr($listing, 0, 1) === ".")) {

                    continue;
                }

                $listingAttr = [];

                if (is_dir($path . DS . $listing)) {

                    if ($listDirectories) {

                        $listingAttr = $this->GetPathInfo(substr($path . DS . $listing, strlen($this->
                                                basePath . DS)), $extensions, $listDirectories, $showHidden);
                    }
                } else if (!(is_array($extensions) &&
                        count($extensions)) ||
                        in_array($listingInfo['extension'], $extensions)) {

                    $listingAttr = $this->GetPathInfo(substr($path . DS . $listing, strlen($this->
                                            basePath . DS)), $extensions, $listDirectories, $showHidden);
                }

                if (isset($listingAttr['path'])) {

                    $returnList[] = $listingAttr;
                }
            }
        }

        return $returnList;
    }

    /**
     * Used to save the uploaded file.
     *
     * @param string $id Provide the identifier of the file being uploaded.
     * @param string $name Provide a different name for the uploaded file.
     * @param string $subPath Provide the subpath where the file needs to be uploaded.
     * @param array $validMimes Provide the list of valid mime types.
     * @param int $maxSize Provide the maximum size of the file.
     * @param int $minSize Provide the minimum size of the file.
     * @return mixed Returns name of file on success else returns failure code. And returns failure code or array of names/errors.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Upload($id, $name = '', $subPath = '', $validMimes = [], $maxSize = 0, $minSize = 0) {

        $request = \FluitoPHP\Request\Request::GetInstance();

        if (!$request->
                        IsPost()) {

            return self::UPLOAD_NO_POST;
        }

        if (!is_string($id) ||
                !strlen($id)) {

            return self::UPLOAD_INVALID_FILE_ID;
        }

        $file = $request->
                Files($id);

        if (!$file) {

            return self::UPLOAD_FILE_ID_NOT_FOUND;
        }

        $return = null;

        if (is_array($file['name'])) {

            $return = [];
            $names = [];

            foreach ($file['name'] as $key => $value) {

                $return[$key] = null;

                if ($file['error'][$key] !== UPLOAD_ERR_OK) {

                    $return[$key] = $file['error'][$key];
                }

                if (count($validMimes) > 0 &&
                        !in_array($file['type'][$key], $validMimes)) {

                    $return[$key] = self::UPLOAD_FILE_MIME_NO_MATCH;
                }

                if (count(self::$restrictedMimes) > 0 &&
                        in_array($file['type'][$key], self::$restrictedMimes)) {

                    $return[$key] = self::UPLOAD_FILE_MIME_RESTRICTED;
                }

                if ($maxSize > 0 &&
                        $file['size'][$key] > $maxSize) {

                    $return[$key] = self::UPLOAD_FILE_EXCEEDS_MAX_SIZE;
                }

                if ($minSize > 0 &&
                        $file['size'][$key] < $minSize) {

                    $return[$key] = self::UPLOAD_FILE_BELOW_MIN_SIZE;
                }

                if ($return[$key] !== null) {

                    continue;
                }

                $names[$key] = (!is_string($name) ||
                        !strlen($name)) ? $file['name'][$key] : $name;

                $return[$key] = $this->MoveUploadedFile($file['tmp_name'][$key], $names[$key], $subPath);
            }
        } else {

            if ($file['error'] !== UPLOAD_ERR_OK) {

                return $file['error'];
            }

            if (count($validMimes) > 0 &&
                    !in_array($file['type'], $validMimes)) {

                return self::UPLOAD_FILE_MIME_NO_MATCH;
            }

            if (count(self::$restrictedMimes) > 0 &&
                    in_array($file['type'], self::$restrictedMimes)) {

                return self::UPLOAD_FILE_MIME_RESTRICTED;
            }

            if ($maxSize > 0 &&
                    $file['size'] > $maxSize) {

                return self::UPLOAD_FILE_EXCEEDS_MAX_SIZE;
            }

            if ($minSize > 0 &&
                    $file['size'] < $minSize) {

                return self::UPLOAD_FILE_BELOW_MIN_SIZE;
            }

            if (!is_string($name) ||
                    !strlen($name)) {

                $name = $file['name'];
            }

            $return = $this->
                    MoveUploadedFile($file['tmp_name'], $name, $subPath);
        }

        return $return;
    }

    /**
     * Used to move the uploaded the file.
     *
     * @param string $tmpFileName Provide the temp file name of uploaded file.
     * @param string $saveName Provide the name for the file to save.
     * @param string $subPath Provide the subpath to save the file is required.
     * @return mixed Returns generated filename on success and false on failure.
     * @author Vipin Jain
     * @since  0.1
     */
    protected function MoveUploadedFile($tmpFileName, $saveName, $subPath = '') {

        if (!file_exists($tmpFileName)) {

            return false;
        }

        if (!is_string($subPath)) {

            $subPath = '';
        }

        if (substr($subPath, 0, 1) === "/" ||
                substr($subPath, 0, 1) === "\\") {

            $subPath = substr($subPath, 1);
        }

        $path = realpath($this->
                basePath . DS . $subPath);

        if (!strlen($path)) {

            mkdir($this->
                    basePath . DS . $subPath, 0777, true);

            $path = realpath($this->
                    basePath . DS . $subPath);
        }

        if (substr($path, 0, strlen($this->
                                basePath)) !== $this->
                basePath) {

            $subPath = "";

            $path = realpath($this->
                    basePath . DS . $subPath);
        }

        $saveName = preg_replace("~[\\\\\\/\\:\\*\\?\\\"\\<\\>\\|\\0]~i", "", $saveName);

        $extn = pathinfo($saveName, PATHINFO_EXTENSION);

        if (strlen($extn)) {

            $saveName = substr($saveName, 0, -(strlen($extn) + 1));
        }

        $returnSaveName = $saveName . ".{$extn}";
        $counter = 0;

        if (file_exists($path . DS . $returnSaveName)) {

            $counter++;

            while (file_exists($path . DS . $saveName . "_{$counter}" . ".{$extn}")) {

                $counter++;
            }
        }

        if ($counter > 0) {

            $returnSaveName = $saveName . "_{$counter}" . ".{$extn}";
        }

        while (true) {

            if (move_uploaded_file($tmpFileName, $path . DS . $returnSaveName)) {

                return array(
                    'filename' => $returnSaveName,
                    'filepath' => substr($path . DS . $returnSaveName, strlen($this->
                                    basePath . DS))
                );
            }

            if (!file_exists($path . DS . $returnSaveName)) {

                return false;
            }

            $counter++;

            $returnSaveName = $saveName . "_{$counter}" . ".{$extn}";
        }

        return false;
    }

    /**
     * Used to delete files.
     *
     * @param mixed $files Provide the filepath or array of file paths.
     * @return mixed Returns true on success and false on failure or in case of multiple files, array of bool values is returned with respective keys from files array.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Delete($files) {

        $return = false;

        if (is_array($files) &&
                count($files)) {

            $return = [];

            foreach ($files as $key => $file) {

                $return[$key] = false;

                if (substr(realpath($this->
                                        basePath . DS . $file), 0, strlen($this->
                                        basePath)) === $this->
                        basePath &&
                        file_exists(realpath($this->
                                        basePath . DS . $file))) {

                    if (is_dir(realpath($this->
                                            basePath . DS . $file)) &&
                            realpath($this->
                                    basePath . DS . $file) !== $this->
                            basePath) {

                        $return[$key] = $this->
                                DeleteDirectory(realpath($this->
                                        basePath . DS . $file));
                    } else {

                        $return[$key] = unlink(realpath($this->
                                        basePath . DS . $file));
                    }
                }
            }
        } else if (is_string($files) &&
                substr(realpath($this->
                                basePath . DS . $files), 0, strlen($this->
                                basePath)) === $this->
                basePath &&
                file_exists(realpath($this->
                                basePath . DS . $files)) &&
                realpath($this->
                        basePath . DS . $files) !== $this->
                basePath) {

            if (is_dir(realpath($this->
                                    basePath . DS . $files))) {

                $return = $this->
                        DeleteDirectory(realpath($this->
                                basePath . DS . $files));
            } else {

                $return = unlink(realpath($this->
                                basePath . DS . $files));
            }
        }

        return $return;
    }

    /**
     * Used to delete a directory recursively.
     *
     * @param string $folder Provide the folder path.
     * @return bool Returns true on success and false on failure.
     * @author Vipin Jain
     * @since  0.1
     */
    protected function DeleteDirectory($folder) {

        $folderResource = opendir($folder);

        while (false !== ($file = readdir($folderResource))) {

            if ($file != '.' &&
                    $file != '..') {

                if (is_dir($folder . DS . $file)) {

                    if (!$this->
                                    DeleteDirectory($folder . DS . $file)) {

                        return false;
                    }
                } else {

                    if (!unlink($folder . DS . $file)) {

                        return false;
                    }
                }
            }
        }

        closedir($folderResource);

        return rmdir($folder);
    }

    /**
     * Used to copy files.
     *
     * @param mixed $files Provide the filepath or array of file paths to be copied.
     * @param string $subPath Provide the subpath where the files needs to be copied.
     * @return mixed Returns true on success and false on failure or in case of multiple files, array of bool values is returned with respective keys from files array.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Copy($files, $subPath) {

        $return = false;

        if (!is_string($subPath)) {

            return $return;
        }

        if (substr($subPath, 0, 1) === "/" ||
                substr($subPath, 0, 1) === "\\") {

            $subPath = substr($subPath, 1);
        }

        $path = realpath($this->
                basePath . DS . $subPath);

        if (!strlen($path)) {

            mkdir($this->
                    basePath . DS . $subPath, 0777, true);

            $path = realpath($this->
                    basePath . DS . $subPath);
        }

        if (is_array($files) &&
                count($files)) {

            $return = [];

            foreach ($files as $key => $file) {

                $return[$key] = false;

                if (is_string($file) &&
                        substr(realpath($this->
                                        basePath . DS . $file), 0, strlen($this->
                                        basePath)) === $this->
                        basePath &&
                        file_exists(realpath($this->
                                        basePath . DS . $file))) {

                    $fileInfo = pathinfo(realpath($this->
                                    basePath . DS . $file));

                    $newFileName = $fileInfo['filename'];

                    $newFileExtn = isset($fileInfo['extension']) ? $fileInfo['extension'] : null;

                    $counter = 0;

                    $newFilepath = $path . DS . "{$newFileName}" . ($newFileExtn !== null ? ".{$newFileExtn}" : "");

                    while (file_exists($newFilepath)) {

                        $counter++;
                        $newFilepath = $path . DS . "{$newFileName}_{$counter}" . ($newFileExtn !== null ? ".{$newFileExtn}" : "");
                    }

                    if (is_dir(realpath($this->
                                            basePath . DS . $file))) {

                        $return[$key] = $this->
                                CopyDirectory(realpath($this->
                                        basePath . DS . $file), $newFilepath);
                    } else {

                        $return[$key] = copy(realpath($this->
                                        basePath . DS . $file), $newFilepath);
                    }
                }
            }
        } else if (is_string($files) &&
                substr(realpath($this->
                                basePath . DS . $files), 0, strlen($this->
                                basePath)) === $this->
                basePath &&
                file_exists(realpath($this->
                                basePath . DS . $files))) {

            $fileInfo = pathinfo(realpath($this->
                            basePath . DS . $files));

            $newFileName = $fileInfo['filename'];

            $newFileExtn = isset($fileInfo['extension']) ? $fileInfo['extension'] : null;

            $counter = 0;

            $newFilepath = $path . DS . "{$newFileName}" . ($newFileExtn !== null ? ".{$newFileExtn}" : "");

            while (file_exists($newFilepath)) {

                $counter++;
                $newFilepath = $path . DS . "{$newFileName}_{$counter}" . ($newFileExtn !== null ? ".{$newFileExtn}" : "");
            }

            if (is_dir(realpath($this->
                                    basePath . DS . $files))) {

                $return = $this->
                        CopyDirectory(realpath($this->
                                basePath . DS . $files), $newFilepath);
            } else {

                $return = copy(realpath($this->
                                basePath . DS . $files), $newFilepath);
            }
        }

        return $return;
    }

    /**
     * Used to copy a directory recursively.
     *
     * @param string $source Provide the source path to copy.
     * @param string $destination Provide the destination path.
     * @return bool Returns true on success and false on failure.
     * @author Vipin Jain
     * @since  0.1
     */
    protected function CopyDirectory($source, $destination) {

        $sourceResource = opendir($source);

        if (!mkdir($destination)) {

            return false;
        }

        while (false !== ($file = readdir($sourceResource))) {

            if ($file != '.' &&
                    $file != '..') {

                if (is_dir($source . DS . $file)) {

                    if (!$this->
                                    CopyDirectory($source . DS . $file, $destination . DS . $file)) {

                        return false;
                    }
                } else {

                    if (!copy($source . DS . $file, $destination . DS . $file)) {

                        return false;
                    }
                }
            }
        }

        closedir($sourceResource);

        return true;
    }

    /**
     * Used to move files.
     *
     * @param mixed $files Provide the filepath or array of file paths to be moved.
     * @param string $subPath Provide the subpath where the files needs to be moved.
     * @return mixed Returns true on success and false on failure or in case of multiple files, array of bool values is returned with respective keys from files array.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Move($files, $subPath) {

        $return = false;

        if (!is_string($subPath)) {

            return $return;
        }

        if (substr($subPath, 0, 1) === "/" ||
                substr($subPath, 0, 1) === "\\") {

            $subPath = substr($subPath, 1);
        }

        $path = realpath($this->
                basePath . DS . $subPath);

        if (!strlen($path)) {

            mkdir($this->
                    basePath . DS . $subPath, 0777, true);

            $path = realpath($this->
                    basePath . DS . $subPath);
        }

        if (is_array($files) &&
                count($files)) {

            $return = [];

            foreach ($files as $key => $file) {

                $return[$key] = false;

                if (is_string($file) &&
                        substr(realpath($this->
                                        basePath . DS . $file), 0, strlen($this->
                                        basePath)) === $this->
                        basePath &&
                        file_exists(realpath($this->
                                        basePath . DS . $file))) {

                    $fileInfo = pathinfo(realpath($this->
                                    basePath . DS . $file));

                    $newFileName = $fileInfo['filename'];

                    $newFileExtn = isset($fileInfo['extension']) ? $fileInfo['extension'] : null;

                    $counter = 0;

                    $newFilepath = $path . DS . "{$newFileName}" . ($newFileExtn !== null ? ".{$newFileExtn}" : "");

                    if (realpath($this->
                                    basePath . DS . $file) === $newFilepath) {

                        $return[$key] = false;
                    } else {

                        while (file_exists($newFilepath)) {

                            $counter++;
                            $newFilepath = $path . DS . "{$newFileName}_{$counter}" . ($newFileExtn !== null ? ".{$newFileExtn}" : "");
                        }

                        $return[$key] = rename(realpath($this->
                                        basePath . DS . $file), $newFilepath);
                    }
                }
            }
        } else if (is_string($files) &&
                substr(realpath($this->
                                basePath . DS . $files), 0, strlen($this->
                                basePath)) === $this->
                basePath &&
                file_exists(realpath($this->
                                basePath . DS . $files))) {

            $fileInfo = pathinfo(realpath($this->
                            basePath . DS . $files));

            $newFileName = $fileInfo['filename'];

            $newFileExtn = isset($fileInfo['extension']) ? $fileInfo['extension'] : null;

            $counter = 0;

            $newFilepath = $path . DS . "{$newFileName}" . ($newFileExtn !== null ? ".{$newFileExtn}" : "");

            if (realpath($this->
                            basePath . DS . $files) === $newFilepath) {

                $return = false;
            } else {

                while (file_exists($newFilepath)) {

                    $counter++;
                    $newFilepath = $path . DS . "{$newFileName}_{$counter}" . ($newFileExtn !== null ? ".{$newFileExtn}" : "");
                }

                $return = rename(realpath($this->
                                basePath . DS . $files), $newFilepath);
            }
        }

        return $return;
    }

    /**
     * Used to rename a file.
     *
     * @param mixed $file Provide the filepath file to be renamed.
     * @param string $newName Provide the new name of the file.
     * @param bool $changeExtn Provide false if you do not want the extension of the file to be updated.
     * @return mixed Returns true on success and false on failure.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Rename($file, $newName, $changeExtn = false) {

        if (!is_string($file) ||
                !file_exists(realpath($this->
                                basePath . DS . $file))) {

            return false;
        }

        $fileInfo = pathinfo(realpath($this->
                        basePath . DS . $file));

        if (isset($fileInfo['extension'])) {

            $fileInfo['extension'] = false;
        }

        $newFileInfo = pathinfo($newName);

        if (isset($newFileInfo['extension'])) {

            $newFileInfo['extension'] = false;
        }

        if (!$changeExtn &&
                !is_dir(realpath($this->
                                basePath . DS . $file)) &&
                $fileInfo['extension'] !== $newFileInfo['extension']) {

            return false;
        }

        $newFileName = $fileInfo['dirname'] . DS . $newName;

        if (file_exists($newFileName)) {

            return false;
        }

        return rename(realpath($this->
                        basePath . DS . $file), $newFileName);
    }

    /**
     * Used to get the base path of the file manager.
     *
     * @return string Returns the base path from the file manager.
     * @author Vipin Jain
     * @since  0.1
     */
    public function GetBasePath() {

        return $this->
                basePath . DS;
    }

    /**
     * Used to get list of attributes for a given subpath.
     *
     * @param string $subPath Provide the relative path from the $basePath.
     * @param array $extensions Provide the list of extensions for filtering.
     * @param bool $listDirectories Provide true if the subdirectories needs to be listed.
     * @param bool $showHidden Provide true if the hidden files and directories (i.e. starting with .) needs to be listed.
     * @return array Returns the list of attributes of the subpath else returns false on failure.
     * @author Vipin Jain
     * @since  0.1
     */
    public function GetPathInfo($subPath = '', $extensions = [], $listDirectories = true, $showHidden = false) {

        $return = false;

        if (!is_string($subPath)) {

            $subPath = '';
        }

        if (substr($subPath, 0, 1) === "/" ||
                substr($subPath, 0, 1) === "\\") {

            $subPath = substr($subPath, 1);
        }

        if (substr(realpath($this->
                                basePath . DS . $subPath), 0, strlen($this->
                                basePath)) !== $this->
                basePath) {

            return $return;
        }

        $path = realpath($this->
                basePath . DS . $subPath);

        $return = [];

        $pathInfo = pathinfo($path);

        if (!isset($pathInfo['extension'])) {

            $pathInfo['extension'] = false;
        }

        $return['name'] = $pathInfo['filename'];
        $return['basename'] = $pathInfo['basename'];
        $return['extension'] = $pathInfo['extension'];
        $return['path'] = $path !== $this->
                basePath ? substr($path, strlen($this->
                                basePath . DS)) : '';
        $return['url'] = \FluitoPHP\Request\Request::GetInstance()->
                URL(str_replace(DS, "/", $this->
                        URLPrefix . (substr($path, 0, strlen($this->
                                        URLTrimPath)) === $this->
                        URLTrimPath ? substr($path, strlen($this->
                                        URLTrimPath)) : $path)));
        $return['thumburl'] = '';
        $return['isdir'] = false;
        $return['size'] = filesize($path);
        $return['write'] = is_writable($path);
        $return['read'] = is_readable($path);
        $return['mime'] = mime_content_type($path);
        $return['imagesizex'] = 0;
        $return['imagesizey'] = 0;
        $return['contains'] = 0;
        $return['containsall'] = 0;
        $return['folders'] = 0;
        $return['files'] = 0;

        if (is_dir($path)) {

            $return['isdir'] = true;
            $pathResource = opendir($path);

            while (false !== ($pathContent = readdir($pathResource))) {


                if ($pathContent == "." ||
                        $pathContent == ".." ||
                        (!$showHidden &&
                        substr($pathContent, 0, 1) === ".")) {

                    continue;
                }

                $return['containsall'] ++;

                $pathContentInfo = pathinfo($path . DS . $pathContent, PATHINFO_EXTENSION);

                if (is_dir($path . DS . $pathContent)) {

                    if ($listDirectories) {

                        $return['contains'] ++;
                        $return['folders'] ++;
                    }
                } else if (!(is_array($extensions) &&
                        count($extensions)) ||
                        in_array($pathContentInfo, $extensions)) {

                    $return['contains'] ++;
                    $return['files'] ++;
                }
            }
        } else if (substr($return['mime'], 0, 5) === 'image') {

            $imageObject = new \FluitoPHP\ImageFile\ImageFile($path);

            $return['imagesizex'] = $imageObject->
                    GetX();
            $return['imagesizey'] = $imageObject->
                    GetY();
            $return['thumburl'] = \FluitoPHP\Request\Request::GetInstance()->
                    URL(str_replace(DS, "/", $this->
                            thumbURLPrefix . (substr($path, 0, strlen($this->
                                            thumbURLTrimPath)) === $this->
                            thumbURLTrimPath ? substr($path, strlen($this->
                                            thumbURLTrimPath)) : $path)));
        } else {

            //Can be used to get additional results.
        }

        return $return;
    }

    /**
     * Used to get list of breadcrumbs for a given subpath.
     *
     * @param string $subPath Provide the relative path from the $basePath.
     * @param array $extensions Provide the list of extensions for filtering.
     * @param bool $listDirectories Provide true if the subdirectories needs to be listed.
     * @param bool $showHidden Provide true if the hidden files and directories (i.e. starting with .) needs to be listed.
     * @return array Returns the list of breadcrumbs to current subpath.
     * @author Vipin Jain
     * @since  0.1
     */
    public function GetBreadcrumbInfo($subPath = '', $extensions = [], $listDirectories = true, $showHidden = false) {

        $return = [];

        if (!is_string($subPath)) {

            $subPath = '';
        }

        if (substr($subPath, 0, 1) === "/" ||
                substr($subPath, 0, 1) === "\\") {

            $subPath = substr($subPath, 1);
        }

        if (substr(realpath($this->
                                basePath . DS . $subPath), 0, strlen($this->
                                basePath)) !== $this->
                basePath) {

            return $return;
        }

        $path = realpath($this->
                basePath . DS . $subPath);

        while ($path !== $this->
        basePath) {

            $currentInfo = $this->GetPathInfo(substr(realpath($path), strlen($this->
                                    basePath . DS)), $extensions, $listDirectories, $showHidden);

            array_unshift($return, $currentInfo);

            $path = dirname($path);
        }

        $currentInfo = $this->GetPathInfo("", $extensions, $listDirectories, $showHidden);

        array_unshift($return, $currentInfo);

        return $return;
    }

    /**
     * Used to create a new directory.
     *
     * @param string $directoryName Provide the name of the directory that needs to be created.
     * @param string $subPath Provide the subpath where the directory needs to be created.
     * @return bool Returns directory name on success and false on failure.
     * @author Vipin Jain
     * @since  0.1
     */
    public function CreateDirectory($directoryName, $subPath = '') {

        if (!is_string($directoryName)) {

            return false;
        }

        if (!is_string($subPath)) {

            $subPath = '';
        }

        if (substr($subPath, 0, 1) === "/" ||
                substr($subPath, 0, 1) === "\\") {

            $subPath = substr($subPath, 1);
        }

        if (substr(realpath($this->
                                basePath . DS . $subPath), 0, strlen($this->
                                basePath)) !== $this->
                basePath) {

            return false;
        }

        $path = realpath($this->
                basePath . DS . $subPath);

        if (!is_dir($path)) {

            return false;
        }

        $directoryName = preg_replace("~[\\\\\\/\\:\\*\\?\\\"\\<\\>\\|\\0]~i", "", $directoryName);

        if (!strlen($directoryName)) {

            return false;
        }

        $newDirectory = $path . DS . $directoryName;

        if (file_exists($newDirectory)) {

            return false;
        }

        if (mkdir($newDirectory)) {

            return $directoryName;
        }

        return false;
    }

}
