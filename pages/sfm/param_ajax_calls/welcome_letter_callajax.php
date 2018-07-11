<?php
include "../../../initialize.php";
global $mysqli;

if ($_POST['request'] == 'fetching_selection'){
	//sfm level
	$q = $mysqli->query('SELECT * FROM sfm_level');
		if($q->num_rows){
			while($r = $q->fetch_object()){
				$ID   = $r->codeID;
				$Code = $r->desc_code;
				$results['select'][] = array('LevelID' => $ID, 'Code'=>$Code);
			}
		}
	//gsutype
	$q1 = $mysqli->query("SELECT * FROM tpi_gsutype");		
		if($q1->num_rows){
			while($r = $q1->fetch_object()){
				$ID   = $r->ID;
				$Name = $r->Name;
				$results['select_gsu'][] = array('GSUID' => $ID, 'GSU_NAME'=>$Name);
			}
		}
	die(json_encode($results));
}
if($_POST['request'] == 'Generate List'){
	
	
	$level 	 		= $_POST['level'];
	$start 	 		= date("Y-m-d",strtotime($_POST['start']));
	$end	 		= date("Y-m-d",strtotime($_POST['end']));
	$dealer  		= $_POST['dealer'];
	$po_appointment = $_POST['po_appointment'];
	$gsu_type       = $_POST['gsu_type'];
	$IBM_Code_From  = $_POST['IBM_Code_From'];
	$IBM_Code_To 	= $_POST['IBM_Code_To'];
	
	
	
	if($IBM_Code_From != "" && $IBM_Code_To == ""){
			$IBM = "AND c.tpi_IBMCode = '".$IBM_Code_From."'";
			
	}elseif ($IBM_Code_From != "" && $IBM_Code_To != ""){
			$IBM = "AND c.tpi_IBMCode  BETWEEN '".$IBM_Code_From."' AND '".$IBM_Code_To."'";
			
	}else{
			$IBM = "";
		
	}
	
	if ($gsu_type == 0){
		$gsu_where = "";
	}else{
		$gsu_where = "AND d.ID = ".$gsu_type;
	}
		//CONCAT(TRIM(ibm.Code),'-',ibm.Name) ibm
		$q = "SELECT a.ID, a.Code, a.Name, MIN(a.EnrollmentDate) EnrollmentDate, b.DocumentNo DocumentNo, ibm.Code IBM_Number, 
					 ibm.Name IBM_Name, d.Name GSU_Name , b.NetAmount InitialPOAmount
			  FROM customer a
			  LEFT JOIN salesinvoice b ON a.ID = b.CustomerID
			  LEFT JOIN tpi_customerdetails c ON a.ID = c.CustomerID
			  LEFT JOIN tpi_gsutype d ON c.tpi_GSUTypeID = d.ID
			  INNER JOIN tpi_rcustomeribm i ON a.ID = i.CustomerID
			  INNER JOIN customer ibm ON ibm.ID = i.IBMID
			  WHERE a.CustomerTypeID = ".$level." and a.Code LIKE '%".$dealer."%' 
			  and a.EnrollmentDate  BETWEEN '".$start."' AND '".$end."' ".$gsu_where." ".$IBM."
			  group by a.ID";

			  //LEFT JOIN customer e ON c.tpi_IBMCode = e.Code

	$qq = $mysqli->query($q);
	if($qq->num_rows){
		$num_rows = $qq->num_rows;
		while($r = $qq->fetch_object()){
			$ID         		= $r->ID;
			$Code         		= $r->Code;
			$Name         		= $r->Name;	
			$GSU_Name         	= $r->GSU_Name;	
			$InitialPOAmount         	= number_format($r->InitialPOAmount,2);	
			$EnrollmentDate		= date("d/m/Y",strtotime($r->EnrollmentDate));
			if($r->IBM_Number != NULL){
				$MontherIBM = $r->IBM_Number." - ".$r->IBM_Name;
			}else{
				$MontherIBM = "NO IBM";
			}
			
			if($po_appointment == 0){
				if($r->DocumentNo == null){
					$DocumentNo = "NO";
				}else{
					$DocumentNo = "YES";
				}
				$results['fetch_data'][] = array('xID'=> $ID, 'Code'=> $Code, 'Name'=>$Name, 'DocumentNo'=> $DocumentNo, 
							  'EnrollmentDate' => $EnrollmentDate, 'MontherIBM' => $MontherIBM, 'GSU_Name'=>$GSU_Name, 'InitialPOAmount' => $InitialPOAmount);
				$validator = $ID;
			}
			
			if($po_appointment == 1){
				if($r->DocumentNo != null){
					$DocumentNo = "YES";
					$results['fetch_data'][] = array('xID'=> $ID, 'Code'=> $Code, 'Name'=>$Name, 'DocumentNo'=> $DocumentNo, 
							  'EnrollmentDate' => $EnrollmentDate, 'MontherIBM' => $MontherIBM, 'GSU_Name'=>$GSU_Name, 'InitialPOAmount' => $InitialPOAmount);
					$validator = $ID;
				}
			}
			
			if($po_appointment == 2){
				if($r->DocumentNo == null){
					$DocumentNo = "NO";
					$results['fetch_data'][] = array('xID'=> $ID, 'Code'=> $Code, 'Name'=>$Name, 'DocumentNo'=> $DocumentNo, 
							  'EnrollmentDate' => $EnrollmentDate, 'MontherIBM' => $MontherIBM, 'GSU_Name'=>$GSU_Name, 'InitialPOAmount' => $InitialPOAmount);
					$validator = $ID;
				}
			}
		}
		if($validator != ""){
			$results['results'] = array('fetching_data'=>'Success');
		}
		else{
			$results['results'] = array('fetching_data'=>'Failed');
		}
		die(json_encode($results));
	}
	else{
		$results['results'] = array('fetching_data'=>'Failed');
		die(json_encode($results));
	}
	
}
if(isset($_GET['term'])){
	$DealerCode = $_GET['term'];
		//echo "SELECT * FROM customer where Code like '%".$DealerCode."%'";
		$dbresult = $mysqli->query("SELECT * FROM customer where Code like '%".$DealerCode."%' LIMIT 10");
		if($dbresult->num_rows){
			while($row = $dbresult->fetch_array(MYSQLI_ASSOC)){
				$results[] = array('dealer_id'=>trim($row['ID']), 'Code'=>trim($row['Code']), 'DealerName'=>trim($row['Name']));
			}
			echo tpi_JSONencode($results);
		}
}
?>