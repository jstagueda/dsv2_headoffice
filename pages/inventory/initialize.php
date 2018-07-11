<?php



// Define the core paths
// Define them as absolute paths to make sure that require_once works as expected

// DIRECTORY_SEPARATOR is a PHP pre-defined constant
// (\ for Windows, / for Unix)
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

// defined('SITE_ROOT') ? null : define('SITE_ROOT', DS.'var'.DS.'www'.DS.'html'.DS.'tpi_dev');
defined('SITE_ROOT') ? null : define('SITE_ROOT', 'D:' . DS . 'apachewwwroot' . DS . 'tpi');

defined('CS_PATH') ? null : define('CS_PATH', SITE_ROOT.DS.'class'.DS);
defined('IN_PATH') ? null : define('IN_PATH', SITE_ROOT.DS.'includes'.DS);


// load config file first
require_once(CS_PATH.DS.'config.php');


// load core objects
require_once(CS_PATH.DS.'dbconnection.php');
require_once(CS_PATH.DS.'session.php');
require_once(CS_PATH.DS.'database_object.php');
require_once(CS_PATH.DS.'mysqli.php');
require_once(CS_PATH.DS.'query.php');
require_once(CS_PATH.DS.'storedproc.php');
require_once(CS_PATH.DS.'stocklog.php');

// load basic functions next so that everything after can use them
require_once(CS_PATH.DS.'functions.php');

// load database-related classes
require_once(CS_PATH.DS.'user.php');

?>
