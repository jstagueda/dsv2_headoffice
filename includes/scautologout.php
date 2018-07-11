

<?php 

include "../initialize.php";
global $session;
global $database;

if(isset($_POST['action'])){
	
	if($_POST['action'] == "checkloginstatus"){
	
		$result['response'] = ($session->is_logged_in())?1:0;
		tpi_JSONencode($result);
		return;
	 }
	 
	 if($_POST['action'] == "updatesessionstatus"){
		
		$database->execute("UPDATE userloginhistory SET LogoutTime = NOW() WHERE UserID = ".$session->session_get('user_id')." ORDER BY ID DESC LIMIT 1");
		User::unsetUserSession($session->session_get('user_id')); 
		$session->logout();
		//redirect_to("../login.php");
		$result['response'] = "success";
		tpi_JSONencode($result);
		return;
	 }

	 if($_POST['action'] == "unloadnewlylog"){
		unset($_SESSION["freshlogin"]);
		return;
	 }	 

	 if($_POST['action'] == "returntologin"){
		$database->execute("UPDATE userloginhistory SET LogoutTime = NOW() WHERE UserID = ".$session->session_get('user_id')." ORDER BY ID DESC LIMIT 1");
		User::unsetUserSession($session->session_get('user_id')); 
		$session->logout();
		$result['response'] = 0;
		tpi_JSONencode($result);
		return;
	 }		 
	 
	 if($_POST['action'] == "checkpendingsql"){
		 $sqlstat=$database->execute("SELECT * FROM INFORMATION_SCHEMA.PROCESSLIST WHERE COMMAND != 'Sleep' AND `host`='localhost' ");
		
				if($sqlstat->num_rows)
				{
					// while($ss = $sqlstat->fetch_object())
					// {
						// $test =	$ss->datedifference;
						// $result['response'] = 'success' ;
					// }
					
					echo '<script type=\"text/javascript\">
							i=1;
						  </script>';
					$result['response'] = 'success' ;
				}
				else{
					$result['response'] = 'fail' ;
				}
				
		
		 tpi_JSONencode($result) ;
		 return ;
		
		

		
	 }		 
	 
	 
}

?>

	

	
	
	
	