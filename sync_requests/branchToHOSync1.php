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
	

	
	//$action 			= $_POST['action'];
	//$sync_option 		= $_POST['sync_opt'];
	//$BranchID 			= $_POST['branchID'];
	//$BranchCode 		= $_POST['branchCode'];
	//$BranchURL 			= $_POST['branchURL'];
	//$SyncFiles		 	= str_replace(" ","+",$_POST['SyncFiles']);
	//$ZipSyncFileName 	= $_POST['ZipSyncFileName'];
	//$NOW 				= date('Ymd'); //Date for today...
	//$stop				= false;
	
	
	$action 			= "START_SYNC_FROM_BRANCH";
	$sync_option 		= "IC";
	$BranchID 			= 40;
	$BranchCode 		= "CEN";
	//$BranchURL 			= $_POST['branchURL'];
	$SyncFiles		 	= str_replace(" ","+",$_POST['SyncFiles']);
	$ZipSyncFileName 	= "sync_files-40-20131215.zip";
	$NOW 				= "20131215"; //Date for today...
	$stop				= false;

	$returns 			= array();

    $DSClass 			= new DataSync; //Initialize class methods for syncing...
    $DSClass->cURL();
    
	//$database->execute("insert into syncdata (SyncFiles,xdate) values ('".$SyncFiles."',now())");
	
	
    //Trigger check when sync was about to start as the Branch requested...
    if($sync_option && $action == 'START_SYNC_FROM_BRANCH'){
        $mysqli->autocommit(FALSE);
        try{
            //Internet Connection
            if($sync_option == 'IC'){
				
				if(!is_dir($BranchCode.'-ZipFilesData')):
					mkdir($BranchCode.'-ZipFilesData', 0777, true);
				endif;
				
					$my_file = $BranchCode.'-ZipFilesData/'.$ZipSyncFileName;
					
					//$content = fread($fhandle,filesize($fname));
					//$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly creates file
					//exec("chmod -R 777 ".$my_file); //change file permission..
					//fwrite($handle, base64_decode($SyncFiles));
					//unzip files..
					unzip($my_file, false, true, true);
				
				
					if(!$stop){
						$DSClass->setDBForeignKeyChecks(0);
						// $YesterDate = date('Ymd',strtotime('yesterday')); //date yesterday...
						$YesterDate = "20131215"; //date yesterday...
						
						
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
			}

            $mysqli->commit();
            $mysqli->autocommit(TRUE);
        }catch(Exception $e){
            $mysqli->rollback();
            $mysqli->autocommit(TRUE);

            $returns['status'] = 'error';
            $returns['message'] = 'Sync to HO fail, kindly check the error: '.$e->getMessage();
        }

        tpi_JSONencode($returns);
    }
    
    //Trigger to update "Changed" fields of HO specific database tables...
    if($action == 'TRIGGER_SYNC_CHANGE_UPDATE'){
        
    }
    
    //Process that will check if EOD of previous day was successfully process...
    //Used in SOD process of specific branch...
    if($action == 'SYNC_SOD_IF_EOD_SUCCESS'){
        try{
           $YesterDate = date('Ymd',strtotime('yesterday')); //date yesterday... 
           //Check method for table system sync log if there are previous/yesterday records of EOD...
           if(!$DSClass->syncCheckSODIfSuccessfulPrevTrans($BranchID,$YesterDate)){
                $returns['status'] = 'success';
                $returns['SODPrevCheck'] = false;
           }else{
               $returns['status'] = 'success';
               $returns['SODPrevCheck'] = true;
           } 
        }catch(Exception $e){
            $returns['status'] = 'error';
            $returns['message'] = $e->getMessage();
        }
        
        tpi_JSONencode($returns);
    }
    
    //Process that will check if Mid Day Sync access key exists for an specific branch set in HO.
    //Used in Mid Day Sync procedure of branch.
	if(isset($_GET['action'])){
	
			$action 			= $_GET['action'];
			$BranchID 			= $_GET['branchID'];
			 if($action == 'MDS_CHECK_IF_ACCESS_RIGHT'){
				$AccessKey = $_GET['AccessKey'];
				error_log($BranchID.' '.$AccessKey);
				try{
				if(!$DSClass->syncValidateMDSBranchAccessKey($BranchID,$AccessKey)){
						$returns['status'] = 'success';
						$returns['AccessKeyCheck'] = false;
				}else{
					$returns['status'] = 'success';
					$returns['AccessKeyCheck'] = true;
				} 
				}catch(Exception $e){
					$returns['status'] = 'error';
					$returns['message'] = $e->getMessage();
				}	
				tpi_JSONencode($returns);
			}
	}
   
	
	
?>
