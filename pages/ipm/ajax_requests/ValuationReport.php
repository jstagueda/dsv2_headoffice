<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: July 11, 2013
 */
    include('../../../initialize.php');
    $post = $_POST;
    $returns = array();
    
    if($post['action'] == 'generate'){
        $BranchID = $post['branch'];
        $Date = date('Y-m-d',strtotime($post['AsOfDate']));
        $location = $post['location'];
        $productLine = $post['pline'];
        $pmg = $post['pmg'];
        $keyword = $post['keyword'];
        
        try{
            $q = HOReportValuationReportQuery($BranchID,$Date,$location,$productLine,$pmg,$keyword);
            
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
