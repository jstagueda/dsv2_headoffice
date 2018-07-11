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
        $i = $mysqli->prepare("INSERT INTO `sfm_level` (`rankCodeID`,`desc_code`,`description`,`status`,`can_purchase`,`has_downline`,`has_personal_acct`,`non_trade`,`credit_line`,`start_date`,`end_date`) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
        $post = tpi_cleanRequest($post);
        
        if($post['desc'] != '' && $post['sDate'] != '' && $post['rank'] != '' && $post['can_purchase'] != '' && $post['abbrv'] != '' && $post['CLC'] != ''){
            $rank = $post['rank'];
            $abbrv = $post['abbrv']; //description code
            $desc = $post['desc'];
            $status = $post['status'];
            $cp = $post['can_purchase'];
            $has_paccount = $post['has_paccount']; //has personal account
            $downline = $post['has_downline'];
            $CLC = $post['CLC'];
            $Trade = $post['Trade'];
            $sdate = date('Y-m-d',strtotime($post['sDate']));
            $edate = date('Y-m-d',strtotime($post['eDate']));
            
            //Check if level start date is conflict with rank end date...
            if(thisCheckLvlEndDateGreaterThanRank($rank,$edate) || thisCheckLvlEndDateGreaterThanRank($rank,$sdate)){
                $returns['message'] = 'Level effectivity date created conflict with the ranks\' end date. Make sure that effective date is not ahead.'; 
                $returns['status'] = 'error';
                tpi_JSONencode($returns);
                exit;
            }
            
            if($edate < $sdate && $edate != '1970-01-01'){
                $returns['message'] = 'Date "To" can\'t be ealier than date from, please make date "To" ahead of it.'; 
                $returns['status'] = 'error';
                tpi_JSONencode($returns);
                exit;
            }
            
            //Check also if Code or Abbreviation of description already exists.
            if(thisCheckIfDescCodeExists($abbrv)):
                $returns['message'] = 'Abbreviation already exists, could not proceed. Please type in other value.'; 
                $returns['status'] = 'error';
                tpi_JSONencode($returns);
                exit;
            endif;
            
            $i->bind_param('sssssssssss',$rank,$abbrv,$desc,$status,$cp,$downline,$has_paccount,$Trade,$CLC,$sdate,$edate);
            $i->execute();
            $mysqli->execute("INSERT INTO customertype set 
                            ID = ".$i->insert_id.",
                            Code = '$abbrv',
                            Name = '$desc',
                            Changed = 1");            
        
            $returns['message'] = 'New level was successfully added.'; 
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
    if($post['action'] == 'get_ranks'){
        $q = $mysqli->query('SELECT codeID,description FROM sfm_rank ORDER BY description ASC');
        $ranks = array();
        $returns = array();
        
        if($q->num_rows > 0){
            while($r = $q->fetch_object()){
                $ranks[] = $r;
            }
            
            $returns['ranks'] = $ranks;
            $returns['status'] = 'success';
        }else{
            $returns['ranks'] = array();
            $returns['status'] = 'error';
        }
        
        tpi_JSONencode($returns);
    }
    
    //Process for updating edited details of levels...
    if($post['action'] == 'update'){
        $returns = array();
        $u = $mysqli->prepare("UPDATE `sfm_level` SET `rankCodeID` = ?,`desc_code` = ?,`description` = ?,`status` = ?,`can_purchase` = ?,`has_downline` = ?,`has_personal_acct` = ?,`non_trade` = ?,`credit_line` = ?,`start_date` = ?,`end_date` = ? WHERE codeID = ?");
        $post = tpi_cleanRequest($post);
        
        if($post['desc'] != '' && $post['sDate'] != '' && $post['rank'] != '' && $post['can_purchase'] != '' && $post['abbrv'] != '' && $post['CLC'] != ''){
            $rank = $post['rank'];
            $codeID = $post['code'];
            $abbrv = $post['abbrv']; //description code
            $desc = $post['desc'];
            $status = $post['status'];
            $cp = $post['can_purchase'];
            $has_paccount = $post['has_paccount']; //has personal account
            $downline = $post['has_downline'];
            $CLC = $post['CLC'];
            $Trade = $post['Trade'];
            $sdate = date('Y-m-d',strtotime($post['sDate']));
            $edate = date('Y-m-d',strtotime($post['eDate']));
            
            //Check if level start date is conflict with rank end date...
            if(thisCheckLvlEndDateGreaterThanRank($rank,$edate) || thisCheckLvlEndDateGreaterThanRank($rank,$sdate)){
                $returns['message'] = 'Level effectivity date created conflict with the ranks\' end date. Make sure that effective date is not ahead.'; 
                $returns['status'] = 'error';
                tpi_JSONencode($returns);
                exit;
            }
            
            if($edate < $sdate && $edate != '1970-01-01'){
                $returns['message'] = 'Date "To" can\'t be ealier than date from, please make date "To" ahead of it.'; 
                $returns['status'] = 'error';
                tpi_JSONencode($returns);
                exit;
            }
            
            $u->bind_param('ssssssssssss',$rank,$abbrv,$desc,$status,$cp,$downline,$has_paccount,$Trade,$CLC,$sdate,$edate,$codeID);
            $u->execute();
            $mysqli->execute("INSERT INTO customertype set
                            Code = '$abbrv',
                            Name = '$desc'
                            WHERE ID = $codeID"); 
        
            $returns['message'] = 'Level is successfully updated';
            $returns['status'] = 'success';
            $returns['from_action'] = $post['action'];
        }else{
            $returns['message'] = 'Level is not updated, fields with * are required.'; 
            $returns['status'] = 'error';
        }
        
        tpi_JSONencode($returns);
    }
    
    //Process for fetching and editing one levels...
    if($post['action'] == 'edit'){
        $codeID = $post['codeID'];
        $returns = array();
        $q = "SELECT codeID,rankCodeID,desc_code,description,status,can_purchase,
               has_downline,has_personal_acct,non_trade,credit_line,start_date,
               IF(end_date != '1970-01-01',end_date,'0000-00-00') AS end_date 
               FROM sfm_level WHERE codeID = ?";
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
    
    //Process for getting the lists of levels....
    if($post['action'] == 'lists'){
        $start = $post['start'];
        $end = $post['end'];
        
        $d = $mysqli->query("SELECT codeID,rankCodeID,description,can_purchase,start_date,IF(end_date != '1970-01-01',end_date,'0000-00-00') AS end_date 
                             FROM sfm_level
                             ORDER BY codeID ASC
                             LIMIT $start, $end");
        $c = $mysqli->query('SELECT (MAX(codeID) + 1) AS nxt_code FROM sfm_level');
        $t = $mysqli->query('SELECT COUNT(*) AS total FROM sfm_level');
        
        $returns = array();
        
        if($d->num_rows > 0){
            while($r = $d->fetch_object()){
                $returns['lists'][] = $r;
            }
            $returns['status'] = 'success';
        }else{
            $returns['lists'] = array();
            $returns['status'] = 'error';
            $returns['message'] = 'No levels in database.';
        }
        
        $nxt_code = $c->fetch_object()->nxt_code;
        $returns['current_codeID'] = ($nxt_code == NULL) ? '1' : $nxt_code;
        
        $total = $t->fetch_object()->total;
        $returns['total'] = $total;
        
        tpi_JSONencode($returns);
    }
    
    //Process for getting the lists of levels....
    if($post['action'] == 'get_levels') {
      if(isset($_POST['candidacy'])){
		$q = $mysqli->query("SELECT codeID, desc_code, description, REPLACE(fields_class, '-', '_') fields_to_validate_key, can_purchase, has_personal_acct FROM sfm_level where desc_code = 'IBMC' ORDER BY codeID ASC");
	  }else{
		$q = $mysqli->query("SELECT codeID, desc_code, description, REPLACE(fields_class, '-', '_') fields_to_validate_key, can_purchase, has_personal_acct FROM sfm_level ORDER BY codeID ASC");
	  }
      
      $levels = array();
      $returns = array();

      if($q->num_rows > 0) {
        
        while($r = $q->fetch_object()) $levels[] = $r;
          
        $returns['levels'] = $levels;
        $returns['status'] = 'success';
      } else {
        
        $returns['levels'] = array();
        $returns['status'] = 'error';
      }

      tpi_JSONencode($returns);
    }
    
    if($post['action'] == 'delete'){
        $IdsForDelete = $post['IdsForDelete'];
        $returns = array();
        
        try{
            foreach($IdsForDelete as $id):
                $mysqli->query("DELETE FROM `sfm_level` WHERE `codeID` = $id");
            endforeach;

            $returns['status'] = 'success';
            $returns['message'] = 'Items for deletion was successfully deleted.';
            
            
        }catch(Exception $e){
            $returns['status'] = 'error';
            $returns['message'] = 'Failure in deleting selected items.';
        }
        
        tpi_JSONencode($returns);
    }
    
    /*
     * @author: jdymosco
     * @date: April 30, 2013
     * @description: Check if level description abbreviation already exists.
     */
    function thisCheckIfDescCodeExists($DescCode = ''){
        global $mysqli;
        
        if($DescCode){
            $c = $mysqli->prepare("SELECT `codeID` AS `IDCheck` FROM `sfm_level` WHERE `desc_code` = ? LIMIT 1");
            $c->bind_param('s',$DescCode);
            $c->execute();
            
            $c->bind_result($IDCheck);
            $c->fetch();
                
            if($IDCheck > 0) return true;
            else return false;
        }
        
        return false;
    }
    
    /*
     * @author: jdymosco
     * @date: May 18, 2013
     * @decription: Function that will check if level effectivity date is not greater that the ranks end date.
     */
    function thisCheckLvlEndDateGreaterThanRank($rId,$date){
        global $mysqli;
        
        if(!empty($date)){
            $q = $mysqli->query("SELECT `start_date`,`end_date` FROM `sfm_rank` WHERE `codeID` = $rId");
            if($q->num_rows > 0){
                $qd = $q->fetch_object();
                $end_date = ($qd->end_date == '1970-01-01' || $qd->end_date == NULL) ? '0000-00-00' : $qd->end_date;
                
                if($date > $end_date && $end_date != '0000-00-00') return true;
                else return false;
            }
        }
    }
?>
