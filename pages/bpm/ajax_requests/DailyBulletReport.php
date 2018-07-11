<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: July 02, 2013
 */
    include('../../../initialize.php');
    $post = $_POST;
    $returns = array();
    
    //Generation report process
    if($post['action'] == 'generate'){
        $BranchID = $post['branch'];
        $Month = date('n',strtotime($post['Period']));
        $Year = date('y',strtotime($post['Period']));
        $lists = array();
        
        try{
            $q = HOReportDailyBulletReportQuery($BranchID,$Month,$Year);
            
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
