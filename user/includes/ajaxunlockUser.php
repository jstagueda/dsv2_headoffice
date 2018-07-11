<?php 
	include "../../initialize.php";
	include "scunlockusers.php";
	include "pagination.php";
	
	
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
				while($res = $usertotal->fetch_object())
				{
					
					$isenabled = '<input type="button" class="btn" value="Unlock User" name="UnlockUser" onclick="return DisabledUser('.$res->UserID.');">';
						$style = "background:lightgreen; color:red;";	
					
					echo '<tr class="trlist">
							<td><a style="color:blue;" href="javascript:void(0);" onclick="return ViewDetails('.$res->UserID.')">'.$res->UserName.'</a></td>
							<td>'.$res->LoginName.'</td>
							<td>'.$res->UserType.'</td>
							<td align="center">'.$res->DateRegistered.'</td>
							<td align="center" style="'.$style.'">'.$res->Status.'</td>
							<td align="center">'.$res->LastLoginTime.'</td>
							<td align="center">
								'.$isenabled.'
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
				
				$database->execute("UPDATE userloginhistory ulh
									SET ulh.LogoutTime = NOW()
									WHERE ulh.LogoutTime IS NULL 
									AND DATE(ulh.LoginTime) = DATE(NOW())
									AND ulh.UserID = ".$_POST['UserID']."
								   ");	
								   
				$database->execute("INSERT INTO loginhistory SET 
									UserID = ".$_POST['UserID'].",
									Status = 'Unlocked',
									HistoryDate = NOW(),
									CreateBy = '".UpdatedBy($_SESSION['user_id'])."',
									Changed = 1");	

				$database->execute("INSERT INTO loginhistory SET 
									UserID = ".$_POST['UserID'].",
									Status = 'Active',
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
		
	}
	
?>