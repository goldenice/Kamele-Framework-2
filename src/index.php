<?php
/**
 * Kamele Framework 2.x
 * 
 * @package     Kamele Framework 2
 * @license     MIT License, see LICENSE.txt
 * 
 */

// Intializing basic stuff
define("KAMELE_VERSION", '2.0');
define("SYSTEM", KAMELE_VERSION);       // For compatibility with Kamele 1.x libraries
define("DIR_SEPARATOR", "/");

// Load all configuration files
$c_dir = opendir('config');
while ($c_item = readdir($c_dir)) {
    if ($c_item != '.' && $c_item != '..' && !is_dir($c_item)) require_once 'config'.DIR_SEPARATOR.$c_item;
}

// Evaluate environment
if (MODE == "production" || MODE == "testing") {
    error_reporting(0);
} 
else if (MODE == "development") {
    error_reporting(E_ALL);
    ini_set("display_errors", true);
} 
else {
    exit("Application environment not set correctly");
}

// Initiate the core
require_once 'system'.DIR_SEPARATOR.'core'.DIR_SEPARATOR.'singleton.php';
require_once 'system'.DIR_SEPARATOR.'core'.DIR_SEPARATOR.'core.php';
$core = \System\Core\Core::getInstance();
$core->main();