<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: March 15, 2013
 */

    include('../../../initialize.php');
    
    $post = $_POST;
    
    if($post['action'] == 'insert'){
        $returns = array();
        $i = $mysqli->prepare("INSERT INTO `sfm_candidacy_matrix` (`LevelID`,`Month`,`TotalPaidUpDGS`,`TotalRecruits`,`TotalBCR`,`CandidacyBonusRate`,`DateCreated`,`Changed`) VALUES (?,?,?,?,?,?,?,?)");
        $post = tpi_cleanRequest($post);
        
        if($post['SFLevel'] != '' && $post['MMonth'] != '' && $post['PUDGS'] != '' && $post['recruits'] != '' && $post['BCR'] != '' && $post['CBR'] != ''){
            $SFLevel = $post['SFLevel'];
            $MMonth = $post['MMonth']; 
            $PUDGS = $post['PUDGS'];
            //$CampaingMonth = date('n',strtotime(substr($post['MCampaign'],0,3)));
            //$CampaingYear = substr($post['MCampaign'],3,2);
            $recruits = $post['recruits'];
            $BCR = $post['BCR'];
            $CBR = $post['CBR']; 
            $NOW = date('Y-m-d');
            $Changed = 1;
            
            $i->bind_param('iisissss',$SFLevel,$MMonth,$PUDGS,$recruits,$BCR,$CBR,$NOW,$Changed);
            $i->execute();
        
            $returns['message'] = 'New candidacy matrix was successfully added.'; 
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
        //UPPER(DATE_FORMAT(STR_TO_DATE(CONCAT(cm.`CampaignMonth`,'-',cm.`CampaignYear`),'%c-%y'),'%b%y')) AS `CampaignCode`,
        $d = $mysqli->query("SELECT cm.`MatrixID`,cm.`LevelID`,lvl.desc_code,cm.`Month`,
                             FORMAT(cm.`TotalPaidUpDGS`,2) AS `TotalPaidUpDGS`,cm.`TotalRecruits`,cm.`TotalBCR`,cm.`CandidacyBonusRate` 
                             FROM sfm_candidacy_matrix cm
                             LEFT JOIN sfm_level lvl ON lvl.codeID = cm.LevelID
                             ORDER BY cm.`LevelID` ASC
                             LIMIT $start, $end");
        $c = $mysqli->query('SELECT (MAX(MatrixID) + 1) AS nxt_code FROM sfm_candidacy_matrix');
        $t = $mysqli->query('SELECT COUNT(*) AS total FROM sfm_candidacy_matrix');
        
        $returns = array();
        
        if($d->num_rows > 0){
            while($r = $d->fetch_object()){
                $returns['lists'][] = $r;
            }
            $returns['status'] = 'success';
        }else{
            $returns['lists'] = array();
            $returns['status'] = 'error';
            $returns['message'] = 'No candidacy matrix in database.';
        }
        
        $nxt_code = $c->fetch_object()->nxt_code;
        $returns['current_codeID'] = ($nxt_code == NULL) ? '1' : $nxt_code;
        
        $total = $t->fetch_object()->total;
        $returns['total'] = $total;
        
        tpi_JSONencode($returns);
    }
    
    //Process for fetching and editing one levels...
    if($post['action'] == 'edit'){
        $matrixID = $post['matrixID'];
        $returns = array();
        //UPPER(DATE_FORMAT(STR_TO_DATE(CONCAT(cm.`CampaignMonth`,'-',cm.`CampaignYear`),'%c-%y'),'%b%y')) AS `CampaignCode`,
        $q = "SELECT cm.`MatrixID`,cm.`LevelID`,cm.`Month`,
              cm.`TotalPaidUpDGS`,cm.`TotalRecruits`,cm.`TotalBCR`,cm.`CandidacyBonusRate` 
              FROM sfm_candidacy_matrix cm WHERE cm.`MatrixID` = ?";
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
        $u = $mysqli->prepare("UPDATE `sfm_candidacy_matrix` SET `LevelID` = ?,`Month` = ?,`TotalPaidUpDGS` = ?,`TotalRecruits` = ?,`TotalBCR` = ?,`CandidacyBonusRate` = ?,`Changed` = ? WHERE `MatrixID` = ?");
        $post = tpi_cleanRequest($post);
        
        if($post['SFLevel'] != '' && $post['MMonth'] != '' && $post['PUDGS'] != '' && $post['recruits'] != '' && $post['BCR'] != '' && $post['CBR'] != ''){
            $MatrixID = $post['MatrixID'];
            $SFLevel = $post['SFLevel'];
            $MMonth = $post['MMonth']; 
            $PUDGS = $post['PUDGS'];
            $recruits = $post['recruits'];
            $BCR = $post['BCR'];
            $CBR = $post['CBR']; 
            $NOW = date('Y-m-d');
            $Changed = 1;
            
            $u->bind_param('iisissii',$SFLevel,$MMonth,$PUDGS,$recruits,$BCR,$CBR,$Changed,$MatrixID);
            $u->execute();
        
            $returns['message'] = 'Candidacy matrix is successfully updated.'; 
            $returns['status'] = 'success';
        }else{
            $returns['message'] = 'Candidacy matrix is not updated, fields with * are required fields.'; 
            $returns['status'] = 'error';
        }
        
        tpi_JSONencode($returns);
    }
    
    if($post['action'] == 'get_campaigns'){
        $returns = array();
        $s = $mysqli->query("SELECT `Code` FROM `campaign` ORDER BY `Code` DESC");
        
        if($s->num_rows > 0){
            while($row = $s->fetch_object()){
                $returns['campaigns'][] = $row;
            }
            $returns['status'] = 'success';
        }else{
            $returns['campaigns'] = array();
            $returns['status'] = 'error';
            $returns['message'] = 'Can\'t fetch campaigns.';
        }
        
        tpi_JSONencode($returns);
    }
?>
