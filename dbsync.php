<?php
// **********************************************
// 		   Webservice Sync Tupperware
// **********************************************

ini_set('display_errors', 0);
ini_set("memory_limit", "256M");

// If it's going to need the database, then it's 
// probably smart to require it before we start.
//defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

//defined('SITE_ROOT') ? null : define('SITE_ROOT', DS.'var'.DS.'www'.DS.'html'.DS.'tpi_jeff');
//defined('SITE_ROOT') ? null : define('SITE_ROOT', DS.'var'.DS.'www'.DS.'html'.DS.'tpi_qa_new');
//defined('CS_PATH') ? null : define('CS_PATH', SITE_ROOT.DS.'class'.DS);
//require_once(CS_PATH.DS.'dbconnection.php');

require_once('initialize.php');
global $database;

$method = $_REQUEST["method"];
$branchID = $_REQUEST["branchID"];

if ($method == "startsync")
{
	$syncTypeID = $_REQUEST["syncTypeID"];
	$dbresult = $sp->spInsertSyncLog($database, $syncTypeID, $branchID);
    //echo "call spInsertSyncLog($database, $syncTypeID, $branchID);";
    // create a new XML document
	$doc = new DomDocument('1.0');
	
	// create root node
	$root = $doc->createElement('SyncStart');
	$root = $doc->appendChild($root);

	while($row = $dbresult->fetch_assoc())
	{
		foreach ($row as $fieldname => $fieldvalue) 
		{
			$child = $doc->createElement("ID");
			$child = $root->appendChild($child);
			$value = $doc->createTextNode($fieldvalue);
			$value = $child->appendChild($value);
		}
	}

	$doc->formatOutput = true; 
	$doc->preserveWhiteSpace = false;

	// get completed xml document
	//echo $doc->saveXML();	
	$compressed = gzcompress($doc->saveXML(), 9);
	echo $compressed;
}
elseif ($method == "syncdetails")
{
	$syncID = $_REQUEST["syncID"];
	$table = $_REQUEST["table"];
	$processed = $_REQUEST["processed"];
	$IDs = $_REQUEST["IDs"];
	
	$dbresult = $sp->spInsertSyncLogDetails($database, $syncID, $table, $processed, $processed);
	try 
	{
		$database->beginTransaction();
		$dbresult = $database->execute("update syncbranch sb inner JOIN synctables st on st.ID = sb.SyncTableID set sb.Changed = 0 " .
			"where st.Name = '$table' and sb.BranchID=$branchID");
		// If there are no more branches that needs to update their copy of the table,
		// it is now safe to set the Changed field fo the main table to zero.
		// The following sql statements cannot be executed in one sql statement
		// because syncbranch
		
		$dbresult = $database->execute("select * from syncbranch sb inner JOIN synctables st on st.ID = sb.SyncTableID where st.Name = '$table' and sb.Changed = 1");
		$string = "select * from syncbranch sb inner JOIN synctables st on st.ID = sb.SyncTableID where st.Name = '$table' and sb.Changed = 1";
		
		if (empty($dbresult))
		{
			$dbresult = $database->execute("Update `$table` set Changed = 0 where ID in ($IDs);");	
		}
		//$dbresult = $database->execute("Update `$table` set Changed = 0 where ID in ($IDs);");	
		$database->commitTransaction();
	}
	catch (Exception $e)
	{
		$database->rollbackTransaction();
	}
	//echo "Update `$table` set Changed = 0 where ID in ($IDs);";
}
elseif ($method == "confirmsync")
{
	$IDs = $_REQUEST["syncID"];
	$table = $_REQUEST["table"];
	$processed = $_REQUEST["processed"];
	
	try 
	{
		$database->beginTransaction();
		$dbresult = $database->execute("update syncbranch sb inner JOIN synctables st on st.ID = sb.SyncTableID set sb.Changed = 0" .
			"where st.Name = '$table' and sb.BranchID=$branchID");
		// If there are no more branches that needs to update their copy of the table,
		// it is now safe to set the Changed field fo the main table to zero.
		// The following sql statements cannot be executed in one sql statement
		// because syncbranch 
		$dbresult = $database->execute("select * from syncbranch sb inner JOIN synctables st on st.ID = sb.SyncTableID where st.Name = '$table' and sb.Changed = 1");
		if (empty($dbresult))
		{
			$dbresult = $database->execute("Update `$table` set Changed = 0 where ID in ($IDs);");
		}
		$database->commitTransaction();
	}
	catch (Exception $e)
	{
		$database->rollbackTransaction();
	}	
}
elseif ($method == "endsync")
{
	$syncID = $_REQUEST["syncID"];
	$dbresult = $sp->spUpdateSyncLog($database, $syncID);
}

