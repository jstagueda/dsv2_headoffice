<?php
include "../../../initialize.php";
global $database;
function AddPagination($RPP, $num, $pageNum)
{
	$PrevIc=		"images/bprv.gif";
	$FirstIc=		"images/bfrst.gif";
	$NextIc=		"images/bnxt.gif";
	$LastIc=		"images/blst.gif";
	
	$dPrevIc=		"images/dprv.gif";
	$dFirstIc=		"images/dfrst.gif";
	$dNextIc=		"images/dnxt.gif";
	$dLastIc=		"images/dlst.gif";
	
	if ($num > 0) {
		//Determine the maxpage and the offset for the query
		$maxPage = ceil($num/$RPP);
		$offset = ($pageNum - 1) * $RPP;
		//Initiate the navigation bar
		$nav  = '';
		//get low end
		$page = $pageNum-3;
		//get upperbound
		$upper =$pageNum+3;
		if ($page <=0) {
			$page=1;
		}
		if ($upper >$maxPage) {
			$upper =$maxPage;
		}
		
		//Make sure there are 7 numbers (3 before, 3 after and current
		if ($upper-$page <6){
	
			//We know that one of the page has maxed out
			//check which one it is
			//echo "$upper >=$maxPage<br>";
			if ($upper >=$maxPage){
				//the upper end has maxed, put more on the front end
				//echo "to begining<br>";
				$dif =$maxPage-$page;
				//echo "$dif<br>";
					if ($dif==3){
						$page=$page-3;
					} elseif ($dif==4){
						$page=$page-2;
					} elseif ($dif==5){
						$page=$page-1;
					}
			} elseif ($page <=1) {
				//its the low end, add to upper end
				//echo "to upper<br>";
				$dif =$upper-1;
	
				if ($dif==3){
					$upper=$upper+3;
				}elseif ($dif==4){
					$upper=$upper+2;
				}elseif ($dif==5){
					$upper=$upper+1;
				}
			}
		}
		
		if ($page <=0) {
			$page=1;
		}
		
		if ($upper > $maxPage) {
			$upper = $maxPage;
		}
		
		//These are the numbered links
		for($page; $page <=  $upper; $page++) {
	
			if ($page == $pageNum){
				//If this is the current page then disable the link
				$nav .= " <font color='red'>$page</font> ";
			} else {
				//If this is a different page then link to it
				$nav .= " <a style:'cursor:pointer' onclick='showPage(\"".$page."\" )'>$page</a> ";
			}
		}
		
		
		//These are the button links for first/previous enabled/disabled
		if ($pageNum > 1){
			$page  = $pageNum - 1;
			$prev  = "<img border='0' src='$PrevIc' onclick='showPage(\"".$page."\")' style:'cursor:pointer'> ";
			$first = "<img border='0' src='$FirstIc' onclick='showPage(\"1\")'  style:'cursor:pointer'> ";
		} else {
			$prev  = "<img border='0' src='$dPrevIc'  style:'cursor:pointer'> ";
			$first = "<img border='0' src='$dFirstIc'   style:'cursor:pointer'> ";
		}
		
		//These are the button links for next/last enabled/disabled
		if ($pageNum < $maxPage AND $upper <= $maxPage) {
			$page = $pageNum + 1;
			$next = " <img border='0' src='$NextIc' onclick='showPage(\"".$page."\")'  >";
			$last = " <img border='0' src='$LastIc'  onclick='showPage(\"".$maxPage."\")' >";
		} else {
			$next = " <img border='0' src='$dNextIc'  style:'cursor:pointer'>";
			$last = " <img border='0' src='$dLastIc'  style:'cursor:pointer'>";
		}
		
		if ($maxPage >= 1) {
			// print the navigation link
			return  $first . $prev . $nav . $next . $last;
		}
	}
}

