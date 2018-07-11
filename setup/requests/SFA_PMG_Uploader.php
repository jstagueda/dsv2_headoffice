<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: March 12, 2013
 */

    include('../../initialize.php');
    include('../config.php');
    
    $file = '../data/sfm-02.txt';
    $failed_logs = LOGS_PATH.'sfa_pmg_uploads.log';
    $success_logs = LOGS_PATH.'process.log';
    $fp = file($file);//let's get the file contents now...
    
    //clean file handler first...
    tpi_file_truncate($failed_logs);
    tpi_file_truncate($success_logs);
    $mysqli->query('TRUNCATE TABLE `tpi_sfasummary_pmg`');//truncate or empty table contents...
    
    foreach($fp as $l):
            list($CampaignCode,        //Month-Year or Campaign
                $EOMDate,              //End Of Month Date
                $CustomerCode,         //Dealer Code
                $SFLvlType,            //Sales Force Level
                $UpLineSFCode,         //Mother or Upline Sales Force ID
                $PMGType,              //PMG-Product Market Group Type (i.e. NCFT,CPI and etc.)
                $TotalInvoiceAmt,      //Total Invoice Amount
                $TotalDGSAmt           //Total DGS Amount
            ) = @explode(' ',$l);
        
        //Clean data to pass first to avoid error on database insertion...
        $CampaignCode = trim(str_replace('"','',$CampaignCode));
        $CampaingMonth = date('n',strtotime(substr($CampaignCode,0,3)));
        $CampaingYear = substr($CampaignCode,3,2);
        
        /*
         * We do format first the EOM date from text file because it is formatted in English format M/D/Y.
         * It should be formatted to ISO Y.M.D which is period delimited.
         */
        $EOMDate = date('Y-m-d', strtotime(str_replace('/','.',trim($EOMDate))));
        
        $UpLineSFCode = trim(str_replace('"','',$UpLineSFCode));
        $PMGType = trim(str_replace('"','',$PMGType));
        $CustomerCode = trim(str_replace('"','',$CustomerCode));
        $SFLvlType = trim(str_replace('"','',$SFLvlType));
        $TotalInvoiceAmt = trim($TotalInvoiceAmt);
        $TotalDGSAmt = trim($TotalDGSAmt);
        
        //These will set all real IDs used in the system processes...
        $rCustomerIDTypeID = SFAgetCustomerIDTypeIDByCode($CustomerCode); //Customer real IDs
        $rUpLineIDTypeID = SFAgetCustomerIDTypeIDByCode($UpLineSFCode); //Upline or Mother IBMs real IDs
        
        //let's make sure first that both have existing customer IDs to avoid error on the summary table...
        if($rCustomerIDTypeID && $rUpLineIDTypeID){
            SFAPMGPreparedInsert($CampaignCode,$CampaingMonth,$CampaingYear,$rUpLineIDTypeID->ID,$rCustomerIDTypeID->ID,$rCustomerIDTypeID->TypeID,$PMGType,$TotalInvoiceAmt,$TotalDGSAmt,$EOMDate);
            
            //let's log successful inserts of records..
            tpi_error_log("INSERTED record for UplineNumber: ".$UpLineSFCode." of CustomerCode: ".$CustomerCode.", INSERT GOOD.\n <br />",$success_logs);
        }else{
            //let's log fail inserts here...
            if(!$rUpLineIDTypeID) tpi_error_log("Missing record for UplineNumber: ".$UpLineSFCode." of CustomerCode: ".$CustomerCode.", INSERT FAILS.\n <br />",$failed_logs);
            if(!$rCustomerIDTypeID) tpi_error_log("Missing record for CustomerCode: ".$CustomerCode." having UplineNumber: ".$UpLineSFCode.", INSERT FAILS.\n <br />",$failed_logs);
        }
    endforeach;
    
    
    /***** Functions used in this process *****/
    
    /*
     * @author: jdymosco
     * @date: March 08, 2013
     * @description: Function that get's customer details ID and Sales Force Level ID
     */
    function SFAgetCustomerIDTypeIDByCode($Code){
        global $mysqli;
        $returns = array();
        $ID = 0;
        $CTypeID = 0;
        
        //let's prepare first the query so we can avoid SQL injection...
        $q = $mysqli->prepare("SELECT `ID`,`CustomerTypeID` FROM `customer` WHERE `Code` = ?");
        $q->bind_param('s',$Code);
        $q->execute();
        $q->bind_result($ID,$CTypeID); //assign fetch results into variables...
        $q->fetch();
        
        if($ID){
           $returns['ID'] = $ID; //Customer ID
           $returns['TypeID'] = $CTypeID; //Customer type ID or Sales Force Level ID
        }else{
           return false; 
        }
        
        return (object) $returns;
    }
    
    function SFAPMGPreparedInsert($CampaignCode,$CampaingMonth,$CampaingYear,$UpLineSFID,$CustomerID,$SFLvlID,$PMGType,$TotalInvoiceAmt,$TotalDGSAmt,$EOMDate){
       
        global $mysqli;
        
        try{
            $NOW = date('Y-m-d h:i:s');
            $CreatedBy = 1;
            
            $i = $mysqli->prepare("INSERT INTO `tpi_sfasummary_pmg`
                                    (`CampaignCode`,`CampaignMonth`,`CampaignYear`,`IBMID`,`LevelID`,`CustomerID`,`PMGType`,`TotalInvoiceAmount`,`TotalDGSAmount`,`EOMDate`,`EnrollmentDate`,`CreatedBy`) 
                                    VALUES
                                    (?,?,?,?,?,?,?,?,?,?,?,?)");
            $i->bind_param('siiiiisiissi',$CampaignCode,$CampaingMonth,$CampaingYear,$UpLineSFID,$SFLvlID,$CustomerID,$PMGType,$TotalInvoiceAmt,$TotalDGSAmt,$EOMDate,$NOW,$CreatedBy);
            $i->execute();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    
    /***** Functions used in this process *****/
?>
