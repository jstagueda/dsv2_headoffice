<?php
/*
 * @author: jdymosco
 * @date: December 17, 2012
 * @used: Prints array or text for debugging purposes only.
 */
function tpi_print_debugger($val){
    echo '<pre>';
    echo print_r($val);
    echo '</pre>';
}

/*
 * @author: jdymosco
 * @date: December 17, 2012
 * @used: Removes comma in number values.
 */
function tpi_format_number($num){
    return str_replace(',','',$num);
}

/*
 * @author: jdymosco
 * @date: December 18, 2012
 * @used: Validates and replace CSV values into correct value.
 */
function tpi_csv_clean_value($val, $type = 'STRING', $return = NULL){
    
    if($val == NULL || empty($val) || $val == '') return $return;
    if($type == 'STRING'){
       return str_replace("'", "\'",$val);
    }
    
    if($type == 'DATE'){
       return date('Y-m-d',strtotime(str_replace("'", "\'",$val)));
    }
}

/*
 * @author: jdymosco
 * @date: December 18, 2012
 * @used: Cleans value from database fetch to database insertion / update.
 */
function tpi_cleanFromDBToDBVal($val,$type = 'STRING',$return = NULL){
    
    if($val == NULL || empty($val) || $val == '') return $return;
    if($type == 'STRING'){
        return mysql_real_escape_string($val);
		//string mysqli_real_escape_string ( mysqli $link , string $escapestr ). 
    }
}

/*
 * @author: jdymosco
 * @date: January 22, 2013
 * @used: Cleans value before insertion in database.
 */
function tpi_cleanRequest($data){
    if(!$data) return 0;
    
    if(is_array($data)){
       $cleaned = array();
       foreach($data as $key => $val){
           $cleaned[$key] = trim(strip_tags($val));
       } 
    }else{
       $cleaned = trim(strip_tags($data));
    }
    
    return $cleaned;
}

/*
 * @author: jdymosco
 * @date: January 28, 2013
 * @description: JSON encoder used for AJAX request call back files.
 */
function tpi_JSONencode($val){
    header('Content-type: text/plain; charset="utf-8"');
    echo json_encode($val);
}

/*
 * @author: jdymosco
 * @date: January 28, 2013
 * @description: Function used to check if manager details already exists.
 */
function tpi_checkIfManagerExists($details){
    global $mysqli;
    
    $fname = $details['first_name'];
    $lname = $details['last_name'];
    $mname = $details['middle_name'];
    $dob = $details['birthdate'];
    $is_exists = 0;
    $where = '';
    
    if($fname != '' && $lname != '') $where = "WHERE (`first_name` = '$fname' && `last_name` = '$lname')";
    
    if($fname != '' && $mname != '') $where.= " || (`first_name` = '$fname' && `middle_name` = '$mname')";
    if($lname != '' && $dob != '') $where.= " || (`last_name` = '$fname' && `birth_date` = '$dob')";
    if($fname != '' && $dob != '') $where.= " || (`first_name` = '$fname' && `birth_date` = '$dob')";
    
    if($where != ''){
        $q = $mysqli->query("SELECT COUNT(mCode) AS is_exits FROM `sfm_manager` $where");
        $is_exists = $q->fetch_object()->is_exits;
    }
    
    if($is_exists <= 0 || $is_exists == null) return false;
    else return true;
}

/*
 * @author: jdymosco
 * @date: January 28, 2013
 * @description: Used for generating unique manager code in manager enrollment page.
 */
function tpi_generateManagerCode(){
    global $mysqli;
    $date = date('ymd');
    $genCode = '';
    
    $q = $mysqli->query('SELECT (MAX(mID) + 1) AS lastID FROM sfm_manager');
    $lastID = $q->fetch_object()->lastID;
    
    if($lastID == null || $lastID <= 0) $lastID = 1;
    else $lastID = $lastID;
    
    $genCode = 'E'.$date.'-00'.$lastID;
    
    return $genCode;
}

/*
 * @author: jdymosco
 * @date: Feb. 19, 2013
 * @description: Generates unique dealer code for customer database table.
 */
function tpi_generateDealerCode(){
    global $mysqli;
    $date = date('ym');
    $genCode = '';
    
    //We get first the MAX customer id that is already in database table.
    $q = $mysqli->query('SELECT (MAX(ID) + 1) AS lastID FROM customer');
    $lastID = $q->fetch_object()->lastID;
    
    if($lastID == null || $lastID <= 0) $lastID = 1;
    else $lastID = $lastID;
    
    if(!$_SESSION['Branch']) $genCode = $date.'-HO-'.$lastID; //Default code generation if no session of branch details.
    else $genCode = $date .'-'. $_SESSION['Branch']->ID .'-'. $lastID;
    
    return $genCode;
}