if($_POST['request'] == 'fetch_data'){
	
	$pageNum    = 1;
	$offset = 0;
	$RPP = 8;
	
	if(isset($_POST['page'])){
		$pageNum    = $_POST['page'];
		$offset 	= $_POST['page'];
	}
	$offset = ($pageNum - 1) * $RPP;
	
	$q = $database->execute("select * from movementtype");
	$num = $q->num_rows;
	$pagination = AddPagination($RPP, $num, $pageNum);
	$result['pagination'] = array('page'=>$pagination);
	

	$dbr = $database->execute(" select * from movementtype order by ID asc
								limit ".$offset.", ".$RPP );
								
	if($dbr->num_rows){
		while($r = $dbr->fetch_object()){
			$ID		= $r->ID;			
			$Code	= $r->Code;			
			$Name 	= $r->Name;
			//Fetch Data
			$result['fetch_data'][] = array('ID' => $ID, 'Code' => $Code , 'Name' => $Name);
		}
			$result['result'] = array('response' => 'Success');
	}else{
			$result['result'] = array('response' => 'Failed');
	}	

	die(json_encode($result));	
}


if($_POST['request'] == 'getting information'){
	$ID = $_POST['ID'];
	$movementtypeQ = $database->execute("select *  from movementtype  where ID = ".$ID);
	if($movementtypeQ->num_rows){
		while($r = $movementtypeQ->fetch_object()){
				$Name = $r->Name;
				$result['movementtype']=array('MoveID'=>$ID, 'Name'=>$Name);
		}
		$result['result_movement'] = array('response' => 'Success');
	}else{
		$result['result_movement'] = array('response' => 'Failed');
	}
	$inventorymovementtype_reasonsQ = $database->execute("select * from inventorymovementtype_reasons where MovementTypeID = ".$ID);
	if($inventorymovementtype_reasonsQ->num_rows){
		while($r = $inventorymovementtype_reasonsQ->fetch_object()){
				$ReasonID = $r->ReasonID;
				$ReasonTypeID = $r->ReasonTypeID;
				$result['reason'][] = array('ReasonID' => $ReasonID,'ReasonTypeID'=>$ReasonTypeID);
		}
		$result['result_reason'] = array('response' => 'Success');
	}else{
		$result['result_reason'] = array('response' => 'Failed');
	}
	
	$totalreasons = $database->execute("select * from reason");
	 while($r = $totalreasons->fetch_object()){
		$result['reasontotalnum_rows'][] = array('ID'=>$r->ID);
	 }
	$result['result_reason_num'] = 'success';
	die(json_encode($result));
}

if($_POST['request']=='get reason type'){
	$module_id = $_POST['module_id'];
	
	$q = $database->execute("SELECT * FROM reasontype WHERE ModuleID = ".$module_id);
	if($q->num_rows){
		while($r = $q->fetch_object()){
			$ID   = $r->ID;
			$Name = $r->Name;
			$result['data_handler'][]=array('ID'=>$ID, 'Name'=>$Name);
		}
		$result['response']	= 'success';
	}else{                    
		$result['response']	= 'failed';
	}
	
	
	die(json_encode($result));
}

if($_POST['request']=='dynamic grouping'){

	if($_POST['selection_group'] == 1){
		$q = $database->execute("SELECT * FROM movementtype");
		
	}else if ($_POST['selection_group'] == 2){
		$q = $database->execute("SELECT codeID ID, description Name FROM sfm_level");
	}else{
		$q = $database->execute("SELECT codeID ID, description Name FROM sfm_level limit 0");
	}
	if($q->num_rows){
		while($r = $q->fetch_object()){
			$ID   = $r->ID;
			$Name = $r->Name;
			$result['data_handler'][]=array('ID'=>$ID, 'Name'=>$Name);
		}
		$result['response']	= 'success';
	}else{                    
		$result['response']	= 'failed';
	}
	die(json_encode($result));
}

//save reason tag maintenance...
if(isset($_POST['selection_group'])){

	//print_r($_POST);
	//die();
	$_POST['checkbox']; //applicable REASONS...
	//$_POST['LinkToDMCM']; // LINK to DMCM..
	//$_POST['doc_type'];  // Doc Type..
	//$_POST['selection_group2'];// sflevel id or movementtype id..
	//$_POST['selection_group']; // group selection 'movement type,sales force level, not applicable'..
	//$_POST['mode_type_reason_tag']; // mode type..
	//$_POST['selection_reason_type']; // Reason Type..
	//$_POST['selection_module']; // Module Type..
	
	//GroupType Column..
	$Module     	= $_POST['selection_module'];
	$ReasonType 	= $_POST['selection_reason_type'];
	$GroupType  	= $_POST['selection_group2'];
	$SelectGroup 	= $_POST['selection_group'];
	$mode_type_reason_tag 	= $_POST['mode_type_reason_tag']; //automatic, manual, not aplicable..
	
	
	if($_POST['selection_group'] == "3"){
		$GroupType1 = "and GroupType is NULL";
		$GroupTypeColumn="";
		$GroupTypeValue="";
	}else if ($_POST['selection_group'] == "2"){
		$GroupType1 = "and GroupType = 'SF'";
		$GroupTypeColumn=",GroupType";
		$GroupTypeValue=",'SF'";
	}else if ($_POST['selection_group'] == "1"){
		$GroupType1 = "and GroupType ='MT'";
		$GroupTypeColumn=",GroupType";
		$GroupTypeValue=",'MT'";
	}
	//echo $GroupTypeValue;
	//die();
	//LinkToDMCM column..
	if($_POST['LinkToDMCM']==0){
		$LinktoDMCM		= " and LinktoDMCM = 0";
		$LinktoDMCMColumn = "";
		$LinktoDMCMValue = "";
	}else{
		$LinktoDMCM		  = " and LinktoDMCM = ".$_POST['LinkToDMCM'];
		$LinktoDMCMColumn = ",LinktoDMCM";
		$LinktoDMCMValue  = ",1";
	}

	//DocType Column..
	if($_POST['doc_type'] == 1){ // Trade
		$DocType = "and DocType = 0";
		$DocTypeColumn = ",DocType";
		$DocTypeValue = ",0";
	}else if ($_POST['doc_type'] == 2){
		$DocType = "and DocType = 1";
		$DocTypeColumn = ",DocType";
		$DocTypeValue = ",1";
	}else{
		$DocType = "and DocType is null";
		$DocTypeColumn = "";
		$DocTypeValue = "";
	}
	

	if($mode_type_reason_tag==3){
		// $ModeType = 'null';
		$ModeType = 'and ModeType is null';
		$ModeTypeColumn = "";
		$ModeTypeValue  = "";
		
	}else if ($mode_type_reason_tag==2){
		// $ModeType = 1;
		$ModeType = 'and ModeType = 1';
		$ModeTypeColumn = ",ModeType";
		$ModeTypeValue  = ",1";
	}else{
		$ModeType = 'and ModeType = 0';
		$ModeTypeColumn = ",ModeType";
		$ModeTypeValue  = ",0";
	}
	
	
	if(isset($_POST['checkbox'])){
		if($_POST['selection_group'] == 3){ // Not Applicable
	
			$q = "DELETE  FROM inventorymovementtype_reasons WHERE ReasonTypeID = ".$ReasonType." ".$GroupType1." ".$ModeType." 
									".$LinktoDMCM." and SFLevelID = ".$GroupType." and MovementTypeID = ".$GroupType." ".$DocType;
			
		}else if ($_POST['selection_group']==1){ // Movement Type..
			$q = "DELETE  FROM inventorymovementtype_reasons WHERE ReasonTypeID = ".$ReasonType." ".$GroupType1." ".$ModeType." 
									".$LinktoDMCM." and MovementTypeID = ".$GroupType." ".$DocType;
		}else { // SFLevel
			$q = "DELETE FROM inventorymovementtype_reasons WHERE ReasonTypeID = ".$ReasonType." ".$GroupType1." ".$ModeType." 
				".$LinktoDMCM." and SFLevelID = ".$GroupType." ".$DocType;
		}
		
		//delete record 
		$database->execute($q);
		
		foreach ($_POST['checkbox'] as $reasons){
			
			if($_POST['selection_group'] == 3){ // Not Applicable

			  $q = "INSERT INTO inventorymovementtype_reasons (ReasonID, ReasonTypeID, SFLevelID, 
					MovementTypeID ".$LinktoDMCMColumn."".$DocTypeColumn."".$ModeTypeColumn."".$GroupTypeColumn.")
					values
					(".$reasons.",".$ReasonType.",".$GroupType.",".$GroupType."".$LinktoDMCMValue."".$DocTypeValue."".$ModeTypeValue."".$GroupTypeValue.")";
				
			}else if ($_POST['selection_group']==1){ // Movement Type..
					 $q = "INSERT INTO inventorymovementtype_reasons (ReasonID, ReasonTypeID, 
						   MovementTypeID".$LinktoDMCMColumn."".$DocTypeColumn."".$ModeTypeColumn."".$GroupTypeColumn.")
						   values
						   (".$reasons.",".$ReasonType.",".$GroupType."".$LinktoDMCMValue."".$DocTypeValue."".$ModeTypeValue."".$GroupTypeValue.")";
			}else{ // SFLevel
					 $q = "INSERT INTO inventorymovementtype_reasons (ReasonID, ReasonTypeID, SFLevelID".$LinktoDMCMColumn."".$DocTypeColumn."".$ModeTypeColumn."".$GroupTypeColumn.")
						   VALUES
						  (".$reasons.",".$ReasonType.",".$GroupType."".$LinktoDMCMValue."".$DocTypeValue."".$ModeTypeValue."".$GroupTypeValue.")";
			}
			$database->execute($q);
		}
	}

	
	
	die(json_encode(array('result'=>'success')));	
}

