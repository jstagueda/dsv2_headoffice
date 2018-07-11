<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: January 23, 2013
 */
    include('../../../class/config.php');
    include('../../../class/mysqli.php');
    include('../../../class/functions.php');
    
    $post = $_POST;
    
    if($post['action'] == 'insert'){
        $returns = array();
        $i = $mysqli->prepare("INSERT INTO `sfm_level_criteria` 
                              (
                                `level_codeID`,`kpi_codeID`,`criteria_type`,`description`,`min_value`,`max_value`,
                                `no_of_months`,`avg_total`,`no_of_units`,`start_date`,`end_date`
                              ) 
                              VALUES (?,?,?,?,?,?,?,?,?,?,?)");
        $post = tpi_cleanRequest($post);
        
        if($post['SFL'] != '' && $post['KPI'] != '' && $post['desc'] != '' 
           && $post['ctype'] != '' && $post['min_val'] != ''
           && $post['num_months'] != '' && $post['avg_total'] != '' && $post['sDate'] != ''
          ){
            $SFL = $post['SFL'];
            $KPI = $post['KPI'];
            $desc = $post['desc'];
            $ctype = $post['ctype'];
            $min_val = $post['min_val'];
            $max_val = $post['max_val'];
            $num_months = $post['num_months'];
            $avg_total = $post['avg_total'];
            $req_units = (empty($post['req_units'])) ? 0 : $post['req_units'];
            $sdate = date('Y-m-d',strtotime($post['sDate']));
            $edate = date('Y-m-d',strtotime($post['eDate']));
            
            if($edate < $sdate && $edate != '1970-01-01'){
                $returns['message'] = 'Date "To" can\'t be ealier than date from, please make date "To" ahead of it.'; 
                $returns['status'] = 'error';
                tpi_JSONencode($returns);
                exit;
            }
            
            if(!$req_units && $ctype == 'maintenance'){
                $returns['message'] = 'You choose maintenance criteria, no. of units is required.'; 
                $returns['status'] = 'error';
                
                tpi_JSONencode($returns);
                exit;
            } 
            
            $i->bind_param('sssssssssss',$SFL,$KPI,$ctype,$desc,$min_val,$max_val,$num_months,$avg_total,$req_units,$sdate,$edate);
            $i->execute();
        
            $returns['message'] = 'New level criteria was successfully added.'; 
            $returns['status'] = 'success';
            $returns['from_action'] = $post['action'];
            $returns['nxt_code'] = $i->insert_id + 1;
        }else{
            $returns['message'] = 'Fields with * are required fields.'; 
            $returns['status'] = 'error';
        }
        
        tpi_JSONencode($returns);
    }
    
    //Process for updating edited details of level criteria...
    if($post['action'] == 'update'){
        $returns = array();
        $u = $mysqli->prepare("UPDATE `sfm_level_criteria` SET 
                               `level_codeID` = ?, `kpi_codeID` = ?, `criteria_type` = ?, `description` = ?, `min_value` = ?, `max_value` = ?,
                               `no_of_months` = ?, `avg_total` = ?, `no_of_units` = ?, `start_date` = ?, `end_date` = ?
                              WHERE codeID = ?"
                             );
        $post = tpi_cleanRequest($post);
        
        if($post['SFL'] != '' && $post['KPI'] != '' && $post['desc'] != '' 
           && $post['ctype'] != '' && $post['min_val'] != ''
           && $post['num_months'] != '' && $post['avg_total'] != '' && $post['sDate'] != ''
          ){
            $codeID = $post['code'];
            $SFL = $post['SFL'];
            $KPI = $post['KPI'];
            $desc = $post['desc'];
            $ctype = $post['ctype'];
            $min_val = $post['min_val'];
            $max_val = $post['max_val'];
            $num_months = $post['num_months'];
            $avg_total = $post['avg_total'];
            $req_units = (empty($post['req_units'])) ? 0 : $post['req_units'];
            $sdate = date('Y-m-d',strtotime($post['sDate']));
            $edate = date('Y-m-d',strtotime($post['eDate']));
            
            if($edate < $sdate && $edate != '1970-01-01'){
                $returns['message'] = 'Date "To" can\'t be ealier than date from, please make date "To" ahead of it.'; 
                $returns['status'] = 'error';
                tpi_JSONencode($returns);
                exit;
            }
            
            if(!$req_units && $ctype == 'maintenance'){
                $returns['message'] = 'You choose maintenance criteria, no. of units is required.'; 
                $returns['status'] = 'error';
                
                tpi_JSONencode($returns);
                exit;
            }
            
            $u->bind_param('ssssssssssss',$SFL,$KPI,$ctype,$desc,$min_val,$max_val,$num_months,$avg_total,$req_units,$sdate,$edate,$codeID);
            $u->execute();
        
            $returns['message'] = 'Level criteria is successfully updated';
            $returns['status'] = 'success';
            $returns['from_action'] = $post['action'];
        }else{
            $returns['message'] = 'Level criteria is not updated, fields with * are required.'; 
            $returns['status'] = 'error';
        }
        
        tpi_JSONencode($returns);
    }
    
    //Process for fetching and editing one level criteria...
    if($post['action'] == 'edit'){
        $codeID = $post['codeID'];
        $returns = array();
        $q = "SELECT codeID,level_codeID,kpi_codeID,description,criteria_type,
              min_value,max_value,no_of_months,avg_total,no_of_units,
              start_date,IF(end_date != '1970-01-01',end_date,'0000-00-00') AS end_date 
              FROM sfm_level_criteria 
              WHERE codeID = ?";
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
    
    //Process for getting the lists of level criteria....
    if($post['action'] == 'lists'){
        $start = $post['start'];
        $end = $post['end'];
        
        $d = $mysqli->query("SELECT lc.codeID,lc.level_codeID,lvl.desc_code,lc.kpi_codeID,kpi.description AS kpi_desc,
                             lc.description,lc.criteria_type,lc.start_date,
                             IF(lc.end_date != '1970-01-01',lc.end_date,'0000-00-00') AS end_date 
                             FROM sfm_level_criteria lc
                             LEFT JOIN sfm_level lvl ON lvl.codeID = lc.level_codeID
                             LEFT JOIN sfm_kpi_standards kpi ON kpi.codeID = lc.kpi_codeID
                             ORDER BY lc.codeID ASC
                             LIMIT $start, $end");
        $c = $mysqli->query('SELECT (MAX(codeID) + 1) AS nxt_code FROM sfm_level_criteria');
        $t = $mysqli->query('SELECT COUNT(*) AS total FROM sfm_level_criteria');
        
        $returns = array();
        
        if($d->num_rows > 0){
            while($r = $d->fetch_object()){
                $returns['lists'][] = $r;
            }
            $returns['status'] = 'success';
        }else{
            $returns['lists'] = array();
            $returns['status'] = 'error';
            $returns['message'] = 'No level criteria in database.';
        }
        
        $nxt_code = $c->fetch_object()->nxt_code;
        $returns['current_codeID'] = ($nxt_code == NULL) ? '1' : $nxt_code;
        
        $total = $t->fetch_object()->total;
        $returns['total'] = $total;
        
        tpi_JSONencode($returns);
    }
?>
