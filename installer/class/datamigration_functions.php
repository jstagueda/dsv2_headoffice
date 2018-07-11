<?php
function CreateConfigFile($file,$Server,$DBname,$UserName,$Password,$HOSync,$FilePath)
{
	if($Password=="NOPASSWORD"){
		$xPassword="";
	}else{
		$xPassword = $Password;
	}
	fwrite($file,
							"<?php
								defined('DB_SERVER') 	? null : define('DB_SERVER', '".$Server."');
								defined('DB_USER')     	? null : define('DB_USER', '".$UserName."');
								defined('DB_PASS')   	? null : define('DB_PASS', '".$xPassword."');
								defined('DB_NAME') 	 	? null : define('DB_NAME', '".$DBname."');
							?>");
		fclose($file);
}

function CreateSyncFile($sync_file,$HOSync,$BranchUrl)
{
		$MODULES_FOR_LOCKING = 'MODULES_FOR_LOCKING';
			fwrite($sync_file,"<?php
					/*
					* To change this template, choose Tools | Templates
					* and open the template in the editor.
					* 
					* @author: jdymosco
					* @date: June 04, 2013
					* @description: Defined constants for sync process...
					*/
					
						DEFINE('HO_SYNC_PATH_URL','".$HOSync."');
						DEFINE('BRANCH_SYNC_PATH_URL','".$BranchUrl."');
						
						$".$MODULES_FOR_LOCKING." = array(34,104,35,40,50,51,97,96,110,46,49,154,26,30,
													27,31,25,29,2,119,3,58,59,159,57,100,32,133,126,
													69,70,168,72,75,79,169,171,180,173,174,175,91,92,93,120,181,183,197 //SFM Modules pages ID...
													);
			?>");
}

function truncate_tables($database,$truncatetables,$success_logs)
{
	foreach ($truncatetables as $table){
			$truncate = "Truncate Table ".$table;
			$database->execute($truncate);
			tpi_error_log("Done ".$truncate.".<br />",$success_logs);
	}
}

function setForeignKeyChecks($database,$val=1)
{
	$database->execute("SET FOREIGN_KEY_CHECKS=".$val);
}
?>