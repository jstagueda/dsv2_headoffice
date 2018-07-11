<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: July 09, 2013
 */
    include('../../../initialize.php');    
    $post = $_POST;
    $returns = array();
    
    //Generation report process
    if($post['action'] == 'generate'){
        $BranchID = $post['branch'];
        $SFL = @explode('_',$post['SFL']);
        $SFL_ID = $SFL[0]; //Sales force level ID
        $CanPurchase = $SFL[1]; //Sales force level field determitor if can purchase or not.
        $accountFrom = $post['accountFrom'];
        $accountTo = $post['accountTo'];
        $CLC = $post['CLC']; //Network classification
        
        try{
            $q = HOReportAccountBalanceInquiryQuery($BranchID,$SFL_ID,$CanPurchase,$accountFrom,$accountTo,$CLC);
        
            if($q->num_rows > 0):
                while($row = $q->fetch_object()):
                    $lists[] = $row;
                endwhile;
                
                $returns['status'] = 'success';
                $returns['lists'] = $lists;
            else:
                $returns['status'] = 'error';
                $returns['lists'] = $lists;
                $returns['message'] = 'No record(s) found.';
            endif;
        }catch(Exception $e){
            $returns['status'] = 'error';
            $returns['message'] = $e->getMessage();
        }
        
        tpi_JSONencode($returns);
    }
    
    if($post['action'] == 'get_networks'){
        $IBMID = $post['IBMID'];
        $BranchID = $post['branch'];
        $networks = array();
        
        try{
            $q = HOReportABIGetNetworksQuery($BranchID,$IBMID);
            if($q->num_rows > 0){
                while($row = $q->fetch_object()):
                    $networks[] = $row;
                endwhile;

                $returns['status'] = 'success';
                $returns['lists'] = $networks;
            }else{
                $returns['status'] = 'error';
                $returns['lists'] = $networks;
                $returns['message'] = 'No record(s) found.';
            }
        }catch(Exception $e){
            $returns['status'] = 'error';
            $returns['message'] = $e->getMessage();
        }
        
        tpi_JSONencode($returns);
    }
?>
