<?php
    include('../../initialize.php');
	global $mysqli;
	
	$success_logs 		= "../logs.txt";
	//tpi_file_truncate($success_logs);
	
	try{     
		$result = $mysqli->query("SELECT * FROM branchparameter");
		
		$BranchID = $_POST['BranchID'];
		$TIN	  = $_POST['TIN'];
		$PermitNo = $_POST['PermitNo'];
		$ServerSN = $_POST['ServerSN'];
		$WelcomeNoteLine1 = $_POST['WelcomeNoteLine1'];
		$WelcomeNoteLine2 = $_POST['WelcomeNoteLine2'];
		$WelcomeNoteLine3 = $_POST['WelcomeNoteLine3'];
		
		if($result->num_rows){
			tpi_JSONencode(array('result'=>'exist'));
		}else{
			$mysqli->query("INSERT INTO `branchparameter`(BranchID, TIN, PermitNo, ServerSN, WelcomeNoteLine1, WelcomeNoteLine2, WelcomeNoteLine3)
									VALUES
									( ".$BranchID.", '".$TIN."', '".$PermitNo."', '".$ServerSN."','".$WelcomeNoteLine1."', '".$WelcomeNoteLine2."', '".$WelcomeNoteLine3."' )");
			tpi_error_log('Branch Parameter setup successful.'."<br />",$success_logs);
			die(json_encode(array("result"=>'success')));
		}
	}catch(Exception $e){
            echo $e->getMessage();
			tpi_error_log($e->getMessage()."<br />",$success_logs);
	}
?>