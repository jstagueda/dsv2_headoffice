<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: January 28, 2013
 */

    include('../../../initialize.php');
    
    $post = $_POST;
    
    if($post['action'] == 'insert'){
        $returns = array();
        $i = $mysqli->prepare("INSERT INTO `sfm_manager` 
                              (`mCode`,`mLevel`,`first_name`,`last_name`,`middle_name`,`birth_date`,`TIN`,
                              `credit_term_id`,`credit_limit`,`bank_acct_num`,`bank_acct_name`,`bank_name`,
                              `date_added`,`branchID`) 
                              VALUES 
                              (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        
        
        
        $ii = $mysqli->prepare('INSERT INTO `sfm_manager_networks` (`manager_code`,`manager_network_code`) VALUES (?,?)'); 
        
        $post = tpi_cleanRequest($post);
        $SFLcp = $post['SFLcp']; //checker if level can purchase...
        $SFLhas_pa = $post['SFLhas_pa']; //checker if level has personal account...
        
        if($post['code'] != '' && $post['SFL'] != '' && $post['fname'] != '' && $post['lname'] != '' && $post['mname'] != '' 
           && $post['dob'] != '' && $post['TIN'] != '' && $post['credit_term'] != '' && $post['credit_limit'] != ''){
            $code = $post['code'];
            $SFL = $post['SFL'];
            $fname = $post['fname'];
            $lname = $post['lname'];
            $mname = $post['mname'];
            $dob = $post['dob'];
            $TIN = $post['TIN'];
            $ULN_IBMID = $post['IBMID'];
            $ULN = (empty($post['ULN'])) ? 'null' : $post['ULN'];
            $credit_limit = $post['credit_limit'];
            $credit_term = $post['credit_term'];
            $bank_acct_num = $post['bank_acct_num'];
            $bank_acct_name = $post['bank_acct_name'];
            $bank_name = $post['bank_name'];
            $date_added = date('Y-m-d');
            $branchID = $_SESSION['Branch']->ID;
            
            try{
                //Other table for customers insertion...
                $cd_fname = $fname;
                $cd_lname = $lname;
                $cd_mname = $mname;
                $cd_bday = $dob;
                $cd_nickname = $fname;
                $cd_telno = 'null';
                $cd_mobileno = 'null';
                $cd_address = 'null';
                $cd_zipcode = 'null';
                $cd_class = 2; //TupperwareBrands
                $cd_dealertype = $SFL; //Sales Force Level
                $cd_gsutype = 1;
                $cd_ibmno = $ULN;
                $cd_ibmname = '';
                $cd_zone = 1;
                $cd_lstay = 0;
                $cd_marital = 1;
                $cd_educational = 7;
                $cd_TIN = $TIN;
                $cd_igscode = $code;
                $cd_areaid = 1;
                $cd_directsellexp = 1;
                $cd_empstatus = 1;
                $cd_credittermid = $credit_term;
                $cd_character = 1;
                $cd_capacity = 1;
                $cd_capital = 1;
                $cd_condition = 1;
                $cd_calculatedcl = 1250; //We just used a random default value for now...
                $cd_recommendedcl = $credit_limit;
                $cd_approvedcl = $credit_limit;
                $cd_remarks = 'null';
                $cd_pdaid = 1;
                $IBMID = ($ULN == 'null') ? 1 : $ULN_IBMID;
                $cd_ibmcode = ($ULN == 'null') ? 'null' : $ULN;
                $cd_recruiter = 'null';
                
                $database->beginTransaction();
                
                //We still used the old stored proc dealer insertion to avoid conflicts of code on sales cycle module.
                $affected_rows = $sp->spInsertDealer($database, 
                                $cd_lname, $cd_fname, $cd_mname, $cd_bday, $cd_nickname, $cd_telno, $cd_mobileno, $cd_address, 
				$cd_zipcode, $cd_class, $cd_dealertype, $cd_gsutype, $cd_ibmno, $cd_ibmname, $cd_zone, 
                                $cd_lstay, $cd_marital, $cd_educational, $cd_TIN, $cd_igscode, $cd_areaid,
				$cd_directsellexp, $cd_empstatus, $cd_credittermid, $cd_character, $cd_capacity, $cd_capital,
				$cd_condition, $cd_calculatedcl, $cd_recommendedcl, $cd_approvedcl, $cd_remarks, 
                                $cd_pdaid, $IBMID, $cd_ibmcode, $cd_recruiter);
                
                $i->bind_param('ssssssssssssss',$code,$SFL,$fname,$lname,$mname,$dob,$TIN,$credit_term,$credit_limit,$bank_acct_num,$bank_acct_name,$bank_name,$date_added,$branchID);
                $i->execute();
                
                if($ULN != ''){
                    $ii->bind_param('ss',$ULN,$code);
                    $ii->execute();
                }
                
                $database->commitTransaction();
                
                $returns['message'] = 'New manager was successfully added.';
                $returns['status'] = 'success';
                $returns['nxt_code'] = tpi_generateManagerCode();
                
                if($SFLhas_pa > 0){ 
                    $returns['SFLhas_pa'] = $post; 
                    $returns['SFL_pa_btn'] = true; 
                }else{ 
                    $returns['SFLhas_pa'] = array(); 
                    $returns['SFL_pa_btn'] = false;
                }
                
            }catch(Exception $e){
                $returns['message'] = 'Generating new manager fails. ('. $e->getMessage() .')'; 
                $returns['status'] = 'error';
                
                $database->rollbackTransaction();
            }
            
        }else{
            $returns['message'] = 'Fields with * are required fields.'; 
            $returns['status'] = 'error';
        }
        $returns['from_action'] = 'insert';
        
        tpi_JSONencode($returns);
    }
    
    if($post['action'] == 'update'){
        $returns = array();
        $u = $mysqli->prepare("UPDATE `sfm_manager` SET `mLevel` = ?,`first_name` = ?,`last_name` = ?,`middle_name` = ?,
                              `birth_date` = ?,`TIN` = ?,`credit_term_id` = ?,`credit_limit` = ?,`bank_acct_num` = ?, 
                              `bank_acct_name` = ?,`bank_name` = ?
                              WHERE `mCode` = ?");
        $uu = $mysqli->prepare("UPDATE `sfm_manager_networks` SET `manager_code` = ? WHERE `manager_network_code` = ? AND `manager_code` = ?");
        $ii = $mysqli->prepare('INSERT INTO `sfm_manager_networks` (`manager_code`,`manager_network_code`) VALUES (?,?)');
        
        $post = tpi_cleanRequest($post);
        
        if($post['code'] != '' && $post['SFL'] != '' && $post['fname'] != '' && $post['lname'] != '' && $post['mname'] != '' 
           && $post['dob'] != '' && $post['TIN'] != '' && $post['credit_term'] != '' && $post['credit_limit'] != ''){
            
            $code = $post['code'];
            $SFL = $post['SFL'];
            $fname = $post['fname'];
            $lname = $post['lname'];
            $mname = $post['mname'];
            $dob = $post['dob'];
            $TIN = $post['TIN'];
            $ULN = $post['ULN'];
            $OldULN = $post['OldULN'];
            $credit_limit = $post['credit_limit'];
            $credit_term = $post['credit_term'];
            $bank_acct_num = $post['bank_acct_num'];
            $bank_acct_name = $post['bank_acct_name'];
            $bank_name = $post['bank_name'];
            $branchID = $_SESSION['Branch']->ID;
            
            try{
                thisUpdateSFMCustomerDetails($post);
                
                $u->bind_param('ssssssssssss',$SFL,$fname,$lname,$mname,$dob,$TIN,$credit_term,$credit_limit,$bank_acct_num,$bank_acct_name,$bank_name,$code);
                $u->execute();
                
                //Update for new manager code to assigned...
                if($ULN != ''){
                    /*
                     * Check first if already have mother IBM code record
                     * If already have then update the record, if no record insert as new
                     */
                    if(alreadyHaveIBMNetwork($OldULN,$code)){
                        $uu->bind_param('sss',$ULN,$code,$OldULN);
                        $uu->execute();
                    }else{
                        $ii->bind_param('ss',$ULN,$code);
                        $ii->execute();
                    }
                }
                
                $returns['status'] = 'success';
                $returns['message'] = 'Manager details was successfully updated';
            }catch(Exception $e){
                $returns['message'] = 'Updating manager details fails. ('. $e->getMessage() .')'; 
                $returns['status'] = 'error';
            }
        }else{
            $returns['message'] = 'Fields with * are required fields.'; 
            $returns['status'] = 'error';
        }
        $returns['from_action'] = 'update';
        
        tpi_JSONencode($returns);
    }
    
    if($post['action'] == 'edit'){
        $returns = array();
        $q = "SELECT 
              m.mCode,m.mLevel,m.first_name,m.last_name,m.middle_name,m.birth_date,m.TIN,
              m.credit_term_id,m.credit_limit,m.bank_acct_num,m.bank_acct_name,m.bank_name, 
              mn.manager_code,c.ID
              FROM `sfm_manager` m
              LEFT JOIN `sfm_manager_networks` mn ON mn.manager_network_code = m.mCode
              LEFT JOIN `customer` c ON c.Code = mn.manager_code
              WHERE mCode = '?'";
        $q = str_replace('?',$post['mCode'],$q);
        $d = $mysqli->query($q);

        if($d->num_rows > 0){
            $returns['status'] = 'success';
            $returns['data'] = $d->fetch_object();
        }else{
            $returns['status'] = 'error';
            $returns['data'] = array();
        }
        
        tpi_JSONencode($returns);
    }
    
    if($post['action'] == 'generate_code'){
        //Generates custom code for manager insertion
        $genCode = tpi_generateManagerCode();
        tpi_JSONencode(array('mCode' => $genCode));
    }
    
    if($post['action'] == 'check_if_exists'){
        $details = array();
        $first_name = $post['first_name'];
        $last_name = $post['last_name'];
        $middle_name = $post['middle_name'];
        $dob = $post['birthdate'];
        $result = false;
        
        //checks if manager exists in SFM managers table....
        $manager_check = tpi_checkIfManagerExists($post);
        $dealer_check = $mysqli->query("CALL spSelectExistingCustomer(-1, '$first_name', '$last_name', '$middle_name', '$dob')");

        if($manager_check == false){ // && $dealer_check->num_rows <= 0
            $result = false;
        }else{
            $result = true;
        }
        
        tpi_JSONencode(array('exists' => $result));
    }
    
    if($post['action'] == 'get_uplines'){
        //Gets all managers that can have uplines...
        $result = tpi_getManagerUplines($post['manager_term']);
        tpi_JSONencode(array('uplines' => $result));
    }
    
    if($post['action'] == 'get_credit_terms'){
        //Gets all credit terms...
        $t = array();
        $q = $mysqli->query("CALL spSelectCreditTerm()");
        
        if($q->num_rows){
            while($r = $q->fetch_object()){
                $t[] = $r;
            }
        }
        
        tpi_JSONencode(array('credit_terms' => $t));
    }
    
    if($post['action'] == 'lists'){
        $start = $post['start'];
        $end = $post['end'];
        
        $d = $mysqli->query("SELECT sfm_m.mCode AS code, CONCAT_WS(' ',sfm_m.first_name, sfm_m.last_name, sfm_m.middle_name) AS full_name,
                             sfm_lvl.desc_code AS abbrv
                             FROM sfm_manager sfm_m
                             LEFT JOIN sfm_level sfm_lvl ON sfm_lvl.codeID = sfm_m.mLevel
                             ORDER BY sfm_m.mCode DESC
                             LIMIT $start, $end");
        $t = $mysqli->query('SELECT COUNT(*) AS total FROM sfm_manager');
        
        $returns = array();
        
        if($d->num_rows > 0){
            while($r = $d->fetch_object()){
                $returns['lists'][] = $r;
            }
            $returns['status'] = 'success';
        }else{
            $returns['lists'] = array();
            $returns['status'] = 'error';
            $returns['message'] = 'No managers in database.';
        }
        
        $total = $t->fetch_object()->total;
        $returns['total'] = $total;
        
        tpi_JSONencode($returns);
    }
    
    /*
     * @author: jdymosco
     * @date: Feb. 25, 2013
     * @description: Custom function used to updated customer details of IBMs.
     */
    function thisUpdateSFMCustomerDetails($data){
        global $mysqli;
        
        $code = $data['code'];
        $SFL = $data['SFL'];
        $fname = $data['fname'];
        $lname = $data['lname'];
        $mname = $data['mname'];
        $dob = $data['dob'];
        $TIN = $data['TIN'];
        $ULN_IBMID = $data['IBMID'];
        $ULN = $data['ULN'];
        $credit_limit = $data['credit_limit'];
        $credit_term = $data['credit_term'];
        
        //Get IBM proper customer ID used..
        $IBMGetCodeToID = $mysqli->query("SELECT `ID` AS customerID FROM customer WHERE `Code` = '$code'"); 
        $GetCodeTocID = $IBMGetCodeToID->fetch_object()->customerID;
        
        //If IBM proper customer ID exists then we do update.
        if($GetCodeTocID){
            $mysqli->query("UPDATE customer
                                    SET
                                        `LastName` = '$lname',
                                        `MiddleName` = '$mname',
                                        `FirstName` = '$fname',
                                        `NAME` = CONCAT_WS(' ', '$fname', '$mname', '$lname'),
                                        `CustomerTypeID` = $SFL,
                                        `TIN` = '$TIN',
                                        `Birthdate` = '$dob',
                                        `Changed` = 1
                                    WHERE `Code` = '$code'");

            $mysqli->query("UPDATE tpi_customerdetails
                                    SET
                                        `Nickname` = '$fname',
                                        `tpi_IBMCode` = '$ULN',
                                        `Changed` = 1
                                    WHERE `CustomerID` = $GetCodeTocID");

            $mysqli->query("UPDATE tpi_credit
                                SET
                                    `CreditTermID` = $credit_term,
                                    `RecommendedCL` = $credit_limit,
                                    `ApprovedCL` = $credit_limit
                                WHERE `CustomerID` = $GetCodeTocID");

            $mysqli->query("UPDATE `tpi_rcustomeribm` SET `IBMID` = $ULN_IBMID, `EnrollmentDate` = NOW() WHERE `CustomerID` = $GetCodeTocID");
        }
    }
    
    /*
     * @author: jdymosco
     * @date: Feb. 19, 2013
     * @description: Function used to check if user already have network with IBM code selected.
     * 
     * $mCode = mother IBM code
     * $mNetCode = IBM code to be updated
     */
    function alreadyHaveIBMNetwork($mCode,$mNetCode){
        global $mysqli;
        
        $check = $mysqli->query("SELECT `manager_code` AS checkCode FROM `sfm_manager_networks`
                                 WHERE `manager_code` = '$mCode' AND `manager_network_code` = '$mNetCode'
                                ");
        
        if($check->num_rows > 0){
            return true;
        }else{
            return false;
        }
    }
?>
