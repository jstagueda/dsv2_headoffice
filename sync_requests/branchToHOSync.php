<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: Gino Leabres
 * @date: May 27, 2013
 * @description: Sync file processor from Branch to HO database table sync.
 */
	// Turn off all error reporting
	ini_set('display_errors', '1');
    include('../initialize.php');
    include('../class/DataSyncTables.php');
    include('../class/DataSync.php');
    include('SyncRequestFunctions.php');

	$action 			= $_POST['action'];
	$sync_option 		= $_POST['sync_opt'];
	$BranchID 			= $_POST['branchID'];
	$BranchCode 		= $_POST['branchCode'];
	$BranchURL 			= $_POST['branchURL'];
	$SyncFiles		 	= str_replace(" ","+",$_POST['SyncFiles']);
	$ZipSyncFileName 	= $_POST['ZipSyncFileName'];
	$NOW 				= date('Ymd'); //Date for today...
	$stop				= false;
	$returns 			= array();
    $DSClass 			= new DataSync; //Initialize class methods for syncing...
    $DSClass->cURL();

	
    //Trigger check when sync was about to start as the Branch requested...
    if($sync_option && $action == 'START_SYNC_FROM_BRANCH'){
        $mysqli->autocommit(FALSE);
        try{
            //Internet Connection
            if($sync_option == 'IC'){
				$my_file = $BranchCode.'-ZipFilesData/'.$ZipSyncFileName;
				
				if(!is_dir($BranchCode.'-ZipFilesData')):
					mkdir($BranchCode.'-ZipFilesData', 0777, true);
				endif;
				
				$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly creates file
				fwrite($handle, base64_decode($SyncFiles));
				
				exec("chmod -R 777 ".$my_file); //change file permission..
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
						$filename = str_replace(".zip","",$my_file)."/data/".$tableNameFile;
						if ( filesize($filename) > 0 ) {
							$JSONEncode = fread(fopen($filename, "r"),filesize(str_replace(".zip","",$my_file)."/data/".$tableNameFile));
							$JSONDecode = json_decode($JSONEncode);
							$DSClass->syncStartEndDateTimeLog($BranchID,$BranchCode,$tableName);
							$affectedRows = $DSClass->doProcessSync($tableName,$JSONDecode);
							$DSClass->syncStartEndDateTimeLog($BranchID,$BranchCode,$tableName,'END',$affectedRows);
						}
						unlink(str_replace(".zip","",$my_file)."/data/".$tableNameFile);
					endforeach;
						rmdir(str_replace(".zip","",$my_file)."/data");
						rmdir(str_replace(".zip","",$my_file));
					$DSClass->setDBForeignKeyChecks();
					//Update HO database tables from Branch...
					$DSClass->syncUpdateChangeField($HOTblNeedToUpdate);
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
	
	 if($action == 'ConnectToHO'){ // Connect to Headoffice
		
		$SQL = array("UPDATE settings set LastModifiedDate = NOW()","UPDATE settings set LastModifiedDate = NOW() where SettingCode = 'BKUPRETAIN' ");
		foreach($SQL as $SqlStatement):
			$result['SQL'][]=$SqlStatement;
		endforeach;
		$result['Response']='successs';
        tpi_JSONencode($result);
    }
    
    //Process that will check if Mid Day Sync access key exists for an specific branch set in HO.
    //Used in Mid Day Sync procedure of branch.
	if(isset($_GET['action'])){
		$action 	= $_GET['action'];
		$BranchID 	= $_GET['branchID'];
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
