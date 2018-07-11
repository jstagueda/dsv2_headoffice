<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: April 18, 2013
 */
//    include('../initialize.php');
    include('cURL.php');
    
    class DataSync extends cURL{
        
        public $error_msg = '';
        public $errors = array();
        public $fromHOToBranch = false;
        public $PrevTransDate = ''; //e.g. 20130520 [YYYYMMDD] Format...

        public function __construct(){
            $this->fromHOToBranch = false;
            $this->PrevTransDate = '';
        }
        
        /*
         * @description: Method that will read and get cron data / json files.
         */
        public function readDataFile($filePath){
            return file_get_contents($filePath);
        }
        
        /*
         * @description: Method that will trigger URL that will update CRON files.
         */
        public function triggerUpdateCRONFiles($URL){
            return parent::get($URL);
        }
        
        /*
         * @description: Method that will tell/trigger HO that sync process should be started.
         */
        public function triggerHOToDoSyncFromBranch($HO_URL){
            return parent::get($HO_URL);
        }
        
        /*
         * @description: Method used in HO checking if EOD from Branch previous EOD was successfully process... 
         */
        public function syncCheckSODIfSuccessfulPrevTrans($BranchID = 0,$PrevDate = ''){
            global $mysqli;
            //DATE(`ActualDateTimeSync`) = DATE_SUB(DATE(NOW()), INTERVAL 1 DAY) OR
            $q = $mysqli->query("SELECT COUNT(*) AS `EODCheck` 
                                 FROM `system_sync_log` 
                                 WHERE `BranchID` = $BranchID 
                                 AND LOCATE('$PrevDate',`FileName`) > 0");
            $check = $q->fetch_object()->EODCheck;
            if($check > 0):
                return true;
            endif;
            
            return false;
        }
        
        /*
         * @description: Method that will remove restrictions on the transaction modules on accessing it's page.
         */
        public function afterSODEnableModuleTransactions($path){
            if(file_exists($path)) tpi_file_truncate($path);
        }
        
        /*
         * @description: Method that will add restrictions on the transaction modules on accessing it's page.
         */
        public function afterEODDisableModuleTransactions($path,$moduleIDsForLock = array()){
            //page IDs of DS system transactional modules...            
            $moduleIDsForLock = ((is_array($moduleIDsForLock) && count($moduleIDsForLock) > 0) ? json_encode($moduleIDsForLock) : '');
            if(file_exists($path)) tpi_file_truncate($path);
            tpi_error_log($moduleIDsForLock,$path);
        }
        
        /*
         * @description: Method that will check EOD if successfully done yesterday or previous day.
         */
        public function SODCheckIfEODPrevSuccess($HO_URL,$BranchID){
            return parent::get("$HO_URL/sync_requests/branchToHOSync.php?action=SYNC_SOD_IF_EOD_SUCCESS&branchID=$BranchID");
        }
        
        /*
         * @description: Method that will start / trigger branch to start syncing from HO to branch database tables.
         */
        public function triggerBranchToDoSyncFromHO($HO_URL,$tables,$Branch){
            $NOW = date('Ymd'); //Date for today...
            
            try{
                if(is_array($tables)):
                    $this->fromHOToBranch = true;
                
                    self::setDBForeignKeyChecks(0);
                    
                    foreach($tables as $tbl):
                        $tableName = $tbl;

                        $JSONEncode = parent::get("$HO_URL/$Branch->Code/$tableName-$NOW.cd"); //Internet Connection
                        $JSONDecode = json_decode($JSONEncode);

                        self::syncStartEndDateTimeLog($Branch->ID,$Branch->Code,$tableName);

                        $affectedRows = self::doProcessSync($tableName,$JSONDecode);

                        self::syncStartEndDateTimeLog($Branch->ID,$Branch->Code,$tableName,'END',(is_numeric($affectedRows) ? $affectedRows : 0));
                    endforeach;
                    
                    self::setDBForeignKeyChecks();
                endif;
                
                return true;
            }catch(Exception $e){
                $this->error_msg = 'Fail, error on syncing HO to Branch. ( ERROR: '. $e->getMessage() .' )';
                return false;
            }
        }
        
        /*
         * @description: Will do the core process of syncing tables.
         */
        public function doProcessSync($tableName,$data){
            global $mysqli;
            $fields = array();
            $fieldRows = array();
            $fieldValRows = array();
            $recordCtr = 0;
            $doIns = false;
            
            try{
                $st = $mysqli->query("DESCRIBE $tableName");//show tables;
                
                if($st):
                    while($field = $st->fetch_object()):
                        $fields[] = $field->Field;
                        $type[$field->Field] = $field->Type;
                    endwhile;
                else:
                    $this->errors[] = "Database table ( $tableName ) does not exists.";
                endif;
            
                if(is_array($data)):
                    foreach($data as $c => $v):
                        foreach($v as $k => $b):
                            if(in_array($k,$fields) && is_array($fields)):
                                $fieldRows[] = "`$k`";
                                $fieldValRows[] = $this->cleanValueByType($type[$k],$b); //clean values and assign default values if null.
                            endif;
                        endforeach;
                        
                        $doIns = self::doInsertOrUpdateInTable($tableName,$fieldRows,$fieldValRows);
                        
                        unset($fieldRows);
                        unset($fieldValRows);
                        
                        if($doIns) $recordCtr++;
                    endforeach;
                endif;
                
                return $recordCtr;
            }catch(Exception $e){               
                return $e->getMessage();
            }
        }
        
        /*
         * @description: Method that will update and insert records in data sync process.
         */
        public function doInsertOrUpdateInTable($table,$fields,$values){
            global $mysqli;
            
            if(is_array($fields) && is_array($values)):
                $onUpdateConcat = self::prepareFieldsForUpdate($fields,$values);
                $strInsert = "INSERT INTO `$table` (".@implode(',',$fields).") VALUES (".@implode(',',$values).") ON DUPLICATE KEY UPDATE $onUpdateConcat";
                $inserted = $mysqli->query($strInsert);
                
                return $inserted;
            endif;
            
            return false;
        }
        
        /*
         * @description: Method that will set/save log of datetime sync was processed. 
         */
        public function syncStartEndDateTimeLog($BranchID,$BranchCode,$tableName = '',$action = 'START',$records = 0){
            global $mysqli;
            $dateNow = date('Ymd');
            
            //condition used for EOD checking if previous day was successfully executed...
            if(!empty($this->PrevTransDate)){ 
                $archiveDIR = $this->PrevTransDate.'/';
                $dateUsed = $this->PrevTransDate;
            }else{ 
                $archiveDIR = '';
                $dateUsed = $dateNow;
            }
            
            if($this->fromHOToBranch) $filePath = 'cron_jobs/data/'.str_replace(' ','_',$BranchCode).'/'.$archiveDIR.$tableName.'-'.$dateUsed.'.cd';
            else $filePath = 'cron_jobs/data/'.$archiveDIR.$tableName.'-'.$dateUsed.'.cd';
            
            if($action == 'START'){
                $q = $mysqli->prepare("INSERT INTO `system_sync_log` (`BranchID`,`FileName`,`TransactionType`) VALUES (?,?,?)");
                $q->bind_param('iss',$BranchID,$filePath,$tableName);
                $q->execute();
            }
            
            if($action == 'END'){
                $u = $mysqli->prepare("UPDATE `system_sync_log` SET `TotalNoOfRecords` = ?, `IsSync` = 1, `ActualDateTimeSync` = NOW() 
                                       WHERE `TransactionType` = ? AND DATE(`DateTime`) = CURDATE()");
                $u->bind_param('is',$records,$tableName);
                $u->execute();
            }
        }
        
        /*
         * @description: Method that will update "Changed" field to 0 when sync 
         *          process is successful in HO to Branch and vice versa.
         */
        public function syncUpdateChangeField($tables,$branchRef = ''){
            global $mysqli;
            
            if($tables){
                foreach($tables as $tbl):
                    $update = $mysqli->query("UPDATE `$tbl` SET `Changed` = 0");
                endforeach;
            }
        }
        
        /*
         * @description: Method that will check if sync was already processed once in a day.
         * @return: Bolean true or false.
         */
        public function syncTodayHasProcessed(){
            global $mysqli;
            
            $c = $mysqli->query("SELECT COUNT(*) AS `check` FROM `system_sync_log` 
                                 WHERE (
                                  DATE(`DateTime`) = CURDATE() OR 
                                  DATE(ActualDateTimeSync) = CURDATE() OR
                                  LOCATE(DATE_FORMAT(CURDATE(),'%Y%m%d'),`FileName`) > 0
                                 )");
            $check = $c->fetch_object()->check;
            
            if($check > 0 && !empty($check)) return true;
            else return false;
        }
        
        /*
         * @description: Method that will locked user sessions if someone already clicked on sync to head office.
         */
        public function setUserSessionLocked($session_id = ''){
            global $mysqli;
            global $session;
            
            $session_id = ((empty($session_id)) ? $session->session_get('user_session_id') : $session_id);
            $lock = $mysqli->prepare("UPDATE `user_sessions` SET `UserSessionLocked` = 1 WHERE `SessionID` != ?");
            $lock->bind_param('s',$session_id);
            $lock->execute();
        }
        
        /*
         * @description: Method that will unlock user sessions that is been lock when sync process started.
         */
        public function setUserSessionUnlocked(){
            global $mysqli;
            $mysqli->query("UPDATE `user_sessions` SET `UserSessionLocked` = 0");
        }
        
        public function setDBForeignKeyChecks($val = 1){
            global $mysqli;
            $mysqli->query("SET FOREIGN_KEY_CHECKS = $val");
        }
        
        /*
         * @description: Method that will clean and check data type of values to be pass on sync process, so blank values are
         *               ommitted.
         * @return: Cleaned / user defined set default values.
         */
        public function cleanValueByType($type,$value){
            $dreturn = '';
            $value = preg_replace('/[\s]+/',' ',$value); //remove whitespaces...
            
            if(stristr($type,'date') !== FALSE){
                if(empty($value) || $value == '') $dreturn = "'0000-00-00'";
                else $dreturn = "'$value'";
            }
			
			if(stristr($type,'timestamp') !== FALSE){
                if(empty($value) || $value == '') $dreturn = "'0000-00-00'";
                else $dreturn = "'$value'";
            }
            
            if(stristr($type,'char') !== FALSE){
                if(empty($value) || $value == '' || strlen($value) <= 0) $dreturn = "NULL";
                else $dreturn = "'$value'";
            }
			
			  
            if(stristr($type,'varchar') !== FALSE){
                if(empty($value) || $value == '' || strlen($value) <= 0) $dreturn = "NULL";
                else $dreturn = "'$value'";
            }
            
            if(stristr($type,'text') !== FALSE ){
                if(empty($value) || $value == '' || strlen($value) <= 0) $dreturn = "NULL";
                else $dreturn = "'$value'";
            }
            
            if(stristr($type,'int') !== FALSE){
                if(empty($value) || $value == '') $dreturn = 0;
                else $dreturn = $value;
            }
			
			if(stristr($type,'bigint') !== FALSE ){
                if(empty($value) || $value == '') $dreturn = 0;
                else $dreturn = $value;
            }
            
            if(stristr($type,'decimal') !== FALSE){
                if(empty($value) || $value == '') $dreturn = "NULL";
                else $dreturn = $value;
            }
			
			if(stristr($type,'enum') !== FALSE){
				if(empty($value) || $value == '' || strlen($value) <= 0) $dreturn = "NULL";
				else $dreturn = "'$value'";
			}
			
			
			if(stristr($type,'tinyint') !== FALSE){
				if(empty($value) || $value == '' || strlen($value) <= 0) $dreturn = "NULL";
				else $dreturn = "'$value'";
			}
            
            
            return $dreturn;
        }
        
        /*
         * @description: Method that will prepare fields for inclusion in "ON DUPLICATE KEY UPDATE" of insertion.
         */
        private function prepareFieldsForUpdate($fields,$values){
            $concat_str = '';
            $strFieldsVal = array();
            $fieldExclude = array('`ID`','`LastModifiedDate`','`HOGeneralID`');
            
            if(is_array($fields) && is_array($values)):
                foreach($fields as $id => $field):
                    if(!in_array($field,$fieldExclude)){
                        $strFieldsVal[] = $field.' = '.$values[$id];
                    }
                endforeach;
            endif;
            
            $concat_str = @implode(', ',$strFieldsVal);
            
            return $concat_str;
        }
        
        /*
         * @description: Method that will check if mid day sync access key is still valid and exists.
         */
        public function syncValidateMDSBranchAccessKey($BranchID,$AccessKey){
            global $mysqli;
            $DCKey = $AccessKey;
            $ECKey = md5($AccessKey);
            
            $q = $mysqli->query("SELECT COUNT(*) AS `Check` FROM `system_access_keys` 
                                 WHERE `DecryptedAccessKey` = '$DCKey' AND `EncryptedAccessKey` = '$ECKey'
                                 AND `BranchID` = $BranchID AND `ExpirationDate` >= NOW()");
            
            if($q->fetch_object()->Check > 0){
                return true;
            }else{
                return false;
            }
        }
    }
?>