<?php
require_once('../initialize.php');
global $database;
if(isset($_POST['CriteriaD'])){


	$BuyinID = $_POST['CriteriaD'];

	$a = $database->execute("SELECT ID, SpecialCriteria FROM incentivespromobuyin where ID = ".$BuyinID);
	if($a->num_rows){
		while($row = $a->fetch_object()){
			$promoID = $row->ID;
			$SpecialCriteria = $row->SpecialCriteria;
		}
	}
	if($promoID){
		$b = $database->execute("SELECT * FROM incentivesspecialcriteria where IBuyinID =".$promoID);
		if($b->num_rows){
			while($row = $b->fetch_object()){
				$NoOfWiks 	 = $row->NoOfWiks;
				$NoOfWiksto  = $row->RequiredNoOfWeeksMet;
				$PromoIDx 	 = $row->IBuyinID;
				$StartWeek1  = date("m/d/Y", strtotime($row->StartWeek1));
				$EndWeek1 	 = date("m/d/Y", strtotime($row->EndWeek1));
				$StartWeek2  = date("m/d/Y", strtotime($row->StartWeek2));
				$EndWeek2 	 = date("m/d/Y", strtotime($row->EndWeek2));
				$StartWeek3  = date("m/d/Y", strtotime($row->StartWeek3));
				$EndWeek3 	 = date("m/d/Y", strtotime($row->EndWeek3));
				$StartWeek4  = date("m/d/Y", strtotime($row->StartWeek4));
				$EndWeek4	 = date("m/d/Y", strtotime($row->EndWeek4));
				$MinVal		 = $row->MinVal;
				
				//01/01/1970
				if($StartWeek1  =="01/01/1970"){ $StartWeek1 = "00/00/0000";}else{$StartWeek1;}
				if($EndWeek1 	=="01/01/1970"){ $EndWeek1   = "00/00/0000";}else{$EndWeek1;}
				if($StartWeek2  =="01/01/1970"){ $StartWeek2 = "00/00/0000";}else{$StartWeek2;}
				if($EndWeek2 	=="01/01/1970"){ $EndWeek2   = "00/00/0000";}else{$EndWeek2;}
				if($StartWeek3  =="01/01/1970"){ $StartWeek3 = "00/00/0000";}else{$StartWeek3;}
				if($EndWeek3 	=="01/01/1970"){ $EndWeek3   = "00/00/0000";}else{$EndWeek3;}
				if($StartWeek4  =="01/01/1970"){ $StartWeek4 = "00/00/0000";}else{$StartWeek4;}
				if($EndWeek4	=="01/01/1970"){ $EndWeek4	 = "00/00/0000";}else{$EndWeek4;}
				
				
				
			}
		$result = array('result'=>'success','SpecialCriteria' => $SpecialCriteria, 'NoOfWiks' => $NoOfWiks, 'StartWeek1' => $StartWeek1, 'EndWeek1' => $EndWeek1, 'StartWeek2' => $StartWeek2, 
						   'EndWeek2' => $EndWeek2, 'StartWeek3' => $StartWeek3, 'EndWeek3' => $EndWeek3, 'StartWeek4' => $StartWeek4, 'EndWeek4' => $EndWeek4,  'MinVal' => $MinVal, 'PromoID'=>$PromoIDx, 'NoOfWiksto' => $NoOfWiksto); 
		die(tpi_JSONencode($result));
		}else{
			$result = array('result'=>'failed');
			die(tpi_JSONencode($result));
		}
	}
}

