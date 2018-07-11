<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: Feb. 04, 2013
 */
    include('../../../initialize.php');
    
    $post = $_POST;
    $pageSessionID = session_id();
    /*
     * @epxlantion: Process for generation and validation of SFM results for customers that will passed on level criterias.
     */
    if($post['action'] == 'generate'){
        $SFLfrom = $post['SFLfrom'];
        $SFLto = $post['SFLto'];
        $moveType = $post['moveType'];
        $returns = array();
        
        $call = $mysqli->query("CALL spGenerateCustomersForMovement($SFLfrom,$SFLto,'$moveType','$pageSessionID')");
        if(!$call){
            $returns['status'] = 'error';
            $returns['message'] = 'Generation and criteria validation fails contact administrator or maybe there are no records.';
        }else{
            if($call->fetch_object()->Status > 0){
                $returns['status'] = 'success';
            }else{
                $returns['status'] = 'empty';
                $returns['message'] = 'Nothing passed on level criterias.';
            }
        }
        
        tpi_JSONencode($returns);
    }
    
    /*
     * @explanation: Process for getting lists of all customers passed on level criteria.
     */
    if($post['action'] == 'lists'){
        $start = $post['start'];
        $end = $post['end'];
        
        try{
            $l = $mysqli->query("SELECT SFMmID,SFMmSFMLevel,SFMmCode,SFMmCustomerName,FORMAT(SUM(SFMmDGSTotalSales),2) AS SFMmDGSTotalSales,SUM(SFMmTotalRecruits) AS SFMmTotalRecruits,FORMAT(SUM(SFMmTotalBCR),2) AS SFMmTotalBCR 
                                 FROM tblsfmmovementresults 
                                 WHERE `SFMSessionID` = '$pageSessionID'
                                 GROUP BY SFMmID LIMIT $start,$end");
            if($l && $l->num_rows > 0){
                while($row = $l->fetch_object()){
                    $returns['lists'][] = $row;
                }
                $returns['status'] = 'success';
            }else{
                $returns['lists'] = array();
                $returns['status'] = 'error';
                $returns['message'] = 'No records found.';
            }

            $returns['total'] = getTotalLists($pageSessionID);
        }catch(Exception $e){
            $returns['status'] = 'error';
            $returns['message'] = 'Generation and criteria validation fails contact administrator or maybe there are no records.<br />
                                  Details: ' . $e->getMessage();
        }
        
        tpi_JSONencode($returns);
    }
    
    /*
     * @explantion: Will process movement status, sales force level and history.
     */
    if($post['action'] == 'do_movement'){
        $returns = array();
        $updates = array();
        $IDs = $post['IDsForMovement'];
        $oldLevelID = $post['oldLevelID'];
        $newLevelID = $post['newLevelID'];
        $MovementType = $post['MType']; //Movement to be executed if it is PROMOTION or REVERSION...
        
        if($IDs){
            try{
                //loop all IDs that is been selected for promotion.
                foreach($IDs as $ID):
                     //lets try first if there are no errors on the stored procedure.
                     try{
                        $u = $mysqli->query("CALL spMovementUpdateStatus($ID,$oldLevelID,$newLevelID,'$MovementType','$pageSessionID')");
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
    
    /*
     * @explanation: Will get total records of movement results that passed on level criterias.
     */
    function getTotalLists($sessionID){
        global $mysqli;
        
        $mysqli->query("SELECT SQL_CALC_FOUND_ROWS res.SFMmID FROM tblsfmmovementresults res 
                        WHERE res.SFMSessionID = '$sessionID' GROUP BY res.SFMmID");
        $found_rows = $mysqli->query('SELECT FOUND_ROWS() AS Total');
        
        return $found_rows->fetch_object()->Total;
    }
?>
