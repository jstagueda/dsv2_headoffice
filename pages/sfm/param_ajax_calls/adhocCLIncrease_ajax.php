<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: May 03, 2013
 */
    include('../../../initialize.php');
    
    $post = $_POST;
    $returns = array();
    
    if($post['action'] == 'insert'){
        $desc = $post['description'];
        $SFL = $post['SFL']; //Sales Force Level ID
        $CT = $post['CT']; //Credit TermID
        $GSUType = $post['GSUType'];
        $eDate = $post['eDate']; //Effectivity Date
        
        //other post details...
        $MMRFEP = requestVariableCheckAndSet($post['min_mon_res_fr_eff_period']);
        $NOM_WOUT_PDA = requestVariableCheckAndSet($post['num_mon_wout_pda']);
        $POPeriodEnd = requestVariableCheckAndSet($post['poPeriodEnd']);
        $POPeriodStart = requestVariableCheckAndSet($post['poPeriodStart']);
        $MinPOAmt = requestVariableCheckAndSet($post['minPOAmt']);
        $CCLEnd = requestVariableCheckAndSet($post['CCLEnd']);
        $CCLStart = requestVariableCheckAndSet($post['CCLStart']);
        $MaxCLInc = requestVariableCheckAndSet($post['maxCLI']);
        $WithAdvancePayment = requestVariableCheckAndSet((isset($post['withAdvancePayment']) ? $post['withAdvancePayment']:''));
        $WAPMinAmt = requestVariableCheckAndSet($post['minAmount']);
        $WAPNoOfDays = requestVariableCheckAndSet($post['noOfDays']);
        $WAPPaymentDatePeriodStart = requestVariableCheckAndSet($post['PaymentDatePeriodStart']);
        $WAPPaymentDatePeriodEnd = requestVariableCheckAndSet($post['PaymentDatePeriodEnd']);
        $hasBOCLI = requestVariableCheckAndSet((isset($post['hasBOCLI']) ? $post['hasBOCLI']:''));
        $hasBOCLIOption = requestVariableCheckAndSet((isset($post['hasBOCLIOption']) ? $post['hasBOCLIOption']:''));
        $hasBOCLIOptPPOB = requestVariableCheckAndSet((isset($post['hasBOCLIOptPPOB']) ? $post['hasBOCLIOptPPOB']:''));
        $hasBOCLIValue = requestVariableCheckAndSet((isset($post['hasBOCLIValue']) ? $post['hasBOCLIValue']:''));
        
        $i = $mysqli->prepare("INSERT INTO `sfm_adhoc_cl`(`Description`,`Level`,`CreditTermId`,`CreditTypeID`,`EffectivityDate`) 
                               VALUES (?,?,?,?,?)");
        $iothers = $mysqli->prepare("INSERT INTO `sfm_adhoc_cl_criterias`(`AdhocID`,`MMRFEP`,`NOM_WOUT_PDA`,`POPeriodStart`,`POPeriodEnd`,
                               `MinPOAmt`,`CCLStart`,`CCLEnd`,`MaxCLInc`,`WithAdvancePayment`,`WAPMinAmt`,`WAPNoOfDays`,
                               `WAPPaymentDatePeriodStart`,`WAPPaymentDatePeriodEnd`,`hasBOCLI`,`hasBOCLIOption`,`hasBOCLIOptPPOB`,`hasBOCLIValue`) 
                               VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        
        if(!empty($desc) && !empty($SFL) && !empty($CT) && !empty($GSUType) && !empty($eDate)):
            
            //Start of validation in other fields...
            if($WithAdvancePayment == '1' && ($WAPMinAmt == 'NULL' || $WAPNoOfDays == 'NULL' || $WAPPaymentDatePeriodStart == 'NULL' || $WAPPaymentDatePeriodEnd == 'NULL')){
                    $returns['status'] = 'error';
                    $returns['message'] = 'You\'ve selected "with advance payment". Fields min. amount, no. of days and due date period are all required.';
                    
                    tpi_JSONencode($returns);
                    exit;
            }
            
            if($POPeriodEnd < $POPeriodStart){
                    $returns['status'] = 'error';
                    $returns['message'] = 'PO end period should be not less than PO start period, make sure it\'s correct.';
                    
                    tpi_JSONencode($returns);
                    exit;
            }
            
            if($CCLEnd < $CCLStart){
                    $returns['status'] = 'error';
                    $returns['message'] = 'Current Credit limit "TO" should be not less than current credit limit "FROM", make sure it\'s correct.';
                    
                    tpi_JSONencode($returns);
                    exit;
            }
            
            if($hasBOCLI == '1'){
                if($hasBOCLIOption == 'NULL' || ($hasBOCLIOption == 'PPOB' && $hasBOCLIOptPPOB == 'NULL')){
                    $returns['status'] = 'error';
                    $returns['message'] = 'You\'ve checked "Basis of CL Increase" options under it should have selections.';
                    
                    tpi_JSONencode($returns);
                    exit;
                }
                
                if($hasBOCLIValue == 'NULL' && $hasBOCLIOption != 'NULL'){
                    $returns['status'] = 'error';
                    $returns['message'] = 'Please provide value for the selected "Basis of CL Increase".';
                    
                    tpi_JSONencode($returns);
                    exit;
                }
            }
            //End of validations...
            
            $i->bind_param('siiis',$desc,$SFL,$CT,$GSUType,$eDate);
            $i->execute();
            $insert_id = $i->insert_id;
            
            if($insert_id){
                $iothers->bind_param('iiisssssssssssssss',$insert_id,$MMRFEP,$NOM_WOUT_PDA,$POPeriodStart,$POPeriodEnd,$MinPOAmt,
                                                     $CCLStart,$CCLEnd,$MaxCLInc,$WithAdvancePayment,$WAPMinAmt,$WAPNoOfDays,
                                                     $WAPPaymentDatePeriodStart,$WAPPaymentDatePeriodEnd,$hasBOCLI,$hasBOCLIOption,$hasBOCLIOptPPOB,$hasBOCLIValue);
                $iothers->execute();
            }
            
            $returns['status'] = 'success';
            $returns['message'] = 'New adhoc CL increase criteria added.';
        else:
            $returns['status'] = 'error';
            $returns['message'] = 'Adhoc CL increase criteria fails to insert, please make sure required fields are filled in.';
        endif;
        
        tpi_JSONencode($returns);
    }
    
    //Get lists of all CL ADHOCs....
    if($post['action'] == 'lists'){
        $start = $post['start'];
        $end = $post['end'];
        
        $l = $mysqli->query("SELECT cl.`AdhocID`,cl.`Description`,cl.`Level`,lvl.`desc_code`,cl.`CreditTermID`,
                                    ct.`Name` AS `ctName`,cl.`CreditTypeID`,gsu.`Name` AS `gsuName`,cl.`EffectivityDate` 
                            FROM `sfm_adhoc_cl` cl
                            LEFT JOIN `sfm_level` lvl ON lvl.`codeID` = cl.`Level`
                            LEFT JOIN `creditterm` ct ON ct.`ID` = cl.`CreditTermID`
                            LEFT JOIN `tpi_gsutype` gsu ON gsu.`ID` = cl.`CreditTypeID`
                            ORDER BY cl.`EffectivityDate` DESC
                            LIMIT $start,$end");
        $t = $mysqli->query('SELECT COUNT(*) AS total FROM `sfm_adhoc_cl`');
        $total = $t->fetch_object()->total;
        
        if($total > 0){
            while($row = $l->fetch_object()):
                $returns['lists'][] = $row;
            endwhile;
            
            $returns['status'] = 'success';
        }else{
            $returns['lists'] = array();
            $returns['status'] = 'error';
            $returns['message'] = 'No lists in database.';
        }
        
        $returns['total'] = $total;
        
        tpi_JSONencode($returns);
    }
    
    if($post['action'] == 'edit'){
        $ID = $post['adhocID'];
        $returns = array();
        
        $q = "SELECT 
                cl.`AdhocID`,cl.`Description`,cl.`Level`,cl.`CreditTermID`,cl.`CreditTypeID`,cl.`EffectivityDate`,
                clc.`MMRFEP`,clc.`NOM_WOUT_PDA`,clc.`POPeriodStart`,clc.`POPeriodEnd`,clc.`MinPOAmt`,clc.`CCLStart`,
                clc.`CCLEnd`,clc.`MaxCLInc`,clc.`WithAdvancePayment`,clc.`WAPMinAmt`,clc.`WAPNoOfDays`,clc.`WAPPaymentDatePeriodStart`,
                clc.`WAPPaymentDatePeriodEnd`,clc.`hasBOCLI`,clc.`hasBOCLIOption`,clc.`hasBOCLIOptPPOB`,clc.`hasBOCLIValue`
              FROM `sfm_adhoc_cl` cl
              INNER JOIN `sfm_adhoc_cl_criterias` clc ON clc.`AdhocID` = cl.`AdhocID`
              WHERE cl.`AdhocID` = ?";
        $d = $mysqli->query(str_replace('?',$ID,$q));
        
        if($d->num_rows > 0){
            $returns['status'] = 'success';
            $returns['data'] = $d->fetch_object();
        }else{
            $returns['status'] = 'error';
            $returns['data'] = array();
        }
        
        tpi_JSONencode($returns);
    }
    
    //Delete process of ADHOC criteria listing...
    if($post['action'] == 'delete'){
        $IdsForDelete = $post['IdsForDelete'];
        try{
            $mysqli->autocommit(FALSE);
            
            if($IdsForDelete){
                foreach($IdsForDelete as $id):
                    $mysqli->query("DELETE FROM `sfm_adhoc_cl` WHERE `AdhocID` = $id");
                    $mysqli->query("DELETE FROM `sfm_adhoc_cl_criterias` WHERE `AdhocID` = $id");
                endforeach;
            }
            
            $mysqli->commit();
            $mysqli->autocommit(TRUE);

            $returns['status'] = 'success';
            $returns['message'] = 'Items for deletion was successfully deleted.';
        }catch(Exception $e){
            $mysqli->rollback();
            $mysqli->autocommit(TRUE);
            
            $returns['status'] = 'error';
            $returns['message'] = 'Failure in deleting selected items.';
        }

        tpi_JSONencode($returns);
    }
    
    if($post['action'] == 'update'){
        $AdhocID = $post['editID'];
        $desc = $post['description'];
        $SFL = $post['SFL']; //Sales Force Level ID
        $CT = $post['CT']; //Credit TermID
        $GSUType = $post['GSUType'];
        $eDate = $post['eDate']; //Effectivity Date
        
        //other post details...
        $MMRFEP = requestVariableCheckAndSet($post['min_mon_res_fr_eff_period']);
        $NOM_WOUT_PDA = requestVariableCheckAndSet($post['num_mon_wout_pda']);
        $POPeriodEnd = requestVariableCheckAndSet($post['poPeriodEnd']);
        $POPeriodStart = requestVariableCheckAndSet($post['poPeriodStart']);
        $MinPOAmt = requestVariableCheckAndSet($post['minPOAmt']);
        $CCLEnd = requestVariableCheckAndSet($post['CCLEnd']);
        $CCLStart = requestVariableCheckAndSet($post['CCLStart']);
        $MaxCLInc = requestVariableCheckAndSet($post['maxCLI']);
        $WithAdvancePayment = requestVariableCheckAndSet((isset($post['withAdvancePayment']) ? $post['withAdvancePayment']:''));
        $WAPMinAmt = requestVariableCheckAndSet($post['minAmount']);
        $WAPNoOfDays = requestVariableCheckAndSet($post['noOfDays']);
        $WAPPaymentDatePeriodStart = requestVariableCheckAndSet($post['PaymentDatePeriodStart']);
        $WAPPaymentDatePeriodEnd = requestVariableCheckAndSet($post['PaymentDatePeriodEnd']);
        $hasBOCLI = requestVariableCheckAndSet((isset($post['hasBOCLI']) ? $post['hasBOCLI']:''));
        $hasBOCLIOption = requestVariableCheckAndSet((isset($post['hasBOCLIOption']) ? $post['hasBOCLIOption']:''));
        $hasBOCLIOptPPOB = requestVariableCheckAndSet((isset($post['hasBOCLIOptPPOB']) ? $post['hasBOCLIOptPPOB']:''));
        $hasBOCLIValue = requestVariableCheckAndSet((isset($post['hasBOCLIValue']) ? $post['hasBOCLIValue']:''));
        
        $u = $mysqli->prepare("UPDATE `sfm_adhoc_cl` SET `description` = ?,`Level` = ? ,`CreditTermId` = ?,
                              `CreditTypeID` = ?,`EffectivityDate` = ?, `Changed` = 1
                               WHERE `AdhocID` = ? ");
        $uothers = $mysqli->prepare("UPDATE `sfm_adhoc_cl_criterias` SET `MMRFEP` = ?,`NOM_WOUT_PDA` = ?,
                                   `POPeriodStart` = ?,`POPeriodEnd` = ?,`MinPOAmt` = ?,`CCLStart` = ?,`CCLEnd` = ?,
                                   `MaxCLInc` = ?,`WithAdvancePayment` = ?,`WAPMinAmt` = ?,`WAPNoOfDays` = ?,`WAPPaymentDatePeriodStart` = ?,
                                   `WAPPaymentDatePeriodEnd` = ?,`hasBOCLI` = ?,`hasBOCLIOption` = ?,`hasBOCLIOptPPOB` = ?, `hasBOCLIValue` = ?, `Changed` = 1
                                   WHERE `AdhocID` = ? ");
        
        if(!empty($desc) && !empty($SFL) && !empty($CT) && !empty($GSUType) && !empty($eDate)):

            //Start of validation in other fields...
            if($WithAdvancePayment == '1' && ($WAPMinAmt == 'NULL' || $WAPNoOfDays == 'NULL' || $WAPPaymentDatePeriodStart == 'NULL' || $WAPPaymentDatePeriodEnd == 'NULL')){
                    $returns['status'] = 'error';
                    $returns['message'] = 'You\'ve selected "with advance payment". Fields min. amount, no. of days and due date period are all required.';
                    
                    tpi_JSONencode($returns);
                    exit;
            }
            
            if($POPeriodEnd < $POPeriodStart){
                    $returns['status'] = 'error';
                    $returns['message'] = 'PO end period should be not less than PO start period, make sure it\'s correct.';
                    
                    tpi_JSONencode($returns);
                    exit;
            }
            
            if($CCLEnd < $CCLStart){
                    $returns['status'] = 'error';
                    $returns['message'] = 'Current Credit limit "TO" should be not less than current credit limit "FROM", make sure it\'s correct.';
                    
                    tpi_JSONencode($returns);
                    exit;
            }
            
            if($hasBOCLI == '1'){
                if($hasBOCLIOption == 'NULL' || ($hasBOCLIOption == 'PPOB' && $hasBOCLIOptPPOB == 'NULL')){
                    $returns['status'] = 'error';
                    $returns['message'] = 'You\'ve checked "Basis of CL Increase" options under it should have selections.';
                    
                    tpi_JSONencode($returns);
                    exit;
                }
                
                if($hasBOCLIValue == 'NULL' && $hasBOCLIOption != 'NULL'){
                    $returns['status'] = 'error';
                    $returns['message'] = 'Please provide value for the selected "Basis of CL Increase".';
                    
                    tpi_JSONencode($returns);
                    exit;
                }
            }
            //End of validations...
            
            $u->bind_param('siiisi',$desc,$SFL,$CT,$GSUType,$eDate,$AdhocID);
            $u->execute();
            
            if($AdhocID){
                $uothers->bind_param('iisssssssssssssssi',$MMRFEP,$NOM_WOUT_PDA,$POPeriodStart,$POPeriodEnd,$MinPOAmt,
                                                     $CCLStart,$CCLEnd,$MaxCLInc,$WithAdvancePayment,$WAPMinAmt,$WAPNoOfDays,
                                                     $WAPPaymentDatePeriodStart,$WAPPaymentDatePeriodEnd,$hasBOCLI,$hasBOCLIOption,$hasBOCLIOptPPOB,$hasBOCLIValue,$AdhocID);
                $uothers->execute();
            }
            
            $returns['status'] = 'success';
            $returns['message'] = 'Update on adhoc CL increase criteria successful.';
        else:
            $returns['status'] = 'error';
            $returns['message'] = 'Adhoc CL increase criteria fails to update, please make sure required fields are filled in.';
        endif;
        
        tpi_JSONencode($returns);
    }
    
    
    if($post['action'] == 'get_cl_options'){
        $l = $mysqli->query("SELECT 
                                cl.`AdhocID`,cl.`Description`,cl.`Level`,cl.`CreditTermID`,cl.`CreditTypeID`,cl.`EffectivityDate`,
                                clc.`MMRFEP`,clc.`NOM_WOUT_PDA`,clc.`POPeriodStart`,clc.`POPeriodEnd`,clc.`MinPOAmt`,clc.`CCLStart`,
                                clc.`CCLEnd`,clc.`MaxCLInc`,clc.`WithAdvancePayment`,clc.`WAPMinAmt`,clc.`WAPNoOfDays`,clc.`WAPPaymentDatePeriodStart`,
                                clc.`WAPPaymentDatePeriodEnd`,clc.`hasBOCLI`,clc.`hasBOCLIOption`,clc.`hasBOCLIOptPPOB`,clc.`hasBOCLIValue`
                            FROM `sfm_adhoc_cl` cl
                            INNER JOIN `sfm_adhoc_cl_criterias` clc ON clc.`AdhocID` = cl.`AdhocID`
                            ORDER BY cl.`EffectivityDate` DESC");
        $t = $mysqli->query('SELECT COUNT(*) AS `total` 
                             FROM `sfm_adhoc_cl` cl
                             INNER JOIN `sfm_adhoc_cl_criterias` clc ON clc.`AdhocID` = cl.`AdhocID`');
        $total = $t->fetch_object()->total;
        
        if($total > 0){
            while($row = $l->fetch_object()):
                $returns['lists'][] = $row;
            endwhile;
            
            $returns['status'] = 'success';
        }else{
            $returns['lists'] = array();
            $returns['status'] = 'error';
            $returns['message'] = 'No lists in database.';
        }
        
        tpi_JSONencode($returns);
    }
    
    /*
     * Start of processing ADHOC CL Increase, for branch users.
     */
    if($post['action'] == 'generate'){
        if(($ADHOCDet = json_decode($post['fullDetails'])) != NULL){
            $where = '';
            $joins = '';
            $CLIncrease = 0;
            $start = $post['start'];
            $end = $post['end'];
            
            //Let's unset old set sessions used for printing...
            if(isset($_SESSION['BasisOfCLIncrease'])) unset($_SESSION['BasisOfCLIncrease']);
            if(isset($_SESSION['WithAdvancePayment'])) unset($_SESSION['WithAdvancePayment']);
            if(isset($_SESSION['PO'])) unset($_SESSION['PO']);
           
            //Mimimum months residency from effectivity period
            if(!empty($ADHOCDet->MMRFEP) || $ADHOCDet->MMRFEP != 0){
                $where.= " AND DATE(c.`EnrollmentDate`) <= DATE('$ADHOCDet->EffectivityDate' - INTERVAL $ADHOCDet->MMRFEP MONTH)";
            }
            
            //No. of months without PDA
            if(!empty($ADHOCDet->NOM_WOUT_PDA) || $ADHOCDet->NOM_WOUT_PDA != 0){
                $joins.= " LEFT JOIN (SELECT `CustomerID`,COUNT(`CustomerID`) AS PDACount
                                      FROM `tpi_rcustomerpda` 
                                      WHERE DATE(`EnrollmentDate`) <= DATE('$ADHOCDet->EffectivityDate' - INTERVAL $ADHOCDet->NOM_WOUT_PDA MONTH)) 
                                      pda ON pda.`CustomerID` = c.`ID`";
                $where.= " AND ISNULL(pda.`PDACount`)";
            }
            
            //P.O. Period OR Minimum P.O. Amount
            //Different cases for POs...
            if(($ADHOCDet->POPeriodStart != '0000-00-00' && $ADHOCDet->POPeriodEnd != '0000-00-00') && $ADHOCDet->MinPOAmt > 0){
                $joins.= " INNER JOIN (SELECT so.`CustomerID`,so.`TxnDate`,so.`CreditTermID`,so.`NetAmount` 
                                        FROM `salesinvoice` si
                                        INNER JOIN `salesorder` so ON so.`CustomerID` = si.`CustomerID`
                                        WHERE DATE(so.`TxnDate`) BETWEEN '$ADHOCDet->POPeriodStart' AND '$ADHOCDet->POPeriodEnd'
                                        AND so.`CreditTermID` = $ADHOCDet->CreditTermID 
                                        AND so.`NetAmount` >= $ADHOCDet->MinPOAmt
                                        AND so.`StatusID` = 7
                                        GROUP BY so.`CustomerID`
                                      ) siso ON siso.`CustomerID` = c.`ID`";
            }else if(($ADHOCDet->POPeriodStart == '0000-00-00' && $ADHOCDet->POPeriodEnd == '0000-00-00') && $ADHOCDet->MinPOAmt > 0){
                $joins.= " INNER JOIN (SELECT so.`CustomerID`,so.`TxnDate`,so.`CreditTermID`,so.`NetAmount` 
                                        FROM `salesinvoice` si
                                        INNER JOIN `salesorder` so ON so.`CustomerID` = si.`CustomerID`
                                        WHERE so.`NetAmount` >= $ADHOCDet->MinPOAmt
                                        AND so.`CreditTermID` = $ADHOCDet->CreditTermID
                                        AND so.`StatusID` = 7
                                        GROUP BY so.`CustomerID`
                                      ) siso ON siso.`CustomerID` = c.`ID`";
            }
            
            //With advance payment...
            if($ADHOCDet->WithAdvancePayment > 0){
                $joins.= " INNER JOIN (
                                        SELECT si.`CustomerID`,si.`TxnDate`,so.`NetAmount`,ct.`Duration`
					FROM `salesinvoice` si
					LEFT JOIN creditterm ct ON ct.`ID` = $ADHOCDet->CreditTermID
					INNER JOIN `salesorder` so ON so.`CustomerID` = si.`CustomerID`
					WHERE so.`NetAmount` >= $ADHOCDet->WAPMinAmt
					AND so.`TxnDate` BETWEEN '$ADHOCDet->WAPPaymentDatePeriodStart' AND '$ADHOCDet->WAPPaymentDatePeriodEnd'
					AND si.`TxnDate` BETWEEN '$ADHOCDet->WAPPaymentDatePeriodStart' AND '$ADHOCDet->WAPPaymentDatePeriodEnd'
					AND TIMESTAMPDIFF(DAY,DATE(si.`LastPaymentDate`),DATE_ADD(DATE(si.`TxnDate`),INTERVAL ct.`Duration` DAY)) >= $ADHOCDet->WAPNoOfDays
                                        AND si.`StatusID` = 7
					GROUP BY si.`CustomerID`
			             ) siAd ON siAd.`CustomerID` = c.`ID`";
            }
            //End of cases...
            
            //Current Credit Limit range value...
            if($ADHOCDet->CCLStart > 0 && $ADHOCDet->CCLEnd > 0){
                $where.= " AND cr.`ApprovedCL` BETWEEN $ADHOCDet->CCLStart AND $ADHOCDet->CCLEnd";
            }
                                    
            //Main query string statement used for getting lists of qualified customers...
            $query_str = "SELECT SQL_CALC_FOUND_ROWS
                            c.`ID`,TRIM(c.`Code`) AS `Code`,TRIM(c.`Name`) AS `Name`,
                            DATE_FORMAT(c.`EnrollmentDate`,'%b %d, %Y') AS `EnrollmentDate`,cr.`ApprovedCL`
                          FROM `customer` c
                          INNER JOIN (SELECT `CustomerID`,`CustomerStatusID`,MAX(`EnrollmentDate`) FROM `tpi_rcustomerstatus` GROUP BY `CustomerID`) stat ON stat.`CustomerID` = c.`ID`
                          INNER JOIN `tpi_customerdetails` cd ON cd.`CustomerID` = c.`ID`
                          INNER JOIN `tpi_credit` cr ON cr.`CustomerID` = c.`ID`
                          $joins
                          WHERE 
                            c.`CustomerTypeID` = $ADHOCDet->Level
                            AND stat.`CustomerStatusID` IN(1,4)
                            AND cr.`CreditTermID` = $ADHOCDet->CreditTermID
                            AND cd.`tpi_GSUTypeID` = $ADHOCDet->CreditTypeID
                            $where
                          LIMIT $start, $end
                        ";
            error_log($query_str);
            $l = $mysqli->query($query_str);
            $found_rows = $mysqli->query("SELECT IFNULL(FOUND_ROWS(),0) AS `Total`;");
            
            if($l->num_rows > 0){
                while($row = $l->fetch_object()):
                    $returns['results'][] = $row;
                endwhile;
                
                $returns['status'] = 'success';
                //Set the CL increase to be used or pass.
                if($ADHOCDet->hasBOCLI > 0){
                    $_SESSION['BasisOfCLIncrease']['Option'] = $ADHOCDet->hasBOCLIOption;
                    $_SESSION['BasisOfCLIncrease']['OptionPPOB'] = $ADHOCDet->hasBOCLIOptPPOB;
                    $_SESSION['BasisOfCLIncrease']['Value'] = $ADHOCDet->hasBOCLIValue;
                }
                
                //Let's set session that will handle values from qualification With advance payment...
                //This can be used in printing and auti CL increase application...
                if($ADHOCDet->WithAdvancePayment > 0){
                    $_SESSION['WithAdvancePayment']['CreditTermID'] = $ADHOCDet->CreditTermID;
                    $_SESSION['WithAdvancePayment']['MinAmt'] = $ADHOCDet->WAPMinAmt;
                    $_SESSION['WithAdvancePayment']['PaymentDatePeriodStart'] = $ADHOCDet->WAPPaymentDatePeriodStart;
                    $_SESSION['WithAdvancePayment']['PaymentDatePeriodEnd'] = $ADHOCDet->WAPPaymentDatePeriodEnd;
                    $_SESSION['WithAdvancePayment']['NoOfDays'] = $ADHOCDet->WAPNoOfDays;
                }
                
                $_SESSION['CLI_CreditTermID'] = $ADHOCDet->CreditTermID;
                //Used for P.O.
                $_SESSION['PO']['POPeriodStart'] = $ADHOCDet->POPeriodStart;
                $_SESSION['PO']['POPeriodEnd'] = $ADHOCDet->POPeriodEnd;
                $_SESSION['PO']['MinPOAmt'] = $ADHOCDet->MinPOAmt;
            }else{
                $returns['results'] = array();
                $returns['status'] = 'error';
                $returns['message'] = 'No results found.';
            }
            
            $returns['total'] = $found_rows->fetch_object()->Total;
            
            tpi_JSONencode($returns);
        }
    }
    
    if($post['action'] == 'prepare_for_printing'){
        $IDsForPrinting = $post['IdsForPrinting'];
        $_SESSION['IDsAdhocCLIForPrinting'] = $IDsForPrinting;
        
        $returns['status'] = 'success';
        
        tpi_JSONencode($returns);
    }
    
    function requestVariableCheckAndSet($var){
        if(isset($var) && !empty($var)){
            return $var;
        }else{
            return 'NULL';
        }
    }
    
?>
