<?php 
include "../initialize.php";

if(isset($_POST['action'])){
	
	if($_POST['action'] == "LoginUser"){
		
/* 		echo '123';
		die(); */
		$query = $database->execute("SELECT LoginAttempt, ID, UserTypeID  FROM user WHERE LoginName = '".$_POST['username']."'");
		$user = $query->fetch_object();
		
		$loginquery = $database->execute("SELECT SettingValue FROM settings WHERE SettingCode = 'LOGATTEMPT'");
		$login = $loginquery->fetch_object();
		
		$loginhistory = $database->execute("SELECT ID FROM loginhistory 
										WHERE UserID = ".$user->ID." 
										AND ID = (SELECT MAX(ID) FROM loginhistory WHERE UserID = ".$user->ID.")
										AND Status = 'Blocked'");
		
		
		//check if user is not administrator then validate account
		if($user->UserTypeID != 28){
		//**************************************
			$maxdaysxp=GetSettingValue($database, 'MAXPWDVAL');
			$pwexp_exempt=GetSettingValue($database, 'PWDEXPEXEMPT');
			$pwexpired=0;
			$pwexp_exempted=0;
			
			$pwordexp= $database->execute("SELECT `ID` id, `userid` userid, `historydate` historydate, NOW() datenow, DATEDIFF(NOW(),`historydate`) datedifference FROM userpasswordhistory WHERE userid=".$user->ID." ORDER BY id DESC LIMIT 1");
			if($pwordexp->num_rows){
				while($pwxp = $pwordexp->fetch_object())
					{
					$datediff =	$pwxp->datedifference;
					}
			}
			
			if ($datediff > $maxdaysxp) {
				$pwexpired=1;
			}
			
			$pwexempt_que= $database->execute("SELECT * FROM `user` u INNER JOIN usertype ut ON u.UserTypeID=ut.ID WHERE ut.code  IN ".$pwexp_exempt."
											   AND u.id=".$user->ID);
			if($pwexempt_que->num_rows){
				$pwexp_exempted=1;
			}				
			
			//**********************************
						//check if the login attempt failed a number if times
						if($user->LoginAttempt >= $login->SettingValue){
							
							if($loginhistory->num_rows == 0){
								$database->execute("INSERT INTO loginhistory SET
												UserID = ".$user->ID.",
												Status = 'Blocked',
												HistoryDate = NOW(),
												CreateBy = 'System',
												Changed = 1");
							}
							
							$result['Success'] = 0;
							$result['ErrorMessage'] = "Login attempt failed a number of times.\n\rAccount is disabled.\n\rPlease call IT Operations.";
							
						}else{
						
							$loginhistoryx = $database->execute("SELECT ID FROM loginhistory 
														WHERE UserID = ".$user->ID." 
														AND ID = (SELECT MAX(ID) FROM loginhistory WHERE UserID = ".$user->ID.")
														AND Status = 'Expired'");
						
							//check if the user was blocked
							if($loginhistoryx->num_rows){
								
								$result['Success'] = 0;
								$result['ErrorMessage'] = "Account is already expired.\n\rPlease change your password.";
								
							}else{//---
							
							if (($pwexpired==1) AND ($pwexp_exempted==0)){
							$database->execute("INSERT INTO loginhistory SET
														UserID = ".$user->ID.",
														Status = 'Expired',
														HistoryDate = NOW(),
														CreateBy = 'System',
														Changed = 1");
														
										
										$result['Success'] = 0;
										$result['ErrorMessage'] = "Account is already expired.\n\rPlease change your password.";
							}
							else
							{
															//check if the user account was expired
								$userloginquery = $database->execute("SELECT * FROM userloginhistory 
																	WHERE UserID = ".$user->ID."
																	AND ID = (SELECT MAX(ID) FROM userloginhistory WHERE UserID = ".$user->ID.")");
								$userlogin = $userloginquery->fetch_object();
								
								//expiration days
								$expiration = $database->execute("SELECT SettingValue FROM settings WHERE SettingCode = 'PWDEXPIRE'");
								$expired = $expiration->fetch_object();
								
								$disabledquery = $database->execute("SELECT ID FROM loginhistory 
																WHERE UserID = ".$user->ID." 
																AND ID = (SELECT MAX(ID) FROM loginhistory WHERE UserID = ".$user->ID.")
																AND Status = 'Disabled'");
								
								if($userloginquery->num_rows){
									//expired account
									$expireddays = ((strtotime(date("Y-m-d")) - strtotime($userlogin->LoginTime)) / 86400);
									if($expireddays >= $expired->SettingValue AND $userlogin->PasswordChanged == 0)
									{
									
									    #verify last password change 
										
										$database->execute("INSERT INTO loginhistory SET
														UserID = ".$user->ID.",
														Status = 'Expired',
														HistoryDate = NOW(),
														CreateBy = 'System',
														Changed = 1");
														
										
										$result['Success'] = 0;
										$result['ErrorMessage'] = "Account is already expired.\n\rPlease change your password.";
										
									}else{
										//deactivated account
										if($disabledquery->num_rows){
											$result['Success'] = 0;
											$result['ErrorMessage'] = "Account was deactivated.\n\rPlease call IT Operations.";
										}else{
											$result['Success'] = 1;
											$_SESSION['freshlogin']=1;
										}		
										
									}
									
								}else{
									//deactivated account
									if($disabledquery->num_rows){
										$result['Success'] = 0;
										$result['ErrorMessage'] = "Account was deactivated.\n\rPlease call IT Operations.";
									}else{
										$result['Success'] = 1;
										$_SESSION['freshlogin']=1;
									}					
									
									}
								
								}

							}//-----
							
						}
		
		}else{
			$result['Success'] = 1;
			$_SESSION['freshlogin']=1;
		}
		unset($_SESSION['changepw']);
		die(json_encode($result));
		
	}
	
}

?>