if ($method == "download")
{
	$table_id = $_REQUEST["table"];
	$lastSyncTime = $_REQUEST["lastSyncTime"];
	$type = $_REQUEST["type"];
	
	if ($type == 1)
	{
		if ($table_id == "inventoryinout")
		{
			$query = "select * from inventoryinout where MovementTypeID in (2,4)
						and BranchID = $branchID and StatusID = 6 and Changed = 1
						union all
						select * from inventoryinout where MovementTypeID in (3,1)
						and ToBranchID = $branchID and StatusID = 6 and Changed = 1";
		}
		elseif ($table_id == "inventoryinoutdetails")
		{
			$query = "select * from inventoryinoutdetails
						where InventoryInoutID in (
						select ID from inventoryinout where MovementTypeID in (2,4)
						and BranchID = $branchID and StatusID = 6 and Changed = 1
						union all
						select ID from inventoryinout where MovementTypeID in (3,1)
						and ToBranchID = $branchID and StatusID = 6 and Changed = 1)";
		}
		elseif ($table_id == "inventoryadjustment")
		{
			$query = "select * from inventoryadjustment where MovementTypeID = 10
						and BranchID = $branchID and StatusID = 24 and Changed = 1";
		}
		elseif ($table_id == "inventoryadjustmentdetails")
		{
			//$query = "select * from inventoryadjustmentdetails where InventoryAdjustmentID in (select ID from inventoryadjustment where MovementTypeID = 10 and BranchID = $branchID and StatusID = 24 and Changed = 1)";
			$query = "select iad.ID, ia.RefID InventoryAdjustmentID, iad.ProductID, iad.UnitTypeID, iad.PrevBalance, iad.CreatedQty, iad.ReasonID, iad.EnrollmentDate, iad.LastModifiedDate, iad.Changed
						from inventoryadjustmentdetails iad
						inner join inventoryadjustment ia on ia.ID = iad.InventoryAdjustmentID
						where iad.InventoryAdjustmentID in (select ID from inventoryadjustment where MovementTypeID = 10 and BranchID = $branchID and StatusID = 24 and Changed = 1)";
		}
		elseif ($table_id == "area")
		{
			$query = "SELECT a.* from area a,syncbranch sb " . 
				"inner join synctables st on st.id=sb.synctableid " .
				"where a.Changed = 1 and st.Name='area' and sb.BranchID=$branchID and sb.Changed=1 order by AreaLevelID";
		}
		elseif ($table_id == "tpi_dealerwriteoff")
		{
			$query = "select tc.ID, cu.RefID CustomerID, tc.PastDue, tc.ReasonID, tc.StatusID, tc.CreatedBy, tc.ApprovedBy, tc.EnrollmentDate, tc.LastModifiedDate, tc.Changed
						from tpi_dealerwriteoff tc
							inner join customer cu on cu.ID = tc.CustomerID
						where tc.Changed = 1 and tc.StatusID in (24, 28) and tc.CustomerID in (select CustomerID from tpi_rcustomerbranch where BranchID = $branchID)";
		}
		elseif ($table_id == "tpi_creditlimitdetails")
		{
			$query = "select tc.ID, cu.RefID CustomerID, tc.OldCreditLimit, tc.NewCreditLimit, tc.EnrollmentDate, tc.LastModifiedDate, tc.StatusID, tc.Changed
						from tpi_creditlimitdetails tc
							inner join customer cu on cu.ID = tc.CustomerID
						where tc.Changed = 1 and tc.StatusID = 24 and tc.CustomerID in (select CustomerID from tpi_rcustomerbranch where BranchID = $branchID)";
		}
		elseif ($table_id == "tpi_credit")
		{
			$query = "select tc.ID, cu.RefID CustomerID, tc.CreditTermID, tc.Character, tc.Capacity, tc.Capital, tc.Condition, tc.CalculatedCL, tc.RecommendedCL, tc.ApprovedCL, tc.EnrollmentDate, tc.LastModifiedDate, tc.Changed
						from tpi_credit tc
							inner join customer cu on cu.ID = tc.CustomerID
						where tc.Changed = 1 and tc.CustomerID in (select CustomerID from tpi_rcustomerbranch where BranchID = $branchID)";
		}
		elseif ($table_id == "product")
		{
			$query = "SELECT a.* from product a,syncbranch sb " . 
				"inner join synctables st on st.id = sb.synctableid " .
				"where a.Changed = 1 and  st.Name = 'product' and sb.BranchID = $branchID and sb.Changed = 1 order by ProductLevelID";
		}
		elseif ($table_id == "promo")
		{
			$query = "select * from promo where Changed = 1 and StatusID = 7
						and ID in (select PromoID from rpromobranch where BranchID = $branchID)";
		}
		elseif ($table_id == "promoavailment")
		{
			$query = "select * from promoavailment where PromoID in (select ID from promo where Changed = 1 and StatusID = 7
						and ID in (select PromoID from rpromobranch where BranchID = $branchID))";
		}
		elseif ($table_id == "promobuyin")
		{
			$query = "select * from promobuyin where PromoID in (select ID from promo where Changed = 1 and StatusID = 7
						and ID in (select PromoID from rpromobranch where BranchID = $branchID))";
		}
		elseif ($table_id == "promoentitlement")
		{
			$query = "select * from promoentitlement where PromoBuyinID in (select ID from promobuyin where PromoID in (select ID from promo where Changed = 1 and StatusID = 7
						and ID in (select PromoID from rpromobranch where BranchID = $branchID)))";
		}
		elseif ($table_id == "promoentitlementdetails")
		{
			$query = "select * from promoentitlementdetails where PromoEntitlementID in (select ID from promoentitlement where PromoBuyinID in (select ID from promobuyin where PromoID in (select ID from promo where Changed = 1 and StatusID = 7
						and ID in (select PromoID from rpromobranch where BranchID = $branchID))))";
		}
		elseif ($table_id == "employee")
		{
			$query = "select * from employee where Changed = 1 and StatusID = 1
						and ID in (select EmployeeID from remployeebranch where BranchID = $branchID union all select EmployeeID from remployeebranch where EmployeeID = 1)";
		}
		elseif ($table_id == "remployeebranch")
		{
			$query = "select * from remployeebranch where BranchID = $branchID union all select * from remployeebranch where EmployeeID = 1";
		}
		elseif ($table_id == "remployeeposition")
		{
			$query = "select * from remployeeposition where Changed = 1 
						and EmployeeID in (select EmployeeID from remployeebranch where BranchID = $branchID union all select EmployeeID from remployeebranch where EmployeeID = 1)";
		}
		elseif ($table_id == "user")
		{
			$query = "select * from `user` where Changed = 1 
						and EmployeeID in (select EmployeeID from remployeebranch where BranchID = $branchID union all select EmployeeID from remployeebranch where EmployeeID = 1)";
		}
		elseif ($table_id == "customer")
		{
			$query = "select * from customer where ID in (
							select c1.CustomerID from tpi_rcustomerstatus c1
								inner join (select CustomerID, max(EnrollmentDate) EnrollmentDate from tpi_rcustomerstatus group by CustomerID) c2 on c2.CustomerID = c1.CustomerID and c2.EnrollmentDate = c1.EnrollmentDate
							where c1.CustomerStatusID = 5
						) and ID not in (select CustomerID from tpi_rcustomerbranch where BranchID = $branchID) 
					  union all
					  select * from customer where ID in (select CustomerID from tpi_rcustomerbranch where BranchID = $branchID)";
		}
		elseif ($table_id == "customerdetails")
		{
			$query = "select c.Code CustomerCode, cd.FieldID, cd.ValueID, cd.Details, cd.EnrollmentDate, cd.LastModifiedDate, cd.Changed from customer c inner join customerdetails cd on cd.CustomerID = c.ID where c.ID in (
							select c1.CustomerID from tpi_rcustomerstatus c1
								inner join (select CustomerID, max(EnrollmentDate) EnrollmentDate from tpi_rcustomerstatus group by CustomerID) c2 on c2.CustomerID = c1.CustomerID and c2.EnrollmentDate = c1.EnrollmentDate
							where c1.CustomerStatusID = 5
						) and c.ID not in (select CustomerID from tpi_rcustomerbranch where BranchID = $branchID) 
					  union all
					  select c.Code CustomerCode, cd.FieldID, cd.ValueID, cd.Details, cd.EnrollmentDate, cd.LastModifiedDate, cd.Changed from customer c inner join customerdetails cd on cd.CustomerID = c.ID 
					  where c.ID in (select CustomerID from tpi_rcustomerbranch where BranchID = $branchID)";
		}
		elseif ($table_id == "tpi_customerdetails")
		{
			$query = "select c.Code CustomerCode, cd.NickName, cd.TelNo, cd.MobileNo, cd.StreetAdd, cd.AreaID, cd.ZipCode, cd.tpi_ZoneID, cd.tpi_GSUTypeID, cd.tpi_IBMCode, cd.tpi_RecruiterID, cd.Remarks, cd.EnrollmentDate, cd.LastModifiedDate, cd.Changed, cd.ApplicationFilePath from customer c inner join tpi_customerdetails cd on cd.CustomerID = c.ID where c.ID in (
							select c1.CustomerID from tpi_rcustomerstatus c1
								inner join (select CustomerID, max(EnrollmentDate) EnrollmentDate from tpi_rcustomerstatus group by CustomerID) c2 on c2.CustomerID = c1.CustomerID and c2.EnrollmentDate = c1.EnrollmentDate
							where c1.CustomerStatusID = 5
						) and c.ID not in (select CustomerID from tpi_rcustomerbranch where BranchID = $branchID) 
					  union all
					  select c.Code CustomerCode, cd.NickName, cd.TelNo, cd.MobileNo, cd.StreetAdd, cd.AreaID, cd.ZipCode, cd.tpi_ZoneID, cd.tpi_GSUTypeID, cd.tpi_IBMCode, cd.tpi_RecruiterID, cd.Remarks, cd.EnrollmentDate, cd.LastModifiedDate, cd.Changed, cd.ApplicationFilePath from customer c inner join tpi_customerdetails cd on cd.CustomerID = c.ID 
					  where c.ID in (select CustomerID from tpi_rcustomerbranch where BranchID = $branchID)";
		}
		elseif ($table_id == "customeraccountsreceivable")
		{
			$query = "select c.Code CustomerCode, cd.SalesInvoiceID, cd.OutstandingAmount, cd.DaysDue, cd.EnrollmentDate, cd.LastModifiedDate, cd.Changed from customer c inner join customeraccountsreceivable cd on cd.CustomerID = c.ID where c.ID in (
							select c1.CustomerID from tpi_rcustomerstatus c1
								inner join (select CustomerID, max(EnrollmentDate) EnrollmentDate from tpi_rcustomerstatus group by CustomerID) c2 on c2.CustomerID = c1.CustomerID and c2.EnrollmentDate = c1.EnrollmentDate
							where c1.CustomerStatusID = 5
						) and c.ID not in (select CustomerID from tpi_rcustomerbranch where BranchID = $branchID)
					  union all
					  select c.Code CustomerCode, cd.SalesInvoiceID, cd.OutstandingAmount, cd.DaysDue, cd.EnrollmentDate, cd.LastModifiedDate, cd.Changed from customer c inner join customeraccountsreceivable cd on cd.CustomerID = c.ID 
					  where c.ID in (select CustomerID from tpi_rcustomerbranch where BranchID = $branchID)";
		}
		elseif ($table_id == "customerpenalty")
		{
			$query = "select c.Code CustomerCode, cd.SalesInvoiceID, cd.Amount, cd.OutstandingAmount, cd.EnrollmentDate, cd.LastModifiedDate, cd.Changed from customer c inner join customerpenalty cd on cd.CustomerID = c.ID where c.ID in (
							select c1.CustomerID from tpi_rcustomerstatus c1
								inner join (select CustomerID, max(EnrollmentDate) EnrollmentDate from tpi_rcustomerstatus group by CustomerID) c2 on c2.CustomerID = c1.CustomerID and c2.EnrollmentDate = c1.EnrollmentDate
							where c1.CustomerStatusID = 5
						) and c.ID not in (select CustomerID from tpi_rcustomerbranch where BranchID = $branchID)
					  union all
					  select c.Code CustomerCode, cd.SalesInvoiceID, cd.Amount, cd.OutstandingAmount, cd.EnrollmentDate, cd.LastModifiedDate, cd.Changed from customer c inner join customerpenalty cd on cd.CustomerID = c.ID 
					  where c.ID in (select CustomerID from tpi_rcustomerbranch where BranchID = $branchID)";
		}
		elseif ($table_id == "customercommission")
		{
			$query = "select c.Code CustomerCode, cd.BranchID, cd.CampaignMonthID, cd.CommissionTypeID, cd.Amount, cd.OustandingBalance, cd.EnrollmentDate, cd.LastModifiedDate from customer c inner join customercommission cd on cd.CustomerID = c.ID where c.ID in (
							select c1.CustomerID from tpi_rcustomerstatus c1
								inner join (select CustomerID, max(EnrollmentDate) EnrollmentDate from tpi_rcustomerstatus group by CustomerID) c2 on c2.CustomerID = c1.CustomerID and c2.EnrollmentDate = c1.EnrollmentDate
							where c1.CustomerStatusID = 5
						) and c.ID not in (select CustomerID from tpi_rcustomerbranch where BranchID = $branchID)
					  union all
					  select c.Code CustomerCode, cd.BranchID, cd.CampaignMonthID, cd.CommissionTypeID, cd.Amount, cd.OustandingBalance, cd.EnrollmentDate, cd.LastModifiedDate from customer c inner join customercommission cd on cd.CustomerID = c.ID 
					  where c.ID in (select CustomerID from tpi_rcustomerbranch where BranchID = $branchID)";
		}
		elseif ($table_id == "productsubstitute" || $table_id == "productkit" || $table_id == "rusertypemodulecontrol")
		{
			$query = "select * from $table_id";
		}
		elseif ($table_id == "tpi_rcustomerbranch")
		{
			$query = "select c.Code CustomerCode, tr.BranchID, tr.IsPrimary, tr.Remarks, tr.CreatedBy, tr.EnrollmentDate from tpi_rcustomerbranch tr inner join customer c on c.ID = tr.CustomerID where tr.BranchID = $branchID ";
		}
		elseif ($table_id == "bank")
		{
			$query = "SELECT * FROM bank where Changed = 1";
		}
		else
		{
			$query = "select a.* from $table_id a,syncbranch sb " . 
				"inner join synctables st on st.id = sb.synctableid " .
				"where a.Changed = 1 and st.Name = '$table_id' and sb.BranchID = $branchID and sb.Changed = 1";
		}
	}
	else
	{
		if ($table_id == "customercommission")
		{
			$query = "SELECT * FROM $table_id";
		}
		elseif ($table_id == "tpi_dealerwriteoff")
		{
			$query = "select tc.ID, cu.RefID CustomerID, tc.PastDue, tc.ReasonID, tc.StatusID, tc.CreatedBy, tc.ApprovedBy, tc.EnrollmentDate, tc.LastModifiedDate, tc.Changed
						from tpi_dealerwriteoff tc
							inner join customer cu on cu.ID = tc.CustomerID
						where tc.Changed = 1 and tc.StatusID in (24, 28) and tc.CustomerID in (select CustomerID from tpi_rcustomerbranch where BranchID = $branchID)";
		}
		elseif ($table_id == "remployeebranch")
		{
			$query = "select * from remployeebranch where BranchID = $branchID union all select * from remployeebranch where EmployeeID = 1";
		}
		else
		{
			$query = "SELECT * FROM $table_id where Changed = 1";
		}
	}
	
	// $dbresult = mysql_query($query, $dbconnect);
	$dbresult = $database->execute($query);
	
	// create a new XML document
	$doc = new DomDocument('1.0');

	// create root node
	$root = $doc->createElement('root');
	$root = $doc->appendChild($root);

	// process one row at a time
	//while($row = mysql_fetch_assoc($dbresult))
	while($row = $dbresult->fetch_assoc())
	{
		// add node for each row
		$occ = $doc->createElement($table_id);
		$occ = $root->appendChild($occ);
		
		// add a child node for each field
		foreach ($row as $fieldname => $fieldvalue) 
		{
			$child = $doc->createElement($fieldname);
			$child = $occ->appendChild($child);
			$value = $doc->createTextNode($fieldvalue);
			$value = $child->appendChild($value);
		}
	}

	$doc->formatOutput = true; 
	$doc->preserveWhiteSpace = false;
	
	// get completed xml document
	$compressed = gzcompress($doc->SaveXML(), 9);
	//$compressed = $doc->SaveXML();
	echo $compressed;
}
else if ($method == "upload")
{
	
	$dom = $_REQUEST["uploadData"];
	$dom = gzuncompress($dom);
	$table = $_REQUEST["table"];
	$branchID = $_REQUEST["branchID"];
	$type = $_REQUEST["type"];
	$debug = $_REQUEST["debug"];
	
	$xml = simplexml_load_string($dom); //or die ("Error in uploading $table data!<br>");
	
	//define table structure and store in an array						
	$tables = array();
	$rsTables = $database->execute("show tables;");
		
	while($tbl = $rsTables->fetch_array())
	{
		$tblName = $tbl[0];
		$colArray = array();
		$dbresult = $database->execute("describe $tblName;");
		
		while($row = $dbresult->fetch_object())
		{
			if (stristr($row->Type, 'datetime') || stristr($row->Type, 'timestamp') || stristr($row->Type, 'char'))
			{
				$colArray[] = 1;
			}
			else 
			{
				$colArray[] = 0;
			}
		}
		$tables["$tblName"] = $colArray;
		
	}
	
	
	if ($type == 1)
	{
		$selectQry = "";
		foreach($xml -> children() as $tableName => $tablenode)
		{		
			$insertQry = "Insert into `".$tableName."` (";
			$valuesQry = "Values (";
			
			$updateQry = "Update `".$tableName."` ";
			$valuesUQry = "Set ";
			$whereQry = "";
			
			$ID = "";
			$ctr = 0;
			$refID = 0;
			$refTxnID = 0;
			$code = "";
			$custid = 0;
			$newRefTxnID = 0;
			$prodid = 0;
			$whouseid = 0;
			$invid = 0;
			$docno = "";
			$mtid = 0;
			
			foreach($tablenode -> children() as $name => $node)
			{
				if ($name == "Changed")
				{
					$insertQry .= $name . ", ";
					$valuesQry .= "0, ";
					$valuesUQry .= "$name = 0, ";
				}
				else if ($name == "IsLocked")
				{
					$insertQry .= $name . ", ";
					$valuesQry .= "0, ";
					$valuesUQry .= "$name = 0, ";
				}
				else if ($name == "LockedBy")
				{
					$insertQry .= $name . ", ";
					$valuesQry .= "0, ";
					$valuesUQry .= "$name = 0, ";
				}
				else if ($name == "BranchID")
				{
					$insertQry .= $name . ", ";
					if ($tableName == "inventoryinout")
					{
						$valuesQry .= "$node, ";
						$valuesUQry .= "$name = $node, ";
					}
					else
					{
						$valuesQry .= "$branchID, ";
						$valuesUQry .= "$name = $branchID, ";
					}
				}
				else if ($name == "ToBranchID")
				{
					$insertQry .= $name . ", ";
					if ($tableName == "inventoryinout")
					{
						$valuesQry .= "$node, ";
						$valuesUQry .= "$name = $node, ";
					}
					else
					{
						$valuesQry .= "$branchID, ";
						$valuesUQry .= "$name = $branchID, ";
					}
				}
				else if ($name == "CustomerID")
				{
					$query = "SELECT * FROM `customer` where RefID = '".$node."'";
					$dbresult = $database->execute($query);
					$num_rows = $dbresult->num_rows;
					$row = $dbresult->fetch_object();
					$custid = $row->ID;
						
					$insertQry .= $name . ", ";
					$valuesQry .= "$custid, ";
					$valuesUQry .= "$name = $custid, ";
				}
				/*elseif ($name == "StatusID")
				{
					if ($tableName != "inventoryinout")
					{
						$insertQry .= $name . ", ";
						$valuesQry .= "'".$node . "', ";
						$valuesUQry .= $name. "=" . "'".$node . "', ";
					}
				}*/
				else if ($name != "ID")
				{
					if ($name == "Code")
					{
						$code = $node;
					}
					
					$insertQry .= $name . ", ";
					
					if ($name == "RefID")
					{
						$valuesQry .= $refID . ", ";
						$valuesUQry .= "$name = $refID, ";
					}
					elseif ($name == "RefTxnID")
					{
						if ($tableName == "deliveryreceipt")
						{
							$query = "SELECT ID from salesorder where RefID = $node and BranchID = $branchID";
							$dbresult = $database->execute($query);
							$row = $dbresult->fetch_object();
							$newRefTxnID = $row->ID;
						}
						elseif ($tableName == "salesinvoice")
						{
							$query = "SELECT ID from deliveryreceipt where RefID = $node and BranchID = $branchID";
							$dbresult = $database->execute($query);
							$row = $dbresult->fetch_object();
							$newRefTxnID = $row->ID;
						}
						else
						{
							$newRefTxnID = $node;
						}
						
						$valuesQry .= $newRefTxnID . ", ";
						$valuesUQry .= "$name = $newRefTxnID, ";
					}
					elseif(empty($node))
					{
						$valuesQry .= "null, ";
						$valuesUQry .= "$name = null, ";
					}
					elseif($node=="")
					{
						if ($tables[$tableName][$ctr] == 0)
							$valuesQry .= "0, ";
							//$valuesUQry .= "$name = 0, ";
						else
							$valuesQry .= "'', ";
							$valuesUQry .= "$name = '', ";
					}
					else
					{
						if ($tables[$tableName][$ctr] == 0)
							$valuesQry .= "'".str_replace("'", " ", $node)."'". ", ";
							//$valuesUQry .= $name. " = " . $node . ", ";
						else
							$valuesQry .= "'".$node . "', ";
							$valuesUQry .= $name. "=" . "'".str_replace("'", " ", $node). "', ";
					}
				}
				else
				{
					$refID = $node;
				}
				
				if ($name == "WarehouseID")
				{
					$whouseid = $node;
				}
				
				if ($name == "InventoryID")
				{
					$invid = $node;
				}
				
				if ($name == "ProductID")
				{
					$prodid = $node;
				}
				
				if ($name == "DocumentNo")
				{
					$docno = $node;
				}
				
				if ($name == "MovementTypeID")
				{
					$mtid = $node;
				}
				
				$ctr++;
			}

			if ($refID != "")
			{
				if ($tableName == "customer")
				{
					if ($code != '')
					{
						$query = "SELECT * FROM `".$tableName."` where Code = '".$code."'";
						$dbresult = $database->execute($query);
						$num_rows = $dbresult->num_rows;
						$row = $dbresult->fetch_object();
						if ($num_rows == 0)
						{
							$custid = 0;
						}
						else
						{
							$custid = $row->ID;
						}
									
						if ($num_rows == 0)
						{
							$insertQry = substr($insertQry, 0, -2).")";
							$valuesQry = substr($valuesQry, 0, -2);
							$valuesQry .= ")";
							
							if ($debug)
							{
								echo "1. ". $insertQry."\r\n";
								echo "1.1. ". $valuesQry."\r\n";
							}
							
							$insertID = 0;
							$database->execute($insertQry." ".$valuesQry);
							$insertID = $database->insert_id();
							
							//check customer branch linking
							$cquery = "SELECT * FROM tpi_rcustomerbranch WHERE CustomerID = " . $insertID . " AND BranchID = " . $branchID;
							$cdbresult = $database->execute($cquery);
							$cnum_rows = $cdbresult->num_rows;
							
							if ($cnum_rows == 0)
							{
								$query = "insert into tpi_rcustomerbranch (CustomerID, BranchID, IsPrimary, CreatedBy, EnrollmentDate, Changed) values (". $insertID . ", ". $branchID . ", 0, 1, now(), 1)";
								$database->execute($query);
							}
						}
						else
						{
							//check customer branch linking
							$cquery = "SELECT * FROM tpi_rcustomerbranch WHERE CustomerID = " . $custid . " AND BranchID = " . $branchID;
							$cdbresult = $database->execute($cquery);
							$cnum_rows = $cdbresult->num_rows;
							
							if ($cnum_rows == 0)
							{
								$query = "insert into tpi_rcustomerbranch (CustomerID, BranchID, IsPrimary, CreatedBy, EnrollmentDate, Changed) values (". $custid . ", ". $branchID . ", 0, 1, now(), 1)";
								$database->execute($query);
							}
							
							//update
							$valuesUQry = substr($valuesUQry, 0, -2);
							$whereQry = " where ID = $custid";
							
							if ($debug)
							{
								echo $updateQry."\r\n";
								echo $valuesUQry."\r\n";
								echo $whereQry."\r\n";
							}
							$database->execute($updateQry." ".$valuesUQry." ".$whereQry);
						}
					}
				}
				elseif ($tableName == "inventory")
				{
					$query = "SELECT * FROM `".$tableName."` where WarehouseID = $whouseid and ProductID = $prodid";
					$dbresult = $database->execute($query);
					$num_rows = $dbresult->num_rows;
					$row = $dbresult->fetch_object();
					$invid = $row->ID;
					
					if ($num_rows == 0)
					{
						$insertQry = substr($insertQry, 0, -2).")";
						$valuesQry = substr($valuesQry, 0, -2);
						$valuesQry .= ")";
						
						if ($debug)
						{
							echo "1. ". $insertQry."\r\n";
							echo "1.1. ". $valuesQry."\r\n";
						}
						
						$insertID = 0;
						$database->execute($insertQry." ".$valuesQry);
						$insertID = $database->insert_id();
						
						//check inventory branch linking
						$cquery = "SELECT * FROM tpi_rinventorybranch WHERE InventoryID = " . $insertID . " AND BranchID = " . $branchID;
						$cdbresult = $database->execute($cquery);
						$cnum_rows = $cdbresult->num_rows;
						
						if ($cnum_rows == 0)
						{
							$query = "insert into tpi_rinventorybranch (InventoryID, BranchID, EnrollmentDate, LastModifiedDate, Changed) values (". $insertID . ", ". $branchID . ", now(), now(), 1)";
							$database->execute($query);
						}
					}
					else
					{
						//check inventory branch linking
						$cquery = "SELECT * FROM tpi_rinventorybranch WHERE InventoryID = " . $invid . " AND BranchID = " . $branchID;
						$cdbresult = $database->execute($cquery);
						$cnum_rows = $cdbresult->num_rows;
						
						if ($cnum_rows == 0)
						{
							$query = "insert into tpi_rinventorybranch (InventoryID, BranchID, EnrollmentDate, LastModifiedDate, Changed) values (". $invid . ", ". $branchID . ", now(), now(), 1)";
							$database->execute($query);
						}
						
						//update
						$valuesUQry = substr($valuesUQry, 0, -2);
						$whereQry = " where ID = $invid";
						
						if ($debug)
						{
							echo $updateQry."\r\n";
							echo $valuesUQry."\r\n";
							echo $whereQry."\r\n";
						}
						$database->execute($updateQry." ".$valuesUQry." ".$whereQry);
					}
				}
				elseif ($tableName == "inventoryinout")
				{
					$cquery = "SELECT * FROM $tableName ";
					if ($mtid == 2 || $mtid == 4)
					{
						$cquery .= "WHERE DocumentNo = '" . $docno . "' AND BranchID = " . $branchID;
					}
					else
					{
						$cquery .= "WHERE DocumentNo = '" . $docno . "' AND ToBranchID = " . $branchID;
					}
					$cdbresult = $database->execute($cquery);
					$cnum_rows = $cdbresult->num_rows;
					
					if ($cnum_rows == 0)
					{
						$insertQry = substr($insertQry, 0, -2).")";
						$valuesQry = substr($valuesQry, 0, -2);
						$valuesQry .= ")";
					
						if ($debug)
						{
							echo "2. ". $insertQry."\r\n";
							echo "2.1. ". $valuesQry."\r\n";
						}
					
						$insertID = 0;
						$database->execute($insertQry." ".$valuesQry);
						$insertID = $database->insert_id();
					}
					else
					{
						//update
						$valuesUQry = substr($valuesUQry, 0, -2);
						if ($mtid == 2 || $mtid == 4)
						{
							$whereQry = " where DocumentNo = '$docno' AND BranchID = $branchID";
						}
						else
						{
							$whereQry = " where DocumentNo = '$docno' AND ToBranchID = $branchID";
						}
						
						if ($debug)
						{
							echo "2. ".$updateQry."\r\n";
							echo "2.1. ".$valuesUQry."\r\n";
							echo "2.1.1. ".$whereQry."\r\n";
						}
						$database->execute($updateQry." ".$valuesUQry." ".$whereQry);
					}
				}
				else
				{
					//check if transaction is already existing
					/*if ($tableName == "salesorder" || $tableName == "deliveryreceipt" || $tableName == "salesinvoice"  || $tableName == "dmcm")
					{
							$cquery .= "WHERE RefTxnID = " . $refID . " AND BranchID = " . $branchID;
					}
					else
					{
						$cquery .= "WHERE RefID = " . $refID . " AND BranchID = " . $branchID;
					}*/
					
					if ($tableName == "tpi_rcustomerbranch")
					{
						$cquery = "SELECT * FROM $tableName where CustomerID = $custid and BranchID = $branchID";
					}
					else
					{
						$cquery = "SELECT * FROM $tableName ";
						$cquery .= "WHERE RefID = " . $refID . " AND BranchID = " . $branchID;
					}
					
					$cdbresult = $database->execute($cquery);
					$cnum_rows = $cdbresult->num_rows;
					
					if ($cnum_rows == 0)
					{
						$insertQry = substr($insertQry, 0, -2).")";
						$valuesQry = substr($valuesQry, 0, -2);
						$valuesQry .= ")";
					
						if ($debug)
						{
							echo "2. ". $insertQry."\r\n";
							echo "2.1. ". $valuesQry."\r\n";
						}
					
						$insertID = 0;
						$database->execute($insertQry." ".$valuesQry);
						$insertID = $database->insert_id();
					}
					else
					{
						//update
						$valuesUQry = substr($valuesUQry, 0, -2);
						if ($tableName == "deliveryreceipt" || $tableName == "salesinvoice")
						{
							$whereQry = " where RefTxnID = $refID AND BranchID = $branchID";
						}
						elseif($tableName == "tpi_rcustomerbranch")
						{
							$whereQry = " where CustomerID = $custid and BranchID = $branchID";
						}
						else
						{
							$whereQry = " where RefID = $refID AND BranchID = $branchID";
						}
						
						if ($debug)
						{
							echo $updateQry."\r\n";
							echo $valuesUQry."\r\n";
							echo $whereQry."\r\n";
						}
						$database->execute($updateQry." ".$valuesUQry." ".$whereQry);
					}
				}
			}
			else
			{
				if ($tableName == "customer")
				{
					if ($code != '')
					{
						$query = "SELECT * FROM `".$tableName."` where Code = '".$code."'";
						$dbresult = $database->execute($query);
						$num_rows = $dbresult->num_rows;
						$row = $dbresult->fetch_object();
						$custid = $row->ID;
									
						if ($num_rows == 0)
						{
							$insertQry = substr($insertQry, 0, -2).")";
							$valuesQry = substr($valuesQry, 0, -2);
							$valuesQry .= ")";
							
							if ($debug)
							{
								echo $insertQry."\r\n";
								echo $valuesQry."\r\n";
							}
							
							$insertID = 0;
							$database->execute($insertQry." ".$valuesQry);
							$insertID = $database->insert_id();
							
							//check customer branch linking
							$cquery = "SELECT * FROM tpi_rcustomerbranch WHERE CustomerID = " . $insertID . " AND BranchID = " . $branchID;
							$cdbresult = $database->execute($cquery);
							$cnum_rows = $cdbresult->num_rows;
							
							if ($cnum_rows == 0)
							{
								$query = "insert into tpi_rcustomerbranch (CustomerID, BranchID, IsPrimary, CreatedBy, EnrollmentDate, Changed) values (". $insertID . ", ". $branchID . ", 0, 1, now(), 1)";
								$database->execute($query);
							}
						}
						else
						{
							//check customer branch linking
							$cquery = "SELECT * FROM tpi_rcustomerbranch WHERE CustomerID = " . $custid . " AND BranchID = " . $branchID;
							$cdbresult = $database->execute($cquery);
							$cnum_rows = $cdbresult->num_rows;
							
							if ($cnum_rows == 0)
							{
								$query = "insert into tpi_rcustomerbranch (CustomerID, BranchID, IsPrimary, CreatedBy, EnrollmentDate, Changed) values (". $insertID . ", ". $branchID . ", 0, 1, now(), 1)";
								$database->execute($query);
							}
							
							//update
							$valuesUQry = substr($valuesUQry, 0, -2);
							$whereQry = " where ID = $custid";
							
							if ($debug)
							{
								echo $updateQry."\r\n";
								echo $valuesUQry."\r\n";
								echo $whereQry."\r\n";
							}
							$database->execute($updateQry." ".$valuesUQry." ".$whereQry);
						}
					}
				}
				else
				{
					$insertQry = substr($insertQry, 0, -2).")";
					$valuesQry = substr($valuesQry, 0, -2);
					$valuesQry .= ")";
					
					if ($debug)
					{
						echo "3. ". $insertQry."\r\n";
						echo "3.1. ". $valuesQry."\r\n";
					}
					
					$insertID = 0;
					$database->execute($insertQry." ".$valuesQry);
					$insertID = $database->insert_id();
				}
			}
		}
	}
	else if ($type == 2)	//Insert details
	{
		$refColumn = $_REQUEST["refColumn"];
		$refTable = $_REQUEST["refTable"];
		
		$selectQry = "";
		foreach($xml -> children() as $tableName => $tablenode)
		{
			
			$insertQry = "Insert into ".$tableName."(";
			$valuesQry = "Values (";
			$values2Qry = "";
			
			$updateQry = "Update `".$tableName."` ";
			$valuesUQry = "Set ";
			$whereQry = "";
			
			$ID = "";
			$ctr = 0;
			
			$refID = "";
			$hoID = 0;
			
			$hcustid = 0;
			$custid = 0;
			$siid = 0;
			$cstatid = 0;
			$ibmid = 0;
			$pdaid = 0;
			$statid = 0;
			$lineid = 0;
			$newRefTxnID = 0;
			$enrolldate = "";
			$fromibm = 0;
			$toibm = 0;
			$iadid = 0;
			$iioid = 0;
			$icoid = 0;
			$itrid = 0;
			$prodid = 0;
			$iiodid = 0;
			$dmcmid = 0;
			$fieldid = 0;
			$ibmbranchid = 0;
			$year = "";
			$month = "";
			$custcode = "";

			foreach($tablenode -> children() as $name => $node)
			{
				
				if ($name == "ID")
				{
					$lineid = $node;
				}
				elseif ($name == "RefID")
				{
					$iiodid = $node;
					$valuesUQry .= "$name = $node, ";
				}
				elseif ($name == "Changed")
				{
					$insertQry .= "`".$name."`" . ", ";
					$values2Qry .= "0, ";
					$valuesUQry .= "$name = 0, ";
				}
				elseif ($name == "IBMID")
				{
					$query = "select ID from customer where ID  in (select CustomerID from tpi_rcustomerbranch where BranchID = $branchID) and RefID = $node";
					$dbresult = $database->execute($query);
					$row = $dbresult->fetch_object();
					if ($dbresult->num_rows != 0)
					{
						$ibmid = $row->ID;
					}
					else
					{
						$ibmid = "null";
					}
						
					$insertQry .= "`".$name."`" . ", ";
					$values2Qry .= "$ibmid, ";
					$valuesUQry .= "$name = $ibmid, ";
				}
				elseif ($name == "CustomerCode")
				{
					$query_c = "SELECT * FROM `customer` where Code = '".$node."'";
					$dbresult_c = $database->execute($query_c);
					$row_c = $dbresult_c->fetch_object();
					$custid = $row_c->ID;
					
					$insertQry .= "`CustomerID`" . ", ";
					$values2Qry .= "$custid, ";
					$valuesUQry .= "CustomerID = $custid, ";
				}
				elseif ($name == "FromIBMID")
				{
					$query = "select ID from customer where ID  in (select CustomerID from tpi_rcustomerbranch where BranchID = $branchID) and RefID = $node";					
					
					$dbresult = $database->execute($query);
					$row = $dbresult->fetch_object();
					if ($dbresult->num_rows != 0)
					{
						$fromibm = $row->ID;
					}
					else
					{
						$fromibm = "null";
					}
						
					$insertQry .= "`".$name."`" . ", ";
					$values2Qry .= "$fromibm, ";
					$valuesUQry .= "$name = $fromibm, ";
				}
				elseif ($name == "ToIBMID")
				{
					$query = "select ID from customer where ID  in (select CustomerID from tpi_rcustomerbranch where BranchID = $branchID) and RefID = $node";
					$dbresult = $database->execute($query);
					$row = $dbresult->fetch_object();
					if ($dbresult->num_rows != 0)
					{
						$toibm = $row->ID;
					}
					else
					{
						$toibm = "null";
					}
						
					$insertQry .= "`".$name."`" . ", ";
					$values2Qry .= "$toibm, ";
					$valuesUQry .= "$name = $toibm, ";
				}
				elseif ($name == "tpi_RecruiterID")
				{
					if (empty($node))
					{
						//$name = null
						$insertQry .= "`".$name."`" . ", ";
						$values2Qry .= "null, ";
						$valuesUQry .= "$name = null, ";
					}
					else
					{
						$query = "select ID from customer where ID  in (select CustomerID from tpi_rcustomerbranch where BranchID = $branchID) and RefID = $node";
						$dbresult = $database->execute($query);
						$row = $dbresult->fetch_object();
						if ($dbresult->num_rows != 0)
						{
							$recruiterid = $row->ID;
						}
						else
						{
							$recruiterid = "null";
						}
						
						$insertQry .= "`".$name."`" . ", ";
						$values2Qry .= "$recruiterid, ";
						$valuesUQry .= "$name = $recruiterid, ";
					}
				}
				elseif ($name == "RefTxnID")
				{
					if ($tableName == "officialreceiptdetails")
					{
						$query = "SELECT ID from salesinvoice where RefID = $node and BranchID = $branchID";
						$dbresult = $database->execute($query);
						$row = $dbresult->fetch_object();
						if ($dbresult->num_rows != 0)
						{
							$newRefTxnID = $row->ID;
						}
						else
						{
							$newRefTxnID = $node;
						}
					}
					else if ($tableName == "dmcmdetails")
					{
						$query = "SELECT ID from salesinvoice where RefID = $node and BranchID = $branchID";
						$dbresult = $database->execute($query);
						$row = $dbresult->fetch_object();
						if ($dbresult->num_rows != 0)
						{
							$newRefTxnID = $row->ID;
						}
						else
						{
							$newRefTxnID = $node;
						}
					}
					else
					{
						$newRefTxnID = $node;
					}
					
					$insertQry .= $name . ", ";
					$values2Qry .= "$newRefTxnID, ";
					$valuesUQry .= "$name = $newRefTxnID, ";
				}
				elseif ($name == "Remarks")
				{
					$insertQry .= $name . ", ";
					$values2Qry .= "'".$node. "', ";
					$whereQry.= "`".$name."`"." = '".$node."' and ";
					$valuesUQry .= "`".$name."`". "=" . "'".$node. "',  ";
				}
				elseif(empty($node))
				{
					$valuesUQry .= "$name = null, ";
				}
				else if ($name != $refColumn)
				{
					$insertQry .= "`".$name."`" . ", ";
					
					if($node=="")
					{
						
						if ($tables[$tableName][$ctr] == 0)
						{
							$values2Qry .= "0, ";
							$whereQry.= "`".$name."`"." = 0 and ";
							$valuesUQry.= "`".$name."`"." = 0,  ";
						}
						else
						{
							$values2Qry .= "'', ";
							$whereQry.= "`".$name."`"." = '' and ";
							$valuesUQry .= "`$name` = '', ";
						}
					}
					else
					{
						if ($tables[$tableName][$ctr] == 0)
						{
							$values2Qry .= "'".str_replace("'", " ", $node)."'". ", ";
							$whereQry.= "`".$name."`"." = "."'".str_replace("'", " ", $node)."'"." and ";
							$valuesUQry.= "`".$name."`"." = "."'".str_replace("'", " ", $node)."'".", ";
						}
						else
						{
							$values2Qry .= "'".str_replace("'", " ", $node)."', ";
							$whereQry.= "`".$name."`"." = '".str_replace("'", " ", $node)."' and ";
							$valuesUQry .= "`".$name."`"."="."'".str_replace("'", " ", $node)."', ";
						}
					}
				}
				else
				{
					$insertQry .= $refColumn . ", ";
					$refID = $node;
				}
				
				if ($name == "SalesInvoiceID")
				{
					$siid = $node;
				}
				
				if ($name == "CustomerID")
				{
					$custid = $node;
				}
				
				if ($name == "CustomerStatusID")
				{
					$cstatid = $node;
				}
				
				/*if ($name == "IBMID")
				{
					$ibmid = $node;
				}*/
				
				if ($name == "StatusID")
				{
					$statid = $node;
				}
				
				if ($name == "EnrollmentDate")
				{
					$enrolldate = $node;
				}
				
				if ($name == "ProductID")
				{
					$prodid = $node;
				}
				
				if ($name == "DMCMID")
				{
					$dmcmid = $node;
				}
				
				if ($name == "FieldID")
				{
					$fieldid = $node;
				}
				
				if ($name == "BranchID")
				{
					$ibmbranchid = $node;
				}
				
				if ($name == "Year")
				{
					$year = $node;
				}
				
				if ($name == "Month")
				{
					$month = $node;
				}
				
				if ($name == "CustomerCode")
				{
					$custcode = $node;
				}
				
				/*if ($name == "InventoryAdjustmentID")
				{
					$query = "SELECT * from inventoryadjustment where RefID = $node and BranchID = $branchID";
					$dbresult = $database->execute($query);
					$row = $dbresult->fetch_object();
					$iadid = $row->ID;
				}*/
				
				/*if ($name == "InventoryCountID")
				{
					$query = "SELECT * from inventorycount where RefID = $node and BranchID = $branchID";
					$dbresult = $database->execute($query);
					$row = $dbresult->fetch_object();
					$icoid = $row->ID;
				}
				
				if ($name == "InventoryTransferID")
				{
					$query = "SELECT * from inventorytransfer where RefID = $node and BranchID = $branchID";
					$dbresult = $database->execute($query);
					$row = $dbresult->fetch_object();
					$itrid = $row->ID;
				}*/
				
				$ctr++;
			}

			if ($refID != "")
			{
				$hoID = 0;

				if ($tableName == "customeraccountsreceivable" || $tableName == "customerpenalty")
				{
					$query_c = "SELECT * FROM `customer` where RefID = '".$custid."'";
					$dbresult_c = $database->execute($query_c);
					$row_c = $dbresult_c->fetch_object();
					$hcustid = $row_c->ID;
					$query = "SELECT ID FROM $tableName where CustomerID = $hcustid and SalesInvoiceID = $siid";
					$dbresult = $database->execute($query);
					$row = $dbresult->fetch_object();
					$hoID = $row_c->ID;
					$num_rows = $dbresult->num_rows;
				}
				elseif ($tableName == "tpi_rcustomerstatus")
				{
					$query_c = "SELECT * FROM `customer` where RefID = '".$custid."'";
					$dbresult_c = $database->execute($query_c);
					$row_c = $dbresult_c->fetch_object();
					$hcustid = $row_c->ID;
					$query = "SELECT ID FROM $tableName where CustomerID = $hcustid and CustomerStatusID = $cstatid";
					$dbresult = $database->execute($query);
					$row = $dbresult->fetch_object();
					$hoID = $row_c->ID;
					$num_rows = $dbresult->num_rows;
				}
				elseif ($tableName == "tpi_rcustomeribm")
				{
					$query_c = "SELECT * FROM `customer` where RefID = '".$custid."'";
					$dbresult_c = $database->execute($query_c);
					$row_c = $dbresult_c->fetch_object();
					$hcustid = $row_c->ID;
					$query = "SELECT ID FROM $tableName where CustomerID = $hcustid and IBMID = $ibmid";
					$dbresult = $database->execute($query);
					$row = $dbresult->fetch_object();
					$hoID = $row_c->ID;
					$num_rows = $dbresult->num_rows;
				}
				elseif ($tableName == "tpi_rcustomerpda")
				{
					$query_c = "SELECT * FROM `customer` where RefID = '".$custid."'";
					$dbresult_c = $database->execute($query_c);
					$row_c = $dbresult_c->fetch_object();
					$hcustid = $row_c->ID;
					$query = "SELECT ID FROM $tableName where CustomerID = $hcustid and tpi_PDA_ID = $pdaid";
					$dbresult = $database->execute($query);
					$row = $dbresult->fetch_object();
					$hoID = $row_c->ID;
					$num_rows = $dbresult->num_rows;
				}
				elseif ($tableName == "tpi_credit")
				{
					$query_c = "SELECT * FROM `customer` where RefID = '".$custid."'";
					$dbresult_c = $database->execute($query_c);
					$row_c = $dbresult_c->fetch_object();
					$hcustid = $row_c->ID;
					$query = "SELECT ID FROM $tableName where CustomerID = $hcustid";
					$dbresult = $database->execute($query);
					$row = $dbresult->fetch_object();
					$hoID = $row_c->ID;
					$num_rows = $dbresult->num_rows;
				}
				elseif ($tableName == "tpi_creditlimitdetails")
				{
					$query_c = "SELECT * FROM `customer` where RefID = '".$custid."'";
					$dbresult_c = $database->execute($query_c);
					$row_c = $dbresult_c->fetch_object();
					$hcustid = $row_c->ID;
					$query = "SELECT ID FROM $tableName where CustomerID = $hcustid and StatusID = $statid";
					$dbresult = $database->execute($query);
					$row = $dbresult->fetch_object();
					$hoID = $row_c->ID;
					$num_rows = $dbresult->num_rows;
				}
				elseif ($tableName == "tpi_dealerwriteoff" || $tableName == "tpi_dealerpromotion" || $tableName == "tpi_dealertransfer")
				{
					$query_c = "SELECT * FROM `customer` where RefID = '".$custid."'";
					$dbresult_c = $database->execute($query_c);
					$row_c = $dbresult_c->fetch_object();
					$hcustid = $row_c->ID;
					$query = "SELECT ID FROM $tableName where CustomerID = $hcustid and EnrollmentDate = '$enrolldate'";
					$dbresult = $database->execute($query);
					$row = $dbresult->fetch_object();
					$hoID = $row_c->ID;
					$num_rows = $dbresult->num_rows;
				}
				elseif ($tableName == "tpi_customerdetails")
				{
					$query_c = "SELECT * FROM `customer` where RefID = '".$custid."'";
					$dbresult_c = $database->execute($query_c);
					$row_c = $dbresult_c->fetch_object();
					$hcustid = $row_c->ID;
					$hoID = $row_c->ID;
					$query = "SELECT CustomerID FROM $tableName where CustomerID = $hcustid";
					$dbresult = $database->execute($query);
					$row = $dbresult->fetch_object();
					$num_rows = $dbresult->num_rows;
				}
				elseif ($tableName == "customerdetails")
				{
					$query_c = "SELECT * FROM `customer` where RefID = '".$custid."'";
					$dbresult_c = $database->execute($query_c);
					$row_c = $dbresult_c->fetch_object();
					$hcustid = $row_c->ID;
					$hoID = $row_c->ID;
					$query = "SELECT CustomerID FROM $tableName where CustomerID = $hcustid and FieldID = $fieldid";
					$dbresult = $database->execute($query);
					$row = $dbresult->fetch_object();
					$num_rows = $dbresult->num_rows;
				}
				elseif ($tableName == "tpi_rcustomerbranch")
				{
					$query_c = "SELECT * FROM `customer` where Code = '".$custcode."'";
					$dbresult_c = $database->execute($query_c);
					$row_c = $dbresult_c->fetch_object();
					$hcustid = $row_c->ID;
					$hoID = $row_c->ID;
					
					if ($hoID != "" || $hoID != 0)
					{
						$query = "SELECT CustomerID FROM $tableName where CustomerID = $hcustid and BranchID = $ibmbranchid";
						$dbresult = $database->execute($query);
						$row = $dbresult->fetch_object();
						$num_rows = $dbresult->num_rows;
					}
					else
					{
						$num_rows = 0;
					}
				}
				elseif ($tableName == "tpi_branchcollectionrating" || $tableName == "tpi_customerstats")
				{
					$query_c = "SELECT * FROM `customer` where RefID = '".$custid."'";
					$dbresult_c = $database->execute($query_c);
					$row_c = $dbresult_c->fetch_object();
					$hcustid = $row_c->ID;
					$hoID = $row_c->ID;
					if ($hcustid != "")
					{
						$query = "SELECT CustomerID FROM $tableName where CustomerID = $hcustid and `Year` = $year and `Month` = $month";
						$dbresult = $database->execute($query);
						$row = $dbresult->fetch_object();
						$num_rows = $dbresult->num_rows;
					}
					else
					{
						$num_rows = 0;
					}
				}
				/*elseif ($tableName == "inventoryadjustmentdetails")
				{
					$query = "SELECT ID FROM $tableName where InventoryAdjustmentID = $iadid and ProductID = $prodid";
					$dbresult = $database->execute($query);
					$row = $dbresult->fetch_object();
					$hoID = $row->ID;
					$num_rows = $dbresult->num_rows;
				}*/
				elseif ($tableName == "inventoryinoutdetails")
				{
					$query = "SELECT ID FROM $tableName where ID = $iiodid and ProductID = $prodid";
					$dbresult = $database->execute($query);
					$row = $dbresult->fetch_object();
					$hoID = $row->ID;
					$num_rows = $dbresult->num_rows;
				}
				/*elseif ($tableName == "inventorycountdetails")
				{
					$query = "SELECT ID FROM $tableName where InventoryCountID = $icoid and ProductID = $prodid";
					$dbresult = $database->execute($query);
					$row = $dbresult->fetch_object();
					$hoID = $row->ID;
					$num_rows = $dbresult->num_rows;
				}
				elseif ($tableName == "inventorytransferdetails")
				{
					$query = "SELECT ID FROM $tableName where InventoryTransferID = $itrid and ProductID = $prodid";
					$dbresult = $database->execute($query);
					$row = $dbresult->fetch_object();
					$hoID = $row->ID;
					$num_rows = $dbresult->num_rows;
				}*/
				elseif ($tableName == "dmcmdetails")
				{
					$query_c = "SELECT * FROM `dmcm` where RefID = ".$dmcmid." and BranchID = ".$branchID;
					$dbresult_c = $database->execute($query_c);
					$row_c = $dbresult_c->fetch_object();
					$hoID = $row_c->ID;
					$query = "SELECT ID FROM $tableName where DMCMID = $hoID and RefTxnID = $newRefTxnID";
					$dbresult = $database->execute($query);
					$row = $dbresult->fetch_object();
					$num_rows = $dbresult->num_rows;
				}
				else
				{
					$query = "SELECT ID FROM $refTable where RefID = $refID";
					$dbresult = $database->execute($query);
					$row = $dbresult->fetch_object();
					$hoID = $row->ID;
					$num_rows = $dbresult->num_rows;
				}

				if ($hoID > 0)
				{
					//if ($tableName != "inventoryadjustmentdetails" && $tableName != "inventoryinoutdetails" && $tableName != "inventorycountdetails" && $tableName != "inventorytransferdetails")
					if ($tableName == "salesorderdetails" || $tableName == "deliveryreceiptdetails" || $tableName == "salesinvoicedetails" || $tableName == "officialreceiptcash" || $tableName == "officialreceiptcheck" || $tableName == "officialreceiptcommission" || $tableName == "officialreceiptdeposit" || $tableName == "officialreceiptdetails" || $tableName == "inventoryadjustmentdetails" || $tableName == "inventorycountdetails" || $tableName == "inventorytransferdetails")
					{
						$checkQry = "select * from $tableName where ".$refColumn." = $hoID and ".substr($whereQry,0,-5)."\r\n";
						$dbresult = $database->execute($checkQry);
						$num_rows = $dbresult->num_rows;
					}
					else if ($tableName != "inventoryinoutdetails" && $tableName != "dmcmdetails" && $tableName != "customerdetails" && $tableName != "tpi_branchcollectionrating" && $tableName != "tpi_customerstats")
					{
						$checkQry = "select * from $tableName where ".$refColumn." = $hoID";
						//$checkQry = "select * from $tableName where ".$refColumn." = $hoID and ".substr($whereQry,0,-5)."\r\n";
						$dbresult = $database->execute($checkQry);
						$num_rows = $dbresult->num_rows;
					}
				}
				
				if (($num_rows == 0 && $hoID > 0) || $hoID == 0 || $hoID == "")
				{
					$insertQry = substr($insertQry, 0, -2).")";
					$values2Qry = substr($values2Qry, 0, -2);
					$values2Qry .= ")";
					
					if ($debug)
					{
						echo  "*** ".$insertQry."\r\n";
						echo  "*** ".$valuesQry." $hoID, ".$values2Qry."\r\n";
					}
					
					if ($tableName == "tpi_rcustomerbranch")
					{
						$database->execute($insertQry." ".$valuesQry.$values2Qry);
					}
					else
					{
						$database->execute($insertQry." ".$valuesQry." $hoID, ".$values2Qry);
					}
				}
				else
				{
					$valuesUQry = substr($valuesUQry, 0, -2);
					if ($tableName == "customeraccountsreceivable" || $tableName == "customerpenalty")
					{
						$whereQry = " where $refColumn = $hoID and SalesInvoiceID = $siid";
					}
					else if ($tableName == "tpi_rcustomerstatus")
					{
						$whereQry = " where $refColumn = $hoID and CustomerStatusID = $cstatid";
					}
					else if ($tableName == "tpi_rcustomeribm")
					{
						$whereQry = " where $refColumn = $hoID and IBMID = $ibmid";
					}
					else if ($tableName == "tpi_rcustomerpda")
					{
						$whereQry = " where $refColumn = $hoID and tpi_PDA_ID = $pdaid";
					}
					else if ($tableName == "tpi_dealerwriteoff" || $tableName == "tpi_creditlimitdetails" || $tableName == "tpi_dealerpromotion" || $tableName == "tpi_dealertransfer")
					{
						$whereQry = " where $refColumn = $hoID and EnrollmentDate = '$enrolldate'";
					}
					else if ($tableName == "tpi_credit" || $tableName == "tpi_customerdetails")
					{
						$whereQry = " where $refColumn = $hoID";
					}
					else if ($tableName == "customerdetails")
					{
						$whereQry = " where $refColumn = $hoID and FieldID = $fieldid";
					}
					else if ($tableName == "tpi_branchcollectionrating" || $tableName == "tpi_customerstats")
					{
						$whereQry = " where $refColumn = $hoID and `Year` = $year and `Month` = $month";
					}
					//else if ($tableName == "inventoryadjustmentdetails" || $tableName == "inventoryinoutdetails" || $tableName == "inventorycountdetails" || $tableName == "inventorytransferdetails")
					else if ($tableName == "inventoryinoutdetails")
					{
						$whereQry = " where ID = $hoID";
					}
					else
					{
						$whereQry = " where ID = $lineid";
					}
						
					if ($debug)
					{
						echo  "@@@ ".$updateQry."\r\n";
						echo  "@@@ ".$valuesUQry."\r\n";
						echo  "@@@ ".$whereQry."\r\n";
					}
					$database->execute($updateQry." ".$valuesUQry." ".$whereQry);
				}
			}
			else
			{
				$insertQry = substr($insertQry, 0, -2).")";
				$values2Qry = substr($values2Qry, 0, -2);
				$values2Qry .= ")";
					
				if ($debug)
				{
					echo  "*** ".$insertQry."\r\n";
					echo  "*** ".$valuesQry." $hoID, ".$values2Qry."\r\n";
				}
				
				if ($tableName == "tpi_rcustomerbranch")
				{
					$database->execute($insertQry." ".$valuesQry.$values2Qry);
				}
				else
				{
					$database->execute($insertQry." ".$valuesQry." $hoID, ".$values2Qry);
				}
			}
		}
	}
}
$database->close();

?>