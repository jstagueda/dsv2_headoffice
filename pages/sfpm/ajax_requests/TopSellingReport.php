<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: July 06, 2013
 */
    include('../../../initialize.php');
    $post = $_POST;
    $returns = array();
    
    //Generation report process
    if($post['action'] == 'generate'){
        $lists = array();
        $BranchID = $post['branch'];
        $dateFrom = $post['dateFrom'];
        $dateTo = $post['dateTo'];
        
        $report_type = $post['report_type'];
        $sales_type = $post['sales_type'];
        $records = $post['num_records'];
        
        try{
            $q = HOReportTopSellingReportQuery($BranchID,$dateFrom,$dateTo,$report_type,$sales_type,$records);
            
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
