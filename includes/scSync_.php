<?php
	require_once "../initialize.php";
	global $database;
	$debug = false;
	
	function myErrorHandler($errno, $errstr, $errfile, $errline)
	{
		//echo "ERROR in ".$errfile."<br>";
		//echo "error: ".$errstr."<br>"."Line No: ".$errline;
		//exit();
	}
	
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

	ini_set('display_errors', 0);
	ini_set("memory_limit", "256M");
	//if(isset($_POST['btnSync']))
	//{
		// ----------- Webservice running in dbsync.php ----------------
		require_once("classUtils.php");
		require_once("classWebservice.php");
		
		//$SERVER_URL = "http://192.168.0.1/tpi_presentation/dbsync.php";
		$SERVER_URL = HO_SYNC;
		$Service = new Webservice($SERVER_URL, "POST", "utf-8");
		
		$Service->PRINT_DEBUG = true;
		
		//$table_name = "product";
		$query = "SELECT LastSyncDate, BranchID FROM branchparameter";
		$dbresult = $database->execute($query);
		//$row = mysql_fetch_row($dbresult);
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
		
		//$sp->spInsertInventoryNewProduct($database);
		
		unset($Params);
		//--------------------------------->>>>>>>>>>>>>Upload<<<<<<<<<<<<<<------------------------
		$Params["syncTypeID"] = 4;
		$Params["method"] = "startsync";
		$Params["branchID"] = $branchID;
		
		flush();
		$Response = $Service->SendRequest($Params);
		
		$dom = $Response["Body"];
		$xml = simplexml_import_dom($dom);	

		foreach($xml -> children() as $tableName => $tablenode)
		{
			$syncID = $tablenode;
		}
		
		try 
		{
			$database->beginTransaction();
			$tableList = array("officialreceipt", "salesinvoice", "customer", "inventoryadjustment");
			
			foreach ($tableList as $tblName)
			{					
				$queryUpload = "SELECT * FROM `$tblName` where Changed = 1";
				//debugecho($queryUpload."<br>");
				
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
				while($row = $dbresult->fetch_assoc())
				{
					// add node for each row
					$occ = $doc->createElement($tblName);
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
				
				debugecho($doc->SaveXML());
				
				$Params["branchID"] = $branchID;
				$Params["table"] = $tblName;
				$Params["uploadData"] = $doc->SaveXML();
				$Params["method"] = "upload";
				$Params["type"] = 1;
				$Params["debug"] = true; 
				//var_dump($Params);
				//echo "<br>";
				flush();
				$Response = $Service->SendRequest($Params);
				
				//debugecho($Response["ResponseBody"]);
			}

			//UploadDetails
			$tableDetailList = array("salesinvoicedetails", "officialreceiptcash", "officialreceiptcheck", "officialreceiptdeposit", "officialreceiptdetails", "inventoryadjustmentdetails");
			$reftableList = array("salesinvoice", "officialreceipt", "officialreceipt", "officialreceipt", "officialreceipt", "inventoryadjustment");
			$refIDList = array("SalesInvoiceID", "OfficialReceiptID", "OfficialReceiptID", "OfficialReceiptID", "OfficialReceiptID", "InventoryAdjustmentID");
			
			$ctr = 0;
			foreach ($tableDetailList as $tblName)
			{					
				$queryUpload = "SELECT * FROM $tblName where $refIDList[$ctr] in (Select ID from $reftableList[$ctr] where Changed = 1)";
				//debugecho("<br>".$queryUpload."<br>");
				
				// $dbresult = mysql_query($queryUpload, $dbconnect);
				$dbresult = $database->execute($queryUpload);
				
				// create a new XML document
				$doc = new DomDocument('1.0');
				$doc->formatOutput = true;
	
				// create root node
				$root = $doc->createElement('root');
				$root = $doc->appendChild($root);
	
				// process one row at a time
				// while($row = mysql_fetch_assoc($dbresult))
				while($row = $dbresult->fetch_assoc())
				{
					// add node for each row
					$occ = $doc->createElement($tblName);
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
				
				$Params["branchID"] = $branchID;
				$Params["table"] = $tblName;
				$Params["uploadData"] = $doc->SaveXML();
				$Params["method"] = "upload";
				$Params["type"] = 2;
				$Params["refTable"] = $reftableList[$ctr];
				$Params["refColumn"] = $refIDList[$ctr];
				$Params["debug"] = true;//<--------------------- set this to true when debugging
				
				flush();
				$Response = $Service->SendRequest($Params);
				
				//debugecho($Response["ResponseBody"]);
				$ctr++;
			}
			
			$database->commitTransaction();
		}
		catch (Exception $e)
		{
			debugecho($e->getTraceAsString());
			$database->rollbackTransaction();
		}
		
		$Params["method"] = "endsync";
		$Params["syncID"] = $syncID;
		
		flush();
		$Response = $Service->SendRequest($Params);
		
		$old_error_handler = set_error_handler("myErrorHandler");
		//--------------------------------->>>>>>>>>>>>>>Download<<<<<<<<<<<--------------------------
		//tables for download ordered by download sequence
        $tableList = array( //"arealevel","branchtype", "customerclass", "productlevel","creditterm","customertype",
							//"departmentlevel","employeetype","fieldgroup","fieldtype","producttype", "statustype",
							//"unittype","tpi_gsutype","officetype","tpi_pmg", 
							//"promotype", "status","reasontype",
							"department","employee", "area","branch","branchdetails",
							"field", "value", "warehouse", "location","module","modulecontrol","movementtype","position",
							"product","productdetails", "productpricing", "reason",
							"remployeeposition","remployeebranch","status",
							"submodule","taxcode","tpi_pda","tpi_zone",
							"promo", "promoavailment", "promobuyin", "promoentitlement", "promoentitlementdetails",
        					"bank", "holiday",
        					//"branchbank", "branchdepartment",
        					"brochure", "campaign", "campaignmonth", 
        					"discount", "position", "productkit",
							"productregkit", "productsubstitute", "remployeebranch", "remployeeposition",
        					// "rusertypemodulecontrol"
							);
		
		unset($Params);					
		//Start Sync
		$Params["syncTypeID"] = 2;
		$Params["method"] = "startsync";
		$Params["branchID"] = $branchID;
		
		flush();
		$Response = $Service->SendRequest($Params);
		
		$dom = $Response["Body"];
		$xml = simplexml_import_dom($dom);	

		foreach($xml -> children() as $tableName => $tablenode)
		{
			$syncID = $tablenode;
		}
		
		//Insert new from HeadOffice				
		foreach ($tableList as $tblName)
		{
			debugecho($tblName . "<br/>");
			unset($Params);
			//Webservice parameters
			$Params["table"] = $tblName;
			$Params["lastSyncTime"] = $lastSyncTime; 
			$Params["type"] = 1;
			$Params["method"] = "download";
			$Params["branchID"] = $branchID;
			
			flush();
			$Response = $Service->SendRequest($Params);
			
			$dom = $Response["Body"];
			$xml = simplexml_import_dom($dom); //or die ("Unable to load XML file!");
			
			$selectQry = "";
			$insertedIDs = "";
			$ID = "";
			
			$processed = 0;
			if ($xml)
			{
				foreach($xml -> children() as $tableName => $tablenode)
				{
					$insertQry = "Insert into `".$tableName."`(";
					$valuesQry = "Values( ";
						
					$selectQry = "";
					$ctr = 0;
									
					foreach($tablenode -> children() as $name => $node)
					{
						$insertQry .= $name . ", ";
						
						if ($name == "Changed")
						{
							$valuesQry .= "0, ";
						}
						else if($node=="")
						{
							if ($tables[$tableName][$ctr] == 0)
								$valuesQry .= "null, ";
							else
								$valuesQry .= "'', ";
						}
						//if($node==null)
						elseif(empty($node))
						{
							$valuesQry .= "null, ";
						}
						else
						{
							if ($tables[$tableName][$ctr] == 0)
								$valuesQry .= $node . ", ";
							else
								$valuesQry .= "'".str_replace("'","\'",$node) . "', ";
						}
						
						if ($ctr==0)
							$ID = $node;
							
						
						$ctr++;
					}
					
					if ($ID != "")
					{
						$query = "SELECT * FROM $tblName where ID = $ID";
						//debugecho("----->".$query." --- ");
						$dbresult = $database->execute($query);
						$num_rows = $dbresult->num_rows;
						//debugecho($num_rows." <br>");
						if ($num_rows == 0)
						{
							$insertQry = substr($insertQry, 0, -2).")";
							$valuesQry = substr($valuesQry, 0, -2).")";
							$selectQry .= $valuesQry;
							
							// mysql_query($insertQry." ".$selectQry, $dbconnect);
							try {
								$database->execute($insertQry." ".$selectQry);
							}
							catch (Exception $e)
							{
								//echo ($e . "<br/>");
							}
							
							//debugecho($insertQry."<br/>)";
							//debugecho($selectQry."<br/>";
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
			
			if ($processed > 0)
			{
				$Params["table"] = $tblName;
				$Params["method"] = "syncdetails";
				$Params["syncID"] = $syncID;
				$Params["processed"] = $processed;
				$Params["IDs"] = $insertedIDs;
				$Params["branchID"] = $branchID;  
				
				flush();
				$Response = $Service->SendRequest($Params);
			}
		}
		debugecho("here<br/>");
		
		//$tableList = array("inventoryinout","inventoryinoutdetails", "inventoryadjustment", "inventoryadjustmentdetails");
		$tableList = array("inventoryinout","inventoryinoutdetails");
		//transactional
		try 
		{
			$database->beginTransaction();
			$inventoryinoutIDs = "";
			$inventoryinoutDetailIDs = "";
			$inventoryCount = 0;
			$inventoryDetailsCount = 0;
			
			$inventoryAdjustmentCount = 0;
			$inventoryAdjustmentDetailsCount = 0;
			$inventoryAdjustmentIDs = "";
			$inventoryAdjustmentDetailIDs = "";
			
			foreach ($tableList as $tblName)
			{
				debugecho("$tblName <br/>");
				//Webservice parameters
				$Params["table"] = $tblName;
				$Params["lastSyncTime"] = $lastSyncTime; 
				$Params["type"] = 1;
				$Params["method"] = "download";
				$Params["branchID"] = $branchID;
				
				flush();
				$Response = $Service->SendRequest($Params);
				
				$dom = $Response["Body"];
				$xml = simplexml_import_dom($dom); //or die ("Unable to load XML file!");
				
				$selectQry = "";
				$insertedIDs = "";
				$ID = "";
				
				if ($xml)
				{
					$processed = 0;
					foreach($xml -> children() as $tableName => $tablenode)
					{
						$insertQry = "Insert into `".$tableName."`(";
						$valuesQry = "Values( ";
							
						$selectQry = "";
						$ctr = 0;
										
						foreach($tablenode -> children() as $name => $node)
						{
							if ($name == "InventoryInOutID")
							{
								$insertQry .= $name . ", ";
								$query = "select ID from inventoryinout where RefID = $node";
								debugecho($query."<br>");
								$rsID = $database->execute($query);
								$row = $rsID->fetch_object();
								$valuesQry .= $row->ID.", ";
							}
							/*else if ($name == "InventoryAdjustmentID")
							{
								$insertQry .= $name . ", ";
								$query = "select ID from inventoryadjustment where RefID = $node";
								debugecho($query."<br>");
								$rsID = $database->execute($query);
								$row = $rsID->fetch_object();
								$valuesQry .= $row->ID.", ";
							}*/
							else if ($name != "ID")
							{
								$insertQry .= $name . ", ";
								
								if ($name == "Changed")
								{
									$valuesQry .= "0, ";
								}
								elseif ($name == "RefID")
							    {
							        $valuesQry .= $ID.", ";
							    }
								else if($node=="")
								{
									if ($tables[$tableName][$ctr] == 0)
										$valuesQry .= "null, ";
									else
										$valuesQry .= "'', ";
								}
								elseif(empty($node))
								{
									$valuesQry .= "null, ";
								}
								else
								{
									if ($tables[$tableName][$ctr] == 0)
										$valuesQry .= $node . ", ";
									else
										$valuesQry .= "'".str_replace("'","\'",$node) . "', ";
								}
							}
							
							if ($ctr==0)
								$ID = $node;
								
							$ctr++;
						}
						
						if ($ID != "")
						{
							$query = "SELECT * FROM $tblName where RefID = $ID";
							//debugecho("----->".$query." --- ");
							$dbresult = $database->execute($query);
							$num_rows = $dbresult->num_rows;
							//debugecho($num_rows." <br>");
							if ($num_rows == 0)
							{
								$insertQry = substr($insertQry, 0, -2).")";
								$valuesQry = substr($valuesQry, 0, -2).")";
								$selectQry .= $valuesQry;
								
								$database->execute($insertQry." ".$selectQry);
								$processed += 1;
								$insertedIDs .= "$ID, ";
							}
						}
					}
				}
				
				$insertedIDs = substr($insertedIDs, 0, -2);	
				if ($tblName == "inventoryinout")
				{
					$inventoryCount = $processed;
					$inventoryinoutIDs = $insertedIDs;
				}
				//else if ($tblName == "inventoryinoutdetails")
				else
				{
					$inventoryDetailsCount = $processed;
					$inventoryinoutDetailIDs = $insertedIDs;
				}
				/*else if ($tblName == "inventoryadjustment")
				{
					$inventoryAdjustmentCount = $processed;
					$inventoryAdjustmentIDs = $insertedIDs;
				}
				else
				{
					$inventoryAdjustmentDetailsCount = $processed;
					$inventoryAdjustmentDetailIDs = $insertedIDs;
				}*/
			}
		
			if ($inventoryCount > 0)
			{
				$Params["table"] = "inventoryinout";
				$Params["method"] = "syncdetails";
				$Params["syncID"] = $syncID;
				$Params["processed"] = $inventoryCount;
				$Params["IDs"] = $inventoryinoutIDs;
				$Params["branchID"] = $branchID;
				
				flush();
				$Response = $Service->SendRequest($Params);
			}
			
			if ($inventoryDetailsCount > 0)
			{
				$Params["table"] = "inventoryinoutdetails";
				$Params["method"] = "syncdetails";
				$Params["syncID"] = $syncID;
				$Params["processed"] = $inventoryDetailsCount;
				$Params["IDs"] = $inventoryinoutDetailIDs;
				$Params["branchID"] = $branchID;
				
				flush();
				$Response = $Service->SendRequest($Params);
			}
			
			/*if ($inventoryAdjustmentCount > 0)
			{
				$Params["table"] = "inventoryadjustment";
				$Params["method"] = "syncdetails";
				$Params["syncID"] = $syncID;
				$Params["processed"] = $inventoryAdjustmentCount;
				$Params["IDs"] = $inventoryAdjustmentIDs;
				$Params["branchID"] = $branchID;
				
				flush();
				$Response = $Service->SendRequest($Params);
			}
			
			if ($inventoryAdjustmentDetailsCount > 0)
			{
				$Params["table"] = "inventoryadjustmentdetails";
				$Params["method"] = "syncdetails";
				$Params["syncID"] = $syncID;
				$Params["processed"] = $inventoryAdjustmentDetailsCount;
				$Params["IDs"] = $inventoryAdjustmentDetailIDs;
				$Params["branchID"] = $branchID;
				
				flush();
				$Response = $Service->SendRequest($Params);
			}*/
		
			$database->commitTransaction();
		}
		catch (Exception $e)
		{
			debugecho($e->getTraceAsString());
			$database->rollbackTransaction();
		}
		
		//Update Routine
		
		$tableList = array( //"arealevel","branchtype", "customerclass", "productlevel","creditterm","customertype",
							//"departmentlevel","employeetype","fieldgroup","fieldtype","producttype", "statustype",
							//"unittype","tpi_gsutype","officetype","tpi_pmg", 
							//"promotype", "status","reasontype",
							"department","employee", "area","branch","branchdetails",
							"field", "value", "warehouse", "location","module","modulecontrol","movementtype","position",
							"product","productdetails", "productpricing", "reason",
							"remployeeposition","remployeebranch","status",
							"submodule","taxcode","tpi_pda","tpi_zone",
							"promo", "promoavailment", "promobuyin", "promoentitlement", "promoentitlementdetails",
        					"bank", 
        					//"branchbank", "branchdepartment",
        					"brochure", "campaign", "campaignmonth", 
        					"discount", "position", "productkit",
							"productregkit", "productsubstitute", "remployeebranch", "remployeeposition",
        					// "rusertypemodulecontrol"
							);

		//$tableList = array( "area");
		debugecho("there <br/>");
		foreach ($tableList as $tblName)
		{
			debugecho($tblName."<br/>");
			//Webservice parameters
			$Params["table"] = $tblName;
			$Params["lastSyncTime"] = $lastSyncTime; 
			$Params["type"] = 2;
			$Params["method"] = "download";
			
			flush();
			$Response = $Service->SendRequest($Params);
			
			$dom = $Response["Body"];
			$xml = simplexml_import_dom($dom); //or die ("Unable to load XML file!");
			
			$selectQry = "";
			$insertQry = "";
			//if ($xml)
			//{
			$updatedIDs = "";
			foreach($xml -> children() as $tableName => $tablenode)
			{
				$updateQry = "Update `".$tableName."` ";
				$valuesQry = "Set ";
				
				$ctr = 0;
				foreach($tablenode -> children() as $name => $node)
				{
					$insertQry .= $name . ", ";
					
					if ($ctr==0)
						$ID = $node;
					else if ($name == "Changed")
					{
						$valuesQry .= "$name = 0, ";
					}
					else
					{
						//if($node=="")
						if(empty($node) || $node=="")
						{
							if ($tables[$tableName][$ctr] == 0)
								$valuesQry .= "$name = null, ";
							else
								$valuesQry .= "$name = '', ";
						}
						else
						{
							if ($tables[$tableName][$ctr] == 0)
								$valuesQry .= "$name = $node, ";
							else
								$valuesQry .= "$name = '".str_replace("'","\'",$node) .  "', ";
						}
					}
						
					$ctr++;
				}
				 
				$valuesQry = substr($valuesQry, 0, -2)." where ID = $ID";
				
				if ($ID != "")
				{
					$query = "SELECT * FROM $tblName where ID = $ID";
					//echo "----->".$query." --- ";
					$dbresult = $database->execute($query);
					$num_rows = $dbresult->num_rows;
					//echo $num_rows." <br>";
					if ($num_rows)
					{
						$processed += 1;
						$updatedIDs .= "$ID, ";
							
						//debugecho($updateQry.$valuesQry."<br/>");
						$database->execute($updateQry.$valuesQry);
						$database->commitTransaction();
					}
				}
			}
				
			if ($processed > 0)
			{
				$updatedIDs = substr($updatedIDs, 0, -2);
				unset($Params);
				$Params["table"] = $tblName;
				$Params["method"] = "syncdetails";
				$Params["syncID"] = $syncID;
				$Params["processed"] = $processed;
				$Params["IDs"] = $updatedIDs;
				$Params["branchID"] = $branchID;
				
				flush();
				//debugecho("updatedIDs = $updatedIDs <br/>");
				$Response = $Service->SendRequest($Params);
			}
		}
		
		$Params["method"] = "endsync";
		$Params["syncID"] = $syncID;
		
		flush();
		$Response = $Service->SendRequest($Params);

		$query = "UPDATE branchparameter set LastSyncDate = Now();";
		$dbresult = $database->execute($query);
		$sp->spInsertInventoryNewProduct($database);
		$database->commitTransaction();
		
		echo "<font color='#00008B'><b>Head Office Sync Successful<b></font>";
	//}	
$database->close();
?>