if($_POST['request']=='get available reason on reason table'){
	//WHERE ReasonTypeID = ".$_POST['parameter']
     $q = $database->execute("SELECT * FROM reason");
     if($q->num_rows){
          $result['total_records']=$q->num_rows;
          while($r=$q->fetch_object()){
               $ReasonID      =  $r->ID;
               $ReasonName    = $r->Name;
               $result['data_handler'][]=array('ReasonID'=>$ReasonID, 'ReasonName'=> $ReasonName);
          }
          $result['response']='success';
     }else{
          $result['response']='failed';
     }
     die(json_encode($result));
}

if($_POST['request'] == 'get reason'){
     $ID = $_POST['ID'];
     $q = $database->execute("select * from reason where ID =".$ID);
     if($q->num_rows){
          while($r = $q->fetch_object()){
               $Code = $r->Code;
			   $ID             = $r->ID;
               $Name           = $r->Name;
               $ReasonTypeID = $r->ReasonTypeID;
               $CreditGLAccount		= $r->CreditGLAccount;
			   $CreditCostCenter	= $r->CreditCostCenter;
			   $DebitGLAccount		= $r->DebitGLAccount;		   
			   $DebitCostCenter		= $r->DebitCostCenter;
			   
			   
			   $result['data_handler'] = array('ID'=>$ID,'Code'=>$Code,'Name'=>$Name,'ReasonTypeID'=>$ReasonTypeID, 'CreditGLAccount' => $CreditGLAccount,'CreditCostCenter' => $CreditCostCenter,
												'DebitGLAccount' => $DebitGLAccount,'DebitCostCenter' => $DebitCostCenter);
          }
               $result['response']='success';
     }else{
          $result['response']='failed';
     }
     die(json_encode($result));
}

