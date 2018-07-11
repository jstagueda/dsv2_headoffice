<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: April 18, 2013
 */

    include('../../initialize.php');
    include_once(CS_PATH.DS.'DataSync.php');
    include_once(CS_PATH.DS.'tpi-start-of-day-transactions.php');
    include_once(CS_PATH.DS.'tpi-end-of-day-transactions.php');
    
    $post = $_POST;
    $returns = array();
    
    /*
    * Array of database tables to be read in HO server and sync in branch database tables.
    */
    $FileToReadInHOForBranch = array('customer','customerdetails','customeraccountsreceivable','customerpenalty','tpi_customerdetails',
        'tpi_rcustomerbranch','customercommission','inventoryadjustment','inventoryadjustmentdetails','tpi_credit','tpi_creditlimitdetails',
        'user','productsubstitute','productkit','productcost','productdetails','productpricing','product','inventoryinout','inventoryinoutdetails',
        'area','holiday','incentivespromobuyin','incentivespromoentitlement','incentivespromoavailemnt','loyaltypromo','loyaltypromoavailment',
        'promo','promoavailment','promobuyin','promoentitlement','promoincentives','incentivesspecialcriteria','bank','loyaltypromobuyin',
        'loyaltypromoentitlement','promoentitlementdetails','employee','remployeebranch','remployeeposition',
        'tpi_dealerwriteoff','rusertypemodulecontrol','discount','tpi_fco','sfm_kpi_standards','sfm_level','sfm_rank');
    
    /*
    * Array of database tables to be read in Branch server and sync in HO database tables.
    */
    $HODBTblToReadInBranch = array('customer','customerdetails','customeraccountsreceivable','customerpenalty','cumulativesales','tpi_customerdetails',
        'tpi_rcustomeribm','tpi_rcustomerstatus','tpi_rcustomerbranch','tpi_rcustomerpda','tpi_customerstats','birseries','inventory',
        'inventoryadjustment','inventorycount','inventoryinout','inventorytransfer','inventoryadjustmentdetails','inventorycountdetails',
        'inventoryinoutdetails','inventorytransferdetails','salesorder','salesinvoice','salesorderdetails','salesinvoicedetails',
        'deliveryreceipt','deliveryreceiptdetails','dmcm','dmcmdetails','tpi_credit','tpi_creditlimitdetails','tpi_dealerwriteoff',
        'tpi_dealerpromotion','tpi_dealertransfer','tpi_branchcollectionrating','officialreceipt','officialreceiptcash','officialreceiptcheck',
        'officialreceiptcommission','officialreceiptdeposit','officialreceiptdetails','customercommission',
        'applied_single_line_promo_entitlement_details','applied_multi_line_promo_entitlement_details','applied_overlay_promo_entitlement_details',
        'applied_incentive_entitlement_details','availed_applicable_promos','availed_applicable_incentives','sfm_manager','sfm_manager_networks');
    
    $EODFilePrevChecker = '../../logs/EODPrevDate.chk';
    $moduleFileLocker = '../../logs/EODModules.lock';
    //Modules for lock/disabling Sales Invoice, Official Receipt and SFMs....
    $moduleIDsForLock = array(34,104,35,40,50,51,97,96,110,46,49,154,26,30,
                              27,31,25,29,2,119,3,58,59,159,57,100,32,133,126,
                              69,70,168,72,75,79,169,171,180,173,174,175,91,92,93,120,181,183,197 //SFM Modules pages ID...
                             );
    
    //Process for SOD (Start Of Day) and HO (Head Office) grab to sync data in Branch...
    if($post['action'] == 'doSODAndHOSync'){
        $sync_option = $post['sync_opt'];
        $DSClass = new DataSync; //Initialize class methods for syncing...
        $SODClass = new TPI_DSS_Start_Of_Day_Transactions; //Initialize class methods for SOD process...
        $DSClass->cURL();
        
        $BranchObj = $session->session_get('Branch');
        $HO_URL = HO_SYNC_PATH_URL;
        $syncProcessLog = '../../logs/syncSOD_process.log';
        $success = false;
        $stop = false;
        
        //Clear / clean sync process log file...
        tpi_file_truncate($syncProcessLog);
        
        //Check first if sycn process was already done to avoid multiple sync process in one day.
        if($DSClass->syncTodayHasProcessed()):
            $returns['status'] = 'error';
            $returns['message'] = 'Sorry, SOD and HO sync was already process, cannot create another process for this day.';
            tpi_error_log("Sorry, SOD and HO sync was already process, cannot create another process for this day....\n",$syncProcessLog);
            
            tpi_JSONencode($returns);
            exit;
        endif;
        
        tpi_error_log("Locking user sessions to stop transactions...\n",$syncProcessLog);
        //Disable other sessions login...
        $DSClass->setUserSessionLocked();
        
        $mysqli->autocommit(FALSE);
        try{
            //Checking in HO server if EOD of Branch was already / successfully transmitted on the previuos day....
            tpi_error_log("Checking previous EOD, if succesfully processed...\n",$syncProcessLog);
            $EODPrevCheck = $DSClass->get("$HO_URL/sync_requests/branchToHOSync.php?action=SYNC_SOD_IF_EOD_SUCCESS&branchID=$BranchObj->ID");
            if($EODPrevCheck){
                $EODResponse = json_decode(trim($EODPrevCheck)); //let's decode the response...
                if($EODResponse->status == 'success' && $EODResponse->SODPrevCheck == false){
                    tpi_error_log("SOD not process, EOD of previous day was not successfully executed. Please do EOD first. \n",$syncProcessLog);
                    $returns['status'] = 'error';
                    $returns['message'] = 'SOD not process, EOD of previous day was not successfully executed. Please do EOD first.';
                    
                    //File to be used by EOD process...
                    //Hint in EOD process that we need to process the previous transactions...
                    tpi_error_log("",$EODFilePrevChecker);
                    
                    $stop = true;//to trigger that now process should be done in SOD...
                }
            }
            
            if(!$stop){
                //Start of processing all SOD calculations...
                tpi_error_log("Starting SOD process...\n",$syncProcessLog);
                $SODClass->computeCustomersPenalty();
                tpi_error_log("Calculating penalties...\n",$syncProcessLog);
                tpi_error_log("$SODClass->errmsg...\n",$syncProcessLog);

                //Start sync process and checking what type of sync procedure; 
                //with Internet connection or no connection and just read data file.
                tpi_error_log("Requesting from HO data connection...\n",$syncProcessLog);
                if($sync_option == 'IC'){
                    //Trigger to update again tha CRON data files so it will get the last transactions made.
                    tpi_error_log("HO updating CRON data files for branch...\n",$syncProcessLog);
                    $DSClass->triggerUpdateCRONFiles("$HO_URL/cron_jobs/cronJobsForSync.php?action=SYNC_CRON_UPDATE&BranchCode=$BranchObj->Code&BranchID=$BranchObj->ID");
                    
                    tpi_error_log("Started sync from HO to Branch...\n",$syncProcessLog);
                    //Response on syncing HO to Branch...
                    $bresponse = $DSClass->triggerBranchToDoSyncFromHO("$HO_URL/cron_jobs/data", $FileToReadInHOForBranch, $BranchObj);

                    if($bresponse){
                        $returns['status'] = 'success';
                        $returns['message'] = 'SOD and HO Data sync in Branch successfully transmitted.';
                        $returns['notices'] = $DSClass->notices;
                        
                        //Update now the tables field Changed because syncing HO to Branch is successful...
                        $DSClass->syncUpdateChangeField($HODBTblToReadInBranch);
                        
                        //We create file that will handle disabled transactional modules of DS system...
                        tpi_error_log("Enabled transactional modules to resume transactions for today...\n",$syncProcessLog);
                        $DSClass->afterSODEnableModuleTransactions($moduleFileLocker);

                        tpi_error_log("Sync from HO to Branch successful...\n",$syncProcessLog);
                    }else{
                        $returns['status'] = 'error';
                        $returns['message'] = $DSClass->error_msg;
                    }
                }

                tpi_error_log("Committing updates...\n",$syncProcessLog);
                $success = true;
            }
            
            $mysqli->commit();
            $mysqli->autocommit(TRUE);
        }catch(Exception $e){
            $mysqli->rollback();
            $mysqli->autocommit(TRUE);
            $success = false;
            
            $returns['status'] = 'error';
            $returns['message'] = 'Sync process fails, you can contact your IT administrator to check problem and issue(s).';
        }
        
        tpi_error_log("Unlocking user sessions for transaction resume...\n",$syncProcessLog);
        //Remove the session locked now...
        $DSClass->setUserSessionUnlocked();
        
        if($success) tpi_error_log("SOD and HO sync process successful! \n",$syncProcessLog);
        else tpi_error_log("Sync process fails, you can contact your IT administrator to check problem and issue(s). \n",$syncProcessLog);
        
        tpi_JSONencode($returns);
    }
    
    //Process for EOD (EOD Of Day) and HO (Head Office) grab to sync data from Branch...
    if($post['action'] == 'doEODAndBranchSync'){
        $sync_option = $post['sync_opt'];
        $DSClass = new DataSync; //Initialize class methods for syncing...
        $EODClass = new TPI_DSS_End_Of_Day_Transactions; //Initialize class methods for SOD process...
        $DSClass->cURL();
        
        $BranchObj = $session->session_get('Branch');
        $BranchURL = BRANCH_SYNC_PATH_URL;
        $HO_URL = HO_SYNC_PATH_URL;
        $syncProcessLog = '../../logs/syncEOD_process.log';
        $success = false;

        //Clear / clean sync process log file...
        tpi_file_truncate($syncProcessLog);
        
        //Check first if EOD for that day was already process...
        $checkEODModuleLockExists = tpi_getEODModulesLockIDs($moduleFileLocker);
        if(is_array($checkEODModuleLockExists)){
            $returns['status'] = 'error';
            $returns['message'] = 'Sorry, EOD and Branch to HO sync was already process, cannot create another process for this end of day.';
            tpi_error_log("Sorry, EOD and Branch to HO sync was already process, cannot create another process for this end of day...\n",$syncProcessLog);
            
            tpi_JSONencode($returns);
            die();
        }
        
        tpi_error_log("Locking user sessions to stop transactions...\n",$syncProcessLog);
        //Disable other sessions login...
        $DSClass->setUserSessionLocked();
        
        $mysqli->autocommit(FALSE);
        try{
            //Start of processing all SOD calculations...
            tpi_error_log("Starting EOD process...\n",$syncProcessLog);
            $EODClass->consolidateBranchCollectionRating();
            tpi_error_log("Consolidating BCR...\n",$syncProcessLog);
            tpi_error_log("$EODClass->errmsg...\n",$syncProcessLog);
            
            tpi_error_log("Updating CRON data files...\n",$syncProcessLog);
            //Trigger to update again tha CRON data files so it will get the last transactions made.
            $DSClass->triggerUpdateCRONFiles("$BranchURL/cron_jobs/cronJobsForSync.php");

            tpi_error_log("Sending trigger in HO to start sync from branch...\n",$syncProcessLog);
            //Response on syncing Branch to HO...
            if(file_exists($EODFilePrevChecker)) $fetchEODPrev = '&fetchEODPrev=true';
            else $fetchEODPrev = '';
            
            $params = "?action=START_SYNC_FROM_BRANCH&sync_opt=$sync_option&branchID=$BranchObj->ID&branchCode=$BranchObj->Code&branchURL=$BranchURL$fetchEODPrev";
            $response = $DSClass->triggerHOToDoSyncFromBranch("$HO_URL/sync_requests/branchToHOSync.php$params");
            if($response){
                //Another try catch error handling since the result is coming from other host or remote processor.
                //So we can avoid error callback in jquery ajax submit of sync.
                try{
                    $DResponse = json_decode(trim($response)); //let's decode the response...
                    //Sync Branch in HO is succsessful, do sync HO in Branch now...
                    if($DResponse->status == 'success'){
                        tpi_error_log("Sync from Branch to HO successful...\n",$syncProcessLog);
                        //Update now the tables field Changed because syncing Branch to HO is successful...
                        $DSClass->syncUpdateChangeField($HODBTblToReadInBranch);
                        
                        //We create file that will handle disabled transactional modules of DS system...
                        tpi_error_log("Disabled transactional modules to block in creating any updates for today...\n",$syncProcessLog);
                        $DSClass->afterEODDisableModuleTransactions($moduleFileLocker,$moduleIDsForLock);
                        
                        //let's remove now the file checker/hint for EOD of previous day was not executed.
                        //this should be done only if file checker exists.
                        if(file_exists($EODFilePrevChecker)) unlink($EODFilePrevChecker);
                        
                        $returns['status'] = 'success';
                        $returns['message'] = 'EOD and Branch sync to HO successfully transmitted.';
                    }else{
                        $returns['status'] = 'error';
                        $returns['message'] = $DResponse->message;
                    }
                }catch(Exception $e){
                    $returns['status'] = 'error';
                    $returns['message'] = $e->getMessage();
                }
            }
            
            tpi_error_log("Committing updates...\n",$syncProcessLog);
            $mysqli->commit();
            $mysqli->autocommit(TRUE);
            
            $success = true;
        }catch(Exception $e){
            $mysqli->rollback();
            $mysqli->autocommit(TRUE);
            $success = false;
            
            $returns['status'] = 'error';
            $returns['message'] = 'Sync process fails, you can contact your IT administrator to check problem and issue(s).';
        }
        
        tpi_error_log("Unlocking user sessions for transaction resume...\n",$syncProcessLog);
        //Remove the session locked now...
        $DSClass->setUserSessionUnlocked();
        
        if($success) tpi_error_log("EOD and Branch sync process successful! \n",$syncProcessLog);
        else tpi_error_log("Sync process fails, you can contact your IT administrator to check problem and issue(s). \n",$syncProcessLog);
        
        tpi_JSONencode($returns);
    }
    
    //process for checking if user session is lock...
    if($post['action'] == 'check_session_if_lock'){
        $my_session_id = $session->session_get('user_session_id');
        $UserSessionLocked = 0;
        
        try{
            $q = $mysqli->prepare("SELECT `UserSessionLocked` FROM `user_sessions` WHERE `SessionID` = ?");
            $q->bind_param('s',$my_session_id);
            $q->execute();
            
            $q->bind_result($UserSessionLocked);
            $q->fetch();
            
            $returns['status'] = 'success';
            $returns['is_locked'] = $UserSessionLocked;
            
            if($UserSessionLocked > 0) $returns['message'] = 'Sorry, your session was locked, data sync triggered and on progress.';
            
            $q->close();
        }catch(Exception $e){
            $returns['status'] = 'error';
            $returns['message'] = 'Something is wrong, maybe your database don\'t have table for sessions.';
        }
        
        tpi_JSONencode($returns);
    }
?>