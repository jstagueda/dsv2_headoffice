<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: April 02, 2013
 */

    include('../../../initialize.php');
    $post = $_POST;
    
    if($post['action'] == 'generate'){
        $SFL = $post['SFL'];
        $CampaignCode = $post['CampaignCode'];
        $moveType = $post['moveType'];
        $returns = array();
        $lists = array();
        
        $call = $mysqli->query("CALL spProcessMovementTermination($SFL,'$CampaignCode','$moveType')");
        if(!$call){
            $returns['status'] = 'error';
            $returns['message'] = 'Generation for termination movement fails, contact administrator or maybe there are no records.';
        }else{
            if($call){
                while($row = $call->fetch_object()):
                    if(isset($row->FALSE)){
                        $returns['status'] = 'empty';
                        $returns['message'] = 'No lists for termination.';
                        
                        tpi_JSONencode($returns);
                        exit;
                    }
                    
                    $lists[] = $row;
                endwhile;
                
                $returns['status'] = 'success';
                $returns['lists'] = $lists;
            }else{
                $returns['status'] = 'empty';
                $returns['message'] = 'No lists for termination.';
            }
        }
        
        tpi_JSONencode($returns);
    }
    
    if($post['action'] == 'do_movement'){
        $returns = array();
        $updates = array();
        $IDs = $post['IDsForMovement'];
        $oldLevelID = $post['oldLevelID'];
        $newLevelID = $post['newLevelID'];
        $MovementType = $post['MType']; 
        
        if($IDs){
            try{
                //loop all IDs that is been selected for promotion.
                $_SESSION['IDsTerminationForPrinting'] = $IDs;
                foreach($IDs as $ID):
                     //lets try first if there are no errors on the stored procedure.
                     try{
                        $u = $mysqli->query("CALL spMovementUpdateStatus($ID,$oldLevelID,$newLevelID,'$MovementType','')");
                        if($u) $updates[] = $ID; //store IDs in array for checking in front end.
                     }catch(Exception $e){
                         $returns['status'] = 'error';
                         $returns['message'] = $e->getMessage();
                         
                         tpi_JSONencode($returns);
                     }
                endforeach;
            
                $returns['status'] = 'success';
                $returns['ids_updated'] = $updates;
                $returns['message'] = strtolower(ucfirst($MovementType)).' process was successfully executed.';
            }catch(Exception $e){
                $returns['status'] = 'error';
                $returns['message'] = $e->getMessage();
            }
            
            $returns['type'] = $MovementType;
        }
        
        tpi_JSONencode($returns);
    }
?>
