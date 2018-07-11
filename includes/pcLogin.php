<?php
require_once "../initialize.php";

	/* getclientip.php */
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');

	if (!empty($_SERVER['HTTP_CLIENT_IP']))
	{
	  $ip=$_SERVER['HTTP_CLIENT_IP'];
	}
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
	{
	  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else
	{
	  $ip=$_SERVER['REMOTE_ADDR'];
	}
	// print json_encode(array('ip' => $ip));
	//echo $ip;
	$ip = trim($ip);


// Remember to give your form's submit tag a name="submit" attribute!
if (isset($_POST['txtUser']) && isset($_POST['txtPass'])) { // Form has been submitted.

  $username = trim($_POST['txtUser']);
  $password = trim($_POST['txtPass']);
  
  // Check database to see if username/password exist.
	$found_user = User::authenticate($username, $password);
	
  if ($found_user)
  {
/* 	  echo $_POST['txtUser'];
	   die(); */
		$_SESSION['ismain'] = 1;
		$_SESSION['vatpercent'] = 12;
		$session->login($found_user);
		$maxdaysxp=GetSettingValue($database, 'MAXPWDVAL');
		$daysbef_pwdexp=GetSettingValue($database, 'DAYSBEFPWDEXPNOT');
		$passnotifexempt=GetSettingValue($database, 'PWDNOTEXEMPT');
		$notifypwd_exp=0;
		$pwnotif_exempted=0;
		$last_logged_ip = $database->execute("SELECT `ClientIPAddress` clip FROM userloginhistory ul WHERE userid=".$found_user->ID." AND DATE(ul.LoginTime) = DATE(NOW()) ORDER BY id DESC LIMIT 1")->fetch_object()->clip;
		
		
		$unlockq = $database->execute("  select settingvalue from settings where settings.settingcode =  'UNLOCKUSER' ");
		if($unlockq->num_rows)
		{
			while($res = $unlockq->fetch_object())
			{
				$userlist = $res->settingvalue;
			}
		}

		$loginquery = $database->execute(" SELECT * 
											FROM userloginhistory hist
											INNER JOIN `user` u ON u.id = hist.UserId
											INNER JOIN usertype usr ON usr.id = u.UserTypeID
											WHERE hist.UserID = ".$found_user->ID."
											AND DATE(hist.LoginTime) = DATE(NOW()) AND hist.logouttime IS NULL
											AND usr.Code NOT IN $userlist ");
		
			if(($loginquery->num_rows) and ($ip!=$last_logged_ip))
			{

					 User::unsetUserSession($session->session_get('user_id')); 
					 $session->logout();
					 $message = "Username is already logged-in different terminal.";
					 redirect_to("../login.php?msg=".$message);	
				
			}
			else
			{ 
				//***
				$pwordexp= $database->execute("SELECT `ID` id, `userid` userid, `historydate` historydate, NOW() datenow, DATEDIFF(NOW(),`historydate`) datedifference FROM userpasswordhistory WHERE userid=".$found_user->ID." ORDER BY id DESC LIMIT 1");
				
				if($pwordexp->num_rows)
				{
					while($pwxp = $pwordexp->fetch_object())
						{
						$datediff =	$pwxp->datedifference;
						}
				}
				
				if ((($datediff <= $maxdaysxp) && ($datediff > ($maxdaysxp-$daysbef_pwdexp)))  OR ($datediff > $maxdaysxp))
				{
					$notifypwd_exp=1;
				}
				
				$pwexempt_que= $database->execute("SELECT * FROM `user` u INNER JOIN usertype ut ON u.UserTypeID=ut.ID WHERE ut.code  IN ".$passnotifexempt."
												   AND u.id=".$found_user->ID);
				
				if($pwexempt_que->num_rows)
				{
					$pwnotif_exempted=1;
				}
				
				//***

				$database->execute("UPDATE user SET LoginAttempt = 0 WHERE LoginName = '".$username."' AND Password = '".md5($password)."'");
				$database->execute("INSERT INTO userloginhistory SET UserID = ".$found_user->ID.", LoginTime = NOW(), `Changed` = 1, `ClientIPAddress`='$ip' ");
				
				$userdetails = $database->execute("SELECT EnrollmentDate FROM user WHERE LoginName = '".$username."' AND Password = '".md5($password)."'");
				$user = $userdetails->fetch_object();
				
				$userquery = $database->execute("SELECT ID FROM loginhistory WHERE UserID = ".$found_user->ID);
				
				//empty logs checker
				if($userquery->num_rows == 0)
				{
					$database->execute("INSERT INTO loginhistory SET
										UserID = ".$found_user->ID.",
										Status = 'Active',
										HistoryDate = '".$user->EnrollmentDate."',
										CreateBy = 'System',
										Changed = 1");
				}
				
				$session->session_set('user_session_name',$username);
				User::setUserSession($found_user->ID); //Added by: jdymosco 2013-05-20
				//tpi_EODChkModLockIfExistsOrEmptyNotProcess($MODULES_FOR_LOCKING);
				
				//redirect_to("../index.php?pageid=0"); //original link
				
				if(($notifypwd_exp==1) && ($pwnotif_exempted==0)) 
				   {
					  redirect_to("../index.php?pageid=0&pwx"); 
				   }
				else
				   {
					  redirect_to("../index.php?pageid=0");
				   }	
			}
  } 
  else
  {
    // username/password combo was not found in the database
    $message = "Username/password combination incorrect.";
	redirect_to("../login.php?msg=".$message);
  }
  
} else { // Form has not been submitted.
  $username = "";
  $password = "";
  $message = "Please try to login first.";
  redirect_to("../login.php?msg=".$message);
}

?>