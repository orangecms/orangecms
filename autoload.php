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
    $inc = __DIR__ . "/$className.php";
    if (file_exists($inc)) {
//        echo "Including $className from $inc ...\n";
        /** @noinspection PhpIncludeInspection */
        require_once($inc);
    }
};

spl_autoload_register($autoload);
