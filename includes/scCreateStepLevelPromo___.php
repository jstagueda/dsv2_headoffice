<?php

	global $database;
	$errmsg = "";
	
	if (!isset($_GET['promoID']))
	{
		$date = time();
		$today = date("m/d/Y",$date);
		$tmpdate = strtotime(date("Y-m-d", strtotime($today)) . " +1 month");
		$end = date("m/d/Y",$tmpdate);
		$ctr = 1;		
	}
	else
	{
		//get promo header
		$rspromodet = $sp->spSelectPromoByID($database, $_GET['promoID']);
		if($rspromodet->num_rows)
		{
			while($row = $rspromodet->fetch_object())
			{
				$promocode = $row->Code;
				$promodesc = $row->Description;
				$tmpsdate = strtotime($row->StartDate);
				$today = date("m/d/Y",$tmpsdate);
				$tmpedate = strtotime($row->EndDate);
				$end = date("m/d/Y",$tmpedate);
			}
			$rspromodet->close();
		}
		
		//get promobuyin
		$rspromobuyin_count = $sp->spSelectPromoBuyInCountByPromoID($database, $_GET['promoID']);
		if($rspromobuyin_count->num_rows)
		{
			while($row = $rspromobuyin_count->fetch_object())
			{
				$tmpcnt = $row->Cnt;
				$prtype = $row->Type;			
			}
			$rspromobuyin_count->close();
		}
		$ctr = $tmpcnt + 1;
		
		$rspromobuyin = $sp->spSelectPromoBuyInByPromoID($database, $_GET['promoID']);
		$rspromobuyin_ent = $sp->spSelectPromoBuyInByPromoID($database, $_GET['promoID']);
	}
	
	//get gsu type
	$rs_gsutype = $sp->spSelectGSUType($database);
	$rs_gsutype_save = $sp->spSelectGSUType($database);
	
	//get pmg
	$rs_pmg = $sp->spSelectPMG($database);
	$rs_pmg2 = $sp->spSelectPMG($database);
	
	//for dynamic combo box
	$index = 0;
	$pmg_id = "";
	$pmg_code = "";
	if($rs_pmg2->num_rows)
	{
		while($row = $rs_pmg2->fetch_object())
		{
			$index += 1;
			if ($index == $rs_pmg2->num_rows)
			{
				$pmg_id = $pmg_id.$row->ID;
				$pmg_code = $pmg_code."'".$row->Code."'";
			}
			else
			{
				$pmg_id = $pmg_id.$row->ID.", ";				
				$pmg_code = $pmg_code."'".$row->Code."'".", ";
			}	
		}
		$rs_pmg2->close();
	}
	
	//get product level list
	$rsprodlevel = $sp->spSelectProductLevel($database);
	
	//get product selection
	if (isset($_POST['cboRange']))
	{
		$levelid = $_POST['cboRange'];
	} 
	else
	{
		$levelid = 0;		
	}
	
	//product selection
	$rsprodselection = $sp->spSelectProductListByLevelID($database, $levelid, "");

	if (isset($_POST['btnCancel']))
	{
		redirect_to("index.php?pageid=64");			
	}
	else if (isset($_POST['btnAdd']))
	{
		try
		{
			$database->beginTransaction();
			
			$promocode = htmlentities(addslashes($_POST['txtCode']));				
			$promodesc = htmlentities(addslashes($_POST['txtDescription']));
			$tmpsdate = strtotime($_POST['txtStartDate']);
			$startdate = date("Y-m-d", $tmpsdate);
			$tmpedate = strtotime($_POST['txtEndDate']);
			$enddate = date("Y-m-d", $tmpedate);
			if(!isset($_POST['cboPReqtType']))
			{
				$preqttype = $prtype;			
			}
			else
			{
				$preqttype = $_POST['cboPReqtType'];
			}	
			$buyprod = $_POST['hBuyinCnt'];
			$entprod = $_POST['hEntitlementCnt'];
			$type = $_POST['cboType'];
			if ($type == 1)
			{
				$typeqty = $_POST['txtTypeQty'];
			}
			else
			{
				$typeqty = 0;
			}
			if(isset($_POST['chkPlusPlan']))
			{
				$isplusplan = $_POST['chkPlusPlan'];				
			}
			else
			{
				$isplusplan = 0;
			}
			
			//check if promo code already exist
			//$rs_code_exist = $sp->spCheckPromoIfExists($database, $promocode);
			//if($rs_code_exist->num_rows)
			//{
			//	$errmsg = "Promo code already exists.";
			//}
			//else
			//{
				if (!isset($_GET['promoID']))
				{
					//insert to promo table
					$rs_promoid = $sp->spInsertPromoHeader($database, $promocode, $promodesc, $startdate, $enddate, 4, $session->emp_id, $isplusplan);
					if (!$rs_promoid)
					{
						throw new Exception("An error occurred, please contact your system administrator.");
					}
					if($rs_promoid->num_rows)
					{
						while($row = $rs_promoid->fetch_object())
						{
							$promoID = $row->ID;
						}
					}
					
					//link promo to branches
					$rs_branch = $sp->spSelectBranch($database, -1, '');
					if($rs_branch->num_rows)
					{
						while($row_branch = $rs_branch->fetch_object())
						{											
							$sp->spInsertPromoBranchLinking($database, $promoID, $row_branch->ID); 
						}
						$rs_branch->close();										
					}	
					
					//insert to promoavailment table
					if($rs_gsutype_save->num_rows)
					{
						while($row = $rs_gsutype_save->fetch_object())
						{
							if(isset($_POST["txtMaxAvail{$row->ID}"]))
							{
								if($_POST["txtMaxAvail{$row->ID}"] != "")
								{
									$rs_promoavail = $sp->spInsertPromoAvailment($database, $promoID, $row->ID, $_POST["txtMaxAvail{$row->ID}"]);
									if (!$rs_promoavail)
									{
										throw new Exception("An error occurred, please contact your system administrator.");
									}							
								}						
							}					
						}
						$rs_gsutype_save->close();
					}		
				}
				else
				{
					$promoID = $_GET['promoID'];			
				}
				
				//insert to promobuyin - parent
				$rs_promobuyin_parent = $sp->spInsertPromoBuyIn($database, $promoID, 'null', 1, 'null', 'null', 'null', 'null', 3, 'null', $preqttype, 'null', 'null', 0);
				if (!$rs_promobuyin_parent)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}	
				if($rs_promobuyin_parent->num_rows)
				{
					while($row = $rs_promobuyin_parent->fetch_object())
					{
						$buyinparentID = $row->ID;
					}
				}
				
				$criteria = $_POST["cboCriteria"];
				$minimum = $_POST["txtMinimum"];
				$maximum = $_POST["txtMaximum"];
					
				//insert to promobuyin - child
				for ($i = 1; $i <= $buyprod; $i++)
				{
					$prodid = $_POST["hProdID_criteria{$i}"];
					
					if ($prodid != "")
					{
						if ($criteria == 1)
						{
							$rs_promobuyin = $sp->spInsertPromoBuyIn($database, $promoID, $buyinparentID, $criteria, $minimum, 'null', $maximum, 'null', 3, $prodid, $preqttype, 'null', 'null', 1);
							if (!$rs_promobuyin)
							{
								throw new Exception("An error occurred, please contact your system administrator.");
							}		
						}
						else
						{
							$rs_promobuyin = $sp->spInsertPromoBuyIn($database, $promoID, $buyinparentID, $criteria, 'null', $minimum, 'null', $maximum, 3, $prodid, $preqttype, 'null', 'null', 1);
							if (!$rs_promobuyin)
							{
								throw new Exception("An error occurred, please contact your system administrator.");
							}
						}
						
						if($rs_promobuyin->num_rows)
						{
							while($row = $rs_promobuyin->fetch_object())
							{
								$buyinID = $row->ID;
							}
						}
					}					
				}
		
				//insert to promoentitlement
				$rs_promoid = $sp->spInsertPromoEntitlement($database, $buyinparentID, $type, $typeqty);
				if (!$rs_promoid)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				if($rs_promoid->num_rows)
				{
					while($row = $rs_promoid->fetch_object())
					{
						$entitlementID = $row->ID;
					}
				}
				
				//insert to promoentitlementdetails
				for ($i = 1; $i <= $entprod; $i++)
				{
					$prodid = $_POST["hEProdID{$i}"];
					$produp = $_POST["hEUnitPrice{$i}"];
					$prodcode = $_POST["txtEProdCode{$i}"];
					$criteria = $_POST["cboECriteria{$i}"];
					$minimum = $_POST["txtEQty{$i}"];
					//$pmg = $_POST["hEpmgid{$i}"];
					$pmg = $_POST["cboEPMG{$i}"];
		
					if ($prodcode != "")
					{
						if ($criteria == 1)
						{
							$rs_promoent_details = $sp->spInsertPromoEntitlementDetails($database, $entitlementID, $prodid, $minimum, 0, $produp, $pmg);
							if (!$rs_promoent_details)
							{
								throw new Exception("An error occurred, please contact your system administrator.");
							}					
						}
						else
						{				
							$savings = $produp - $minimum;
							$rs_promoent_details = $sp->spInsertPromoEntitlementDetails($database, $entitlementID, $prodid, 1, $minimum, $savings, $pmg);
							if (!$rs_promoent_details)
							{
								throw new Exception("An error occurred, please contact your system administrator.");
							}
						}
					}				
				}
				
				$database->commitTransaction();
				redirect_to("index.php?pageid=65&promoID=$promoID");
			//}
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";			
		}
	}
	elseif (isset($_POST['btnDone']))
	{
		$msg = "Successfully created Step Level Promo.";
		redirect_to("index.php?pageid=64&msg=$msg");			
	}
?>
