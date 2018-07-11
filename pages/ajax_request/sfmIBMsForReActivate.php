<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: Feb 06, 2013
 */
    include('../../initialize.php');
    
    if($_POST){
        extract($_POST);
        
        if($action == 'lists'){
            $ibms = array();
            $returns = array();
            $rsIBMNetwork = $mysqli->query("CALL spSelectIBMsOptionForReActivation($start, $limit)");

            if($rsIBMNetwork->num_rows){
                while($r = $rsIBMNetwork->fetch_object()){
                    $ibms[] = $r;
                }

                $returns['status'] = 'success';
                $returns['lists'] = $ibms;
            }else{
                $returns['status'] = 'error';
                $returns['lists'] = array();
                $returns['message'] = 'No IBMs to lists.';
            }
            
            tpi_JSONencode($returns);
        }
    }
    
?>
