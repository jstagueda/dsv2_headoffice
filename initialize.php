<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

// Define the core paths
// Define them as absolute paths to make sure that require_once works as expected

// DIRECTORY_SEPARATOR is a PHP pre-defined constant
// (\ for Windows, / for Unix)
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

//defined('SITE_ROOT') ? null : define('SITE_ROOT', DS.'var'.DS.'www'.DS.'html'.DS.'dss_svn_branch');
//defined('SITE_ROOT') ? null : define('SITE_ROOT','C:\Users\ucas382\Desktop\xampp\htdocs\dss_test_branch');
/*
 * @author: jdymosco
 * @date: May 9, 2013
 * @reason: Updated the defining of site root in a dynamic way...
 */
defined('SITE_ROOT') ? null : define('SITE_ROOT',dirname(__FILE__));
//End of update...

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
require_once(CS_PATH.DS.'promo.php');

require_once(CS_PATH.DS.'promostoredproc.php');
require_once(CS_PATH.DS.'sicancellation.php');
require_once(CS_PATH.DS.'dealerupload.php');
//bupload created by Gino C. Leabres
require_once(CS_PATH.DS.'bupload.php');

// load basic functions next so that everything after can use them
require_once(CS_PATH.DS.'functions.php');

// load database-related classes
require_once(CS_PATH.DS.'user.php');

// Developer 1's Source Codes' inclusion starts here by JP.
require_once(CS_PATH.DS.'dev1-source-codes.php');
// Developer 1's Source Codes' inclusion ends here by JP.
require_once(CS_PATH.DS.'HOReportFunctionsQuery.php');
//pagination..
//require_once(IN_PATH.DS."pagination.php");

//included file permissions updater to make sure that directory for cron jobs data is always writable...
//exec("chmod -R 777 cron_jobs/data");
//exec("chmod -R 777 logs");