if(isset($_GET['specialUpdate'])){

	$process = "0%";
	
	$PromoID = $_POST['promo_id'];
	
	if(isset($_POST['activate'])){
		$activate = 1;
	}else{
		$activate = 0;
	}
	
    $NoOfWiksto = 0;
	if(isset($_POST['noofwiks'])){
		$NoOfWiksto = $_POST['NoOfWiksto'];
	}else{
		$NoOfWiksto = 0;
	}
	
	
	$noofwiks = 0;
	if(isset($_POST['noofwiks'])){
		$noofwiks = $_POST['noofwiks'];
	}else{
		$noofwiks = 0;
	}
	
	
	//Special Criteria
	$Swik1 = "";
	$Ewik1 = "";
	$Swik2 = "";
	$Ewik2 = "";
	$Swik3 = "";
	$Ewik3 = "";
	$Swik4 = "";
	$Ewik4 = "";
	$SminValue = 0;
	if(isset($_POST['txtSPStartDate1']) && isset($_POST['txtSPEndDate1'])){
		$Swik1 = date("Y-m-d",strtotime($_POST['txtSPStartDate1']));
		$Ewik1 = date("Y-m-d",strtotime($_POST['txtSPEndDate1']));
	}else{
		$Swik1 = "0000-00-00";
		$Ewik1 = "0000-00-00";
	}
	
	if(isset($_POST['txtSPStartDate2']) && isset($_POST['txtSPEndDate2'])){
		$Swik2 = date("Y-m-d",strtotime($_POST['txtSPStartDate2']));
		$Ewik2 = date("Y-m-d",strtotime($_POST['txtSPEndDate2']));
	}else{
		$Swik2 = "0000-00-00";
		$Ewik2 = "0000-00-00";
	}
	
	if(isset($_POST['txtSPStartDate3']) && isset($_POST['txtSPEndDate3'])){
		$Swik3 = date("Y-m-d",strtotime($_POST['txtSPStartDate3']));
		$Ewik3 = date("Y-m-d",strtotime($_POST['txtSPEndDate3']));
	}else{
		$Swik3 = "0000-00-00";
		$Ewik3 = "0000-00-00";
	}
	
	if(isset($_POST['txtSPStartDate4']) && isset($_POST['txtSPEndDate4'])){
		$Swik4 = date("Y-m-d",strtotime($_POST['txtSPStartDate4']));
		$Ewik4 = date("Y-m-d",strtotime($_POST['txtSPEndDate4']));
	}else{
		$Swik4 = "0000-00-00";
		$Ewik4 = "0000-00-00";
	}
	if(isset($_POST['SminValue'])){
		if($_POST['SminValue'] != ""){	
			$SminValue = $_POST['SminValue'];
		}else{
			$SminValue = 0;
		}
	}else{
		$SminValue = 0;
	}
	$database->execute("UPDATE incentivespromobuyin set SpecialCriteria = ".$activate.", Changed = 1 where ID = ".$PromoID);
	
	if($activate == 1){
		//check if exist
		$result = $database->execute("SELECT * FROM incentivesspecialcriteria where IBuyinID = ".$PromoID);
		
		if($result->num_rows){
			//$noOfWiks = $_POST['noofwiks'];
			//$Swik1, $Ewik1, $Swik2, $Ewik2, $Swik3, $Ewik3, $Swik4, $Ewik4,$IBuyinID, $SminValue, $noofwiks
				$database->execute("UPDATE incentivesspecialcriteria SET
				NoOfWiks   = $noofwiks,
				RequiredNoOfWeeksMet   = $NoOfWiksto,
				StartWeek1 = '$Swik1',
				StartWeek2 = '$Swik2',
				StartWeek3 = '$Swik3',
				StartWeek4 = '$Swik4',
				EndWeek1   = '$Ewik1',
				EndWeek2   = '$Ewik2',
				EndWeek3   = '$Ewik3',
				EndWeek4   = '$Ewik4',
				MinVal	   = $SminValue,
				LastModifiedDate = now(), Changed = 1
				where IBuyinID = ".$PromoID);
				$process = "100%";
		}else{
			$database->execute("INSERT INTO incentivesspecialcriteria (
			NoOfWiks, StartWeek1,StartWeek2,StartWeek3,StartWeek4,EndWeek1,EndWeek2,  EndWeek3, EndWeek4,MinVal,IBuyinID,LastModifiedDate,EnrollmentDate,Changed, RequiredNoOfWeeksMet) values (
				$noofwiks,'$Swik1','$Swik2','$Swik3','$Swik4','$Ewik1','$Ewik2','$Ewik3','$Ewik4',$SminValue,".$PromoID.",now(),now(),1,$NoOfWiksto)");
			$process = "100%";
		}
	}else{
		//do nothing
		$process = "50%";
	}
	if($process == "100%"){
		$ajax = array('show'=>'success');
		die(tpi_JSONencode($ajax));
	}else{
		$ajax = array('show' => 'not update');
		
		die(tpi_JSONencode($ajax));
	}
}
?>