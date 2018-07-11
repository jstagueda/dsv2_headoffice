<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: January 24, 2013
 */
    include('../../../class/config.php');
    include('../../../class/mysqli.php');
    include('../../../class/functions.php');
    
    $post = $_POST;
    
    //Process for getting the lists of level criteria....
    if($post['action'] == 'lists'){
        $start = $post['start'];
        $end = $post['end'];
        $SFL = $post['SFL'];
        $KPI = $post['KPI'];
        $CType = $post['CType'];
        $wArray = array();
        $where = '';
        
        if($SFL) $wArray[] = "lc.level_codeID = $SFL";
        if($KPI) $wArray[] = "lc.kpi_codeID = $KPI";
        if($CType) $wArray[] = "lc.criteria_type = '$CType'";
        
        if($wArray){
            $where = 'WHERE '.@implode(' && ',$wArray);
        }
        
        $d = $mysqli->query("SELECT lc.codeID,lvl.desc_code AS level_desc,kpi.description AS kpi_desc,lc.description 
                             FROM sfm_level_criteria lc
                             LEFT JOIN sfm_level lvl ON lvl.codeID = lc.level_codeID
                             LEFT JOIN sfm_kpi_standards kpi ON kpi.codeID = lc.kpi_codeID
                             $where
                             ORDER BY lc.codeID ASC
                             LIMIT $start, $end");
        $t = $mysqli->query("SELECT COUNT(*) AS total FROM sfm_level_criteria lc $where");
        
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
        
        $total = $t->fetch_object()->total;
        $returns['total'] = $total;
        
        tpi_JSONencode($returns);
    }
    
    if($post['action'] == 'view'){
        $returns = array();
        $id = $post['code'];
        
        $v = $mysqli->query("SELECT 
                                lvl.description AS lvlDesc,kpi.description AS kpiDesc,
                                lc.codeID,lc.criteria_type,lc.description,
                                FORMAT(lc.min_value,2) AS min_value,FORMAT(lc.max_value,2) AS max_value,lc.no_of_months,lc.avg_total,
                                lc.no_of_units,lc.start_date,IF(lc.end_date != '1970-01-01',lc.end_date,'0000-00-00') AS end_date
                             FROM `sfm_level_criteria` lc
                             LEFT JOIN `sfm_level` lvl ON lvl.codeID = lc.level_codeID
                             LEFT JOIN `sfm_kpi_standards` kpi ON kpi.codeID = lc.kpi_codeID
                             WHERE lc.codeID = $id");
        
        if($v->num_rows > 0){
            $returns['status'] = 'success';
            $returns['details'] = $v->fetch_object();
        }else{
            $returns['status'] = 'error';
            $returns['details'] = array();
            $returns['message'] = 'Could not find details for this work standard.';
        }
        
        tpi_JSONencode($returns);
    }
?>
