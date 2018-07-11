<?php
	require_once("../initialize.php");
	global $database;

	if(isset($_POST['btnSave'])) 
	{
		try
		{
			$database->beginTransaction();
			
			$code = htmlentities(addslashes($_POST['txtCodeEmployee']));
			$lname = htmlentities(addslashes($_POST['txtlnameEmployee']));
			$fname = htmlentities(addslashes($_POST['txtfnameEmployee']));
			$mname = htmlentities(addslashes($_POST['txtmnameEmployee']));
			$bday = addslashes($_POST['txtBdayEmployee']);
			$dhired = addslashes($_POST['txtDateHired']);
			$etid = addslashes($_POST['cboEmployeeType']);
			$deptid = addslashes($_POST['cboDepartment']);		
			$posid = addslashes($_POST['cboPosition']);
			$brid = addslashes($_POST['cboBranch']);
				
			$rs_ExistingEmployee = $sp->spSelectExistingEmployee($database, 0, $code);
			
			if($rs_ExistingEmployee->num_rows)
			{
				$database->commitTransaction();
				$errormessage = "Code already exists.";
				redirect_to("../index.php?pageid=12&errmsg=$errormessage");	
			}
			else
			{
				$database->execute("SET FOREIGN_KEY_CHECKS = 0");
				$affected_rows = $sp->spInsertEmployee($database,$code, $lname, $fname, $mname, date('Y-m-d',strtotime($bday)), date('Y-m-d',strtotime($dhired)), $etid, $deptid, $posid, $brid);
				if (!$affected_rows)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				
				$database->commitTransaction();	
				$message = "Successfully saved record.";
				redirect_to("../index.php?pageid=12&msg=$message");		
			}
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage()."\n";
			redirect_to("../index.php?pageid=12&errmsg=$message");	
		}
	} 
	elseif (isset($_POST['btnUpdate'])) 
	{
		try
		{
			$database->beginTransaction();	
			$idtest = $_GET['empid'];
			$id = $_POST["hdnEmployeeID"];
			$code = htmlentities(addslashes($_POST['txtCodeEmployee']));
			$lname = htmlentities(addslashes($_POST['txtlnameEmployee']));
			$fname = htmlentities(addslashes($_POST['txtfnameEmployee']));
			$mname = htmlentities(addslashes($_POST['txtmnameEmployee']));
			$bday = addslashes($_POST['txtBdayEmployee']);
			$dhired = addslashes($_POST['txtDateHired']);
			$etid = addslashes($_POST['cboEmployeeType']);
			$deptid = addslashes($_POST['cboDepartment']);		
			$posid = addslashes($_POST['cboPosition']);
			$brid = addslashes($_POST['cboBranch']);
			
			$rs_ExistingEmployee = $sp->spSelectExistingEmployee($database, $id,$code);
			$rs_ExistingEmployee2 = $sp->spSelectExistingEmployee2($database, $id,$code);
			
			if($rs_ExistingEmployee2->num_rows)
			{
				$database->execute("SET FOREIGN_KEY_CHECKS = 0");
				$affected_rows = $sp->spUpdateEmployee($database,$id, $code, $lname, $fname, $mname, date('Y-m-d',strtotime($bday)), date('Y-m-d',strtotime($dhired)), $etid, $deptid, $posid, $brid);
				if (!$affected_rows)
				{			
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				$database->commitTransaction();
				$message = "Successfully updated record.";
				redirect_to("../index.php?pageid=12&msg=$message");
			}
			else
			{			
				if($rs_ExistingEmployee -> num_rows)
				{
					$database->commitTransaction();
					$errormessage = "Code already exists.";
					redirect_to("../index.php?pageid=12&errmsg=$errormessage");	
				}
				else
				{
					$affected_rows = $sp->spUpdateEmployee($database,$id, $code, $lname, $fname, $mname, date('Y-m-d',strtotime($bday)), date('Y-m-d',strtotime($dhired)), $etid, $deptid, $posid, $brid);
					if (!$affected_rows)
					{				
						throw new Exception("An error occurred, please contact your system administrator.");
					}
					$database->commitTransaction();
					$message = "Successfully updated record.";
					redirect_to("../index.php?pageid=12&msg=$message");
				}
			}
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage()."\n";
			redirect_to("../index.php?pageid=12&errmsg=$message");	
		}
	}
	elseif (isset($_POST['btnDelete']))
	{
		try
		{
			$database->beginTransaction();
			$id = 0;
			$id = $_POST["hdnEmployeeID"];
		    $affected_rows = $sp->spDeleteEmployee($database, $id);
			if (!$affected_rows)
			{	
				throw new Exception("An error occurred, please contact your system administrator.");
			}
			$database->commitTransaction();
			$message = "Successfully deleted record.";
			redirect_to("../index.php?pageid=12&msg=$message");
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage()."\n";
			redirect_to("../index.php?pageid=12&errmsg=$message");	
		}
	}
?>