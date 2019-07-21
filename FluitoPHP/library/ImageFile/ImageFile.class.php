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

namespace FluitoPHP\ImageFile;

/**
 * ImageFile Class.
 *
 * Used for image file handling.
 *
 * Variables:
 *      1. $imagePath
 *      2. $imageInfo
 *      3. $imagesConfig
 *      4. $mimeToFunction
 *
 * Functions:
 *      1. __construct
 *      2. SetImagePath
 *      3. GetImagePath
 *      4. GetX
 *      5. GetY
 *      6. GetSizeString
 *      7. GetMime
 *      8. GetTypeConstant
 *      9. IsRGB
 *      10. IsCMYK
 *      11. GetBits
 *      12. Resize
 *      13. GetImageFuncSuffix
 *      14. Render
 *
 * @author Vipin Jain
 * @since  0.1
 */
class ImageFile {

    /**
     * Used for storing image file path.
     *
     * @var string
     * @author Vipin Jain
     * @since  0.1
     */
    protected $imagePath = null;

    /**
     * Used to store the image info.
     *
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    protected $imageInfo = [];

    /**
     * Used to store the image configuration from configuration files.
     *
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    static protected $imagesConfig = null;

    /**
     * Used to store the mime to function conversions
     *
     * @var array
     * @author Vipin Jain
     * @since  0.1
     */
    static protected $mimeToFunction = array(
        IMAGETYPE_GIF => 'gif',
        IMAGETYPE_JPEG => 'jpeg',
        IMAGETYPE_JPEG2000 => 'jpeg',
        IMAGETYPE_PNG => 'png',
        IMAGETYPE_BMP => 'bmp',
        IMAGETYPE_WBMP => 'wbmp',
        IMAGETYPE_WEBP => 'webp',
        IMAGETYPE_XBM => 'xbm'
    );

    /**
     * Used to initialize the image file.
     *
     * @param string $imagePath Provide path of the image file.
     * @throws \Exception Throws exception if the image file is not found or is not an image.
     * @author Vipin Jain
     * @since  0.1
     */
    public function __construct($imagePath) {

        $this->
                SetImagePath($imagePath);

        if (self::$imagesConfig === null) {

            self::$imagesConfig = \FluitoPHP\FluitoPHP::GetInstance()->
                    GetConfig('IMAGES');

            self::$imagesConfig = self::$imagesConfig ? self::$imagesConfig : [];

            $moduleConfig = \FluitoPHP\FluitoPHP::GetInstance()->
                    GetModuleConfig('IMAGES');

            $moduleConfig = $moduleConfig ? $moduleConfig : [];

            self::$imagesConfig = array_replace_recursive(self::$imagesConfig, $moduleConfig);
        }
    }

    /**
     * Used to init or change the image filepath.
     *
     * @param string $imagePath Provide path of the image file.
     * @return $this Self reference is returned for chained calls.
     * @throws \Exception Throws exception if the image file is not found or is not an image.
     * @author Vipin Jain
     * @since  0.1
     */
    public function SetImagePath($imagePath) {

        if (!file_exists($imagePath)) {

            throw new \Exception("Error: The path ({$imagePath}) do not exists.", 0);
        }

        if (substr(mime_content_type($imagePath), 0, 5) !== 'image') {

            throw new \Exception("Error: The path ({$imagePath}) is not an image file.", 0);
        }

        $this->
                imagePath = $imagePath;

        $this->
                imageInfo = getimagesize($this->
                imagePath);

        return $this;
    }

    /**
     * Used to get the image filepath.
     *
     * @return string
     * @author Vipin Jain
     * @since  0.1
     */
    public function GetImagePath() {

        return $this->
                imagePath;
    }

    /**
     * Used to get X size of the image file.
     *
     * @return int Returns the horizontal size of the image file.
     * @author Vipin Jain
     * @since  0.1
     */
    public function GetX() {

        return $this->
                imageInfo[0];
    }

    /**
     * Used to get Y size of the image file.
     *
     * @return int Returns the vertical size of the image file.
     * @author Vipin Jain
     * @since  0.1
     */
    public function GetY() {

        return $this->
                imageInfo[1];
    }

    /**
     * Used to get size string of the image file. Possible uses in image tag in html.
     *
     * @return int Returns the size string of the image file.
     * @author Vipin Jain
     * @since  0.1
     */
    public function GetSizeString() {

        return $this->
                imageInfo[3];
    }

    /**
     * Used to get mime type of the image file.
     *
     * @return int Returns the mime type of the image file.
     * @author Vipin Jain
     * @since  0.1
     */
    public function GetMime() {

        return $this->
                imageInfo['mime'];
    }

    /**
     * Used to get image type constant of the image file, defined in the GD library.
     *
     * @return int Returns the image type constant of the image file, defined in the GD library.
     * @author Vipin Jain
     * @since  0.1
     */
    public function GetTypeConstant() {

        return $this->
                imageInfo[2];
    }

    /**
     * Used to get if the image file is using RGB pallete.
     *
     * @return bool Returns true if the image is using RGB pallete.
     * @author Vipin Jain
     * @since  0.1
     */
    public function IsRGB() {

        return $this->
                imageInfo['channels'] === 3;
    }

