<?php 

	include "../../initialize.php";
	global $database;
	global $session;
	$pageno=0;
	$pageid = $database->execute("SELECT `pagenum` FROM modulecontrol WHERE `code`='CHNGPASSW'");
			if($pageid->num_rows){
				while($r3 = $pageid->fetch_object())
								{
								$pageno=$r3->pagenum; 
								}
			}
			
	if(isset($_POST['changepassword'])) 
	{
		try
		{
			$database->beginTransaction();
			
			$oldpassword=$_POST['oldpassword'];
			$newpassword=$_POST['newpassword'];
			$confirmnewpassword=$_POST['newpasswordconfirm'];
			$action=$_POST['action'];
			$userid = $session->user_id;
			$username=$_SESSION['user_session_name'];
			$paswrecurrence=GetSettingValue($database, 'PASWRDRECUR');
			$minimumpasswordlength=GetSettingValue($database, 'MINUSRPWDLEN');
			$passwordreuse_counter=GetSettingValue($database, 'PWREUSEINT');
			$passwordreuse_counter_msg= round($passwordreuse_counter/30);
			$passbuffer=array();
			$oldpassdb='';
			$err=0;

			$query0 = $database->execute("SELECT Password FROM user WHERE id=$userid");
			if($query0->num_rows){
				while($r0 = $query0->fetch_object())
								{
								$oldpassdb=$r0->Password; 
								}
			}
			
			if(preg_match('/[^a-zA-Z\d]/', $newpassword)){
				$message = "Please make sure your password does not contain any special character. Please try again";
				$err++;
				redirect_to("../../index.php?pageid=$pageno&errmsg=$message");				
			}

			if(md5($oldpassword) != $oldpassdb)
			{
				$message = "Invalid current password. Please try again";
				$err++;
				redirect_to("../../index.php?pageid=$pageno&errmsg=$message");
			}
			
			if(md5($confirmnewpassword) != md5($newpassword))
			{
				$message = "Passwords do not match. Please try again.";
				$err++;
				redirect_to("../../index.php?pageid=$pageno&errmsg=$message");
			}
			
			if(strlen($newpassword) < $minimumpasswordlength){ 
				$message = "Please make sure your password length is at least ".$minimumpasswordlength." characters . Please try again";
				$err++;
				redirect_to("../../index.php?pageid=$pageno&errmsg=$message");
			}		

			if (!preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $newpassword)) 
			{
				$message = "Please make sure your password is a combination of alphabet and numbers. Please try again";
				$err++;
				redirect_to("../../index.php?pageid=$pageno&errmsg=$message");
			}			

			if (!(strtolower($newpassword) != $newpassword)){ 
				$message = "Please make sure your password contains at least 1 uppercase alphabet . Please try again";
				$err++;
				redirect_to("../../index.php?pageid=$pageno&errmsg=$message");
			}			
	
			$query = $database->execute("SELECT * FROM userpasswordhistory WHERE userid=$userid ORDER BY id DESC LIMIT $paswrecurrence"); //recurrence
			if($query->num_rows){

				while($r = $query->fetch_object())
								{
								$passbuffer[]=$r->Password; 
								}
				if(in_array(md5($newpassword),$passbuffer)){
					$message = "Password Already used. Please try again.";
					$err++;
					redirect_to("../../index.php?pageid=$pageno&errmsg=$message");
				 }	
			
			}
			
			$query = $database->execute("SELECT DATEDIFF(NOW(),historydate) datedifference FROM userpasswordhistory WHERE userid=$userid AND `password`='".md5($newpassword)."'"); 
			if($query->num_rows){
			
				while($r = $query->fetch_object())
								{
									$daysdifference = $r->datedifference; 
								}
					
				if($daysdifference<$passwordreuse_counter){
					$message = "Password has been used in the last $passwordreuse_counter_msg months. Please try again.";
					$err++;
					redirect_to("../../index.php?pageid=$pageno&errmsg=$message");					
				}
			}			
			if($err==0)
			{
				$database->execute("UPDATE `user`
									SET `password`='".md5($newpassword)."',
									`changenextlogon`=0
									WHERE `id`=$userid");
				
				$database->execute("INSERT INTO userpasswordhistory SET
									UserID = ".$userid.",
									`Password` = '".md5($newpassword)."',
									HistoryDate = NOW(),
									CreateBy = '".$username."',
									CreatedByID = ".$userid.",
									`Changed` = 1");
				
				$database->execute("UPDATE userloginhistory SET LogoutTime = NOW() WHERE UserID = ".$userid." ORDER BY ID DESC LIMIT 1");
				User::unsetUserSession($userid); 
				$session->logout();
				
				$database->commitTransaction();	
				$message = "Successfully updated records.";
				redirect_to("../../index.php?pageid=$pageno&msg=$message");
				//redirect_to("../../login.php?logout");//redirect to logout page
				
				
			}
			die();
		}	
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("../index.php?pageid=$pageno&errmsg=$errmsg");
		}	
			
	}
?>