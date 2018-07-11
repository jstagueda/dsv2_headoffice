<?php
    include('../../initialize.php');
    include('../config.php');
	global $mysqli;
	
	try{     
		$result = $mysqli->query("SELECT * FROM branchparameter");
		
		$BranchID = $_POST['BranchID'];
		$TIN	  = $_POST['TIN'];
		$PermitNo = $_POST['PermitNo'];
		$ServerSN = $_POST['ServerSN'];
		
		if($result->num_rows){
			tpi_JSONencode(array('result'=>'exist'));
		}else{
			$mysqli->query("INSERT INTO `branchparameter`(BranchID, TIN, PermitNo, ServerSN)
									VALUES
									( ".$BranchID.", '".$TIN."', '".$PermitNo."', '".$ServerSN."')");
			
			tpi_JSONencode(array('result'=>'success'));
		}
		

		
	}catch(Exception $e){
            echo $e->getMessage();
	}
?>