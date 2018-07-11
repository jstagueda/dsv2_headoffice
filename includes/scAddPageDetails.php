<?php 
	global $database;
	$brochureSearch = "";
	$cnt_callout = 0;
	$cnt_violator = 0;
	$cnt_heroed = 0;
	$cnt_wornby = 0;
	
	$rs_prodlist = $sp->spSelectProductListByLevelID($database, 3, "");
	$rs_colorlist = $sp->spSelectProductColors($database, 0);
	
	if (isset($_GET['PID']) && (!isset($_POST['btnAdd1']) && !isset($_POST['btnAdd2']) && !isset($_POST['btnAdd3']) && !isset($_POST['btnAdd4'])) && !isset($_POST['btnSaveInfo']))
	{
		unset($_SESSION["callouts"]);
	 	unset($_SESSION["violators"]);
	 	unset($_SESSION["heroed"]);
	 	unset($_SESSION["itemworn"]);
	 	
		$rs_callout = $sp->spSelectBrochureText($database, $_GET['PID']);
		$rs_violator = $sp->spSelectBrochureText($database, $_GET['PID']);
		$rs_heroed = $sp->spSelectProductInfoHeroed($database, $_GET['PID']);
		$rs_wornbymodel = $sp->spSelectProductInfoWornByModel($database, $_GET['PID']);
	
		if (!isset($_SESSION['callouts']))
		{
			$_SESSION['callouts'] = array();
		}
		
		if($rs_callout->num_rows)
		{
			while($row = $rs_callout->fetch_object())
			{
				if ($row->CallOut != "")
				{
					array_push($_SESSION['callouts'], $row->CallOut);
					$cnt_callout = sizeof($_SESSION['callouts']);					
				}
			}
			$rs_callout->close();
		}
		
		if (!isset($_SESSION['violators']))
		{
			$_SESSION['violators'] = array();
		}
		
		if($rs_violator->num_rows)
		{
			while($row = $rs_violator->fetch_object())
			{
				if ($row->Violator != "")
				{
					array_push($_SESSION['violators'], $row->Violator);
					$cnt_violator = sizeof($_SESSION['violators']);					
				}
			}
			$rs_violator->close();
		}
		
		if (!isset($_SESSION['heroed']))
		{
			$_SESSION['heroed'] = array();
		}
		
		if($rs_heroed->num_rows)
		{
			while($row = $rs_heroed->fetch_object())
			{
				array_push($_SESSION['heroed'], $row->Heroed);
				$cnt_heroed = sizeof($_SESSION['heroed']);
			}
			$rs_heroed->close();
		}
		
		if (!isset($_SESSION['itemworn']))
		{
			$_SESSION['itemworn'] = array();
		}
		
		if($rs_wornbymodel->num_rows)
		{
			while($row = $rs_wornbymodel->fetch_object())
			{
				array_push($_SESSION['itemworn'], $row->WornByModel);
				$cnt_wornby = sizeof($_SESSION['itemworn']);
			}
			$rs_wornbymodel->close();
		}
	}
	
	if(isset($_POST['btnSearch2']))
	{
		$brochureSearch = addslashes($_POST['searchTxtFld2']);	
		$rs_brochure = $sp->spSelectBrochures($database, -1, $brochureSearch);
	}	
	elseif(isset($_GET['searchedTxt2']))
	{
		$brochureSearch = addslashes($_GET['searchedTxt2']);	
		$rs_brochure = $sp->spSelectBrochures($database, -1, $brochureSearch);
	}
	else
	{
		$rs_brochure = $sp->spSelectBrochures($database, -1, "");
	}
	
	if(isset($_GET['ID']))
	{
		$bID = $_GET['ID'];		
		$rs_brochuredetails =  $sp->spSelectBrochures($database, $bID, "");		
		
		if($rs_brochuredetails->num_rows)
		{
			while($row = $rs_brochuredetails->fetch_object())
			{								
				$bcode = $row->Code;
				$bname = $row->Name;
				$nopage = $row->NumberOfPages;	
				$collateralid = $row->CollateralTypeID;
				$tmpdc = $row->EnrollmentDate;
				$datecreated = date("M d, Y",strtotime($tmpdc));
			}
			$rs_brochuredetails->close();
		}
	}
	
	if(isset($_POST['btnSaveInfo']))
	{
		try
		{
			$database->beginTransaction();
			
			if(isset($_GET['PID']))
			{
				$pageID = $_GET['PID'];
			}
			
			if(isset($_SESSION['callouts']))
			{
				$affected_rows = $sp->spDeleteBrochureCallOut($database, $pageID);
				if (!$affected_rows)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
					
				$tmplist = $_SESSION['callouts'];
	    		$n = sizeof($tmplist);
	    		for ($i = 0; $i < $n; $i++)
	    		{
	    			$affected_rows = $sp->spInsertBrochureText($database, $pageID, $tmplist[$i], null);
					if (!$affected_rows)
					{
						throw new Exception("An error occurred, please contact your system administrator.");
					}
	    		}				
			}
			
			if(isset($_SESSION['violators']))
			{
				$affected_rows = $sp->spDeleteBrochureViolator($database, $pageID);
				if (!$affected_rows)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				
				$tmplist = $_SESSION['violators'];
	    		$n = sizeof($tmplist);
	    		for ($i = 0; $i < $n; $i++)
	    		{
	    			$affected_rows = $sp->spInsertBrochureText($database, $pageID, null, $tmplist[$i]);
					if (!$affected_rows)
					{
						throw new Exception("An error occurred, please contact your system administrator.");
					}
	    		}				
			}
			
			if(isset($_SESSION['heroed']))
			{
				$affected_rows = $sp->spDeleteBrochureProdInfoHeroed($database, $pageID);
				if (!$affected_rows)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				
				$tmplisth = $_SESSION['heroed'];
	    		$n = sizeof($tmplisth);
	    		for ($i = 0; $i < $n; $i++)
	    		{
	    			$affected_rows = $sp->spInsertBRochureProductInfo($database, $pageID, 0, $tmplisth[$i], 0);
					if (!$affected_rows)
					{
						throw new Exception("An error occurred, please contact your system administrator.");
					}
	    		}				
			}
			
			if(isset($_SESSION['itemworn']))
			{
				$affected_rows = $sp->spDeleteBrochureProdInfoWornByModel($database, $pageID);
				if (!$affected_rows)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				
				$tmplist = $_SESSION['itemworn'];
	    		$n = sizeof($tmplist);
	    		for ($i = 0; $i < $n; $i++)
	    		{
	    			$affected_rows = $sp->spInsertBRochureProductInfo($database, $pageID, 0, 0, $tmplist[$i]);
					if (!$affected_rows)
					{
						throw new Exception("An error occurred, please contact your system administrator.");
					}
	    		}				
			}
			
			$database->commitTransaction();
			$message = "Successfully saved Page Details.";
			$tid = $_GET["ID"];
			$pid = $_GET["PID"];
			redirect_to("index.php?pageid=116.1&ID=$tid&PID=$pid&msg=$message");
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";	
			redirect_to("index.php?pageid=116.1&ID=$tid&msg=$errmsg");
		}
	}
	
	if(isset($_POST['btnCancel']))
	{
		$tid = $_GET["ID"];
		redirect_to("index.php?pageid=116.1&ID=$tid");		
	}
	
	if(isset($_POST['btnBack']))
	{
		redirect_to("index.php?pageid=116");		
	}
	
	if(isset($_POST['btnAdd1']))
	{
		if (!isset($_SESSION['callouts']))
		{
			$_SESSION['callouts'] = array();
		}
		array_push($_SESSION['callouts'], $_POST['txtCallouts']);
		$_SESSION['callouts'] = array_unique($_SESSION['callouts']);
		$cnt_callout = sizeof($_SESSION['callouts']);
	}
	
	if(isset($_POST['btnAdd2']))
	{		
		if (!isset($_SESSION['violators']))
		{
			$_SESSION['violators'] = array();
		}
		array_push($_SESSION['violators'], $_POST['txtOfferViol']);
		$_SESSION['violators'] = array_unique($_SESSION['violators']);
		$cnt_violator = sizeof($_SESSION['violators']);
	}
	
	if(isset($_POST['btnAdd3']))
	{
		if (!isset($_SESSION['heroed']))
		{
			$_SESSION['heroed'] = array();
		}
		array_push($_SESSION['heroed'], $_POST['hProdIDH']);
		$_SESSION['heroed'] = array_unique($_SESSION['heroed']);
		$cnt_heroed = sizeof($_SESSION['heroed']);		
	}
	
	if(isset($_POST['btnAdd4']))
	{
		if (!isset($_SESSION['itemworn']))
		{
			$_SESSION['itemworn'] = array();
		}
		array_push($_SESSION['itemworn'], $_POST['hProdID']);
		$_SESSION['itemworn'] = array_unique($_SESSION['itemworn']);
		$cnt_wornby = sizeof($_SESSION['itemworn']);		
	}
?>