<?php

	global $database;
	$brochureSearch = '';
	$bcode = '';
	$bname = '';
	$nopage = '';
	
	$campcode = '';
	$campname = '';
	$campstart = '';	
	$campend = '';
	$collateralid = 0;
	$datecreated = '';
	$cd_code = '';
	$cd_name = '';
	$cd_nopage = '';
	$cd_coltype = '';	
	
	$callouts = '';
	$offviol = '';
	$bID = 0;
	$campCount = 0;
	
	$startdate = '';
	$enddate = '';
	$height = '';
	$width = '';
	$statid = 0;
	$ptype = '';
	$ltype = '';
	$campaigncount = 0;
	$refid = 0;
	$tablecount = 0;
	$linkcount = 0;
	
	if(isset($_GET['ID']))
	{
		$bID = $_GET['ID'];
	}
	
 	$rs_campaign = $sp->spSelectCampaignByCollateral($database, $bID);
 	$rs_collateral = $sp->spSelectCollateralType($database);
 	$rs_pagetype = $sp->spSelectPageType($database, "", 0);
 	$rs_layouttype = $sp->spSelectLayoutType($database);
 	$rs_collstatus = $sp->spSelectStatusListByStatusTypeID($database, 6);
 	
 	if (!isset($_GET["PID"]))
 	{
 		$_GET["PID"] = 0;
 	}
 	
 	//for dynamic combo box
	$index = 0;
	$pagetypeid = "";
	$pagetypename = "";
	if($rs_pagetype->num_rows)
	{
		while($row = $rs_pagetype->fetch_object())
		{
			$index += 1;
			if ($index == $rs_pagetype->num_rows)
			{
				$pagetypeid = $pagetypeid.$row->ID;
				$pagetypename = $pagetypename."'".$row->Name."'";
			}
			else
			{
				$pagetypeid = $pagetypeid.$row->ID.", ";				
				$pagetypename = $pagetypename."'".$row->Name."'".", ";
			}	
		}
		$rs_pagetype->close();
	}
	
	$index1 = 0;
	$layouttypeid = "";
	$layouttypename = "";
	
	if($rs_layouttype->num_rows)
	{
		while($row1 = $rs_layouttype->fetch_object())
		{
			$index1 += 1;
			if ($index1 == $rs_layouttype->num_rows)
			{
				$layouttypeid = $layouttypeid.$row1->ID;
				$layouttypename = $layouttypename."'".$row1->Name."'";
			}
			else
			{
				$layouttypeid = $layouttypeid.$row1->ID.", ";				
				$layouttypename = $layouttypename."'".$row1->Name."'".", ";
			}	
		}
		$rs_layouttype->close();
	}
	
	if(isset($_POST['btnSearch']))
	{
		$brochureSearch = addslashes($_POST['searchTxtFld']);	
		$rs_brochure = $sp->spSelectBrochuresAllStatus($database,-1,$brochureSearch);
	}
	elseif(isset($_GET['searchedTxt']))
	{
		$brochureSearch = addslashes($_GET['searchedTxt']);	
		$rs_brochure = $sp->spSelectBrochuresAllStatus($database,-1,$brochureSearch);
	}
	else
	{
		$rs_brochure = $sp->spSelectBrochuresAllStatus($database,-1,"");
	}
	
	if(isset($_GET['ID']))
	{
		$bID = $_GET['ID'];		
		$rs_brochuredetails =  $sp->spSelectBrochuresAllStatus($database, $bID, "");		
		$rs_brochurelayout = $sp->spSelectBrochureLayout($database, $bID);
		$rs_brochurelayout1 = $sp->spSelectBrochureLayout($database, $bID);
		$rs_brochurepagedetails =  $sp->spSelectBrochureDetailsByBrochureID($database, $bID);
		$rs_brochurepage = $sp->spSelectBrochureDetailsByPageID($database, $_GET['PID']);
		$rs_brochurepagelink = $sp->spSelectLinkedPromosAndProducts($database, $_GET['PID']);
		
		$rs_brochurepagedetails_count =  $sp->spSelectBrochureDetailsByBrochureID($database, $bID);
		$tablecount = $rs_brochurepagedetails_count->num_rows;
		
		$linkcount = $rs_brochurepagelink->num_rows;
				
		if($rs_brochuredetails->num_rows)
		{
			while($row = $rs_brochuredetails->fetch_object())
			{								
				$bcode = $row->Code;
				$bname = $row->Name;
				$nopage = $row->NumberOfPages;	
				$collateralid = $row->CollateralTypeID;
				$datecreated = $row->EnrollmentDate;
				$startdate = date('m/d/Y',strtotime($row->StartDate));
				$enddate = date('m/d/Y',strtotime($row->EndDate));
				$height = $row->Height;
				$width = $row->Width;
				$statid = $row->StatusID;
				$status = $row->Status;
				$refid = $row->ReferenceID;
			}
			$rs_brochuredetails->close();
		}
		
		$rs_campaignlist = $sp->spSelectCampaignByBrochureID($database, $bID);
		$rs_campaignlist2 = $sp->spSelectCampaignByBrochureID($database, $bID);
		$campaigncount = $rs_campaignlist2->num_rows;
		$campCount = $rs_campaignlist2->num_rows;
		
		if($rs_brochurepage->num_rows)
		{
			while($row = $rs_brochurepage->fetch_object())
			{
				$ltype = $row->LayoutType;
				$ptype = $row->PageType;
			}
			$rs_brochurepage->close();
		}
	}
	
	if(isset($_GET['campid']))
	{
		$campid=$_GET['campid'];		
		$rs_campdetails =  $sp->spSelectCampaignByID($database, $campid);		
		
		if($rs_campdetails->num_rows)
		{
			while($row = $rs_campdetails->fetch_object())
			{								
				$campcode = $row->Code;
				$campname = $row->Name;
				$campstart = $row->StartDate;	
				$campend = $row->EndDate;
			}
			
			$start = date("m-d-Y", strtotime($campstart));
			$campstart = $start;
			$end = date("m-d-Y", strtotime($campend));
			$campend = $end;
			$rs_campdetails->close();
		}
	}
	
	if(isset($_POST['btnSave']))
	{
		$cd_id = 0;
		if(isset($_GET['ID']))
		{
			$cd_id = $_GET['ID'];	
		}

		if($_POST['txtCode'] != '')
		{
			$cd_code = htmlentities(addslashes($_POST['txtCode']));		
		}
		else
		{
			$cd_code =  " ";
		}
		
		if($_POST['txtName'] != '')
		{
			$cd_name = htmlentities(addslashes($_POST['txtName']));		
		}
		else
		{
			$cd_name =  " ";
		}
		
		if($_POST['txtNoPage'] != '')
		{
			$cd_nopage = htmlentities(addslashes($_POST['txtNoPage']));		
		}
		else
		{
			$cd_nopage =  0;
		}
		
		if($_POST['pColType'] != 0)
		{
			$cd_coltype = htmlentities(addslashes($_POST['pColType']));	
		}
		else
		{
			$cd_coltype = 1;
		}
		
		$sdate = date("Y-m-d", strtotime($_POST['txtStartDate']));
		$edate = date("Y-m-d", strtotime($_POST['txtEndDate']));
		
		if ($_POST['txtHeight'] == "")
		{
			$sheight = null;
		}
		else
		{
			$sheight = $_POST['txtHeight'];
		}
		
		if ($_POST['txtWidth'] == "")
		{
			$swidth = null;
		}
		else
		{
			$swidth = $_POST['txtWidth'];
		}
		
		if (isset($_POST['cboStatus']))
		{
			$status = $_POST['cboStatus'];
		}
		else
		{
			$status = 25;
		}
		
		try
		{
			$database->beginTransaction();
			
			//check if code exists
			$exist = $sp->spCheckBrochureCodeIfExist($database, $cd_code, $bID);
			if ($exist->num_rows)
			{
				$message = "Code already exist.";		
				redirect_to("index.php?pageid=113&msg=$message");				
			}
			else
			{
				//brochure
				$affected_rows = $sp->spInsertUpdateBrochure($database, $bID, $cd_code, $cd_name, $cd_nopage, $cd_coltype, $sdate, $edate, $sheight, $swidth, $status, 0);
				if ($affected_rows->num_rows)
				{
					while ($row = $affected_rows->fetch_object())
					{
						$bID = $row->ID;
					}
					$affected_rows->close();
				}
				
				//brochure version
				if ($status == 26)
				{
					$reference = $sp->spSelectCollateralReferenceByID($database, $bID, 1);
					if ($reference->num_rows)
					{
						while ($row = $reference->fetch_object())
						{
							if ($row->ReferenceID != 0)
							{
								$sp->spUpdateBrochureVersionStatus($database, $row->ReferenceID);							
							}
						}
						$reference->close();
					}
					
					$version = $sp->spSelectCollateralReferenceByID($database, $bID, 2);
					if ($version->num_rows)
					{
						while ($row = $version->fetch_object())
						{
							$sp->spUpdateBrochureVersionStatus($database, $row->ID);
						}
						$version->close();
					}
				}	
	
				//brochuredetails
				if(isset($_POST['hdnCntr']) && $_POST['hdnCntr'] > 0)
				{
					if ($cd_nopage > 0)
					{
						/*if($bID > 0)
						{
							$affected_rows1 = $sp->spDeleteBrochureDetails($database, $bID);
						}*/
							
						$y = 1;
						for($x = 1; $x <= $cd_nopage; $x++)
						{
							$pagetype = $_POST["pPageType{$y}"];
							if (isset($_POST["pPageLayout{$y}"]))
							{
								$pagelayout = $_POST["pPageLayout{$y}"];								
							}
							else
							{
								$pagelayout = 1;
							}
							
							$affected_rows2 = $sp->spInsertBrochureDetails($database, $bID, $x, $pagetype, $pagelayout);
							$y++;
						}
					}				
				}
				
				//brochurecampaign
				if ($_POST["hCampList"] != "")
				{
					$campaign = explode(",", substr($_POST["hCampList"], 0, -1));
					foreach ($campaign as $key=>$campID)
					{
						$sp->spInsertBrochureCampaign($database, $bID, $campID);			
					}				
				}
				/*else
				{
					$list = $sp->spSelectCampaignByBrochureID($database, $bID);
					if($list->num_rows)
					{
						while($row = $list->fetch_object())
						{
							$sp->spDeleteBrochureCampaign($database, $bID, $row->CampaignID);
						}
						$list->close();
					}
				}*/
							
				$database->commitTransaction();
				$message = "Successfully saved record.";		
				redirect_to("index.php?pageid=113&msg=$message");				
			}
		
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";	
			redirect_to("index.php?pageid=113&msg=$errmsg");
		}
	}
	
	if (isset($_POST['btnCopy']))
	{
		try
		{
			$database->beginTransaction();
			//check if code exists
			$exist = $sp->spCheckBrochureCodeIfExist($database, $_POST['hNewCode'], 0);
			if ($exist->num_rows)
			{
				$message = "Code already exist.";		
				redirect_to("index.php?pageid=113&msg=$message");				
			}
			else
			{
				$bID = $_GET['ID'];
				$affected_rows = $sp->spCreateCollateralVersion($database, $bID, $_POST['hNewCode']);
					
				$database->commitTransaction();
				$message = "Successfully created collateral version.";		
				redirect_to("index.php?pageid=113&ID=$bID&msg=$message");			
			}
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";	
			redirect_to("index.php?pageid=113&msg=$errmsg");
		}
	}
	
	if(isset($_GET['link']))
	{
		echo "here"; exit;
		try
		{
			$database->beginTransaction();
			
			if(isset($_GET['hdnids']) && $_GET['hdnids'] != "" && isset($_GET['promoid']))
			{
				$split = split(",", $_GET['hdnids']);
				
				for($i=0, $n=sizeof($split); $i < $n; $i++)
				{
					$affected_rows = $sp->spInsertBrochurePage($database, $bID,  $split[$i], $_GET['promoid'], 0);	
				}
			}
			
			if(isset($_GET['hdnids2']) && $_GET['hdnids2'] != "" && isset($_GET['promoid']))
			{
				$split = split(",", $_GET['hdnids2']);
				
				for($i=0, $n=sizeof($split); $i < $n; $i++)
				{
					$affected_rows = $sp->spInsertBrochurePage($database, $bID,  $split[$i], $_GET['promoid'], 1);	
				}
			}
			
			if (!$affected_rows)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}
			
			//update promo status
			
			$database->commitTransaction();
			$message = "Successfully saved record.";		
			redirect_to("index.php?pageid=114.1&ID=$bID&msg=$message");
		
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";	
			redirect_to("index.php?pageid=114.1&ID=$bID&msg=$errmsg");
		}
	}
?>