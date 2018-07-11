<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: May 27, 2013
 * @description: Sync file processor from Branch to HO database table sync.
 */
	// Turn off all error reporting
	ini_set('display_errors', '1');
    include('../initialize.php');
    include('../class/DataSyncTables.php');
    include('../class/DataSync.php');
    include('SyncRequestFunctions.php');
    //ini_set('memory_limit', '-1');
	

	

          //  if($sync_option == 'IC'){
				
				if(!is_dir('CEN-ZipFilesData')):
					mkdir('CEN-ZipFilesData', 0777, true);
				endif;
				
					$my_file = 'CEN-ZipFilesData/sync_files-40-20131212.zip';
					
					//$content = fread($fhandle,filesize($fname));
					$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly creates file
					exec("chmod -R 777 ".$my_file); //change file permission..
					fwrite($handle, base64_decode($SyncFiles));
					//unzip files..
					unzip($my_file, false, true, true);
				
				
					if(!$stop){
						$DSClass->setDBForeignKeyChecks(0);
						// $YesterDate = date('Ymd',strtotime('yesterday')); //date yesterday...
						$YesterDate = date('Ymd'); //date yesterday...
						
						
						if($fetchEODPrev){
							$TableCDFile = "$YesterDate/[TABLE_NAME]-$YesterDate.txt";
							$DSClass->PrevTransDate = "$YesterDate";
							
						}else{
							
							$TableCDFile = "[TABLE_NAME]-$NOW.txt";
							$DSClass->PrevTransDate = '';
						}
						
						foreach($HODBTblToReadInBranch as $tableName):
							$tableNameFile = str_replace('[TABLE_NAME]',$tableName,$TableCDFile);
							// $JSONEncode = $DSClass->get("/cron_jobs/data/$tableNameFile"); 
							$filename = str_replace(".zip","",$my_file)."/data/".$tableNameFile;
							if ( filesize($filename) > 0 ) {
								$JSONEncode = fread(fopen($filename, "r"),filesize(str_replace(".zip","",$my_file)."/data/".$tableNameFile));
								//echo $JSONEncode;
							}
							
							$JSONDecode = json_decode($JSONEncode);
							$DSClass->syncStartEndDateTimeLog($BranchID,$BranchCode,$tableName);
							$affectedRows = $DSClass->doProcessSync($tableName,$JSONDecode);
							$DSClass->syncStartEndDateTimeLog($BranchID,$BranchCode,$tableName,'END',$affectedRows);
							unlink(str_replace(".zip","",$my_file)."/data/".$tableNameFile);
						endforeach;
							rmdir(str_replace(".zip","",$my_file)."/data");
							rmdir(str_replace(".zip","",$my_file));
						$DSClass->setDBForeignKeyChecks();
		
						//Update HO database tables from Branch...
						$DSClass->syncUpdateChangeField($HOTblNeedToUpdate);
						
						//rmdir(str_replace(".zip","",$my_file));
						
						$returns['status'] = 'success';
						$returns['message'] = 'Sync to HO done.';
					}
			//}
?>