if($_POST['request']=='update reason oye!'){
	
     $ID  =    $_POST['ID'];
     $reason = $_POST['reason'];
     $selection_reason_type = $_POST['selection_reason_type'];
	 $CreditGLAcount       = $_POST['CreditGLAcount'];
	 $CreditCostCenter     = $_POST['CreditCostCenter'];
	 $DebitGLAccount       = $_POST['DebitGLAccount'];
	 $DebitCostCenter	  = $_POST['DebitCostCenter'];
	 $database->execute("SET FOREIGN_KEY_CHECKS = 0");
     $database->execute("update reason set NAME = '".$reason."',
                          CreditGLAccount='".$CreditGLAcount."', CreditCostCenter='".$CreditCostCenter."', DebitGLAccount='".$DebitGLAccount."', 
						  DebitCostCenter='".$DebitCostCenter."', Changed = 1
						  where ID=".$ID);
	$q = $database->execute("select * from reason");
	if($q->num_rows){
		while($r = $q->fetch_object()){
			$ID = $r->ID;
			$Name = $r->Name;
			$result['data_handler'][] = array('ID'=>$r->ID, 'Name'=>$r->Name);
		}
	}
	$result['response'] = 'success';
	die(json_encode($result));
}

if($_POST['request']=='save reason'){
	if(isset($_POST['ID'])){
		//delete record..
		$database->execute("delete from reason where ID = ".$_POST['ID']);
	}else{
		$reason_code          = $_POST['reason_code'];
		$reason               = $_POST['reason'];
		//$reasontype  		  = $_POST['selection_reason_type'];	
		$CreditGLAcount       = $_POST['CreditGLAcount'];
		$CreditCostCenter     = $_POST['CreditCostCenter'];
		$DebitGLAccount       = $_POST['DebitGLAccount'];
		$DebitCostCenter	  = $_POST['DebitCostCenter'];
		$qchecker = $database->execute("select * from reason where Code ='".$reason_code."'");
		if($qchecker->num_rows){
				$result['response'] = 'failed';
				die(json_encode($result));
		}else{
			$database->execute("SET FOREIGN_KEY_CHECKS = 0");
			$database->execute("insert into reason (Code, Name, StatusID, CreditGLAccount, CreditCostCenter, DebitGLAccount, 
								DebitCostCenter, EnrollmentDate, LastModifiedDate)
								values ('".$reason_code."', '".$reason."', 1, '".$CreditGLAcount."', '".$CreditCostCenter."', '
								".$DebitGLAccount."', '".$DebitCostCenter."',
								NOW(), NOW())");		
		}
	}
	$q = $database->execute("select * from reason");
	if($q->num_rows){
		while($r = $q->fetch_object()){
			$ID = $r->ID;
			$Name = $r->Name;
			$result['data_handler'][] = array('ID'=>$r->ID, 'Name'=>$r->Name);
		}
		$result['response'] = 'success';
	}
	die(json_encode($result));
}

if($_POST['request']=='search reason'){
	$q = $database->execute("select * from reason where Name LIKE '%".$_POST['like']."%'");
	if($q->num_rows){
		while($r = $q->fetch_object()){
			$ID = $r->ID;
			$Name = $r->Name;
			$result['data_handler'][] = array('ID'=>$r->ID, 'Name'=>$r->Name);
		}
		$result['response'] = 'success';
	}else{
		$result['response'] = 'failed';
	}
	die(json_encode($result));
}

if($_POST['request']=='check all checkboxes'){
	if($_POST['SelectGroup'] == "3"){
		$GroupType1 = "and GroupType is NULL";
	}else if ($_POST['SelectGroup'] == "2"){
		$GroupType1 = "and GroupType = 'SF'";
	}else if ($_POST['SelectGroup'] == "1"){
		$GroupType1 = "and GroupType ='MT'";
	}
	
	if($_POST['LinkToDMCM']==0){
		// $LinktoDMCM		= " and LinktoDMCM = 0";
		$LinktoDMCM		= " and LinktoDMCM IS NULL";
	}else{
		$LinktoDMCM		= " and LinktoDMCM = ".$_POST['LinkToDMCM'];
	}
	
	if($_POST['DocType'] == 1){ // Trade
		//$DocType = 0;
		$DocType = "and DocType = 0";
	}else if ($_POST['DocType'] == 2){
		//$DocType = 1;
		$DocType = "and DocType = 1";
	}else{
		// $DocType = "and DocType = 0";
		$DocType = "and DocType is null";
	}
	
	$Module     	= $_POST['Module'];
	$ReasonType 	= $_POST['ReasonType'];
	$GroupType  	= $_POST['GroupType'];
	$SelectGroup 	= $_POST['SelectGroup'];
	$mode_type_reason_tag 	= $_POST['mode_type_reason_tag']; //automatic, manual, not aplicable..
	
	if($mode_type_reason_tag==3){
		// $ModeType = 'null';
		$ModeType = 'and ModeType is null';
		// $ModeType = 'and ModeType = 0';
	}else if ($mode_type_reason_tag==2){
		// $ModeType = 1;
		$ModeType = 'and ModeType = 1';
	}else{
		$ModeType = 'and ModeType = 0';
	} 
	
	//$q = $database->execute("SELECT * FROM inventorymovementtype_reasons WHERE ReasonTypeID = ".$ReasonType." AND ".$GroupType1." ".$ModeType." 
	//						 and SFLevelID =".$GroupType ." ".$LinktoDMCM);
	
	if($_POST['SelectGroup'] == 3){ // Not Applicable
		$qq = "SELECT * FROM inventorymovementtype_reasons WHERE ReasonTypeID = ".$ReasonType." ".$GroupType1." ".$ModeType." 
								".$LinktoDMCM." and SFLevelID = ".$GroupType." and MovementTypeID = ".$GroupType." ".$DocType;
	}else if ($_POST['SelectGroup']==1){ // Movement Type..
		$qq = "SELECT * FROM inventorymovementtype_reasons WHERE ReasonTypeID = ".$ReasonType." ".$GroupType1." ".$ModeType." 
								".$LinktoDMCM." and MovementTypeID = ".$GroupType." ".$DocType;
	}else { // SFLevel
		$qq = "SELECT * FROM inventorymovementtype_reasons WHERE ReasonTypeID = ".$ReasonType." ".$GroupType1." ".$ModeType." 
			 ".$LinktoDMCM." and SFLevelID = ".$GroupType." ".$DocType;
	}
	
	// echo $qq;
	// die();
	$q = $database->execute($qq);
	
	
	if($q->num_rows) {
		while($r = $q->fetch_object()) {
			$ReasonID = $r->ReasonID;
			$ModeType = $r->ModeType;
			$result['data_handler'][]=array('checkedID'=>$ReasonID,'ModeType'=>$ModeType);
		}
		$result['response'] = 'success';
	}else {
		$result['response'] = 'failed';
	}
	
	die(json_encode($result));
}


if($_POST['request']=='check all checkboxes by doctype'){
	//print_r($_POST);
	//die();
	
	if($_POST['SelectGroup'] == "3"){
		$GroupType1 = "AND GroupType is NULL";
	}else if ($_POST['SelectGroup'] == "2"){
		$GroupType1 = "AND GroupType = 'SF'";
	}else if ($_POST['SelectGroup'] == "1"){
		$GroupType1 = "AND GroupType = 'MT'";
	}
	
	$Module     	= $_POST['Module'];
	$ReasonType 	= $_POST['ReasonType'];
	$GroupType  	= $_POST['GroupType'];
	$SelectGroup 	= $_POST['SelectGroup'];
	//$DocType		= $_POST['DocType'];
	
	if($_POST['DocType'] == 1){ // Trade
		//$DocType = 0;
		$DocType = "and DocType = 0";
	}else if ($_POST['DocType'] == 2){
		//$DocType = 1;
		$DocType = "and DocType = 1";
	}else{
		$DocType = "and DocType is null";
	}
	$mode_type_reason_tag 	= $_POST['mode_type_reason_tag']; //automatic, manual, not aplicable..
	if($mode_type_reason_tag==3){
		$ModeType = "AND ModeType is null ";
	}else if ($mode_type_reason_tag==2){
		$ModeType = "AND ModeType = 1";
	}else if($mode_type_reason_tag==1){
		$ModeType = "AND ModeType = 0";
	}
	
	if($_POST['LinkToDMCM']==0){
		$LinktoDMCM		= " and LinktoDMCM = 0";
	}else{
		$LinktoDMCM		= " and LinktoDMCM = ".$_POST['LinkToDMCM'];
	}
	
	if($_POST['SelectGroup'] == 3){ // Not Applicable
		$qq = "SELECT * FROM inventorymovementtype_reasons WHERE ReasonTypeID = ".$ReasonType." ".$GroupType1." ".$ModeType." 
								".$LinktoDMCM." and SFLevelID = ".$GroupType." and MovementTypeID = ".$GroupType." ".$DocType;
	}else if ($_POST['SelectGroup']==1){ // Movement Type..
		$qq = "SELECT * FROM inventorymovementtype_reasons WHERE ReasonTypeID = ".$ReasonType." ".$GroupType1." ".$ModeType." 
								".$LinktoDMCM." and MovementTypeID = ".$GroupType." ".$DocType;
	}else { // SFLevel
		$qq = "SELECT * FROM inventorymovementtype_reasons WHERE ReasonTypeID = ".$ReasonType." ".$GroupType1." ".$ModeType." 
			 ".$LinktoDMCM." and SFLevelID = ".$GroupType." ".$DocType;
	}

	$q = $database->execute($qq);
	if($q->num_rows) {
		while($r = $q->fetch_object()) {
			$ReasonID = $r->ReasonID;
			$ModeType = $r->ModeType;
			$result['data_handler'][]=array('checkedID'=>$ReasonID,'ModeType'=>$ModeType);
		}
		$result['response'] = 'success';
	}else {
		$result['response'] = 'failed';
	}
	
	die(json_encode($result));
}

if($_POST['request']=='check all checkboxes by dmcm'){
	//print_r($_POST);
	//die();
	
	if($_POST['SelectGroup'] == "3"){
		$GroupType1 = "AND GroupType is NULL";
	}else if ($_POST['SelectGroup'] == "2"){
		$GroupType1 = "AND GroupType = 'SF'";
	}else if ($_POST['SelectGroup'] == "1"){
		$GroupType1 = "AND GroupType = 'MT'";
	}
	
	$Module     	= $_POST['Module'];
	$ReasonType 	= $_POST['ReasonType'];
	$GroupType  	= $_POST['GroupType'];
	$SelectGroup 	= $_POST['SelectGroup'];
	//$DocType		= $_POST['DocType'];
	
	if($_POST['DocType'] == 1){ // Trade
		//$DocType = 0;
		$DocType = "and DocType = 0";
	}else if ($_POST['DocType'] == 2){
		//$DocType = 1;
		$DocType = "and DocType = 1";
	}else{
		$DocType = "and DocType is null";
	}
	$mode_type_reason_tag 	= $_POST['mode_type_reason_tag']; //automatic, manual, not aplicable..
	if($mode_type_reason_tag==3){
		$ModeType = "AND ModeType is null ";
	}else if ($mode_type_reason_tag==2){
		$ModeType = "AND ModeType = 1";
	}else if($mode_type_reason_tag==1){
		$ModeType = "AND ModeType = 0";
	}
	
	if($_POST['LinkToDMCM']==0){
		$LinktoDMCM		= " and LinktoDMCM = 0";
	}else{
		$LinktoDMCM		= " and LinktoDMCM = ".$_POST['LinkToDMCM'];
	}
	
	if($_POST['SelectGroup'] == 3){ // Not Applicable
		$qq = "SELECT * FROM inventorymovementtype_reasons WHERE ReasonTypeID = ".$ReasonType." ".$GroupType1." ".$ModeType." 
								".$LinktoDMCM." and SFLevelID = ".$GroupType." and MovementTypeID = ".$GroupType." ".$DocType;
	}else if ($_POST['SelectGroup']==1){ // Movement Type..
		$qq = "SELECT * FROM inventorymovementtype_reasons WHERE ReasonTypeID = ".$ReasonType." ".$GroupType1." ".$ModeType." 
								".$LinktoDMCM." and MovementTypeID = ".$GroupType." ".$DocType;
	}else { // SFLevel
		$qq = "SELECT * FROM inventorymovementtype_reasons WHERE ReasonTypeID = ".$ReasonType." ".$GroupType1." ".$ModeType." 
			 ".$LinktoDMCM." and SFLevelID = ".$GroupType." ".$DocType;
	}
	$q=$database->execute($qq);
	if($q->num_rows) {
		while($r = $q->fetch_object()) {
			$ReasonID = $r->ReasonID;
			$ModeType = $r->ModeType;
			$result['data_handler'][]=array('checkedID'=>$ReasonID,'ModeType'=>$ModeType);
		}
		$result['response'] = 'success';
	}else {
		$result['response'] = 'failed';
	}
	
	die(json_encode($result));
}




//all reason type here..
if($_POST['request']=='fetch_reasontype'){
	$q = $database->execute("select * from reasontype");
	if($q->num_rows){
		while($r = $q->fetch_object()){
			$ID = $r->ID;
			$Name = $r->Name;
			$result['data_handler'][]=array('ID'=>$ID,'Name'=>$Name);
		}
		$result['response']='success';
	}else{
		$result['response']='failed';
	}
	die(json_encode($result));
}


if($_POST['request']=='delete reason type'){
	
	$database->execute("delete from reasontype where ID =".$_POST['ID']);
	
	$q = $database->execute("select * from reasontype");
	if($q->num_rows){
		while($r = $q->fetch_object()){
			$ID = $r->ID;
			$Name = $r->Name;
			$result['data_handler'][]=array('ID'=>$ID,'Name'=>$Name);
		}
		$result['response']='success';
	}else{
		$result['response']='failed';
	}
	die(json_encode($result));
}

if($_POST['request'] == 'get reason type by id'){
	$q = $database->execute("select * from reasontype where ID=".$_POST['ID']);
	if($q->num_rows){
		while($r = $q->fetch_object()){
			$ID 		 = $r->ID;
			$Name 		 = $r->Name;
			$ModeType	 = $r->ModeType;
			$GroupType 	 = $r->GroupType;
			$ModuleID 	 = $r->ModuleID;
			$DocType     = $r->DocType;
			
			$result['data_handler']=array(	'ID'=>$ID,'Name'=>$Name,'GroupType'=>$GroupType,'ModeType'=>$ModeType,
											'ModuleID'=>$ModuleID, 'DocType'=>$DocType);
		}
		$result['response']='success';
	}else{
		$result['response']='failed';
	}
	die(json_encode($result));
}

if($_POST['request']=="update reason type")
{
	$Module = $_POST['SelectionModuleType'];
	$reason_type_name = $_POST['reason_type_name'];
	$group_type = "null";
	if($_POST['ReasonTypeGroup'] == 1){
		$group_type = "SF";
	}else if ($_POST['ReasonTypeGroup'] == 2){
		$group_type = "MT";
	}else{
		$group_type = "null";
	}
		$DocType = $_POST['isTradeNonTrade'];
		$ModeType = $_POST['ModeType'];
	$database->execute("update reasontype set ModuleID = ".$Module.", Name = '".$reason_type_name."', ModeType = ".$ModeType.", DocType = ".$DocType.",
						GroupType='".$group_type."' where ID = ".$_POST['reason_type_ID']);
	
	$q = $database->execute("select * from reasontype");
	if($q->num_rows){
		while($r = $q->fetch_object()){
			$ID 		 = $r->ID;
			$Name 		 = $r->Name;
			$ModeType	 = $r->ModeType;
			$GroupType 	 = $r->GroupType;
			$ModuleID 	 = $r->ModuleID;
			$result['data_handler'][]=array('ID'=>$ID,'Name'=>$Name,'GroupType'=>$GroupTypem,'ModeType'=>$ModeType,'ModuleID'=>$ModuleID);
		}
		$result['response']='success';
	}else{
		$result['response']='failed';
	}
	die(json_encode($result));
}
?>