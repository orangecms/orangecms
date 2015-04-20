<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 4/26/15
 * Time: 12:18 AM
 */

namespace ORN;

use finfo;

class MediaFile {
    /**
     * @var array
     */
    private static $validImageFileTypes = array('image/gif', 'image/jpeg', 'image/png');

    /**
     * @var array
     */
    private static $validImageExtensions = array('jpeg', 'jpg', 'png', 'gif');

    /**
     * @var array
     */
    private static $validAudioFileTypes = array('audio/mpeg', 'audio/ogg');

    /**
     * @var array
     */
    private static $validAudioExtensions = array('mp3', 'ogg');

    /**
     * @param string $file
     * @param null $fileName
     * @return string
     */
    public static function imageFileType($file, $fileName = null)
    {
        $fileName = $fileName ? $fileName : $file;
        if (file_exists($file)) {
            $extension = self::getFileExtension($fileName);
            $mimeType  = self::getMimeType($file);
            if (DEBUG && VERBOSE) var_dump('image file: ', $file, $extension, $mimeType);
            if (in_array($extension, self::$validImageExtensions) &&
                in_array($mimeType, self::$validImageFileTypes)
            ) {
                return $mimeType;
            }
        }
        return null;
    }

    /**
     * @param string $file
     * @param null $fileName
     * @return string
     */
    public static function audioFileType($file, $fileName = null)
    {
        $fileName = $fileName ? $fileName : $file;
        if (file_exists($file)) {
            $extension = self::getFileExtension($fileName);
            $mimeType  = self::getMimeType($file);
            if (DEBUG && VERBOSE) var_dump('audio file: ', $file, $extension, $mimeType);
            if (in_array($extension, self::$validAudioExtensions) &&
                in_array($mimeType, self::$validAudioFileTypes)
            ){
                return $mimeType;
            }
        }
        return null;
    }

    /**
     * @param string $fileName
     * @return string
     */
    public static function getFileExtension($fileName)
    {
        return strtolower(substr(strrchr($fileName, '.'), 1));
    }

    /**
     * Obtain the MIME type of a file
     *
     * @param string $file
     * @return string
     */
    public static function getMimeType($file)
    {
        $fileInfo = new finfo(FILEINFO_MIME_TYPE);
        return $fileInfo->file($file);
    }
}
