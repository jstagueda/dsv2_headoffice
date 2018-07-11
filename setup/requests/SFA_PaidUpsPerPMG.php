<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: March 18, 2013
 */
    include('../../initialize.php');
    include('../config.php');
 
    $success_logs = LOGS_PATH.'process.log'; //process logs for status viewing in front end.
    
    //Prepared mysql statement for getting all computed PMG paid ups per type.
    $pmg_str = "SELECT summ.CustomerID,summ.IBMID,summ.CampaignMonth,summ.CampaignYear,pmg.PMGType,
            ROUND(( summ.TotalOnTimeInvoicePayment / summ.TotalInvoiceAmount) * pmg.TotalDGSAmount,2) AS PMGTypePaidUpDGS,summ.EnrollmentDate
            FROM tpi_sfasummary summ
            INNER JOIN tpi_sfasummary_pmg pmg 
                ON pmg.CustomerID = summ.CustomerID 
                AND pmg.CampaignMonth = summ.CampaignMonth 
                AND pmg.CampaignYear = summ.CampaignYear
            WHERE pmg.PMGType IN('NCFT','CFT','CPI')
            AND ROUND(( summ.TotalOnTimeInvoicePayment / summ.TotalInvoiceAmount) * pmg.TotalDGSAmount,2) NOT LIKE '%NULL%'
            AND ROUND(( summ.TotalOnTimeInvoicePayment / summ.TotalInvoiceAmount) * pmg.TotalDGSAmount,2) > 0
            ORDER BY pmg.PMGType DESC";
    
    tpi_file_truncate($success_logs);
    //Process of inserting to tpi_branchcollectionrating database table here...
    $pmgs_qry = $mysqli->query($pmg_str);
    try{
        while($row = $pmgs_qry->fetch_object()):
            $customerID = $row->CustomerID;
            $IBMID = $row->IBMID;
            $campMonth = $row->CampaignMonth;
            $campYear = $row->CampaignYear;
            $PMGType = strtoupper($row->PMGType);
            $PMGTypePaidUpDGS = $row->PMGTypePaidUpDGS;
            $EnrollmentDate = $row->EnrollmentDate;  
            
            $checkCID = doCheckIfInBCRTable($customerID,$campYear,$campMonth);

            if($checkCID){
                doPreparedUpdateBCRTable($customerID,$campYear,$campMonth,$PMGTypePaidUpDGS,$PMGType);
                tpi_error_log("UPDATED PMG Details for CustomerID: ".$customerID." Campaign Month:".$campMonth." Campaign Year: ".$campYear."\n <br />",$success_logs);
            }else{
                doPreparedInsertBCRTable($customerID,$IBMID,$campYear,$campMonth,$PMGTypePaidUpDGS,$EnrollmentDate,$PMGType);
                tpi_error_log("INSERTED PMG Details for CustomerID: ".$customerID." Campaign Month:".$campMonth." Campaign Year: ".$campYear."\n <br />",$success_logs);
            }

        endwhile;
    }catch(Exception $e){
        echo $e->getMessage();
    }
    
    //Prepared insert function for BCR table...
    function doPreparedInsertBCRTable($customerID,$IBMID,$campYear,$campMonth,$PMGTypePaidUpDGS,$EnrollmentDate,$type){
        global $mysqli;
        
        $i = $mysqli->prepare("INSERT INTO tpi_branchcollectionrating (`CustomerID`,`ParentID`,`Year`,`Month`,`PaidDGS$type`,`EnrollmentDate`) VALUES (?,?,?,?,?,?)");
        $i->bind_param('iiiiss',$customerID,$IBMID,$campYear,$campMonth,$PMGTypePaidUpDGS,$EnrollmentDate);
        $i->execute();
    }
    
    //Prepared update function for BCR table...
    function doPreparedUpdateBCRTable($customerID,$campYear,$campMonth,$PMGTypePaidUpDGS,$type){
        global $mysqli;
        
        $u = $mysqli->prepare("UPDATE `tpi_branchcollectionrating` SET `PaidDGS$type` = ? WHERE `CustomerID` = ? AND `Year` = ? AND `Month` = ?");
        $u->bind_param('siii',$PMGTypePaidUpDGS,$customerID,$campYear,$campMonth);
        $u->execute();
    }
    
    function doCheckIfInBCRTable($customerID,$campYear,$campMonth){
        global $mysqli;
        $checkCID = 0;
        
        $prep_check = $mysqli->prepare("SELECT `CustomerID` FROM tpi_branchcollectionrating WHERE `CustomerID` = ? AND `Year` = ? AND `Month` = ?");
        
        $prep_check->bind_param('iii',$customerID,$campYear,$campMonth);
        $prep_check->execute();
        $prep_check->bind_result($checkCID); //Customer ID if already exists...
        $prep_check->fetch();
        
        if($checkCID > 0) return true;
        else return false;
    }
?>
