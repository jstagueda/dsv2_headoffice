<?php 

	include "../../initialize.php";
	include "users.php";
	include "pagination.php";
	$cuserid = $session->user_id;
	
	if(isset($_POST['action'])){
		
		if($_POST['action'] == "UserListPagination"){
			
			echo '<table width="100%" cellspacing="0" cellpadding="0" class="bordersolo">
					
						<tr class="trheader">
							<td>User Name</td>
							<td>Login Name</td>
							<td>User Type</td>
							<td>Date Enrolled</td>
							<td>Status</td>
							<td>Last Login</td>
							<td>Action</td>
						</tr>';
			
			$user = userlist($_POST['page'], 10, false);
			$usertotal = userlist($_POST['page'], 10, true);
			if($usertotal->num_rows){
				while($res = $usertotal->fetch_object()){
					
					if($res->Status == "Active"){
						$isenabled = '<input type="button" class="btn" value="Disable" name="btnDisabled" onclick="return DisabledUser('.$res->UserID.');">';
						$style = "background:lightgreen; color:red;";
					}else{
					
						if($res->Status == "Expired"){
							
							$isenabled = '<input type="button" class="btn" value="Change Password" name="btnChangePassword" onclick="return ChangePasword('.$res->UserID.');">';
							$style = "background:#FF6666; color:white;";
							
						}else{
							$isenabled = '<input type="button" class="btn" value="Enable" name="btnEnabled" onclick="return EnabledUser('.$res->UserID.');">';
							$style = "background:#FF6666; color:white;";
						}
						
					}
					
					echo '<tr class="trlist">
							<td><a style="color:blue;" href="javascript:void(0);" onclick="return ViewDetails('.$res->UserID.')">'.$res->UserName.'</a></td>
							<td>'.$res->LoginName.'</td>
							<td>'.$res->UserType.'</td>
							<td align="center">'.$res->DateRegistered.'</td>
							<td align="center" style="'.$style.'">'.$res->Status.'</td>
							<td align="center">'.$res->LastLoginTime.'</td>
							<td align="center">
								'.$isenabled.'
								<input type="button" class="btn" value="View Status Logs" name="btnStatusLogs" onclick="return getLogs('.$res->UserID.');">
							</td>
						</tr>';
				}
			}else{
				
				echo '<tr class="trlist">
						<td align="center" colspan="7">No result found.</td>
					</tr>';
				
			}
			
			echo '</table>';
					
			if($usertotal->num_rows){
				echo '<div style="margin-top:10px;">';
				echo AddPagination(10, $user->num_rows, $_POST['page']);
				echo '</div>';
			}
			
			die();
		}
		
		
		if($_POST['action'] == "DisabledUser"){
			
			try{
				
				$database->beginTransaction();
				
				$database->execute("INSERT INTO loginhistory SET 
									UserID = ".$_POST['UserID'].",
									Status = 'Disabled',
									HistoryDate = NOW(),
									CreateBy = '".UpdatedBy($_SESSION['user_id'])."',
									Changed = 1");
				
				$database->commitTransaction();
				$result['Success'] = 1;
				
			}catch(Exception $e){
				
				$database->rollbackTransaction();
				$result['Success'] = 0;
				$result['ErrorMessage'] = $e->getMessage();
				
			}
			
			die(json_encode($result));
			
		}
		
		if($_POST['action'] == "EnabledUser"){
			
			try{
				
				$database->beginTransaction();
				
				$database->execute("INSERT INTO loginhistory SET 
									UserID = ".$_POST['UserID'].",
									Status = 'Active',
									HistoryDate = NOW(),
									CreateBy = '".UpdatedBy($_SESSION['user_id'])."',
									Changed = 1");
				$database->execute("UPDATE user SET LoginAttempt = 0 WHERE ID = ".$_POST['UserID']."");
				$result['Success'] = 1;
				$database->commitTransaction();
				
			}catch(Exception $e){
				
				$database->rollbackTransaction();
				$result['Success'] = 0;
				$result['ErrorMessage'] = $e->getMessage();
				
			}
			
			die(json_encode($result));
			
		}
		
		
		if($_POST['action'] == "getUserLogs"){
			
			echo "<div style='margin:5px; max-height:500px; min-width:400px;' class='bordersolo'>";
				echo "<table width='100%' cellpadding='0' cellspacing='0'>";
					
					echo "<tr class='trheader'>
							<td>Date</td>
							<td>Status</td>
							<td>Updated By</td>
						</tr>";
						
					$userstatuslogs = userstatuslogs($_POST['UserID']);
					if($userstatuslogs->num_rows){
						while($res = $userstatuslogs->fetch_object()){
							echo "<tr class='trlist'>
									<td align='center'>".$res->UserStatusDate."</td>
									<td align='center' style='font-weight:bold;'>".$res->UserStatus."</td>
									<td align='center' style='font-weight:bold;'>".$res->CreateBy."</td>
								</tr>";
						}
					}
					
				echo "</table>";
			echo "</div>";
			
			die();
		}
		
		if($_POST['action'] == "ChangePasswordForm"){
			
			echo "<div style='margin:5px;'>";
			echo "<form action='' name='changepasswordform' method=''>";
				echo "<br />";
				echo "<input type='hidden' name='UserID' value='".$_POST['UserID']."'>";
				
				echo "<b>New Password &nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp; </b>";
				echo "<input type='password' name='changepassword' class='txtfield'>";
			
			echo "</form>";
			echo "</div>";
			die();
		}
		
		
		if($_POST['action'] == "ViewUserDetails"){
			
			$query = $database->execute("SELECT 
										e.FirstName,
										e.LastName,
										IFNULL(e.MiddleName, 'N/A') MiddleName,
										DATE_FORMAT(e.Birthdate, '%M %d, %Y') Birthdate,
										ut.Name `Position`
									FROM employee e 
									INNER JOIN `user` u ON u.EmployeeID = e.ID
									INNER JOIN usertype ut ON ut.ID = u.UserTypeID
									WHERE u.ID = ".$_POST['UserID']."");
			$res = $query->fetch_object();
			
			echo "<div style='margin:10px; width:400px;'>";
			
			echo "<div style='font-weight:bold; font-size:14px; text-align:center; padding:10px;'>User Details</div>";
			
			echo "<table style='width:100%;' cellpadding='2' cellspacing='2'>";
			
				echo "<tr>
						<td class='fieldlabel'>First Name</td>
						<td class='separator'>:</td>
						<td>".$res->FirstName."</td>
					</td>";
				
				echo "<tr>
						<td class='fieldlabel'>Last Name</td>
						<td class='separator'>:</td>
						<td>".$res->LastName."</td>
					</td>";
					
				echo "<tr>
						<td class='fieldlabel'>Middle Name</td>
						<td class='separator'>:</td>
						<td>".$res->MiddleName."</td>
					</td>";
					
				echo "<tr>
						<td class='fieldlabel'>Birth Date</td>
						<td class='separator'>:</td>
						<td>".$res->Birthdate."</td>
					</td>";
					
				echo "<tr>
						<td class='fieldlabel'>Position</td>
						<td class='separator'>:</td>
						<td>".$res->Position."</td>
					</td>";
			
			echo "</table>";
			echo "</div>";
			die();
		}
		
		
		if($_POST['action'] = "SaveChangePassword"){
			
			try{
				
				$database->beginTransaction();
				
				$ispasswordexist = $database->execute("SELECT * FROM userpasswordhistory WHERE UserID = ".$_POST['UserID']." AND Password = '".md5($_POST['changepassword'])."'");
				if($ispasswordexist->num_rows){
					throw new Exception("<p>Password already used.</p>");
				}
				
				$database->execute("INSERT INTO userpasswordhistory SET
									UserID = ".$_POST['UserID'].",
									Password = '".md5($_POST['changepassword'])."',
									CreateBy = '".UpdatedBy($_SESSION['user_id'])."',
									CreatedByID = ".$cuserid.",
									HistoryDate = NOW(),
									Changed = 1");
									
				$database->execute("UPDATE user SET
									Password = '".md5($_POST['changepassword'])."',
									`changenextlogon`=1,
									Changed = 1
									WHERE ID = ".$_POST['UserID']."");
				
				$database->execute("INSERT INTO loginhistory SET 
									UserID = ".$_POST['UserID'].",
									Status = 'Active',
									HistoryDate = NOW(),
									CreateBy = '".UpdatedBy($_SESSION['user_id'])."',
									Changed = 1");
									
				$database->execute("update userloginhistory
				                    set `PasswordChanged` = 1
				                    where userloginhistory.UserID  = ".$_POST['UserID']."
									ORDER BY id DESC LIMIT 1   
									");					
									
				
				$result['Success'] = 1;
				$result['ErrorMessage'] = "<p>Password has been successfully updated.</p>";
				
				$database->commitTransaction();
			
			}catch(Exception $e){
				
				$database->rollbackTransaction();
				$result['Success'] = 0;
				$result['UserID'] = $_POST['UserID'];
				$result['ErrorMessage'] = $e->getMessage();
				
			}
			
			die(json_encode($result));
			
		}
		
	}
	
?>