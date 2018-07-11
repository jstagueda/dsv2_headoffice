<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: January 18, 2013
 * @description: Different cron job functions for HO.
 */

    function cron_pre_log($data){
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
    
    function cron_ProcessStatus(){
        global $mysqli;
        
        echo '<br /><br /><br />****************************<br />';
        echo '<b>STATUS:::</b>'. $mysqli->stat.'<br />';
        echo '<b>WARNINGS:::</b>'. $mysqli->warning_count;
    }
    
    function cron_FileWriteCreate($file,$data = ''){
        $fc = $file;
        
        try{
            $fh = fopen($fc,'w');
            ftruncate($fh,0);
            fwrite($fh,$data);
            fclose($fh);
        }catch(Exception $e){
            echo $e->getMessage().' '.$e->getTrace();
        }
    }
    
    function cron_doQueryJobs($query_jobs,$BranchDIR = '',$BranchID = 0){
        global $mysqli;
        set_time_limit(0);
        
        $data = array();
        $date = date('Ymd');
        
        if(!is_array($query_jobs)) return 0; 
        
        //If branch dir name is not empty create new directory...
        if($BranchDIR){
            //Check first if dir already exists, if not then create...
            if(!file_exists(DATA_CRON_SYNC_FILES.$BranchDIR)) mkdir(DATA_CRON_SYNC_FILES.$BranchDIR);
            
            $BranchDIR = $BranchDIR.DS; 
        }else{
            $BranchDIR = '';
        }
        
        cronTruncateLog();
        //Start of doing cron job data file per database table that is included in sync process...
        foreach($query_jobs as $kj => $qj){
            $q = $mysqli->query(str_replace('[BRANCH_ID]',$BranchID,$qj));
            
            if($q->num_rows > 0){
                print "Getting all table datas of $kj...<br /> \n";
                while($row = $q->fetch_object()){
                    //Unset specific field HOGeneralID since this is only available in HO database specific tables.
                    unset($row->HOGeneralID);
                    $data[] = $row;
                }
                
                //Creating file...
                print "Writing data in file $kj.d...<br /><br /><br /> \n\n";
                cronLog($kj); //log start 
                cron_FileWriteCreate(DATA_CRON_SYNC_FILES.$BranchDIR."$kj-$date.cd", json_encode($data));
                cronLog($kj,'END'); //log end
                
                unset($data); //reset data...
                $q->free_result();
            }else{
                print "No data to write in file $kj.d...<br /><br /><br /> \n\n";
                //Just create the file if no data so it will still exists.
                cron_FileWriteCreate(DATA_CRON_SYNC_FILES.$BranchDIR."$kj-$date.cd", '');
            }
        }
        
        cron_ProcessStatus();
    }
    
    /*
     * @author: jdymosco
     * @date: May 29, 2013
     * @description: Function that will get all branches available in DS system, to be use in creation of DIR.
     */
    function cronGetBranchesForDIRCreation(){
        global $mysqli;
        $branches = array();
        
        $q = $mysqli->query("SELECT `ID`,TRIM(`Code`) AS `Code` FROM `branch` ORDER BY `Code` ASC");
        if($q){
            while($row = $q->fetch_object()):
                $branches[$row->ID] = str_replace(' ','_',trim($row->Code));
            endwhile;
            
            return $branches;
        }
        
        return $branches;
    }
    
    //Cron history logger function...
    function cronLog($transactionType,$logType = 'START'){
        global $mysqli;
        
        $time = date('H:s:i',time());
        $date = date('Y-m-d');
        
        if($logType == 'START'){
            $q = $mysqli->prepare("INSERT INTO `cron_log`(`TransactionType`,`Started`,`DateProcessed`) VALUES (?,?,?)");
            $q->bind_param('sss',$transactionType,$time,$date);
            $q->execute();
        }
        
        if($logType == 'END'){
            $q = $mysqli->prepare("UPDATE `cron_log` SET `Ended` = ? WHERE `TransactionType` = ? AND `DateProcessed` = ?");
            $q->bind_param('sss',$time,$transactionType,$date);
            $q->execute();
        }
        
    }
    
    //function that will clear logs of CRON..
    function cronTruncateLog(){
        global $mysqli;
        $mysqli->query("TRUNCATE TABLE `cron_log`");
    }
    
    /*
     * @author: jdymosco
     * @date: April 17, 2013
     * @description: Function that will move all old cron data files for sync in arhcive directory depending on
     *              date minus 1 day (yesterday) by default.
     * $parameter: $date format YYYYMMDD
     */
    function cronMoveCronDataFilesToArchive($date = '',$BranchDIR = ''){
        $dirPath = DATA_CRON_SYNC_FILES.(($BranchDIR) ? $BranchDIR.DS :'');
        $date = (!empty($date) ? $date : date('Ymd',strtotime('-1 day')));
        $dir_handler = '';
        $archive_dir = $dirPath.$date;
        $ctr = 0;
        
        if (is_dir($dirPath)) {
            //Create directory archive for old date...
            if(!file_exists($archive_dir)){
               mkdir($archive_dir);
               chmod($archive_dir,0777); 
            }
            
            print "Moving of old data CRON sync file starts...<br />\n";
            //Start of reading directory data for all old cron data files for archiving...
            if ($dir_handler = opendir($dirPath)) {
                while (($file = readdir($dir_handler)) !== false) {
                    if(!in_array($file,array('.','..')) && strstr($file,$date)){
                        //Let's copy the old file to archive directory now...
                        //Make sure that file to be moved is not a directory...
                        if(filetype($dirPath.$file) != 'dir' && filetype($dirPath.$file) == 'file'){
                            //remove and copy file when file exists only...
                            if(file_exists($dirPath.$file)){
                                copy($dirPath.$file, $archive_dir.DIRECTORY_SEPARATOR.$file);
                                unlink($dirPath.$file);
                                $ctr++;
                            }
                        }
                    }
                }
                closedir($dir_handler);
            }
            print "Total Moved Files: ". $ctr. "<br />\n";
        }
    }
	
	function SyncUpdateTable($update_tables,$BranchID)
	{
        global $mysqli;
		foreach ($update_tables as $query):
			$mysqli->query(str_replace('[BRANCH_ID]',$BranchID,$query));
		endforeach;
	}
    
?>
