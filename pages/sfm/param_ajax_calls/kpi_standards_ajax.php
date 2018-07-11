<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: January 21, 2013
 */
    include('../../../class/config.php');
    include('../../../class/mysqli.php');
    include('../../../class/functions.php');
    
    $post = $_POST;
    
    //Process for creation of new KPI...
    if($post['action'] == 'insert'){
        $returns = array();
        $i = $mysqli->prepare("INSERT INTO `sfm_kpi_standards` (`description`,`start_date`,`end_date`) VALUES (?,?,?)");
        $post = tpi_cleanRequest($post);
        
        if($post['desc'] != '' && $post['sDate'] != ''){
            $desc = $post['desc'];
            $sdate = date('Y-m-d',strtotime($post['sDate']));
            $edate = date('Y-m-d',strtotime($post['eDate']));
            
            if($edate < $sdate && $edate != '1970-01-01'){
                $returns['message'] = 'Date "To" can\'t be ealier than date from, please make date "To" ahead of it.'; 
                $returns['status'] = 'error';
                tpi_JSONencode($returns);
                exit;
            }
            
            $i->bind_param('sss',$desc,$sdate,$edate);
            $i->execute();
        
            $returns['message'] = 'New KPI standards was successfully added.'; 
            $returns['status'] = 'success';
            $returns['from_action'] = $post['action'];
            $returns['nxt_code'] = $i->insert_id + 1;
        }else{
            $returns['message'] = 'Fields with * are required fields.'; 
            $returns['status'] = 'error';
        }
        
        tpi_JSONencode($returns);
    }
    
    //Process for updating edited details of KPI...
    if($post['action'] == 'update'){
        $returns = array();
        $u = $mysqli->prepare("UPDATE `sfm_kpi_standards` SET `description` = ?,`start_date` = ?,`end_date` = ? WHERE codeID = ?");
        $post = tpi_cleanRequest($post);
        
        if($post['desc'] != '' && $post['sDate'] != ''){
            $codeID = $post['code'];
            $desc = $post['desc'];
            $sdate = date('Y-m-d',strtotime($post['sDate']));
            $edate = date('Y-m-d',strtotime($post['eDate']));
            
            if($edate < $sdate && $edate != '1970-01-01'){
                $returns['message'] = 'Date "To" can\'t be ealier than date from, please make date "To" ahead of it.'; 
                $returns['status'] = 'error';
                tpi_JSONencode($returns);
                exit;
            }

            $u->bind_param('sssi',$desc,$sdate,$edate,$codeID);
            $u->execute();
        
            $returns['message'] = 'KPI standards is successfully updated';
            $returns['status'] = 'success';
            $returns['from_action'] = $post['action'];
        }else{
            $returns['message'] = 'KPI standards is not updated, fields with * are required.'; 
            $returns['status'] = 'error';
        }
        
        tpi_JSONencode($returns);
    }
    
    //Process for fetching and editing one KPI...
    if($post['action'] == 'edit'){
        $codeID = $post['codeID'];
        $returns = array();
        $q = "SELECT codeID,description,start_date,IF(end_date != '1970-01-01',end_date,'0000-00-00') AS end_date FROM sfm_kpi_standards WHERE codeID = ?";
        $d = $mysqli->query(str_replace('?',$codeID,$q));
        
        if($d->num_rows > 0){
            $returns['status'] = 'success';
            $returns['data'] = $d->fetch_object();
        }else{
            $returns['status'] = 'error';
            $returns['data'] = array();
        }
        
        tpi_JSONencode($returns);
    }
    
    //Process for getting the lists of KPI....
    if($post['action'] == 'lists'){
        $start = $post['start'];
        $end = $post['end'];
        
        $d = $mysqli->query("SELECT codeID,description,start_date,IF(end_date != '1970-01-01',end_date,'0000-00-00') AS end_date 
                             FROM sfm_kpi_standards 
                             ORDER BY codeID ASC
                             LIMIT $start, $end");
        $c = $mysqli->query('SELECT (MAX(codeID) + 1) AS nxt_code FROM sfm_kpi_standards');
        $t = $mysqli->query('SELECT COUNT(*) AS total FROM sfm_kpi_standards');
        
        $returns = array();
        
        if($d->num_rows > 0){
            while($r = $d->fetch_object()){
                $returns['lists'][] = $r;
            }
            $returns['status'] = 'success';
        }else{
            $returns['lists'] = array();
            $returns['status'] = 'error';
            $returns['message'] = 'No KPI standards in database.';
        }
        
        $nxt_code = $c->fetch_object()->nxt_code;
        $returns['current_codeID'] = ($nxt_code == NULL) ? '1' : $nxt_code;
        
        $total = $t->fetch_object()->total;
        $returns['total'] = $total;
        
        tpi_JSONencode($returns);
    }
    
    //Process for getting the lists of KPI....
    if($post['action'] == 'get_KPIs'){
        $q = $mysqli->query('SELECT codeID,description FROM sfm_kpi_standards ORDER BY description ASC');
        $KPIs = array();
        $returns = array();
        
        if($q->num_rows > 0){
            while($r = $q->fetch_object()){
                $KPIs[] = $r;
            }
            
            $returns['KPIs'] = $KPIs;
            $returns['status'] = 'success';
        }else{
            $returns['KPIs'] = array();
            $returns['status'] = 'error';
        }
        
        tpi_JSONencode($returns);
    }
    
    if($post['action'] == 'delete'){
        $IdsForDelete = $post['IdsForDelete'];
        $returns = array();
        
        try{
            foreach($IdsForDelete as $id):
                $mysqli->query("DELETE FROM `sfm_kpi_standards` WHERE `codeID` = $id");
            endforeach;

            $returns['status'] = 'success';
            $returns['message'] = 'Items for deletion was successfully deleted.';
            
        }catch(Exception $e){
            $returns['status'] = 'error';
            $returns['message'] = 'Failure in deleting selected items.';
        }
        
        tpi_JSONencode($returns);
    }
?>
