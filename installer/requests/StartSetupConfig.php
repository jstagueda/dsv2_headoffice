<?php
/*
	@Author: Gino Leabres
*/
try 
{
    include('../../class/tpi_functions.php');
	include ('../class/datamigration_functions.php');
	//filepath..
	$DIR = __DIR__;
	//files need to update..
	$config_file = '../../class/config.php';
	
	//change file restriction
	exec("chmod -R 777 ".$config_file);
	//logs.. 
	exec("chmod -R 777 ../logs.txt");
	//exec("chmod -R 777 ".$jsfile);
	
	//file open..
	$file 	 	= fopen($config_file,'w');
	//$file_js = fopen($jsfile,'w');
	
	//parameters..
	$Server   	= $_POST['xServer'];
	$DBname   	= $_POST['xDBName'];
	$UserName 	= $_POST['xUserName'];
	if($_POST['xPassword']==""){
		$Password = "NOPASSWORD";
	}else{
		$Password 	= $_POST['xPassword']; 
	}	
	$FilePath 	= $_POST['xFilePath'];
	$HOSync   	= $_POST['xHOSync'];
	$BranchUrl  = $_POST['xBranchUrl'];
	$success_logs 		= "../logs.txt";
	tpi_file_truncate($success_logs);
	if($_POST['request'] == 'start_setup' ){
		if($file){
			CreateConfigFile($file,$Server,$DBname,$UserName,$Password,$HOSync,$FilePath);
			tpi_error_log('Done Creating Config File '."<br />",$success_logs);
		}
		
		if($sync_file){
			CreateSyncFile($sync_file,$HOSync,$BranchUrl);
			tpi_error_log('Done Creating Sync Config File '."<br />",$success_logs);
		}
		
		
		$all_files=array('truncate.php','bupload.php');
		foreach($all_files as $upload_files){
			include($upload_files);
		}
		
		$json = json_encode(array('request'=>'successful..'));
	}else{
		$json =  json_encode(array('request'=>'failed'));
	}
	die($json);
}
catch(Exception $e)
{		
	$message = $e->getMessage()."\n";	
	tpi_error_log('Error please contact IT message: '.$message."<br />",$success_logs);
	die(json_encode(array('request'=>$message)));
}
?>