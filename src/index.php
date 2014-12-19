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
define("MODE", "development");

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

// Load all configuration files
$c_dir = opendir('config');
while ($c_item = readdir($c_dir)) {
    if ($c_item != '.' && $c_item != '..' && !is_dir($c_item)) require_once $c_item;
}

// Initiate the core
$core = \System\Core\Core::getInstance();
$core->main();