/*
 * @author: jdymosco
 * @date: Feb. 19, 2013
 * @description: Set branch parameters globally so no need to alwasy query the branch details.
 */
function tpi_setGlobalBranchParams(){
    global $mysqli;
    
    try{
        /*
         * We do check here if branch details session is already set so we can avoid query of details
         * every page load, to make page load not that heavy.
         */
        if(!isset($_SESSION['Branch'])){
            $q = $mysqli->query('SELECT bp.`BranchID`,bp.`TIN`,bp.`PermitNo`,bp.`ServerSN`,bp.`LastSyncDate`,
                                 bp.`CharacterFactor`,bp.`CapacityFactor`,bp.`CapitalFactor`,
                                 bp.`ConditionFactor`,bp.`DefaultCreditLimit`, b.`Code`
                                 FROM `branchparameter` bp
                                 INNER JOIN `branch` b ON b.`ID` = bp.`BranchID`');
            $branch = array();

            if($q->num_rows > 0){
                $b = $q->fetch_object();
                //Set branch parameters...
                $branch['ID'] = $b->BranchID;
                $branch['Code'] = $b->Code;
                $branch['TIN'] = $b->TIN;
                $branch['PermitNo'] = $b->PermitNo;
                $branch['ServerSN'] = $b->ServerSN;
                $branch['LastSyncDate'] = $b->LastSyncDate;
                $branch['CharacterFactor'] = $b->CharacterFactor;
                $branch['CapacityFactor'] = $b->CapacityFactor;
                $branch['CapitalFactor'] = $b->CapitalFactor;
                $branch['ConditionFactor'] = $b->ConditionFactor;
                $branch['DefaultCreditLimit'] = $b->DefaultCreditLimit;

                $_SESSION['Branch'] = (object) $branch;
            }else{
                throw new Exception("Could not initialize branchparameter details, database table is empty.");
            }
        }
    }catch(Exception $e){
        echo 'Problem in branchparameter database table, check if have values.';
    }
}

/*
 * @author: jdymosco
 * @date: January 28, 2013
 * @description: Gets all IBMs that can have downlines used in manager page enrollment.
 */
function tpi_getManagerUplines($term = ''){
    global $mysqli;
    
    $returns = array();
    $uplines = @implode(',', tpi_getLevelIDsHasUpline());
    
    if($term != ''){
        $where = " AND (`last_name` LIKE '%$term%' OR 
                        `first_name` LIKE '%$term%' OR 
                        `middle_name` LIKE '%$term%' OR 
                        `mCode` LIKE '%$term%' OR 
                         CONCAT_WS(' ',`last_name`,`first_name`,`middle_name`) LIKE '%$term%'
                       )";
    }else{
        $where = '';
    }
    
    $q = $mysqli->query("SELECT c.ID,sm.mID,sm.mCode,CONCAT_WS(' ',sm.first_name,sm.last_name,sm.middle_name) as uplineName 
                         FROM sfm_manager sm
                         LEFT JOIN customer c ON c.Code = sm.mCode
                         WHERE mLevel IN ($uplines) 
                         $where");
    
    if($q->num_rows > 0){
        while($r = $q->fetch_object()){
            $returns[] = $r;
        }
    }
    
    return $returns;
}

function tpi_getLevelIDsHasUpline(){
    global $mysqli;
    $arr_uplines = array();
    
    $uplines = $mysqli->query("SELECT `codeID` FROM `sfm_level` WHERE `has_downline` = 1");
    if($uplines->num_rows > 0){
        while($ru = $uplines->fetch_object()){
            $arr_uplines[] = $ru->codeID;
        }
    }
    
    return $arr_uplines;
}

/*
 * @author: jdymosco
 * @date: Feb. 20, 2013
 * @description: Function used to get all SFM level used for dealer or manager enrollment.
 */
function tpi_getSalesForceLevel($code = 0,$can_purchase = 1){
    global $mysqli;
    $where = "";
    
    if($code == 0) $where = "";
    if($code == 1) $where = "WHERE `can_purchase` = $can_purchase";
        
    $q = $mysqli->query("SELECT `codeID`,`desc_code`,`description`,`can_purchase` FROM `sfm_level` $where ORDER BY desc_code ASC");
    
    return $q;
}

/*
 * @author: jdymosco
 * @date: Feb 07, 2013
 * @description: Simple pagination with next and previous buttons.
 */
function tpi_simplePaginationArrowed($total_pages, $page, $otherLinks = ''){
    if($total_pages > 0){
        $url = $_SERVER['REQUEST_URI'];
        $match = 'paging=';
        $pos = strpos($url, $match);
        $amp_qm = strpos($url,'?');
        $concat = ($amp_qm) ? '&' : '?';
        $next_link = '#';
        $prev_link = '#';
        
        //This will replace the current match string so it will not iterate every page load.
        if($pos) $url = str_replace($concat.$match.$page.$otherLinks,'',$url);
        
        //Do the next and previous button links
        if($page > 1) $prev_link = $url.$concat.$match.($page - 1).$otherLinks; //previous button link
        if($page < $total_pages) $next_link = $url.$concat.$match.($page + 1).$otherLinks; //next button link
?> 
    <a href="<?php echo $prev_link; ?>"><img src="images/bprv.gif" class="page-prev"></a>
    <a href="<?php echo $next_link; ?>"><img src="images/bnxt.gif" class="page-next"></a>
    <p class="tbl-float-right" style="margin: 0;line-height: 22px;font-weight: bold;">Showing page <?php echo $page; ?> of <?php echo $total_pages; ?> pages</p>
<?php
    }
}

/*
 * @author: jdymosco
 * @date: March 11, 2013
 * @description: Customer file log writer.
 */
function tpi_error_log($string,$path){
    $fc = $path;
    
    if(!file_exists($path)){ //Let's create the file if not exists...
        $fh = fopen($fc,'w');
        fclose($fh);
        chmod($path,0777);
    }
    
    try{
        $fh = fopen($fc,'a');
        fwrite($fh,$string);
        fclose($fh);
    }catch(Exception $e){
        echo $e->getMessage().' '.$e->getTrace();
    }
}

/*
 * @author: jdymosco
 * @date: March 11, 2013
 * @description: Truncates or clear file contents.
 */
function tpi_file_truncate($path){
    if(file_exists($path)) file_put_contents("$path", "");
}

/*
 * @author: jdymosco
 * @date: April 08, 2013
 */
function tpi_KPISelectRoutines(){
    global $mysqli;
    
    $routines = array();
    $r = $mysqli->query("SELECT ROUTINE_NAME
                         FROM INFORMATION_SCHEMA.ROUTINES
                         WHERE ROUTINE_SCHEMA = 'ems_tpi_test_branch'
                         AND ROUTINE_TYPE = 'PROCEDURE'
                         ");
    if($r->num_rows > 0):
        while($routine = $r->fetch_object()):
            $routines[] = $routine->ROUTINE_NAME;
        endwhile;
    endif;
    
    return $routines;
}

/*
 * @author: jdymosco
 * @date: April 25, 2013
 * @description: Function that will get all credit terms details.
 */
function tpi_getCreditTerms(){
    global $mysqli;
    
    $cterms = array();
    
    $s = $mysqli->query("SELECT * FROM `creditterm` ORDER BY `Name` ASC");
    if($s->num_rows > 0){
        while($row = $s->fetch_object()):
            $cterms[] = $row;
        endwhile;
        
        return $cterms;
    }
    
    return $cterms;
}

/*
 * @author: John Paul Pineda
 * @date: May 31, 2013
 * @description: Function to get the dealer's penalty.
*/
function tpi_get_dealer_penalty($v_amount_due, $v_days_due) {
  
  $v_amount_due=((float)$v_amount_due);
  $v_days_due=((int)$v_days_due);    
  
  $penalty=0.00;
  
  if($v_days_due>0) for($x=1;$x<=$v_days_due;$x++) $penalty+=($x==1)?0.05:(($x>=2 && $x<=8)?0.02:(($x==9)?0.01:0.00));
  
  return ($v_amount_due*$penalty);  
}
/*
 * @author: jdymosco
 * @date: June 10, 2013
 * @description: Function that will check if EOD modules for lock was created.
 */
function tpi_getEODModulesLockIDs($pathFile){
    if(file_exists($pathFile)){
        $contents = file_get_contents($pathFile);
        $ModuleIDs = json_decode($contents);
        
        return $ModuleIDs;
    }
    
    return false;
}

function tpi_BlockEODModulesLockIDs($pageID = 0){
    $EODModulesLocked = tpi_getEODModulesLockIDs('logs/EODModules.lock');
    if(is_array($EODModulesLocked)){
        if(in_array($pageID,$EODModulesLocked)){
            return true;
        }
    }
    
    return false;
}

function tpi_EODUnlinkReloader(){
    $reloaderFile = 'logs/EODModules.reload';
    //reload should not include the EOD and SOD process...
    if(isset($_GET['do_reload']) && $_GET['do_reload'] == 'unlink' && $_GET['pageid'] != 109){
        if(file_exists($reloaderFile)) unlink($reloaderFile);
    }
}
//End of functions for EOD blocked modules...

/*
 * @author: jdymosco
 * @date: July 02, 2013
 * @description: Function that will get all branches available.
 */
function tpi_GetBranches(){
    global $mysqli;
    $branches = array();
    $q = $mysqli->query("SELECT `ID`,`Code`,`Name` FROM `branch` WHERE `StatusID` = 1 ORDER BY `Name` ASC");
    
    if($q->num_rows > 0){
        while($row = $q->fetch_object()):
            $branches[] = $row;
        endwhile;
    }
    
    return $branches;
}

/*
 * @author: jdymosco
 * @date: July 08, 2013
 * #description: Function that will get all customer account by branch selected or set...
 */
function tpi_GetCustomers($branchID,$can_purchase = 1){
    global $mysqli;
    $lists = array();
    
    $q = $mysqli->query("SELECT c.`ID`,TRIM(c.`Code`) AS `Code`,TRIM(c.`Name`) AS `Name`
                                FROM `customer` c
                                INNER JOIN (SELECT `codeID` FROM `sfm_level` WHERE `can_purchase` = $can_purchase) lvl ON lvl.`codeID` = c.`CustomerTypeID` 
                                WHERE LOCATE(BINARY '-$branchID',CONCAT(' ',c.`HOGeneralID`,' '))
                       ");
    if($q->num_rows > 0){
         while($row = $q->fetch_object()):
                $lists[] = $row;
         endwhile;
         
         return $lists;
    }
    
    return false;
}

/*
 * @author: jdymosco
 * @date: July 10, 2013
 * @description: Function that will get all reasons a specific reason type ids.
 */
function tpi_GetReasonsByTypeID($typeIDs){
    global $mysqli;
    $reasons = array();
    
    $q = $mysqli->query("SELECT `ID`,`Code`,`Name` FROM `reason` WHERE `ReasonTypeID` IN ($typeIDs)");
    if($q->num_rows > 0){
        while($row = $q->fetch_object()):
            $reasons[] = $row;
        endwhile;
        
        return $reasons;
    }
    
    return false;
}

/*
 * @author: jdymosco
 * @date: August 13, 2013
 * @description: Function that will get branch details "Name".
 */
function tpi_getBranchName($ID){
    global $mysqli;
    $q = $mysqli->query("SELECT `Name` FROM `branch` WHERE `ID` = $ID");
    if($q->num_rows > 0){
        return $q->fetch_object()->Name; 
    }
    
    return false;
}

/*
 * @author: jdymosco
 * @date: August 13, 2013
 * @description: Function that will get customer details by Code or ID.
 */
function tpi_getBranchCustomer($IDorCode,$BranchID,$ByFieldType = 'ID'){
    global $mysqli;
    
    if($ByFieldType == 'ID') $AND = "AND `ID` = $IDorCode";
    if($ByFieldType == 'Code') $AND = "AND `Code` = '$IDorCode'";
    
    $q = $mysqli->query("SELECT CONCAT(`Code`,' - ',`Name`) AS `CustomerName` FROM `customer` WHERE LOCATE(BINARY '-$BranchID',CONCAT(' ',`HOGeneralID`,' ')) $AND");
    if($q->num_rows > 0){
        return $q->fetch_object()->CustomerName;
    }
    
    return false;
}

/*
 * @author: jdymosco
 * @date: August 15, 2013
 * @description: Function that will get sales force level details (e.g. Name,ID and Code).
 */
function tpi_getSFLDetailsByID($ID){
    global $mysqli;
    $q = $mysqli->query("SELECT `desc_code`,`description` FROM `sfm_level` WHERE `codeID` = $ID");
    if($q->num_rows > 0){
        return $q->fetch_object();
    }
    
    return false;
}


function GetSettingValue($database,$SettingCode){
	return $database->execute("SELECT SettingValue FROM settings WHERE TRIM(SettingCode)='".TRIM($SettingCode)."'")->fetch_object()->SettingValue;
}
