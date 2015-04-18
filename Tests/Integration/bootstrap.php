<?php
/**
 * Bootstrap file for testing
 *
 * User: dan
 * Date: 2/21/15
 * Time: 10:34 PM
 */

/* config */
$configFile = __DIR__ . '/../../config.php';
/** @noinspection PhpIncludeInspection */
require_once($configFile);
global $config;

/* verbose output */
//echo "Config file: $configFile\nConfig: ";
//var_dump($config);

/**
 * Autoloader
 * @param $className
 */
$autoload = function($className)
{
    $className = str_replace('\\', '/', $className);
    $inc = __DIR__ . "/../../$className.php";
    if (file_exists($inc)) {
        echo "Including $className from $inc ...\n";
        /** @noinspection PhpIncludeInspection */
        require_once($inc);
    }
};

spl_autoload_register($autoload);

require_once(__DIR__ . '/../../vendor/autoload.php');
define('BASE_URL', 'http://192.168.33.20/');
