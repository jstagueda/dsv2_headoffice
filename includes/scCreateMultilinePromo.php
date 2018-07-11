<?php

	global $database;

	$errmsg = "";	
	$date  = time();
	$today = date("m/d/Y",$date);
	$tmpdate = strtotime(date("Y-m-d", strtotime($today)) . " +1 month");
	$end = date("m/d/Y",$tmpdate);

	//get gsu type
	$rs_gsutype = $sp->spSelectGSUType($database);
	$rs_gsutype_save = $sp->spSelectGSUType($database);
	
	//get pmg
	$rs_pmg  = $sp->spSelectPMG($database);
	$rs_pmg2 = $sp->spSelectPMG($database);
	
	//for dynamic combo box
	$index  = 0;
	$pmg_id = "";
	$pmg_code = "";
	if($rs_pmg2->num_rows)
	{
		while($row = $rs_pmg2->fetch_object())
		{
			$index += 1;
			if ($index == $rs_pmg2->num_rows)
			{
				$pmg_id   = $pmg_id.$row->ID;
				$pmg_code = $pmg_code."'".$row->Code."'";
			}
			else
			{
				$pmg_id   = $pmg_id.$row->ID.", ";				
				$pmg_code = $pmg_code."'".$row->Code."'".", ";
			}	
		}
		$rs_pmg2->close();
	}
	
	if (isset($_POST['btnCancel']))
	{
		redirect_to("index.php?pageid=62");			
	}
	else if (isset($_POST['btnSave']))
	{
		try
		{
			$database->beginTransaction();
				
			$buyinprod = $_POST['hBuyInCnt'];		
			$entprod = $_POST['hEntitlementCnt'];
			$promocode = htmlentities(addslashes($_POST['txtCode']));				
			$promodesc = htmlentities(addslashes($_POST['txtDescription']));
			$tmpsdate = strtotime($_POST['txtStartDate']);
			$startdate = date("Y-m-d", $tmpsdate);
			$tmpedate = strtotime($_POST['txtEndDate']);
			$enddate = date("Y-m-d", $tmpedate);
			$preqttype = $_POST['cboPReqtType'];
			$type = $_POST['cboType'];
			$IsForNewRecruit = $_POST['IsNewRecruit'];
			
			if(isset($_POST['txtTypeQty'])){
				$txtTypeQty = $_POST['txtTypeQty'];
			}else{
				$txtTypeQty = 0;
			}
			if(isset($_POST['chkPlusPlan']))
			{
				$isplusplan = $_POST['chkPlusPlan'];				
			}
			else
			{
				$isplusplan = 0;
			}

			if ($type == 2)
			{
				$typeqty = $_POST['txtTypeQty'];
			}
			else
			{
				$typeqty = 0;
			}
			$pagenum = $_POST['bpage']."-". $_POST['epage'];
			//echo $typeqty;
			//die();
			//check if promo code already exist
			$rs_code_exist = $sp->spCheckPromoIfExists($database, $promocode);
			if($rs_code_exist->num_rows)
			{
				$errmsg = "Promo code already exists.";
			}
			else
			{
				//insert to promo table
				//$rs_promoid = $sp->spInsertPromoHeader($database, $promocode, $promodesc, $startdate, $enddate, 2, $session->emp_id, $isplusplan);
				
				$database->execute("SET FOREIGN_KEY_CHECKS=0");
				
				$database->execute("INSERT INTO `promo` (Code, Description, StartDate, EndDate, PromoTypeID, StatusID, CreatedBy, EnrollmentDate, Changed, IsPlusPlan, PageNum,IsForNewRecruit)
								VALUES ('$promocode', '$promodesc', '$startdate', '$enddate', 2, 6,$session->emp_id, NOW(), 1, $isplusplan, '$pagenum',$IsForNewRecruit)");
				$rs_promoid = $database->execute("SELECT LAST_INSERT_ID() ID");
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
				
				//insert to promobuyin - parent
				
				$rs_promobuyin_parent = $sp->spInsertPromoBuyIn($database, $promoID, 'null', 1, 'null', 'null', 'null', 'null', 3, 'null', $preqttype, $startdate, $enddate, 0);
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
				

		
				//insert to promobuyin - child
				
				$buyin = $_POST["hBuyInIndex"];
				
				for ($i=1 ;$i <= $buyin ; $i++){
					
					$prodid = $_POST["hProdID{$i}"];
					$prodcode = $_POST["txtProdCode{$i}"];
					$criteria = $_POST["cboCriteria{$i}"];
					$minimum = $_POST["txtQty{$i}"];
					
					if ($prodcode != "")
					{
						if ($criteria == 1)
						{
							$rs_promobuyin_child = $sp->spInsertPromoBuyIn($database, $promoID, $buyinparentID, $criteria, $minimum, 'null', 'null', 'null', 3, $prodid, $preqttype, $startdate, $enddate, 1);
							if (!$rs_promobuyin_child)
							{
								throw new Exception("An error occurred, please contact your system administrator.");
							}
							//$rs_buyin = $sp->spInsertBuyIn($database, $promoID, $buyinID, $type, $minimum, 'null', 'null', 'null', 3, $prodid, $preqttype, 'null', 'null', $type, $typeqty, 1);					
						}
						else
						{
							$rs_promobuyin_child = $sp->spInsertPromoBuyIn($database, $promoID, $buyinparentID, $criteria, 'null', $minimum, 'null', 'null', 3, $prodid, $preqttype, $startdate, $enddate, 1);
							if (!$rs_promobuyin_child)
							{
								throw new Exception("An error occurred, please contact your system administrator.");
							}
							//$rs_buyin = $sp->spInsertBuyIn($database, $promoID, $buyinID, $type, $minimum, 'null', 'null', 'null', 3, $prodid, $preqttype, 'null', 'null', $type, $typeqty, 1);					
						}
					}	
				}
				
				/*
				$buyin = $_POST["chkSelect"];
				
				foreach ($buyin as $key=>$ID) 
				{
					$prodid = $_POST["hProdID{$ID}"];
					$prodcode = $_POST["txtProdCode{$ID}"];
					$criteria = $_POST["cboCriteria{$ID}"];
					$minimum = $_POST["txtQty{$ID}"];
		
			
					if ($prodcode != "")
					{
						if ($criteria == 1)
						{
							$rs_promobuyin_child = $sp->spInsertPromoBuyIn($database, $promoID, $buyinparentID, $criteria, $minimum, 'null', 'null', 'null', 3, $prodid, $preqttype, $startdate, $enddate, 1);
							if (!$rs_promobuyin_child)
							{
								throw new Exception("An error occurred, please contact your system administrator.");
							}
							//$rs_buyin = $sp->spInsertBuyIn($database, $promoID, $buyinID, $type, $minimum, 'null', 'null', 'null', 3, $prodid, $preqttype, 'null', 'null', $type, $typeqty, 1);					
						}
						else
						{
							$rs_promobuyin_child = $sp->spInsertPromoBuyIn($database, $promoID, $buyinparentID, $criteria, 'null', $minimum, 'null', 'null', 3, $prodid, $preqttype, $startdate, $enddate, 1);
							if (!$rs_promobuyin_child)
							{
								throw new Exception("An error occurred, please contact your system administrator.");
							}
							//$rs_buyin = $sp->spInsertBuyIn($database, $promoID, $buyinID, $type, $minimum, 'null', 'null', 'null', 3, $prodid, $preqttype, 'null', 'null', $type, $typeqty, 1);					
						}
					}		
				}*/
			
				
				if($type != 2){
					$entSet = $_POST["hEntIndex"];
					for ($i=1;$i <= $entSet ; $i++){
						$prodcode = $_POST["txtEProdCode{$i}"];
						if ($prodcode != ""){
						    $typeqty++;
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
				$entSet = $_POST["hEntIndex"];
					for ($i=1;$i <= $entSet ; $i++){
						$prodid = $_POST["hEProdID{$i}"];
						$produp = $_POST["hEUnitPrice{$i}"];
						$prodcode = $_POST["txtEProdCode{$i}"];
						$criteria = $_POST["cboECriteria{$i}"];
						$minimum = $_POST["txtEQty{$i}"];
						$pmg = $_POST["hEpmgid{$i}"];
						if ($pmg == ""){
							$c_pmg = "null";					
						}
						else{
							$c_pmg = $pmg; 					
						}
						if ($prodcode != ""){
						if ($criteria == 1){
							$rs_promoent_details = $sp->spInsertPromoEntitlementDetails($database, $entitlementID, $prodid, $minimum, 0, $produp, $c_pmg);
							if (!$rs_promoent_details){
								throw new Exception("An error occurred, please contact your system administrator.");
							}					
						}
						else{
							//check if product in entitlement list exists in buyin list
								$buyin2 = $_POST["chkSelect"];
								foreach ($buyin2 as $key=>$ID) 
								{
								$prodid_bi = $_POST["hProdID{$ID}"];
								$criteria_bi = $_POST["cboCriteria{$ID}"];
								$minimum_bi = $_POST["txtQty{$ID}"];
								
							}
							$savings = $produp - $minimum;
							$rs_promoent_details = $sp->spInsertPromoEntitlementDetails($database, $entitlementID, $prodid, 1, $minimum, $savings, $c_pmg);
							if (!$rs_promoent_details)
							{
								throw new Exception("An error occurred, please contact your system administrator.");
							}	
						}
						}
					}
					
				/*
				$ent = $_POST["chkSelectEnt"];
				foreach ($ent as $key=>$ID) 
				{
					$prodid = $_POST["hEProdID{$ID}"];
					$produp = $_POST["hEUnitPrice{$ID}"];
					$prodcode = $_POST["txtEProdCode{$ID}"];
					$criteria = $_POST["cboECriteria{$ID}"];
					$minimum = $_POST["txtEQty{$ID}"];
					$pmg = $_POST["hEpmgid{$ID}"];
					//$pmg = 1;
					
					if ($pmg == "")
					{
						$c_pmg = "null";					
					}
					else
					{
						$c_pmg = $pmg; 					
					}
					
		
					if ($prodcode != "")
					{
						if ($criteria == 1)
						{
							$rs_promoent_details = $sp->spInsertPromoEntitlementDetails($database, $entitlementID, $prodid, $minimum, 0, $produp, $c_pmg);
							if (!$rs_promoent_details)
							{
								throw new Exception("An error occurred, please contact your system administrator.");
							}					
						}
						else
						{
							//check if product in entitlement list exists in buyin list
								$buyin2 = $_POST["chkSelect"];
								foreach ($buyin2 as $key=>$ID) 
								{
								$prodid_bi = $_POST["hProdID{$ID}"];
								$criteria_bi = $_POST["cboCriteria{$ID}"];
								$minimum_bi = $_POST["txtQty{$ID}"];
								
								/*if($prodid == $prodid_bi)
								{
									if ($criteria_bi == 1)
									{
										$minqty = $minimum_bi;
										break;									
									}
									else
									{
										$minqty = 1;
										break;
									}							
								}
								else
								{
									$minqty = 1;							
								}
							}
							$savings = $produp - $minimum;
							$rs_promoent_details = $sp->spInsertPromoEntitlementDetails($database, $entitlementID, $prodid, 1, $minimum, $savings, $c_pmg);
							if (!$rs_promoent_details)
							{
								throw new Exception("An error occurred, please contact your system administrator.");
							}	
						}
					}				
				}
				*/
				$database->commitTransaction();
				redirect_to("index.php?pageid=62");					
			}
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
		}
	}


	
   if ((isset($_POST['btnVerify'])))
	
	{
		$database->beginTransaction();			
		$promocode = htmlentities(addslashes($_POST['txtCode']));
		$minimum = htmlentities(addslashes($_POST["txtQty1"]));
		
	//check if promo code already exist
	$rs_code_exist = $sp->spCheckPromoIfExists($database, $promocode);
	if($rs_code_exist->num_rows){
				$errmsg = "Existing Promo code.";
				redirect_to("index.php?pageid=63&error=$errmsg");				
		}
		else{

			if(empty($promocode)){
			
				$errmsg = "Please enter Promo code.";
				redirect_to("index.php?pageid=63&error=$errmsg");				
	
			}
			else{
				//$errmsg = "Promo code does not exists.";				
				redirect_to("index.php?pageid=63.1&code=$promocode");					
		
			}
		}
	}

	   // if ((isset($_POST['txtQty1'])))
	   		// $minimum = htmlentities(addslashes($_POST["txtQty1"]));
		// {
			// $errmsg = "Please enter Quantity / Amount Value.";
		// }
	
?>
