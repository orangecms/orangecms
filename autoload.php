<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 2/22/15
 * Time: 1:48 AM
 */

/**
 * @param $className
 * @return bool
 */
$autoload = function($className) {
    $className = str_replace('\\', '/', $className);
    $file = __DIR__ . "/$className.php";
    if (file_exists($file)) {
        if (DEBUG && VERBOSE) echo "Including $className from $file ...\n";
        /** @noinspection PhpIncludeInspection */
        require_once($file);
    }
};

spl_autoload_register($autoload);
