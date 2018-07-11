<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: July 08, 2013
 */
    include('../../../initialize.php');
    $post = $_POST;
    $returns = array();
    
    //Generation report process
    if($post['action'] == 'generate'){  
        $BranchID = $post['branch'];
        $dateFrom = $post['dateFrom'];
        $dateTo = $post['dateTo'];
        $trans_type = $post['ttype']; //if debit or credit...
        $account_no = $post['accountNo']; //Customer ID...
        $lists = array();
        
        try{
            $q = HOReportStatementOfAccountQuery($BranchID,$dateFrom,$dateTo,$trans_type,$account_no);
            
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
    
    if($post['action'] == 'get_customers'){
        $branchID = $post['bID'];
        $customers = tpi_GetCustomers($branchID,1);
        
        if($customers){
            $returns['status'] = 'success';
            $returns['lists'] = $customers;
        }else{
            $returns['status'] = 'error';
            $returns['lists'] = array();
            $returns['message'] = 'Sorry, no records found for this branch.';
        }
        
        tpi_JSONencode($returns);
    }
?>
