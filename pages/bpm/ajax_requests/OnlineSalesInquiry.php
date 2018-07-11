<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: July 05, 2013
 */
    include('../../../initialize.php');
    $post = $_POST;
    $returns = array();
    
    //Generation report process
    if($post['action'] == 'generate'){
        $BranchID = $post['branch'];
        $Month = date('n',strtotime($post['date']));
        $Year = date('y',strtotime($post['date']));
        $CURDATE = date('Y-m-d',strtotime($post['date']));
        
        try{
            $q = HOReportOnlineSalesInquiryQuery($BranchID,$Month,$Year,$CURDATE);
            
            $today = $q->Today; 
            $c = $q->Campaign;
            
            //For today values...
            if($today->num_rows){
                $Today = $today->fetch_object();
                
                $returns['TodayTotalDGS'] = $Today->TodayTotalDGS;
                $returns['TodayTotaInvoiceAmount'] = $Today->TodayTotaInvoiceAmount;
                $returns['TodayTotalDGSLessCPI'] = $Today->TodayTotalDGSLessCPI;
                $returns['TodayTotalInvoiceAmountLessCPI'] = $Today->TodayTotalInvoiceAmountLessCPI;
            }else{
                $returns['TodayTotalDGS'] = 0.00;
                $returns['TodayTotaInvoiceAmount'] = 0.00;
                $returns['TodayTotalDGSLessCPI'] = 0.00;
                $returns['TodayTotalInvoiceAmountLessCPI'] = 0.00;
            }
            
            //For campaing to date return values....
            if($c->num_rows){
                $Campaign = $c->fetch_object();
                
                $returns['CampaignToDateDGS'] = $Campaign->CampaignToDateDGS;
                $returns['InvoiceAmount'] = $Campaign->InvoiceAmount;
                $returns['CampaignToDateDGSLessCPI'] = $Campaign->CampaignToDateDGSLessCPI;
                $returns['InvoiceAmountLessCPI'] = $Campaign->InvoiceAmountLessCPI;
            }else{
                $returns['CampaignToDateDGS'] = 0.00;
                $returns['InvoiceAmount'] = 0.00;
                $returns['CampaignToDateDGSLessCPI'] = 0.00;
                $returns['InvoiceAmountLessCPI'] = 0.00;
            }
            
            $returns['status'] = 'success';
        }catch(Exception $e){
            $returns['status'] = 'error';
            $returns['message'] = $e->getMessage();
        }
        
        tpi_JSONencode($returns);
    }
?>
