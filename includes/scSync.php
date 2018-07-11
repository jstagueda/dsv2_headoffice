<?php
	require_once "../initialize.php";
	global $database;
	$debug = true;
		
	function myErrorHandler($errno, $errstr, $errfile, $errline)
	{
		if (!(error_reporting() & $errno)) 
		{
			// This error code is not included in error_reporting
			return;
		}
		
		 switch ($errno) 
		 {
			case 1:               	print "Error: ".$errstr."<br />\n";         	break;
			case 2:       															break;
			case 4:               	print "Parse Error: ".$errstr."<br />\n";     	break;
			case 8:              	print "Notice: ".$errstr."<br />\n";          	break;
			case 16:          		print "Core Error: ".$errstr."<br />\n";      	break;
			case 32:        		print "Core Warning: ".$errstr."<br />\n";    	break;
			case 64:       			print "Compile Error: ".$errstr."<br />\n";   	break;
			case 128:     			print "Compile Warning: ".$errstr."<br />\n"; 	break;
			case 256:          		print "User Error: ".$errstr."<br />\n";      	break;
			case 512:        														break;
			case 1024:         		print "User Notice: ".$errstr."<br />\n";     	break;
			default:				print "Unknown Error: ".$errstr."<br />\n";		break;
		}

		/* Don't execute PHP internal error handler */
		return true;
	}

	
	error_reporting(E_ALL); 
	set_error_handler("myErrorHandler");
	
	/**
	 * Used to print debugging messages. To turn off debugging, set the $_debug variable to false;
	 * @param $mesg
	 */
	function debugecho($mesg)
	{
		global $debug;
		if ($debug == true)
		{
			echo $mesg;
		}
	}

	
	//if(isset($_POST['btnSync']))
	//{
		// ----------- Webservice running in dbsync.php ----------------
		require_once("classUtils.php");
		require_once("classWebservice.php");
		
		//$SERVER_URL = "http://192.168.0.1/tpi_presentation/dbsync.php";
		$SERVER_URL = HO_SYNC;
		$Service = new Webservice($SERVER_URL, "POST", "utf-8");
		
		$Service->PRINT_DEBUG = false;
		
		//$table_name = "product";
		$query = "SELECT LastSyncDate, BranchID FROM branchparameter";
		$dbresult = $database->execute($query);
		if($dbresult->num_rows)
        {
	      	while($row = $dbresult->fetch_object())
 	      	{										 						 			
	 	       $lastSyncTime = $row->LastSyncDate;	
	 	       $branchID = $row->BranchID;							   
	      	}
        }
		
		
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
		
		unset($Params);
		//--------------------------------->>>>>>>>>>>>>Upload<<<<<<<<<<<<<<------------------------
		$Params["syncTypeID"] = 4;
		$Params["method"] = "startsync";
		$Params["branchID"] = $branchID;
		
		flush();
		try
		{
			$Response = $Service->SendRequest($Params);

		}
		catch (Exception $Ex)
		{
			echo "Unable to establish connection to host. Please check your connection settings.."."<br>";
		}
		
		$dom = $Response["Body"];
		$xml = simplexml_import_dom($dom);	
	
		foreach($xml -> children() as $tableName => $tablenode)
		{
			$syncID = $tablenode;
		}
		try 
		{
			$database->beginTransaction();
			
			//$tableList = array("customer", "inventoryadjustment", "inventorycount", "inventoryinout", "inventorytransfer", "inventory", "salesorder", "deliveryreceipt", "salesinvoice", "officialreceipt", "dmcm");
			$tableList_query_array = array("SELECT * FROM `customer` where Changed = 1",
										   "SELECT * FROM `inventoryadjustment` where Changed = 1 and StatusID = 7 and MovementTypeID != 10 UNION ALL SELECT * FROM `inventoryadjustment` where Changed = 1 and StatusID in (7, 23) and MovementTypeID = 10",
										   "SELECT * FROM `salesorder` where Changed = 1 and StatusID in (7, 9)",
										   "SELECT * FROM `inventory` where Changed = 1 and SOH = 0",
										   "SELECT * FROM `salesinvoice` where Changed = 1 and StatusID in (7, 8)",
										   "SELECT * FROM `inventorycount` where Changed = 1 and StatusID = 7",
										   "SELECT * FROM `inventoryinout` where Changed = 1 and StatusID = 7",
										   "SELECT * FROM `inventorytransfer` where Changed = 1 and StatusID = 7",
										   "SELECT * FROM `deliveryreceipt` where Changed = 1 and StatusID = 7",
										   "SELECT * FROM `officialreceipt` where Changed = 1 and StatusID = 7",
										   "SELECT * FROM `dmcm` where Changed = 1 and StatusID = 7");
			foreach ($tableList_query_array as $query){
				//Query
				$queryUpload = $query;
				
				//$dbresult = mysql_query($queryUpload, $dbconnect);
				$dbresult = $database->execute($queryUpload);
				
				// create a new XML document
				$doc = new DomDocument('1.0');
				$doc->formatOutput = true;
	
				// create root node
				$root = $doc->createElement('root');
				$root = $doc->appendChild($root);
	
				// process one row at a time
				// while($row = mysql_fetch_assoc($dbresult))
				while($row = $dbresult->fetch_assoc()){
					// add node for each row
					$occ = $doc->createElement($tblName);
					$occ = $root->appendChild($occ);
					
					// add a child node for each field
					foreach ($row as $fieldname => $fieldvalue){
						$child = $doc->createElement($fieldname);
						$child = $occ->appendChild($child);
						$value = $doc->createTextNode($fieldvalue);
						$value = $child->appendChild($value);
					}
				}
				
				$Params["branchID"] = $branchID;
				$Params["table"] = $tblName;
				$Params["uploadData"] = gzcompress($doc->saveXML(), 9);
				//$Params["uploadData"] = $doc->saveXML();
				$Params["method"] = "upload";
				$Params["type"] = 1;
				$Params["debug"] = false; 
				flush();
				try
				{
					$Response = $Service->SendRequest($Params);
				}
				catch (Exception $Ex) 
				{
					echo "Unable to establish connection to host. Please check your connection settings.."."<br>";
				}
			}
			
			//UploadDetails			

			
			$tableDetailList = array("customerdetails", "tpi_customerdetails", "customeraccountsreceivable", "customerpenalty", "tpi_rcustomeribm", "tpi_rcustomerstatus", "tpi_rcustomerbranch", "tpi_rcustomerpda", "tpi_credit", "tpi_creditlimitdetails", "tpi_dealerwriteoff", "tpi_dealerpromotion", "tpi_dealertransfer", "tpi_branchcollectionrating", "tpi_customerstats", "salesorderdetails", "deliveryreceiptdetails", "salesinvoicedetails", "officialreceiptcash", "officialreceiptcheck", "officialreceiptcommission", "officialreceiptdeposit", "officialreceiptdetails", "dmcmdetails", "inventoryadjustmentdetails", "inventorycountdetails", "inventoryinoutdetails", "inventorytransferdetails");
			$reftableList = array("customer", "customer", "customer", "customer", "customer", "customer", "customer", "customer", "customer", "customer", "customer", "customer", "customer", "customer", "customer", "salesorder", "deliveryreceipt", "salesinvoice", "officialreceipt", "officialreceipt", "officialreceipt", "officialreceipt", "officialreceipt", "dmcm", "inventoryadjustment", "inventorycount", "inventoryinout", "inventorytransfer");
			$refIDList = array("CustomerID", "CustomerID", "CustomerID", "CustomerID", "CustomerID", "CustomerID", "CustomerID", "CustomerID", "CustomerID", "CustomerID", "CustomerID", "CustomerID", "CustomerID", "CustomerID", "CustomerID", "SalesOrderID", "DeliveryReceiptID", "SalesInvoiceID", "OfficialReceiptID", "OfficialReceiptID", "OfficialReceiptID", "OfficialReceiptID", "OfficialReceiptID", "DMCMID", "InventoryAdjustmentID", "InventoryCountID", "InventoryInOutID", "InventoryTransferID");
			$tableDetailList_query_array = array(
													"SELECT * FROM customerdetails where Changed = 1",
													"SELECT * FROM tpi_customerdetails where Changed = 1",
													"SELECT * FROM customeraccountsreceivable where Changed = 1",											
													"SELECT * FROM customerpenalty where Changed = 1",													
													"SELECT * FROM tpi_rcustomeribm where Changed = 1",													
													"SELECT * FROM tpi_rcustomerstatus where Changed = 1",													
													"SELECT cu.Code CustomerCode, tcb.BranchID, tcb.IsPrimary, tcb.Remarks, tcb.CreatedBy, tcb.EnrollmentDate, tcb.Changed FROM tpi_rcustomerbranch tcb inner join customer cu on cu.ID = tcb.CustomerID where tcb.Changed = 1",
													"SELECT * FROM tpi_rcustomerpda where Changed = 1",
													"SELECT * FROM tpi_credit where Changed = 1",													
													"SELECT * FROM tpi_creditlimitdetails where Changed = 1 and StatusID = 23",										
													"SELECT * FROM tpi_dealerwriteoff where Changed = 1 and StatusID = 23",											
													"SELECT * FROM tpi_dealerpromotion where Changed = 1",								
													"SELECT * FROM tpi_dealertransfer where Changed = 1	",											
													"SELECT * FROM tpi_branchcollectionrating",
													"SELECT * FROM tpi_customerstats",										
													"SELECT * FROM salesorderdetails where SalesOrderID in (SELECT ID FROM salesorder where Changed = 1 and StatusID in (7, 9))",
													"SELECT * FROM deliveryreceiptdetails where DeliveryReceiptID in (SELECT ID FROM deliveryreceipt where Changed = 1 and StatusID = 7)",													
													"SELECT * FROM salesinvoicedetails where SalesInvoiceID in (SELECT ID FROM salesinvoice where Changed = 1 and StatusID in (7, 8))",													
													"SELECT * FROM officialreceiptcash where OfficialReceiptID in (SELECT ID FROM officialreceipt where Changed = 1 and StatusID = 7)",													
													"SELECT * FROM officialreceiptcheck where OfficialReceiptID in (SELECT ID FROM officialreceipt where Changed = 1 and StatusID = 7)",													
													"SELECT * FROM officialreceiptcommission where OfficialReceiptID in (SELECT ID FROM officialreceipt where Changed = 1 and StatusID = 7)",													
													"SELECT * FROM officialreceiptdeposit where OfficialReceiptID in (SELECT ID FROM officialreceipt where Changed = 1 and StatusID = 7)",													
													"SELECT * FROM officialreceiptdetails where OfficialReceiptID in (SELECT ID FROM officialreceipt where Changed = 1 and StatusID = 7)",													
													"SELECT * FROM dmcmdetails where DMCMID in (SELECT ID FROM dmcm where Changed = 1 and StatusID = 7)",													
													"SELECT * FROM inventoryadjustmentdetails where InventoryAdjustmentID in (Select ID from inventoryadjustment where Changed = 1 and StatusID = 7 and MovementTypeID != 10 UNION ALL Select ID from inventoryadjustment where Changed = 1 and StatusID = 23 and MovementTypeID = 10)",													
													"SELECT * FROM inventorycountdetails where InventoryCountID in (SELECT ID FROM inventorycount where Changed = 1 and StatusID = 7)",													
													"SELECT * FROM inventoryinoutdetails where InventoryInOutID in (SELECT ID FROM inventoryinout where Changed = 1 and StatusID = 7)",												
													"SELECT * FROM inventorytransferdetails where InventoryTransferID in (SELECT ID FROM inventorytransfer where Changed = 1 and StatusID = 7)"												
												);
			$ctr = 0;
			foreach ($tableDetailList_query_array as $query){					
				//Query
				$queryUpload = $query;
				
				debugecho($queryUpload);
				$dbresult = $database->execute($queryUpload);
				
				// create a new XML document
				$doc = new DomDocument('1.0');
				$doc->formatOutput = true;
	
				// create root node
				$root = $doc->createElement('root');
				$root = $doc->appendChild($root);
	
				// process one row at a time
				while($row = $dbresult->fetch_assoc()){
					// add node for each row
					$occ = $doc->createElement($tblName);
					$occ = $root->appendChild($occ);
					
					// add a child node for each field
					foreach ($row as $fieldname => $fieldvalue){
						$child = $doc->createElement($fieldname);
						$child = $occ->appendChild($child);
						$value = $doc->createTextNode($fieldvalue);
						$value = $child->appendChild($value);
					}
				}

				$Params["branchID"] = $branchID;
				$Params["table"] = $tblName;
				$Params["uploadData"] = gzcompress($doc->saveXML(), 9);
				//$Params["uploadData"] = $doc->saveXML();
				$Params["method"] = "upload";
				$Params["type"] = 2;
				$Params["refTable"] = $reftableList[$ctr];
				$Params["refColumn"] = $refIDList[$ctr];
				$Params["debug"] = true;//<--------------------- set this to true when debugging
				flush();
				try
				{
					$Response = $Service->SendRequest($Params);
								
				}
				catch (Exception $Ex) 
				{
					echo "Unable to establish connection to host. Please check your connection settings.."."<br>";
					
				}
				$ctr++;
			}
			/** update Changed column **/

			/*
			UPDATE `tpi_branchcollectionrating` set Changed = 0 where Changed = 1 and CustomerID in (SELECT ID FROM customer where Changed = 1 and StatusID = 7)
			UPDATE `tpi_customerstats` set Changed = 0 where Changed = 1 and CustomerID in (SELECT ID FROM customer where Changed = 1 and StatusID = 7)
			
			$tableDetailList = array("customerdetails", "tpi_customerdetails", "customeraccountsreceivable", "customerpenalty", "tpi_rcustomeribm", "tpi_rcustomerstatus", "tpi_rcustomerbranch", "tpi_rcustomerpda", "tpi_credit", "tpi_creditlimitdetails", "tpi_dealerwriteoff", "tpi_dealerpromotion", "tpi_dealertransfer", "tpi_branchcollectionrating", "tpi_customerstats", "salesorderdetails", "deliveryreceiptdetails", "salesinvoicedetails", "officialreceiptcash", "officialreceiptcheck", "officialreceiptcommission", "officialreceiptdeposit", "officialreceiptdetails", "dmcmdetails", "inventoryadjustmentdetails", "inventorycountdetails", "inventoryinoutdetails", "inventorytransferdetails");
			$reftableList = array("customer", "customer", "customer", "customer", "customer", "customer", "customer", "customer", "customer", "customer", "customer", "customer", "customer", "customer", "customer", "salesorder", "deliveryreceipt", "salesinvoice", "officialreceipt", "officialreceipt", "officialreceipt", "officialreceipt", "officialreceipt", "dmcm", "inventoryadjustment", "inventorycount", "inventoryinout", "inventorytransfer");
			$refIDList = array("CustomerID", "CustomerID", "CustomerID", "CustomerID", "CustomerID", "CustomerID", "CustomerID", "CustomerID", "CustomerID", "CustomerID", "CustomerID", "CustomerID", "CustomerID", "CustomerID", "CustomerID", "SalesOrderID", "DeliveryReceiptID", "SalesInvoiceID", "OfficialReceiptID", "OfficialReceiptID", "OfficialReceiptID", "OfficialReceiptID", "OfficialReceiptID", "DMCMID", "InventoryAdjustmentID", "InventoryCountID", "InventoryInOutID", "InventoryTransferID");	
			if ($tblName != "tpi_branchcollectionrating" && $tblName != "tpi_customerstats"){
					$dbresult = $database->execute($queryUpdate);
			}
			*/
			$ctr = 0;
			$tableDetailList_query_array_update = array(
														"UPDATE `customerdetails` set Changed = 0 where Changed = 1",
														"UPDATE `tpi_customerdetails` set Changed = 0 where Changed = 1",
														"UPDATE `customeraccountsreceivable` set Changed = 0 where Changed = 1",
														"UPDATE `customerpenalty` set Changed = 0 where Changed = 1",
														"UPDATE `tpi_rcustomeribm` set Changed = 0 where Changed = 1",
														"UPDATE `tpi_rcustomerstatus` set Changed = 0 where Changed = 1",
														"UPDATE `tpi_rcustomerbranch` set Changed = 0 where Changed = 1 and CustomerID in (SELECT ID FROM customer where Changed = 1 and StatusID = 7)",
														"UPDATE `tpi_rcustomerpda` set Changed = 0 where Changed = 1",
														"UPDATE `tpi_credit` set Changed = 0 where Changed = 1",
														"UPDATE `tpi_creditlimitdetails` set Changed = 0 where Changed = 1 and StatusID = 23",
														"UPDATE `tpi_dealerwriteoff` set Changed = 0 where Changed = 1 and StatusID = 23",
														"UPDATE `tpi_dealerpromotion` set Changed = 0 where Changed = 1",
														"UPDATE `tpi_dealertransfer` set Changed = 0 where Changed = 1",
														"UPDATE `salesorderdetails` set Changed = 0 where Changed = 1",
														"UPDATE `deliveryreceiptdetails` set Changed = 0 where Changed = 1",
														"UPDATE `salesinvoicedetails` set Changed = 0 where Changed = 1",
														"UPDATE `officialreceiptcash` set Changed = 0 where Changed = 1",
														"UPDATE `officialreceiptcheck` set Changed = 0 where Changed = 1",
														"UPDATE `officialreceiptcommission` set Changed = 0 where Changed = 1",
														"UPDATE `officialreceiptdeposit` set Changed = 0 where Changed = 1",
														"UPDATE `officialreceiptdetails` set Changed = 0 where Changed = 1",
														"UPDATE `dmcmdetails` set Changed = 0 where Changed = 1",
														"UPDATE `inventoryadjustmentdetails` set Changed = 0 where Changed = 1",
														"UPDATE `inventorycountdetails` set Changed = 0 where Changed = 1",
														"UPDATE `inventoryinoutdetails` set Changed = 0 where Changed = 1",
														"UPDATE `inventorytransferdetails` set Changed = 0 where Changed = 1"
														);
			foreach ($tableDetailList_query_array_update as $query){
					$queryUpdate = $query;
					$dbresult = $database->execute($queryUpdate);
			}
			//$tableList = array("customer", "inventoryadjustment", "inventorycount", "inventoryinout", "inventorytransfer", "inventory", "salesorder", "deliveryreceipt", "salesinvoice", "officialreceipt", "dmcm");
			$tablelList_query_array_update = array(  "UPDATE `customer` set Changed = 0 where Changed = 1",
													 "UPDATE `inventoryadjustment` set Changed = 0 where Changed = 1",
													 "UPDATE `inventorycount` set Changed = 0 where Changed = 1 and StatusID = 7",
													 "UPDATE `inventoryinout` set Changed = 0 where Changed = 1 and StatusID = 7",
													 "UPDATE `inventorytransfer` set Changed = 0 where Changed = 1 and StatusID = 7",
													 "UPDATE `inventory` set Changed = 0 where Changed = 1",
													 "UPDATE `salesorder` set Changed = 0 where Changed = 1 and StatusID in (7, 9)",
													 "UPDATE `deliveryreceipt` set Changed = 0 where Changed = 1 and StatusID = 7",
													 "UPDATE `salesinvoice` set Changed = 0 where Changed = 1 and StatusID in (7, 8)",
													 "UPDATE `officialreceipt` set Changed = 0 where Changed = 1 and StatusID = 7",
													 "UPDATE `dmcm` set Changed = 0 where Changed = 1 and StatusID = 7"
								);
			foreach ($tablelList_query_array_update as $query){
					$queryUpdate = $query;
					$dbresult = $database->execute($queryUpdate);
			}
			$database->commitTransaction();
		}
		catch (Exception $e)
		{
			echo $e;
			debugecho($e->getTraceAsString());
			$database->rollbackTransaction();
			die ("<font color='#8B0000'><b>An error occured while synchronizing data to the server. If the problem persists, please contact your System Administrator1.<b></font>");
		}
		
		$Params["method"] = "endsync";
		$Params["syncID"] = $syncID;
		
		flush();
		try
		{
			$Response = $Service->SendRequest($Params);
		}
		catch (Exception $Ex) 
		{
			echo "Unable to establish connection to host. Please check your connection settings.."."<br>";
		}
		//tables for download ordered by download sequence
        $tableList = array( "area", "department", "branch", "branchdetails", "branchdepartment", "holiday", "campaign", "campaignmonth",
							"pagetype", "position", "tpi_netfactor", "value",
							"product","productdetails", "productpricing", "productcost", "productregkit", "productsubstitute", "productkit",
							"promo", "promoavailment", "promobuyin", "promoentitlement", "promoentitlementdetails", "promodeleteddetails",
							"brochure", "brochuredetails", "brochureproduct", "brochurecampaign",
							"user", "usertype", "module","modulecontrol", "submodule", "rusertypemodulecontrol",
							"employee", "remployeebranch", "remployeeposition",
							"customer", "tpi_customerdetails", "customerdetails", "customeraccountsreceivable", "customerpenalty", "customercommission",
							"tpi_dealerwriteoff", "tpi_creditlimitdetails", "tpi_credit", "tpi_rcustomerbranch", "bank"
							);
							
		unset($Params);					
		//Start Sync
		$Params["syncTypeID"]	=  2;
		$Params["method"]		=  "startsync";
		$Params["branchID"]		=  $branchID;
		
		flush();
		try
		{
			$Response = $Service->SendRequest($Params);
		}
		catch (Exception $Ex) 
		{
			echo "Unable to establish connection to host. Please check your connection settings.."."<br>";
		}
		
		$dom = $Response["Body"];
		$xml = simplexml_import_dom($dom);	

		foreach($xml -> children() as $tableName => $tablenode){
			$syncID = $tablenode;
		}
		
		try
		{
			$database->beginTransaction();
			//truncate table
			$truncate_array = array("truncate table productsubstitute", 
									"truncate table productkit", 
									"truncate table rusertypemodulecontrol");
			foreach ($truncate_array as $truncate){				
				
					try
					{
						$database->beginTransaction();
						$query = $truncate;
						$dbresult = $database->execute($query);
						$database->commitTransaction();
					}
					catch (Exception $e)
					{
						debugecho($e->getTraceAsString());
						$database->rollbackTransaction();
					}
				
			}

			//Insert new from HeadOffice				
			foreach ($tableList as $tblName){
				unset($Params);
				//Webservice parameters
				$Params["table"] = $tblName;
				$Params["lastSyncTime"] = $lastSyncTime; 
				$Params["type"] = 1;
				$Params["method"] = "download";
				$Params["branchID"] = $branchID;
				
				flush();
				try
				{
					$Response = $Service->SendRequest($Params);
				}
				catch (Exception $Ex) 
				{
					echo "Unable to establish connection to host. Please check your connection settings.."."<br>";
				}
				
				$dom = $Response["Body"];
				$xml = simplexml_import_dom($dom); //or die ("Unable to load XML file!");
				
				$selectQry = "";
				$insertedIDs = "";
				$ID = "";
				$processed = 0;
				$utid = 0;
				$mcid = 0;
				$bankid = 0;
				$promoid = 0;
				$branchid = 0;
				$departmentid = 0;
				$type = 0;
				$customerid = 0;
				$enrolldate = "";
				$kitid = 0;
				$compid = 0;
				$prodid = 0;
				$subsid = 0;
				$startdate = "";
				$enddate = "";
				$custcode = "";
				$fieldid = 0;
				$salesinvoiceid = 0;
				$num_rows_tpi = 1;
				$campmonthid = 0;
				
				if ($xml){
					foreach($xml -> children() as $tableName => $tablenode){
						$insertQry = "Insert into `".$tableName."`(";
						$valuesQry = "Values( ";
							
						$selectQry = "";
						$ctr = 0;
										
						foreach($tablenode -> children() as $name => $node){
							if (($name == "ID" && $tableName == "customer") || ($name == "CustomerCode" && $tableName == "customerdetails") || ($name == "CustomerCode" && $tableName == "tpi_customerdetails") || ($name == "CustomerCode" && $tableName == "customeraccountsreceivable") || ($name == "CustomerCode" && $tableName == "customerpenalty") || ($name == "CustomerCode" && $tableName == "customercommission") || ($name == "CustomerCode" && $tableName == "tpi_rcustomerbranch")){
								$insertQry = $insertQry;
							}else{
								$insertQry .= $name . ", ";
							}
							
							if (($name == "CustomerCode" && $tableName == "customerdetails") || ($name == "CustomerCode" && $tableName == "tpi_customerdetails") || ($name == "CustomerCode" && $tableName == "customeraccountsreceivable") || ($name == "CustomerCode" && $tableName == "customerpenalty") || ($name == "CustomerCode" && $tableName == "customercommission")){
								$cquery 	= "SELECT * FROM `customer` where Code = '$node'";
								$cdbresult  = $database->execute($cquery);
								$cnum_rows  = $dbresult->num_rows;
								$crow 		= $cdbresult->fetch_object();

								if ($cnum_rows != 0){				
									$insertQry .= "CustomerID, ";
									$valuesQry .= $crow->ID.", ";
								}else{
									$insertQry .= "CustomerID, ";
									$valuesQry .= "null, ";
								}
							}elseif ($name == "CustomerCode" && $tableName == "tpi_rcustomerbranch"){
								$cquery = "SELECT * FROM `customer` where Code = '$node'";
								$cdbresult = $database->execute($cquery);
								$cnum_rows = $dbresult->num_rows;
								$crow = $cdbresult->fetch_object();
								
								if ($cnum_rows != 0){
									$insertQry .= "CustomerID, ";
									$valuesQry .= $crow->ID.", ";
									$customerid = $crow->ID;
								}else{
									$insertQry .= "CustomerID, ";
									$valuesQry .= "0, ";
									$customerid = 0;
								}
							}
							else if ($name == "ID" && $tableName == "customer"){
								$valuesQry = $valuesQry;	
							}
							else if ($name == "Changed"){
								$valuesQry .= "0, ";
							}
							else if ($name == "StartDate"){
								if($tableName == "productsubstitute"){
									$tmpdate = date("Y-m-d", strtotime($node));
									$valuesQry .= "'".str_replace("'","\'",$tmpdate) . "', ";
								}else{
									$valuesQry .= "'".str_replace("'","\'",$node) . "', ";
								}
							}
							elseif ($name == "EndDate"){
								if ($tableName == "productsubstitute"){
									$tmpdate = date("Y-m-d", strtotime($node));
									$valuesQry .= "'".str_replace("'","\'",$tmpdate) . "', ";
								}else{
									$valuesQry .= "'".str_replace("'","\'",$node) . "', ";
								}
							}
							else if($node==""){
								if ($tables[$tableName][$ctr] == 0)
									$valuesQry .= "null, ";
								else
									$valuesQry .= "'', ";
							}
							elseif(empty($node)){
								$valuesQry .= "null, ";
							}
							else{
								if ($tables[$tableName][$ctr] == 0)
									$valuesQry .= "'".$node."'". ", ";
								else
									$valuesQry .= "'".str_replace("'","\'",$node) . "', ";
							}
							if ($name == "UserTypeID"){
								$utid = $node;
							}
							
							if ($name == "ModuleControlID"){
								$mcid = $node;
							}
							
							if ($name == "PromoID"){
								$promoid = $node;
							}
							
							if ($name == "BranchID"){
								$branchid = $node;
							}
							
							if ($name == "DepartmentID"){
								$departmentid = $node;
							}
							if ($name == "Type"){
								$type = $node;
							}
							if ($name == "RefID"){
								$customerid = $node;
							}
							if ($name == "CustomerID" && $tableName == "tpi_dealerwriteoff"){
								$customerid = $node;
							}
							if ($name == "EnrollmentDate"){
								$enrolldate = $node;
							}
							if ($name == "KitID"){
								$kitid = $node;
							}
							if ($name == "ComponentID"){
								$compid = $node;
							}
							if ($name == "ProductID"){
								$prodid = $node;
							}
							if ($name == "SubstituteID"){
								$subsid = $node;
							}
							if ($name == "StartDate"){
								$startdate = $node;
							}
							if ($name == "EndDate"){
								$enddate = $node;
							}
							if ($name == "Code"){
								$custcode = $node;
							}
							if ($name == "CustomerCode"){
								$custcode = $node;
							}
							if ($name == "FieldID"){
								$fieldid = $node;
							}
							if ($name == "SalesInvoiceID"){
								$salesinvoiceid = $node;
							}
							if ($name == "CampaignMonthID"){
								$campmonthid = $node;
							}
							if ($ctr==0)
								$ID = $node;
								
							$ctr++;
						}
						
						if ($ID != ""){
							if ($tableName == "rusertypemodulecontrol"){
								$query = "SELECT * FROM $tblName where UserTypeID = $utid and ModuleControlID = $mcid";
							}
							else if ($tableName == "rpromobranch"){
								$query = "SELECT * FROM $tblName where PromoID = $promoid and BranchID = $branchid";
							}
							else if ($tableName == "tpi_dealerwriteoff" || $tableName == "tpi_creditlimitdetails"){
								$query = "SELECT * FROM $tblName where CustomerID = $customerid and EnrollmentDate = '$enrolldate'";
							}
							else if ($tableName == "tpi_credit"){
								$query = "SELECT * FROM $tblName where CustomerID = $customerid";
							}
							else if ($tableName == "productkit"){
								$query = "SELECT * FROM $tblName where KitID = $kitid AND ComponentID = $compid";
							}
							else if ($tableName == "productsubstitute"){
								$query = "SELECT * FROM $tblName where ProductID = $prodid AND SubstituteID = $subsid AND StartDate = '$startdate' AND EndDate = '$enddate'";
							}
							else if ($tableName == "branchdepartment"){
								$query = "SELECT * FROM $tblName where BranchID = $branchid AND DepartmentID = $departmentid AND Type = $type";
							}
							else if ($tableName == "customer"){
								$query = "SELECT * FROM $tblName where Code = '$custcode'";
							}
							else if ($tableName == "customerdetails" || $tableName == "tpi_customerdetails" || $tableName == "customeraccountsreceivable" || $tableName == "customerpenalty" || $tableName == "customercommission"){
								$cquery = "SELECT * FROM `customer` where Code = '$custcode'";
								$cdbresult = $database->execute($cquery);
								$cnum_rows = $dbresult->num_rows;
								$crow = $cdbresult->fetch_object();
								//echo $cnum_rows;
								if ($cnum_rows != 0){
									if($crow->ID){
										if ($tableName == "customerdetails"){
											$query = "SELECT * FROM $tableName where CustomerID =".$crow->ID." and FieldID = $fieldid";
										}
										else if ($tableName == "customeraccountsreceivable" || $tableName == "customerpenalty"){
											$query = "SELECT * FROM $tableName where CustomerID =".$crow->ID." and SalesInvoiceID = $salesinvoiceid";
										}
										else if ($tableName == "tpi_customerdetails"){
											$query = "SELECT * FROM $tableName where CustomerID =".$crow->ID;
										}
										else if ($tableName == "customercommission"){
											$query = "SELECT * FROM $tableName where CustomerID =".$crow->ID." and CampaignMonthID = ".$campmonthid;
										}
									}
								}else{
									$num_rows_tpi = 0;
								}
							}
							else if ($tableName == "tpi_rcustomerbranch"){
								$query = "SELECT * FROM $tblName where CustomerID = $customerid AND BranchID = $branchid";
							}
							else{
								$query = "SELECT * FROM $tblName where ID = $ID";
							}
							
							//echo "*********".$query."<br />";
							if ($num_rows_tpi == 0){
								$num_rows = 0;
							}
							else{
								$dbresult = $database->execute($query);
								$num_rows = $dbresult->num_rows;
							}

							if ($num_rows == 0 && $num_rows_tpi == 1){
								$insertQry = substr($insertQry, 0, -2).")";
								$valuesQry = substr($valuesQry, 0, -2).")";
								$selectQry .= $valuesQry;
								
								if ($debug){
									echo "insert1:".$insertQry."\r\n";
									echo "insert2:".$selectQry."\r\n";
								}
								
								try 
								{
									$database->execute($insertQry." ".$selectQry);
								}
								catch (Exception $e)
								{
									//echo ($e . "<br/>");
								}
								
								$processed += 1;
								$insertedIDs .= "$ID, ";
									
								echo "<script type='text/javascript'>";
								echo "document.getElementById('POSMsg').innerHTML = 'Downloading ".$tblName."...'";
								echo "</script>";
							}
						}
					}
				}
				
				$insertedIDs = substr($insertedIDs, 0, -2);
				$sp->spInsertInventoryNewProduct($database);
				
				if ($processed > 0){
					$Params["table"] 		= $tblName;
					$Params["method"] 		= "syncdetails";
					$Params["syncID"] 		= $syncID;
					$Params["processed"] 	= $processed;
					$Params["IDs"] 			= $insertedIDs;
					$Params["branchID"] 	= $branchID;  
					
					flush();
					try
					{
						$Response = $Service->SendRequest($Params);
					}
					catch (Exception $Ex) 
					{
						echo "Unable to establish connection to host. Please check your connection settings.."."<br>";
					}
				}
				
				$database->commitTransaction();
			}
		}
		catch (Exception $e)
		{
			echo $e;
			debugecho($e->getTraceAsString());
			$database->rollbackTransaction();
			die ("<font color='#8B0000'><b>An error occured while synchronizing data to the server. If the problem persists, please contact your System Administrator2.<b></font>");
		}
		
		$tableList = array("inventoryinout", "inventoryinoutdetails", "inventoryadjustment", "inventoryadjustmentdetails");
		//$tableList = array();
		//transactional
		try 
		{
			$database->beginTransaction();
			$inventoryinoutIDs = "";
			$inventoryinoutDetailIDs = "";
			$inventoryCount = 0;
			$inventoryDetailsCount = 0;
			
			$inventoryadjIDs = "";
			$inventoryadjDetailIDs = "";
			$inventoryadjCount = 0;
			$inventoryadjDetailsCount = 0;
			
			foreach ($tableList as $tblName){
				//Webservice parameters
				$Params["table"]		 	= $tblName;
				$Params["lastSyncTime"]	 	= $lastSyncTime; 
				$Params["type"] 			= 1;
				$Params["method"] 			= "download";
				$Params["branchID"] 		= $branchID;
				
				flush();
				try
				{
					$Response = $Service->SendRequest($Params);
				}
				catch (Exception $Ex) 
				{
					echo "Unable to establish connection to host. Please check your connection settings.."."<br>";
				}
				
				$dom = $Response["Body"];
				$xml = simplexml_import_dom($dom); //or die ("Unable to load XML file!");
				
				$selectQry 		= "";
				$insertedIDs 	= "";
				$ID 			= "";
				
				if ($xml){
					$processed = 0;
					foreach($xml -> children() as $tableName => $tablenode){
						$insertQry 	= "Insert into `".$tableName."`(";
						$valuesQry 	= "Values( ";
						
						$updateQry 	= "Update `".$tableName."` ";
						$valuesUQry = "Set ";
						$whereQry 	= "";
							
						$selectQry 	= "";
						$ctr 	  	= 0;
						$reftxnid 	= 0;
						$prodid   	= 0;
						$iadid 	  	= 0;
						$iioid    	= 0;
										
						foreach($tablenode -> children() as $name => $node){
							if ($name == "InventoryInOutID"){
								$insertQry .= 	$name . ", ";
								$query 		= 	"select ID from inventoryinout where RefID = $node";
								$rsID 		= 	$database->execute($query);
								$row 		= 	$rsID->fetch_object();
								$valuesQry .= 	$row->ID.", ";
								$valuesUQry .= 	"$name = $row->ID, ";
							}
							else if ($name == "InventoryAdjustmentID"){
								$insertQry .= 	$name . ", ";
								$query 		= 	"select ID from inventoryadjustment where ID = $node";
								$rsID 		= 	$database->execute($query);
								$row 		= 	$rsID->fetch_object();
								$valuesQry .= 	$row->ID.", ";
								$valuesUQry .= 	"$name = $row->ID, ";
							}
							else if ($name != "ID"){
								$insertQry .= $name . ", ";
								
								if ($name == "Changed"){
									$valuesQry .= 	"0, ";
									$valuesUQry .= 	"$name = 0, ";
								}
								elseif ($name == "RefID"){
							        $valuesQry .= $ID.", ";
									//$valuesUQry .= "$name = $ID, ";
									$reftxnid = $node;
							    }
								elseif ($name == "PrevBalance"){
									if ($node == ""){
										$valuesQry .= "null, ";
									}
									else{
										$valuesQry .= $node.", ";
									}
								}
								elseif ($name == "StatusID"){
									if ($tableName == "inventoryadjustment" || $tableName == "inventoryinout"){
										$valuesQry .= "6, ";
										$valuesUQry .= "$name = 6, ";
									}
									else{
										$valuesQry .= $node.", ";
										$valuesUQry .= "$name = $node, ";
									}
							    }
								elseif($node == ""){
									if ($tables[$tableName][$ctr] == 0){
										$valuesQry .= "null, ";
									}
									else{
										$valuesQry .= "'', ";
										$valuesUQry .= "$name = null, ";
									}
								}
								elseif(empty($node)){
									$valuesQry .= "null, ";
									$valuesUQry .= "$name = null, ";
								}
								else{
									if ($tables[$tableName][$ctr] == 0){
										$valuesQry .= "'".$node."'".", ";
									}else{
										$valuesQry .= "'".str_replace("'","\'",$node) . "', ";
										$valuesUQry .= "$name = "."'".str_replace("'","\'",$node) . "', ";
									}
								}
							}
							
							if ($name == "RefID"){
								$reftxnid = $node;
							}
							if ($name == "ProductID"){
								$prodid = $node;
							}
							if ($name == "InventoryAdjustmentID"){
								$iadid = $node;
							}
							if ($name == "InventoryInOutID"){
								$iioid = $node;
							}
							
							if ($ctr==0)
								$ID = $node;
								
							$ctr++;
						}
						
						if ($ID != ""){
							if ($tblName == "inventoryadjustmentdetails"){
								$query = "SELECT * FROM $tblName WHERE InventoryAdjustmentID = $iadid AND ProductID = $prodid";
							}
							else if ($tblName == "inventoryinoutdetails"){
								$query = "SELECT * FROM $tblName WHERE RefID = $ID";
							}
							else if ($tblName == "inventoryinout"){
								$query = "SELECT * FROM $tblName WHERE RefID = $ID";
							}
							else{
								$query = "SELECT * FROM $tblName WHERE ID = $reftxnid";
							}
							
							$dbresult = $database->execute($query);
							$num_rows = $dbresult->num_rows;
							
							if ($debug){
								echo $query."\r\n";
							}
							
							if ($num_rows == 0){
								//insert
								$insertQry = substr($insertQry, 0, -2).")";
								$valuesQry = substr($valuesQry, 0, -2).")";
								$selectQry .= $valuesQry;
								
								if ($debug){
									echo $insertQry."\r\n";
									echo $valuesQry."\r\n";
									echo $selectQry."\r\n";
								}
								
								$database->execute($insertQry." ".$selectQry);
								$processed += 1;
								$insertedIDs .= "$ID, ";
							}
							else{
								//update
								$valuesUQry = substr($valuesUQry, 0, -2);
								if ($tblName == "inventoryadjustment"){
									$whereQry = " where ID = $reftxnid";
									
								}else if ($tblName == "inventoryinout"){
									$whereQry = " where RefID = $ID";
									
								}else if ($tblName == "inventoryadjustmentdetails"){
									$whereQry = " where InventoryAdjustmentID = $iadid AND ProductID = $prodid";
									
								}else if ($tblName == "inventoryinoutdetails"){
									$whereQry = " where RefID = $ID";
									
								}else{
									$whereQry = " where ID = $ID";
								
								}
								
								if ($debug){
									echo $updateQry."\r\n";
									echo $valuesUQry."\r\n";
									echo $whereQry."\r\n";
								}
								//gino
								$database->execute($updateQry." ".$valuesUQry." ".$whereQry);
							}
						}
					}
				}
				
				$insertedIDs = substr($insertedIDs, 0, -2);	
				if ($tblName == "inventoryinout"){
					$inventoryCount = $processed;
					$inventoryinoutIDs = $insertedIDs;
				}
				else if ($tblName == "inventoryinoutdetails"){
					$inventoryDetailsCount = $processed;
					$inventoryinoutDetailIDs = $insertedIDs;
				}
				else if ($tblName == "inventoryadjustment"){
					$inventoryadjCount = $processed;
					$inventoryadjIDs = $insertedIDs;
				}
				else if ($tblName == "inventoryadjustmentdetails"){
					$inventoryadjDetailsCount = $processed;
					$inventoryadjDetailIDs = $insertedIDs;
				}
			}
		
			if ($inventoryCount > 0){
				$Params["table"]	 = "inventoryinout";
				$Params["method"]	 = "syncdetails";
				$Params["syncID"] 	 = $syncID;
				$Params["processed"] = $inventoryCount;
				$Params["IDs"]		 = $inventoryinoutIDs;
				$Params["branchID"]  = $branchID;
				
				flush();
				try
				{
					$Response = $Service->SendRequest($Params);
				}
				catch (Exception $Ex) 
				{
					echo "Unable to establish connection to host. Please check your connection settings.."."<br>";
				}
			}
			
			if ($inventoryadjCount > 0){
				$Params["table"]  	 = "inventoryadjustment";
				$Params["method"] 	 = "syncdetails";
				$Params["syncID"] 	 = $syncID;
				$Params["processed"] = $inventoryadjCount;
				$Params["IDs"] 		 = $inventoryadjIDs;
				$Params["branchID"]  = $branchID;
				
				flush();
				try
				{
					$Response = $Service->SendRequest($Params);
				}
				catch (Exception $Ex) 
				{
					echo "Unable to establish connection to host. Please check your connection settings.."."<br>";
				}
			}
			
			if ($inventoryDetailsCount > 0){
				$Params["table"]     =  "inventoryinoutdetails";
				$Params["method"]    =  "syncdetails";
				$Params["syncID"]    =  $syncID;
				$Params["processed"] =  $inventoryDetailsCount;
				$Params["IDs"]       =  $inventoryinoutDetailIDs;
				$Params["branchID"]  =  $branchID;
				
				flush();
				try
				{
					$Response = $Service->SendRequest($Params);
				}
				catch (Exception $Ex) 
				{
					echo "Unable to establish connection to host. Please check your connection settings.."."<br>";
				}
			}
			
			if ($inventoryadjDetailsCount > 0){
				$Params["table"]  	 = "inventoryadjustmentdetails";
				$Params["method"] 	 = "syncdetails";
				$Params["syncID"] 	 = $syncID;
				$Params["processed"] = $inventoryadjDetailsCount;
				$Params["IDs"] 		 = $inventoryadjDetailIDs;
				$Params["branchID"]  = $branchID;
				
				flush();
				try
				{
					$Response = $Service->SendRequest($Params);
				}
				catch (Exception $Ex) 
				{
					echo "Unable to establish connection to host. Please check your connection settings.."."<br>";
				}
			}
		
			$database->commitTransaction();
		}
		catch (Exception $e)
		{
			debugecho($e->getTraceAsString());
			$database->rollbackTransaction();
			die ("<font color='#8B0000'>
				   <b>
				    An error occured while synchronizing data to the server. If the problem persists, please contact your System Administrator3.
				   <b>
				 </font>");
		}
		
		//Update Routine
		//$tableList = array();
		
		$tableList = array ( 
							"area", "department", "branch", "branchdetails", "branchdepartment", 
							"holiday", "campaign", "campaignmonth","pagetype", "position", "tpi_netfactor", 
							"value","product","productdetails", "productpricing", "productcost", "productregkit", 
							"productsubstitute", "productkit","promo", "promoavailment", "promobuyin", "promoentitlement", 
							"promoentitlementdetails", "promodeleteddetails","brochure", "brochuredetails", 
							"brochureproduct", "brochurecampaign","user", "usertype", "module","modulecontrol", "submodule", 
							"rusertypemodulecontrol","employee", "remployeebranch", "remployeeposition",
							"customer", "tpi_customerdetails", "customerdetails", "customeraccountsreceivable", 
							"customerpenalty", "customercommission","tpi_dealerwriteoff", "tpi_creditlimitdetails", "tpi_credit", "bank"
							);
							
		try
		{
			
			$database->beginTransaction();
			
			$FOREIGN_KEY_CHECKS = "SET FOREIGN_KEY_CHECKS = 0";
			$database->execute($FOREIGN_KEY_CHECKS);
			
			foreach ($tableList as $tblName){
				//Webservice parameters
				$Params["table"] 		 = $tblName;
				$Params["lastSyncTime"]  = $lastSyncTime; 
				$Params["type"]			 = 2;
				$Params["method"]		 = "download";
				flush();
				try
				{
					$Response = $Service->SendRequest($Params);
				}
				catch (Exception $Ex) 
				{
					echo "Unable to establish connection to host. Please check your connection settings.."."<br>";
				}
				$dom = $Response["Body"];
				$xml = simplexml_import_dom($dom); //or die ("Unable to load XML file!");
				
				$selectQry = "";
				$insertQry = "";
				$updatedIDs = "";
				
				foreach($xml -> children() as $tableName => $tablenode)
				{
					$updateQry = "Update `".$tableName."` ";
					$valuesQry = "Set ";
					
					$ctr 	  	        = 0;
					$utid 	  	        = 0;
					$mcid 	  	        = 0;
					$custid   	        = 0;
					$ibmid 	  	        = 0;
					$branchid 	        = 0;
					$departmentid       = 0;
					$type 		        = 0;
					$enrolldate         = "";
					$fieldid 	        = 0;
					$salesinvoiceid     = 0;
					$campmonthid 	    = 0;
					$promoid 		    = 0;
					$promobuyinid 	    = 0;
					$promoentitlementid = 0;
					
					foreach($tablenode -> children() as $name => $node){
						$insertQry .= $name . ", ";
						
						if (($name == "ID" && $tableName == "customer")){
							$valuesQry = $valuesQry;
						}
						else if (($name == "CustomerCode" && $tableName == "customerdetails") || ($name == "CustomerCode" && $tableName == "tpi_customerdetails") || ($name == "CustomerCode" && $tableName == "customeraccountsreceivable") || ($name == "CustomerCode" && $tableName == "customerpenalty") || ($name == "CustomerCode" && $tableName == "customercommission")){
							$cquery = "SELECT * FROM `customer` where Code = '$node'";
							$cdbresult = $database->execute($cquery);
							$cnum_rows = $dbresult->num_rows;
							$crow = $cdbresult->fetch_object();
							if ($cnum_rows != 0){
								$custid = $crow->ID;
								$valuesQry .= "CustomerID = ".$crow->ID.", ";
							}
						}
						else if ($ctr == 0){
							$ID = $node;
						}
						else if ($name == "Changed"){
							$valuesQry .= "$name = 0, ";
						}
						else if ($name == "StartDate")
						{
							if ($tableName == "productsubstitute"){
								$tmpdate = date("Y-m-d", strtotime($node));
								$valuesQry .= "`"."$name"."`"."= '".str_replace("'","\'",$tmpdate) .  "', ";
							}else{
								$valuesQry .= "`"."$name"."`"."= '".str_replace("'","\'",$node) .  "', ";
							}
						}
						else if ($name == "EndDate"){
							if ($tableName == "productsubstitute"){
								$tmpdate = date("Y-m-d", strtotime($node));
								$valuesQry .= "`"."$name"."`"."= '".str_replace("'","\'",$tmpdate) .  "', ";
							}else{
								$valuesQry .= "`"."$name"."`"."= '".str_replace("'","\'",$node) .  "', ";
							}
						}
						else{
							if(empty($node) || $node==""){
								if ($tables[$tableName][$ctr] == 0){
									$valuesQry .= "$name = null, ";
								}else{
									$valuesQry .= "$name = '', ";
								}
							}
							else{
								if ($tables[$tableName][$ctr] == 0){
									$valuesQry .= "`"."$name"."`"." = $node, ";
								}
								else{
									$valuesQry .= "`"."$name"."`"."= '".str_replace("'","\'",$node) .  "', ";
								}
							}
						}
						
						if ($name == "UserTypeID"){
							$utid = $node;
						}
						if ($name == "ModuleControlID"){
							$mcid = $node;
						}
						if ($name == "CustomerID"){
							$custid = $node;
						}
						if ($name == "EnrollmentDate"){
							$enrolldate = $node;
						}
						if ($name == "BranchID"){
							$branchid = $node;
						}
						if ($name == "DepartmentID"){
							$departmentid = $node;
						}
						if ($name == "Type"){
							$type = $node;
						}
						if ($name == "FieldID"){
							$fieldid = $node;
						}
						if ($name == "SalesInvoiceID"){
							$salesinvoiceid = $node;
						}
						if ($name == "CampaignMonthID"){
							$campmonthid = $node;
						}
						if ($name == "PromoID"){
							$promoid = $node;
						}
						if ($name == "PromoBuyinID"){
							$promobuyinid = $node;
						}
						if ($name == "PromoEntitlementID"){
							$promoentitlementid = $node;
						}
						
						$ctr++;
					}
					 
					if ($ID != ""){
						if ($tableName == "rusertypemodulecontrol"){
							$query = "SELECT * FROM $tableName where UserTypeID = $utid and ModuleControlID = $mcid";
							$valuesQry = substr($valuesQry, 0, -2)." where UserTypeID= $utid and ModuleControlID = $mcid";
						}
						else if ($tableName == "tpi_dealerwriteoff" || $tableName == "tpi_creditlimitdetails"){
							$query = "SELECT * FROM $tableName where CustomerID = $custid and EnrollmentDate = '$enrolldate'";
							$valuesQry = substr($valuesQry, 0, -2)." where CustomerID = $custid and EnrollmentDate = '$enrolldate'";
						}
						else if ($tableName == "tpi_credit"){
							$query = "SELECT * FROM $tableName where CustomerID = $custid";
							$valuesQry = substr($valuesQry, 0, -2)." where CustomerID = $custid";
						}
						else if ($tableName == "branchdepartment"){
							$query = "SELECT * FROM $tableName where BranchID = $branchid AND DepartmentID = $departmentid AND Type = $type";
							$valuesQry = substr($valuesQry, 0, -2)." where BranchID = $branchid AND DepartmentID = $departmentid AND Type = $type";
						}
						else if ($tableName == "customerdetails"){
							$query = "SELECT * FROM $tableName where CustomerID = $custid and FieldID = $fieldid";
							$valuesQry = substr($valuesQry, 0, -2)." where CustomerID = $custid and FieldID = $fieldid";
						}
						else if ($tableName == "tpi_customerdetails"){
							$query = "SELECT * FROM $tableName where CustomerID = $custid";
							$valuesQry = substr($valuesQry, 0, -2)." where CustomerID = $custid";
						}
						else if ($tableName == "customeraccountsreceivable" || $tableName == "customerpenalty"){
							$query = "SELECT * FROM $tableName where CustomerID = $custid and SalesInvoiceID = $salesinvoiceid";
							$valuesQry = substr($valuesQry, 0, -2)." where CustomerID = $custid and SalesInvoiceID = $salesinvoiceid";
						}
						else if ($tableName == "customercommission"){
							$query = "SELECT * FROM $tableName where CustomerID = $custid and CampaignMonthID = $campmonthid";
							$valuesQry = substr($valuesQry, 0, -2)." where CustomerID = $custid and CampaignMonthID = $campmonthid";
						}
						else if ($tableName == "promobuyin"){
							$query = "SELECT * FROM $tableName where ID = $ID and $promoid in (select ID from promo)";
							$valuesQry = substr($valuesQry, 0, -2)." where ID = $ID";
						}
						else if ($tableName == "promoentitlement"){
							$query = "SELECT * FROM $tableName where ID = $ID and $promobuyinid in (select ID from promobuyin)";
							$valuesQry = substr($valuesQry, 0, -2)." where ID = $ID";
						}
						else if ($tableName == "promoentitlementdetails"){
							$query = "SELECT * FROM $tableName where ID = $ID and $promoentitlementid in (select ID from promoentitlement)";
							$valuesQry = substr($valuesQry, 0, -2)." where ID = $ID";
						}else{
							$query = "SELECT * FROM $tableName where ID = $ID";
							$valuesQry = substr($valuesQry, 0, -2)." where ID = $ID";
						}
						
						//echo "Query :".$query."<br />";
						$dbresult = $database->execute($query);
						$num_rows = $dbresult->num_rows;
							
						if ($num_rows){
							$processed += 1;
							$updatedIDs .= "$ID, ";
							if ($debug){
								echo "update1:".$updateQry."\r\n";
								echo "update2:".$valuesQry."\r\n";
							}
							$database->execute($updateQry.$valuesQry);
							$database->commitTransaction();
						}
					}
				}
					
				if ($processed > 0){
					$updatedIDs           = substr($updatedIDs, 0, -2);
					unset($Params);
					$Params["table"]  	  = $tblName;
					$Params["method"] 	  = "syncdetails";
					$Params["syncID"] 	  = $syncID;
					$Params["processed"]  = $processed;
					$Params["IDs"]        = $updatedIDs;
					$Params["branchID"]   = $branchID;
					
					flush();
					try
					{
						$Response = $Service->SendRequest($Params);
					}
					catch (Exception $Ex) 
					{
						echo "Unable to establish connection to host. Please check your connection settings.."."<br>";
					}
				}
			}
		}
		catch (Exception $e)
		{
			debugecho($e->getTraceAsString());
			$database->rollbackTransaction();
			echo $e;
			die (" <font color='#8B0000'>
					  <b>An error occured while synchronizing data to the server. If the problem persists, please contact your System Administrator4.<b>
				   </font> ");
		}
		
		$Params["method"] = "endsync";
		$Params["syncID"] = $syncID;
		
		flush();
		try
		{
			$Response = $Service->SendRequest($Params);
		}
		catch (Exception $Ex) 
		{
			echo "Unable to establish connection to host. Please check your connection settings.."."<br>";
		}

		try
		{
			$database->beginTransaction();
			$query = "UPDATE branchparameter set LastSyncDate = Now();";
			$dbresult = $database->execute($query);
			$sp->spInsertInventoryNewProduct($database);
			$sp->spDeletePromoDetailsBranch($database);
			$database->commitTransaction();
		}
		catch (Exception $e)
		{
			debugecho($e->getTraceAsString());
			$database->rollbackTransaction();
		
			die (" <font color='#8B0000'>
				        <b>An error occured while synchronizing data to the server. If the problem persists, please contact your System Administrator5.</b>
				   </font> ");
		}
		
		echo  "
			  	<font color='#00008B'>
			  		<b>Head Office Sync Successful</b>
			  	</font>
			  ";
		
$database->close();
?>