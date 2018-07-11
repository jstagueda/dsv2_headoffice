<?php
include "../../../initialize.php";
global $database;
	if($_POST['request']=='getting information'){
		$holiday = $database->execute("select * from holiday where ID = ".$_POST['ID']);
		if($holiday->num_rows){
			while ($r = $holiday->fetch_object()){
				$ID	= $r->ID;			
				$Description	= $r->Description;			
				$Date 			= date("m/d/Y",strtotime($r->Date));
				$Date_From  	= date("m/d/Y",strtotime($r->Date_From)); 
				$StatusName 	= $r->StatusName;
				$result['Holiday_Data'] = array('Description' => $Description, 'Date' => $Date , 'Date_From' => $Date_From , 'StatusName' => $StatusName,'ID'=>$ID);
			}
			$result['holiday_result']= array('response'=>'success');
		}
		
		$branchspecialactivity = $database->execute("select * from branchspecialactivity where HolidayID =".$_POST['ID']);
		if($branchspecialactivity->num_rows){
			while($r = $branchspecialactivity->fetch_object()){
				$BRID = $r->BranchID;
				$result['BranchIDs'][] = array('BranchID'=>$BRID);
			}
			$result['branch_result'] = array('response'=>'success');
		}else{
			$result['branch_result'] = array('response'=>'failed');
		}
		die(json_encode($result));
	}
	
	if(isset($_POST['HolidayID'])){
		$HolidayID = $_POST['HolidayID'];
		$database->execute("DELETE FROM branchspecialactivity WHERE HolidayID =".$HolidayID);
		foreach ($_POST['checkbox'] as $value){
			$database->execute("insert into branchspecialactivity (BranchID, HolidayID, LastModifiedDate, EnrollmentDate, Changed) values
			(".$value.", ".$HolidayID.", NOW(), NOW(), 1)");
		}
		die(json_encode(array('result'=>'success')));
	}
?>