    /**
     * Used to get if the image file is using CMYK pallete.
     *
     * @return bool Returns true if the image is using CMYK pallete.
     * @author Vipin Jain
     * @since  0.1
     */
    public function IsCMYK() {

        return $this->
                imageInfo['channels'] === 4;
    }

    /**
     * Used to get the number of bits the image file is using.
     *
     * @return mixed Returns number of bits the image file is using, in case the bit is not identified then false is returned.
     * @author Vipin Jain
     * @since  0.1
     */
    public function GetBits() {

        return isset($this->
                        imageInfo['bits']) ? $this->
                imageInfo['bits'] : false;
    }

    /**
     * Used to resize the image and put it in a new file path.
     *
     * @param type $outPath Provide the file path that needs to be generated.
     * @param type $xOrSize Provide size from the config if empty default size will be taken from the config or provide the horizontal value.
     * @param type $y Provide the vertical value.
     * @param type $hard Provide true if the image needs to be cropped to resize and false if any side needs to be reduced.
     * @return mixed Returns true on success and false on failure in case outpath is provided else returns the false on failure and image resource on success.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Resize($outPath = '', $xOrSize = 0, $y = 0, $hard = false) {

        $imageFunction = $this->
                GetImageFuncSuffix();

        if (!$imageFunction) {

            return false;
        }

        $imageResource = call_user_func_array("imagecreatefrom{$imageFunction}", array(
                    $this->
                    GetImagePath()
        ));

        if (is_string($xOrSize)) {

            $imageSize = [];

            if (isset(self::$imagesConfig['sizes'][$xOrSize])) {

                $imageSize = self::$imagesConfig['sizes'][$xOrSize];
            } else if (!strlen($xOrSize) &&
                    isset(self::$imagesConfig['defaultsize']) &&
                    isset(self::$imagesConfig['sizes'][self::$imagesConfig['defaultsize']])) {

                $imageSize = self::$imagesConfig['sizes'][self::$imagesConfig['defaultsize']];
            }

            $xOrSize = intval($imageSize['x']);
            $y = intval($imageSize['y']);
            $hard = boolval($imageSize['hard']);
        }

        $imageResizedResource = null;

        if ($xOrSize > 0) {

            if ($y < 1) {

                $y = $xOrSize;
            }

            $imgx = $this->
                    GetX();

            $imgy = $this->
                    GetY();

            if (!$hard) {

                if ($imgx / $xOrSize !== $imgy / $y) {
                    if ($imgx > $xOrSize ||
                            $imgy > $y) {

                        if ($imgx / $xOrSize < $imgy / $y) {

                            $xOrSize = ($y * $imgx) / $imgy;
                        } else {

                            $y = ($xOrSize * $imgy) / $imgx;
                        }
                    } else {

                        if ($imgx / $xOrSize > $imgy / $y) {

                            $y = ($xOrSize * $imgy) / $imgx;
                        } else {

                            $xOrSize = ($y * $imgx) / $imgy;
                        }
                    }
                }
            } else if ($xOrSize / $y > $imgx / $imgy) {

                $imgy = ($y * $imgx) / $xOrSize;
            } else if ($xOrSize / $y < $imgx / $imgy) {

                $imgx = ($xOrSize * $imgy) / $y;
            }

            $imageResizedResource = imagecreatetruecolor($xOrSize, $y);

            imagecopyresized($imageResizedResource, $imageResource, 0, 0, 0, 0, $xOrSize, $y, $imgx, $imgy);
        } else {

            $imageResizedResource = $imageResource;
        }

        imageAlphaBlending($imageResizedResource, true);
        imageSaveAlpha($imageResizedResource, true);

        if (is_string($outPath) &&
                strlen($outPath)) {

            return call_user_func_array("image{$imageFunction}", array(
                $imageResizedResource,
                $outPath
            ));
        } else {

            return $imageResizedResource;
        }
    }

    /**
     * Used to get the image GD function suffix.
     *
     * @return mixed Returns false if the function do not exists, else returns the function suffix.
     * @author Vipin Jain
     * @since  0.1
     */
    public function GetImageFuncSuffix() {

        $imageTypeConstant = $this->
                GetTypeConstant();

        if (!isset(self::$mimeToFunction[$imageTypeConstant])) {

            return false;
        }

        return self::$mimeToFunction[$imageTypeConstant];
    }

    /**
     * Used to render the resized image.
     *
     * @param type $xOrSize Provide size from the config if empty default size will be taken from the config or provide the horizontal value.
     * @param type $y Provide the vertical value.
     * @param type $hard Provide true if the image needs to be cropped to resize and false if any side needs to be reduced.
     * @author Vipin Jain
     * @since  0.1
     */
    public function Render($xOrSize = 0, $y = 0, $hard = false) {

        \FluitoPHP\Response\Response::GetInstance()->
                SetContentType($this->
                        GetMime());

        if (!call_user_func_array("image{$this->
                                GetImageFuncSuffix()}", array($this->
                            Resize("", $xOrSize, $y, $hard)))) {

            return false;
        }

        exit();
    }

}
