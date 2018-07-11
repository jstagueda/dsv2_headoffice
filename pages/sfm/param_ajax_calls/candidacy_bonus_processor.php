<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: March 21, 2013
 */

    include('../../../initialize.php');
    
    $post = $_POST;
    $returns = array();
    
    if($post['action'] == 'generate'){
        $SFlevel = $post['SFLevel'];
        $CampaignCode = $post['CampaignCode'];
        $CampaingMonth = date('n',strtotime(substr($CampaignCode,0,3)));
        $CampaingYear = substr($CampaignCode,3,2);
        
        try{
            $res = $mysqli->query("CALL spProcessCandidacyBonus($SFlevel,$CampaingMonth,$CampaingYear)");
            
            if($res){
               $status = $res->fetch_object()->Status;
               
                if($status){
                    $returns['status'] = 'success';
                }else{
                    $returns['status'] = 'error';
                    $returns['message'] = 'No one passed on candidacy bonus.';
                }  
            }
        }catch(Exception $e){
            $returns['status'] = 'error';
            $returns['message'] = 'Error on procedure, please contact system support. ('. $e->getMessage() .')';
        }
        
        tpi_JSONencode($returns);
    }
    
    if($post['action'] == 'lists'){
        $res = $mysqli->query("SELECT c.ID,TRIM(c.Code) AS Code,c.Name,FORMAT(cc.`EarnedBonusAmount`,2) AS `EarnedBonusAmount`,
                               FORMAT(cc.`VAT`,2) AS `VAT`,FORMAT(cc.`WithHoldingTax`,2) AS `WithHoldingTax`,
                               UPPER(DATE_FORMAT(STR_TO_DATE(CONCAT(cc.`CampaignMonth`,'-',cc.`CampaignYear`),'%c-%y'),'%b%y')) AS `CampaignCode`
                               FROM `customercommission` cc 
                               INNER JOIN customer c ON c.ID = cc.CustomerID
                               WHERE DATE(cc.`LastModifiedDate`) = CURRENT_DATE()
                               AND `CommissionTypeID` = 3");
        $lists = array();
        
        if($res->num_rows > 0){
            while($row = $res->fetch_object()):
               $lists[] = $row; 
            endwhile;
            
            $returns['status'] = 'success';
            $returns['lists'] = $lists;
        }else{
            $returns['status'] = 'error';
            $returns['message'] = 'No one passed for candidacy bonus.';
            $returns['lists'] = $lists;
        }
        
        tpi_JSONencode($returns);
    } 
?>
