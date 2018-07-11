<?php
	global $database;
	
	$prmSID = 0;
	$prmBuyINID = 0;
	$entType = 0;
	$entQty = 0;
	$prmBuyStartdate = "";
	$prmBuyEnddate = "";
	$inc = $_GET['inc'];
	$ctr = 0;
	$buycnt = 0;
	$date = time();
	$today2 = date("m/d/Y",$date);
	$errmsg = "";
	$update = 0;
	$totcntbuy = 0;
	$buycnt = 0;
	
	if(isset($_POST['btnRemove1'])){
		$database->execute("DELETE FROM `promoentitlementdetails` WHERE ID = ".$_POST['EID']."");
	}
	
	if(isset($_POST['btn_remove'])){
		$database->execute("DELETE FROM `promobuyin` WHERE ID = ".$_POST['BuyinID']."");
	}
	
	
	if (isset($_GET['fromremove']))
	{
		$fromremove = 1;
	}
	else
	{
		$fromremove = 0;		
	}
	
	if(isset($_GET['prmsid']) && !isset($_POST['btnRemove']) && !isset($_GET['fromremove']) && !isset($_POST['btnSave']) && !isset($_POST['cboRange']))
	{
		unset($_SESSION["buyin"]);		
	}
	
	if(isset($_GET['prmsid']))
	{
		//get promo header
		$rspromodet = $sp->spSelectPromoByID($database, $_GET['prmsid']);
		if($rspromodet->num_rows)
		{
			while($row = $rspromodet->fetch_object())
			{
				$promoID = $row->ID;
				$promocode = $row->Code;
				$promodesc = $row->Description;
				$tmpsdate = strtotime($row->StartDate);
				$today = date("m/d/Y",$tmpsdate);
				$tmpedate = strtotime($row->EndDate);
				$end = date("m/d/Y",$tmpedate);
				$ovrlaytype = $row->OverlayType;
				$qq = $database->execute("select IsForNewRecruit from promo where ID = ".$promoID);
				$IsForNewRecruit = $qq->fetch_object()->IsForNewRecruit;
				$IsAnyPromo = $row->IsAnyPromo;
				$TotalPrice = number_format($row->TotalPrice, 2, '.', '');
				
				if($ovrlaytype==1 ||$ovrlaytype==2){
					if($row->OverlayQty == 0){
					$overlayQty = "";
					}else{
						$overlayQty = $row->OverlayQty;
					}
				}else{
					if($row->OverlayAmt == 0){
						$overlayQty = "";
					}else{
						$overlayQty = number_format($row->OverlayAmt,2,'.','');
					}
				}
				
                                if($row->OverlayApplyAsDiscount==1){
                                     $OverlayApplyAsDiscount = "checked='checked'";
                                }else{
                                     $OverlayApplyAsDiscount = "";
                                }
                                
				$overlayAmt = $row->OverlayAmt;
				$prmPplan = $row->IsPlusPlan;
				
				$PageNum = explode("-",$row->PageNum);
				$fPage = $PageNum[0];
				$ePage = $PageNum[1];
				$OverlayIsRegular = $row->OverlayIsRegular;
			}
			$rspromodet->close();
		}
		$promobuyin = $database->execute("select * from promobuyin where PromoID = $promoID and LevelType = 0");
		if($promobuyin->num_rows){
			if($row = $promobuyin->fetch_object()){
				$PurchaseRequirementType = $row->PurchaseRequirementType;
			}
		}
		//$rs_pmg = $sp->spSelectPMG($database);
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
			$inc = $_GET['inc'];
		}
		else
		{
			$levelid = 0;		
		}
		
		$rsprodselection = $sp->spSelectProductListByLevelID($database, $levelid, "");
		
		//get promobuyin
		$rspromobuyin_count = $sp->spSelectPromoBuyInCountByPromoID($database, $_GET['prmsid']);
		if($rspromobuyin_count->num_rows)
		{
			while($row = $rspromobuyin_count->fetch_object())
			{
				$tmpcnt = $row->Cnt;
				$prtype = $row->Type;	
				$prmBuyINID = $row->ID;		
			}
			$rspromobuyin_count->close();
		}
		
		$rspromentitlementType = $sp->spSelectPromoEntitlementByPromoBuyInID($database, $prmBuyINID);
		if($rspromentitlementType->num_rows)
		{
			while($row = $rspromentitlementType->fetch_object())
			{
				$entType = $row->Type;
				$entQty = $row->Qty;	
					
			}
			$rspromentitlementType->close();
		}
		
		//$rspromobuyin = $sp->spSelectPromoBuyInByPromoID($database, $_GET['prmsid']);
		
		
		$rspromobuyin = $database->execute("select pbi.BrochurePageFrom, pbi.BrohurePageTo, pbi.ParentPromoBuyinID, pbi.ID PromoBuyinID, pbi.PromoID, pbi.Type, pr.Code PromoCode, DATE_FORMAT(pbi.StartDate, '%m/%d/%Y') StartDate,
								DATE_FORMAT(pbi.EndDate, '%m/%d/%Y') EndDate,
								case
										when isnull(pbi.MinQty) then 2
										else 1
								end Criteria,
								case
										when isnull(pbi.MinQty) then pbi.MinAmt
										else pbi.MinQty
								end Minimum,
								pbi.MinQty,pbi.MinAmt,
								case
								when isnull(pbi.MaxQty) then pbi.MaxAmt
								else pbi.MaxQty
								end Maximum,
								pbi.PurchaseRequirementType,
								p.Code ProdCode,
								p.Name ProdName,
								pmg.Code pmgCode,
								p.ID prodID,
								pbi.ProductLevelID plid,
                                                                ct.Name CollateralType
							from `promobuyin` pbi
								left join `product` p on p.ID = pbi.ProductID
								left join promo pr on pr.ID = pbi.PromoWithinPromoID
								left join productpricing pp on pp.ProductID = p.ID
								left join tpi_pmg pmg on pmg.ID = pp.pmgid
                                                                left join collateraltype ct on pbi.CollateralType = ct.ID
							where pbi.PromoID = ".$_GET['prmsid']." and pbi.ParentPromoBuyinID != 0");
			
							
		$rspromobuyin_ent = $sp->spSelectPromoBuyInByPromoIDOverlay($database, $_GET['prmsid']);
		
		$totcntbuy = $rspromobuyin->num_rows;
		$buycnt = $rspromobuyin->num_rows;
		
		//get total count of ENT upon loading
		$rspromobuyin_ent2 = $sp->spSelectPromoBuyInByPromoIDOverlay($database, $_GET['prmsid']);
		if ($rspromobuyin_ent2->num_rows)
		{
		 	while($row2 = $rspromobuyin_ent2->fetch_object())
			{
		 		$ctr += 1;
			  	
			  	//get promoentitlementid
			  	$rspromentitlement2 = "rspromentitlement".$ctr;
			  	$rspromentitlement2 = $sp->spSelectPromoEntitlementByPromoBuyInID($database, $row2->PromoBuyinID);
			  	if ($rspromentitlement2->num_rows)
				{
					$index = 0;
					while($rowEnt2 = $rspromentitlement2->fetch_object())
					{
				 		$index += 1;
					 	//get promoentitlementdetails
					 	$rspromentitlement_details2 = "rspromentitlement_details".$index;
					 	$rspromentitlement_details2 = $sp->spSelectPromoEntitlementDetailsByPromoEntitlementID($database, $rowEnt2->ID);
					 	$totcntent = $rspromentitlement_details2->num_rows;
					}
				}
			}
		}
		
		$rs_promoAvailment = $sp->spSelectPromoAvailByPromoID($database, $_GET['prmsid']);
	    $rs_gsutype = $sp->spSelectGSUType($database);
	}
	
	if(isset($_SESSION['buyin']))	
	{
		$buycnt = 0;
		if (sizeof($_SESSION['buyin']))
		{
			$buyin_list2 = $_SESSION["buyin"];
			for ($i=0, $n=sizeof($buyin_list2); $i < $n; $i++ ) 
		    {
		    	$buycnt++;
		    }			    		    
		}
	}
	else
	{
		if(isset($_POST['cboRange']))
		{
		}
		else
		{
			//load saved data and add to session
			//if ($rspromobuyin->num_rows)
			//{
			//	$prmBuyStartdate = "";
			//	$prmBuyEnddate = "";
			//	$totcntbuy = $rspromobuyin->num_rows;
			//	$buycnt = $rspromobuyin->num_rows;
	        //
			//	while($row = $rspromobuyin->fetch_object())
		    //	{
			//		$preqttype = $row->PurchaseRequirementType;
			//		$range = $row->plid;
			//		$prodid = $row->prodID;
			//		$proddesc = $row->ProdName;
			//		$breqid = 0;
			//		$pmgCode = $row->pmgCode;
			//			
			//		if($row->Criteria == 1)
			//		{
			//			$criteria = 1;
			//			$minimum = $row->Minimum;
			//		}
			//		else
			//		{
			//			$criteria = 2;
			//			$minimum = number_format($row->Minimum, 2, '.', '');
			//		}
			//		
			//		//$minimum = number_format($row->Minimum, 2, '.', '');
			//		$maximum = number_format($row->Maximum, 2, '.', '');
			//						
			//	 	if ($row->StartDate == "00/00/0000")
			//		{
			//			$prmBuyStartdate = "&nbsp;";								 	
			//		}
			//	 	else
			//	 	{
			//			$prmBuyStartdate = $row->StartDate;
			//	 	}
			//						 
			//		if ($row->EndDate == "00/00/0000")
			//	 	{
			//	 		$prmBuyEnddate = "&nbsp;";								 	
			//     	}
			//		else
			//	 	{
			//    		$prmBuyEnddate = $row->EndDate;
			//	 	}
			//						 
			//	 	$_SESSION['buyin'][] = array('PReqType'=>$preqttype, 'Range'=>$range, 'ProdID'=>$prodid, 'ProdDesc'=>$proddesc, 'BReqID'=>$breqid, 'Criteria'=>$criteria, 'MinQty'=>$minimum, 'MinAmt'=>$minimum, 'StartDate'=>$prmBuyStartdate, 'EndDate'=>$prmBuyEnddate,'PMGCode'=>$pmgCode);
			//	}
			//}
		}
	}
	
	if (isset($_POST['btnDelete']))
	{
		try
		{
			$linked = $sp->spSelectLinkedBrochureProductByPromoID($database, $_GET['prmsid']);
			
			if($_GET['inc']== 1){
				//redirect to set overlay page
				$incn = 2;
			}
			else{
				//redirect to incentives page
				$incn = 1;
			}
			
			if($linked->num_rows)
			{
				echo"<script language=JavaScript>
						opener.location.href = '../../index.php?pageid=178&inc=2&errmsg=Promo cannot be deleted because it is already linked to a Brochure or it has started.';
						window.close();
					</script>";					
			}
			else
			{
				$database->beginTransaction();
				//$affected_rows = $sp->spDeletePromo($database, $_GET['prmsid']);
				$database->execute("SET FOREIGN_KEY_CHECKS = 0");
				
				$database->execute(" DELETE FROM promoentitlementdetails WHERE promoEntitlementID = (
									 SELECT ID FROM promoentitlement WHERE PromoBuyinID = 
									 (SELECT ID FROM promobuyin WHERE 
									 PromoID = (SELECT ID FROM promo WHERE ID = ".$_GET['prmsid'].") AND ParentPromoBuyinID = 0))");
				
				$database->execute(" DELETE FROM promoentitlement WHERE PromoBuyinID = 
									(SELECT ID FROM promobuyin WHERE 
									 PromoID = (SELECT ID FROM promo WHERE ID = ".$_GET['prmsid'].") AND ParentPromoBuyinID = 0)");
				
				$database->execute("DELETE from promobuyin where 
									PromoID = (select ID from promo where ID = ".$_GET['prmsid'].")");
									
				$database->execute("DELETE FROM promo WHERE ID = ".$_GET['prmsid']);
				
				$database->commitTransaction();
				echo"<script language=JavaScript>
							opener.location.href = '../../index.php?pageid=178&inc=1&msg=Successfully deleted promo.';
							window.close();
					</script>";		
			}
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage()."\n";	
		}
	}
				
	if (isset($_POST['btnAdd']))
	{	
		$tmpsdate = strtotime($_POST['txtStartDate']);
		$startdate = date("Y-m-d", $tmpsdate);
		$tmpedate = strtotime($_POST['txtEndDate']);
		$enddate = date("Y-m-d", $tmpedate);
		
		//purchase reqt type
		if(!isset($_POST['cboPReqtType']))
		{
			$preqttype = $prtype;			
		}
		else
		{
			$preqttype = $_POST['cboPReqtType'];
		}
		
		if(isset($_SESSION['buyin']))	
	 	{
			if (sizeof($_SESSION['buyin']))
		 	{
				$buyin_list = $_SESSION["buyin"];
			
				for ($i=0, $n=sizeof($buyin_list); $i < $n; $i++ ) 
				{
			  		$preqttype = $buyin_list[$i]['PReqType'];
				}
		 	}
		}
		
		//buy-in requirement
		$range = $_POST["cboRange"];
		$prodid = $_POST["hProdID_criteria"];
		$breqid = $_POST['rdoBReqt'];
		if ($breqid == 2)
		{
			$criteria = $_POST["cboPHCriteria"];
			$minimum = $_POST["txtPHMinimum"];
			// $br_startdate = 'null';			
			// $br_enddate = 'null';
			$br_startdate = $startdate;
			$br_enddate = $enddate;
			if ($criteria == 1)
			{
				//$min_qty = $_POST["txtPHMinimum"];
				$min_qty = 1;
				$min_amt = 'null';				
			}
			else
			{
				$min_qty = 'null';
				$min_amt = $_POST["txtPHMinimum"];
			}
		}
		else
		{
			$criteria = $_POST["cboCriteria"];
			$minimum = $_POST["txtMinimum"];
			$tmpbsdate = strtotime($_POST['txtSetStartDate']);
			$br_startdate = date("m/d/Y", $tmpbsdate);
			$tmpbedate = strtotime($_POST['txtSetEndDate']);
			$br_enddate = date("m/d/Y", $tmpbedate);
			if ($criteria == 1)
			{
				$min_qty = $_POST["txtMinimum"];
				$min_amt = 'null';				
			}
			else
			{
				$min_qty = 'null';
				$min_amt = $_POST["txtMinimum"];
			}				
		}
		
		//get product description
		$rs_prod = $sp->spSelectProductbyID($database, $prodid, 1);
		if($rs_prod->num_rows)
		{
			while($row = $rs_prod->fetch_object())
			{
				$proddesc = $row->Name;
				$pmgCode = $row->pmgCode;
			}
		}
		
		//add to session
		$_SESSION['buyin'][] = array('PReqType'=>$preqttype, 'Range'=>$range, 'ProdID'=>$prodid, 'ProdDesc'=>$proddesc, 'BReqID'=>$breqid, 'Criteria'=>$criteria, 'MinQty'=>$min_qty, 'MinAmt'=>$min_amt, 'StartDate'=>$br_startdate, 'EndDate'=>$br_enddate,'PMGCode'=>$pmgCode);
	}
	
	if (isset($_POST['btnRemove']))
	{
		if(isset($_POST["chkSelect"]))	
		{
			$tmpbuyin = $_SESSION["buyin"];
			$x = sizeof($tmpbuyin);
		    $pricing = $_POST["chkSelect"];
		    
			foreach ($pricing as $key=>$ID) 
			{
				for ($i = 0; $i < $x; $i++)
				{
					if (isset($tmpbuyin[$i]['ProdID']))
					{		
						if (($_POST['hprodIDID'.$ID] == $tmpbuyin[$i]['ProdID']))
						{									
							unset($tmpbuyin[$i]);
							break;	
						}
					}
				}
			}
			
			unset($_SESSION["buyin"]);
			for ($i = 0; $i < $x; $i++)
			{
				if (isset($tmpbuyin[$i]['ProdID']))
				{	 
					$_SESSION['buyin'][] = array('PReqType'=>$tmpbuyin[$i]['PReqType'], 'Range'=>$tmpbuyin[$i]['Range'], 'ProdID'=>$tmpbuyin[$i]['ProdID'], 'ProdDesc'=>$tmpbuyin[$i]['ProdDesc'], 'BReqID'=>$tmpbuyin[$i]['BReqID'], 'Criteria'=>$tmpbuyin[$i]['Criteria'], 'MinQty'=>$tmpbuyin[$i]['MinQty'], 'MinAmt'=>$tmpbuyin[$i]['MinAmt'], 'StartDate'=>$tmpbuyin[$i]['StartDate'], 'EndDate'=>$tmpbuyin[$i]['EndDate'],'PMGCode'=>$tmpbuyin[$i]['PMGCode']);
				}	
			}

			$buycnt = 0;
			if(isset($_SESSION['buyin']))	
			{
				if (sizeof($_SESSION['buyin']))
				{
					$buyin_list2 = $_SESSION["buyin"];
					for ($i=0, $n=sizeof($buyin_list2); $i < $n; $i++ ) 
				    {
				    	$buycnt++;
				    }			    		    
				}
				
				if($_GET['inc']== 0)
				{
					$incn = 0;
				}
				else
				{
					$incn = 1;
				}
				$prmID = $_GET['prmsid'];	
				redirect_to("./promo_setpromoDetails.php?prmsid=$prmID&inc=$incn&fromremove=1");
			}
		}		
	}	
	
	if (isset($_POST['btnSave']))
	{
               
		try
		{
			$database->beginTransaction();
			$prodid_buyin = "";
			$prodid_entitlement = "";
			$prmID = $_GET['prmsid'];
			$promocode = htmlentities(addslashes($_POST['txtCode']));				
			$promodesc = htmlentities(addslashes($_POST['txtDescription']));
			$tmpsdate = strtotime($_POST['txtStartDate']);
			$startdate = date("Y-m-d", $tmpsdate);
			$tmpedate = strtotime($_POST['txtEndDate']);
			$enddate = date("Y-m-d", $tmpedate);
			$OverlayType = $_POST['rdoBReqt'];
			
			$IsAnyPromo = (isset($_POST['IsAnyPromo'])) ? $_POST['IsAnyPromo'] : 0;
			$TotalPrice = (isset($_POST['TotalPrice'])) ? $_POST['TotalPrice'] : 0;
			
			if(isset($_POST['chkPlusPlan'])){
				$isplusplan = 1;				
			}else{
				$isplusplan = 0;
			}
			$pagenum = $_POST['bpage']."-". $_POST['epage'];
			$PurchaseRequirementType = $_POST['cboPReqtType'];
			$sType = $_POST['cboType'];
			if(isset($_POST['Sselection'])){
				$Sselection = $_POST['Sselection'];
			}else{
				$Sselection = 1;
			}
			
			if(isset($_POST['selection_no'])){
				
				$OverlayQty = $_POST['selection_no'];
			}else{
				$OverlayQty = 0;
			}
			
			if(isset($_POST['selection_no'])){
				if($_POST['rdoBReqt'] == 1 ){	
					$column = "OverlayAmt=0,OverlayQty=0";
				}else if($_POST['rdoBReqt'] == 2){
					$column = "OverlayQty=".$_POST['selection_no'].",OverlayAmt=0";
					//$overlay_header_selection = $_POST['selection_no'].",0";
				}else{
					$column = "OverlayAmt=".$_POST['selection_no'].",OverlayQty=0";
	
				}
			}else{
				$column = "OverlayAmt=0,OverlayQty=0";
			}
			
			$IsNewRecruit=$_POST['IsNewRecruit'];
			
			
			
			
                        if(isset($_POST['chkApplyAsDiscount'])){
                              $chkApplyAsDiscount = 1;     
                        }else{
                             $chkApplyAsDiscount = 0;
                        }
                        $isRegular = $_POST['isRegular'];
			
			$database->execute("UPDATE promo SET 
									IsForNewRecruit=".$IsNewRecruit.",
									OverlayApplyAsDiscount=".$chkApplyAsDiscount.", 
									OverlayIsRegular = ".$isRegular.", 
									".$column.", 
									Description = '".$promodesc."', 
									OverlayType =".$OverlayType.", 
									StartDate = '".$startdate."', 
									EndDate = '".$enddate."', 
									IsPlusPlan = ".$isplusplan.", 
									PageNum ='".$pagenum."',
									IsAnyPromo = $IsAnyPromo,
									TotalPrice = $TotalPrice
								WHERE ID = ".$prmID."");
			$database->execute("Update promobuyin SET PurchaseRequirementType = ".$PurchaseRequirementType." where PromoID = ".$prmID."");
			
			$q = $database->execute("select * from promoavailment where PromoID = ".$prmID);		
			if($q->num_rows){
				//insert to promoavailment table
				$rs_gsutype_save = $sp->spSelectGSUType($database);
				if($rs_gsutype_save->num_rows){
						while($row = $rs_gsutype_save->fetch_object()){
							if(isset($_POST["txtMaxAvail{$row->ID}"])){
								if($_POST["txtMaxAvail{$row->ID}"] != ""){
									$database->execute('UPDATE promoavailment set MaxAvailment = '.$_POST["txtMaxAvail{$row->ID}"].', Changed = 1 where PromoID = '.$prmID.' AND GSUTypeID = '.$row->ID);
								}
							}
						}
					$rs_gsutype_save->close();	
				}
			}else{
				//insert to promoavailment table
				$rs_gsutype_save = $sp->spSelectGSUType($database);
				if($rs_gsutype_save->num_rows){
						while($row = $rs_gsutype_save->fetch_object()){
							if(isset($_POST["txtMaxAvail{$row->ID}"])){
								if($_POST["txtMaxAvail{$row->ID}"] != ""){
								
										$rs_promoavail = $sp->spInsertPromoAvailment($database, $prmID, $row->ID, $_POST["txtMaxAvail{$row->ID}"]);
										if (!$rs_promoavail){
											throw new Exception("An error occurred, please contact your system administrator.");
										}
									}
								}
																
								}						
							}					
				$rs_gsutype_save->close();
			}			
			
			$buyinID = $database->execute("SELECT * FROM promobuyin where PromoID =".$prmID." and LevelType = 0");
			if($buyinID->num_rows){
				while($row = $buyinID->fetch_object()){
					$XBuyinID = $row->ID;
				}
			}else{
				$XBuyinID = 0;
			}
			
			if($sType == 1){ //set..
				$entitlementselection = 1;
			}else{
				$entitlementselection = $_POST['txtTypeQty'];
			}
			$database->execute("UPDATE promoentitlement SET Type = ".$sType.", Qty = ".$entitlementselection." where PromoBuyinID = ".$XBuyinID);
			$hEntitlementCnt = $_POST['hEntitlementCnt'];
			for($i = 1; $i  <= $hEntitlementCnt; $i++){
				
				$EProdCode 	= $_POST["txtEProdCode{$i}"];
				$EProdID 	= $_POST["hEProdID{$i}"];
				$ECriteria 	= $_POST["cboECriteria{$i}"];
				$EUnitPrice = $_POST["hEUnitPrice{$i}"];
				$EQty 		= $_POST["txtEQty{$i}"];
				$EPMG 		= $_POST["cboEPMG{$i}"];
				$entitID	= $_POST["entitID{$i}"];
				if($ECriteria == 1){
					$database->execute("UPDATE promoentitlementdetails SET ProductID = ".$EProdID.", Quantity = ".$EQty.", EffectivePrice = 0, Savings = ".$EUnitPrice.", 
																		   PMGID = ".$EPMG." WHERE ID = ".$entitID."");
				}else{
					$savings = $EUnitPrice - $EQty;
					$database->execute("UPDATE promoentitlementdetails SET ProductID = ".$EProdID.", Quantity = 1, EffectivePrice = ".$EQty.", 
																		   Savings   = ".$savings.", PMGID 	  = ".$EPMG." 
																		   WHERE ID  = ".$entitID."");
				}
				
			}
			$database->commitTransaction();
				echo"<script language=JavaScript>
						opener.location.href = '../../index.php?pageid=178&inc=1&msg=Successfully Updated promo.';
						window.close();
					</script>";				
			
		}
		
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";			
		}
	}

?>