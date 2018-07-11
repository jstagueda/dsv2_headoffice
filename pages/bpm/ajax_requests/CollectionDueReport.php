<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: July 17, 2013
 */
    include('../../../initialize.php');
    $post = $_POST;
    $returns = array();
    
    if($post['action'] == 'generate'){
        $BranchID = $post['branch'];
        $SFL = @explode('_',$post['SFL']); //Sales Force Level...
        $SFLId = $SFL[0];
        $CanPurchase = $SFL[1];
        $dueFrom = date('Y-m-d',strtotime($post['dateFrom']));
        $dueTo = date('Y-m-d',strtotime($post['dateTo']));
        $accountFrom = $post['accountFrom'];
        $accountTo = $post['accountTo'];
        $CAT = $post['CAT']; //Creadit Account Type...
        $lists = array();
        
        try{
            $q = HOReportCollectionDueReportQuery($BranchID,$SFL,$dueFrom,$dueTo,$accountFrom,$accountTo,$CAT);
            
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
?>
