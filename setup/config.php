<?php
/*
		defined('DB_SERVER') 	? null : define('DB_SERVER', 'localhost');
		defined('DB_USER')      ? null : define('DB_USER', 'root');
		defined('DB_PASS')      ? null : define('DB_PASS', '');
		defined('DB_NAME') 	    ? null : define('DB_NAME', 'ems_tpi_test_branch');
		defined('HO_SYNC')  	? null : define('HO_SYNC', 'http://10.132.54.134/10.132.54.166/trunk_ho/dbsync.php');
*/

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: March 11, 2013
 * @description: Configuration setup for file DSS system setup.
 */
 
	$setup_config['select'][] = array('label' => 'Truncate Tables',
                                      'file_process' => 'truncate.php',
                                      'file_error_log' => 'truncatelogs.log');
    
	$setup_config['select'][] = array('label' => 'Upload Branch',
                                      'file_process' => 'branch.php',
                                      'file_error_log' => 'branch.log');
    
	$setup_config['select'][] = array('label' => 'Setup Branch Parameter',
                                      'file_process' => 'branchparameter.php',
                                      'file_error_log' => 'branchparameter.log');
	
	$setup_config['select'][] = array('label' => 'Upload Employee',
                                      'file_process' => 'EmployeeUploader.php',
                                      'file_error_log' => 'Employee.log');
	
	
	$setup_config['select'][] = array('label' => 'Data Migration',
                                      'file_process' => 'DataMigration.php',
                                      'file_error_log' => 'branchparameter.log');
	
	
	$setup_config['select'][] = array('label' => 'Setup for Sales Force Account',
                                      'file_process' => 'SFA_Uploader.php',
                                      'file_error_log' => 'sfa_uploads.log');
    
	$setup_config['select'][] = array('label' => 'Setup for Sales Force Account PMG',
                                      'file_process' => 'SFA_PMG_Uploader.php',
                                      'file_error_log' => 'sfa_pmg_uploads.log');
    
	$setup_config['select'][] = array('label' => 'Setup Branch Collection Rating Table',
                                      'file_process' => 'SFA_PaidUpsPerPMG.php',
                                      'file_error_log' => 'sfa_pmg_uploads.log');
	

    
    DEFINE('LOGS_PATH','../../logs/');
?>