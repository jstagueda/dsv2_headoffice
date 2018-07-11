<?php

	global $database;
	$errmsg="";
	
	if (isset($_POST['btnUpdate']))
	{
		try
		{
			$database->beginTransaction();
			$id = $_POST["hdnBranchID"];
				
			$code 			= htmlentities(addslashes($_POST['txtfldCode']));
			$name 			= htmlentities(addslashes($_POST['txtfldName']));
			$address 		= htmlentities(addslashes($_POST['txtStreetAdd']));
			$areaid 		= htmlentities(addslashes($_POST['cboRegion']));
			$zipcode 		= htmlentities(addslashes($_POST['txtZipCode']));
			$telno1 		= htmlentities(addslashes($_POST['txtTelNo1']));
			$telno2 		= htmlentities(addslashes($_POST['txtTelNo2']));
			$telno3 		= htmlentities(addslashes($_POST['txtTelNo3']));
			$faxno 			= htmlentities(addslashes($_POST['txtFaxNo']));
			$branchtype 	= htmlentities(addslashes($_POST['cboBranchType']));
			$branchsize 	= htmlentities(addslashes($_POST['cboBranchSize']));
			$contactperson 	= htmlentities(addslashes($_POST['cboEmployee']));
			$salesdir 		= htmlentities(addslashes($_POST['cboSalesDir']));
			$aodir 			= htmlentities(addslashes($_POST['cboAODir']));
			$salesdept 		= htmlentities(addslashes($_POST['cboSalesDept']));
			$aodept 		= htmlentities(addslashes($_POST['cboAODept']));
			$tin			= htmlentities(addslashes($_POST['txtTIN']));
			$permitno 		=htmlentities(addslashes($_POST['txtPermitNo']));
			$serversn 		=htmlentities(addslashes($_POST['txtServerSN']));
			
			$rs_existingBranch = $sp->spSelectExistingBranch($database, $id, trim($code));
			
			if(!$rs_existingBranch ->num_rows)
			{
				$database->execute("SET FOREIGN_KEY_CHECKS = 0");
				$affected_rows = $sp->spUpdateBranch($database, $id, $code, $name, $address, $areaid, $zipcode, $telno1, $telno2, $telno3, $faxno, $branchtype, $contactperson, $tin, $permitno, $serversn, $salesdir, $aodir, $salesdept, $aodept, $branchsize);	
				if (!$affected_rows)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				
				foreach ($_POST['chkInclude'] as $key=>$value)
				{
					if(isset($_POST['isPrimary']))
					{
						if ($_POST['isPrimary'] == $value)
						{
							$primary = 1;				
						}
						else
						{
							$primary = 0;
						}
					}
					else
					{
						$primary = 0;
					}
					$rs_branchbank = $sp->spInsertUpdateBranchBank($database, $id, $value, $primary);				
				}
											
			  	$database->commitTransaction();							
			  	$message = "Successfully updated record.";
			  	redirect_to("index.php?pageid=5&msg=$message");
			}
			else
			{
				  $database->commitTransaction();
				  $errorMessage = "Code already exists.";
				  redirect_to("index.php?pageid=5&errmsg=$errorMessage");	
			}
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("index.php?pageid=5&errmsg=$errmsg");
		}
	}
	else if(isset($_POST['btnSave'])) 
	{
		try
		{
			$database->beginTransaction();
			$code			 = 	htmlentities(addslashes($_POST['txtfldCode']));
			$name			 = 	htmlentities(addslashes($_POST['txtfldName']));
			$address 		 = 	htmlentities(addslashes($_POST['txtStreetAdd']));
			$areaid 		 = 	htmlentities(addslashes($_POST['cboRegion']));
			$zipcode 		 = 	htmlentities(addslashes($_POST['txtZipCode']));
			$telno1 		 = 	htmlentities(addslashes($_POST['txtTelNo1']));
			$telno2 		 = 	htmlentities(addslashes($_POST['txtTelNo2']));
			$telno3 		 = 	htmlentities(addslashes($_POST['txtTelNo3']));
			$faxno 			 = 	htmlentities(addslashes($_POST['txtFaxNo']));
			$branchtype 	 = 	htmlentities(addslashes($_POST['cboBranchType']));
			$branchsize 	 = 	htmlentities(addslashes($_POST['cboBranchSize']));
			$contactperson 	 = 	htmlentities(addslashes($_POST['cboEmployee']));
			$salesdir 		 = 	htmlentities(addslashes($_POST['cboSalesDir']));
			$aodir 			 = 	htmlentities(addslashes($_POST['cboAODir']));
			$salesdept 		 = 	htmlentities(addslashes($_POST['cboSalesDept']));
			$aodept 		 = 	htmlentities(addslashes($_POST['cboAODept']));
			$tin			 = 	htmlentities(addslashes($_POST['txtTIN']));
			$permitno 		 = 	htmlentities(addslashes($_POST['txtPermitNo']));
			$serversn 		 = 	htmlentities(addslashes($_POST['txtServerSN']));
			
			$rs_existingBranch = $sp->spSelectExistingBranch($database,-1,trim($code));
			if($rs_existingBranch->num_rows)
			{
				$database->commitTransaction();
				$errorMessage = "Code already exists.";
				redirect_to("index.php?pageid=5&errmsg=$errorMessage");	
			}
			else
			{	
				$database->execute("SET FOREIGN_KEY_CHECKS = 0");
				$affected_rows = $sp->spInsertBranch($database,$code, $name, $address, $areaid, $zipcode, $telno1, $telno2, $telno3, $faxno, $branchtype, $contactperson, $tin, $permitno, $serversn, $salesdir, $aodir, $salesdept, $aodept, $branchsize);	
				if (!$affected_rows)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				
				if($affected_rows->num_rows)
				{
					while($row = $affected_rows->fetch_object())
					{
						$bID = $row->ID;
					}
				}
				
				foreach ($_POST['chkInclude'] as $key=>$value)
				{
					if(isset($_POST['isPrimary']))
					{
						if ($_POST['isPrimary'] == $value)
						{
							$primary = 1;				
						}
						else
						{
							$primary = 0;
						}
					}
					else
					{
						$primary = 0;
					}
					$rs_branchbank = $sp->spInsertUpdateBranchBank($database, $bID, $value, $primary);				
				}
			
				$database->commitTransaction();
				$message = "Successfully saved record.";
				redirect_to("index.php?pageid=5&msg=$message");	
			}	
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("index.php?pageid=5&errmsg=$errmsg");
		}
	}
?>