<?php

try 
{
	//filepath
	$config_file = '../../class/config.php';
	$jsfile = '../js/systemFileSetup.js';
	
	//change file restriction
	exec("chmod -R 777 ".$config_file);
	exec("chmod -R 777 ".$jsfile);
	//end change file restriction
	
	//file open
	$file 	 = fopen($config_file,'w');
	$file_js = fopen($jsfile,'w');

	//parameters
	$Server   = $_POST['xServer'];
	$DBname   = $_POST['xDBName'];
	$UserName = $_POST['xUserName']; 
	$Password = $_POST['xPassword']; 
	$FilePath = $_POST['xFilePath'];
	$HOSync   = $_POST['xHOSync'];
	
	//request is start setup.
	if($_POST['request'] == 'start_setup' ){
		if($file){
				//config.php
				fwrite($file,
							"<?php
							defined('DB_SERVER') 	? null : define('DB_SERVER', '".$Server."');
							defined('DB_USER')     	? null : define('DB_USER', '".$UserName."');
							defined('DB_PASS')   	? null : define('DB_PASS', '".$Password."');
							defined('DB_NAME') 	 	? null : define('DB_NAME', '".$DBname."');
							defined('HO_SYNC')  	? null : define('HO_SYNC', '".$HOSync."');
							
							//	########## FILE PATH #############	//
							defined('data_upload')  ? null : define('data_upload', '/var/www/html/".$FilePath."');
							//product master
							
							defined('prod_master')	 ? null : define('prod_master', 'item.prd');
							//OpenSI
							
							defined('OpenSI')   	 ? null : define('OpenSI', 'OpenSI.csv');
							//LastSI
							
							defined('LastSI')   	 ? null : define('LastSI', 'LastSI.csv');
							
							//inventory
							defined('iv')   	 	 ? null : define('iv', 'Inventory.csv');
							//dealer
							
							defined('dlr')   		 ? null : define('dlr', 'Dealer.csv');
							
							//balance
							defined('bal')   		 ? null : define('bal', 'Custbal.csv');
							
							
							//DS PROJECT BRANCH DETAILS
							defined('branch')   	 ? null : define('branch', 'DS PROJECT BRANCH DETAILS.csv');
							
							//DS Employee
							defined('employee')   	 ? null : define('employee', 'Employee.csv');
							?>"
					);
					
			fclose($file);
			//end config.php
		}
		
		if($file_js){
			//javascript file setup
			fwrite($file_js,
					"
						/* 
						* To change this template, choose Tools | Templates
						* and open the template in the editor.
						*/
						var dir_path = '/".$FilePath."';
					");
			fclose($file_js);
			//end javascript file setup
		}
		
		
		
		echo json_encode(array('request'=>'successful'));
	}
}
catch(Exception $e)
{		
	$message = $e->getMessage()."\n";	
	echo $message;
}
		

