<?php
	require_once("../initialize.php");
	global $database;
	$errmsg = "";
	
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	}		
		
	if (isset($_POST['btnUpdate']))
	{
		try
		{
			$database->beginTransaction();
			$id = 0;			
			$id = $_POST["hdnUserID"];
			
			$username = htmlentities(addslashes($_POST['txtUserName']));
			$loginname = htmlentities(addslashes($_POST['txtLoginName']));	
			$usertypeid = $_POST["cboUserType"];
			$password = htmlentities(addslashes($_POST['txtPassword']));	
			$oldpassword = htmlentities(addslashes($_POST['txtOldPassword']));
			
			/*$rsCheckUser = $sp->spCheckUser($database,$loginname,$oldpassword);
			if($rsCheckUser->num_rows >= 1)
			{*/
				
				//check if new password already exist to password history
				$passwordhistory = $database->execute("SELECT ID FROM userpasswordhistory WHERE UserID = ".$id." AND `Password` = '".md5($password)."'");
				if($passwordhistory->num_rows){
					//throw new Exception("<p>Password is already used. Please insert new password.</p>");
					$message = "Password already used. Please insert new password.";
					redirect_to("../index.php?pageid=15&msg=$message");
				}
			
				$affected_rows = $sp->spUpdateUser($database, $id, $username, $loginname, $password, $usertypeid);
				if (!$affected_rows)
				{
					throw new Exception("An error occurred, please contact your system administrator");
				}	
				
				$currentuser = $database->execute("SELECT u.ID, CONCAT(e.FirstName, ' ', e.LastName) CurrentUser FROM user u INNER JOIN employee e ON e.ID = u.EmployeeID WHERE u.ID = ".$_SESSION['user_id']);
				$cuser = $currentuser->fetch_object();
				
				$database->execute("INSERT INTO userpasswordhistory SET
									UserID = ".$id.",
									`Password` = '".md5($password)."',
									HistoryDate = NOW(),
									CreateBy = '".$cuser->CurrentUser."',
									CreatedByID = ".$cuser->ID.",
									`Changed` = 1");

				$database->execute("UPDATE `user` SET `changenextlogon`=1 WHERE `id`=".$id);
									
				$database->commitTransaction();	
				$message = "Successfully updated record.";
				redirect_to("../index.php?pageid=15&msg=$message");
			/*}
			else
			{
				$message = "Invalid old password.";
				redirect_to("../index.php?pageid=15&msg=$message");
				
			}*/
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage();
			redirect_to("../index.php?pageid=15&errmsg=$errmsg");
		}
	}
	else if(isset($_POST['btnSave'])) 
	{
		try
		{
			$database->beginTransaction();
			$employeeid = $_POST["hdnEmployeeID"];
			$username = htmlentities(addslashes($_POST['txtUserName']));
			$loginname = htmlentities(addslashes($_POST['txtLoginName']));	
			$usertypeid = $_POST["cboUserType"];
			$password = htmlentities(addslashes($_POST['txtPassword']));	
					
			$rs_existingLogin = $sp->spSelectExistingLogin($database, $loginname, 0);
			
			if($rs_existingLogin->num_rows)
			{
				$errorMessage = "Username already exists.";
				redirect_to("../index.php?pageid=15&errmsg=$errorMessage");	
			}
			else
			{
				$affected_rows = $sp->spInsertUser($database, $username, $loginname, $password, $employeeid, $usertypeid);
				if (!$affected_rows)
				{
					throw new Exception("An error occurred, please contact your system administrator");
				}
				
				$currentuser = $database->execute("SELECT u.ID, CONCAT(e.FirstName, ' ', e.LastName) CurrentUser FROM user u INNER JOIN employee e ON e.ID = u.EmployeeID WHERE u.ID = ".$_SESSION['user_id']);
				$cuser = $currentuser->fetch_object();
				
				$query = $database->execute("SELECT u.ID FROM user u INNER JOIN employee e ON e.ID = u.EmployeeID WHERE e.ID = $employeeid");
				$user = $query->fetch_object();
				
				$database->execute("INSERT INTO loginhistory SET
									UserID = ".$user->ID.",
									`Status` = 'Active',
									HistoryDate = NOW(),
									CreateBy = '".$cuser->CurrentUser."',
									`Changed` = 1");
									
				$database->execute("INSERT INTO userpasswordhistory SET
									UserID = ".$user->ID.",
									`Password` = '".md5($password)."',
									HistoryDate = NOW(),
									CreateBy = '".$cuser->CurrentUser."',
									CreatedByID = ".$cuser->ID.",
									`Changed` = 1");
				
				$database->execute("UPDATE `user` SET `changenextlogon`=1 WHERE `id`=".$user->ID);
				$database->commitTransaction();
				$message = "Successfully saved record.";
				redirect_to("../index.php?pageid=15&msg=$message");		
			}
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage();
			redirect_to("../index.php?pageid=15&errmsg=$errmsg");
		}					
	}
	else if(isset($_POST['btnDelete'])) 
	{
		try
		{
			$database->beginTransaction();
			$id = 0;
			$id = $_POST["hdnUserID"];
		
			$affected_rows = $sp->spDeleteUser($database, $id);
			if (!$affected_rows)
			{
				throw new Exception("An error occurred, please contact your system administrator");
			}	
			$database->commitTransaction();
			$message = "Successfully deleted record.";
			redirect_to("../index.php?pageid=15&msg=$message");
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage();
			redirect_to("../index.php?pageid=15&errmsg=$errmsg");
		}
	}
		
?>