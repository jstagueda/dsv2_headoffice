<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: April 25, 2013
 */

    include('../../../initialize.php');
    $post = $_POST;
    $returns = array();    
    
    if($post['action'] == 'search'){
        $term = $post['input_term'];
        $SFL = $post['SFL'];
        $lists = array();
        
        $s = $mysqli->query("SELECT c.`ID`,TRIM(c.`Code`) AS `Code`,TRIM(c.`Name`) AS `Name`,ibm.`IBMID`,
                                    cIBM.`Code` AS `IBMCode`,cIBM.`Name` AS `IBMName`, FORMAT(crIBM.`ApprovedCL`,2) AS `IBMCredit`,
                                    cd.`tpi_GSUTypeID`, cr.`CreditTermID`,cr.`ApprovedCL`
                             FROM `customer` c
                             INNER JOIN `tpi_customerdetails` cd ON cd.`CustomerID` = c.`ID`
                             INNER JOIN `tpi_credit` cr ON cr.`CustomerID` = c.`ID`
                             INNER JOIN (SELECT `CustomerID`,`IBMID`,MAX(`EnrollmentDate`) FROM `tpi_rcustomeribm` GROUP BY `CustomerID`) ibm ON ibm.`CustomerID` = c.`ID`
                             INNER JOIN `customer` cIBM ON cIBM.`ID` = ibm.`IBMID`
                             INNER JOIN `tpi_credit` crIBM ON crIBM.`CustomerID` = cIBM.`ID`
                             WHERE 
                             (c.`Code` LIKE '%$term%' || c.`Name` LIKE '%$term%' || c.`FirstName` LIKE '%$term%' || 
                              c.`LastName` LIKE '%$term%' || c.`MiddleName` LIKE '%$term%') 
                             AND c.`CustomerTypeID` = $SFL");
        
        if($s->num_rows > 0){
            while($row = $s->fetch_object()):
                $lists[] = $row;
            endwhile;
            
            $returns['lists'] = $lists;
            $returns['status'] = 'success';
        }else{
            $returns['lists'] = $lists;
            $returns['status'] = 'error';
        }
        $s->free();
        
        
        tpi_JSONencode($returns);
    }
    
    if($post['action'] == 'update'){
        $CustomerID = $post['CustomerID'];
        $IBMID = $post['IBMID'];
        $NewGSU = (empty($post['NewGSUType']) ? $post['OldGSUType'] : $post['NewGSUType']); //GSU Type ID
        $NewCL = (empty($post['NewCL']) ? $post['OldCL'] : $post['NewCL']); //Credit Limit
        $NewCT = (empty($post['NewCT']) ? $post['OldCT'] : $post['NewCT']); //Credit Term ID
        $OldGSU = $post['OldGSUType'];
        $OldCL = $post['OldCL'];
        $OldCT = $post['OldCT'];
        
        $FromTo = "{ DETAILS: CustomerID: $CustomerID, GSUTypeID { From: $OldGSU To: $NewGSU}, CreditLimit { From: $OldCL To: $NewCL}, CreditTermID { From: $OldCT To: $NewCT} }";
        
        //Update conditioning starts now...
        if($NewGSU == '1'): //If NonGSU type...
            $update = thisUpdateFinanceDetails($CustomerID,$NewCT,$NewCL,$NewGSU);
        
            if($update){
                $returns['message'] = 'Financial details updated.';
                $returns['status'] = 'success';
                thisDoFDMLog("Financial details successfully updated. $FromTo\n");
            }else{
                $returns['message'] = 'Updating financial details fails, please contact administrator.';
                $returns['status'] = 'success';
                thisDoFDMLog("Updating financial details fails, please contact administrator.\n");
            }
        else:    
            //Start of checking for GSU type is indirect and direct...
            $availableCredit = 0.00;
            $MotherID = $IBMID;
            $netACL = $mysqli->query("SELECT GetAvailableCreditByCustomerID($MotherID) AS availableCredit");
            if($netACL->num_rows > 0){
                while($row = $netACL->fetch_object()):
                    $availableCredit = $row->availableCredit;
                endwhile;
            }
            $netACL->free();
            
            
            $IBMAVCL = round($availableCredit,2);

            if($NewCL > $IBMAVCL):
                $returns['message'] = 'New credit limit cannot be greater than network available credit limit.';
                $returns['status'] = 'error';
                thisDoFDMLog("New credit limit cannot be greater than network available credit limit. $FromTo\n");
            else:
                $update = thisUpdateFinanceDetails($CustomerID,$NewCT,$NewCL,$NewGSU);
                if($update){
                    $returns['message'] = 'Financial details updated.';
                    $returns['status'] = 'success';
                    thisDoFDMLog("Financial details successfully updated. $FromTo\n");
                }else{
                    $returns['message'] = 'Updating financial details fails, please contact administrator.';
                    $returns['status'] = 'error';
                    thisDoFDMLog("Updating financial details fails, please contact administrator.\n");
                }
            endif;
        endif;
        
        tpi_JSONencode($returns);
    }
    
    /*
     * @author: jdymosco
     * @date: April 26, 2013
     * @description: Function used for updating customer credit limit and GSU type...
     */
    function thisUpdateFinanceDetails($CustomerID,$CTermID,$CLimit,$GSUTypeID){
        global $mysqli;
        
        $cr = $mysqli->query("UPDATE `tpi_credit` SET `CreditTermID` = $CTermID, `ApprovedCL` = $CLimit WHERE `CustomerID` = $CustomerID");
        $gsu = $mysqli->query("UPDATE `tpi_customerdetails` SET `tpi_GSUTypeID` = $GSUTypeID WHERE `CustomerID` = $CustomerID");
        
        if($cr && $gsu){ return true; }
        
        return false;
    }
    
    /*
     * @author: jdymosco
     * @date: April 26, 2013
     * @description: Function used for creating logs in every update or actions made in FDM...
     */
    function thisDoFDMLog($msg){
        global $session;
        
        $date_log = date('Y-m-d H:i:s A');
        $user = $session->user_id;
        $log_msg = "[$date_log][USER ID: $user] - ".$msg;
        
        tpi_error_log($log_msg,'../../../logs/FDM_logs.log');
    }
?>
