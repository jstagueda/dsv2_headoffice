<?php
	    include('../../initialize.php');
		include('../../class/config.php');
		global $mysqli;
		
		$result = $mysqli->query("SELECT * FROM branch where ID = 1");
		
		if($result->num_rows){
			while($row =  $result->fetch_array(MYSQLI_ASSOC))
			{
				
				$results[] = array('label' => $row['Name'], 'BranchID' => $row['ID'], 'TIN' => $row['TIN'], 'PermitNo' => $row['PermitNo'], 'ServerSN' => $row['ServerSN']);
			}
			echo json_encode($results);
		}else{
			echo json_encode(array("label" => "No Record(s) Displayed."));
		}
		
?>