<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: April 05, 2013
 */
include('../../../initialize.php');
    
    $post = $_POST;
    
    if($post['action'] == 'insert'){
        $returns = array();
        $i = $mysqli->prepare("INSERT INTO `servicefeecommission` (`Code`,`PMGID`,`Minimum`,`Maximum`,`Discount`,`Changed`) VALUES (?,?,?,?,?,?)");
        $post = tpi_cleanRequest($post);
        
        if($post['Code'] != '' && $post['PMG'] != '' && $post['Min'] != '' && $post['Max'] != '' && $post['Rate'] != ''){
            $Code = $post['Code'];
            $PMGID = $post['PMG']; 
            $Min = $post['Min'];
            $Max = $post['Max'];
            $Discount = $post['Rate'];
            $Changed = 1;
            
            $i->bind_param('sissss',$Code,$PMGID,$Min,$Max,$Discount,$Changed);
            $i->execute();
        
            $returns['message'] = 'New service fee matrix was successfully added.'; 
            $returns['status'] = 'success';
            $returns['from_action'] = $post['action'];
            $returns['nxt_code'] = $i->insert_id + 1;
        }else{
            $returns['message'] = 'Fields with * are required fields.'; 
            $returns['status'] = 'error';
        }
        
        tpi_JSONencode($returns);
    }
    
    //Process for getting the lists of levels....
    if($post['action'] == 'lists'){
        $start = $post['start'];
        $end = $post['end'];
        
        $d = $mysqli->query("SELECT sfc.`ID`,sfc.`Code`,pmg.`Code` AS `PMGCode`,FORMAT(sfc.`Minimum`,2) AS `Minimum`,
                             FORMAT(sfc.`Maximum`,2) AS `Maximum`,sfc.`Discount` 
                             FROM `servicefeecommission` sfc
                             LEFT JOIN `tpi_pmg` pmg ON pmg.`ID` = sfc.`PMGID`
                             ORDER BY pmg.`Code`,sfc.`Minimum`,sfc.`Maximum` ASC
                             LIMIT $start, $end");
        $c = $mysqli->query('SELECT (MAX(ID) + 1) AS nxt_code FROM servicefeecommission');
        $t = $mysqli->query('SELECT COUNT(*) AS total FROM servicefeecommission');
        $PMGs = $mysqli->query("SELECT `ID`,`Code` FROM `tpi_pmg` WHERE `ID` IN(1,2)");
        
        $returns = array();
        
        if($d->num_rows > 0){
            while($r = $d->fetch_object()){
                $returns['lists'][] = $r;
            }
            $returns['status'] = 'success';
        }else{
            $returns['lists'] = array();
            $returns['status'] = 'error';
            $returns['message'] = 'No service fee matrix in database.';
        }
        
        $nxt_code = $c->fetch_object()->nxt_code;
        $returns['current_codeID'] = ($nxt_code == NULL) ? '1' : $nxt_code;
        
        $total = $t->fetch_object()->total;
        $returns['total'] = $total;
        
        if($PMGs->num_rows > 0):
            while($r = $PMGs->fetch_object()){
                $returns['PMGsOpt'][] = $r;
            }
        endif;
        
        tpi_JSONencode($returns);
    }
    
    //Process for fetching and editing one levels...
    if($post['action'] == 'edit'){
        $matrixID = $post['matrixID'];
        $returns = array();
        
        $q = "SELECT sfc.`ID`,sfc.`Code`,sfc.`PMGID`,sfc.`Minimum`,sfc.`Maximum`,sfc.`Discount` 
              FROM `servicefeecommission` sfc WHERE sfc.`ID` = ?";
        $d = $mysqli->query(str_replace('?',$matrixID,$q));
        
        if($d->num_rows > 0){
            $returns['status'] = 'success';
            $returns['data'] = $d->fetch_object();
        }else{
            $returns['status'] = 'error';
            $returns['data'] = array();
        }
        
        tpi_JSONencode($returns);
    }
    
    //Process for updating edited details of levels...
    if($post['action'] == 'update'){
        $returns = array();
        $u = $mysqli->prepare("UPDATE `servicefeecommission` SET `Code` = ?,`PMGID` = ?,`Minimum` = ?,`Maximum` = ?,`Discount` = ?,`Changed` = ? WHERE `ID` = ?");
        $post = tpi_cleanRequest($post);
        
        if($post['Code'] != '' && $post['PMG'] != '' && $post['Min'] != '' && $post['Max'] != '' && $post['Rate'] != ''){
            $ID = $post['MatrixID'];
            $Code = $post['Code'];
            $PMGID = $post['PMG']; 
            $Min = $post['Min'];
            $Max = $post['Max'];
            $Discount = $post['Rate'];
            $Changed = 1;
            
            $u->bind_param('sisssii',$Code,$PMGID,$Min,$Max,$Discount,$Changed,$ID);
            $u->execute();
        
            $returns['message'] = 'Service fee matrix is successfully updated.'; 
            $returns['status'] = 'success';
        }else{
            $returns['message'] = 'Service fee matrix is not updated, fields with * are required fields.'; 
            $returns['status'] = 'error';
        }
        
        tpi_JSONencode($returns);
    }
?>
