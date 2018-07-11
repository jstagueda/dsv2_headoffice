<?php
include "../initialize.php";
global $database;


    $username 	 = $_POST['xusername'];
    $password 	 = $_POST['xpassword'];
    $newpassword = $_POST['new_password'];
	$checker = 0;
	$q = $database->execute("SELECT * FROM user WHERE LoginName = '".$username."' AND password = MD5('".$password."')");
	if($q->num_rows){
			$database->execute("update user set password = MD5('".$newpassword."')  WHERE LoginName = '".$username."' AND password = MD5('".$password."')");
			$checker = 1;
	}
	
	if($checker == 1){
		die(tpi_JSONencode(array('gino' => 'success', 'message' => 'Changed Password Successful')));
	}else{
		die(tpi_JSONencode(array('res' => 'failed', 'message_error' => 'Incorect Username or Password')));
	}
?>