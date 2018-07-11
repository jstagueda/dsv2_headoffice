<?php
	//increase max execution time
	ini_set("max_execution_time", 1000);
	//ini_set('display_errors',0);
	global $database;
		
	$errmsg = "";
	$date = time();
	$today = date("m/d/Y",$date);
	$tmpdate = strtotime(date("Y-m-d", strtotime($today)) . " +1 month");
	$end = date("m/d/Y",$tmpdate);

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
			if ($index == $rs_pmg2->num_rows){
				$pmg_id = $pmg_id.$row->ID;
				$pmg_code = $pmg_code."'".$row->Code."'";
			}else{
				$pmg_id = $pmg_id.$row->ID.", ";				
				$pmg_code = $pmg_code."'".$row->Code."'".", ";
			}	
		}
		$rs_pmg2->close();
	}
	
        
	if (isset($_POST['btnCancel'])){
		redirect_to("index.php?pageid=60");			
	}else if (isset($_POST['btnSaves'])){
		//echo "<pre>";
		//	print_r($_POST);
		//echo "</pre>";
		//die();
		try
		{
			print_r($_POST);
			die();
			$database->beginTransaction();	
			$buyinprod = $_POST['hBuyInIndex'] + 1;		
			$entprod = $_POST['hEntIndex'];
			$promocode = htmlentities(addslashes($_POST['txtCode']));				
			$promodesc = htmlentities(addslashes($_POST['txtDescription']));
			$tmpsdate = strtotime($_POST['txtStartDate']);
			$startdate = date("Y-m-d", $tmpsdate);
			$tmpedate = strtotime($_POST['txtEndDate']);
			$enddate = date("Y-m-d", $tmpedate);
			if(isset($_POST['chkPlusPlan'])){
				$isplusplan = $_POST['chkPlusPlan'];				
			}else{
				$isplusplan = 0;
			}
			$pagenum = $_POST['bpage']."-". $_POST['epage'];
			$IsForNewRecruit = $_POST['IsNewRecruit'];

			
			$database->execute("INSERT INTO `promo` (Code, Description, StartDate, EndDate, PromoTypeID, StatusID, CreatedBy, EnrollmentDate, Changed, IsPlusPlan, PageNum, IsForNewRecruit)
								VALUES ('$promocode', '$promodesc', '$startdate', '$enddate', 1, 6,$session->emp_id, NOW(), 1, $isplusplan, '$pagenum', $IsForNewRecruit)");
								
			$rs_promoid = $database->execute("SELECT LAST_INSERT_ID() ID");
				if($rs_promoid->num_rows){
					while($row = $rs_promoid->fetch_object()){
						$promoID = $row->ID;
					}
				}
				
				
				//link promo to branches
				$rs_branch = $sp->spSelectBranch($database, -1, '');
				if($rs_branch->num_rows){
					while($row_branch = $rs_branch->fetch_object()){											
						$sp->spInsertPromoBranchLinking($database, $promoID, $row_branch->ID); 
					}
					$rs_branch->close();	
				}
				
			  	//insert to promoavailment table
				if($rs_gsutype_save->num_rows){
					while($row = $rs_gsutype_save->fetch_object()){
						if(isset($_POST["txtMaxAvail{$row->ID}"])){
							if($_POST["txtMaxAvail{$row->ID}"] != ""){
								$rs_promoavail = $sp->spInsertPromoAvailment($database, $promoID, $row->ID, $_POST["txtMaxAvail{$row->ID}"]);
								if (!$rs_promoavail){
									throw new Exception("An error occurred, please contact your system administrator.");
								}							
							}						
						}					
					}
					$rs_gsutype_save->close();
				}
			    //$i = 1;
				//$y = 1;
				//$itemcount = 1;
				
				//new code 8/1/2013
				for($i = 1; $buyinprod > $i; $i++){
						$prodid 	= $_POST["hProdID{$i}"];
                        $prodcode 	= $_POST["txtProdCode{$i}"];
					 	$criteria 	= $_POST["cboCriteria{$i}"];
					 	$minimum 	= $_POST["txtQty{$i}"];
						$pmg1 		= $_POST["txtbPmg{$i}"]; 
						$criteria1  = $_POST["cboECriteria{$i}"];
						$minimum1 	= $_POST["txtEQty{$i}"];
						$prodcode1 	= $_POST["txtProdCode{$i}"];
						
					if ($prodcode != ""){
						$q = $database->execute("SELECT UnitPrice FROM productpricing where ProductID=".$prodid);
						if($q->num_rows){
							while($r=$q->fetch_object()){
								$produp1=$r->UnitPrice;
							}
						}
						
						if ($criteria == 1){
							$rs_promobuyin_child = $sp->spInsertPromoBuyIn($database, $promoID, 'null', $criteria, $minimum, 'null', 'null', 'null', 1, $prodid, 'null', $startdate, $enddate, 1);
							if (!$rs_promobuyin_child){
								throw new Exception("An error occurred, please contact your system administrator.");
							}	
							
							if($rs_promobuyin_child->num_rows){
								while($row = $rs_promobuyin_child->fetch_object()){
									$buyinparentID = $row->ID;
								}
							}
							
							//insert to promoentitlement
							$rs_promoid = $sp->spInsertPromoEntitlement($database, $buyinparentID, $criteria1, 1);
							if (!$rs_promoid){
								throw new Exception("An error occurred, please contact your system administrator.");
							}
							
							if($rs_promoid->num_rows){
								while($row = $rs_promoid->fetch_object()){
									$entitlementID = $row->ID;
								}
							}
		
							if ($prodcode1 != ""){
								if($criteria1 != 1){
									$savings = $produp1 - $minimum1;
									$rs_promoent_details = $sp->spInsertPromoEntitlementDetails($database, $entitlementID, $prodid, 1, $minimum1, $savings, $pmg1);
									if (!$rs_promoent_details){
										throw new Exception("An error occurred, please contact your system administrator.");
									}
								}else{
									$rs_promoent_details = $sp->spInsertPromoEntitlementDetails($database, $entitlementID, $prodid, $minimum1, 0, $produp1, $pmg1);
									if (!$rs_promoent_details)
									{
										throw new Exception("An error occurred, please contact your system administrator.");
									}
								}
							}
						}else{
							$rs_promobuyin_child = $sp->spInsertPromoBuyIn($database, $promoID, 'null', $criteria, 'null', $minimum, 'null', 'null', 3, $prodid, 'null', 'null', 'null', 1);
						
							if($rs_promobuyin_child->num_rows){
								while($row = $rs_promobuyin_child->fetch_object()){
									$buyinparentID = $row->ID;
								}
							}
							
							//insert to promoentitlement
							$rs_promoid = $sp->spInsertPromoEntitlement($database, $buyinparentID,  $criteria1, 1);
							if (!$rs_promoid){
								throw new Exception("An error occurred, please contact your system administrator.");
							}
							
							if($rs_promoid->num_rows){
								while($row = $rs_promoid->fetch_object()){
									$entitlementID = $row->ID;
								}
							}
	
							if ($prodcode1 != ""){
								if($criteria1 != 1){
									$savings = $produp1 - $minimum1;
	
									$rs_promoent_details = $sp->spInsertPromoEntitlementDetails($database, $entitlementID, $prodid1, 1, $minimum1, $savings, $pmg1);
									if (!$rs_promoent_details)
									{
										throw new Exception("An error occurred, please contact your system administrator.");
									}
								}else{
									$rs_promoent_details = $sp->spInsertPromoEntitlementDetails($database, $entitlementID, $prodid1, $minimum1, 0, $produp1, $pmg1);
									if (!$rs_promoent_details){
										throw new Exception("An error occurred, please contact your system administrator.");
									}
								}
							}				
						}
					}
				}
				
		$database->commitTransaction();
		redirect_to("index.php?pageid=60");
		
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
	
		
	//check if promo code already exist
	$rs_code_exist = $sp->spCheckPromoIfExists($database, $promocode);
	if($rs_code_exist->num_rows){
				$errmsg = "Existing Promo code.";
				redirect_to("index.php?pageid=61&error=$errmsg");				
		}
		else{

			if(empty($promocode)){
			
				$errmsg = "Please enter Promo code.";
				redirect_to("index.php?pageid=61&error=$errmsg");				
	
			}
			else{
				//$errmsg = "Promo code does not exists.";				
				redirect_to("index.php?pageid=61.1&code=$promocode");					
		
			}
		}
	}

?>
